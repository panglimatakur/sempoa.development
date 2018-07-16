<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','ADMIN',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$parent_id		=	$_REQUEST['parent_id'];
	$qchild			=	$db->query("SELECT * FROM system_pages_client WHERE ID_PAGE_CLIENT='".$parent_id."'");
	$dtchild		=	$db->fetchNextObject($qchild);
	$no 			= 	$dtchild->ID_PAGE_CLIENT;
	$nama_parent	= 	$dtchild->NAME;
}
else{  defined('mainload') or die('Restricted Access'); }
?>
<input type="hidden" name="parent_id" value='<?php echo $parent_id; ?>' id='parent_id'/>
<div class="alert alert-info">
    Di bawah adalah Nama anak dari <?php echo "<b><u>".$nama_parent."</u></b>"; ?>
    <a href='javascript:void();' onclick='resetchild();' style="float:right;">
        <i class="fa fa-trash"></i>
    </a>
</div>
