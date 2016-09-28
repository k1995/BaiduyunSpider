<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*****************************************************************
* Make the request
* This request object uses custom header/body reading functions
* so we can start processing responses on the fly - e.g. we don't
* need to wait till the whole file has downloaded before deciding
* if it needs parsing or can be sent out unchanged.
******************************************************************/

class Request {

	# Response status code
	public $status = 0;

	# Headers received and read by our callback
	public $headers = array();

	# Returned data (if saved)
	public $return;

	# Reason for aborting transfer (or empty to continue downloading)
	public $abort;

	# The error (if any) returned by curl_error()
	public $error;

	# Type of resource downloaded [html, js, css] or empty if no parsing needed
	public $parseType;

	# Automatically detect(ed) content type?
	public $sniff = false;

	# Forward cookies or not
	private $forwardCookies = false;

	# Limit filesize?
	private $limitFilesize = 0;

	# Speed limit (bytes per second)
	private $speedLimit = 0;

	# URL array split into pieces
	private $URL;

	# = $options from the global scope
	private $browsingOptions;

	# Options to pass to cURL
	private $curlOptions;


	# Constructor - takes the parameters and saves them
	public function __construct($curlOptions) {

		global $options, $CONFIG;

		# Set our reading callbacks
		$curlOptions[CURLOPT_HEADERFUNCTION] = array(&$this, 'readHeader');
		$curlOptions[CURLOPT_WRITEFUNCTION] = array(&$this, 'readBody');

		# Determine whether or not to forward cookies
		if ( $options['allowCookies'] && ! $CONFIG['cookies_on_server'] ) {
			$this->forwardCookies = $CONFIG['encode_cookies'] ? 'encode' : 'normal';
		}

		# Determine a filesize limit
		if ( $CONFIG['max_filesize'] ) {
			$this->limitFilesize = $CONFIG['max_filesize'];
		}

		# Determine speed limit
		if ( $CONFIG['download_speed_limit'] ) {
			$this->speedLimit = $CONFIG['download_speed_limit'];
		}

		# Set options
		$this->browsingOptions = $options;
		$this->curlOptions = $curlOptions;

		# Extend the PHP timeout
		set_time_limit($CONFIG['transfer_timeout']);

		# Record debug information
		if ( DEBUG_MODE ) {
			$this->cookiesSent = isset($curlOptions[CURLOPT_COOKIE]) ? $curlOptions[CURLOPT_COOKIE] : ( isset($curlOptions[CURLOPT_COOKIEFILE]) ? 'using cookie jar' : 'none');
			$this->postSent = isset($curlOptions[CURLOPT_POSTFIELDS]) ? $curlOptions[CURLOPT_POSTFIELDS] : '';
		}

	}

	# Make the request and return the downloaded file if parsing is needed
	public function go($URL) {

		# Save options
		$this->URL = $URL;

		# Get a cURL handle
		$ch = curl_init($this->URL['href']);

		# Set the options
		curl_setopt_array($ch, $this->curlOptions);

		# Make the request
		curl_exec($ch);

		# Save any errors (but not if we caused the error by aborting!)
		if ( ! $this->abort ) {
			$this->error = curl_error($ch);
		}

		# And close the curl handle
		curl_close($ch);

		# And return the document (will be empty if no parsing needed,
		# because everything else is outputted immediately)
		return $this->return;

	}


	/*****************************************************************
	* * * * * * * * * * Manage the RESPONSE * * * * * * * * * * * *
	******************************************************************/


	/*****************************************************************
	* Read headers - receives headers line by line (cURL callback)
	******************************************************************/

