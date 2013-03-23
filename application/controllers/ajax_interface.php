<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax_interface extends MY_Controller{
	
	function __construct(){
		
		parent::__construct();
	}
	
	function existEmail(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$statusval = array('status'=>FALSE);
		$parametr = trim($this->input->post('parametr'));
		if($parametr):
			if(!$this->users->record_exist('users','email',$parametr)):
				$statusval['status'] = TRUE;
			endif;
		else:
			$statusval['status'] = TRUE;
		endif;
		echo json_encode($statusval);
	}

	/******************************************** accounts *******************************************************/
	function login(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'message'=>'Login is impossible','redirect'=>base_url());
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$i] = trim($dataid[1]);
			endfor;
			if($dataval):
				$user = $this->users->auth_user($dataval[0],$dataval[1]);
				if($user):
					$statusval['status'] = TRUE;
					$statusval['message'] = '';
					$this->session->set_userdata(array('logon'=>md5($dataval[0]),'userid'=>$user['id']));
					switch($user['class']):
						case 1: $statusval['redirect'] .= ADM_START_PAGE;
							break;
						case 2: $statusval['redirect'] .= BROKER_START_PAGE;
							break;
						case 3: $statusval['redirect'] .= OWNER_START_PAGE;
							break;
					endswitch;
				endif;
			endif;
		endif;
		echo json_encode($statusval);
	}

	function signup_account(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'error'=>FALSE,'email'=>FALSE,'message'=>'');
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$dataid[0]] = trim($dataid[1]);
			endfor;
			if($dataval):
				if(!$this->users->user_exist('email',$dataval['email'])):
					$dataval['user_id'] = $this->users->insert_record($dataval);
					$this->load->helper('string');
					$activate_code = random_string('alpha',25);
					if($dataval['user_id']):
						switch($dataval['class']):
							case 2:
								$this->load->model('brokers');
								$brokerID = $this->brokers->insert_record($dataval);
								$this->users->update_field($dataval['user_id'],'user_id',$brokerID,'users');
								$this->users->update_field($dataval['user_id'],'temporary_code',$activate_code,'users');
								$user_class = 'broker';
								break;
							case 3:
								$this->load->model('owners');
								$this->load->model('properties');
								$ownerID = $this->owners->insert_record($dataval);
								$this->users->update_field($dataval['user_id'],'user_id',$ownerID,'users');
								$this->users->update_field($dataval['user_id'],'class',3,'users');
								$this->users->update_field($dataval['user_id'],'temporary_code',$activate_code,'users');
								$user_class = 'homeowner';
								break;
						endswitch;
						ob_start();?>
<p>Hello <em><?=$dataval['fname'].' '.$dataval['lname'];?></em>,</p>
<p>Thank you for registering at House2Trade.<br/>Please click the link below to activate your account:<br/>
<?=anchor('confirm-registering/'.$user_class.'/activation-code/'.$activate_code,base_url().'confirm-registering/'.$user_class.'/activation-code/'.$activate_code,array('target'=>'_blank'));?></p><?
$mailtext = ob_get_clean();
						$this->send_mail($dataval['email'],'robot@house2trade.com','House2Trade','Register to House2Trade',$mailtext);
						$statusval['status'] = TRUE;
						$statusval['message'] = '<img src="'.site_url("img/check.png").'" alt="" /> The letter with registration confirmation was sent to your email';
					endif;
				else:
					$statusval['email'] = TRUE;
				endif;
			endif;
		endif;
		echo json_encode($statusval);
	}

	function signup_properties(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'message'=>'Signup is impossible');
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$dataid[0]] = trim($dataid[1]);
			endfor;
			if($dataval):
				$this->load->model('properties');
				if(!$this->users->user_exist('email',$dataval['email']) && !$this->properties->properties_exits($dataval['mls'],$dataval['zip_code'])):
					$this->load->helper('string');
					$dataval['password'] = random_string('alnum',12);
					$dataval['user_id'] = $this->users->insert_record($dataval);
					if($dataval['user_id']):
						$this->load->model('owners');
						$ownerID = $this->owners->insert_record($dataval);
						$property_id = $this->properties->insert_record($dataval);
						$this->users->update_field($dataval['user_id'],'user_id',$ownerID,'users');
						$this->users->update_field($dataval['user_id'],'class',3,'users');
						$status = $this->users->read_field($this->user['uid'],'users','status');
						$this->properties->update_field($property_id,'status',$status,'properties');
						ob_start();?>
