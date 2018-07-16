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
						 (ID_NOTIFICATION_TYPE = '7' OR ID_NOTIFICATION_TYPE = '8' OR ID_NOTIFICATION_TYPE = '9' OR ID_NOTIFICATION_TYPE = '10' OR ID_NOTIFICATION_TYPE = '11' OR ID_NOTIFICATION_TYPE = '12') 
						 ".$condition."
						 ORDER BY ID_NOTIFICATION DESC LIMIT 0,100";
	//echo $str_notification;
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
            <td>
            <a href='<?php echo $dirhost; ?>/?page=input_distribusi'>
           	 <button type="button" class="btn btn-mini" ><i class="icsw16-magnifying-glass"></i></button>
            </a>
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