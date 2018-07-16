<?php 
session_start();
if(!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765") {

	define('mainload','SEMPOA',true); 
	include("../../includes/config.php");
	include("../../includes/classes.php");
	include("../../includes/functions.php");
	include("../../includes/declarations.php");
	
	$direction 			= isset($_REQUEST['direction']) 		? $_REQUEST['direction'] 	: "";
	$id_merchant_target = isset($_REQUEST['id_merchant_target'])? $_REQUEST['id_merchant_target'] 	: "";
	$id_merchant 		= isset($_REQUEST['id_merchant']) 		? $_REQUEST['id_merchant'] 	: "";
	$id_customer		= isset($_REQUEST['id_customer']) 		? $_REQUEST['id_customer'] 	: "";
	$nm_customer		= isset($_REQUEST['nm_customer']) 		? $_REQUEST['nm_customer'] 	: "";
	$lastID 			= isset($_REQUEST['lastID']) 			? $_REQUEST['lastID'] 		: "";
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 		? $_REQUEST['mycallback'] 	: "";
	
	$result['msg_log'] 	= "";
	$result['io_log']  	= "";
	
	if(empty($_SESSION['sidkey'])){
		$data 			 = relogin($id_merchant,$id_customer);
		$result['io_log'] 	= $data['io_log'];
		$result['msg_log']  = $data['msg_log'];
	}
	
	if(!empty($id_customer)){
		
		function per_item_list($id_merchant,$id_product_discs){
			global $db;
			global $tpref;
			global $basepath;
			global $dirhost;
			$f				  	= 0;
			$num_product_discs	= substr_count($id_product_discs,";");
			$id_product_disc  	= explode(";",str_replace(",","",$id_product_discs)); 
			$content			= "";
			while($f < $num_product_discs){
				$f++;
				@$code	= $db->fob("CODE",$tpref."products"," WHERE ID_PRODUCT = '".$id_product_disc[$f]."'");
				@$photo	= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product_disc[$f]."'");
				
				$content .= '    
				<div class="pframe" id="pframe_'.$f.'" style="margin:3px 0 0 4px">
					<label><b><small class="code">'.$code.'</small></b></label>';
					if(!empty($photo) && is_file($basepath."/files/images/products/".$id_merchant."/thumbnails/".$photo)){
	$result['content'] .= '<img src="'.$dirhost.'/files/images/products/'.$id_merchant.'/thumbnails/'.$photo.'" class="photo" style="height:60px"/>';
					}else{
				$content .= '
					<img src="'.$dirhost.'/files/images/no_image.jpg" class="photo" style="height:60px"/>';
					}
				$content .= '
				</div>';
			} 
			$content .= '
			<br clear="all" />';
			
			return $content;
		}
		function disc_price($real_price,$discount){
			$data['diskon_num']    = ($real_price/100)*$discount;
			$data['harga_diskon']  = pembulatan($real_price-$data['diskon_num']);
			return $data;
		}
		
		if(!empty($direction) && $direction == "load"){
			$result['content']		= "";
			$q_info_merchant 		= $db->query("SELECT * FROM ".$tpref."clients 
												  WHERE ID_CLIENT='".$id_merchant_target."'");
			@$dt_info_merchant		= $db->fetchNextObject($q_info_merchant);
			@$nm_merchant 			= $dt_info_merchant->CLIENT_NAME;
			@$deal_flag 			= $dt_info_merchant->DEALING_FLAG;
			@$logo 					= $dt_info_merchant->CLIENT_LOGO;
			@$phone_merchant 		= $dt_info_merchant->CLIENT_PHONE;
			@$alamat_merchant 		= $dt_info_merchant->CLIENT_ADDRESS;
			@$website_merchant 		= $dt_info_merchant->CLIENT_URL;
			@$desc_merchant			= $dt_info_merchant->CLIENT_DESCRIPTIONS;
			$client_statement		= $dt_info_merchant->CLIENT_STATEMENT; 
			if(!empty($dt_info_merchant->COLOUR)){
				@$colour			= explode(";",$dt_info_merchant->COLOUR);
				@$colour_1			= "style='background:".$colour[0]."'";
				@$result['colour_1']= $colour[0];
			}
			if(is_file($basepath."/files/images/logos/".$logo)) { @$logo_path = "logos/".$logo;	}
			else												{ @$logo_path = "no_image.jpg";	} 
			@$addons 		= ch_addon($id_merchant);					
			$nm_customer	= $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER = '".$id_customer."'");
			
			
	/* =========================================== INTRO ============================================== */	
			$result['content'] = '
			<link href="'.@$dirhost.'/discoin_api/merchant/css/style.css" rel="stylesheet" type="text/css" />
			<div class="w-box-content">
					<div class="col-xs-2 col-md-2 merchant_logo img-circle" style="padding:0">
						<img src="'.@$dirhost.'/files/images/'.@$logo_path.'"/>
					</div>
					<div  class="col-xs-10 col-md-10 desc-text">
						Hi <b>'.@$nm_customer.str_repeat(substr(@$nm_customer,-1),3).'....<img src="'.@$dirhost.'/files/images/icons/images.png" width="16" style="margin-top:-8px"/></b>, <br />
						Selamat datang di Discoinnya <b>'.@$nm_merchant.'</b><br />';
						if(!empty($desc_merchant)){
			$result['content'] .= '
							<div>
								<b>'.$nm_merchant.'</b> : '.cutext($desc_merchant,200).'
							</div>';
						}
			$result['content']  .= '
					</div>
					<div class="clearfix"></div>
			</div>';
	/* ======================================= END OF INTRO ================================================ */	
			
			
			
	/* ======================================= PROMO LIST ================================================ */	
			if($id_merchant == $id_merchant_target){
				$ch_cust_disc = $db->recount("SELECT ID_DISCOUNT FROM ".$tpref."clients_discounts 
											  WHERE ID_CLIENT = '".$id_merchant_target."'");
				if($ch_cust_disc > 0){
					$flag = " AND DISCOUNT_SEGMENT 	= 'customer' ";	
				}else{
					$flag = " AND DISCOUNT_SEGMENT 	= 'community' ";	
				}
			}else{
				$flag = " AND DISCOUNT_SEGMENT 	= 'community' ";	
			}
				
			
			$str_disc 				= "SELECT * FROM ".$tpref."clients_discounts 
									   WHERE ID_CLIENT = '".$id_merchant_target."' AND DISCOUNT_STATUS='3'";
			$q_disc 				= $db->query($str_disc);
			$num_discount			= $db->numRows($q_disc);
			
			//$result['content'] 		.= $str_disc;
			if($num_discount > 0){
			$result['content'] .= '  
				<div class="w-box-header" '.@$colour_1.'>
						<i class="icsw16-tag icsw16-white" ></i> <b>Promo Diskon</b>
				</div>
				<div class="w-box-content" style="background:transparent">
					<ul class="list-group" style="margin-bottom:0">';
				@$current_cart	= $db->sum("AMOUNT",$tpref."customers_carts"," 
											WHERE ID_CLIENT = '".$id_merchant_target."' AND STATUS = '0'");
				if(empty($current_cart)){ $current_cart = 0; }
				$b = 0;
				while($dt_discount 	= $db->fetchNextObject($q_disc)){$b++;
					$masa_akhir		= "";
					@$id_discount	= $dt_discount->ID_DISCOUNT;
					@$id_pattern	= $dt_discount->ID_DISCOUNT_PATTERN;
					@$pattern 		= $db->fob("DESCRIPTION",$tpref."discount_patterns"," 
												WHERE ID_DISCOUNT_PATTERN = '".$id_pattern."'");
					@$discount		= $dt_discount->DISCOUNT;
					@$statement		= $dt_discount->DISCOUNT_STATEMENT;
					@$expiration	= $dt_discount->EXPIRATION_DATE;
					if(!empty($expiration) && $expiration != "0000-00-00"){
						$masa_akhir	= "Berlaku s/d ".$dtime->date2indodate($expiration);
					}
					@$besar			= $dt_discount->DISCOUNT_VALUE; 
					@$piece			= $dt_discount->DISCOUNT_UNIT;
					$sifat_jual		= $dt_discount->SELLING_METHOD;
					@$jml_kupon		= $dt_discount->SELLING_METHOD_PO_COUPON_QTY;
					@$pr_kupon		= $dt_discount->SELLING_METHOD_PO_COUPON_PRICE;
					
					if(!empty($statement)){
						$statement = "<b>Keterangan</b><br>".$statement;	
					}
					switch($id_pattern){
						case "1":
						if(substr_count($besar,";") > 0){ 
							$besar_label = "<label class='label label-default '>
										<a  href='javascript:void()'>
											Lihat Daftar Item
										</a>
									  </label>"; 
						}
						if($besar == "all"){ $besar_label = "<label class='label label-warning'>Semua Item</label>"; }
						break;
						case "2":
							$besar_label = "Minimal ".money("Rp.",$besar);
						break;
					}
					switch($sifat_jual){
						case "readystock": 
						$sifat_jual_label = 
							"<label class='label label-info'>Ready Stock</label><br>"; 
						break;
						case "preorder": 
						$sifat_jual_label = 
							"<a href='javascript:void' title='".$jml_kupon." Kupon / @".money("Rp.",$pr_kupon)."'>
								<label class='label label-danger'>Pre Order</label>
							</a><br>"; 
						break;
					}
			$result['content'] .= '<li class="list-group-item" onclick="view_discount(\''.$id_discount.'\')">
										'.$sifat_jual_label.'
										'.$dt_discount->DISCOUNT.'% '.$pattern.'<br>
										'.$besar_label.'<br>
										'.@$statement.'<br>
										'.@$masa_akhir.'
									</li>';
				}
			$result['content'] .= 
				'	<ul>
				</div>';
			 }
	/* ================================== END OF PROMO LIST ============================================== */	
	
	
	
	/* ====================================== PRODUCT LIST ============================================== */	
			$query_str	= " SELECT 
							a.*,b.PHOTOS 
						FROM 
							".$tpref."products a,
							".$tpref."products_photos b 
						WHERE 
							a.ID_CLIENT = '".$id_merchant_target."' AND 
							a.ID_PRODUCT = b.ID_PRODUCT AND 
							a.ID_STATUS != '1' AND
							(a.ADDITIONAL_PRODUCT IS NULL || a.ADDITIONAL_PRODUCT = '0' || a.ADDITIONAL_PRODUCT = '')
						ORDER BY a.ID_PRODUCT DESC";
						//echo  $query_str;
			$num_produk	= $db->recount($query_str);
			
			if($num_produk > 0){
			$result["content"] .= '
			<div class="w-box-header" '.@$colour_1.'>
				<i class="icsw16-balloons icsw16-white" style="margin:0; padding:0"></i> <b>Katalog</b>
			</div>
			<div class="w-box-content" id="gallery_product">
				<input type="hidden" id="id_product" value="">
				<ul class="gallery" style="padding:0">';
			
				$q_produk 	= $db->query($query_str."  LIMIT 0,10");
				while($dt_produk = $db->fetchNextObject($q_produk)){
					$harga_diskon	= "";
					$rank			= "0";
					$ch_deal		= "";
					@$photo 		= $dt_produk->PHOTOS;
					@$harga			= $dt_produk->SALE_PRICE;
					@$pr_discount	= $dt_produk->DISCOUNT;
					@$unit 			= $db->fob("NAME",$tpref."products_units"," 
												WHERE ID_PRODUCT_UNIT = '".$dt_produk->ID_PRODUCT_UNIT."'");				
			$result["content"] .= '
					<li class="wrdLatest" data-info="'.$dt_produk->ID_PRODUCT.'" style="position:relative;">
						
						<div class="col-xs-3 col-sm-3 col-md-3" style="padding:0">
							<div class="gallery_pic thumbnail">
								<div class="gallery_pic_inner">';
								if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_merchant_target."/thumbnails/".$photo)){ 
					$result["content"] .= '
								<a href="javascript:void()" onclick="open_detail_product(\'pages/detail/view.html\',\''.$id_merchant_target.'\',\''.$dt_produk->ID_PRODUCT.'\')">
									<img src="'.$dirhost.'/'.$img_dir.'/products/'.$id_merchant_target.'/thumbnails/'.$photo.'" />
								</a>';
								 }else{
					$result["content"] .= '
									<img src="'.$dirhost.'/files/images/no_image.jpg"/>';
								}
					$result["content"] .= '
								</div>
							</div>
						</div>
						<div class="gallery_info col-xs-8 col-sm-8 col-md-8">
							<b>'.$dt_produk->NAME.' </b><br />';
							if(!empty($harga)){	
								if(!empty($discount)  && $besar == "all"){
								$data  	= disc_price($harga,$discount);
					 $result["content"] .= '
										<span style="color:#09F"><b>Diskon : </b>'.@$discount.'%</span><br>
										<i class="code" style="text-decoration:line-through">
											'.money("Rp.",@$harga).' / '.$unit.'
										</i>
										<br />
										'.money("Rp.",@$data['harga_diskon'])." / ".$unit;
									
								}else{
								$discount = "";
					  $result["content"] .= money("Rp.",@$harga)." / ".$unit."<br>";
								}
								
			$result["content"] .= '
					<br>
					<button type="button" 
							class="btn btn-block btn-primary" 
							id="sorder_'.$dt_produk->ID_PRODUCT.'" 
							onclick = "open_cart_product(\''.$id_merchant_target.'\',\''.$dt_produk->ID_PRODUCT.'\',\''.@$besar.'\',\''.@$harga.'\',\''.@$discount.'\')" 
							style="font-size:12px; line-height: 0">
						<i class="icsw16-white icsw16-price-tags"></i> Ke Keranjang Belanja
					</button>';
							}
									
			$result["content"] .= '
						</div>
						<div class="clearfix"></div>
					</li>
					<div class="clearfix"></div>';
				}
			$result["content"] .= '
				</ul>
				<div class="end_gallery_product"></div>
				<div id="lastPostsLoader"></div>
				
			</div>';
			} 
				 
			$result["content"] .= '
				<div class="merchant_pagination" id="footer_product">';
					if($num_produk > 10){?>
				<?php 
			$result["content"] .= '
						<a href="javascript:void()" onclick="next_product(\''.@$id_merchant_target.'\')" class="next_button">
							<i class="fa fa-chevron-down"></i> SELANJUTNYA
						</a>';
					 }
			$result["content"] .= '
					<div class="clearfix"></div>
				</div>';
			
	/* ================================== END OF PRODUCT LIST =========================================== */	
	
	
	
	/* ====================================== CUSTOMER LIST ============================================== */	
		//if(!empty($addons['st_customer'])){
	$str_member		= " SELECT ID_CUSTOMER,CUSTOMER_NAME,CUSTOMER_PHOTO,TGLUPDATE
						FROM ".$tpref."customers 
						WHERE 
							ID_CLIENT = '".$id_merchant_target."' AND 
							CUSTOMER_STATUS = '3'
						ORDER BY ID_CUSTOMER DESC";
	$num_member		= $db->recount($str_member);
	$result['content']  .= '
			<input type="hidden" id="member_data" value="">
			<div class="w-box-header" '.@$colour_1.'>
				<i class="icsw16-white icsw16-users-2"></i><b> Member '.$nm_merchant.' ( '.$num_member.' Member )</b>
			</div>
			
			<div class="w-box-content" id="gallery_member">
				<div class="gallery">';
				if($num_member > 0){
					$q_member 	= $db->query($str_member."  LIMIT 0,10");
					while($dt_member = $db->fetchNextObject($q_member)){
						@$photo 		= $dt_member->CUSTOMER_PHOTO;
	$result['content']  .= '
					<div data-info="'.$dt_member->ID_CUSTOMER.'" class="memberLatest col-xs-3 col-sm-3 col-md-3"  style="padding:3px">
						<div class="gallery_pic thumbnail">
							<div class="gallery_pic_inner">';
							if(!empty($photo) && is_file($basepath."/".$img_dir."/members/".$photo)){
		$result['content']  .= '
							<a href="javascript:void()" onclick="open_member(\'pages/detail/view.html\',\''.$dt_member->ID_CUSTOMER.'\')">
								<img src="'.$dirhost.'/'.$img_dir.'/members/'.$photo.'" />
							</a>';
							
							}else{
		$result['content']  .= '
							<a href="javascript:void()" onclick="open_member(\'pages/detail/view.html\',\''.$dt_member->ID_CUSTOMER.'\')">
								<img src="'.$dirhost.'/files/images/noimage-m.jpg">
							</a>';
							}
		$result['content']  .= '
							</div>
							<div class="clearfix"></div>
						</div>
					</div>';
					}
				}
	$result['content']  .= '
				</div>
				<div class="clearfix"></div>
				<div class="end_gallery_member"></div>
				<div id="lastMemberLoader"></div>
				<div class="clearfix"></div>
			</div>
			
			<div class="merchant_pagination" id="footer_member">';
				if($num_member > 10){
	$result['content']  .= '
					<a href="javascript:void()" onclick="next_member(\''.$id_merchant_target.'\')" class="next_button">
						<i class="fa fa-chevron-down"></i> SELANJUTNYA
					</a>';
				}
	$result['content']  .= '
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>';
		//}
	/* ================================== END OF CUSTOMER LIST =========================================== */
	
	
	
	/* ===========================--======= BRAND PROFILE ========-----=================================== */
		$result['content']  .= '
		<div class="w-box-header" '.@$colour_1.'>
			<i class="icsw16-home icsw16-white" style="padding:0; margin:0"></i> Profil '.@$nm_merchant.'
		</div>
		<div class="w-box-content">
					
			<div class="col-xs-4 col-sm-4 col-md-4">'.
				getclientlogo($id_merchant_target,"class=\"thumbnail\" style=\"width:100%\"")
			.'</div>
			<div class="col-xs-8 col-sm-8 col-md-8">
				<h3 style="margin-top:0">'.@$nm_merchant.'</h3>';
				if(!empty($phone_merchant)){
$result['content']  .= '
					<b>No Tlp:</b> <br />
					'.@$phone_merchant.'<br>
				';
				}
				if(!empty($alamat_merchant)){
$result['content']  .= '
					<b>Alamat:</b><br />
					'.@$alamat_merchant.'&nbsp;<br>
				';
				}
		$result['content'] .= '
			</div>
			<div class="clearfix"></div>
			';
				if(!empty($website_merchant)){
$result['content']  .= '
					<p class="formSep" style="padding:0 20px">
						<small class="muted">Wesbite :</small>
						<div style="padding:0 20px">
							<a href="'.@$website_merchant.'">
								'.@$website_merchant.'
							</a>
						</div>
					</p>';
				}
				if(!empty($desc_merchant)){
$result['content']  .= '
				<p class="formSep" style="padding:0 20px">
					<small class="muted">Deskripsi:</small>
					<div style="padding:0 20px">
						<small>'.@$desc_merchant.'&nbsp;</small>
					</div>
				</p>';
				}
				
	$result['content']  .= '
		</div>
		';
		@$result['marker_icon']	= "";
		if(is_file($basepath."/files/images/logos/".$logo)){
			@$result['marker_icon']	= $dirhost."/files/images/logos/".$logo;
		}
		$q_client_map		=	$db->query("SELECT * FROM ".$tpref."clients_maps 
											WHERE 
												ID_CLIENT='".$id_merchant_target."' ORDER BY ID_CLIENT_MAP ASC");
		$coordinate	= array();
		while($dt_client_map =	$db->fetchNextObject($q_client_map)){
			$coord 			= explode(",",$dt_client_map->COORDINATES);
			$coordinate[] 	= array($coord[0],$coord[1],$dt_client_map->COORDINATE_DESCRIPTIONS);
		}
		if(count($coordinate) > 0){
			$result['content'] .= '
			<div id="map_canvas" style="margin-top:8px;width:100%; height:400px;">
				dfh
			</div>';
		}
		@$result['coordinate']	= $coordinate;	
		
	/* ================================ END OF BRAND PROFILE ============================================ */
		echo $callback.'('.json_encode($result).')'; 
		}
	
	
	
	/* ===========================--======= NEXT PAGE ========-----=================================== */
		if(!empty($direction) && $direction == "next_product"){
			$ch_cust_disc = $db->recount("SELECT ID_DISCOUNT FROM ".$tpref."clients_discounts 
										  WHERE ID_CLIENT = '".$id_merchant_target."'");
			if($ch_cust_disc > 0){
				$flag = " AND DISCOUNT_SEGMENT 	= 'customer' ";	
			}else{
				$flag = " AND DISCOUNT_SEGMENT 	= 'community' ";	
			}
				
			
			$str_disc 				= "SELECT * FROM ".$tpref."clients_discounts 
									   WHERE ID_CLIENT = '".$id_merchant_target."' AND DISCOUNT_STATUS='3'";
			$q_disc 				= $db->query($str_disc);
			$num_discount			= $db->numRows($q_disc);
			if($num_discount > 0){
				$dt_discount 	= $db->fetchNextObject($q_disc);
				$masa_akhir		= "";
				@$id_discount	= $dt_discount->ID_DISCOUNT;
				@$id_pattern	= $dt_discount->ID_DISCOUNT_PATTERN;
				@$pattern 		= $db->fob("DESCRIPTION",$tpref."discount_patterns"," 
											WHERE ID_DISCOUNT_PATTERN = '".$id_pattern."'");
				@$discount		= $dt_discount->DISCOUNT;
				@$besar			= $dt_discount->DISCOUNT_VALUE; 
			
			}
			
			
			@$addons 	 	= ch_addon($id_merchant_target);
			$query_str	 		= " SELECT 
										a.*,b.PHOTOS 
									FROM 
										".$tpref."products a,".$tpref."products_photos b 
									WHERE 
										a.ID_CLIENT = '".$id_merchant_target."' AND 
										a.ID_PRODUCT = b.ID_PRODUCT  AND 
										a.ID_STATUS != '1' AND
										a.ID_PRODUCT < ".$lastID."
									ORDER BY a.ID_PRODUCT DESC";
			$q_produk 	 		= $db->query($query_str."  LIMIT 0,10");
			$num_product 		= $db->numRows($q_produk);
			$result['jumlah'] = $num_product;		
			$result['content'] = "";
			while($dt_produk = $db->fetchNextObject($q_produk)){
				$harga_diskon	= "";
				$rank			= "0";
				$ch_deal		= "";
				@$photo 		= $dt_produk->PHOTOS;
				@$harga			= $dt_produk->SALE_PRICE;
				@$pr_discount	= $dt_produk->DISCOUNT;
				@$unit 			= $db->fob("NAME",$tpref."products_units"," 
											WHERE ID_PRODUCT_UNIT = '".$dt_produk->ID_PRODUCT_UNIT."'");				
		$result["content"] .= '
				<li class="wrdLatest" data-info="'.$dt_produk->ID_PRODUCT.'" style="position:relative;">
					
					<div class="col-xs-3 col-sm-3 col-md-3" style="padding:0">
						<div class="gallery_pic thumbnail">
							<div class="gallery_pic_inner">';
							if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_merchant_target."/thumbnails/".$photo)){ 
				$result["content"] .= '
							<a href="javascript:void()" onclick="open_detail_product(\'pages/detail/view.html\',\''.$id_merchant_target.'\',\''.$dt_produk->ID_PRODUCT.'\')">
								<img src="'.$dirhost.'/'.$img_dir.'/products/'.$id_merchant_target.'/thumbnails/'.$photo.'" />
							</a>';
							 }else{
				$result["content"] .= '
								<img src="'.$dirhost.'/files/images/no_image.jpg"/>';
							}
				$result["content"] .= '
							</div>
						</div>
					</div>
					<div class="gallery_info col-xs-8 col-sm-8 col-md-8">
						<b>'.$dt_produk->NAME.'</b><br />';
						if(!empty($harga)){		
							if(!empty($discount)  && $besar == "all"){
							$data  = disc_price($harga,$discount);
				 $result["content"] .= '
									<span style="color:#09F"><b>Diskon : </b>'.@$pr_discount.'%</span><br>
									<i class="code" style="text-decoration:line-through">
										'.money("Rp.",@$harga).' / '.$unit.'
									</i>
									<br />
									'.money("Rp.",@$data['harga_diskon'])." / ".$unit;
								
							}else{
				  $result["content"] .= money("Rp.",@$harga)." / ".$unit."<br>";
							}
						}
								
		$result["content"] .= '
					</div>
					<div class="clearfix"></div>
				</li>
				<div class="clearfix"></div>';
			}
			
			echo $callback.'('.json_encode($result).')';
		}
	
		if(!empty($direction) && $direction == "next_member"){	
			$str_member			= " SELECT ID_CUSTOMER,CUSTOMER_NAME,CUSTOMER_PHOTO,TGLUPDATE
									FROM ".$tpref."customers 
									WHERE 
										ID_CLIENT = '".$id_merchant_target."' AND 
										CUSTOMER_STATUS = '3' AND ID_CUSTOMER < ".$lastID."
									ORDER BY ID_CUSTOMER DESC";
			
			@$num_member		= $db->recount($str_member);
			$q_member 			= $db->query($str_member."  LIMIT 0,10");
			$result['jumlah'] 	= $num_member;		
			$result['content'] 	= "";
	
			while($dt_member = $db->fetchNextObject($q_member)){
			@$photo 	 = $dt_member->CUSTOMER_PHOTO;
	$result['content']  .= '
					<div data-info="'.$dt_member->ID_CUSTOMER.'" class="memberLatest col-xs-3 col-sm-3 col-md-3"  style="padding:3px">
						<div class="gallery_pic thumbnail">
							<div class="gallery_pic_inner">';
							if(!empty($photo) && is_file($basepath."/files/images/members/".$photo)){
	$result['content']  .= '
				<a href="javascript:void()" onclick="open_member(\'pages/detail/view.html\',\''.$dt_member->ID_CUSTOMER.'\')">
								<img src="'.$dirhost.'/files/images/members/'.@$photo.'" />
				</a>';
							}else{
	$result['content']  .= '
							<a href="javascript:void()" onclick="open_member(\'pages/detail/view.html\',\''.$dt_member->ID_CUSTOMER.'\')">
								<img src="'.$dirhost.'/files/images/noimage-m.jpg">
							</a>';
							}
	$result['content']  .= '
							</div>
							<div class="clearfix"></div>
							
						</div>
					</div>';
			}
			
			echo $callback.'('.json_encode($result).')'; 
		}
	
	/* =============================-====END OF NEXT PAGE ========-----=================================== */
	
	
	
	}
}
?>