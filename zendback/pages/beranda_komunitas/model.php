<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$comm_cond = "";
	if($_SESSION['admin_only'] == "false"){
		if($_SESSION['cparentkey'] != 1){
			if(empty($_SESSION['id_purple'])){
				$jml_community = count($_SESSION['comidkey']);
				if($jml_community > 1){
					$purple_cond 	.= " AND (";
					foreach($_SESSION['comidkey'] as &$id_community){
						$comm_cond .= "ID_COMMUNITY = '".$id_community."' OR ";	
					}
					$comm_cond	.= substr_replace($purple_cond, "", -4).")";
				}else{
					$comm_cond .= " AND ID_COMMUNITY = '".$_SESSION['comidkey'][0]."'";
				}
			}else{
				$comm_cond .= " AND BY_ID_PURPLE = '".$_SESSION['id_purple']."'";
			}
			$cname = $_SESSION['cname'];
		}else{
			$comm_cond .= " AND BY_ID_PURPLE = '".$_SESSION['cidkey']."'";
		}
	}
	
	$str_community	= "SELECT 
							ID_COMMUNITY,
							NAME,
							BY_ID_PURPLE
					   FROM 
					   		".$tpref."communities
					   WHERE 
							ID_COMMUNITY IS NOT NULL
							".@$comm_cond." AND 
							STATUS_ACTIVE = '3' 
					    ORDER BY ID_COMMUNITY ASC";
	$q_community	= $db->query($str_community);
?>