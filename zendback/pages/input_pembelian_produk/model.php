<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if((empty($direction) && empty($msg)) || (!empty($direction) && $direction == "show") || (!empty($direction) && $direction !="export")){
	$class_multi_proses = "active";
}
$status_lunas_label = 2;
?>
