<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition = "";
	if(!empty($direction) && $direction == "show"){
		if(!empty($_REQUEST['subject_report']))	{ $condition 	.= " AND POST_TITLE LIKE '%".$subject_report."%'"; 		}
		if(!empty($_REQUEST['question_report'])){ $condition 	.= " AND POST_CONTENT LIKE '%".$question_report."%'"; 	}
		$class_report = "active";
	}else{
		$class_proses = "active";
		$condition 	  = " AND (ID_POST_PARENT IS NULL OR ID_POST_PARENT = 0)";
	}
	$jml_com		= count($_SESSION['comidkey']);
	$y 				= 0;
	$com_condition	= " OR (DESTINY = 'komunitas' AND (";
	if($id_client != "1"){
		foreach($_SESSION['comidkey'] as &$com_id){
			$y++;
			$op	= "";
			if($y < $jml_com){ $op = " OR "; } 
			$com_condition .= "PARTICIPANTS LIKE '%:".$com_id.";%' ".$op;
		}
	}else{
		$com_condition .= " ID_CLIENT IS NOT NULL";	
	}
	$com_condition .= "))";
	
	$str_query		= "SELECT * FROM ".$tpref."posts WHERE ID_POST IS NOT NULL ".$condition." AND DESTINY = 'umum' OR (DESTINY = 'personal' AND PARTICIPANTS LIKE '%:".$_SESSION['uidkey'].";%') ".$com_condition." ORDER BY ID_POST DESC"; 
	#echo $str_query;
	$q_polling 		= $db->query($str_query." LIMIT 0,10");
	$num_polling	= $db->recount($str_query);
	$command_button = "insert";
	
	
	
	if(!empty($direction) && $direction == "edit"){
		$q_polling_edit = $db->query("SELECT * FROM ".$tpref."posts WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_POST='".$no."'");
		$dt_polling_edit= $db->fetchNextObject($q_polling_edit);
		
		$id_post 			= $dt_polling_edit->ID_POST; 	
		$subject 			= $dt_polling_edit->POST_TITLE; 		
		$meta_title 		= $dt_polling_edit->TITLE; 		
		$meta_keywords 		= $dt_polling_edit->KEYWORDS; 		
		$meta_description 	= $dt_polling_edit->DESCRIPTIONS; 		
		$question 			= $dt_polling_edit->POST_CONTENT;
		$cover 				= $dt_polling_edit->POST_COVER;
		$command_button 	= "save";
	}
?>