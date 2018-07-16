<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../../includes/config.php");
	include_once("../../../../../includes/classes.php");
	include_once("../../../../../includes/functions.php");
	include_once("../../../../../includes/declarations.php");
	$id_coin 	= isset($_REQUEST['id_coin']) 	? $_REQUEST['id_coin'] 	: "";	
	@$lastID 	= isset($_REQUEST['lastID']) 	? $_REQUEST['lastID'] 	: "";
	$display	= isset($_REQUEST['display']) 	? $_REQUEST['display'] 	: "";
		
	$next_list	= "";
	if(!empty($display)){
		$next_list = " AND a.ID_PRODUCT < ".$lastID." "; 
	}
	$query_str	= " SELECT 
						a.*,b.PHOTOS 
					FROM 
						".$tpref."products a,".$tpref."products_photos b 
					WHERE 
						a.ID_CLIENT = '".$id_coin."' AND 
						a.ID_PRODUCT = b.ID_PRODUCT AND
						a.ID_STATUS != '1'
						".$next_list."
					ORDER BY a.ID_PRODUCT DESC";
					//echo $query_str;
	$num_produk	= $db->recount($query_str);
	$discount 	= $db->fob("VALUE",$tpref."client_discounts","WHERE ID_CLIENT = '".$id_coin."' AND COMMUNITY_FLAG != '0' AND REQUEST_BY_ID_CUSTOMER = ''");
	if(!empty($display) && $display == "list_product_pop"){ $class="col-sm-3"; }else{ $class="col-sm-4"; }
		if($num_produk > 0){
			$q_produk 	= $db->query($query_str."  LIMIT 0,10");
			while($dt_produk = $db->fetchNextObject($q_produk)){
				$harga_diskon	= "";
				@$photo 		= $dt_produk->PHOTOS;
				@$harga			= $dt_produk->SALE_PRICE;
		?>
            <div class="<?php echo $class; ?> wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>'>
                <div class="product-image-wrapper">
                    <div class="single-products">
                            <div class="productinfo text-center">
                            	<div class="thumbnail">
                                <a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo permalink($dt_produk->NAME,""); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>">
                                <?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_coin."/thumbnails/".$photo)){ ?>
                               
                                    <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>" class="potrait"/>
                                <?php }else{ ?>
                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>" class="potrait"/>
                                <?php } ?>
                                </a>    
                                </div>                
                                <h2 style="height:23px;"><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                                <p><?php echo ucwords($dt_produk->NAME); ?></p>
                            </div>
                            <?php if(!empty($dt_produk->SALE_PRICE)){?>
                            <!--<div class="product-overlay">
                                <div class="overlay-content">
                                    <h2><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                                    <p><?php echo ucwords($dt_produk->NAME); ?></p>
                                    <a href="javascript:void()" onclick="ajax_fancybox('<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>')" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Simpan Item</a>
                                </div>
                            </div>-->
                            <?php } ?>
                    </div>
                    <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                            <?php if(!empty($dt_produk->SALE_PRICE)){?>
                            <!--<li><a href="javascript:void()" onclick="ajax_fancybox('<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>')"><i class="fa fa-shopping-cart"></i>Simpan Item</a></li>-->
                            <?php } ?>
                            <li><a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo permalink($dt_produk->NAME,""); ?>"><i class="fa fa-search"></i>Lihat Item</a></li>
                        </ul>
                    </div>
                </div>
            </div>
		<?php
			}
		}
		?>
		<?php if(empty($lastID)){?><div id="lastPostsLoader"></div> <?php } ?>
                
<?php
}
?>