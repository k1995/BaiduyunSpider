<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
load_template('public/header',array(
  'title'        => '最近收录列表'
));
?>
<style>
#top-search-bar{border-collapse:separate;border-spacing:2px;background:#f1f1f1;border-bottom:1px solid #e5e5e5;padding:10px 0;position:relative;}
#logo{height:35px;width:115px;display:block;left:10px;top:20px;position:absolute;background:url(<?php echo base_url('static/img/logo-bar.png') ?>);}
body{font-family: arial,sans-serif;}
@media (min-width:992px) {.container {margin-left:130px;}}
.link a{color:#545454}
@media (max-width:992px){#logo{display:none;}}
#search-bar a{padding:5px 10px;margin-right:5px;}
td,th{ white-space:nowrap;text-overflow:ellipsis; overflow:hidden;max-width:100px}
</style>
<div id="top-search-bar">
	<a id="logo" href="<?php echo site_url() ?>" title="返回网盘搜索"></a>
    <div class="container">
    	<div class="row">
        	<div class="col-lg-7">
        		<?php load_template('public/search-form','type=all'); ?>
          	</div>  
        </div>
    </div>
</div>
<div class="container">
	<p class="text-danger text-center" style="margin-top:10px;">60分钟更新一次，本次更新时间:<?php echo date("Y-m-d h:i:s") ?></p>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">视频</div>
			  <div class="panel-body">
			  	<table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($videos as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">种子</div>
			  <div class="panel-body">
			    <table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($torrents as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">文档/电子书</div>
			  <div class="panel-body">
			    <table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($documents as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">音乐</div>
			  <div class="panel-body">
			    <table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($musics as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">专辑</div>
				<div class="panel-body">
					<table class="table">
						<tr>
							<th>标题</th>
							<th>分享时间</th>
						</tr>
						<?php foreach($dirs as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				 </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">专辑</div>
			  <div class="panel-body">
			    <table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($ambs as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">压缩包</div>
			  <div class="panel-body">
			    <table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($packages as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading">软件</div>
			  <div class="panel-body">
			    <table class="table">
					<tr>
						<th>标题</th>
						<th>分享时间</th>
					</tr>
					<?php foreach($software as $i):?>
						<tr>
							<td><a target="_blank" href="<?php echo $i['link']?>"><?php echo $i['title']?></a></td>
							<td><?php echo timeago($i['feed_time']) ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			  </div>
			</div>
		</div><!--/col -->

	</div><!--/row-->
</div>
<?php
load_template('public/js');
load_template('public/footer');
?>