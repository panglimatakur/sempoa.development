<?php defined('mainload') or die('Restricted Access'); ?>
 <div class="features_items"><!--features_items-->
    <h2 class="title text-center">Produk Baru</h2>
    <?php
    if($num_produk > 0){
        $q_produk 	= $db->query($query_str."  LIMIT 0,9");
        while($dt_produk = $db->fetchNextObject($q_produk)){
            $harga_diskon	= "";
            @$photo 		= $dt_produk->PHOTOS;
            @$harga			= $dt_produk->SALE_PRICE;
    ?>
            <div class="col-sm-4">
                <div class="product-image-wrapper">
                    <div class="single-products">
                            <div class="productinfo text-center">
                            	<div class="thumbnail">
                                <a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo strtolower(str_replace(" ","_",$dt_produk->NAME)); ?>" title="<?php echo ucwords($dt_produk->NAME); ?>">
                                <?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_coin."/thumbnails/".$photo)){ ?>
                               
                                    <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' alt=" <?php echo ucwords($dt_produk->NAME); ?>" title="<?php echo ucwords($dt_produk->NAME); ?>" class="potrait"/>
                                <?php }else{ ?>
                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>"  class="potrait"/>
                                <?php } ?>
                                </a>  
                                </div>                  
                                <h2 style="height:23px"><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?>&nbsp;</h2>
                                
                            </div>
                            <?php if(!empty($dt_produk->SALE_PRICE)){?>
                            <!--<div class="product-overlay">
                                <div class="overlay-content">
                                    <h2><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                                    <p><?php echo ucwords($dt_produk->NAME); ?></p>
                                    <a href="javascript:void()" 
                                       data-url="<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>" 
                                       class="btn btn-default add-to-cart to-cart"><i class="fa fa-shopping-cart"></i>Simpan Item</a>
                                </div>
                            </div>-->
                            <?php } ?>
                    </div>
                    <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                        	<?php if(!empty($dt_produk->SALE_PRICE)){?>
                            <!--<li><a href="javascript:void()" data-url="<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>"  class="to-cart">
                            <i class="fa fa-shopping-cart"></i>Simpan Item</a>
                            </li>-->
                            <?php } ?>
                            <li><a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo permalink($dt_produk->NAME,""); ?>" title="Daftar Produk / Item <?php echo $nm_merchant; ?>"><i class="fa fa-search"></i>Lihat Item</a></li>
                        </ul>
                    </div>
                </div>
            </div>
    <?php }
    } 
    ?>
    <br clear="all" />
        <div style="text-align:center" class="col-sm-12">
            <a href='<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/products' class='btn  btn-primary' style="width:100%"  title="Daftar Produk / Item <?php echo $nm_merchant; ?>"><i class="fa fa-arrow-circle-o-right"></i> SELENGKAPNYA </a>
        <br clear="all" />
        </div>
        <br clear="all" />
</div><!--features_items-->
<br clear="all" />



<?php
if($num_community > 0 && $num_discount_client > 0){?>
<div class="features_items"><!--features_items-->   
    <h2 class="title text-center">Mitra Discoin <?php echo @$nm_merchant; ?></h2>
    <div style="color:#F00; text-align:center"> 
    
    	<strong>NOTE :</strong><br /> 
    	COIN dari Aplikasi Discoin member <?php echo $nm_merchant; ?>, bisa di validasi untuk menikmati diskon belanja di merchant dibawah ini.
    
    </div>
    <br />
</div>
<div class="features_items"><!--category-tab-->
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <?php 
            $i = 0;
            while($dt_comm	= $db->fetchNextObject($q_list_comm)){  
                $i++;
                $lastID 		= $dt_comm->ID_COMMUNITY;
                $nm_komunitas 	= @$db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY='".$dt_comm->ID_COMMUNITY."'");
                ?>
            <li <?php if($i==1){?>class="active"<?php } ?>><a href="#komunitas_<?php echo $dt_comm->ID_COMMUNITY; ?>" data-toggle="tab">Komunitas <?php echo $nm_komunitas; ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="tab-content" style="margin-top:20px; padding-top:30px;">
    <?php
    while($dt_comm	= $db->fetchNextObject($q_list_merch)){  $j++;
        $str_merchant  	= "SELECT 
                                a.ID_COMMUNITY,
                                b.ID_CLIENT,
                                b.CLIENT_NAME,
								b.CLIENT_APP
                           FROM 
                                ".$tpref."communities_merchants a,".$tpref."clients b
                           WHERE 
                                a.ID_CLIENT = b.ID_CLIENT AND
                                b.ACTIVATE_STATUS = '3' AND
                                a.ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND 
                                (a.ID_CLIENT != '1' AND a.ID_CLIENT != '".$id_coin."') 
                           ORDER BY 
                                a.ID_COMMUNITY_MERCHANT ASC limit 0,6";
        $q_merchant 	= $db->query($str_merchant); ?>
        
        <div class="tab-pane fade  <?php if($j==1){?>active<?php } ?> in community-list" 
              id="komunitas_<?php echo $dt_comm->ID_COMMUNITY; ?>" >
            <?php while($dt_merchant	= $db->fetchNextObject($q_merchant)){
					$q_discount_partner 	 = $db->query("SELECT * FROM ".$tpref."clients_discounts WHERE ID_CLIENT  = '".$dt_merchant->ID_CLIENT."'"); 
					$num_discount_partner = $db->numRows($q_discount_partner); 
					if($num_discount_partner > 0){
			
			?>
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <div class="thumbnail">
                                    <?php echo getclientlogo($dt_merchant->ID_CLIENT," 
											   title='".$dt_merchant->CLIENT_NAME."' 
											   alt='".$dt_merchant->CLIENT_NAME."' class='potrait'"); ?>
                                </div>
                                <h2 style="font-size:13px;"><?php echo @$dt_merchant->CLIENT_NAME; ?></h2>
                                <a href="<?php echo $dirhost; ?>/<?php echo @$dt_merchant->CLIENT_APP; ?>.coin" 
                                   title="Halaman Discoin <?php echo $dt_merchant->CLIENT_NAME; ?>">
                                   <button type="button" class="btn btn-primary add-to-cart view-merchant" value="show">
                                        <i class="fa fa-home"></i> Kunjungi
                                   </button>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </div>
                
            <?php 
					}
			} 
			?>
            <div style="text-align:center" class="col-sm-12">
                <a href='<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/community' 
                   class='btn  btn-primary' style="width:100%"  
                   title="Komunitas Brand <?php echo $nm_merchant; ?>">
                        <i class="fa fa-arrow-circle-o-right"></i> SELENGKAPNYA 
                 </a>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    <?php 
    } ?>            
    </div>
</div><!--/category-tab-->
<?php } ?>