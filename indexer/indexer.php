#!/usr/local/php/bin/php
<?php
# 将$prefix替换为你网站部署的根路径
# 参考 url: http://xunsearch.com/doc/php/guide/start.installation
require '$prefix/application/helpers/xs/lib/XS.php';

class Db{

	protected $conn;
	protected $host='localhost';
	protected $port='3306';
	protected $user='root';
	# 数据库 密码
	protected $pass='';
	protected $charset='utf8';
	protected $db_name='pan';

	public function connect(){

		$this->conn=mysql_connect($this->host.':'.$this->port,$this->user,$this->pass);
		if(!$this->conn){
			die('Could not connect Mysql!\n:'.mysql_error());
		}
		mysql_select_db($this->db_name,$this->conn);
		//$this->query('set names='.$this->charset);
	}

	public function query($sql){
		
		if(!$this->conn)
			$this->connect();
		$rs=mysql_query($sql,$this->conn);
		
		if(!$rs){
			if(!mysql_ping($this->conn)){
				//连接已断开，重新连接
				$this->close();
				$this->connect();
				$rs=$this->query($sql);
			}else{
				die('query error:'.mysql_error());
			}
		}
		return $rs;
	}

	function fetch($sql){

		$res=$this->query($sql);
		if(mysql_num_rows($res)==0)
			return null;

		$return=array();
		
		while(($result=mysql_fetch_assoc($res))){
			$return[]=$result;
		}
		mysql_free_result($res);
		return $return;
	}

	function close(){

		if($this->conn){
			mysql_close($this->conn);
		}
	}
}

class Indexer{

	private $db;
	private $indexer;
	private $xs;

	public function __construct(){

		$this->db=new Db();
		$this->xs = new XS('pan'); // 建立 XS 对象
		$this->indexer = $this->xs->index; // 获取 索引对象
	}

	public function run(){

		while (1) {

			$indexing=$this->db->fetch('select * from share_file WHERE `indexed`=0 limit 0,100');
			$fetched=0;

			foreach ($indexing as $i) {
				$error=0;
				try{
					$doc = new XSDocument();
					$doc->setFields(array(
						'fid'=>$i['fid'],
						'title'=>$i['title'],
						'uk'=>$i['uk'],
						'shorturl'=>$i['shorturl'],
						'isdir'=>$i['isdir'],
						'size'=>$i['size'],
						'shareid'=>$i['shareid'],
						'md5'=>$i['md5'],
						'd_cnt'=>$i['d_cnt'],
						'ext'=>$i['ext'],
						'create_time'=>$i['create_time'],
						'feed_time'=>$i['feed_time'],
						'file_type'=>$i['file_type'],
						'feed_type'=>$i['feed_type'],
					));
					$this->indexer->add($doc);
				}catch (XSException $e){
					echo "document add error\n";
					$error=1;
				}
				if(!$error){
					$fetched++;
					$this->db->query('update share_file set indexed=1 where fid='.$i['fid']);
				}
			}
			
			echo date('Y-m-d H:i:s',time()).":indexed $fetched files \n";
			
			if(count($indexing)<100){
				sleep(60*10);//未索引的文件数量少，则10分钟后再爬
			}else{
				sleep(5);
			}
		}
	}
}

$indexer=new Indexer();
$indexer->run();
