<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Share_file extends CI_Model {

    public function __construct(){

        parent::__construct();
    }

    public function getFiles($q, $type, $ord, $page, $perPage = 20){

		$q=str_replace(array(',','，','|',),' ',$q);
		$keywords=explode(" ",$q);
		
		foreach ($keywords as $key) {

			if($where==null)
				$where="title like '%$key%'";
			else
				$where.=" OR title like '%$key%'";
		}
		
		if($type!=''){

			if($type=='dir')
				$where.='AND isdir=1';
			else if($type=='abm')
				$where.='AND isdir=2';
			else
				$where.="AND file_type='$type'";
		}

		if($ord=='cdown')
			$order='d_cnt desc';
		else
			$order='feed_type desc';

		$offset = ($page-1)*$perPage;

		$results = $this->db->query("SELECT SQL_CALC_FOUND_ROWS * WHERE $where order by $order limit $offset,$perPage");
		$found = $this->db->query('SELECT FOUND_ROWS()');
		$found = $found->row();
		return array('results'=>$results->result_array(),'found_rows'=>$found['0']['found_rows()']);
	}

	public function search($q, $type, $ord, $page, $xunsearch=true, $perPage=20){

		if(!$xunsearch){
			return $this->getFiles($q,$type,$ord,$page,$perPage);
		}
		$CI = &get_instance();
		$CI->load->helper('xs');
		$xs = new XS('pan');
		$where="title:$q";
		
		if($type!=''&&$type!='all'){
			if($type=='dir'){
				$where.=' isdir:1';
			}else if($type=='abm'){
				$where.=' isdir:2';
			}
			else{
				$where.=" file_type:$type";
			}
		}
		
		$search = $xs->search;
		
		$search->setQuery($where);
		$search->setLimit($perPage, ($page-1)*$perPage); // 设置返回结果最多为 5 条，并跳过前 10 条
		$results = $search->search();
		$found = $search->count(); // 获取搜索结果的匹配总数估算值
		return array('results'=>$results,'found_rows'=>$found);
	}
}