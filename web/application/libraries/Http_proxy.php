<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('PROXY_PAYH',dirname(__FILE__).'/Proxy');

include_once PROXY_PAYH.'/init.php';

class Http_proxy{

	#目标URL
	public 	$toLoad;

	#目标URL相关信息
	public 	static $URL;

	#document解析回调函数
	public 	$callback;

	public 	$redirectCallback;

	public 	$document;
	
	private $request;

	private $curlOpts;
	
	#当前代理服务器的url
	public $_url_;

	private static $instance;

	public function __construct($args){

		$defaults=array(
			'toLoad'			=>	'',
			'callback'			=>	null,
			'redirectCallback'	=> 	null,
			'_url_'				=>	'',
			'userAgent'			=>	''
		);

		self::$instance=&$this;

		$args=wp_parse_args($args,$defaults);

		$this->toLoad				=	self::deproxyURL($args['toLoad'],true);
		$this->_url_				=	$args['_url_'];
		$this->callback				=	$args['callback'];
		$this->redirectCallback		=	$args['redirectCallback'];
		$this->userAgent 			=	$args['userAgent'];
		
		if(!$this->toLoad){

			$this->redirect();
			exit;
		}

		# Log cURLs activity to file
		# Change filename below if desired. Ensure file exists and is writable.
		if ( CURL_LOG	&& ( $fh = @fopen('curl.txt', 'w')) ) {
			$this->curlOpts[CURLOPT_STDERR] = $fh;
			$this->curlOpts[CURLOPT_VERBOSE] = true;
		}

		$this->init();
	}

	public static function getInstance(){

		return self::$instance;
	}

	public function parseUrl(){

		# Validate the URL
		if ( ! preg_match('#^((https?)://(?:([a-z0-9-.]+:[a-z0-9-.]+)@)?([a-z0-9-.]+)(?::([0-9]+))?)(?:/|$)((?:[^?/]*/)*)([^?]*)(?:\?([^\#]*))?(?:\#.*)?$#i', $this->toLoad, $tmp) ) {

			# Invalid, show error
			error('invalid_url', htmlentities($this->toLoad));
		}

		# Rename parts to more useful names
		self::$URL = array(
			'scheme_host'	=> $tmp[1],
			'scheme'		=> $tmp[2],
			'auth'			=> $tmp[3],
			'host'			=> strtolower($tmp[4]),
			'domain'		=> strtolower(preg_match('#(?:^|\.)([a-z0-9-]+\.(?:[a-z.]{5,6}|[a-z]{2,}))$#', $tmp[4], $domain) ? $domain[1] : $tmp[4]), # Attempt to split off the subdomain (if any)
			'port'			=> $tmp[5],
			'path'			=> '/' . $tmp[6],
			'filename'		=> $tmp[7],
			'extension'		=> pathinfo($tmp[7], PATHINFO_EXTENSION),
			'query'			=> isset($tmp[8]) ? $tmp[8] : ''
		);


		# Apply encoding on full URL. In theory all parts of the URL need various special
		# characters encoding but this needs to be done by the author of the webpage.
		# We can make a guess at what needs encoding but some servers will complain when
		# receiving the encoded character instead of unencoded and vice versa. We want
		# to edit the URL as little as possible so we're only encoding spaces, as this
		# seems to 'fix' the majority of cases.
		self::$URL['href'] = str_replace(' ', '%20', $this->toLoad);

		# Add any supplied authentication information to our auth array
		if (self::$URL['auth']) {
			$_SESSION['authenticate'][self::$URL['scheme_host']] = self::$URL['auth'];
		}
	}


	/*****************************************************************
	* URL encoding
	* There are 3 options that affect URL encodings - the path info setting,
	* the unique URLs setting and the users choice of to encode or not.
	******************************************************************/

