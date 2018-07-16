<?php
session_start();
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
if(!defined('mainload')) { define('mainload','SEMPOA',true); }

	include_once("../../includes/config.php");
	include_once("../../includes/classes.php");
	include_once("../../includes/functions.php");
	
	$direction 		= isset($_REQUEST['direction']) 		? $_REQUEST['direction'] 		: "";
	$id_merchant 	= isset($_REQUEST['id_merchant']) 		? $_REQUEST['id_merchant'] 		: "";
	$id_product 	= isset($_REQUEST['id_product']) 		? $_REQUEST['id_product'] 		: "";
	$besar 			= isset($_REQUEST['besar']) 			? $_REQUEST['besar'] 		: "";
	$price 			= isset($_REQUEST['price']) 			? $_REQUEST['price'] 			: "";
	$discount 		= isset($_REQUEST['discount']) 			? $_REQUEST['discount'] 		: "";
	$filter_merchant= isset($_REQUEST['filter_merchant']) 	? $_REQUEST['filter_merchant'] 	: "";
	$callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) 		? $_REQUEST['mycallback'] 		: "";
	$result['content'] = "";
	
	if(!empty($direction) && $direction == "load"){
		//$discount 		= ch_diskon($id_merchant,$_SESSION['csidkey']);
	
		if(!empty($id_product)){
			$jumlah_order   = 1;
			$ttl_price  	= $price*$jumlah_order;
			if(!empty($discount)){ 
				$discount_price 	= ($ttl_price/100)*$discount;
				$ttl_price 			= $ttl_price - $discount_price;
			}
			@$ch_order 	 = $db->recount("SELECT ID_PRODUCT FROM ".$tpref."customers_carts WHERE ID_PRODUCT = '".$id_product."' AND ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0'");	
			if($ch_order == 0){
				$container 		= array(1=>
										array("ID_CLIENT",@$id_merchant),
										array("ID_PRODUCT",@$id_product),
										array("ID_CUSTOMER",@$_SESSION['sidkey']),
										array("PRICE",@$price),
										array("AMOUNT",@$jumlah_order),
										array("DISCOUNT",@$discount),
										array("TOTAL_PRICE",@$ttl_price),
										array("STATUS","0"),
										array("TGLUPDATE",$tglupdate." ".$wktupdate));
				$db->insert($tpref."customers_carts",$container);
			}
		}
		
		
		$q_merchant			= $db->query("SELECT COLOUR,CLIENT_LOGO,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT = '".$id_merchant."'");
		$dt_merchant 		= $db->fetchNextObject($q_merchant);
		@$nm_merchant		= $dt_merchant->CLIENT_NAME;
		@$logo_merchant 	= $dt_merchant->CLIENT_LOGO;
		@$color_merchant 	= explode(";",$dt_merchant->COLOUR);
		@$color_1			= $color_merchant[0];
		@$client_address 	=  client_address($id_merchant);
				
	$result['content']  .= '
		<style type="text/css">
		.no-m-btm{margin-bottom:5px; }
		.deal_tbl td.overflow{
			max-height:80px;
			overflow:scroll;	
		}
		.tabbable-bordered .nav-tabs > li.active {
			border-top: 3px solid '.$color_1.';
			margin-top: 0;
			position: relative;
		}		
		.content tr td.value{ font-weight:100; margin:0; padding:0 0 0 15px; border:1px dashed #CCCCCC; }
		.value{ margin-left:15px; }
		</style>
		
		
		<div class="row" style="padding:0 10px 10px 10px">';
			/*if(empty($filter_merchant)){
		$result['content']  .= '
			<div class="form-group"> 
				<label>Merchant</label>
				<select id="merchant_cart" style="margin-top:-5px; font-size:9px;" class="input-sm form-control">';
				$q_cart_merchant	= $db->query("SELECT ID_CLIENT FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0' GROUP BY ID_CLIENT ORDER BY ID_CART");
					while($dt_cart_merchant = $db->fetchNextObject($q_cart_merchant)){
						$select 		  = "";
						$id_cart_merchant = $dt_cart_merchant->ID_CLIENT; 
						$nm_cart_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$id_cart_merchant."'");
						if($id_cart_merchant ==  $id_merchant){ $select = "selected"; }
$result['content']  .= '<option value="'.$id_cart_merchant.'" '.$select.'>'.$nm_cart_merchant.'</option>';
					}
$result['content']  .= '
				</select>
			</div>';
			}*/
		$result['content']  .= '
			<div class="tabbable tabbable-bordered">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#tb1_a">Daftar Belanjaan</a></li>
					<li><a data-toggle="tab" href="#tb1_b">Daftar Pemesanan</a></li>
				</ul>
				<div class="tab-content" style="margin-top:0;padding:2px">
					<div id="tb1_a" class="tab-pane active">
						<p>';
						
						
		include $call->inc("discoin_api/cart/includes","shopping_list.php");
			
			
		$result['content']  .= '
						</p>
					</div>
					<div id="tb1_b" class="tab-pane">
						<p>';
		
		//include $call->inc("discoin_api/includes","purchasing_list.php");
		
		$result['content']  .= '
						</p>
					</div>
				</div>
			</div>';
		
		$result['content']  .= '
		</div>';
	} 
	
	
	
	echo $callback.'('.json_encode($result).')';
}?>

