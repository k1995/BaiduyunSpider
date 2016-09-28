<form id="s-form" action="<?php echo site_url('search').'/'?>" method="get">
    <input type="hidden" id="type" name="type" value="<?php echo $type?>" /> 
    <input type="hidden" name="from" value="sf" />
    <div class="input-group">
      <input baiduSug="2" name="q" id="q" type="text" style="box-shadow:none;" value="<?php echo htmlspecialchars($keyword) ?>" class="form-control" />
      <span class="input-group-btn">
        <button type="submit" class="btn" style="background:#3385ff;border-bottom:1px solid #2d78f4;padding-right:5px;padding-left:5px;color:#fff;">&nbsp;&nbsp;<i class="glyphicon glyphicon-search"></i>&nbsp;&nbsp;</button>
      </span>
    </div>
</form>