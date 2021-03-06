<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
	
	function current_date($time = FALSE){
		
		if($time):
			return strtotime(date('Y-m-d H:i:s'));
		else:
			return strtotime(date('Y-m-d'));
		endif;
	}
	
	function yesterday($user_date){
		
		if(!$user_date):
			return FALSE;
		endif;
		
		$sub_date = strtotime(date_without_time($user_date))- mktime(0,0,0,date("m"),date("d")-1,date("Y"));
		if($sub_date == 0):
			return TRUE;
		else:
			return FALSE;
		endif;
	}
	
	function dialog_date($user_date){
		
		if(!$user_date):
			return '';
		endif;
		$date = strtotime(date_without_time($user_date));
		if($date == current_date()):
			return $string = 'cегодня в '.date_time($user_date);
		elseif(yesterday($user_date)):
			return $string = 'вчера в '.date_time($user_date);
		else:
			return $string = month_date_with_time($user_date);
		endif;
	}
	
	function dialog_time($user_date){
		
		if(!$user_date):
			return '';
		endif;
		
		$date = strtotime(date_without_time($user_date));
		if($date == current_date()):
			return $string = date_full_time($user_date);
		else:
			return $string = date("d.m.y",strtotime(swap_dot_date_without_time($user_date)));
		endif;
	}
	
	function month_date($field){
		
		$months = array("01"=>"янв","02"=>"фев","03"=>"мар","04"=>"апр","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"авг","09"=>"сен","10"=>"окт","11"=>"ноя","12"=>"дек");
		$list = preg_split("/-/",$field);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5 $nmonth \$1";
		return preg_replace($pattern, $replacement,$field);
	}
	
	function month_date_with_time($field){
		
		$months = array("01"=>"янв","02"=>"фев","03"=>"мар","04"=>"апр","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"авг","09"=>"сен","10"=>"окт","11"=>"ноя","12"=>"дек");
		$list = preg_split("/-/",$field);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5 $nmonth \$1 в \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
	
	function month_dot_date($field){
		
		$months = array("01"=>"янв","02"=>"фев","03"=>"мар","04"=>"апр","05"=>"мая","06"=>"июня","07"=>"июля","08"=>"авг","09"=>"сен","10"=>"окт","11"=>"ноя","12"=>"дек");
		$list = preg_split("/\./",$field);
		$nmonth = $months[$list[1]];
		$pattern = "/(\d+)(\.)(\w+)(\.)(\d+)/i";
		$replacement = "\$1 $nmonth \$5"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	function date_without_time($field){
	
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$1-$3-\$5";
		return preg_replace($pattern, $replacement,$field);
	}
	
	function date_time($field){
	
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
	
	function date_full_time($field){
	
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$6:\$8:\$10";
		return preg_replace($pattern, $replacement,$field);
	}
	
	function swap_dot_date($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+)/i";
		$replacement = "\$5.$3.\$1"; 
		return preg_replace($pattern, $replacement,$field);
	}
	
	function swap_dot_date_without_time($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1";
		return preg_replace($pattern, $replacement,$field);
	}
	
	function swap_dot_date_with_time($field){
			
		$list = preg_split("/-/",$field);
		$pattern = "/(\d+)(-)(\w+)(-)(\d+) (\d+)(:)(\d+)(:)(\d+)/i";
		$replacement = "\$5.$3.\$1 \$6:\$8";
		return preg_replace($pattern, $replacement,$field);
	}
?>