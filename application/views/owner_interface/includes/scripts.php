<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.js"></script>
<script>window.jQuery || document.write('<script src="<?=site_url("js/libs/jquery-1.9.0.min.js");?>"><\/script>')</script>
<script type="text/javascript" src="<?=site_url('js/libs/bootstrap.js');?>"></script>
<script type="text/javascript" src="<?=site_url('js/vendor/jquery.jgrowl.js');?>"></script>
<script type="text/javascript" src="<?=site_url('js/vendor/jquery.cookie.js');?>"></script>
<script type="text/javascript" src="<?=site_url('js/main.js');?>"></script>
<script type="text/javascript" src="<?=site_url('js/logined.js');?>"></script>
<script type="text/javascript">
<?php if($this->uri->segment(2) == 'search'):?>
	$("li[data-active='<?=$this->uri->segment(2);?>']").addClass('active');
<?php elseif($this->uri->total_segments() == 3):?>
	$("li[data-active='<?=$this->uri->segment(3);?>']").addClass('active');
<?php elseif($this->uri->total_segments() == 2):?>
	$("li[data-active='<?=$this->uri->segment(2);?>']").addClass('active');
<?php endif;?>
</script>