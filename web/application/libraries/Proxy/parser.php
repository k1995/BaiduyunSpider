<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*******************************************************************
* Glype is copyright and trademark 2007-2015 UpsideOut, Inc. d/b/a Glype
* and/or its licensors, successors and assigners. All rights reserved.
*
* Use of Glype is subject to the terms of the Software License Agreement.
* http://www.glype.com/license.php
*******************************************************************
* This is the parser for the proxy - changes the original 'raw'
* document so that everything (images, links, etc.) is rerouted to
* be downloaded via the proxy script instead of directly.
******************************************************************/

class parser {

	# State of javascript parser - null for parse everything, false
	# for parse all non-standard overrides, or (array) with specifics
	private $jsFlagState;

	# Browsing options (Remove Scripts, etc.)
	private $htmlOptions;

	# Constructor accepts options and saves them in the object
	function __construct($htmlOptions, $jsFlags) {
		$this->jsFlagState = $jsFlags;
		$this->htmlOptions = $htmlOptions;
	}


	/*****************************************************************
	* HTML parsers - main parsing function splits up document into
	* component parts ('normal' HTML, scripts and styles)
	******************************************************************/

	function HTMLDocument($input, $insert='', $inject=false, $footer='') {

		if (strlen($input)>65536) {
			if (version_compare(PHP_VERSION, '5.3.7')<=0) {
				ini_set('pcre.backtrack_limit', 1000000);
			}
		}

		#
		# Apply parsing that only needs to be done once..
		#

		# Record the charset
		global $charset;
		
		if (!isset($charset)) {
			$meta_equiv = preg_match('#(<meta[^>]*http\-equiv\s*=[^>]*>)#is', $input, $tmp, PREG_OFFSET_CAPTURE) ? $tmp[0][0] : null;
			if (isset($meta_equiv)) {
				$charset = preg_match('#charset\s*=\s*["\']+([^"\'\s>]*)#is', $meta_equiv, $tmp, PREG_OFFSET_CAPTURE) ? $tmp[1][0] : null;
			}
		}
		if (!isset($charset)) {
			$meta_charset = preg_match('#<meta[^>]*charset\s*=\s*["\']+([^"\'\s>]*)#is', $input, $tmp, PREG_OFFSET_CAPTURE) ? $tmp[1][0] : null;
			if (isset($meta_charset)) {
				$charset = $meta_charset;
			}
		}

		# Remove empty script comments
		$input = preg_replace('#/\*\s*\*/#s', '', $input);

		# Remove conditional comments
		$input = preg_replace('#<\!\-\-\[if \!IE\]>\s*\-\->(.*?)<\!\[endif\]\-\->#s','$1',$input);
		$input = preg_replace('#<\!\-\-\[if.*?<\!\[endif\]\-\->#s','',$input);

		# Prevent websites from calling disableOverride()
		$input = preg_replace('#disableOverride#s', 'disabled___disableOverride', $input);

		# Prevent websites from making STUN requests
		$input = preg_replace('#RTCPeerConnection#s', 'disabled___RTCPeerConnection', $input);

		# Remove titles if option is enabled
		if ( $this->htmlOptions['stripTitle'] || $this->htmlOptions['encodePage'] ) {
			$input = preg_replace('#<title.*?</title>#is', '', $input, 1);
			$input = preg_replace('#<meta[^>]*name=["\'](title|description|keywords)["\'][^>]*>#is', '', $input, 3);
            $input = preg_replace('#<link[^>]*rel=["\'](icon|shortcut icon)["\'][^>]*>#is', '', $input, 2);
		}

		# Remove and record a <base> href
		$input = preg_replace_callback('#<base href\s*=\s*([\\\'"])?((?(1)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^\s"\\\'>]{1,2048}))(?(1)\\1|)[^>]*>#i', 'html_stripBase', $input, 1);

		# Proxy url= values in meta redirects
		$input = preg_replace_callback('#content\s*=\s*(["\\\'])?[0-9]+\s*;\s*url=([\\\'"]|&\#39;)?((?(?<=")[^"]+|(?(?<=\\\')[^\\\']+|[^\\\'" >]+)))(?(2)\\2|)(?(1)\\1|)#i', 'html_metaRefresh', $input, 1);

		# Process forms
		$input = preg_replace_callback('#<form([^>]*)>(.*?)</form>#is', 'html_form', $input);

		# Remove scripts blocks (avoids individual processing below)
		if ( $this->htmlOptions['stripJS'] ) {
			$input = preg_replace('#<script[^>]*>.*?</script>#is', '', $input);
		}


		#
		# Split up the document into its different types and parse them
		#

		# Build up new document into this var
		$new	  = '';
		$offset = 0;

		# Find instances of script or style blocks
		while ( preg_match('#<(s(?:cript|tyle))[^>]*>#i', $input, $match, PREG_OFFSET_CAPTURE, $offset) ) {

			# What type of block is this?
			$block = strtolower($match[1][0]);

			# Start position of content
			$outerStart = $match[0][1];
			$innerStart = $outerStart + strlen($match[0][0]);

			# Determine type of end tag and find it's position
			$endTag	 = "</$block>";
			$innerEnd = stripos($input, $endTag, $innerStart);
			if ($innerEnd===false) {
				$endTag	 = "</";
				$innerEnd = stripos($input, $endTag, $innerStart);
				if ($innerEnd===false) {
					$input = preg_replace('#<script[^>]*>.*?$#is', '', $input);
					break;
				}
			}
			$outerEnd = $innerEnd + strlen($endTag);

			# Parse everything up till here and add to the new document
			$new .= $this->HTML(substr($input, $offset, $innerStart - $offset));

			# Find parsing function
			$parseFunction = $block == 'style' ? 'CSS' : 'JS' ;

			# Add the parsed block
			$new .= $this->$parseFunction(substr($input, $innerStart, $innerEnd - $innerStart));

			# Move offset to new position
			$offset = $innerEnd;

		}

		# And add the final chunk (between last script/style block and end of doc)
		$new .= $this->HTML(substr($input, $offset));

		# Replace input with the updated document
		$input = $new;

		# Make URLs relative
		$input = preg_replace('#=\s*(["\'])?\s*https?://[^"\'>/]*/#i', '=$1/', $input);

		# Encode the page
		if ( $this->htmlOptions['encodePage'] ) {
			$input = encodePage($input);
		}


		# Return new document
		return $input;

	}

