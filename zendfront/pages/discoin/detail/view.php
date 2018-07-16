<?php defined('mainload') or die('Restricted Access'); ?>
    <?php if($num_produk > 0){?>
    <div class="product-details"><!--product-details-->
        <div class="col-sm-5">
            <div class="view-product">
            	<?php if(is_file($basepath."/files/images/products/".$id_coin."/".$dt_produk->PHOTOS)){?>
				<img src='<?php echo $dirhost; ?>/files/images/products/<?php echo @$id_coin; ?>/<?php echo $dt_produk->PHOTOS; ?>' width="95%"/>
                <?php }else{ ?>
					<img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' width="95%"/>
                <?php } ?>
                <h3>ZOOM</h3>
            </div>
        </div>
				
        <div class="col-sm-7">
            <div class="product-information"><!--/product-information-->
                <img src="<?php echo $dicoin_tpl_dir; ?>/images/product-details/new.jpg" class="newarrival" alt="" />
				<?php if(!empty($dt_produk->NAME)){?>
					<h2><?php echo @$dt_produk->NAME; ?></h2>
				<?php } ?>
                <p>Kote Item: <?php echo @$dt_produk->CODE; ?></p>
                <!--<img src="<?php echo $dicoin_tpl_dir; ?>/images/product-details/rating.png" alt="" />-->
                <span>
					<?php if(!empty($dt_produk->SALE_PRICE)){?>
                        <!--<span><?php echo money("Rp.",@$dt_produk->SALE_PRICE); ?></span>
						<br />
                        <a href="javascript:void()" onclick="ajax_fancybox('<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>')">
                            <button type="button" 
                            		class="btn btn-fefault cart" 
                                    style="width:100%; margin-top:6px; margin-left:0; color:#FFF">
                                	<i class="fa fa-shopping-cart"></i>
                                	Simpan Item
                            </button>
                        </a>-->
                    <?php } ?>
                </span>
                <p><b>Merk Toko:</b> <?php echo $nm_merchant; ?></p>
                <p>
                	<b>Deskripsi:</b><br />
                    <?php if(!empty($dt_produk->DESCRIPTION)){?>
                        <?php echo @ucfirst($dt_produk->DESCRIPTION); ?>
                    <?php } ?>
                </p>
            </div><!--/product-information-->
        </div>
    </div><!--/product-details-->
    <?php } else{ ?>  
    	<div class="alert alert-danger">Maaf, Untuk Saat Ini, Produk Ini Belum Tersedia</div>
    <?php } ?>
    <div class="recommended_items"><!--recommended_items-->
        <h2 class="title text-center">Item <?php echo $nm_merchant; ?> Lainnya</h2>
        
        <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel"> <!---->
            <div class="carousel-inner">
				<?php
				$y = 0;
                $query_str	= " SELECT 
                                    a.*,b.PHOTOS 
                                FROM 
                                    ".$tpref."products a,".$tpref."products_photos b 
                                WHERE 
                                    a.ID_CLIENT = '".$id_coin."' AND
									a.ID_PRODUCT != '".$id_product."'  AND
                                    a.ID_PRODUCT = b.ID_PRODUCT AND 
									a.ID_STATUS != '1'
                                ORDER BY a.ID_PRODUCT DESC";
                                //echo $query_str;
                $num_produk	= $db->recount($query_str);
                if($num_produk > 0){?>
                    <div class="item active">	
                <?php
                    $q_produk 			= $db->query($query_str."  LIMIT 0,12");
                    while($dt_produk 	= $db->fetchNextObject($q_produk)){
						$y++;
                        $harga_diskon	= "";
                        @$photo 		= $dt_produk->PHOTOS;
                        @$harga			= $dt_produk->SALE_PRICE;?>            
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                            				<div class="thumbnail">
												<?php if(is_file($basepath."/files/images/products/".$id_coin."/".$dt_produk->PHOTOS)){?>
                                                <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo @$id_coin; ?>/<?php echo $dt_produk->PHOTOS; ?>' class="potrait"/>
                                                <?php }else{ ?>
                                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="potrait"/>
                                                <?php } ?>
                                            </div>
                                            <h2 style="height:23px;">
                                                <?php if(!empty($dt_produk->SALE_PRICE)){?>
                                                    <?php echo money("Rp.",@$dt_produk->SALE_PRICE); ?>
                                                <?php } ?>
                                             </h2>
											<?php if(!empty($dt_produk->NAME)){?>
                                            <p><?php echo @$dt_produk->NAME; ?></p>
											<?php } ?>
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
                                            <!--<li><a href="#"><i class="fa fa-shopping-cart"></i>Simpan Item</a></li>-->
                                            <?php } ?>
                                            <li><a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo strtolower(str_replace(" ","_",$dt_produk->NAME)); ?>"><i class="fa fa-search"></i>Lihat Item</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
						<?php  if($y == 3){ $y = 0?>
                    </div>
                    <div class="item">	
                        <?php } ?>
                <?php } ?>
                    </div>
                <?php } ?>        
                
            </div>
             <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
                <i class="fa fa-angle-left"></i>
              </a>
              <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
                <i class="fa fa-angle-right"></i>
              </a>			
        </div>
    </div><!--/recommended_items-->