	# Takes a normal URL and converts it to a URL that, when requested,
	# will load the resource through our proxy
	public static function proxyURL($url, $givenFlag = false) {

		global $CONFIG, $options, $bitfield, $flag;

		# Remove excess whitespace
		$url = trim($url);

		# check for binary images
		if (stripos($url,'data:image')===0) {
			return $url;
		}

		# handle javascript
		if (stripos($url,'javascript:')===0 || stripos($url,'livescript:')===0) {
		#	return JS($url);
			return '';
		}

		# Validate the input
		if ( empty($url) || $url[0]=='#' || $url=='about:' || stripos($url,'data:')===0 || stripos($url,'file:')===0 || stripos($url,'res:')===0 || stripos($url,'C:')===0 || strpos($url, self::getInstance()->_url_)===0 ) {
			return '';
		}

		# Extract any #anchor since we don't want to encode that
		if ( $tmp = strpos($url, '#') ) {
			$anchor = substr($url, $tmp);
			$url = substr($url, 0, $tmp);
		} else {
			$anchor = '';
		}

		# Convert to absolute URL (if not already)
		$url = self::absoluteURL($url);

		# Add encoding
		if ( $options['encodeURL'] ) {

			# Part of our encoding is to remove HTTP (saves space and helps avoid detection)
			$url = substr($url, 4);
			# Encrypt
			if ( isset($GLOBALS['unique_salt']) ) {
				$url = arcfour('encrypt',$GLOBALS['unique_salt'],$url);
			}
		}

		# Protect chars that have other meaning in URLs
		$url = rawurlencode($url);

		# Determine flag to use - $givenFlag is passed into function, $flag
		# is global flag currently in use (used here for persisting the frame state)
		$addFlag = $givenFlag ? $givenFlag : ( $flag == 'frame' ? 'frame' : '' );

		# Return in path info format (only when encoding is on)
		if ( $CONFIG['path_info_urls'] && $options['encodeURL'] ) {
			return self::getInstance()->_url_ . '/' . str_replace('%', '_', chunk_split($url, 8, '/')) . 'b' . $bitfield . '/' . ( $addFlag ? 'f' . $addFlag : '') . $anchor;
		}

		# Otherwise, return in 'normal' (query string) format
		return self::getInstance()->_url_ . '?u=' . $url . '&b=' . $bitfield . ( $addFlag ? '&f=' . $addFlag : '' ) . $anchor;
	}

	# Takes a URL that has been proxied by the proxyURL() function
	# and returns it to a normal, direct URL
	public static function deproxyURL($url, $verifyUnique=false) {

		# Check we have URL to deproxy
		if ( empty($url) ) {
			return $url;
		}

		# Remove our prefix
		$url = str_replace(self::getInstance()->_url_, '', $url);

		# Take off flags and bitfield
		if ( $url[0] == '/' ) {

			# First char is slash, must be path info format
			$url = preg_replace('#/b[0-9]{1,5}(?:/f[a-z]{1,10})?/?$#', '', $url);

			# Return % and strip /
			$url = str_replace('_', '%', $url);
			$url = str_replace('/', '', $url);

		} else {

			# First char not / so must be the standard query string format
			if ( preg_match('#\bu=([^&]+)#', $url, $tmp) ) {
				$url = $tmp[1];
			}

		}

		# No :// here means url is encoded or encrypted.
		if (!strpos($url, '://')) {
			$url = rawurldecode($url);
		}

		# No :// here means url is encrypted.
		if (!strpos($url, '://')) {

			# Decrypt
			if ( isset($GLOBALS['unique_salt']) ) {
				$url = arcfour('decrypt',$GLOBALS['unique_salt'],$url);
			}

			# Add http back
			$url = 'http' . $url;

		}

		# URLs were originally HTML attributes so *should* have had all
		# entities encoded. Decode it.
		$url = htmlspecialchars_decode($url);

		# Check for successful decoding
		if ( strpos($url, '://') === false ) {
			return false;
		}

		# Return decoded URL
		return $url;

	}