	# Parse HTML sections
	function HTML($input) {

		# Removing objects? Follow spec and display inner content of object tags instead.
		if ( $this->htmlOptions['stripObjects'] ) {

			# Remove all object tags (including those deprecated but still common)
			$input = preg_replace('#<(?>object|applet|param|embed)[^>]*>#i', '', $input, -1, $tmp);

			# Found any? Remove the corresponding end tags
			if ( $tmp ) {
				$input = preg_replace('#</(?>object|applet|param|embed)>#i', '', $input, $tmp);
			}

		} else {

			# Parse <param name="movie" value="URL"> tags
			$input = preg_replace_callback('#<param[^>]+value\s*=\s*([\\\'"])?((?(1)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^\s"\\\'>]{1,2048}))(?(1)\\1|)[^>]*>#i', 'html_paramValue', $input);

			# To do: proxy object related URLs

		}

		# Show content within <noscript> tags
		# (preg_ seems to be faster than 2 str_ireplace() calls)
		if ( $this->htmlOptions['stripJS'] ) {
			$input = preg_replace('#</?noscript>#i', '', $input);
		}

		# remove srcset attribute for now
		$input = preg_replace('#srcset\s*=\s*[\\\'"][^"]*[\\\'"]#i', '', $input);

		# Parse onX events
		$input = preg_replace_callback('#\b(on(?<!\.on)[a-z]{2,20})\s*=\s*([\\\'"])?((?(2)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^\s"\\\'>]{1,2048}))(?(2)\\2|)#i', array(&$this, 'html_eventJS'), $input);

		# Parse style attributes
		$input = preg_replace_callback('#style\s*=\s*([\\\'"])?((?(1)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^\s"\\\'>]{1,2048}))(?(1)\\1|)#i', array(&$this, 'html_elementCSS'), $input);

		# Proxy URL attributes - this is the bottleneck but optimized as much as possible
		$input = preg_replace_callback('#(?><[A-Z0-9]{1,15})(?>\s+[^>\s]+)*?\s*(?>(href|src|background|poster)\s*=(?!\\\\)\s*)(?>([\\\'"])?)((?(2)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^ >]{1,2048}))(?(2)\\2|)#i', 'html_attribute', $input);

		# Return changed input
		return $input;

	}

