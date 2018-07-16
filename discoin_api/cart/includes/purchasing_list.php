<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$cart_string   		= "SELECT * FROM ".$tpref."customers_carts WHERE STATUS = '3' AND ID_CUSTOMER = '".$_SESSION['sidkey']."' AND ID_CLIENT = '".$id_merchant."' ORDER BY ID_CART DESC";
	$q_cart 			= $db->query($cart_string);
	$num_cart			= $db->numRows($q_cart);
		
	$q_cart_merchant	= $db->query("SELECT ID_CLIENT FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '3' GROUP BY ID_CLIENT ORDER BY ID_CART");
	//$result['content'] = $cart_string; 
	
	if($num_cart > 0){
	$result['content']  .= '
	<div id="form_cart">';
	
	$result['content']  .= '
			<div class="tbl_purchase" style="margin-bottom:3px;">
				<div>
					<table style="width:100%;background:#FFF; " class="deal_tbl">';
	
						while($dt_cart = $db->fetchNextObject($q_cart)){
							$expiration_date= "";
							$hrg_product 	= "";
							$image_list	 	= "";
							$hrg_product	= $dt_cart->PRICE;
							@$nm_merchant	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_merchant."'");
							// JIKA ISI CART PEMBELIAN PRODUCT
							if(!empty($dt_cart->ID_PRODUCT)){
								$id_product 	 = $dt_cart->ID_PRODUCT;
								$id_merchant    = $db->fob("ID_CLIENT",$tpref."products"," WHERE ID_PRODUCT = '".$id_product."'");
								$product        = get_product_info($id_product,"80%");
								$unit_product   = $product['unit'];
								$real_price 	= $product['price'];
								$add_prod 		= $product['add_product'];
								$image_list   .="
								<div class='img_box'>
									<div class='img_box_inline'>
										".$product['photo']."
									</div>
								</div>";
								$jml_prod	  = 1;
							}
							//INFORMASI PRODUCT		
	$result['content']  .= '
					  <tr id="div_'.$dt_cart->ID_CART.'" class="fadein_cart">
						<td>
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td colspan="2" class="w-box-header" style="background:'.$color_1.'">
										<div style="font-weight:100; line-height:15px;">
											'.ucwords(strtolower($product['name'])).'
										</div>
									</td>
								</tr>
								<tr>
									<td width="25%"  align="center" valign="top" style="padding:4px; " >
										 '.$image_list.'
									</td>
									<td style="padding:5px 0 0 5px; vertical-align:top">
										<table class="content">
											<tr>
												<td>Harga</td>
												<td style="paddding-left:9px">
													<b>'.money("Rp.",$real_price).' / '.$unit_product.'</b>
												</td>
											</tr>
											<tr>
												<td>Jumlah</td>
												<td style="paddding-left:9px">
														<b>'.@$dt_cart->AMOUNT.' '.$unit_product.'</b>
												</td>
											</tr>';
											if(!empty($expiration_date) && $expiration_date != "0000-00-00"){
	$result['content']  .= '
												<tr>
													<td width="122" valign="top">Masa Berlaku</td>
													<td width="511" class="value">
														<b class="code">Hingga : 
															'.$dtime->now2indodate2($expiration_date).'
														</b>
													</td>
												</tr>';
											}
											$ch_list_product = substr_count($discount['list_product'],";".$dt_cart->ID_PRODUCT.",");
											if($add_prod != "1" && (($discount['pattern'] == 1 && 
												empty($discount['list_product'])) 
												|| $ch_list_product > 0
												|| $discount['pre_order_status'] == 1)){
	$result['content']  .= '
											<tr style="color:#FF0000">
												<td valign="top">Diskon (%)</td>
												<td style="paddding-left:9px">'.$discount['discount'].' %</td>
											</tr>';
											}
	$result['content']  .= '
											<tr>
												<td valign="top">Total Bayar</td>
												<td style="paddding-left:9px">
													<span class="code">
														'.money("Rp.",@$dt_cart->TOTAL_PRICE).'
													</span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					  </tr>';
					}
	$result['content']  .= '
					</table>
				</div>
			</div>';


	$result['content']  .= '
		</div>';
		
	}else{
	$result['content']  .= '
	<div class="alert alert-error" style="margin:10px;">Tidak ada Pemesanan yang terjadi saat ini</div>';
	}

?>