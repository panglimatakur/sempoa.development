<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	
	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction']	:"";
	$id_merchant 	= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant']	:"";
	$id_customer 	= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer']	:"";
	
	if(!empty($direction) && $direction == "note_bell_chat"){
		$q_customer 			= $db->query("SELECT 
												CUSTOMER_PHOTO,CUSTOMER_NAME 
											  FROM 
												".$tpref."customers 
											  WHERE 
												ID_CUSTOMER = '".$id_customer."'");
		$dt_customer			= $db->fetchNextObject($q_customer);
		@$customer_foto			= $dt_customer->CUSTOMER_PHOTO;
		if(!empty($customer_foto) && is_file($basepath."/files/images/members/".$customer_foto)) {
				@$customer_foto = "members/".$customer_foto;  			}
		else{	@$customer_foto = "noimage-m.jpg";  							}
		@$customer_foto 		= "<img src='".$dirhost."/files/images/".$customer_foto."' class='img-circle' width='100%'>"; 								
		@$customer_name			= $dt_customer->CUSTOMER_NAME;
		@$wkt 					= $dtime->timeDiff($tglupdate." ".$wktupdate);
		$note_content 			= "Pelanggan <strong>".$customer_name."</strong> mengirimkan pesan chat."; 
		$notif 	= array(1=>
					array("NOTIFICATION_SRC","CUSTOMER"),
					array("NOTIFICATION_TYPE","chat"),
					array("ID_SENDER",$id_customer),
					array("ID_CLIENT",$_SESSION['cidkey']),
					array("ID_USER",$_SESSION['uidkey']),
					array("NOTIFICATION_CONTENT",@$note_content),
					array("UPDATEDATETIME",$tglupdate." ".$wktupdate));
		$db->insert($tpref."notifications",$notif);
	?>	
            <li id="notif_chat_<?php echo $id_customer; ?>"
                class="note_to_page"
                data-url="<?php echo $dirhost; ?>/?page=chat_pelanggan&id_customer=<?php echo $id_customer; ?>">
                <div class="dropdown-messages-box">
                    <a href="#" class="pull-left">
                        <?php echo $customer_foto; ?>
                    </a>
                    <div class="media-body">
                        <small class="pull-right"><?php echo $wkt; ?></small>
                        <?php echo $note_content;?><br>
                        <small class="text-muted">
							<?php echo $dtime->date2indodate($tglupdate)." ".substr($wktupdate,0,5); ?>
                        </small>
                    </div>
                </div>
            </li>
            <li class="divider" id="divider_notif_chat_<?php echo $id_customer; ?>"></li>
	<?php
	}
}else{
	defined('mainload') or die('Restricted Access');
}