	# Proxy an onX javascript event
	function html_eventJS($input) {
		return $this->htmlOptions['stripJS'] ? '' : $input[1] . '=' . $input[2] . $this->JS($input[3]) . $input[2];
	}

	# Proxy a style="CSS" attribute
	function html_elementCSS($input) {
		return 'style=' . $input[1] . $this->CSS($input[2]) . $input[1];
	}


	/*****************************************************************
	* CSS parser - main parsing function
	* CSS parsing is a complicated by the caching of CSS files. We need
	* to consider (A) cross-domain caching and (B) the unique URLs option.
	*	 A) If possible, use a relative URL so the saved URLs do not explictly
	*		 point to a single domain.
	*	 B) There is a second set of callback functions with "_unique" suffixed
	*		 and these return the original URL to be reparesed.
	******************************************************************/

	# The URLs depend on the unique and path info settings. The type parameter allows
	# us to specify the unique callbacks.
	function CSS($input, $storeUnique=false) {

		# What type of parsing is this? Normally we parse any URLs to redirect
		# back through the proxy but not when storing a cache with unique URLs.
		$type = $storeUnique ? '_unique' : '';

		# CSS needs proxying the calls to url(), @import and src=''
		$input = preg_replace_callback('#\burl\s*\(\s*[\\\'"]?([^\\\'"\)]+)[\\\'"]?\s*\)#i', 'css_URL' . $type, $input);
		$input = preg_replace_callback('#@import\s*[\\\'"]([^\\\'"\(\)]+)[\\\'"]#i', 'css_import' . $type, $input);
		$input = preg_replace_callback('#\bsrc\s*=\s*([\\\'"])?([^)\\\'"]+)(?(1)\\1|)#i', 'css_src' . $type, $input);

		# Make URLs relative
		$input = preg_replace('#https?://[^"\'>/]*/#i', '/', $input);

		# Return changed
		return $input;

	}


	/*****************************************************************
	* Javascript parser - main parsing function
	*
	* The specific parts that need proxying depends on which javascript
	* functions we've been able to override. On first page load, the browser
	* capabilities are tested to see what we can do client-side and the results
	* sent back to us. This allows us to parse only what we have to.
	* If $CONFIG['override_javascript'] is disabled, all commands are parsed
	* server-side. This will use much more CPU!
	*
	* Commands to proxy only if no override at all:
	*	 document.write()
	*	 document.writeln()
	*	 window.open()
	*	 eval()
	*
	* Commands to proxy, regardless of browser capabilities:
	*	 location.replace()
	*	 .innerHTML=
	*
	* Commands to proxy if the extra "watch" flag is set
	* (the browser doesn't support the .watch() method):
	*	 location=
	*	 x.location=
	*	 location.href=
	*
	* Commands to proxy if the extra "setters" flag is set
	* (the browser doesn't support the __defineSetter__() method):
	*	 .src=
	*	 .href=
	*	 .background=
	*	 .action=
	*
	* Commands to proxy if the extra "ajax" flag is set
	* (the browser failed to override the .open() method):
	*	 XMLHttpRequest.open()
	******************************************************************/

