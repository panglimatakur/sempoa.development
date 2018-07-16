<?php 
session_start();
//if(!empty($_SESSION['sidkey']) && !empty($_GET['sempoakey']) && $_GET['sempoakey'] == "99765") {

	define('mainload','SEMPOA',true); 
	include("../../../includes/config.php");
	include("../../../includes/classes.php");
	include("../../../includes/functions.php");
	include("../../../includes/declarations.php");
	
	$direction 			= isset($_REQUEST['direction']) 		? $_REQUEST['direction'] 	: "";
	$id_merchant_target = isset($_REQUEST['id_merchant_target'])? $_REQUEST['id_merchant_target'] 	: "";
	$id_merchant 		= isset($_REQUEST['id_merchant']) 		? $_REQUEST['id_merchant'] 	: "";
	$id_product 		= isset($_REQUEST['id_product']) 		? $_REQUEST['id_product'] 	: "";
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 		? $_REQUEST['mycallback'] 	: "";

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
		@$addons 		= ch_addon($id_merchant_target);
		@$discount_info	= ch_diskon($id_merchant_target,$id_merchant);
		
/* ====================================== PRODUCT LIST ============================================== */	
		$query_str	= " SELECT 
						a.*,b.PHOTOS 
					FROM 
						".$tpref."products a,".$tpref."products_photos b 
					WHERE 
						a.ID_CLIENT = '".$id_merchant_target."' AND 
						a.ID_PRODUCT = '".$id_product."' AND
						a.ID_PRODUCT = b.ID_PRODUCT AND 
						a.ID_STATUS != '1' AND
						(a.ADDITIONAL_PRODUCT IS NULL || a.ADDITIONAL_PRODUCT = '0' || a.ADDITIONAL_PRODUCT = '')
					ORDER BY a.ID_PRODUCT DESC";
					//echo  $query_str;
		$num_produk	= $db->recount($query_str);
		
		if($num_produk > 0){
		$result["content"] .= '
		<style type="text/css">
			.detail_info{ font-size:12px; }
		</style>
		<div class="w-box-content" id="gallery_product">';
		
			$q_produk 	= $db->query($query_str."  LIMIT 0,10");
			$dt_produk = $db->fetchNextObject($q_produk);
			$harga_diskon	= "";
			$rank			= "0";
			$ch_deal		= "";
			@$photo 		= $dt_produk->PHOTOS;
			@$harga			= $dt_produk->SALE_PRICE;
			@$unit 			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT = '".$dt_produk->ID_PRODUCT_UNIT."'");
			$harga_info 	= disc_price($harga,$discount_info['discount']);
		$result["content"] .= '
					
					<div class="col-xs-12 col-sm-12 col-md-12" style="padding:0">
						<div class="gallery_pic thumbnail">
							<div class="gallery_pic_inner">';
							if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_merchant_target."/thumbnails/".$photo)){ 
				$result["content"] .= '
							<a href="javascript:void()" onclick="open_detail_product\''.$dt_produk->ID_PRODUCT.'\')">
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
					<div class="detail_info col-xs-12 col-sm-12 col-md-12" style="padding:0">
						<h3 style="margin:0;padding:0"><b>'.$dt_produk->NAME.'</b></h3><br>
						<span style="text-align:justify">'.@$dt_produk->DESCRIPTION.'</span><br><br>'; 
						if(!empty($harga)){		
							if(!empty($discount_info['discount'])){
								@$ch_list_product = substr_count($discount_info['list_product'],";".$dt_produk->ID_PRODUCT.",");
								if(($discount_info['pattern'] == 1 && empty($discount_info['list_product'])) 
									|| $ch_list_product > 0
									|| $discount_info['pre_order_status'] == 1){
			$result["content"] .= '
									<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0"><b>Diskon</b></div>
									<div class="col-xs-7 col-sm-7 col-md-7">
										<span style="color:#09F">'.@$discount_info['discount'].'%</span>
									</div>
									<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0"><b>Harga</b></div>
									<div class="col-xs-7 col-sm-7 col-md-7">
										<i class="code" style="text-decoration:line-through">
											'.money("Rp.",@$harga).' / '.$unit.'
										</i>
									</div>
									<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0">
										<b>Harga Diskon</b>
									</div>
									<div class="col-xs-7 col-sm-7 col-md-7">
										'.money("Rp.",@$harga_info['harga_diskon'])." / ".$unit.'
									</div>
';
								}else{
									$result["content"] .= '
									<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0"><b>Harga</b></div>
									<div class="col-xs-7 col-sm-7 col-md-7">
										'.money("Rp.",@$harga).' / '.$unit.'
									</div>';
								}
							}else{
								$result["content"] .= '
								<div class="col-xs-4 col-sm-4 col-md-4" style="padding-left:0"><b>Harga</b></div>
								<div class="col-xs-7 col-sm-7 col-md-7">
									'.money("Rp.",@$harga).' / '.$unit.'
								</div>';
							}
						}
						  
		$result["content"] .= '
						</form>
					</div>
					<div class="clearfix"></div>

		</div>';
		} 
			 
		echo $callback.'('.json_encode($result).')';
/* ================================== END OF PRODUCT LIST =========================================== */	
	}
	
//}
?>