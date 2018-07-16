<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
		$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<?php
if((!empty($display) && $display == "list_report")){
	@$lastID 	= $_REQUEST['lastID'];
	$query_str		= "SELECT ID_CLIENT,CLIENT_NAME,CLIENT_STATEMENT,CLIENT_URL,ID_CLIENT_LEVEL FROM ".$tpref."clients WHERE ID_CLIENT > '".$lastID."' AND ID_CLIENT_LEVEL = '2'  ORDER BY ID_CLIENT ASC"; 
	echo $query_str;
	$q_user 		= $db->query($query_str." LIMIT 0,20");
	$num_user		= $db->numRows($q_user);
	while($dt_user	= $db->fetchNextObject($q_user)){ 
			$lastID = $dt_user->ID_CLIENT;
	  ?>
	  <tr id="tr_<?php echo $dt_user->ID_CLIENT; ?>" style="cursor:pointer" onclick="pick('<?php echo $dt_user->ID_CLIENT; ?>')">
		<td style="text-align:center">
			<?php echo getclientlogo($dt_user->ID_CLIENT," class='thumbnail' style='width:50px'"); ?>
			<input type="hidden" name="client[]" value="<?php echo $dt_user->ID_CLIENT; ?>" />
		</td>
		<td>
			<b style="color:#C00"><?php echo $dt_user->CLIENT_NAME; ?></b><br>
            <?php
				$statement 		= "";
				$q_discount_2 	= $db->query("SELECT VALUE,PIECE,STATEMENT,ID_PRODUCTS FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$dt_user->ID_CLIENT."'");
				$num_discount	= $db->numRows($q_discount_2);
				if($num_discount > 0 || !empty($dt_user->CLIENT_STATEMENT)){
			?>
            <div style="font-size:11px; max-height:200px;" class='code'>
				<?php 
                if($num_discount > 0){
                    $persen	= "";
                    $rupiah	= "";
                    while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
                        if($dt_discount_2->PIECE == "persen"){ $persen = "%"; 	}
                        $id_product_discs 	= $dt_discount_2->ID_PRODUCTS;
                   	 	echo  "<div style='border-bottom:1px dashed #666666'>
									Diskon ".$rupiah."".$dt_discount_2->VALUE."".$persen." 
									".$dt_discount_2->STATEMENT."
									<div style='clear:both; heigth:4px'></div>
                           	   </div>";
                    }
                }else{
                    echo $dt_user->CLIENT_STATEMENT;
                }
                ?>
            </div>
            <?php } ?>
		</td>
	</tr>
	<?php }  
}
?>
<div class='wrdLatest' data-info='<?php echo $newLastID; ?>'></div>
