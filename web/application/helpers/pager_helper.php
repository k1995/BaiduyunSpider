<?php
/**
*
* 分页 Helpers 扩展函数
* @author:yuking
* @copyright:2015/11/14
*
**/
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('pager')){

	function pager($now=null,$rows, $args = ''){

		if($now === null){
			return '';
		}

		$defaults = array(
			'perPage'  => 20,
			#分页导航显示的页数
			'showPage' => 10
		);
		$args = wp_parse_args($args, $defaults);
		$args=(object)$args;
		$pager = '<nav><ul class="pagination">';
		$totalPage = ceil($rows / $args->perPage);
		$middlePage = ceil($args->showPage/2);

		#如果是前几页
		if($now <= $middlePage||$totalPage<=$args->showPage)
			$first = 1;		
		#如果是后几页
		elseif(($totalPage-$now)<$middlePage)
			$first = $now-($showPage-($totalPage - $now))+1;
		else
			$first = $now -$middlePage;
		

		for($i=0;$i<$args->showPage;$i++){


			$curPage = $first+$i;
			if($curPage > $totalPage)
				break;
			if($curPage == $now){
				$pager .= '<li class="active"><a href="#">'.$curPage.'</a></li>';
			}else{
				$pager .= '<li><a href="?p='.$curPage.'">'.$curPage.'</a></li>';
			}
		}
		$pager .= '</ul></nav>';
		return $pager;
	}
}
