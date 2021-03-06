<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view("broker_interface/includes/head");?>
</head>
<body>
	<?php $this->load->view("broker_interface/includes/header");?>
	<div class="container">
		<div class="row">
			<hr/>
			<?php $this->load->view("broker_interface/includes/rightbar");?>
			<div class="span9">
				<div class="navbar">
				<?php $this->load->view("broker_interface/forms/select-property");?>
				</div>
				<div class="clear"></div>
	<?php if($this->session->userdata('current_property') == FALSE || empty($levels['level2'])):?>
				<p>Instant trade is missing or is not selected current seller</p>
	<?php else:?>
				<h2>Level 1</h2>
				<div class="media">
					<?php if($levels['level1']['seller_number'] > 0):?>
						<span title="Sellers count" class="badge badge-info seller-number"><?=$levels['level1']['seller_number'];?></span>
					<?php endif;?>
					<?php if($levels['level1']['buyer_number'] > 0):?>
						<span title="Buyers count" class="badge badge-success buyer-number"><?=$levels['level1']['buyer_number'];?></span>
					<?php endif;?>
					<a class="none pull-left" href="#">
						<img class="img-polaroid media-object" src="<?=base_url($levels['level1']['photo']);?>" alt="">
					</a>
					<div class="media-body">
						<h4 class="media-heading">
							<a href="<?=site_url('broker/'.$this->uri->segment(2).'/information/'.$levels['level1']['id']);?>">
								<small>HT-<?=$levels['level1']['id'];?></small> <?=$levels['level1']['address1'];?>
							</a>
							<span><?= $levels['level1']['city'].', '.$levels['level1']['state'].' '.$levels['level1']['zip_code']; ?></span>
						</h4>
						<p>
							$<?=$levels['level1']['price'];?> <span class="separator">|</span> 
							<?=$levels['level1']['bedrooms'];?> Bd <span class="separator">|</span> 
							<?=$levels['level1']['bathrooms'];?> Ba <span class="separator">|</span> 
							<?=$levels['level1']['sqf'];?> Sq Ft <span class="separator">|</span> 
							<?=$levels['level1']['lotsize'];?> Acres <br/>
							<?= ucfirst($levels['level1']['type']); ?> Home
						</p>
					</div>
				</div>
		<?php if(!empty($levels['level2'])):?>
				<h2>Level 2</h2>
				<div class="cycle-blocks-2">
				<?php for($i=0;$i<count($levels['level2']);$i++):?>
					<div class="media cycle-clocks-elements">
					<?php if($levels['level2'][$i]['seller_number'] > 0):?>
						<span title="Sellers count" class="badge badge-info seller-number"><?=$levels['level2'][$i]['seller_number'];?></span>
					<?php endif;?>
					<?php if($levels['level2'][$i]['buyer_number'] > 0):?>
						<span title="Buyers count" class="badge badge-success buyer-number"><?=$levels['level2'][$i]['buyer_number'];?></span>
					<?php endif;?>
						<a class="none pull-left" href="#">
							<img class="img-polaroid media-object" src="<?=site_url($levels['level2'][$i]['photo']);?>" alt="">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?=site_url('broker/'.$this->uri->segment(2).'/information/'.$levels['level2'][$i]['id']);?>">
									<small>HT-<?=$levels['level2'][$i]['id'];?></small> <?=$levels['level2'][$i]['address1'];?>
								</a>
								<span><?= $levels['level2'][$i]['city'].', '.$levels['level2'][$i]['state'].' '.$levels['level2'][$i]['zip_code']; ?></span>
							</h4>
							<p>
								$<?=$levels['level2'][$i]['price'];?> <span class="separator">|</span> 
								<?=$levels['level2'][$i]['bedrooms'];?> Bd <span class="separator">|</span> 
								<?=$levels['level2'][$i]['bathrooms'];?> Ba <span class="separator">|</span> 
								<?=$levels['level2'][$i]['sqf'];?> Sq Ft <span class="separator">|</span> 
								<?=$levels['level2'][$i]['lotsize'];?> Acres <br/>
								<?= ucfirst($levels['level2'][$i]['type']); ?> Home
							</p>
						</div>
					<?php if($levels['level2'][$i]['potentialby'] == FALSE):?>
						<a href="#addToPotentialBy" role="button" class="btn btn-mini btn-link show-modal-confirm" data-propery-target="null" data-propery-id="<?=$levels['level2'][$i]['id'];?>" data-toggle="modal">Add to potential buy</a>
					<?php else:?>
						<p class="property-owner">Already added to potential buy</p>
					<?php endif;?>
					</div>
				<?php endfor;?>
				</div>
				<?php if(count($levels['level2'])>1):?>
				<ul data-index="2" class="nav-cycle nav-2 clearfix">
				<?php for($i=0;$i<count($levels['level2']);$i++):?>
					<li><a href="#"><img src="<?=site_url($levels['level2'][$i]['photo']);?>"></a></li>
				<?php endfor;?>
				</ul>
				<?php endif;?>
			<?php if(!empty($levels['level3'])):?>
				<h2>Level 3</h2>
				<div class="cycle-blocks-3">
				<?php for($i=0;$i<count($levels['level3']);$i++):?>
					<div class="media cycle-clocks-elements">
					<?php if($levels['level3'][$i]['seller_number'] > 0):?>
						<span title="Sellers count" class="badge badge-info seller-number"><?=$levels['level3'][$i]['seller_number'];?></span>
					<?php endif;?>
					<?php if($levels['level3'][$i]['buyer_number'] > 0):?>
						<span title="Buyers count" class="badge badge-success buyer-number"><?=$levels['level3'][$i]['buyer_number'];?></span>
					<?php endif;?>
						<a class="none pull-left" href="#">
							<img class="img-polaroid media-object" src="<?=site_url($levels['level3'][$i]['photo']);?>" alt="">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?=site_url('broker/'.$this->uri->segment(2).'/information/'.$levels['level3'][$i]['id']);?>">
									<small>HT-<?=$levels['level3'][$i]['id'];?></small> <?=$levels['level3'][$i]['address1'];?>
								</a>
								<span><?= $levels['level3'][$i]['city'].', '.$levels['level3'][$i]['state'].' '.$levels['level3'][$i]['zip_code']; ?></span>
							</h4>
							<p>
								$<?=$levels['level3'][$i]['price'];?> <span class="separator">|</span> 
								<?=$levels['level3'][$i]['bedrooms'];?> Bd <span class="separator">|</span> 
								<?=$levels['level3'][$i]['bathrooms'];?> Ba <span class="separator">|</span> 
								<?=$levels['level3'][$i]['sqf'];?> Sq Ft <span class="separator">|</span> 
								<?=$levels['level3'][$i]['lotsize'];?> Acres <br/>
								<?= ucfirst($levels['level3'][$i]['type']); ?> Home
							</p>
						</div>
					<?php if($levels['level3'][$i]['potentialby'] == FALSE):?>
						<a href="#addToPotentialBy" role="button" class="btn btn-mini btn-link show-modal-confirm" data-propery-target="null" data-propery-id="<?=$levels['level3'][$i]['id'];?>" data-toggle="modal">Add to potential buy</a>
					<?php else:?>
						<p class="property-owner">Already added to potential buy</p>
					<?php endif;?>
					</div>
				<?php endfor;?>
				</div>
				<?php if(count($levels['level3'])>1):?>
				<ul data-index="3" class="nav-cycle nav-3 clearfix">
				<?php for($i=0;$i<count($levels['level3']);$i++):?>
					<li><a href="#"><img src="<?=site_url($levels['level3'][$i]['photo']);?>"></a></li>
				<?php endfor;?>
				</ul>
				<?php endif;?>
			<?php endif;?>
			<?php if(!empty($levels['level4'])):?>
				<h2>Level 4</h2>
				<div class="cycle-blocks-4">
				<?php for($i=0;$i<count($levels['level4']);$i++):?>
					<div class="media cycle-clocks-elements">
					<?php if($levels['level4'][$i]['seller_number'] > 0):?>
						<span title="Sellers count" class="badge badge-info seller-number"><?=$levels['level4'][$i]['seller_number'];?></span>
					<?php endif;?>
					<?php if($levels['level4'][$i]['buyer_number'] > 0):?>
						<span title="Buyers count" class="badge badge-success buyer-number"><?=$levels['level4'][$i]['buyer_number'];?></span>
					<?php endif;?>
						<a class="none pull-left" href="#">
							<img class="img-polaroid media-object" src="<?=site_url($levels['level4'][$i]['photo']);?>" alt="">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?=site_url('broker/'.$this->uri->segment(2).'/information/'.$levels['level4'][$i]['id']);?>">
									<small>HT-<?=$levels['level4'][$i]['id'];?></small> <?=$levels['level4'][$i]['address1'];?>
								</a>
								<span><?= $levels['level4'][$i]['city'].', '.$levels['level4'][$i]['state'].' '.$levels['level4'][$i]['zip_code']; ?></span>
							</h4>
							<p>
								$<?=$levels['level4'][$i]['price'];?> <span class="separator">|</span> 
								<?=$levels['level4'][$i]['bedrooms'];?> Bd <span class="separator">|</span> 
								<?=$levels['level4'][$i]['bathrooms'];?> Ba <span class="separator">|</span> 
								<?=$levels['level4'][$i]['sqf'];?> Sq Ft <span class="separator">|</span> 
								<?=$levels['level4'][$i]['lotsize'];?> Acres <br/>
								<?= ucfirst($levels['level4'][$i]['type']); ?> Home
							</p>
						</div>
					<?php if($levels['level4'][$i]['potentialby'] == FALSE):?>
						<a href="#addToPotentialBy" role="button" class="btn btn-mini btn-link show-modal-confirm" data-propery-target="null" data-propery-id="<?=$levels['level4'][$i]['id'];?>" data-toggle="modal">Add to potential buy</a>
					<?php else:?>
						<p class="property-owner">Already added to potential buy</p>
					<?php endif;?>
					</div>
				<?php endfor;?>
				</div>
				<?php if(count($levels['level4'])>1):?>
				<ul data-index="4" class="nav-cycle nav-4 clearfix">
				<?php for($i=0;$i<count($levels['level4']);$i++):?>
					<li><a href="#"><img src="<?=site_url($levels['level4'][$i]['photo']);?>"></a></li>
				<?php endfor;?>
				</ul>
				<?php endif;?>
			<?php endif;?>
			<?php if(!empty($levels['level5'])):?>
				<h2>Level 5</h2>
				<div class="cycle-blocks-5">
				<?php for($i=0;$i<count($levels['level5']);$i++):?>
					<div class="media cycle-clocks-elements">
					<?php if($levels['level5'][$i]['seller_number'] > 0):?>
						<span title="Sellers count" class="badge badge-info seller-number"><?=$levels['level5'][$i]['seller_number'];?></span>
					<?php endif;?>
					<?php if($levels['level5'][$i]['buyer_number'] > 0):?>
						<span title="Buyers count" class="badge badge-success buyer-number"><?=$levels['level5'][$i]['buyer_number'];?></span>
					<?php endif;?>
						<a class="none pull-left" href="#">
							<img class="img-polaroid media-object" src="<?=site_url($levels['level5'][$i]['photo']);?>" alt="">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?=site_url('broker/'.$this->uri->segment(2).'/information/'.$levels['level5'][$i]['id']);?>">
									<small>HT-<?=$levels['level5'][$i]['id'];?></small> <?=$levels['level5'][$i]['address1'];?>
								</a>
								<span><?= $levels['level5'][$i]['city'].', '.$levels['level5'][$i]['state'].' '.$levels['level5'][$i]['zip_code']; ?></span>
							</h4>
							<p>
								$<?=$levels['level5'][$i]['price'];?> <span class="separator">|</span> 
								<?=$levels['level5'][$i]['bedrooms'];?> Bd <span class="separator">|</span> 
								<?=$levels['level5'][$i]['bathrooms'];?> Ba <span class="separator">|</span> 
								<?=$levels['level5'][$i]['sqf'];?> Sq Ft <span class="separator">|</span> 
								<?=$levels['level5'][$i]['lotsize'];?> Acres <br/>
								<?= ucfirst($levels['level5'][$i]['type']); ?> Home
							</p>
						</div>
					<?php if($levels['level5'][$i]['potentialby'] == FALSE):?>
						<a href="#addToPotentialBy" role="button" class="btn btn-mini btn-link show-modal-confirm" data-propery-target="null" data-propery-id="<?=$levels['level5'][$i]['id'];?>" data-toggle="modal">Add to potential buy</a>
					<?php else:?>
						<p class="property-owner">Already added to potential buy</p>
					<?php endif;?>
					</div>
				<?php endfor;?>
				</div>
				<?php if(count($levels['level5'])>1):?>
				<ul data-index="5" class="nav-cycle nav-5 clearfix">
				<?php for($i=0;$i<count($levels['level5']);$i++):?>
					<li><a href="#"><img src="<?=site_url($levels['level5'][$i]['photo']);?>"></a></li>
				<?php endfor;?>
				</ul>
				<?php endif;?>
			<?php endif;?>
			<?php if(!empty($levels['level6'])):?>
				<h2>Level 6</h2>
				<div class="cycle-blocks-6">
				<?php for($i=0;$i<count($levels['level6']);$i++):?>
					<div class="media cycle-clocks-elements">
					<?php if($levels['level6'][$i]['seller_number'] > 0):?>
						<span title="Sellers count" class="badge badge-info seller-number"><?=$levels['level6'][$i]['seller_number'];?></span>
					<?php endif;?>
					<?php if($levels['level6'][$i]['buyer_number'] > 0):?>
						<span title="Buyers count" class="badge badge-success buyer-number"><?=$levels['level6'][$i]['buyer_number'];?></span>
					<?php endif;?>
						<a class="none pull-left" href="#">
							<img class="img-polaroid media-object" src="<?=site_url($levels['level6'][$i]['photo']);?>" alt="">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="<?=site_url('broker/'.$this->uri->segment(2).'/information/'.$levels['level6'][$i]['id']);?>">
									<small>HT-<?=$levels['level6'][$i]['id'];?></small> <?=$levels['level6'][$i]['address1'];?>
								</a>
								<span><?= $levels['level6'][$i]['city'].', '.$levels['level6'][$i]['state'].' '.$levels['level6'][$i]['zip_code']; ?></span>
							</h4>
							<p>
								$<?=$levels['level6'][$i]['price'];?> <span class="separator">|</span> 
								<?=$levels['level6'][$i]['bedrooms'];?> Bd <span class="separator">|</span> 
								<?=$levels['level6'][$i]['bathrooms'];?> Ba <span class="separator">|</span> 
								<?=$levels['level6'][$i]['sqf'];?> Sq Ft <span class="separator">|</span> 
								<?=$levels['level6'][$i]['lotsize'];?> Acres <br/>
								<?= ucfirst($levels['level6'][$i]['type']); ?> Home
							</p>
						</div>
					<?php if($levels['level6'][$i]['potentialby'] == FALSE):?>
						<a href="#addToPotentialBy" role="button" class="btn btn-mini btn-link show-modal-confirm" data-propery-target="null" data-propery-id="<?=$levels['level6'][$i]['id'];?>" data-toggle="modal">Add to potential buy</a>
					<?php else:?>
						<p class="property-owner">Already added to potential buy</p>
					<?php endif;?>
					</div>
				<?php endfor;?>
				</div>
				<?php if(count($levels['level6'])>1):?>
				<ul data-index="6" class="nav-cycle nav-6 clearfix">
				<?php for($i=0;$i<count($levels['level6']);$i++):?>
					<li><a href="#"><img src="<?=site_url($levels['level6'][$i]['photo']);?>"></a></li>
				<?php endfor;?>
				</ul>
				<?php endif;?>
			<?php endif;?>
		<?php endif;?>
	<?php endif;?>
			</div>
		</div>
		<?php $this->load->view("broker_interface/modal/add-to-potential-by");?>
	</div>
	<?php $this->load->view("broker_interface/includes/footer");?>
	<?php $this->load->view("broker_interface/includes/scripts");?>
	<script type="text/javascript" src="<?=site_url('js/jquery.easing.js');?>"></script>
	<script type="text/javascript" src="<?=site_url('js/jquery.cycle.js');?>"></script>
	<script type="text/javascript" src="<?=site_url('js/cabinet/cycle-config.js');?>"></script>
</body>
</html>
