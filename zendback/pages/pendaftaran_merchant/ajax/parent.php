<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','ADMIN',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$parent_id		=	$_REQUEST['parent_id'];
	$qchild			=	$db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$parent_id."' ORDER BY ID_CLIENT");
	$dtchild		=	$db->fetchNextObject($qchild);
	$no 			= 	$dtchild->ID_CLIENT;
	$nama_parent	= 	$dtchild->CLIENT_NAME;
}
else{  defined('mainload') or die('Restricted Access'); }
?>
<input type="hidden" name="parent_id" value='<?php echo $parent_id; ?>' id='parent_id'/>
<div class="alert alert-info">
        Di bawah ini adalah Cabang dari <?php echo "<b><u>".$nama_parent."</u></b>"; ?>
        <a href='javascript:void();' onclick='resetchild();' style="float:right;color:#6D8DBC">
        	<i class="icsw16-trashcan"></i>
        </a>
        <div class="clearfix"></div>
</div>