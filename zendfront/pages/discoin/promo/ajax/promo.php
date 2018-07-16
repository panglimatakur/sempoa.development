<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../../includes/config.php");
include_once("../../../../../includes/classes.php");
include_once("../../../../../includes/functions.php");
include_once("../../../../../includes/declarations.php");
$id_coin	= isset($_REQUEST['id_coin']) ? $_REQUEST['id_coin'] 	: "";

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$q_merchant  		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$id_coin."'");
	$dt_merchant 		= $db->fetchNextObject($q_merchant);
	@$nm_merchant		= $dt_merchant->CLIENT_NAME;
	$str_community				= " SELECT 
										a.BY_ID_PURPLE, 
										a.NAME,
										b.ID_COMMUNITY 
									FROM 
										".$tpref."communities a, 
										".$tpref."communities_merchants b 
									WHERE 
										b.ID_CLIENT = '".$id_coin."' AND
										a.ID_COMMUNITY = b.ID_COMMUNITY";
	//echo $str_community;
	$q_community 				= $db->query($str_community);
	$num_community				= $db->numRows($q_community);
	$join 						= "";
	$nm_community				= ""; 	
	if($num_community > 0){
		while($dt_join = $db->fetchNextObject($q_community)){
			$join 			.= $dt_join->ID_COMMUNITY;	
			$nm_community	.= $dt_join->NAME.",";	
		}
	}
	$nm_community	= substr($nm_community,0,-1);
?>
<div class="features_items" style="padding:10px;" ><!--features_items-->
    <h2 class="title text-center">Promo Discoin Diskon <?php echo $nm_merchant; ?></h2>
    
    
        <div style="color:#F00; text-align:center"><b>NOTE :</b><br />Berlaku Bagi Member Dari Komunitas <?php echo @$nm_community; ?> / COIN Tervalidasi</div><br>
        <?php
		$o = 0;
        $str_discount 		= "SELECT * FROM ".$tpref."client_discounts WHERE ID_CLIENT='".$id_coin."' AND COMMUNITY_FLAG = '1' AND DISCOUNT_STATUS='3'";
        $client_statement	= $db->fob("CLIENT_STATEMENT",$tpref."clients"," WHERE ID_CLIENT='".$id_coin."'"); 
        //echo $str_discount;
        $q_discount_2 		= $db->query($str_discount);
        $num_discount		= $db->numRows($q_discount_2);
        if($num_discount > 0 || !empty($client_statement)){
            if($num_discount > 0){
                $persen	= "";
                $rupiah	= "";
                while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
					$o++;
                    if($dt_discount_2->PIECE == "persen"){ $persen = "%"; 	}
					$discount 	= $dt_discount_2->VALUE;
                    $img_show 	= "";
                    $photo		= "";
                    $exp_date	= "";
                    $exp_date	= $dt_discount_2->EXPIRATION_DATE;
                    if(!empty($exp_date) && $exp_date != "0000-00-00"){
                        $exp_date = "<small class='code'>Berlaku s/d : ".$dtime->date2indodate($exp_date)."</small>";	
                    }else{
                        $exp_date = "";
                    }
                    
                    if($dt_discount_2->ID_DISCOUNT_PATTERN == 1){
                        if(empty($dt_discount_2->ID_PRODUCTS)) { $for_sum = " Untuk Semua Produk";	}
                        if(!empty($dt_discount_2->ID_PRODUCTS)){ $for_sum = " Untuk Beberapa Produk";	}
                    }else{
                        $for_sum = " Untuk Minimal Pembelian ".money("Rp.",$dt_discount_2->BUY_SUMMARY); 
                    }
                echo  "
				<h2  class='title text-left' style='margin:4px 0 3px 0;'>Diskon ".$o."</h2>
				<div style='clear:both; margin:0 0 3px 17px; padding:8px; text-align:justify; line-height:normal'>
                            Diskon ".$rupiah."".$dt_discount_2->VALUE."".$persen." ".@$for_sum."
                            <br>";
				if(!empty($dt_discount_2->STATEMENT)){
				echo "			
                            <b>Keterangan : </b><br>
                            ".$dt_discount_2->STATEMENT."
                            <br>";
				}
				if($exp_date != "0000-00-00"){ echo @$exp_date;  }
				echo "
                            <div style='clear:both; heigth:4px'></div>";
							
							$id_product_discs 	= $dt_discount_2->ID_PRODUCTS;
							if(!empty($id_product_discs)){ 
								$f				  = 0;
								$num_product_discs= substr_count($id_product_discs,";");
								$id_product_disc  = explode(";",str_replace(",","",$id_product_discs)); 
								while($f < $num_product_discs){
									$f++;
									@$q_product		= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT = '".$id_product_disc[$f]."'");
									$dt_produk		= $db->fetchNextObject($q_product);
									@$harga 		= $dt_produk->SALE_PRICE;
									@$code			= $dt_produk->CODE;
									@$photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product_disc[$f]."'");
									?>
									<div class="col-sm-2">
										<div class="product-image-wrapper">
											<div class="single-products">
												<div class="productinfo text-center">
													 <label><b><small class='code'><?php echo $code; ?></small></b></label>
													<?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_coin."/thumbnails/".$photo)){ ?>
														<img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' class="thumbnail" />
													<?php }else{ ?>
														<img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="thumbnail"/>
													<?php } ?>
													
													<?php if(!empty($discount)){ ?>
													<span style="color:#09F"><b>Diskon : </b><?php echo @$discount; ?>%</span>
													<br />
													<?php } ?>
													<?php 
													if(!empty($discount) && !empty($dt_produk->SALE_PRICE)){
														$diskon 		= ($harga/100)*$discount;
														$harga_diskon  = $harga-$diskon;
													}
													if(!empty($dt_produk->SALE_PRICE)){
														if(empty($harga_diskon)){
															echo money("Rp.",@$harga);
														}else{?>
															<i class="code" style="text-decoration:line-through">
																<?php echo money("Rp.",@$harga); ?>
															</i>
															<br />
															<?php echo money("Rp.",@$harga_diskon);
														}
						 
													}
													?>
					
												</div>
										   </div>
									   </div>
								   </div>
							<?php }
							}
							
				echo "		</div>";
                }
            }else{
                echo "<div style='clear:both; margin:0 0 3px 17px; padding:8px; text-align:justify; line-height:normal'>".$client_statement."</div>";
            }
         } ?>
</div>
<br /><?php } ?>