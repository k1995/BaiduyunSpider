<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index(){

		$this->output->cache(30);
		$this->load->database();

		$rs = $this->db->query("SELECT count(*) AS fetched FROM share_file WHERE create_time>".strtotime(date('Y-m-d')));
		#今日收录数
		$rs = $rs->row();
		$fetched = $rs->fetched;

		#如果今日还没有收录，则显示昨日的，主要发生在凌晨的时候
		if(!$fetched){

			$rs = $this->db->query("SELECT count(*) AS fetched FROM share_file WHERE create_time>".strtotime(date('Y-m-d',strtotime('-1 day'))));
			$rs->row();
			#昨日收录数
			$yesday_fetched = $rs->fetched;	
		}else{
			$yesday_fetched = 0;
		}

		$data=array(
			'fetched' 			=> $fetched+10000,
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