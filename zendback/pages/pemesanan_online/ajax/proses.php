<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_POST['direction'])  ? $_POST['direction']  	 : "";
	$id_merchant  = isset($_POST['id_merchant'])? $_POST['id_merchant']   : "";
	$id_deal 	  = isset($_POST['id_deal']) 	? $_POST['id_deal'] 	   : "";
	$st 		   = isset($_POST['st']) 		 ? $_POST['st'] 		 	: "";
	
	if($direction == "set_status"){
			$subject 	   = "Status Proses Order";
	 		$q_cart   	  	= $db->query("SELECT * FROM ".$tpref."customers_carts  WHERE ID_CUSTOMER = '".$id_deal."' AND STATUS = '3' AND ID_CLIENT = '".$id_merchant."'");
			$nama_coin	 = $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER = '".$id_deal."'");
			$nm_merchant   = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$id_merchant."'");
			$db->query("UPDATE ".$tpref."customers_carts SET STATUS = '".$st."' WHERE ID_CUSTOMER = '".$id_deal."' AND ID_CLIENT = '".$id_merchant."'");
		if($st == "4"){
			$info_pesan	= "
			<style type='text/css'>
				.tbl_content{
					margin-bottom:10px;
					border-radius:5px;
					-moz-border-radius:5px;
					-webkit-border-radius:5px;
					border:1px solid #999;
					background:#FFF;
				}
				.tbl_content td{
					padding:4px;	
					border-bottom:1px dashed #999999;
				}
			</style>
			
			<table style='width:100%;' class='deal_tbl'>";
			while($dt_cart     = $db->fetchNextObject($q_cart)){
				//INFORMASI PRODUCT		
				$dt_product = get_product_info($dt_cart->ID_PRODUCT,"90%");
				$info_pesan .= '
                    <tr>
                       <td width="10%">'.$dt_product['photo'].'</td>
                       <td width="90%" class="deal_tbl">
                       		<b>'.$dt_product['name'].'</b>
                       		<table width="100%" class="tbl_content">
                            	<tr>
                                	<td width="19%">Harga</td>
                                	<td width="81%">: '.money("Rp.",$dt_cart->PRICE).'</td>
                                </tr>
                                <tr>
                                	<td>Jumlah</td>
                                	<td>: '.$dt_cart->AMOUNT.' '.$dt_product['unit'].'</td>
                                </tr>
                                <tr>
                                	<td>Discount</td>
                                	<td>: '.$dt_cart->DISCOUNT.' %</td>
                                </tr>
								<tr>
                                	<td>Total</td>
                                	<td>: '.money("Rp.",$dt_cart->TOTAL_PRICE).'</td>
                            	</tr>
                            </table>
                       </td>
                    </tr>';
			}
			$info_pesan	.= "</table>";
			
			$msg 			= "
			<div style='font-family:Verdana, Geneva, sans-serif;'>
				Dear ".@$nama_coin.",<br>
				".$nm_merchant." sedang memproses pemesanan anda;
				<br> 
				<br>
				".$info_pesan."
				<br>
				<br>
				Terimakasih<br><br>
				<img src='".$logo_path."'><br>
				
			</div>	";		
			//send_cust_msg("user",$_SESSION['uidkey'],$id_customer,$subject,$msg);
			//echo trim($email_coin)."<br>".$subject."<br>".$msg."<br>".$from."<br>".$type."<br>";
			echo $msg;
		}
	}
}
?>