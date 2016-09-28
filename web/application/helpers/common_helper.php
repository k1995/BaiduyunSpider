<?php
/**
*
* HTML Helpers 扩展函数
* @author:yuking
* @copyright:2015/10/29
*
**/
defined('BASEPATH') OR exit('No direct script access allowed');

// ------------------------------------------------------------------------

function wp_parse_args( $args, $defaults = '' )
{
	
	if ( is_object( $args ) )
		$r = get_object_vars( $args );
	elseif ( is_array( $args ) )
		$r =& $args;
	else
		wp_parse_str( $args, $r );
	
	if ( is_array( $defaults ) )
		return array_merge( $defaults, $r );

	return $r;
}

// ------------------------------------------------------------------------

function wp_parse_str( $string, &$array ) {

	parse_str( $string, $array );
	if ( get_magic_quotes_gpc() )
		$array = stripslashes_deep( $array );
}

// ------------------------------------------------------------------------

function stripslashes_deep($value) {

	if ( is_array($value) ) 
	{
		$value = array_map('stripslashes_deep', $value);
	} elseif ( is_object($value) ) 
	{
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) 
		{
			$value->{$key} = stripslashes_deep( $data );
		}
	} elseif ( is_string( $value ) ) 
	{
		$value = stripslashes($value);
	}
	return $value;
}


// ------------------------------------------------------------------------

function timeago($time){

	$etime=time()-$time;

	if($etime<1)
        return "刚刚";
	
	$interval = array (         

        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $time).')',

        30 * 24 * 60 * 60       =>  '个月前 ('.date('m-d', $time).')',

        7 * 24 * 60 * 60        =>  '周前',//'周前 ('.date('m-d', $time).')',

        24 * 60 * 60            =>  '天前',

        60 * 60                 =>  '小时前',

        60                      =>  '分钟前',

        1                       =>  '秒前'

    );

    foreach ($interval as $secs => $str) {

        $d = $etime / $secs;

        if ($d >= 1) {

            $r = round($d);
            return $r.$str;
        }
    };
}

// ------------------------------------------------------------------------

function wp_is_mobile(){
    static $is_mobile;

    if ( isset($is_mobile) )
        return $is_mobile;

    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        $is_mobile = false;
    } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            $is_mobile = true;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

// ------------------------------------------------------------------------

if ( ! function_exists('urlencode_base64')){

	function urlencode_base64($str){
		
		return urlencode(base64_encode($str));
	}
}

if ( ! function_exists('urldecode_base64')){

	function urldecode_base64($str){
		
		return base64_decode(urldecode($str));
	}
}
