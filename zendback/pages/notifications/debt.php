<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../includes/config.php");
	include_once("../../includes/classes.php");
	include_once("../../includes/functions.php");
	
	$condition	= "";
	if($_SESSION['uclevelkey'] != "2"){ $condition = "AND FOR_ID_CLIENT = '".$_SESSION['cidkey']."'"; }
	$str_notification = "SELECT 
							* 
						 FROM 
							".$tpref."notifications
						 WHERE 
						 	ID_NOTIFICATION_TYPE = '16'
							".$condition."
						 ORDER BY ID_NOTIFICATION DESC LIMIT 0,100";
	$q_notification = $db->query($str_notification);
  ?>
    <table class="table">
        <tbody>
  <?php
  while($dt_notification = $db->fetchNextObject($q_notification)){
		$datetime = explode(" ",$dt_notification->TGLWKTUPDATE);
  ?>
        <tr>
            <td>
            	<span class="label">
					<small><?php echo $dtime->now2indodate2($datetime[0]); ?> <?php echo $datetime[1]; ?></small>
                </span>
                <br />
                <?php echo $dt_notification->NOTIFICATION_VALUE; ?>
            </td>
        </tr>
  <?php
  }
  ?>
        </tbody>
    </table>
  <?php
}else{
	defined('mainload') or die('Restricted Access');
}