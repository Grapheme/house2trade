<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Broker_interface extends MY_Controller{
	
	function __construct(){
		
		parent::__construct();
		if(!$this->loginstatus || ($this->user['class'] != 2)):
			redirect('');
		endif;
		$password = $this->users->read_field($this->user['uid'],'users','password');
		if(empty($password) && ($this->uri->segment(2) != 'set-password')):
			redirect('broker/set-password');
		endif;
	}
	
	/******************************************** cabinet *******************************************************/
	
	public function setPassword(){
		$password = $this->users->read_field($this->user['uid'],'users','password');
		if(!empty($password)):
			redirect(BROKER_START_PAGE);
		endif;
		$this->load->view("broker_interface/pages/set-password");
	}
	
	public function register_properties(){
		
		$this->load->model('property_type');
		$pagevar = array('property_type'=>$this->property_type->read_records('property_type'));
		$this->session->unset_userdata(array('owner_id'=>'','property_id'=>''));
		$this->load->view("broker_interface/pages/properties",$pagevar);
	}
	
	public function profile(){
		
		$this->load->model('brokers');
		$pagevar = array('profile' => $this->users->read_record($this->user['uid'],'users'));
		$pagevar['profile']['info'] = $this->brokers->read_record($pagevar['profile']['user_id'],'brokers');
		$this->load->view("broker_interface/pages/profile",$pagevar);
	}
	
	/********************************************* trading ********************************************************/
	public function searchProperty(){
		
		if($this->uri->total_segments() < 3):
			$this->session->unset_userdata('search_sql');
		endif;
		$this->load->model('property_type');
		$from = (int)$this->uri->segment(5);
		$pagevar = array(
			'property_type'=>$this->property_type->read_records('property_type'),
			'properties' => array(),
			'pages' => array(),
		);
		if($this->session->userdata('search_sql')):
			$sql = $this->session->userdata('search_sql')." LIMIT $from,7";
			$this->load->model('properties');
			$this->load->model('images');
			$pagevar['properties'] = $this->properties->query_execute($sql);
			for($i=0;$i<count($pagevar['properties']);$i++):
				$pagevar['properties'][$i]['photo'] = $this->images->mainPhoto($pagevar['properties'][$i]['id']);
				if(!$pagevar['properties'][$i]['photo']):
					$pagevar['properties'][$i]['photo'] = 'img/thumb.png';
				endif;
			endfor;
			$count = 0;
			if($pagevar['properties']):
				$count = count($this->properties->query_execute($this->session->userdata('search_sql')));
			endif;
			$pagevar['pages'] = $this->pagination('broker/search/result',5,$count,7);
			$this->session->set_userdata('backpath',uri_string());
		endif;
		$this->load->view("broker_interface/pages/search-properties",$pagevar);
	}
	
	public function favoriteProperty(){
		
		$this->load->model('property_favorite');
		$this->load->model('union');
		$this->load->model('images');
		$from = (int)$this->uri->segment(4);
		$pagevar = array(
			'properties' => $this->union->brokerFavoriteList(3,$this->user['uid'],7,$from),
			'pages' => $this->pagination('broker/favorite',4,$this->property_favorite->count_records('property_favorite','owner',$this->user['uid']),7)
		);
		for($i=0;$i<count($pagevar['properties']);$i++):
			$pagevar['properties'][$i]['photo'] = $this->images->mainPhoto($pagevar['properties'][$i]['id']);
			if(!$pagevar['properties'][$i]['photo']):
				$pagevar['properties'][$i]['photo'] = 'img/thumb.png';
			endif;
		endfor;
		$this->session->set_userdata('backpath',uri_string());
		$this->load->view("broker_interface/pages/list-properties",$pagevar);
	}
	
	public function potentialByProperty(){
		
		$this->load->model('property_potentialby');
		$this->load->model('union');
		$this->load->model('images');
		$from = (int)$this->uri->segment(4);
		$pagevar = array(
			'properties' => $this->union->brokerPotentialList(3,$this->user['uid'],7,$from),
			'pages' => $this->pagination('broker/potential-by',4,$this->property_potentialby->count_records('property_potentialby','owner',$this->user['uid']),7)
		);
		for($i=0;$i<count($pagevar['properties']);$i++):
			$pagevar['properties'][$i]['photo'] = $this->images->mainPhoto($pagevar['properties'][$i]['id']);
			if(!$pagevar['properties'][$i]['photo']):
				$pagevar['properties'][$i]['photo'] = 'img/thumb.png';
			endif;
		endfor;
		$this->session->set_userdata('backpath',uri_string());
		$this->load->view("broker_interface/pages/list-properties",$pagevar);
	}
	
	/********************************************* properties ********************************************************/
	
	public function properties(){
		
		$this->load->model('properties');
		$this->load->model('union');
		$this->load->model('images');
		$from = (int)$this->uri->segment(4);
		$pagevar = array(
			'properties' => $this->union->brokerPropertiesList(3,$this->user['uid'],10,$from),
			'pages' => $this->pagination(BROKER_START_PAGE.'/',4,$this->properties->count_records('properties','broker_id',$this->user['uid']),10)
		);
		for($i=0;$i<count($pagevar['properties']);$i++):
			$pagevar['properties'][$i]['photo'] = $this->images->mainPhoto($pagevar['properties'][$i]['id']);
			if(!$pagevar['properties'][$i]['photo']):
				$pagevar['properties'][$i]['photo'] = 'img/thumb.png';
			endif;
		endfor;
		$this->session->set_userdata('backpath',uri_string());
		$this->load->view("broker_interface/pages/list-properties",$pagevar);
	}
	
	public function property(){
		
		$this->load->model('union');
		$property = (int)$this->uri->segment(4);
		$pagevar = array(
			'properties' => $this->union->brokerProperties(3,$this->user['uid']),
			'property' => array(),
			'images' => array()
		);
		if($pagevar['properties']):
			$this->load->model('images');
			$pagevar['property'] = $this->union->propertyInformation($property);
			if(!$pagevar['property']):
				show_error('Property missing');
			endif;
			$pagevar['property']['photo'] = $this->images->mainPhoto($pagevar['property']['id']);
			$this->load->model('property_favorite');
			if($pagevar['property']['broker_id'] != $this->user['uid']):
				$pagevar['property']['favorite'] = $this->property_favorite->record_exist($pagevar['property']['id'],$this->user['uid']);
			endif;
			$this->load->model('property_potentialby');
			if($pagevar['property']['broker_id'] != $this->user['uid']):
				$pagevar['property']['potentialby'] = $this->property_potentialby->record_exist($pagevar['property']['id'],$this->user['uid']);
			endif;
			if(!$pagevar['property']['photo']):
				$pagevar['property']['photo'] = 'img/thumb.png';
			endif;
			$pagevar['images'] = $this->images->read_records($pagevar['property']['id'],$this->user['uid']);
		endif;
		$this->load->view("broker_interface/pages/property-information",$pagevar);
	}
	
	public function edit_property(){
		
		$this->load->model('properties');
		$this->load->model('owners');
		$this->load->model('images');
		$this->load->model('property_type');
		$pagevar = array(
			'property' => $this->properties->read_record($this->uri->segment(4),'properties'),
			'images' => $this->images->read_records($this->uri->segment(4),'images'),
			'property_type'=>$this->property_type->read_records('property_type')
		);
		if($pagevar['property']['broker_id'] != $this->user['uid']):
			show_error('Access Denied!');
		endif;
		$pagevar['property']['user'] = $this->users->read_record($pagevar['property']['owner_id'],'users');
		$pagevar['property']['owner'] = $this->owners->read_record($pagevar['property']['user']['user_id'],'owners');
		$this->session->set_userdata(array('owner_id'=>$pagevar['property']['owner']['id'],'property_id'=>$pagevar['property']['id']));
		$this->load->view("broker_interface/pages/property-card",$pagevar);
	}
}