	function JS($input) {

		# Stripping?
		if ( $this->htmlOptions['stripJS'] ) {
			return '';
		}

		# Get our flags
		$flags = $this->jsFlagState;

		# Unless we know we don't need to, apply all the browser-specific flags
		if ( ! is_array($this->jsFlagState) ) {
			$flags = array('ajax', 'watch', 'setters');
		}

		# If override is disabled, add a "base" flag
		if ( $this->jsFlagState === null ) {
			$flags[] = 'base';
		}

		# Start parsing!
		$search = array();

		# Create shortcuts to various search patterns:
		#	  "before"	  - matches preceeding character (string of single char) [ignoring whitespace]
		#	  "after"	  - matches next character (string of single char) [ignoring whitespace]
		#	  "id"		  - key for identifying the original match (e.g. if we have >1 of the same key)
		$assignmentPattern	= array('before'	  => '.',				  'after' => '='); 
		$methodPattern			= array('before'	  => '.',				  'after' => '(');
		$functionPattern		= array('after' => '(');

		# Configure strings to search for, starting with always replaced commands
		$search['innerHTML'][] = $assignmentPattern;
		$search['location'][]  = array('after' => '.', 'id' => 'replace()');
			# ^ This is only for location.replace() - other forms are handled later

		# Look for attribute assignments
		if ( in_array('setters', $flags) ) {
			$search['src'][]		= $assignmentPattern;
			$search['href'][]		= $assignmentPattern;
			$search['action'][]		= $assignmentPattern;
			$search['background'][] = $assignmentPattern;
			$search['poster'][] 	= $assignmentPattern;
		}

		# Look for location changes
		# location.href will be handled above, location= is handled here
		if ( in_array('watch', $flags) ) {
			$search['location'][] = array('after' => '=', 'id' => 'assignment');
		}

		# Look for .open() if either AJAX (XMLHttpRequest.open) or
		# base (window.open) flags are present
		if ( in_array('ajax', $flags) || in_array('base', $flags) ) {
			$search['open'][] = $methodPattern;
		}

		# Add the basic code if no override
		if ( in_array('base', $flags) ) {
			$search['eval'][]		= $functionPattern;
			$search['writeln'][]	  = $methodPattern;
			$search['write'][]	= $methodPattern;
		}

		# Set up starting parameters
		$offset			= 0;
		$length			= strlen($input);
		$searchStrings = array_keys($search);

		while ( $offset < $length ) {

			# Start off by assuming no more items (i.e. the next position
			# of interest is the end of the document)
			$commandPos = $length;

			# Loop through the search subjects
			foreach ( $searchStrings as $item ) {

				# Any more instances of this?
				if ( ( $tmp = strpos($input, $item, $offset) ) === false ) {

					# Nope, skip to next item
					continue;

				}

				# If $item is whole word?
				if ( ( $input[$tmp-1] == '_' ) || ctype_alpha($input[$tmp-1]) ) {
				    
				    # No
					continue;

				}

				# Closer to the currently held 'next' position?
				if ( $tmp < $commandPos ) {

					$commandPos = $tmp;
					$command = $item;

				}

			}

			# No matches found? Finish parsing.
			if ( $commandPos == $length ) {
				break;
			}

			# We've found the main point of interest; now use the
			# search parameters to check the surrounding chars to validate
			# the match.
			$valid = false;

			foreach ( $search[$command] as $pattern ) {

				# Check the preceeding chars
				if ( isset($pattern['before']) && str_checkprev($input, $pattern['before'], $commandPos-1) === false ) {
					continue;
				}

				# Check next chars
				if ( isset($pattern['after']) && ( $charPos = str_checknext($input, $pattern['after'], $commandPos + strlen($command), false, false) ) === false ) {
					continue;
				}

				$postCharPos = ($charPos + 1) + strspn($input, " \t\r\n", $charPos + 1);

				# Still here? Match must be OK so generate a match ID
				if ( isset($pattern['id']) ) {
					$valid = $command . $pattern['id'];
				} else {
					$valid = $command;
				}

				break;
			}

			# What we do next depends on which match (if any) we've found...
			switch ( $valid ) {

				# Assigment
				case 'src':
				case 'href':
				case 'background':
				case 'poster':
				case 'action':
				case 'locationassignment':
				case 'innerHTML':

					# Check our post-char position for = as well (could be equality
					# test rather than assignment, i.e. == )
					if ( ! isset($input[$postCharPos]) || $input[$postCharPos] == '=' ) {
						break;
					}

					# Find the end of this statement
					$endPos = analyzeAssign_js($input, $charPos);
					$valueLength = $endPos - $postCharPos;

					# Produce replacement command
					$replacement = sprintf('parse%s(%s)', $command=='innerHTML' ? 'HTML' : 'URL', substr($input, $postCharPos, $valueLength));

					# Adjust total document length as appropriate
					$length += strlen($replacement);

					# Make the replacement
					$input = substr_replace($input, $replacement, $postCharPos, $valueLength);

					# Move offset up to new position
					$offset = $endPos + 10;

					# Go get next match
					continue 2;


				# Function calls - we don't know for certain if these are in fact members of the
				# appropriate objects (window/XMLHttpRequest for .open(), document for .write() and
				# .writeln) so we won't change anything. Main.js still overrides these functions but
				# does nothing with them by default. We add an extra parameter to tell our override
				# to kick in.
				case 'open':
				case 'write':
				case 'writeln':

					# Find the end position (the closing ")" for the function call)
					$endPos = analyze_js($input, $charPos);

					# Insert our additional argument just before that
					$glStr=',"gl"';
					if (strspn($input, ";\n\r\+{}()[]", $charPos) >= ($endPos - $charPos)) {
						$glStr='"gl"';
					}
					$input = substr_replace($input, $glStr, $endPos - 1, 0);

					# Adjust the document length
					$length += strlen($glStr);

					# And move the offset
					$offset = $endPos + strlen($glStr);

					# Get next match
					continue 2;


				# Eval() is a just as easy since we can just wrap the entire thing in parseJS().
				case 'eval':

					# Ensure this is a call to eval(), not anotherfunctionendingineval()
					if ( isset($input[$commandPos-1]) && strpos('abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_', $input[$commandPos-1]) !== false ) {
						break;
					}

					# Find the end position (the closing ")" for the function call)
					$endPos = analyze_js($input, $charPos);
					$valueLength = $endPos - $postCharPos;

					# Generate our replacement
					$replacement = sprintf('parseJS(%s)', substr($input, $postCharPos, $valueLength));

					# Make the replacement
					$input = substr_replace($input, $replacement, $postCharPos, $valueLength);

					# Adjust the document length
					$length += 9;

					# And move the offset
					$offset = $endPos + 9;
					continue 2;


				# location.replace() is a tricky one. We have the position of the char
				# after . as $postCharPos and need to ensure we're calling replace(), 
				# then parse the entire URL
				case 'locationreplace()':

					# Validate the match
					if ( ! preg_match('#\Greplace\s*\(#', $input, $tmp, 0, $postCharPos) ) {
						break;
					}

					# Move $postCharPos to inside the brackets of .replace()
					$postCharPos += strlen($tmp[0]);

					# Find the end position (the closing ")" for the function call)
					$endPos = analyze_js($input, $postCharPos);
					$valueLength = $endPos - $postCharPos;

					# Generate our replacement
					$replacement = sprintf('parseURL(%s)', substr($input, $postCharPos, $valueLength));

					# Make the replacement
					$input = substr_replace($input, $replacement, $postCharPos, $valueLength);

					# Adjust the document length
					$length += 9;

					# And move the offset
					$offset = $endPos + 9;

					continue 2;
			}

			# Still here? A match didn't validate so adjust offset to just after
			# current position
			$offset = $commandPos + 1;

		}

		# Ignore document.domain
		$input = str_replace('document.domain', 'ignore', $input);

		# Return changed
		return $input;

	}

}


