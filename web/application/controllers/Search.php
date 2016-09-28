<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Search extends CI_Controller {
	
	private $keyword = '';
	
	#访问来源	sf(搜索框)、tab...?
	private $from = '';
	
	#类型
	private $type;

	private $types=array(
		'视频'			=> '0',
		'图片'			=> '1',
		'文档/电子书'	=> '2',
		'音乐'			=> '3',
		'压缩包'		=> '4',
		'软件'			=> '5',
		'文件夹'		=> 'dir',
		'专辑'			=> 'abm',
		'全部'	 		=> 'all'
	);

	private $icons=array(
		'0'		=>'icon-16-video',
		'1'		=>'icon-16-img',
		'2'		=>'icon-16-doc',
		'3'		=>'icon-16-mic',
		'4'		=>'icon-16-pak',
		'5'		=>'icon-16-exe',
		'-1'	=>'icon-16-other'
	);

	/*********************************\
	 
	 				action
	
	\*********************************/
	
	public function index() {

		$this->_search();
	}

	public function q($kw = '') {

		$kw=urldecode_base64($kw);
		$this->_search($kw);
	}

	#搜索历史记录
	public function history(){

		$this->load->database();
		$this->load->helper('pager');
		$perPage = 40;
		$nowPage = isset($_GET['p']) ? $_GET['p'] : 1;
		$offset  = ($nowPage-1) * $perPage;

		$results = $this->db->query("select * from search order by post_date desc limit $offset,40");
		$total 	 = $this->db->count_all("search");
		$pager	 = pager($nowPage,$total,'perPage=40');

		load_template('search/history',array('pager'=>$pager, 'results'=>$results->result_array()));
	}

	/********************************************\
	 
	 					辅助函数
	
	\********************************************/
	#判断当前用户是否是爬虫
	private function _isCrawler() {

	    $agent= strtolower($_SERVER['HTTP_USER_AGENT']); 
	    
	    if (!empty($agent)) { 

	        $spiderSite= array( 
	            "TencentTraveler", 
	            "Baiduspider+", 
	            "BaiduGame", 
	            "Googlebot", 
	            "msnbot", 
	            "Sosospider+", 
	            "Sogou web spider", 
	            "ia_archiver", 
	            "Yahoo! Slurp", 
	            "YoudaoBot", 
	            "Yahoo Slurp", 
	            "MSNBot", 
	            "Java (Often spam bot)", 
	            "BaiDuSpider", 
	            "Voila", 
	            "Yandex bot", 
	            "BSpider", 
	            "twiceler", 
	            "Sogou Spider", 
	            "Speedy Spider", 
	            "Google AdSense", 
	            "Heritrix", 
	            "Python-urllib", 
	            "Alexa (IA Archiver)", 
	            "Ask", 
	            "Exabot", 
	            "Custo", 
	            "OutfoxBot/YodaoBot", 
	            "yacy", 
	            "SurveyBot", 
	            "legs", 
	            "lwp-trivial", 
	            "Nutch", 
	            "StackRambler", 
	            "The web archive (IA Archiver)", 
	            "Perl tool", 
	            "MJ12bot", 
	            "Netcraft", 
	            "MSIECrawler", 
	            "WGet tools", 
	            "larbin", 
	            "Fish search", 
	        ); 
	        foreach($spiderSite as $val) { 
	            $str = strtolower($val); 
	            if (strpos($agent, $str) !== false) { 
	                return true; 
	            } 
	        } 
	    } else{

	        return false; 
	    } 
	} 

	private function _search($kw='', $type='', $order=''){

		$time_start=microtime();

		$kw=trim($kw);
		$type=trim($type);

		$uip=$_SERVER['REMOTE_ADDR'];

		$this->keyword = $kw == '' ? $this->input->get('q') : $kw;
		$this->type = $type == '' ? $this->input->get('type',true) : $type;
		$this->order = $order == '' ? $this->input->get('order',true) : $order;
		$page = $this->input->get('p');
		$page=(int)$page;

		if($page<=0)
			$page=1;

		if(!$this->keyword){
			header('Location: '.site_url());
			exit;
		}

		if($this->type==='' || !in_array($this->type, $this->types)){
			
			$this->type = 'all';
		}
		
		/*if(!$this->_isCrawler())
			$this->_logSearch($this->keyword);
		*/

		$this->load->model('Share_file');
		
		$results	=$this->Share_file->search($this->keyword,$this->type,$this->order,$page);
		$files		=$results['results'];
		$found_rows	=$results['found_rows'];
		
		$this->load->helper('pager');
		$pager	 	= pager($page,$found_rows,'perPage=20');

		$results=array();

		foreach ($files as $item) {
			
			$shorturl=$item['shorturl'];
			$feed_type=$item['feed_type'];
			
			if($shorturl!=''){
				$link='http://pan.baidu.com/s/'.$shorturl;
			}else if($feed_type=='album'){
				$link='http://yun.baidu.com/pcloud/album/info?uk='.$item['uk'].'&album_id='.$item['shareid'];
			}else{
				$link='http://yun.baidu.com/share/link?uk='.$item['uk'].'&shareid='.$item['shareid'];
			}

			$file_type=$item['file_type'];
			$ext=$item['ext'];
			$isdir=$item['isdir'];
			
			if($isdir=='1'){
				$icon='icon-16-dir';
			}elseif($isdir=='2'){
				$icon='icon-16-abm';
			}else{
				switch ($ext) {
					case '.torrent':
						$icon='icon-16-torrent';
						break;
					case '.doc':
					case '.docx':
						$icon='icon-16-word';
						break;
					case '.xls':
					case '.xlsx':
						$icon='icon-16-xls';
						break;
					case '.apk':
						$icon='icon-16-android';
						break;
					case '.pdf':
						$icon='icon-16-pdf';
						break;
					default:
						$icon=$this->icons[$file_type];
						break;
				}
			}

			$results[]=array(
				'title'=>$item['title'],
				'feed_time'=>timeago($item['feed_time']),
				'd_cnt'=>$item['d_cnt'],
				'size'=>$this->_getSize($item['size']),
				'link'=>$link,
				'isdir'=>$item['isdir'],
				'icon'=>$icon
			);
		}

		$time_end=microtime();
		$time_used=$time_end-$time_start;

		$data = array(
			'pager' 	=> $pager,
			'found' 	=> $found_rows,
			'key_word'	=> $this->keyword,
			'results' 	=> $results,
			'type' 		=> $this->type,
			'order' 	=> $this->order,
			'time_used' => $time_used
		);

		load_template('search/result',$data);
	}

	private function _logSearch($keyword){

		$this->load->database();

		$data = array(
			'key_word'	=> $keyword,
			'type' 		=> $this->type,
			'ip' 		=> $this->input->server('REMOTE_ADDR', true),
			'useragent' => $this->input->server('HTTP_USER_AGENT', true),
			'from_url' 	=> $this->input->server('HTTP_REFERER', true),
			'post_date' => time(),
			'from' 	=> $this->input->get('from')
		);

		$sql=$this->db->insert_string('search', $data);
		$this->db->query($sql);
	}

	private function _getSize($byte){

		$b = array (         
	        1024 * 1024 * 1024			=>  'G ('.date('m-d', $byte).')',
	        1024 * 1024					=>  'M',
	        1024						=>  'K',
	        1							=>  'B'
	    );
	    foreach ($b as $b_num => $b_str) {
	        $d = $byte / $b_num;
	        if ($d >= 1) {
	            $r = round($d);
	            return $r . $b_str;
	        }
	    };
	}
}