<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
load_template('public/header',array(
  'title'        => $key_word.' - 搜索结果 ',
  'keywords'     => $key_word,
  'description'  => $key_word.'的搜索结果，找到约 '.$found.'条结果<nobr> （用时 '.$time_used.'秒）'
));
?>
<style>
#top-search-bar{border-collapse:separate;border-spacing:2px;background:#f1f1f1;border-bottom:1px solid #e5e5e5;padding:10px 0;position:relative;}
#logo{height:35px;width:115px;display:block;left:10px;top:20px;position:absolute;background:url(<?php echo base_url('static/img/logo-bar.png') ?>);}
#search_type{ border-bottom:1px solid #EAEAEA;}
#search_type a{display:inline-block;line-height:40px;padding:5px;text-decoration:none;color:#777; margin-right:10px;}
#search_type .active{border-bottom:3px solid #dd4b39;color:#dd4b39;font-weight:bold;}
#res{color:#545454;}
#res .g{margin-bottom:23px;margin-top:0;zoom:1;}
a:visited{color:#61C;}
a:link{color:#1a0dab;cursor:pointer;}
#res .r{line-height:1.2;}
#res h3{font-size:18px;}
#tip{margin-top:15px;margin-bottom:20px;color:#999;font-size:13px;}
#nav td{padding:5px;}
.container img,._YQd,.container .action-menu{display:none;}
body{font-family: arial,sans-serif;}
@media (min-width:992px) {.container {margin-left:130px;}}
.link a{color:#545454}
@media (max-width:992px){#logo{display:none;}}
#s-bar{border-bottom:1px solid #ebebeb;}
#search-bar a{padding:5px 10px;margin-right:5px;}
.filter{margin-top:10px;background:#eee;padding:3px 0;border-left:2px solid #666}
.filter .label{color:#666}
.filter .label:hover{color:red}
.filter .label-success{color:#fff}
#res .attr{margin-right:5px;}
.icon{line-height:1.1;position:relative;top:7px;}
.icon-16 {display:inline-block;width:26px;height:22px;
background:url(http://s1.pan.bdstatic.com/yun-static/common-cdn/images/clouddisk-ui/sprite_list_icon.gif) 9999px 9999px no-repeat;
background-position:-421px -83px;
}
.icon-16-torrent{background-position:-453px -83px;}
.icon-16-video{background-position:-229px -83px;}
.icon-16-pak{background-position:-357px -83px;}
.icon-16-dir{background-position:-5px -3px;}
.icon-16-abm{background-position:-43px -27px;}
.icon-16-img{background-position:-5px -83px;}
.icon-16-mic{background-position:-197px -83px;}
.icon-16-exe{background-position:-326px -83px;}
.icon-16-doc{background-position:-485px -83px;}
.icon-16-other{background-position:-261px -83px;}
.icon-16-word{background-position:-102px -83px;}
.icon-16-xls{background-position:-133px -83px;}
.icon-16-android{background-position:-389px -83px;}
.icon-16-pdf{background-position:-70px -83px;}
</style>
<div id="top-search-bar">
	<a id="logo" href="/" title="返回网盘搜索"></a>
    <div class="container">
    	<div class="row">
        	<div class="col-lg-7">
              <?php load_template('public/search-form-result',array('keyword'=>$key_word)) ?>
          </div><!-- /.col-lg-6 -->  
        </div>
    </div>
</div>
<?php load_template('public/search-bar', array('keyword'=>$key_word)) ?>
<div class="container">
  <div class="row">	
    <div id="main" class="col-md-6">
        <div class="filter">
          <ul>
            <li>
              <span class="label">类型：</span>
              <a id="type-all" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order" ?>" class="label">全部</a>
              <a id="type-0" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=0" ?>" class="label">视频</a>
              <a id="type-1" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=1" ?>"  class="label">图片</a>
              <a id="type-2" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=2" ?>" class="label">文档</span>
              <a id="type-3" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=3" ?>" class="label">音乐</a>
              <a id="type-4" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=4" ?>" class="label">压缩包</a>
              <a id="type-5" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=5" ?>" class="label">软件</a>
              <a id="type-dir" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=dir" ?>" class="label">文件夹</a>
              <a id="type-abm" href="/search?q=<?php echo urlencode("$key_word")."&ord=$order&type=abm" ?>" class="label">专辑</a>
            </li>
            <!--
            <li>
              <span class="label">排序：</a>
              <a href="/search?q={$key_word|urlencode}&type={$type}&ord=time" class="label">最近分享</a>
              <a href="/search?q={$key_word|urlencode}&type={$type}&ord=cdown" class="label">下载量</a>
            </li>
          -->
          </ul>
        </div>
        <div id="tip">
            找到约 <?php echo $found?> 条结果<nobr> （用时 <?php echo $time_used?>秒）&nbsp;</nobr>
            <a style="float:right" href="http://www.ibying.com/search?q=<?php echo urlencode("$key_word")."&type=pan"?>">使用深度搜索</a>
        </div>
        <style type="text/css">
              .search-tip{margin-top:20px;line-height:200%;}
              .search-tip b{color:#f00}
        </style>
        <?php if($results==null||count($results)==0):?>
            <div class="search-tip">
                找不到和您的查询 "<b><?php echo $key_word?></b>" 相符的内容或信息。</br>
                建议：
                <ul style="margin-left:20px; list-style-type:disc">
                    <li>请检查输入字词有无错误</li>
                    <li>请尝试其他的查询词</li>
                    <li>请改用较常见的字词</li>
                    <li>请减少查询字词的数量</li>
                </ul>
            </div>
        <?php else:?>
          <ul id="res">
            <?php foreach($results as $i):?>
              <li class="g">
                <h3>
                  <span class="icon icon-16 <?php echo $i['icon']?>"></span>
                  <a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a>
                </h3>
                <p>
                  <span class="attr">分享时间：<?php echo $i['feed_time']?></span>
                  <?php if ($i['isdir']==0):?>
                    <span class="attr">大小：<?php echo $i['size']?></span>
                  <?php endif;?>
                  <?php if ($i['d_cnt'] > 0):?>
                    <span class="attr">下载：<?php echo $i['d_cnt']?>次</span>
                  <?php endif;?>
                </p>
              </li>
            <?php endforeach;?>
          </ul>
          <?php echo $pager?>
        <?php endif;?>
    </div>
    <br/>
    <br/>
    <br/>
    <div class="col-md-4 col-md-offset-1"></div>
  </div>
</div>

<?php 
load_template('public/ads');
load_template('public/js');
?>
<script type="text/javascript">
$(function(){
  $('#type-<?php echo $type ?>').addClass('label-success');
}) 
</script>
<?php
load_template('public/footer');
?>