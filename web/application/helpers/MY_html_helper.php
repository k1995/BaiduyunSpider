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

if ( ! function_exists('load_template'))
{
	function load_template($name,$args='')
	{
		$CI = & get_instance();
		$args = wp_parse_args($args);
		$CI->load->view($name,$args);
	}
}
