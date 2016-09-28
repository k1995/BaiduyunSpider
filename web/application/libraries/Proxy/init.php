<?php
/*******************************************************************
* Glype is copyright and trademark 2007-2015 UpsideOut, Inc. d/b/a Glype
* and/or its licensors, successors and assigners. All rights reserved.
*
* Use of Glype is subject to the terms of the Software License Agreement.
* http://www.glype.com/license.php
*******************************************************************
* This file is a global include used everywhere in the script.
******************************************************************/

/*****************************************************************
* Initialise
******************************************************************/


# Prefix for cookies (change if having trouble running multiple proxies on same domain)
define('COOKIE_PREFIX', 'c');

# Set up paths/urls
define('GLYPE_ROOT', PROXY_PAYH);

# Running on HTTPS?
define('HTTPS', ( empty($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) == 'off' ? false : true ));


# Set timezone (uncomment and set to desired timezone)
#date_default_timezone_set('GMT');

# Ensure request time is available
$_SERVER['REQUEST_TIME'] = time();

define('COMPATABILITY_MODE', false);

# Set list of letters and numbers
define('ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');

# Load settings
require PROXY_PAYH . '/settings.php';

global $bitfield;

/*****************************************************************
* Language - text for error messages
******************************************************************/
$phrases['invalid_url']		 = 'The requested URL was not recognised as a valid URL. Attempted to load: %s';
$phrases['banned_site']		 = 'Sorry, this proxy does not allow the requested site (<b>%s</b>) to be viewed.';
$phrases['file_too_large']	 = 'The requested file is too large. The maximum permitted filesize is %s MB.';
$phrases['server_busy']		 = 'The server is currently busy and unable to process your request. Please try again in a few minutes. We apologise for any inconvenience.';
$phrases['http_error']		 = 'The requested resource could not be loaded because the server returned an error:<br> &nbsp; <b>%s %s</b> (<span class="tooltip" onmouseout="exit()" onmouseover="tooltip(\'%s\');">?</span>).';
$phrases['curl_error']		 = 'The requested resource could not be loaded. libcurl returned the error:<br><b>%s</b>';
$phrases['unknown_error']	 = 'The script encountered an unknown error. Error id: <b>%s</b>.';

# If an HTTP error (status code >= 400) is encountered, the script will look here
# for an additional "friendly" explanation of the problem.
$httpErrors = array('404' => 'A 404 error occurs when the requested resource does not exist.');


/*****************************************************************
* Start session
******************************************************************/

# Set name to the configured value - change if running multiple proxies in same
# folder and experiencing session conflicts.
session_name('s');

# Allow caching. We don't want PHP to send any cache-related headers automatically
# (and by default it tries to stop all caching). Using this limiter sends the fewest
# headers, which we override later.
session_cache_limiter('private_no_expire');

# Don't call _start() if session.auto_start = 1
if ( glype_session_id() == '' ) {
	session_start();
}


/*****************************************************************
* Find bitfield to determine options from
******************************************************************/

# First, find the bitfield!
if ( $CONFIG['path_info_urls'] && ! empty($_SERVER['PATH_INFO']) && preg_match('#/b([0-9]{1,5})(?:/f([a-z]{1,10}))?/?$#', $_SERVER['PATH_INFO'], $tmp) ) {

	# Found a /bXX/ value at end of path info
	$bitfield = $tmp[1];

	# (And while we're here, grab the flag too)
	$flag = isset($tmp[2]) ? $tmp[2] : '';

} else if ( ! empty($_GET['b']) ) {

	# Found a b= value in the query string
	$bitfield = intval($_GET['b']);

} else if ( ! empty($_SESSION['bitfield']) ) {

	# Use stored session bitfield - mid-browsing but somehow lost the bitfield
	$bitfield = $_SESSION['bitfield'];

} else {

	# Could not find any bitfield, regenerate (later)
	$regenerate = true;
	$bitfield = 0;

}
# Get flag from query string while we're here
if ( ! isset($flag) ) {
	$flag = isset($_GET['f']) ? $_GET['f'] : '';
}


/*****************************************************************
* Determine options / use defaults
******************************************************************/

$i = 0; 

global $options;

