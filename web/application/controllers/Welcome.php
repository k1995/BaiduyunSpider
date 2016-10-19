<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *--------------------------------------------		 
 *
 * 	本程序由 [Github 中文社区](http://www.githubs.cn/)发布
 *		  
 * 	Github 仓库: https://github.com/k1995/BaiduyunSpider
 * 
 * 	安装教程：http://www.githubs.cn/post/22
 *
 *  疑问？解答：http://www.githubs.cn/topic/118
 * ----------------------------------------*/

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index(){

		$this->output->cache(30);
		$this->load->database();

		#今日收录数
		$fetched = 0;

		$row = $this->db->select('count(*)')
			->where('create_time>', strtotime(date('Y-m-d')))
			->get('share_file')
			->row_array();

		if($row){
			$fetched = $row['count(*)'];
		}

		#如果今日还没有收录，则显示昨日的，主要发生在凌晨的时候
		if(!$fetched){

			$rs = $this->db->query("SELECT count(*) AS fetched FROM share_file WHERE create_time>".strtotime(date('Y-m-d',strtotime('-1 day'))));
			$rs->row();

			$row = $this->db->select('count(*)')
				->where('create_time>', strtotime(date('Y-m-d',strtotime('-1 day'))))
				->get('share_file')
				->row_array();

			if($row){
				$yesday_fetched = $row['count(*)'];
			}else{
				$yesday_fetched = 0;
			}
		}else{
			$yesday_fetched = 0;
		}

		$data=array(
			'fetched' 			=> $fetched+10000,// 基数10000，为了好看...
			'yesday_fetched'	=> $yesday_fetched+10000,
			'type' 				=> 'all'
		);

		load_template('index',$data);
	}

	public function spiderlist(){

		$this->output->cache(60);
		$this->load->database();

		$data=array();

		$data['videos'] 	= $this->_getFiles(0);
		$data['torrents'] 	= $this->_getFiles(6);
    	$data['documents'] 	= $this->_getFiles(2);
    	$data['musics'] 	= $this->_getFiles(3);
    	$data['packages']	= $this->_getFiles(4);
    	$data['software']	= $this->_getFiles(5);
    	$data['dirs']		= $this->_getFiles(0,1);
    	$data['ambs']		= $this->_getFiles(0,2);

    	load_template('spiderlist',$data);
	}

	private function _getFiles($file_type,$isdir=0){

		$limit = 'limit 0,10';
    	$order = 'order by create_time desc';
    	if(!$isdir)
    		$files=$this->db->query("select * from share_file where file_type=$file_type $order $limit");
    	else
    	   	$files=$this->db->query("select * from share_file where isdir=$isdir $order $limit");
    	
    	$files = $files->result_array();
    	
    	foreach ($files as $key => $item) {
			
			$shorturl=$item['shorturl'];
			$feed_type=$item['feed_type'];
			
			if($shorturl!=''){
				$link='http://pan.baidu.com/s/'.$shorturl;
			}else if($feed_type=='album'){
				$link='http://yun.baidu.com/pcloud/album/info?uk='.$item['uk'].'&album_id='.$item['shareid'];
			}else{
				$link='http://yun.baidu.com/share/link?uk='.$item['uk'].'&shareid='.$item['shareid'];
			}
			$files[$key]['link']=$link;
		}
		return $files;
    }
}