	public function readHeader($handle, $header) {

		# Extract the status code (can occur more than once if 100 continue)
		if ( $this->status == 0 || ( $this->status == 100 && ! strpos($header, ':') ) ) {
			$this->status = substr($header, 9, 3);
		}

		# Attempt to extract header name and value
		$parts = explode(':', $header, 2);

		# Did it split successfully? (i.e. was there a ":" in the header?)
		if ( isset($parts[1]) ) {

			# Header names are case insensitive
			$headerType = strtolower($parts[0]);

			# And header values will have trailing newlines and prevailing spaces
			$headerValue = trim($parts[1]);

			# Set any cookies
			if ( $headerType == 'set-cookie' && $this->forwardCookies ) {

				$this->setCookie($headerValue);

			}

			# Everything else, store as associative array
			$this->headers[$headerType] = $headerValue;

			# Do we want to forward this header? First list the headers we want:
			$toForward = array('last-modified',
									 'content-disposition',
									 'content-type',
									 'content-range',
									 'content-language',
									 'expires',
									 'cache-control',
									 'pragma');

			# And check for a match before forwarding the header.
			if ( in_array($headerType, $toForward) ) {
				header($header);
			}

		} else {

			# Either first header or last 'header' (more precisely, the 2 newlines
			# that indicate end of headers)

			# No ":", so save whole header. Also check for end of headers.
			if ( ( $this->headers[] = trim($header) ) == false ) {

				# Must be end of headers so process them before reading body
				$this->processHeaders();

				# And has that processing given us any reason to abort?
				if ( $this->abort ) {
					return -1;
				}

			}

		}

		# cURL needs us to return length of data read
		return strlen($header);

	}


	/*****************************************************************
	* Process headers after all received and before body is read
	******************************************************************/

	private function processHeaders() {

		# Ensure we only run this function once
		static $runOnce;

		# Check for flag and if found, stop running function
		if ( isset($runOnce) ) {
			return;
		}

		# Set flag for next time
		$runOnce = true;

		# Send the appropriate status code
		header(' ', true, $this->status);

		# Find out if we want to abort the transfer
		switch ( true ) {

			# Redirection
			case isset($this->headers['location']):

				$this->abort = 'redirect';

				return;

			# 304 Not Modified
			case $this->status == 304:

				$this->abort = 'not_modified';

				return;

			# 401 Auth required
			case $this->status == 401:

				$this->abort = 'auth_required';

				return;

			# Error code (>=400)
			/*
			case $this->status >= 400:

				$this->abort = 'http_status_error';

				return;*/

			# Check for a content-length above the filesize limit
			case isset($this->headers['content-length']) && $this->limitFilesize && $this->headers['content-length'] > $this->limitFilesize:

				$this->abort = 'filesize_limit';

				return;

		}

		# Still here? No need to abort so next we determine parsing mechanism to use (if any)
		if ( isset($this->headers['content-type']) ) {

			# Define content-type to parser type relations
			$types = array(
				'text/javascript'			=> 'javascript',
				'text/ecmascript'			=> 'javascript',
				'application/javascript'	=> 'javascript',
				'application/x-javascript'	=> 'javascript',
				'application/ecmascript'	=> 'javascript',
				'application/x-ecmascript'	=> 'javascript',
				'text/livescript'			=> 'javascript',
				'text/jscript'				=> 'javascript',
				'application/xhtml+xml'		=> 'html',
				'text/html'					=> 'html',
				'text/css'					=> 'css',
			#	'text/xml'					=> 'rss',
			#	'application/rss+xml'		=> 'rss',
			#	'application/rdf+xml'		=> 'rss',
			#	'application/atom+xml'		=> 'rss',
			#	'application/xml'			=> 'rss',
			);

			# Extract mimetype from charset (if exists)
			global $charset;
			$content_type = explode(';', $this->headers['content-type'], 2);
			$mime = isset($content_type[0]) ? trim($content_type[0]) : '';
			if (isset($content_type[1])) {
				$charset = preg_match('#charset\s*=\s*([^"\'\s]*)#is', $content_type[1], $tmp, PREG_OFFSET_CAPTURE) ? $tmp[1][0] : null;
			}

			# Look for that mimetype in our array to find the parsing mechanism needed
			if ( isset($types[$mime]) ) {
				$this->parseType = $types[$mime];
			}

			# validate mimetypes
			if (!preg_match('#^(application|audio|image|text|video)/#i', $mime)) {
				header('Content-Type: text/plain');
			}

		} else {

			# Tell our read body function to 'sniff' the data to determine type
			$this->sniff = true;

		}

		# If no content-disposition sent, send one with the correct filename
		if ( ! isset($this->headers['content-disposition']) && $this->URL['filename'] ) {
			header('Content-Disposition: filename="' . $this->URL['filename'] . '"');
		}

		# If filesize limit exists, content-length received and we're still here, the
		# content-length is OK. If we assume the content-length is accurate (and since
		# clients [and possibly libcurl too] stop downloading after reaching the limit,
		# it's probably safe to assume that),we can save on load by not checking the
		# limit with each chunk received.
		if ( $this->limitFilesize && isset($this->headers['content-length']) ) {
			$this->limitFilesize = 0;
		}

	}


