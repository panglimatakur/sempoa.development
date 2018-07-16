<?php defined('mainload') or die('Restricted Access'); ?>
<?php
//CHART POLLING
$str_query		= "SELECT * FROM ".$tpref."pollings WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).")"; 
$q_polling 		= $db->query($str_query);
$num_polling	= $db->numRows($q_polling);
?>

