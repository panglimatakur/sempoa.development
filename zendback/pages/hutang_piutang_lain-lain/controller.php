<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$noref			=	isset($_REQUEST['noref']) 			? $_REQUEST['noref']	:"";
	$parent_id		=	isset($_REQUEST['parent_id']) 		? $_REQUEST['parent_id']	:"";
	$status_lunas	=	isset($_REQUEST['status_lunas']) 	? $_REQUEST['status_lunas']	:"";
	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}
	
	if(empty($status_lunas)){ $status_lunas = 1; }
	if(empty($tgl_1)){ 
		$tgl_1_new 	= date("d/m/Y"); 
		$tgl_1_ex	= explode("-",$dtime->yesterday(7,date("d"),date("m"),date("Y")));
		$tgl_1		= $tgl_1_ex[2]."/".$tgl_1_ex[1]."/".$tgl_1_ex[0];
	} 
	if(empty($tgl_2)){ 
		$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
		$tgl_2_new 		=  date("d/m/Y", $dateformat);
		$tgl_2			= date("d/m/Y");
	} 
?>