<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 ?>
<form id="s-form" action="<?php echo site_url('search').'/'?>" method="get">
    <div class="input-group">
      <input baiduSug="2" autofocus placeholder="搜索网页或要下载的资源" name="q" id="q" type="text" style="box-shadow:none;" class="form-control" />
      <input type="hidden" name="from" value="sf" />
      <input id="type" type="hidden" name="type" value="<?php echo $type ?>" />
      <span class="input-group-btn">
        <button type="submit" id="search-btn" class="btn" style="background:#3385ff;border-bottom:1px solid #2d78f4;padding-right:10px;padding-left:16px;color:#fff;"><i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;</button>
      </span>
    </div>
</form>