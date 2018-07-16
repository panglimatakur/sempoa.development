<?php defined('mainload') or die('Restricted Access'); 	?>
<?php 
/*echo rightaccess($page)."rakses";
$id_page 		= $db->fob("ID_PAGE_CLIENT","system_pages_client","WHERE PAGE='".$page."'");
echo "SELECT * FROM system_pages_client_rightaccess WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_PAGE_CLIENT='".$id_page."' AND ID_CLIENT_LEVEL='".$_SESSION['uclevelkey']."' AND ID_CLIENT_USER_LEVEL='".$_SESSION['ulevelkey']."' -- ".rightaccess($page);

$r_ori = $db->query("SELECT * FROM system_pages_client_rightaccess WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_PAGE_CLIENT='".$id_page."' AND ID_CLIENT_LEVEL='".$_SESSION['uclevelkey']."' AND ID_CLIENT_USER_LEVEL='".$_SESSION['ulevelkey']."'");
$dt_ori = $db->fetchNextObject($r_ori);
print_r($dt_ori);*/

	if(rightaccess($page) > 0){
		include $call->inc($page_dir,"page.php");
	}else{
		//redirect_page($dirhost);
	}
?>