	# Take any type of URL (relative, absolute, with base, from root, etc.)
	# and return an absolute URL.
	public static function absoluteURL($input) {
		
		global $base;

		# Check we have something to work with
		if ( $input == false ) {
			return $input;
		}

		# "//domain.com" is valid - add the HTTP protocol if we have this
		if ( $input[0] == '/' && isset($input[1]) && $input[1] == '/' ) {
			$input= self::$URL['scheme'].':'.$input;
		}

		# URIs that start with ? are relative to the page loaded
		if ($input[0] == '?') {
			$input = self::$URL['href'].$input;
		}

		# Look for http or https and if necessary, convert relative to absolute
		if ( stripos($input, 'http://') !== 0 && stripos($input, 'https://') !== 0 ) {

			# . refers to current directory so do nothing if we find it
			if ( $input == '.' ) {
				$input = '';
			}

			# Check for the first char indicating the URL is relative from root,
			# in which case we just need to add the hostname prefix
			if ( $input && $input[0] == '/' ) {
				$input = self::$URL['scheme_host'] . $input;
			} else if ( isset($base) ) {

				# Not relative from root, is there a base href specified?
				$input = $base . $input;

			} else {

				# Not relative from root, no base href, must be relative to current directory
				$input = self::$URL['scheme_host'] . self::$URL['path'] . $input;

			}
		}

		# URL is absolute. Now attempt to simplify path.	 
		# Strip ./ (refers to current directory)
		$input = str_replace('/./', '/', $input);

		# Strip double slash #
		if ( isset($input[8]) && strpos($input, '//', 8) ) {
		#	$input = preg_replace('#(?<!:)//#', '/', $input);
		}

		# Look for ../
		if ( strpos($input, '../') ) {

			# Extract path component only
			$oldPath = 
			$path		= parse_url($input, PHP_URL_PATH);

			# Convert ../ into "go up a directory"
			while ( ( $tmp = strpos($path, '/../') ) !== false ) {

				# If found at start of path, simply remove since we can't go
				# up beyond the root.
				if ( $tmp === 0 ) {
					$path = substr($path, 3);
					continue;
				}

				# It was found later so find the previous /
				$previousDir = strrpos($path, '/', - ( strlen($path) - $tmp + 1 ) );

				# And splice that directory out
				$path = substr_replace($path, '', $previousDir, $tmp+3-$previousDir);

			}

			# Replace path component with new
			$input = str_replace($oldPath, $path, $input);

		}

		return $input;

	}

	/*****************************************************************
	* * * * * * * * * * Prepare the REQUEST * * * * * * * * * * * *
	******************************************************************/

