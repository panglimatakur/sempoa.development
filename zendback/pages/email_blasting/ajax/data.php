<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$show 		= isset($_POST['show']) 	? $_POST['show'] 	: "";
	
	if(!empty($show) && $show == "destiny"){
		$destiny 	= isset($_POST['destiny']) 	? $_POST['destiny'] : "";
		$search 	= isset($_POST['search']) 	? $_POST['search'] : "";
		if($destiny == "komunitas"){
			echo "<span class='code'>Pilih salah satu atau beberapa nama Komunitas dibawah ini</span><br>";
			$str_list_comm	= "SELECT ID_COMMUNITY,NAME FROM ".$tpref."communities WHERE ID_COMMUNITY IS NOT NULL AND STATUS_ACTIVE = '2' AND NAME LIKE '%".$search."%' ORDER BY ID_COMMUNITY ASC";
			$q_list_comm	= $db->query($str_list_comm);
			while($dt_comm = $db->fetchNextObject($q_list_comm)){ ?>
				<div class='col-md-4 dest_list' id="id_comm_<?php echo $dt_comm->ID_COMMUNITY; ?>" style="margin:4px 4px 0 0;	" onclick="pick_this('komunitas','<?php echo $dt_comm->ID_COMMUNITY; ?>')">
					<b><?php echo $dt_comm->NAME; ?></b>
                    <input type="hidden" id="val_comm_<?php echo $dt_comm->ID_COMMUNITY; ?>" value='"nama":"<?php echo trim($dt_comm->NAME); ?>"'/>
                </div>
			<?php }
		}
		if($destiny == "personal"){
			echo "<span class='code'>Pilih salah satu atau beberapa nama Pengguna dibawah ini</span><br>";
			$str_user	= "SELECT ID_CLIENT,ID_USER,USER_NAME FROM system_users_client WHERE ID_USER IS NOT NULL AND USER_NAME LIKE '%".$search."%' ORDER BY USER_NAME ASC";
			$q_user	= $db->query($str_user);
			while($dt_user = $db->fetchNextObject($q_user)){ 
				$nm_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$dt_user->ID_CLIENT."'");
			?>
				<div class='col-md-4 dest_list' id="id_user_<?php echo $dt_user->ID_USER; ?>" style="margin:4px 4px 0 0;" onclick="pick_this('personal','<?php echo $dt_user->ID_USER; ?>')">
					<b><?php echo $dt_user->USER_NAME; ?></b><br />
                    <b class='code'><?php echo $nm_merchant; ?></b>
                    <input type="hidden" id="val_personal_<?php echo $dt_user->ID_USER; ?>" value='"nama":"<?php echo trim($dt_user->USER_NAME); ?>","merchant":"<?php echo $nm_merchant; ?>"'/>
                </div>
			<?php }
		}
	}
}
?>