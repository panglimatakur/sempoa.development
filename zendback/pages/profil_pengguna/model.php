<?php defined('mainload') or die('Restricted Access'); ?>
<?php

$qedit 			= $db->query("SELECT * FROM system_users_client WHERE ID_USER = '".$_SESSION['uidkey']."' ");
$dtedit 		= $db->fetchNextObject($qedit);
$photo 			= $dtedit->USER_PHOTO;
$name 			= $dtedit->USER_NAME;
$email 			= $dtedit->USER_EMAIL;
$phone 			= $dtedit->USER_PHONE;
$alamat 		= $dtedit->USER_ADDRESS;
$propinsi 		= $dtedit->USER_PROVINCE;
$kota 			= $dtedit->USER_CITY;
$kecamatan 		= $dtedit->USER_DISTRICT;
$kelurahan 		= $dtedit->USER_SUBDISTRICT;

$q_doc			= $db->query("SELECT * FROM ".$tpref."documents 
							  WHERE ID_USER='".$_SESSION['uidkey']."' AND 
							  ID_CLIENT='".$_SESSION['cidkey']."'");

@$num_doc		= $db->numRows($q_doc);
?>