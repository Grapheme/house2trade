<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view("broker_interface/includes/head");?>
<link rel="stylesheet" type="text/css" media="all" href="<?=site_url('css/images.css');?>" />
</head>
<body>
	<?php $this->load->view("broker_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<hr/>
			<?php $this->load->view("broker_interface/includes/rightbar");?>
			<div class="span9">
				<div class="navbar">
					<div class="navbar-inner">
						<a class="brand" href="<?=site_url(uri_string());?>">House2Trade property registration</a>
					</div>
				</div>
				<div class="clear"></div>
				<div id="div-choise-metod">
				<?php $this->load->view('forms/metod-properties-register');?>
				</div>
				<div class="clear"></div>
				<div id="div-account-properties" class="hidden">
					<?php $this->load->view('forms/account-properties');?>
				</div>
				<div class="clear"></div>
				<div id="div-insert-photo-properties" class="hidden">
					<?php $this->load->view('forms/insert-photos-properties');?>
				</div>
			</div>
		</div>
	</div>
	<?php $this->load->view("broker_interface/includes/scripts");?>
	<script type="text/javascript" src="<?=site_url('js/upload.js');?>"></script>
</body>
</html>
