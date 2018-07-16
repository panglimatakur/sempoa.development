<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function merchant_child($parent){
	global $tpref;
	global $db;
	global $lparam;
	global $ajax_dir;
	
	$q_merchant_child 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='".$parent."' ORDER BY CLIENT_NAME ASC");
	$num_merchant_child 	= $db->numRows($q_merchant_child);
	if($num_merchant_child >0){
?>
        <ul>
<?php
		while($dt_merchant_child = $db->fetchNextObject($q_merchant_child)){
		?>
            <li  id="li_<?php echo $dt_merchant_child->ID_CLIENT; ?>" class="expandable" >
                <div class='link-name pull-left'>
                	<p class="folder">
                        <a href="javascript:void(0);" onclick="getparent('<?php echo $dt_merchant_child->ID_CLIENT; ?>','divparent_id');" title="<?php echo $dt_merchant_child->ID_CLIENT; ?>">
                            &nbsp;<?php echo $dt_merchant_child->CLIENT_NAME; ?>
                        </a>
                    </p>
                </div>
                <div class='link-btn pull-right'>
                    <div class="btn-group">
                        <button type="button" class='btn btn-sm btn-warning fancybox fancybox.ajax' title="View">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button type="button" onclick="delete_link('<?php echo $dt_merchant_child->ID_CLIENT; ?>');" class="btn btn-sm btn-info" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-sempoa-3">
                            <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_merchant_child->ID_CLIENT; ?>"  title="Edit" style="color:#FFF">
                                <i class="fa fa-pencil-square-o"></i>
                            </a>
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php echo merchant_child($dt_merchant_child->ID_CLIENT); ?>
            </li>
		<?php
		}
?>
	</ul>
<?php
	}
}

$condition 			 	= "";
if($_SESSION['admin_only'] == 'false'){ $condition = " AND BY_ID_PURPLE='".$_SESSION['cidkey']."'";  }
$str_merchant_parent 	= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ".$condition." ORDER BY CLIENT_NAME ASC";
$q_merchant_parent 	 	= $db->query($str_merchant_parent);


$q_client_level 		= $db->query("SELECT ID_CLIENT_LEVEL,NAME FROM system_master_client_level WHERE ID_CLIENT_LEVEL != '1' ORDER BY ID_CLIENT_LEVEL ASC");
if(!empty($direction) && $direction == "edit" ){
	$qcont				=	$db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$no."' ");
	$dtedit				=	$db->fetchNextObject($qcont);
	$nama				=	$dtedit->CLIENT_NAME;
	$photo				=	$dtedit->CLIENT_LOGO;
	$propinsi 			= 	$dtedit->CLIENT_PROVINCE; 	
	$kota 				= 	$dtedit->CLIENT_CITY; 		
	$alamat 			= 	$dtedit->CLIENT_ADDRESS; 		
	$tlp 				= 	$dtedit->CLIENT_PHONE; 		
	$nmkontak 			= 	$dtedit->CLIENT_PERSON_NAME; 		
	$kontak 			= 	$dtedit->CLIENT_PERSON_CONTACT; 		
	$email 				= 	$dtedit->CLIENT_EMAIL; 		
	$website 			= 	$dtedit->CLIENT_URL; 		
	$parent_id			=	$dtedit->CLIENT_ID_PARENT;
	$parent_id_list		=	$dtedit->CLIENT_ID_PARENT_LIST;
	$deskripsi			=	$dtedit->CLIENT_DESCRIPTIONS;
	$statement			=	$dtedit->CLIENT_STATEMENT;
	$expiration			=	$dtime->date2indodate($dtedit->EXPIRATION_DATE);
	
	$color				=	explode(";",$dtedit->COLOUR);
	@$w[1] 				= 	$color[0]; //#993366
	@$w[2] 				= 	$color[1]; //#732b4f
	$id_client_level	=	$dtedit->ID_CLIENT_LEVEL;
	$app				=	$dtedit->CLIENT_APP;
	$purple				= 	$dtedit->BY_ID_PURPLE;
}
if(!empty($parent_id)){
	$nama_parent		=	$db->fob("NAMA_CLIENT","".$tpref."clients","where ID_CLIENT='".$parent_id."'");
}
/*$q_client				=	$db->query("SELECT * FROM ".$tpref."clients ORDER BY ID_CLIENT");
while($dt_client		=	$db->fetchNextObject($qcont)){
	$client_code 		= 	substr(md5($dt_client->CLIENT_NAME.$dt_client->CLIENT_EMAIL.$dt_client->ID_CLIENT.$dt_client->TGLUPDATE),0,17);
	echo $client_code."<br>";
	$container2 = array(1=>
		array("CLIENT_COIN",strtoupper(@$client_code)));
	$db->update($tpref."clients",$container2," WHERE ID_CLIENT='".$dt_client->ID_CLIENT."' ");
}*/
?>
