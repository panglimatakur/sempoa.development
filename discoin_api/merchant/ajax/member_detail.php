<?php 
session_start();
if(!empty($_SESSION['sidkey']) && !empty($_GET['sempoakey']) && $_GET['sempoakey'] == "99765") {

	define('mainload','SEMPOA',true); 
	include("../../../includes/config.php");
	include("../../../includes/classes.php");
	include("../../../includes/functions.php");
	include("../../../includes/declarations.php");
	
	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$id_member 		= isset($_REQUEST['id_member']) 	? $_REQUEST['id_member'] 	: "";
	$callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	if(!empty($direction) && $direction == "load"){
		$str_user_subject	= "SELECT TGLUPDATE,CUSTOMER_NAME,CUSTOMER_PHOTO FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_member."'";
		$q_user_subject		= $db->query($str_user_subject); 
		$dt_user_subject	= $db->fetchNextObject($q_user_subject);
		@$user_foto_subject	= $dt_user_subject->CUSTOMER_PHOTO;
		
		$result['content'] = 
		"
		<div class='col-xs-12 col-sm-12 col-md-12' style='padding:0'>
			<div class='thumbnail'>";
				if(!empty($user_foto_subject) && is_file($basepath."/files/images/members/big/".$user_foto_subject)){
					$result['content'] .= 
					'<img src="'.$dirhost.'/files/images/members/big/'.$user_foto_subject.'" style="width:100%"/>';
				}else{
					$result['content'] .= 
					'<img src="'.$dirhost.'/files/images/no_image.jpg" style="width:100%"/>';
				}
		$result['content'] .= '
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0"><b>Nama</b></div>
			<div class="col-xs-7 col-sm-7 col-md-7">
				'.$dt_user_subject->CUSTOMER_NAME.'
			</div>
			<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0">
				<b>Bergabung</b>
			</div>
			<div class="col-xs-7 col-sm-7 col-md-7">
				'.$dtime->now2indodate2($dt_user_subject->TGLUPDATE).'
			</div>
		</div>
		<div class="clearfix"></div>';
		echo $callback.'('.json_encode($result).')';
	}
	
}
?>