	/*****************************************************************
	* Read body - takes chunks of data (cURL callback)
	******************************************************************/

	public function readBody($handle, $data) {

		# Static var to tell us if this function has been run before
		static $first;

		# Check for set variable
		if ( ! isset($first) ) {

			# Run the pre-body code
			$this->firstBody($data);

			# Set the variable so we don't run this code again
			$first = false;

		}

		# Find length of data
		$length = strlen($data);

		# Limit speed to X bytes/second
		if ( $this->speedLimit ) {

			# Limit download speed
			# Speed		 = Amount of data / Time
			# [bytes/s] = [bytes]			/ [s]
			# We know the desired speed (defined earlier in bytes per second)
			# and we know the number of bytes we've received. Now we need to find
			# the time that it should take to receive those bytes.
			$time = $length / $this->speedLimit; # [s]

			# Convert time to microseconds and sleep for that value
			usleep(round($time * 1000000));
		}

		# Monitor length if desired
		if ( $this->limitFilesize ) {

			# Set up a static downloaded-bytes value
			static $downloadedBytes;

			if ( ! isset($downloadedBytes) ) {
				$downloadedBytes = 0;
			}

			# Add length to downloadedBytes
			$downloadedBytes += $length;

			# Is downloadedBytes over the limit?
			if ( $downloadedBytes > $this->limitFilesize ) {

				# Set the abort variable and return -1 (so cURL aborts)
				$this->abort = 'filesize_limit';
				return -1;

			}

		}

		# If parsing is required, save as $return
		if ( $this->parseType ) {

			$this->return .= $data;

		} else {
			echo $data; # No parsing so print immediately
		}

		# cURL needs us to return length of data read
		return $length;

	}




	/*****************************************************************
	* Process first chunk of data in body
	* Sniff the content if no content-type was sent and create the file
	* handle if caching this.
	******************************************************************/

	private function firstBody($data) {

		# Do we want to sniff the data to gues the mimetype?
		if ( $this->sniff ) {
			if (stripos($data, '<html')!==false && stripos($data, '<head')!==false) {
				header('Content-Type: text/html');
				$this->parseType = 'html';
			} else {
				header('Content-Type: text/plain');
			}
		}

		# Now we know if parsing is required, we can forward content-length
		if ( ! $this->parseType && isset($this->headers['content-length']) ) {
			header('Content-Length: ' . $this->headers['content-length']);
		}

	}


	/*****************************************************************
	* Accept cookies - takes the value from Set-Cookie: [COOKIE STRING]
	* and forwards cookies to the client
	******************************************************************/

