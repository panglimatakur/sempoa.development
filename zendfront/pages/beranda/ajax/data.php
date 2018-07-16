<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction = isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : ""; 

	if((!empty($direction) && $direction == "next_merchant")){
		$last_id 		= isset($_REQUEST['last_id']) 	? $_REQUEST['last_id'] : ""; 
		$q_client 		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT < '".$last_id."' AND CLIENT_ID_PARENT != '1' AND ACTIVATE_STATUS = '3' ORDER BY ID_CLIENT DESC LIMIT 0,6");
		while($dt_client = $db->fetchNextObject($q_client)){ $last_id = $dt_client->ID_CLIENT; ?>
			<div class="client_list">
				<?php if(is_file($basepath."/files/images/logos/".$dt_client->CLIENT_LOGO)){?>
					<img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo str_replace(" ","%20",$dt_client->CLIENT_LOGO); ?>" alt="Logo <?php echo $dt_client->CLIENT_NAME; ?>" style="width:40%;" >
				<?php }else{ ?>
					<img src="<?php echo $dirhost; ?>/files/images/no_image.jpg" alt="Logo <?php echo $dt_client->CLIENT_NAME; ?>" style="width:40%;">
				<?php } ?>
				<div class="col-cnt" style="height:170px;position:relative">
					<h2 style="font-size:13px;"><?php echo $dt_client->CLIENT_NAME; ?></h2>
					<p style="text-align:justify"><?php echo cutext($dt_client->CLIENT_DESCRIPTIONS,120); ?></p>
					<a href="<?php echo $dirhost; ?>/<?php echo $dt_client->CLIENT_APP; ?>.coin" class="more" title="Discoin <?php echo $dt_client->CLIENT_NAME; ?>" target="_blank">view more</a>
				</div>
			</div>
		<?php } ?>
		<div class="cl">&nbsp;</div>
    	<span id="next_merchant" data-info="<?php echo $last_id; ?>"></span>
    <?php
	}	
}else{  
	defined('mainload') or die('Restricted Access'); 
}
?>