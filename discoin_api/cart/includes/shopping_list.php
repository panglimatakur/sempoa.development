<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$cart_string   		= "SELECT 
								*
							FROM 
								".$tpref."customers_carts a,
								".$tpref."products b 
							WHERE 
								a.ID_CLIENT 	= b.ID_CLIENT 			AND
								a.ID_CLIENT 	= '".$id_merchant."' 	AND
								a.ID_PRODUCT 	= b.ID_PRODUCT 			AND
								a.STATUS 		= 0 					AND
								a.ID_CUSTOMER 	= '".$_SESSION['sidkey']."'
							ORDER BY a.ID_CART DESC";
	$q_cart 			= $db->query($cart_string);
	$num_cart			= $db->numRows($q_cart);
	$total_bayar 		= $db->sum("TOTAL_PRICE",$tpref."customers_carts"," WHERE ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0' AND ID_CLIENT = '".$id_merchant."'");	
	
	if($num_cart > 0){
	$result['content']  .= '
	<div id="form_cart">';
	
	$result['content']  .= '
			<div class="tbl_purchase" style="margin-bottom:3px;">
				<div>';
	
						while($dt_cart = $db->fetchNextObject($q_cart)){
							$expiration_date= "";
							$hrg_product 	= "";
							$image_list	 	= "";
							@$id_cart		= $dt_cart->ID_CART;
							@$nm_merchant	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_merchant."'");
							// JIKA ISI CART PEMBELIAN PRODUCT
							if(!empty($dt_cart->ID_PRODUCT)){
								@$id_product 	 = $dt_cart->ID_PRODUCT;
								@$id_merchant    = $dt_cart->ID_CLIENT;
								@$name_product	 = $dt_cart->NAME;
								@$unit_product   = $db->fob("NAME",$tpref."products_units"," 
															 WHERE ID_PRODUCT_UNIT = '".$dt_cart->ID_PRODUCT_UNIT."'");
								@$real_price 	 = $dt_cart->SALE_PRICE;
								@$add_prod 		 = $dt_cart->ADDITIONAL_PRODUCT;
								$photo_product 	 = $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_cart->ID_PRODUCT."'");
								$image_list   	="";
										if(is_file($basepath."/files/images/products/".$id_merchant."/".$photo_product)){
	$image_list  .= "<img src='".$dirhost."/files/images/products/".$id_merchant."/".$photo_product."' class='thumbnail' style='width:100%'>";										
										}else{
	$image_list  .= "<img src='".$dirhost."/files/images/no_image.jpg' class='thumbnail' style='width:100%'>";										
										}

								$jml_prod	  = 1;
							//INFORMASI PRODUCT		
	$result['content']  .= '
							  <div id="div_'.$id_cart.'" class="fadein_cart">
								<div class="w-box-header" style="background:'.@$color_1.'; 
																 font-weight:100; margin-bottom:10px;
																 line-height:15px;">
									'.ucwords(strtolower($name_product)).'
								</div>
								<div class="col-xs-4 col-sm-4 col-md-4">
									'.$image_list.'
								</div>
								<div class="col-xs-8 col-sm-8 col-md-8">
									<div class="form-group no-m-btm">
										<label>Harga</label>
										<div class="value">
											'.money("Rp.",$price).' / '.$unit_product.'
											<input type="hidden" id="ori_price_'.$id_cart.'" 
													value="'.$price.'">
										</div>
									</div>
									<div class="form-group no-m-btm">
										<label>Jumlah</label>
										<div class="input-group value col-xs-7 col-sm-7 col-md-7">
											<div class = "input-group-btn">
												<button type="button" class="btn btn-sm" onclick="set_amount(\'min\',\''.$id_cart.'\')">-</button>	
											</div>
											<span id="current_deal">
												<input type="text" 
													   value="'.@$dt_cart->AMOUNT.'"  
													   id="jmlh_'.$id_cart.'" 
													   onKeyUp="save_jml(this,\''.$id_cart.'\')" style="text-align:center" class="input-sm form-control"/>
											</span>
											<div class="input-group-btn">
												<button type="button" 
														class="btn btn-sm" 
														onclick="set_amount(\'max\',\''.$id_cart.'\')">+</button>	
											</div>
										</div>
									</div>
									
									<div class="form-group no-m-btm">
										<label>Total Bayar</label>
										<div class="value">
											<span id="new_pay_'.$id_cart.'">
												'.money("Rp.",@$dt_cart->TOTAL_PRICE).'
											</span>
										</div>
									</div>
									<span id="v_loader_'.$id_cart.'"></span>
									<a href="javascript:void()" onclick="cancel_this(\''.$id_cart.'\')" >
										<button type="button" class="btn btn-danger btn-block">
											<i class="fa fa-trash"></i> Batal
										</button>
									</a> 
								</div>
							  </div>';
							}
						}
						
	$result['content']  .= '
				</div>
			</div>';
			

	$result['content']  .= '
		</div>';
		
	}else{
	$result['content']  .= '
	<div class="alert alert-error" style="margin:10px;">Tidak ada Pembelanjaan yang terjadi saat ini</div>';
	}

?>