/*****************************************************************
* HTML callbacks
******************************************************************/

# Remove and record the <base> href
function html_stripBase($input) {
	global $base;
	$base = $input[2];
	return '';
}

# Proxy the location of a meta refresh
function html_metaRefresh($input) {
	return str_replace($input[3], Http_proxy::proxyURL($input[3]), $input[0]);
}

# Proxy URL in <param name="movie" value="URL">
function html_paramValue($input) {

	# Check for a name="movie" tag
	if ( stripos($input[0], 'movie') === false ) {
		return $input[0];
	}

	return str_replace($input[2], Http_proxy::proxyURL($input[2]), $input[0]);
}

# Process forms - the query string is used by the proxy script
# and GET data needs to be encoded anyway. We convert all GET
# forms to POST and then the proxy script will forward it properly.
function html_form($input) {

	# Check for a given method
	if ( preg_match('#\bmethod\s*=\s*["\\\']?(get|post)["\\\']?#i', $input[1], $tmp) ) {

		# Not POST?
		if ( strtolower($tmp[1]) != 'post' ) {

			# Convert to post and flag as a conversion
			$input[1] = str_replace($tmp[0], 'method="post"', $input[1]);
			$converted = true;

		}

	} else {

		# Append a POST method (no method given and GET is default)
		$input[1] .= ' method="post"';
		$converted = true;

	}

	# Prepare the extra input to insert
	$add = empty($converted) ? '' : '<input type="hidden" name="convertGET" value="1">';

	# To do: javascript onsubmit event to immediately redirect to the appropriate
	# location using GET data, without an intermediate POST to the proxy script.

	# Proxy the form action
	$input[1] = preg_replace_callback('#\baction\s*=\s*([\\\'"])?((?(1)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^\s"\\\'>]{1,2048}))(?(1)\\1|)#i', 'html_formAction', $input[1]);

	# What type of form is this? Due to register_globals support, PHP converts
	# a number of characters to _ in incoming variable names. To get around this,
	# we can use the raw post data from php://input but this is not available
	# for multipart forms. Instead we must encode the input names in these forms.
	if ( stripos($input[1], 'multipart/form-data') ) {

		$input[2] = preg_replace_callback('#name\s*=\s*([\\\'"])?((?(1)(?(?<=")[^"]{1,2048}|[^\\\']{1,2048})|[^\s"\\\'>]{1,2048}))(?(1)\\1|)#i', 'html_inputName', $input[2]);

	}

	# Return updated form
	return '<form' . $input[1] . '>' . $add . $input[2] . '</form>';

}

