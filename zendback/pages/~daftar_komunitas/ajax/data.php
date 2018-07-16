<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
		$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<div class="ibox float-e-margins">
<?php
if((!empty($display) && $display == "list_report")){
	@$lastID 	= $_REQUEST['lastID'];
	if(!empty($_REQUEST['id_com']))	{ $id_com 		= $sanitize->number($_REQUEST['id_com']); 		}
	
	$condition		= "";
	if(!empty($id_com)){ $condition .= " AND ID_COMMUNITY = '".$id_com."'";	}
	$str_list_comm	= "SELECT * FROM ".$tpref."communities WHERE ID_COMMUNITY > ".$lastID." ".$condition." ORDER BY ID_COMMUNITY ASC";
	#echo $str_list_comm;
	$q_list_comm	= $db->query($str_list_comm." LIMIT 0,3");
	while($dt_comm	= $db->fetchNextObject($q_list_comm)){
		$newLastID	=  $dt_comm->ID_COMMUNITY; 
	?>
	<div class="w-box-content col-md-4 merchants_class">
        <div class='ibox-title'><?php echo @$dt_comm->NAME; ?></div>
        <?php
            $q_merchant = $db->query("SELECT * FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' ORDER BY ID_COMMUNITY_MERCHANT ASC");
		?>
        <table class="table table-striped " style="width:100%">
            <tbody>
            <?php
            while($dt_merchant	= $db->fetchNextObject($q_merchant)){
                $q_client 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_merchant->ID_CLIENT."'");
                $dt_client	= $db->fetchNextObject($q_client);
            ?>
                  <tr id="tr_<?php echo @$dt_merchant->ID_COMMUNITY_MERCHANT; ?>">
                    <td width="46">
                    <?php if(allow('delete') == 1){?>
                        <a href='javascript:void()' onclick="removal('<?php echo $dt_merchant->ID_COMMUNITY_MERCHANT; ?>','<?php echo $dt_merchant->ID_COMMUNITY; ?>')" class="btn btn-mini" title="Delete"> <i class="icon-trash"></i> </a>
                    <?php } ?>
                    </td>
                    <td width="127">
                        <?php echo getclientlogo($dt_client->ID_CLIENT," class='thumbnail' style='width:50px'"); ?>
                    </td>
                    <td width="835"><?php echo @$dt_client->CLIENT_NAME; ?></td>
                </tr>
             <?php } ?>
            </tbody>
        </table>
	</div>
	<?php } 
}
?>
<div class='wrdLatest' data-info='<?php echo $newLastID; ?>'></div>
</div>
