<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function merchant_list($parent,$deep){
	global $tpref;
	global $db;
	$deep = $deep*2; 
	$q_merchant 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='".$parent."' ORDER BY CLIENT_NAME ASC");
	$num_merchant 	= $db->numRows($q_merchant);
	if($num_merchant >0){
		while($dt_merchant = $db->fetchNextObject($q_merchant)){
?>
		<option class="item" value="<?php echo $dt_merchant->ID_CLIENT; ?>" 
        		data-parent="<?php echo $parent; ?>" style="margin-right:10px;"><?php echo str_repeat("&nbsp;",$deep).$dt_merchant->CLIENT_NAME; ?></option>
<?php
		echo merchant_list($dt_merchant->ID_CLIENT,$deep); 
		}
	}
}

$q_merchant 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ORDER BY CLIENT_NAME ASC");


$level_condition = "";
if($_SESSION["admin_only"] == "true"){
	$query_str 	= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ORDER BY CLIENT_NAME ASC";
	
}else{
	$query_str 	= "SELECT * FROM ".$tpref."clients WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ORDER BY CLIENT_NAME ASC";
	$parent_id = $id_client;
	$level_condition .= "AND ID_CLIENT != '1' AND (ID_CLIENT = '0' OR ID_CLIENT = '".$_SESSION['cidkey']."') "; 
}
$qlink 			= $db->query($query_str);



$query_level = $db->query("SELECT * FROM system_master_client_users_level WHERE NAME IS NOT NULL ".@$level_condition." ORDER BY NAME ASC");

if(!empty($direction) && $direction == "edit" ){
	$qcont			=	$db->query("SELECT * FROM system_users_client WHERE ID_USER='".$no."'");
	$dtedit			=	$db->fetchNextObject($qcont);
	$parent_id		= 	$dtedit->ID_CLIENT; 
	$name			=	$dtedit->USER_NAME;
	$user_name 		= 	$dtedit->USER_USERNAME; 	
	$user_pass 		= 	$dtedit->USER_PASS; 	
	$user_email 	= 	$dtedit->USER_EMAIL; 	
	$photo 			= 	$dtedit->USER_PHOTO; 		
	$level 			= 	$dtedit->ID_CLIENT_USER_LEVEL; 
	$add_info 		= 	$dtedit->ADDITIONAL_INFO; 
	$insert_proses 	= 	$dtedit->INSERT_DATA; 
	$edit_proses 	= 	$dtedit->EDIT_DATA; 
	$delete_proses 	= 	$dtedit->DELETE_DATA; 
	@$nama_parent		=	$db->fob("CLIENT_NAME","".$tpref."clients","where ID_CLIENT='".$parent_id."'");
	$q_doc			=	$db->query("SELECT * FROM ".$tpref."documents WHERE ID_USER='".$no."'");
	$num_doc		= 	$db->numRows($q_doc);
}



//=====================================================================


if(empty($id_client_form)){
	$id_client_form	 = $_SESSION['cidkey'];
}

if($_SESSION['uclevelkey'] != 1){
	$condition_1 = 	"WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$_SESSION['cidkey'].",%'";
	$condition_2 =  "AND (ID_CLIENT='".$id_client_form."' ".parent_condition($id_client_form).") ";
}else{
	
	$condition_1 = "";
	if(empty($direction)){
		$condition_2 = "";
	}else{
		$condition_2 =  "AND (ID_CLIENT='".$id_client_form."' ".parent_condition($id_client_form).") ";
	}
}
if(!empty($direction) && $direction == "edit"){ $condition_2 .= " AND ID_USER != '".$no."' "; }
$query_branch 	= $db->query("SELECT * FROM ".$tpref."clients ".$condition_1." ORDER BY CLIENT_NAME");

$query_str		= "SELECT * FROM system_users_client WHERE ID_CLIENT IS NOT NULL  ".$condition_2." ORDER BY ID_USER DESC";
$link_str		= "";
$q_user 		= $db->query($query_str." LIMIT 0,50");
$num_user 		= $db->recount($query_str);	
?>