# Proxy the action="URL" value in forms
function html_formAction($input) {
	return 'action=' . $input[1] . Http_proxy::proxyURL($input[2]) . $input[1];
}

# Encode input names
function html_inputName($input) {
	return 'name=' . $input[1] . inputEncode($input[2]) . $input[1];
}

# Proxy URL values in attributes
function html_attribute($input) {

	# Is this an iframe?
	$flag = stripos($input[0], 'iframe') === 1 ? 'frame' : '';

	# URL occurred as value of an attribute and should have been htmlspecialchar()ed
	# We need to do the job of the browser and decode before proxying.
	return str_replace($input[3], htmlspecialchars(Http_proxy::proxyURL(htmlspecialchars_decode($input[3]), $flag)), $input[0]);
}

# Flag frames in a frameset so only the first one shows the mini-form.
# This could be done in the above callback but adds extra processing
# when 99% of the time, it won't be needed.
function html_flagFrames($input) {

	static $addFlag;

	# If it's the first frame, leave it but set the flag var
	if ( ! isset($addFlag) ) {
		$addFlag = true;
		return $input[0];
	}

	# Add the frame flag
	$newURL = $input[2] . ( strpos($input[2], '?') ? '&amp;f=frame' : 'fframe/');

	return str_replace($input[2], $newURL, $input[0]);

}


/*****************************************************************
* CSS callbacks
******************************************************************/

# Proxy CSS url(LOCATION)
function css_URL($input) {

	return 'url(' . Http_proxy::proxyURL(trim($input[1])) . ')';
}

# Proxy CSS @import "URL"
function css_import($input) {
	return '@import "' . Http_proxy::proxyURL($input[1]) . '"';
}

# Proxy CSS src=
function css_src($input) {
	return 'src=' . $input[1] . Http_proxy::proxyURL($input[2]) . $input[1];
}

# Callbacks for use with unique URLs and cached CSS
# The <UNIQUE[]URL> acts as a marker for quick and easy processing later

# Unique CSS url(LOCATION)
function css_URL_unique($input) {
	return 'url(<UNIQUE[' . Http_proxy::absoluteURL($input[1],'') . ']URL>)';
}

# Unique CSS @import "URL"
function css_import_unique($input) {
	return '@import "<UNIQUE[' . Http_proxy::absoluteURL($input[1]) . ']URL>"';
}

# Unique CSS src=
function css_src_unique($input) {
	return 'src=' . $input[1] . '<UNIQUE[' . Http_proxy::absoluteURL($input[2]) . ']URL>' . $input[1];
}


/*****************************************************************
* Helper functions
******************************************************************/

# Take a string, and check that the next non-whitespace char is the
# passed in char (X). Return false if non-whitespace and non-X char is
# found. Otherwise, return the position of X.
# If $inverse is true, the next non-whitespace char must NOT be in $char
# If $pastChar is true, ignore whitespace after finding X and return
# the position of the last post-X whitespace char.
function str_checknext($input, $char, $offset, $inverse = false, $pastChar = false) {

	for ( $i = $offset, $length = strlen($input); $i < $length; ++$i ) {

		# Examine char
		switch ( $input[$i] ) {

			# Ignore whitespace
			case ' ':
			case "\t":
			case "\r":
			case "\n":
				break;

			# Found the passed char
			case $char:

				# $inverse means we do NOT want this char
				if ( $inverse ) {
					return false;
				}

				# Move past this to the next non-whitespace?
				if ( $pastChar ) {
					++$i;
					return $i + strspn($input, " \t\r\n", $i);
				}

				# Found desired char, no $pastChar, just return  X offset
				return $i;

			# Found non-$char non-whitespace
			default:

				# This is the desired result if $inverse
				if ( $inverse ) {
					return $i;
				}

				# No $inverse, found a non-$char, return false
				return false;

		}

	}

	return false;

}


