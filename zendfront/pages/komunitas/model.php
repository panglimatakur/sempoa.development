<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition = "";
	if(!empty($parameters)){
		$condition = " AND ID_COMMUNITY = '".$parameters."'";
	}
	$str_community	= "SELECT a.ID_COMMUNITY,a.NAME FROM ".$tpref."communities a,".$tpref."communities_merchants b 
					   WHERE a.ID_COMMUNITY = b.ID_COMMUNITY AND a.STATUS_ACTIVE = '3' GROUP BY b.ID_COMMUNITY";	
	//echo $str_merchant;
	$q_community 	= $db->query($str_community);
?>