<p>Hello <em><?=$dataval['fname'].' '.$dataval['lname'];?></em>,</p>
<p>Your account has been created at Hause2Trade !<br/>
To log in to your personal account, use the username and password specified during registration.<br/>
Your login: <?=$dataval['email'];?><br/>
Your password: <?=$dataval['password'];?><br/>
<strong>Attention! </strong>Do not forget to change your password!<br/>
<br/>Please click on the link below to go to your profile:<br/>
<?=anchor('homeowner/profile',base_url().'homeowner/profile',array('target'=>'_blank'));?></p><?
$mailtext = ob_get_clean();
						$this->send_mail($dataval['email'],'robot@house2trade.com','Hause2Trade','Register to Hause2Trade',$mailtext);
						$statusval['message'] = '<img src="'.site_url("img/check.png").'" alt="" /> The letter with registration confirmation was sent to homeowner email';
						$statusval['status'] = TRUE;
						$this->session->set_userdata(array('owner_id'=>$dataval['user_id'],'property_id'=>$property_id));
					endif;
				else:
					$statusval['message'] = '<img src="'.site_url("img/no-check.png").'" alt="" />  Property already exist';
				endif;
			endif;
		endif;
		echo json_encode($statusval);
	}
	
	function change_user_status(){
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE);
		$data = trim($this->input->post('postdata'));
		if($data):
			$currentStatus = $this->users->read_field($data,'users','status');
			if(!$currentStatus):
				$this->users->update_field($data,'status',1,'users');
				$this->users->update_field($data,'temporary_code','','users');
				$statusval['status'] = TRUE;
			else:
				$this->users->update_field($data,'status',0,'users');
				$statusval['status'] = FALSE;
			endif;
		endif;
		echo json_encode($statusval);
	}
	
	function save_property_info(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'message'=>'Property saved','redirect'=>'');
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$dataid[0]] = trim($dataid[1]);
			endfor;
			if($dataval):
				$dataval['password'] = $dataval['confirm'] = ''; //это пока не решат нужно ли управлять брокер паролем владельца
				$this->load->model('owners');
				$this->load->model('properties');
				if($this->user['class'] == 2):
					$broker = $this->properties->read_field($this->session->userdata('property_id'),'properties','broker_id');
					if($broker != $this->user['uid']):
						exit;
					endif;
				endif;
				if($dataval['password'] != $dataval['confirm']):
					$statusval['message'] = 'Passwords do not match';
				else:
					$statusval['status'] = TRUE;
					if(!isset($dataval['setpswd'])):
						if($this->user['class'] == 2):
							$this->owners->update_record($this->session->userdata('owner_id'),$dataval);
						endif;
						$this->properties->update_record($this->session->userdata('property_id'),$dataval);
					endif;
					switch($this->user['class']):
						case 2:	$statusval['redirect'] = site_url(BROKER_START_PAGE);
								break;
						case 3:	$statusval['redirect'] = site_url(OWNER_START_PAGE);
								break;
					endswitch;
					if(($this->user['class'] != 2) && !empty($dataval['password'])):
						$this->users->update_field($this->user['uid'],'password',md5($dataval['password']),'users');
					endif;
				endif;
			endif;
		endif;
		echo json_encode($statusval);
	}
	
	function saveProfile(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'message'=>'Profile saved','redirect'=>'');
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$dataid[0]] = trim($dataid[1]);
			endfor;
			if($dataval):
				if($dataval['password'] != $dataval['confirm']):
					$statusval['message'] = 'Passwords do not match';
				else:
					$statusval['status'] = TRUE;
					if(!isset($dataval['setpswd'])):
						switch($this->user['class']):
							case 2:	$this->load->model('brokers');
									$dataval['id'] = $this->users->read_field($this->user['uid'],'users','user_id');
									$this->brokers->update_record($dataval);
									break;
							case 3:	$this->load->model('owners');
									$owner = $this->users->read_field($this->user['uid'],'users','user_id');
									$this->owners->update_record($owner,$dataval);
									break;
						endswitch;
					endif;
					switch($this->user['class']):
						case 2:	$statusval['redirect'] = site_url(BROKER_START_PAGE);
								break;
						case 3:	$statusval['redirect'] = site_url(OWNER_START_PAGE);
								break;
					endswitch;
					if(!empty($dataval['password'])):
						$this->users->update_field($this->user['uid'],'password',md5($dataval['password']),'users');
					endif;
				endif;
			endif;
		endif;
		echo json_encode($statusval);
	}
	
	function deleteProperty(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$property = $this->input->post('parameter');
		$json_request = array('status'=>FALSE,'redirect'=>site_url(BROKER_START_PAGE),'messages'=>'');
		if($property):
			$this->load->model('images');
			$this->load->model('properties');
			$this->load->model('owners');
			$images = $this->images->read_records($property,$this->user['uid']);
			for($i=0;$i<count($images);$i++):
				$this->filedelete($images[$i]['photo']);
			endfor;
			$this->images->delete_records($property,$this->user['uid']);
			$owner = $this->properties->read_field($property,'properties','owner_id');
			$ownerID = $this->users->read_field($owner,'users','user_id');
			$this->properties->delete_record($property,'properties');
			$this->users->delete_record($owner,'users');
			$this->owners->delete_record($ownerID,'owners');
			$json_request['status'] = TRUE;
			$this->session->set_userdata('msgs','<img src="'.site_url('img/check.png').'" alt="" /> Property deleted');
		else:
			$json_request['message'] = '<img src="'.site_url('img/no-check.png').'" alt="" /> Error deleting<hr/>';
		endif;
		echo json_encode($json_request);
	}
	
	function deletePropertyImages(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'message'=>'Images deleted');
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$i] = trim($dataid[1]);
			endfor;
			if($dataval):
				if($this->user['class'] == 2):
					$this->load->model('properties');
					$broker = $this->properties->read_field($this->session->userdata('property_id'),'properties','broker_id');
					if($broker != $this->user['uid']):
						exit;
					endif;
				endif;
				$this->load->model('images');
				$mainPhotoDeleted = FALSE;
				for($i=0;$i<count($dataval);$i++):
					$image = $this->images->read_record($dataval[$i],'images');
					if($image['main']):
						$mainPhotoDeleted = TRUE;
					endif;
					$this->filedelete($image['photo']);
					$this->images->delete_record($image['id'],'images');
				endfor;
				if($mainPhotoDeleted):
					$images = $this->images->read_records($this->session->userdata('property_id'));
					if(isset($images[0]['id'])):
						$this->images->update_field($images[0]['id'],'main',1,'images');
					endif;
				endif;
				$statusval['status'] = TRUE;
			endif;
		endif;
		echo json_encode($statusval);
	}
	
	function send_forgot_password(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Аccess denied');
		endif;
		$statusval = array('status'=>FALSE,'error'=>FALSE,'email'=>FALSE,'message'=>'');
		$data = trim($this->input->post('postdata'));
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$dataid[0]] = trim($dataid[1]);
			endfor;
			if($dataval):
				$uid = $this->users->user_exist('email',$dataval['email']);
				if($uid):
					$user_id = $this->users->read_field($uid,'users','user_id');
					$user_class = $this->users->read_field($uid,'users','class');
					$this->load->helper('string');
					$activate_code = random_string('alpha',25);
					$this->users->update_field($uid,'password','','users');
					$this->users->update_field($uid,'temporary_code',$activate_code,'users');
					if($user_id):
						switch($user_class):
							case 2:
								$this->load->model('brokers');
								$user_name = $this->brokers->read_name($user_id,'brokers');
								$user_class = 'broker';
								break;
							case 3:
								$this->load->model('properties');
								$user_name = $this->properties->read_name($user_id,'properties');
								$user_class = 'homeowner';
								break;
						endswitch;
						ob_start();?>