# Same as above but go backwards
function str_checkprev($input, $char, $offset, $inverse = false) {

	for ( $i = $offset; $i > 0; --$i ) {

		# Examine char
		switch ( $input[$i] ) {

			# Ignore whitespace
			case ' ':
			case "\t":
			case "\r":
			case "\n":
				break;

			# Found char
			case $char:
				return $inverse ? false : $i;

			# Found non-$char char
			default:
				return $inverse ? $i : false;

		}
	}

	return $inverse;

}


# Analyze javascript and return offset positions.
# Default is to find the end of the statement, indicated by:
#	 (1) ; while not in string
#	 (2) newline which, if not there, would create invalid syntax
#	 (3) a closing bracket (object, language construct or function call) for which
#		  no corresponding opening bracket was detected AFTER the passed offset
# If (int) $argPos is true, we return an array of the start and end position
# for the nth argument, where n = $argPos. The $start position must be just inside
# the parenthesis of the function call we're interested in.
function analyze_js($input, $start, $argPos = false) {

	# Add , if looking for an argument position
	if ( $argPos ) {
		$currentArg = 1;
	}

	# Loop through the input, stopping only at special chars
	for ( $i = $start, $length = strlen($input), $end = false, $openObjects = $openBrackets = $openArrays = 0;
			$end === false && $i < $length;
			++$i ) {
		$char = $input[$i];

		switch ( $char ) {

			# Starting string delimiters
			case '"':
			case "'":

				if ( $input[$i-1] == '\\' ) { 
					break;
				}

				# Skip straight to end of string
				# Find the corresponding end delimiter and ensure it's not escaped
				while ( ( $i = strpos($input, $char, $i+1) ) && $input[$i-1] == '\\' );

				# Check for false, in which case we assume the end is the end of the doc
				if ( $i === false ) {
					break 2;
				}

				break;

			# End of operation?
			case ';':
				$end = $i;
				break;

			# New lines
			case "\n":
			case "\r":
				# Newlines are OK if occuring within an open brackets, arrays or objects.
				if ( $openObjects || $openBrackets || $openArrays || $argPos ) {
					break;
				}

				# Newlines are also OK if followed by an opening function OR concatenation
				# e.g. someFunc\n(params) or someVar \n + anotherVar
				# Find next non-whitespace char position
				$tmp = $i + strspn($input, " \t\r\n", $i+1);

				# And compare to allowed chars
				if ( isset($input[$tmp+1]) && ( $input[$tmp+1] == '(' || $input[$tmp+1] == '+' ) ) {
					$i = $tmp;
					break;
				}

				# Newline not indicated as OK, set the end to here
				$end = $i;
				break;

			# Concatenation
			case '+':
				# Our interest in the + operator is it's use in allowing an expression
				# to span multiple lines. If we come across a +, move past all whitespace,
				# including newlines (which would otherwise indicate end of expression).
				$i += strspn($input, " \t\r\n", $i+1);
				break;

			# Opening chars (objects, parenthesis and arrays)
			case '{':
				++$openObjects;
				break;
			case '(':
				++$openBrackets;
				break;
			case '[':
				++$openArrays;
				break;

			# Closing chars - is there a corresponding open char?
			# Yes = reduce stored count. No = end of statement.
			case '}':
				$openObjects	? --$openObjects	 : $end = $i;
				break;
			case ')':
				$openBrackets	? --$openBrackets	 : $end = $i;
				break;
			case ']':
				$openArrays		? --$openArrays	 : $end = $i;
				break;

			# Commas - tell us which argument it is
			case ',':

				# Ignore commas inside other functions or whatnot
				if ( $openObjects || $openBrackets || $openArrays ) {
					break;
				}

				# End now
				if ( $currentArg == $argPos ) {
					$end = $i;
				}

				# Increase the current argument number
				++$currentArg;

				# If we're not after the first arg, start now?
				if ( $currentArg == $argPos ) {
					$start = $i+1;
				}

				break;

		}

	}

	# End not found? Use end of document
	if ( $end === false ) {
		$end = $length;
	}

	# Return array of start/end
	if ( $argPos ) {
		return array($start, $end);
	}

	# Return end
	return $end;

}
function analyzeAssign_js($input, $start) {

	# Loop through the input, stopping only at special chars
	for ( $i = $start, $length = strlen($input), $end = false, $openObjects = $openBrackets = $openArrays = 0;
			$end === false && $i < $length;
			++$i ) {
		$char = $input[$i];

		switch ( $char ) {

			# Starting string delimiters
			case '"':
			case "'":

				if ( $input[$i-1] == '\\' ) { 
					break;
				}

				# Skip straight to end of string
				# Find the corresponding end delimiter and ensure it's not escaped
				while ( ( $i = strpos($input, $char, $i+1) ) && $input[$i-1] == '\\' );

				# Check for false, in which case we assume the end is the end of the doc
				if ( $i === false ) {
					break 2;
				}

				break;

			# End of operation?
			case ';':
				$end = $i;
				break;

			# New lines
			case "\n":
			case "\r":
				# Newlines are OK if occuring within an open brackets, arrays or objects.
				if ( $openObjects || $openBrackets || $openArrays ) {
					break;
				}
				break;

			# Concatenation
			case '+':
				# Our interest in the + operator is it's use in allowing an expression
				# to span multiple lines. If we come across a +, move past all whitespace,
				# including newlines (which would otherwise indicate end of expression).
				$i += strspn($input, " \t\r\n", $i+1);
				break;

			# Opening chars (objects, parenthesis and arrays)
			case '{':
				++$openObjects;
				break;
			case '(':
				++$openBrackets;
				break;
			case '[':
				++$openArrays;
				break;

			# Closing chars - is there a corresponding open char?
			# Yes = reduce stored count. No = end of statement.
			case '}':
				$openObjects	? --$openObjects	 : $end = $i;
				break;
			case ')':
				$openBrackets	? --$openBrackets	 : $end = $i;
				break;
			case ']':
				$openArrays		? --$openArrays	 : $end = $i;
				break;

			# Commas - tell us which argument it is
			case ',':

				# Ignore commas inside other functions or whatnot
				if ( $openObjects || $openBrackets || $openArrays ) {
					break;
				}

				# End now
				$end = $i;
				break;

		}

	}

	# End not found? Use end of document
	if ( $end === false ) {
		$end = $length;
	}

	# Return end
	return $end;

}