	/*****************************************************************
	* Set cURL transfer options
	* These options are merely passed to cURL and our script has no further
	* impact or dependence of them. See the libcurl documentation and
	* http://php.net/curl_setopt for more details.
	*
	* The following options are required for the proxy to function or
	* inherit values from our config. In short: they shouldn't need changing.
	******************************************************************/
	public function prepareRequest(){

		global $CONFIG;
		
		# Time to wait for connection
		$this->curlOpts[CURLOPT_CONNECTTIMEOUT] = $CONFIG['connection_timeout'];

		# Time to allow for entire transfer
		$this->curlOpts[CURLOPT_TIMEOUT] = $CONFIG['transfer_timeout'];

		# Show SSL without verifying - we almost definitely don't have an up to date CA cert
		# bundle so we can't verify the certificate. See http://curl.haxx.se/docs/sslcerts.html
		$this->curlOpts[CURLOPT_SSL_VERIFYPEER] = false;
		$this->curlOpts[CURLOPT_SSL_VERIFYHOST] = false;

		# Send an empty Expect header (avoids 100 responses)
		$this->curlOpts[CURLOPT_HTTPHEADER][] = 'Expect:';

		# Can we use "If-Modified-Since" to save a transfer? Server can return 304 Not Modified
		if ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ) {

			# How to treat the time condition : if un/modified since
			$this->curlOpts[CURLOPT_TIMECONDITION] = CURL_TIMECOND_IFMODSINCE;

			# The time value. Requires a timestamp so we can't just forward it raw
			$this->curlOpts[CURLOPT_TIMEVALUE] = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);

		}

		# Resume a transfer?
		if ( $CONFIG['resume_transfers'] && isset($_SERVER['HTTP_RANGE']) ) {

			# And give cURL the right part
			$this->curlOpts[CURLOPT_RANGE] = substr($_SERVER['HTTP_RANGE'], 6);

		}

		# cURL has a max filesize option but it's not listed in the PHP manual so check it's available
		if ( $CONFIG['max_filesize'] && defined('CURLOPT_MAXFILESIZE') ) {

			# Use the cURL option - should be faster than our implementation
			$this->curlOpts[CURLOPT_MAXFILESIZE] = $CONFIG['max_filesize'];

		}


		/*****************************************************************
		* Performance options
		* The values below are NOT the result of benchmarking tests. For
		* optimum performance, you may want to try adjusting these values.
		******************************************************************/

		# DNS cache expiry time (seconds)
		$this->curlOpts[CURLOPT_DNS_CACHE_TIMEOUT] = 600;

		# Speed limits - aborts transfer if we're going too slowly
		#$toSet[CURLOPT_LOW_SPEED_LIMIT] = 5; # speed limit in bytes per second
		#$toSet[CURLOPT_LOW_SPEED_TIME] = 20; # seconds spent under the speed limit before aborting

		# Number of max connections (no idea what this should be)
		# $toSet[CURLOPT_MAXCONNECTS] = 100;

		# Accept encoding in any format (allows compressed pages to be downloaded)
		# Any bandwidth savings are likely to be minimal so better to save on load by
		# downloading pages uncompressed. Use blank string for any compression or
		# 'identity' to explicitly ask for uncompressed.
		# $toSet[CURLOPT_ENCODING] = '';

		# Undocumented in PHP manual (added 5.2.1) but allows uploads to some sites
		# (e.g. imageshack) when without this option, an error occurs. Less efficient
		# so probably best not to set this unless you need it.
		#	 $toSet[CURLOPT_TCP_NODELAY] = true;


		/*****************************************************************
		* "Accept" headers
		* No point sending back a file that the browser won't understand.
		* Forward all the "Accept" headers. For each, check if it exists
		* and if yes, add to the custom headers array.
		* These may cause problems if the target server provides different
		* content for the same URI based on these headers and we cache the response.
		******************************************************************/

		# Language (geotargeting will find the location of the server -
		# forwarding this header can help avoid incorrect localisation)
		if ( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
			$this->curlOpts[CURLOPT_HTTPHEADER][] = 'Accept-Language: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		}

		# Accepted filetypes
		if ( isset($_SERVER['HTTP_ACCEPT']) ) {
			$this->curlOpts[CURLOPT_HTTPHEADER][] = 'Accept: ' . $_SERVER['HTTP_ACCEPT'];
		}

		# Accepted charsets
		if ( isset($_SERVER['HTTP_ACCEPT_CHARSET']) ) {
			$this->curlOpts[CURLOPT_HTTPHEADER][] = 'Accept-Charset: ' . $_SERVER['HTTP_ACCEPT_CHARSET'];
		}
	}

	/*****************************************************************
	* Cookies
	* Find the relevant cookies for this request. All cookies get sent
	* to the proxy, but we only want to forward the ones that were set
	* for the current domain.
	*
	* Cookie storage methods:
	* (1) Server-side - cookies stored server-side and handled
	*		(mostly) internally by cURL
	* (2) Encoded	- cookies forwarded to client but encoded
	* (3) Normal - cookies forwarded without encoding
	******************************************************************/
	public function prepareCookies(){

		global $CONFIG;
	
		# Option (1): cookies stored server-side
		if ( $CONFIG['cookies_on_server'] ) {

			# Check cookie folder exists or try to create it
			if ( $s = checkTmpDir($CONFIG['cookies_folder'], 'Deny from all') ) {

				# Set cURL to use this as the cookie jar
				$this->curlOpts[CURLOPT_COOKIEFILE] = $this->curlOpts[CURLOPT_COOKIEJAR] = $CONFIG['cookies_folder'] . glype_session_id();

			}

		} else if ( isset($_COOKIE[COOKIE_PREFIX]) ) {

			# Encoded or unencoded?
			if ( $CONFIG['encode_cookies'] ) {

				# Option (2): encoded cookies stored client-side
				foreach ( $_COOKIE[COOKIE_PREFIX] as $attributes => $value ) {

					# Decode cookie to [domain,path,name]
					$attributes = explode(' ', base64_decode($attributes));

					# Check successful decoding and skip if failed
					if ( ! isset($attributes[2]) ) {
						continue;
					}

					# Extract parts
					list($domain, $path, $name) = $attributes;

					# Check for a domain match and skip if no match
					if ( stripos(self::$URL['host'], $domain) === false ) {
						continue;
					}

					# Check for match and skip to next path if fail
					if ( stripos(self::$URL['path'], $path) !== 0 ) {
						continue;
					}

					# Multiple cookies of the same name are permitted if different paths
					# so use path AND name as the key in the temp array
					$key = $path . $name;

					# Check for existing cookie with same domain, same path and same name
					if ( isset($toSend[$key]) && $toSend[$key]['path'] == $path && $toSend[$key]['domain'] > strlen($domain) ) {

						# Conflicting cookies so ignore the one with the less complete tail match
						# (i.e. the current one)
						continue;

					}

					# Domain and path OK, decode cookie value
					$value = base64_decode($value);

					# Only send secure cookies on https connection - secure cookies marked by !SEC suffix
					# so remove the suffix
					$value = str_replace('!SEC', '', $value, $tmp);

					# And if secure cookie but not https site, do not send
					if ( $tmp && self::$URL['scheme'] != 'https' ) {
						continue;
					}


					# Everything checked and verified, add to $toSend for further processing later
					$toSend[$key] = array('path_size' => strlen($path), 'path' => $path, 'domain' => strlen($domain), 'send' => $name . '=' . $value);

				}

			} else {

				# Option (3): unencoded cookies stored client-side
				foreach ( $_COOKIE[COOKIE_PREFIX] as $domain => $paths ) {
					//因为CI会过滤$_COOKIE键名中的'.'，所以将‘.’替换为‘_’，解析时还原即可
					$domain=str_replace('_', '.', $domain);
					# $domain holds the domain (surprisingly) and $path is an array
					# of keys (paths) and more arrays (each child array of $path = one cookie)
					# e.g. Array('domain.com' => Array('/' => Array('cookie_name' => 'value')))
					
					# First check for domain match and skip to next domain if no match
					if ( stripos(self::$URL['host'], $domain) === false ) {

						continue;
					}

					# If conflicting cookies with same name and same path,
					# send the one with the more complete tail match. To do this we
					# need to know how long each match is/was so record domain length.
					$domainSize = strlen($domain);

					# Now look at all the available paths
					foreach ( $paths as $path => $cookies ) {

						# Check for match and skip to next path if fail
						if ( stripos(self::$URL['path'], $path) !== 0 ) {
							continue;
						}

						# In final header, cookies are ordered with most specific path
						# matches first so include the length of match in temp array
						$pathSize = strlen($path);

						# All cookies in $cookies array should be sent
						foreach ( $cookies as $name => $value ) {

							# Multiple cookies of the same name are permitted if different paths
							# so use path AND name as the key in the temp array
							$key = $path . $name;

							# Check for existing cookie with same domain, same path and same name
							if ( isset($toSend[$key]) && $toSend[$key]['path'] == $path && $toSend[$key]['domain'] > $domainSize ) {

								# Conflicting cookies so ignore the one with the less complete tail match
								# (i.e. the current one)
								continue;

							}

							# Only send secure cookies on https connection - secure cookies marked by !SEC suffix
							# so remove the suffix
							$value = str_replace('!SEC', '', $value, $tmp);

							# And if secure cookie but not https site, do not send
							if ( $tmp && self::$URL['scheme'] != 'https') {
								continue;
							}

							# Add to $toSend for further processing later
							$toSend[$key] = array('path_size' => $pathSize, 'path' => $path, 'domain' => $domainSize, 'send' => $name . '=' . $value);

						}

					}

				}

			}


			# Ensure we have found cookies
			if ( ! empty($toSend) ) {

				# Order by path specificity (as per Netscape spec)
				function compareArrays($a, $b) {
					return ( $a['path_size'] > $b['path_size'] ) ? -1 : 1;
				}

				# Apply the sort to order by path_size descending
				uasort($toSend, 'compareArrays');

				# Go through the ordered array and generate the Cookie: header
				$tmp = '';

				foreach ( $toSend as $cookie ) {
					$tmp .= $cookie['send'] . '; ';
				}

				# Give the string to cURL
				$this->curlOpts[CURLOPT_COOKIE] = $tmp;

			}

			# And clear the toSend array
			unset($toSend);

		}
	}

	public function init(){

		global $CONFIG,$options,$flag;

		/*****************************************************************
		* PHP sends some headers by default. Stop them.
		******************************************************************/

		# Clear the default mime-type
		header('Content-Type:');

		# And remove the caching headers
		header('Cache-Control:');
		header('Last-Modified:');

		$this->parseUrl();

		/*****************************************************************
		* Close session to allow simultaneous transfers
		* PHP automatically prevents multiple instances of the script running
		* simultaneously to avoid concurrency issues with the session.
		* This may be beneficial on high traffic servers but we have the option
		* to close the session and thus allow simultaneous transfers.
		******************************************************************/

		if ( ! $CONFIG['queue_transfers'] ) {

			session_write_close();
		}

		/*****************************************************************
		* Check load limit. This is done now rather than earlier so we
		* don't stop serving the (relatively) cheap cached files.
		******************************************************************/
		if (
			# Option enabled
			$CONFIG['load_limit']

			# Ignore inline elements - when borderline on the server load, if the HTML
			# page downloads fine but the inline images, css and js are blocked, the user
			# may get very frustrated very quickly without knowing about the load issues.
			&& ! in_array(self::$URL['extension'], array('jpg','jpeg','png','gif','css','js','ico'))
			) {

			# Do we need to find the load and regenerate the temp cache file?
			# Try to fetch the load from the temp file (~30 times faster than
			# shell_exec()) and ensure the value is accurate and not outdated,
			if( ! file_exists($file = $CONFIG['tmp_dir'] . 'load.php') || ! (include $file) || ! isset($load, $lastChecked) || $lastChecked < $_SERVER['REQUEST_TIME']-60 ) {

				$load = (float) 0;

				# Attempt to fetch the load
				if ( ($uptime = @shell_exec('uptime')) && preg_match('#load average: ([0-9.]+),#', $uptime, $tmp) ) {
					$load = (float) $tmp[1];

					# And regenerate the file
					file_put_contents($file, '<?php $load = ' . $load . '; $lastChecked = ' . $_SERVER['REQUEST_TIME'] . ';');
				}

			}

			# Load found, (or at least, should be), check against max permitted
			if ( $load > $CONFIG['load_limit'] ) {
				error('server_busy'); # Show error
			}
		}

		$this->prepareRequest();


		/*****************************************************************
		* Browser options
		******************************************************************/
		# Send user agent
		
		if($this->userAgent){

			$this->curlOpts[CURLOPT_USERAGENT]='Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36';
		
		}else if ( $_SESSION['custom_browser']['user_agent'] ) {
			
			$this->curlOpts[CURLOPT_USERAGENT] = $_SESSION['custom_browser']['user_agent'];

		}

		
		# Set referrer
		if ( $_SESSION['custom_browser']['referrer'] == 'real' ) {

			# Automatically determine referrer
			if ( isset($_SERVER['HTTP_REFERER']) && $flag != 'norefer' && strpos($tmp = self::deproxyURL($_SERVER['HTTP_REFERER']), self::getInstance()->_url_) === false ) {
				$this->curlOpts[CURLOPT_REFERER] = $tmp;
			}

		} else if ( $_SESSION['custom_browser']['referrer'] ) {

			# Send custom referrer
			$this->curlOpts[CURLOPT_REFERER] = $_SESSION['custom_browser']['referrer'];

		}

		# Clear the norefer flag
		if ( $flag == 'norefer' ) {
			$flag = '';
		}


		/*****************************************************************
		* Authentication
		******************************************************************/

		# Check for stored credentials for this site
		if ( isset($_SESSION['authenticate'][self::$URL['scheme_host']])) {

			# Found credentials so use them!
			$this->curlOpts[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
			$this->curlOpts[CURLOPT_USERPWD] = $_SESSION['authenticate'][self::$URL['scheme_host']];
		}

		if ( $options['allowCookies'] ) {

			$this->prepareCookies();
		}

		/*****************************************************************
		* Post
		* Forward the post data. Usually very simple but complicated by
		* multipart forms because in those cases, the raw post is not available.
		******************************************************************/

		if ( ! empty($_POST) ) {

			# enable backward compatibility with cURL's @ option for uploading files in PHP 5.5 and 5.6
			if (version_compare(PHP_VERSION, '5.5')>=0) {
				$this->curlOpts[CURLOPT_SAFE_UPLOAD] = false;
			}

			# Attempt to get raw POST from the input wrapper
			if ( ! ($tmp = file_get_contents('php://input')) ) {

				# Raw data not available (probably multipart/form-data).
				# cURL will do a multipart post if we pass an array as the
				# POSTFIELDS value but this array can only be one deep.

				# Recursively flatten array to one level deep and rename keys
				# as firstLayer[second][etc]. Also apply the input decode to all
				# array keys.
				function flattenArray($array, $prefix='') {

					# Start with empty array
					$stack = array();

					# Loop through the array to flatten
					foreach ( $array as $key => $value ) {

						# Decode the input name
						$key = inputDecode($key);

						# Determine what the new key should be - add the current key to
						# the prefix and surround in []
						$newKey = $prefix ? $prefix . '[' . $key . ']' : $key;

						if ( is_array($value) ) {

							# If it's an array, recurse and merge the returned array
							$stack = array_merge($stack, flattenArray($value, $newKey));

						} else {

							# Otherwise just add it to the current stack
							$stack[$newKey] = clean($value);

						}

					}

					# Return flattened
					return $stack;

				}

				$tmp = flattenArray($_POST);

				# Add any file uploads?
				if ( ! empty($_FILES) ) {

					# Loop through and add the files
					foreach ( $_FILES as $name => $file ) {

						# Is this an array?
						if ( is_array($file['tmp_name']) ) {

							# Flatten it - file arrays are in the slightly odd format of
							# $_FILES['layer1']['tmp_name']['layer2']['layer3,etc.'] so add
							# layer1 onto the start.
							$flattened = flattenArray(array($name => $file['tmp_name']));

							# And add all files to the post
							foreach ( $flattened as $key => $value ) {
								$tmp[$key] = '@' . $value;
							}

						} else {

							# Not another array. Check if the file uploaded successfully?
							if ( ! empty($file['error']) || empty($file['tmp_name']) ) {
								continue;
							}

							# Add to array with @ - tells cURL to upload this file
							$tmp[$name] = '@' . $file['tmp_name'];

						}

						# To do: rename the temp file to it's real name before
						# uploading it to the target? Otherwise, the target receives
						# the temp name instead of the original desired name
						# but doing this may be a security risk.

					}

				}

				}

			# Convert back to GET if required
			if ( isset($_POST['convertGET']) ) {

				# Remove convertGET from POST array and update our location
				self::$URL['href'] .= ( empty(self::$URL['query']) ? '?' : '&' ) . str_replace('convertGET=1', '', $tmp);
			} else {

				# Genuine POST so set the cURL post value
				$this->curlOpts[CURLOPT_POST] = 1;
				$this->curlOpts[CURLOPT_POSTFIELDS] = $tmp;
			}
		}
	}

	public function parse($document){

		global $flag,$options,$jsFlags,$CONFIG;


		/*****************************************************************
		* Transfer finished and errors handle. Process the file.
		******************************************************************/

		# Is this AJAX? If so, don't cache, log or parse.
		# Also, assume ajax if return is VERY short.
		if ( $flag == 'ajax' || ( $this->request->parseType && strlen($this->document) < 10 ) ) {

			# Print if not already printed
			if ( $this->request->parseType ) {
				echo $this->document;
			}

			# And exit
			exit;
		}

		# Do we want to parse the file?
		if ( $this->request->parseType ) {

			/*****************************************************************
			* Apply the relevant parsing methods to the document
			******************************************************************/

			# Decode gzip compressed content
			if (isset($this->request->headers['content-encoding']) && $this->request->headers['content-encoding']=='gzip') {
				if (function_exists('gzinflate')) {
					unset($this->request->headers['content-encoding']);
					$this->document=gzinflate(substr($this->document,10,-8));
				}
			}

			# Load the main parser
			include_once PROXY_PAYH.'/parser.php';

			# Create new instance, passing in the options that affect parsing
			$parser = new parser($options, $jsFlags);

			# Method of parsing depends on $parseType
			switch ( $this->request->parseType ) {

				# HTML document
				case 'html':

					# Do we want to insert our own code into the document?
					$inject = 
					$footer = 
					$insert = false;

					# Mini-form only if NOT frame or sniffed
					if ( $flag != 'frame' && $this->request->sniff == false ) {

						# And load the footer
						$footer = $CONFIG['footer_include'];

					}

					# Inject javascript unless sniffed
					if ( $this->request->sniff == false ) {
						$inject = true;
					}

					# Run through HTML parser
					$this->document = $parser->HTMLDocument($this->document, $insert, $inject, $footer);

					break;


				# CSS file
				case 'css':

					# Run through CSS parser
					$this->document = $parser->CSS($this->document);

					break;


				# Javascript file
				case 'javascript':

					# Run through javascript parser
					$this->document = $parser->JS($this->document);

					break;

			}


			# Send output
			if ( ! DEBUG_MODE ) {

				# Do we want to gzip this? Yes, if all of the following are true:
				#	  - gzip option enabled
				#	  - client supports gzip
				#	  - zlib extension loaded
				#	  - output compression not automated
				if ( $CONFIG['gzip_return'] && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip') !== false && extension_loaded('zlib') && ! ini_get('zlib.output_compression') ) {

					# Send compressed (using level 3 compression - can be adjusted
					# to give smaller/larger files but will take longer/shorter time!)
					header('Content-Encoding: gzip');
					echo gzencode($this->document, 3);

				} else {

					# Send uncompressed
					echo $this->document;

				}

			}

		}

	}

	# Redirect
	function redirect($to = '') {

		# Did we have an absolute URL?
		if ( strpos($to, 'http') !== 0 ) {

			# If not, prefix our current URL
			$to = self::getInstance()->_url_ . '/' . $to;

		}
		# Send redirect
		header('Location: ' . $to);

		exit;
	}

	public function execute(){

		include_once PROXY_PAYH.'/Request.php';

		global $falg;

		/*****************************************************************
		* Execute the request
		******************************************************************/

		# Initiate cURL wrapper request object with our cURL options
		$this->request = new Request($this->curlOpts);

		# And make the request
		$this->document = $this->request->go(self::$URL);

		if ( $this->request->abort ) {

			switch ( $this->request->abort ) {

				# Do a redirection
				case 'redirect':
					if($this->redirectCallback){

						if(!call_user_func($this->redirectCallback,$this->request->headers['location'])){

							exit();
						}
					}
					# Proxy the location
					$location = self::proxyURL($this->request->headers['location'], $flag);

					# Do not redirect in debug mode
					if ( DEBUG_MODE ) {
						$this->request->redirected = '<a href="' . $location . '">' . $this->request->headers['location'] . '</a>';
						break;
					}

					# Go there
					header('Location: ' . $location, true, $this->request->status);
					exit;


				# Send back a 304 Not modified and stop running the script
				case 'not_modified':
					header("HTTP/1.1 304 Not Modified", true, 304);
					exit;


				# 401 Authentication (HTTP authentication hooks not available in all PHP versions
				# so we have to use our method)
				case 'auth_required':

					# Ensure we have some means of authenticating and extract details about the type of authentication
					if ( ! isset($this->request->headers['www-authenticate']) ) {
						break;
					}

					# Realm to display to the user
					$realm = preg_match('#\brealm="([^"]*)"#i', $fetch->headers['www-authenticate'], $tmp) ? $tmp[1] : '';

					# Prevent caching
					sendNoCache();

					# Prepare template variables (session may be closed at this point so send via form)
					$tmp = array('site'	 => self::$URL['scheme_host'],
									 'realm'	 => $realm,
									 'return' => currentURL());

					# Show our form and quit
					echo loadTemplate('authenticate.page', $tmp);
					exit;


				# File request above filesize limit
				case 'filesize_limit':

					# If already sent some of the file, we can't display an error
					# so just stop running
					if ( ! $this->request->parseType ) {
						exit;
					}

					# Send to error page with filesize limit expressed in MB
					error('file_too_large', round($CONFIG['max_filesize']/1024/1024, 3));
					exit;


				# >=400 response code (some sort of HTTP error)
				case 'http_status_error':

					# Provide a friendly message
					$explain = isset($httpErrors[$this->request->status]) ? $httpErrors[$this->request->status] : '';

					# Simply forward the error with details
					error('http_error', $this->request->status, trim(substr($this->request->headers[0], 12)), $explain);
					exit;


				# Unknown (shouldn't happen)
				default:
					error('cURL::$abort (' . $this->request->abort .')');
			}

		}

		# Any cURL errors?
		if ( $this->request->error ) {

			error('curl_error', $this->request->error);

		}
		if($this->callback){
			
			$this->document = call_user_func($this->callback,$this->document); 
			if(!$this->document)
				return;
		}
		call_user_func(array(&$this,'parse'),$this->document);
	}
}