	private function setCookie($cookieString) {

		# The script can handle cookies following the Netscape specification
		# (or close enough!) and supports "Max-Age" from RFC2109

		# Split parts by ;
		$cookieParts = explode(';', $cookieString);

		# Process each line
		foreach ( $cookieParts as $part ) {

			# Split attribute/value pairs by =
			$pair = explode('=', $part, 2);

			# Ensure we have a second part
			$pair[1] = isset($pair[1]) ? $pair[1] : '';

			# First pair must be name/cookie value
			if ( ! isset($cookieName) ) {

				# Name is first pair item, value is second
				$cookieName = $pair[0];
				$cookieValue = $pair[1];

				# Skip rest of loop and start processing attributes
				continue;

			}

			# If still here, must be an attribute (case-insensitive so lower it)
			$pair[0] = strtolower($pair[0]);

			# And save in array
			if ( $pair[1] ) {

				# We have a attribute/value pair so save as associative
				$attr[ltrim($pair[0])] = $pair[1];

			} else {

				# Not a pair, just a value
				$attr[] = $pair[0];

			}

		}

		# All cookies need to be sent to this script (and then we choose
		# the correct cookies to forward to the client) so the extra attributes
		# (path, domain, etc.) must be stored in the cookie itself

		# Cookies stored as c[domain.com][path][cookie_name] with values of
		# cookie_value;secure;
		# If encoded, cookie name becomes c[base64_encode(domain.com path cookie_name)]

		# Find the EXPIRES date
		if ( isset($attr['expires']) ) {

			# From the "Expires" attribute (original Netscape spec)
			$expires = strtotime($attr['expires']);

		} else if ( isset($attr['max-age']) ) {

			# From the "Max-Age" attribute (RFC2109)
			$expires = $_SERVER['REQUEST_TIME']+$attr['max-age'];

		} else {

			# Default to temp cookies
			$expires = 0;

		}

		# If temp cookies, override expiry date to end of session unless time
		# is in the past since that means the cookie should be deleted
		if ( $this->browsingOptions['tempCookies'] && $expires > $_SERVER['REQUEST_TIME'] ) {
			$expires = 0;
		}

		# Find the PATH. The spec says if none found, default to the current path.
		# Certain browsers default to the the root path so we'll do the same.
		if ( ! isset($attr['path']) ) {
			$attr['path'] = '/';
		}

		# Were we sent a DOMAIN?
		if ( isset($attr['domain']) ) {
			# Ensure it's valid and we can accept this cookie
			if ( stripos($attr['domain'], $this->URL['domain']) === false ) {

				# Our current domain does not match the specified domain
				# so we reject the cookie
				return;

			}

			# Some cookies will be sent with the domain starting with . as per RFC2109
			# The . then has to be stripped off by us when doing the tail match to determine
			# which cookies to send since ".glype.com" should match "glype.com". It's more
			# efficient to do any manipulations while forwarding cookies than on every request
			if ( $attr['domain'][0] == '.' ) {
				$attr['domain'] = substr($attr['domain'], 1);
			}

		} else {

			# No domain sent so use current domain
			$attr['domain'] = $this->URL['domain'];
		}

		# Check for SECURE cookie
		$sentSecure = in_array('secure', $attr);

		# Append "[SEC]" to cookie value if we should only forward to secure connections
		if ( $sentSecure ) {
			$cookieValue .= '!SEC';
		}

		# If we're on HTTPS, we can also send this cookie back as secure
		$secure = HTTPS && $sentSecure;

		# If the PHP version is recent enough, we can also forward the httponly flag
		$httponly = in_array('httponly', $attr) && version_compare(PHP_VERSION,'5.2.0','>=') ? true : false;

		# Prepare cookie name/value to save as
		$attr['domain']=str_replace('.', '_', $attr['domain']);//因为CI会过滤$_COOKIE键名中的'.'，所以将‘.’替换为‘_’，解析时还原即可
		$name = COOKIE_PREFIX . '[' . $attr['domain'] . '][' . $attr['path'] . '][' . inputEncode($cookieName) . ']';
		$value = $cookieValue;

		# Add encodings
		if ( $this->forwardCookies == 'encode' ) {

			$name = COOKIE_PREFIX . '[' . urlencode(base64_encode($attr['domain'] . ' ' . $attr['path'] . ' ' . urlencode($cookieName))) . ']';
			$value = base64_encode($value);

		}

		# Send cookie ...
		if ( $httponly ) {

			# ... with httponly flag
			setcookie($name, $value, $expires, '/', '', $secure, true);

		} else {
			# ... without httponly flag
			setcookie($name, $value, $expires, '/', '', $secure);

		}
		# And log if in debug mode
		if ( DEBUG_MODE ) {

			$this->cookiesReceived[] = array(
				'name'			=> $cookieName,
				'value'			=> $cookieValue,
				'attributes'	=> $attr
			);

		}

	}

}