/*****************************************************************
* Page encoding functions
******************************************************************/

# Encode page - splits into HTML/script sections and encodes HTML
function encodePage($input) {

	# Look for script blocks
#	if ( preg_match_all('#<(?:script|style).*?</(?:script|style)>#is', $input, $scripts, PREG_OFFSET_CAPTURE) ) { # not working
	if ( preg_match_all('#<script.*?</script>#is', $input, $scripts, PREG_OFFSET_CAPTURE) ) {

		# Create starting offset - only start encoding after the <head>
		# as this seems to help browsers cope!
		$offset = preg_match('#<body[^>]*>(.)#is', $input, $tmp, PREG_OFFSET_CAPTURE) ? $tmp[1][1] : 0;
		$new	  = $offset ? substr($input, 0, $offset) : '';

		# Go through all the matches
		foreach ( $scripts[0] as $id => $match ) {

			# Determine position of the preceeding non-script block
			$end	  = $match[1] ? $match[1]-1 : 0;
			$start  = $offset; 
			$length = $end - $start;

			# Add encoded block to page if there is one
			if ($length && $length>0) {
				$new .= "\n\n\n<!--start encode block-->\n";
				$new .= encodeBlock(substr($input, $start, $length));
				$new .= "\n<!--end encode block-->\n\n\n";
			}

			# Add unencoded script to page
			$new .= "\n\n\n<!--start unencoded block-->\n";
			$new .= $match[0];
			$new .= "\n<!--end unencoded block-->\n\n\n";

			# Move offset up
			$offset = $match[1] + strlen($match[0]);
		}

		# Add final block
		if ( $remainder = substr($input, $offset) ) {
			$new .= encodeBlock($remainder);
		}

		# Update input with new
		$input = $new;

	} else {
		# No scripts is easy - just encode the lot
		$input = encodeBlock($input);
	}

	# Return the encoded page
	return $input;
}

# Encode block - applies the actual encoding
# note - intended to obfustate URLs and HTML source code. Does not provide security. Use SSL for actual security.
function encodeBlock($input) {
	global $charset;
	$new='';

	if (isset($charset)) {
		$charset=strtolower($charset);
		if (function_exists('mb_convert_encoding')) {
			$input=mb_convert_encoding($input, 'HTML-ENTITIES', $charset);
		}
	}

	# Return javascript decoder
	return '<script type="text/javascript">document.write(arcfour(ginf.enc.u,base64_decode(\'' . arcfour('encrypt',$GLOBALS['unique_salt'],$input) . '\')));</script>';
}