<p>Hello <em><?=$user_name;?></em>,</p>
<p>You have requested a new password to access personal account. To do this, follow the links below:<br/>
<?=anchor('password-recovery/'.$user_class.'/temporary-code/'.$activate_code,base_url().'password-recovery/'.$user_class.'/temporary-code/'.$activate_code,array('target'=>'_blank'));?></p><?
$mailtext = ob_get_clean();
						$this->send_mail($dataval['email'],'robot@house2trade.com','House2Trade','Restore account password to House2Trade',$mailtext);
						$statusval['status'] = TRUE;
						$statusval['message'] = '<img src="'.site_url("img/check.png").'" alt="" /> Letter from further action was sent to your email';
					endif;
				else:
					$statusval['email'] = TRUE;
				endif;
			endif;
		endif;
		echo json_encode($statusval);
	}
	
	function multiUpload(){
		
		$this->load->model('images');
		$randomNumber = mt_rand(1,1000);
		$nextPropertyID = $this->images->nextID('images');
		$insert = array('main'=>0,'property_id'=>0,'photo'=>'','owner_id'=>0);
		$insert['owner_id'] = $this->session->userdata('owner_id');
		$insert['property_id'] = $this->session->userdata('property_id');
		if($this->user['class'] == 3):
			$this->load->model('owners');
			$insert['broker_id'] = $this->owners->read_field($insert['owner_id'],'owners','broker_id');
		endif;
		if(!$insert['owner_id'] || !$insert['property_id']):
			show_error('Missing data');
		endif;
		if(!$this->images->image_exist($insert['property_id'])):
			$insert['main'] = 1;
		endif;
		$fn = (isset($_SERVER['HTTP_X_FILENAME'])?$_SERVER['HTTP_X_FILENAME']:false);
		if($fn):
			$newFileName = preg_replace('/.+(.)(\.)+/','property_'.$nextPropertyID.'_'.$randomNumber."\$2",$fn);
			file_put_contents(getcwd().'/upload_images/'.$newFileName,file_get_contents('php://input'));
			echo "$fn uploaded";
			$insert['photo'] = 'upload_images/'.$newFileName;
			$this->images->insert_record($insert);
			exit();
		else:
			if(isset($_FILES['fileselect'])):
				$files = $_FILES['fileselect'];
				$i = 0;
				foreach($files['error'] as $id => $err):
					if($err == UPLOAD_ERR_OK):
						$fn = $files['name'][$id];
						$newFileName = preg_replace('/.+(.)(\.)+/','property_'.$nextPropertyID.'_'.$randomNumber."\$2",$fn);
						move_uploaded_file($files['tmp_name'][$id],getcwd().'/upload_images/'.$newFileName);
						if(!$i):
							$insert['main'] = 1;
						else:
							$insert['main'] = 0;
						endif;
						$insert['photo'] = 'upload_images/'.$newFileName;
						$this->images->insert_record($insert);
						echo "<p>File $fn uploaded.</p>";
						$i++;
					endif;
				endforeach;
			else:
				show_404();
			endif;
		endif;
	}

	function searchProperty(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$data = $this->input->post('postdata');
		$json_request = array('status'=>FALSE,'redirect'=>site_url('broker/search/result'),'messages'=>'');
		if($data):
			$data = preg_split("/&/",$data);
			for($i=0;$i<count($data);$i++):
				$dataid = preg_split("/=/",$data[$i]);
				$dataval[$dataid[0]] = trim($dataid[1]);
			endfor;
			if($dataval):
				$sql = 'SELECT users.id AS uid,users.email,users.status,owners.id AS oid,owners.fname,owners.lname,properties.*';
				$sql .= ' FROM users INNER JOIN owners ON users.user_id = owners.id INNER JOIN properties ON users.id = properties.owner_id';
				$sql .= ' WHERE properties.broker_id != '.$this->user['uid'];
				if(!empty($dataval['property_mls'])):
					$sql .= ' AND properties.mls = '.$dataval['property_mls'];
				endif;
				if(!empty($dataval['beds_num'])):
					$sql .= ' AND properties.bedrooms = '.$dataval['beds_num'];
				endif;
				if(!empty($dataval['baths_num'])):
					$sql .= ' AND properties.bathrooms = '.$dataval['baths_num'];
				endif;
				if(!empty($dataval['property_min_price'])):
					$sql .= ' AND properties.price >= '.$dataval['property_min_price'];
				endif;
				if(!empty($dataval['property_max_price'])):
					$sql .= ' AND properties.price <= '.$dataval['property_max_price'];
				endif;
				if(!empty($dataval['square_feet'])):
					$sql .= ' AND properties.sqf <= '.$dataval['square_feet'];
				endif;
				if(!empty($dataval['type'])):
					$sql .= ' AND properties.type = '.$dataval['type'];
				endif;
				$sql .= ' ORDER BY users.signdate DESC,users.id';
				$this->load->model('properties');
				$properties = $this->properties->query_execute($sql);
				if($properties):
					$this->session->set_userdata('search_sql',$sql);
					$json_request['status'] = TRUE;
				else:
					$json_request['message'] = '<img src="'.site_url('img/no-check.png').'" alt="" /> nothing found';
				endif;
			endif;
		endif;
		echo json_encode($json_request);
	}

	function addToFavorite(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$property = $this->input->post('parameter');
		$json_request['message'] = '<img src="'.site_url('img/no-check.png').'" alt="" /> Error adding';
		if($property):
			$this->load->model('property_favorite');
			$this->load->model('properties');
			if($this->properties->record_exist('properties','id',$property) && !$this->property_favorite->record_exist('property_favorite','property',$property)):
				$this->property_favorite->insert_record($property);
				$json_request['message'] = '<img src="'.site_url('img/check.png').'" alt="" /> Property added';
			endif;
		endif;
		echo json_encode($json_request);
	}
	
	function removeToFavorite(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$property = $this->input->post('parameter');
		$json_request['message'] = '<img src="'.site_url('img/no-check.png').'" alt="" /> Error removing';
		if($property):
			$this->load->model('property_favorite');
			$this->load->model('properties');
			$favoriteID = $this->property_favorite->record_exist($property,$this->user['uid']);
			if($favoriteID):
				$this->property_favorite->delete_record($favoriteID,'property_favorite');
				$json_request['message'] = '<img src="'.site_url('img/check.png').'" alt="" /> Property removed from favorite';
			endif;
		endif;
		echo json_encode($json_request);
	}
	
	function addToPotentialBy(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$property = $this->input->post('parameter');
		$json_request['message'] = '<img src="'.site_url('img/no-check.png').'" alt="" /> Error adding';
		if($property):
			$this->load->model('property_potentialby');
			$this->load->model('properties');
			if($this->properties->record_exist('properties','id',$property) && !$this->property_potentialby->record_exist('property_potentialby','property',$property)):
				$this->property_potentialby->insert_record($property);
				$json_request['message'] = '<img src="'.site_url('img/check.png').'" alt="" /> Property added';
			endif;
		endif;
		echo json_encode($json_request);
	}
	
	function removeToPotentialBy(){
		
		if(!$this->input->is_ajax_request()):
			show_error('Access denied');
		endif;
		$property = $this->input->post('parameter');
		$json_request['message'] = '<img src="'.site_url('img/no-check.png').'" alt="" /> Error removing';
		if($property):
			$this->load->model('property_potentialby');
			$this->load->model('properties');
			$favoriteID = $this->property_potentialby->record_exist($property,$this->user['uid']);
			if($favoriteID):
				$this->property_potentialby->delete_record($favoriteID,'property_potentialby');
				$json_request['message'] = '<img src="'.site_url('img/check.png').'" alt="" /> Property removed from potential by';
			endif;
		endif;
		echo json_encode($json_request);
	}
	
}