# Loop through the possible options
foreach ( $CONFIG['options'] as $name => $details ) {

	# Is the option forced?
	if ( ! empty($details['force']) ) {

		# Use default
		$options[$name] = $details['default'];

		# And move onto next option
		continue;
	}

	# Which bit does this option occupy in the bitfield?
	$bit = pow(2, $i);

	# Use value from bitfield if possible,
	if ( ! isset($regenerate) ) {

		# Use value from bitfield
		$options[$name] = checkBit($bitfield, $bit);

	}
	# No bitfield available - use defaults and regenerate
	else {

		# Use default value
		$options[$name] = $details['default'];

		# Set bit
		if ( $details['default'] ) {
			setBit($bitfield, $bit);
		}

	}

	# Increase index
	++$i;
}

# Save new session value
$_SESSION['bitfield'] = $bitfield;


/*****************************************************************
* Unique URLs
******************************************************************/

# First visit? Ensure we have a unique salt
if (!isset($_SESSION['unique_salt'])) {
	$alphabet=ALPHABET;
	$unique_salt='';
	$alphas=strlen($alphabet);
	for ($i=0; $i<128; ++$i) {$unique_salt.=$alphabet[(rand()%$alphas)];}
	$_SESSION['unique_salt']=$unique_salt;
}

# Session gets closed before all parsing complete so copy unique to globals
$GLOBALS['unique_salt'] = $_SESSION['unique_salt'];


/*****************************************************************
* Sort javascript flags
* These determine how much parsing we do server-side and what can
* be left for the browser client-side.
*	  FALSE	 - unknown capabilities, parse all non-standard code
*	  NULL	 - javascript override disabled, parse everything
*	  (array) - flags of which overrides have failed (so parse these)
******************************************************************/

if ( $CONFIG['override_javascript'] ) {
	$jsFlags = isset($_SESSION['js_flags']) ? $_SESSION['js_flags'] : false;
} else {
	$jsFlags = null;
}


/*****************************************************************
* Custom browser - set up defaults
******************************************************************/

if ( ! isset($_SESSION['custom_browser']) ) {
	$_SESSION['custom_browser'] = array(
		'user_agent'	=> isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
		'referrer'		=> 'real',
		'tunnel'		=> '',
		'tunnel_port'	=> '',
		'tunnel_type'	=> '',
	);
}



/*****************************************************************
* Input encoding / decoding
* PHP converts a number of characters to underscores in incoming
* variable names in an attempt to be compatible with register globals.
* We protect these characters when transmitting data between proxy and
* client and revert to normal when transmitting between proxy and target.
******************************************************************/

# Encode
function inputEncode($input) {

	# rawurlencode() does almost everything so start with that
	$input = rawurlencode($input);

	# Periods are not encoded and PHP doesn't accept them in incoming
	# variable names so encode them too
	$input = str_replace('.', '%2E', $input);

	# [] can be used to create an array so preserve them
	$input = str_replace('%5B', '[', $input);
	$input = str_replace('%5D', ']', $input);

	# And return changed
	return $input;
}

# And the complementary decode
function inputDecode($input) {

	return rawurldecode($input);
}


/*****************************************************************
* Bitfield operations
******************************************************************/

function checkBit($value, $bit) {
	return ($value & $bit) ? true : false;
}

function setBit(&$value, $bit) {
	$value = $value | $bit;
}



/*****************************************************************
* Compatability
******************************************************************/

# Requirements are only PHP5 but this function was introduced in PHP 5.1.3
if ( ! function_exists('curl_setopt_array') ) {

	# Takes an array of options and sets all at once
	function curl_setopt_array($ch, $options) {
		foreach ( $options as $option => $value ) {
			curl_setopt($ch, $option, $value);
		}
	}
  
}


/*****************************************************************
* Miscellaneous
******************************************************************/

# Send no-cache headers.
function sendNoCache() {
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
}

# Trim and stripslashes
function clean($value) {	

	# Recurse if array
	if ( is_array($value) ) {
		return array_map($value);
	}

	# Trim extra spaces
	$value = trim($value);

	# Validation
	if (is_string($value)) {
		$value = stripslashes(strip_tags($value));
	}

	# Return cleaned
	return $value;
}

