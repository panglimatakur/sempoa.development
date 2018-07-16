<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 		: "";
	$id_pur 		= isset($_REQUEST['id_pur']) 	? $_REQUEST['id_pur'] 		: "";
	$id_com 		= isset($_REQUEST['id_com']) 	? $_REQUEST['id_com'] 		: "";
	
}
	if(!empty($direction) && $direction == "move"){
		$nm_komunitas	= $db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY = '".$id_com."'");
		
		$purple_cond	= " AND (";
		foreach($_SESSION['id_purples'] as &$id_purple){
			$purple_cond .= "BY_ID_PURPLE = '".$id_purple."' OR ";	
		}
		$purple_cond	= substr_replace($purple_cond, "", -4).")";
		
		$str_list_comm	= "SELECT ID_COMMUNITY,NAME,BY_ID_PURPLE FROM ".$tpref."communities WHERE ID_COMMUNITY IS NOT NULL ".$purple_cond." ORDER BY ID_COMMUNITY ASC";
		$q_community	= $db->query($str_list_comm);
	?>
        <div class='ibox-title' style="text-align:center">Pilih Komunitas Tujuan</div>
        <div class='form-group'>
        	Anda akan pindah dari komunitas <b class="code"><?php echo $nm_komunitas; ?></b> dan bergabung 
            <label>Ke Komunitas</label>
            <select id="to_comm">
                <option>--PILIH KOMUNITAS--</option>
                <?php while($dt_comm = $db->fetchNextObject($q_community)){ ?>
                    <option value='<?php echo $dt_comm->ID_COMMUNITY; ?>'><?php echo $dt_comm->NAME; ?></option>
                <?php } ?>	
            </select>
        </div>
        <div class='form-group' id="dir_load" style="text-align:center"></div>
        <input type="hidden" id="from_id_comm" value="<?php echo $id_com; ?>"/>
        <input type="hidden" id="from_id_pur" value="<?php echo $id_pur; ?>"/>
    <?php	
	}
?>

