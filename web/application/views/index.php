<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 *--------------------------------------------     
 *
 *  本程序由 Github 中文社区发布
 *      
 *  Github 仓库: https://github.com/k1995/BaiduyunSpider
 * 
 *  安装教程：http://www.githubs.cn/post/22
 *
 *  疑问？解答：http://www.githubs.cn/topic/118
 * ----------------------------------------*/
 
load_template('public/header',array(
  'title'        => $this->config->item('site_title').'，'.$this->config->item('sub_title'),
  'keywords'     => $this->config->item('key_words'),
  'description'  => $this->config->item('site_description')
));
?>
<style type="text/css">
#tagscloud{width:250px;height:260px;position:relative;font-size:12px;color:#333;margin:20px auto 0;text-align:center;}
#tagscloud a{position:absolute;top:0px;left:0px;color:#333;font-family:Arial;text-decoration:none;margin:0 10px 15px 0;line-height:18px;text-align:center;font-size:12px;padding:1px 5px;display:inline-block;border-radius:3px;}
#tagscloud a.tagc1{background:#666;color:#fff;}
#tagscloud a.tagc2{background:#F16E50;color:#fff;}
#tagscloud a.tagc5{background:#006633;color:#fff;}
#tagscloud a:hover{color:#fff;background:#0099ff;}
.f{font-family:"微软雅黑", Arial, sans-serif;margin-right:20px;}
</style>
<br/><br />
<div id="main-container" class="container" style="margin-top:80px; margin-bottom:30px;">
  <center>
    <?php echo img(array('src'=>'static/img/logo_home.png','alt'=>'logo'))?>
  </center>
  <br /><br />
  <div class="row">
    <div class="col-md-6 col-md-offset-3">   
      <div class="input-group col-xs-12">
         <?php load_template('public/search-form','type=all'); ?>
      </div>
    </div>
  </div>
</div>
<center>
  <?php if($fetched>=0):?>
    <span class="f">今日已收录：<b><?php echo $fetched ?></b>(<?php echo date("h:i:s a")?>)</span>
  <?php else:?>
    <span class="f">昨日收录：<b><?php echo $yesday_fetched ?></b>(<?php echo date("h:i:s a")?>)</span>
  <?php endif;?>
  <a class="f" href="/spider-list">最新收录</a>
  <div id="tagscloud">
    <a href="/s/<?php echo urlencode_base64('港囧')?>" class="tagc1">港囧</a>
    <a href="/s/<?php echo urlencode_base64('极客学院')?>" class="tagc2">极客学院</a>
    <a href="/s/<?php echo urlencode_base64('捉妖记')?>" class="tagc5">捉妖记</a>
    <a href="/s/<?php echo urlencode_base64('大圣归来')?>" class="tagc2">大圣归来<a>
    <a href="/s/<?php echo urlencode_base64('夏洛特烦恼')?>" class="tagc2">夏洛特烦恼</a>
    <a href="/s/<?php echo urlencode_base64('解救吾先生')?>" class="tagc1">解救吾先生</a>
    <a href="/s/<?php echo urlencode_base64('煎饼侠')?>" class="tagc2">煎饼侠</a>
    <a href="/s/<?php echo urlencode_base64('碟中谍5')?>" class="tagc5">碟中谍5</a>
    <a href="/s/<?php echo urlencode_base64('进击的巨人')?>" class="tagc2">进击的巨人</a>
    <a href="/s/<?php echo urlencode_base64('PHP教程')?>" class="tagc2">PHP教程</a>
    <a href="/s/<?php echo urlencode_base64('速度与激情')?>" class="tagc5">速度与激情</a>
    <a href="/s/<?php echo urlencode_base64('死神来了')?>" class="tagc2">死神来了</a>
    <a href="/s/<?php echo urlencode_base64('同桌的你')?>" class="tagc1">同桌的你</a>
    <a href="/s/<?php echo urlencode_base64('人在囧途')?>" class="tagc2">人在囧途</a>
    <a href="/s/<?php echo urlencode_base64('明日世界')?>" class="tagc5">明日世界</a>
    <a href="/s/<?php echo urlencode_base64('琅琊榜')?>" class="tagc2">琅琊榜<a>
    <a href="/s/<?php echo urlencode_base64('大主宰')?>" class="tagc2">大主宰</a>
    <a href="/s/<?php echo urlencode_base64('花千骨')?>" class="tagc1">花千骨</a>
    <a href="/s/<?php echo urlencode_base64('左耳')?>" class="tagc2">左耳</a>
    <a href="/s/<?php echo urlencode_base64('斗鱼')?>" class="tagc5">斗鱼</a>
    <a href="/s/<?php echo urlencode_base64('大猫儿追爱记')?>" class="tagc2">大猫儿追爱记</a>
    <a href="/s/<?php echo urlencode_base64('调皮王妃')?>" class="tagc2">调皮王妃</a>
    <a href="/s/<?php echo urlencode_base64('Python')?>" class="tagc5">Python</a>
  </div>
</center>
<?php load_template('public/js'); ?>
<script src="<?php echo base_url('static/js/zzsc.js')?>"></script>
<div style="display:none"><?php load_template('public/analytics'); ?></div>
</body>
</html>

 