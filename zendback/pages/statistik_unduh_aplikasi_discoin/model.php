<?php defined('mainload') or die('Restricted Access'); ?>
<?php 

$cur_day	 	= date('d'); 
$cur_month		= date("m");
$last_month 	= date("m", strtotime("-1 months"));
$cur_year	 	= date('Y');
$cur_year2	 	= date('Y')-5;
$cur_year3	 	= date('Y');

$condition		= "";
if($_SESSION['uidkey'] != '1'){ $condition = " AND ID_CLIENT = '".$_SESSION['cidkey']."'"; }
@$downloaded 	= $db->recount("SELECT IP_ADDRESS FROM ".$tpref."logs WHERE  ACTIVITY LIKE '%Melihat Halaman Discoin%' AND MONTH(TGLUPDATE) = '".$cur_month."' AND YEAR(TGLUPDATE) = '".$cur_year."' ".@$condition." GROUP BY IP_ADDRESS");
?>