# Error message
function error($type, $allowReload=false) {

	global $CONFIG, $themeReplace, $options, $phrases, $flag;

	# Get extra arguments
	$args = func_get_args();

	# Remove first argument (we have that as $type)
	array_shift($args);

	# Check error exists
	# Force to the "unknown" error message
	if ( ! isset($phrases[$type]) ) {
		$args = array($type);
		$type = 'unknown_error';
	}

	if ( $args ) {
		# Error text must be generated by calling sprintf - we only have
		# the extra args as an array so we have to use call_user_func_array
		$errorText = call_user_func_array('sprintf', array_merge((array) $phrases[$type], $args));
	} else {
		# Error text can be fetched simply from the $phrases array
		$errorText = $phrases[$type];
	}

	$errorText = '<div id="error">' . $errorText . '</div>';

	# And a link to try again?
	$return=currentURL();
	if (strlen($return)>0) {
		$errorText .= '<p style="text-align:right">[<a href="' . htmlentities($return) . '">Reload ' . htmlentities(Http_proxy::deproxyURL($return)) . '</a>]</p>';
	}

	# Start with an empty array
	$toShow = array();

	sendNoCache();

	echo $errorText;

	# And flush buffer
	ob_end_flush();
	exit;
}

# Return current URL (absolute URL to proxied page)
function currentURL() {

	# Which method are we using
	$method = empty($_SERVER['PATH_INFO']) ? 'QUERY_STRING' : 'PATH_INFO';

	# Slash or question
	$separator = $method == 'QUERY_STRING' ? '?' : '';

	# Return full URL
	return Http_proxy::getInstance()->_url_ . $separator . ( isset($_SERVER[$method]) ? $_SERVER[$method] : '');
}

# Check tmp directory and create it if necessary
function checkTmpDir($path, $htaccess=false) {

	global $CONFIG;

	# Does it already exist?
	if ( file_exists($path) ) {

		# Return "ok" (true) if folder is writable
		if ( is_writable($path) ) {
			return 'ok';
		}

		# Exists but not writable. Nothing else we can do.
		return false;

	} else {

		# Does not exist, can we create it? (No if the desired dir is not
		# inside the temp dir)
		if ( is_writable($CONFIG['tmp_dir']) && realpath($CONFIG['tmp_dir']) == realpath(dirname($path) . '/') && mkdir($path, 0755, true) ) {

			# New dir, protect it with .htaccess
			if ( $htaccess ) {
				file_put_contents($path . '/.htaccess', $htaccess);
			}

			# Return (true) "made"
			return 'made';
		}
	}

	return false;
}

# note - intended to obfustate URLs and HTML source code. Does not provide security. Use SSL for actual security.
function arcfour($w,$k,$d) {
	if ($w=='decrypt')
	{
		$d=base64_decode($d);
	}
	$o='';
	$s=array();
	$n=256;
	$l=strlen($k);
	$e=strlen($d);
	for($i=0;$i<$n;++$i){
		$s[$i]=$i;
	}
	for($j=$i=0;$i<$n;++$i){
		$j=($j+$s[$i]+ord($k[$i%$l]))%$n;
		$x=$s[$i];$s[$i]=$s[$j];$s[$j]=$x;
	}
	for($i=$j=$y=0;$y<$e;++$y){
		$i=($i+1)%$n;
		$j=($j+$s[$i])%$n;
		$x=$s[$i];
		$s[$i]=$s[$j];
		$s[$j]=$x;
		$o.=$d[$y]^chr($s[($s[$i]+$s[$j])%$n]);
	}
	if ($w=='encrypt')
	{
		$o=base64_encode($o);
	}
	return $o;
}

# note - intended to obfustate URLs and HTML source code. Does not provide security. Use SSL for actual security.
function glype_session_id() {
	$session_id = session_id();
	if ($session_id=='') {
		return '';
	} elseif (!preg_match('/^[a-zA-Z0-9-]+$/', $session_id)) { # valid characters are a-z, A-Z, 0-9 and '-'
		return md5($_SERVER['HTTP_HOST'].$_SERVER['REMOTE_ADDR']);
	} else {
		return $session_id;
	}
}

# Proxify is a registered trademark of UpsideOut, Inc. All rights reserved.
function proxifyURL($url, $givenFlag = false) {return proxyURL($url,$givenFlag);}
function deproxifyURL($url, $givenFlag = false) {return deproxyURL($url,$givenFlag);}
