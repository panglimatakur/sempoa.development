<?php defined('mainload') or die('Restricted Access'); ?>
 <div class="features_items"><!--features_items-->
    <h2 class="title text-center">Produk Baru</h2>
    <input type="hidden" id="data_page" value="<?php echo $dirhost; ?>/zendfront/pages/discoin/<?php echo $discoin_page; ?>/ajax/data.php">
    <?php
    if($num_produk > 0){
        $q_produk 	= $db->query($query_str."  LIMIT 0,6");
        while($dt_produk = $db->fetchNextObject($q_produk)){
            $harga_diskon	= "";
            @$photo 		= $dt_produk->PHOTOS;
            @$harga			= $dt_produk->SALE_PRICE;
    ?>
            <div class="col-sm-4 wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>'>
                <div class="product-image-wrapper">
                    <div class="single-products">
                            <div class="productinfo text-center">
                            	<div class="thumbnail">
                                <a href="<?php echo $dirhost; ?>/<?php echo $file_name; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo permalink($dt_produk->NAME,""); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>">
                                <?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_coin."/thumbnails/".$photo)){ ?>
                               
                                    <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' alt="<?php echo ucwords($dt_produk->NAME); ?>" title="<?php echo ucwords($dt_produk->NAME); ?>" class="potrait"/>
                                <?php }else{ ?>
                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' alt="<?php echo ucwords($dt_produk->NAME); ?>" title="<?php echo ucwords($dt_produk->NAME); ?>" class="potrait"/>
                                <?php } ?>
                                </a>   
                                </div>                 
                                <h2 style="height:23px"><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
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
    <?php }
    } else{?>
  	<div class="status alert alert-danger">Item Produk Untuk Kategori Ini Tidak Ditemukan</div>  
    <?php }  ?>
    <?php if(empty($lastID)){?><div id="lastPostsLoader"></div> <?php } ?>
    <br clear="all" />
    <?php 
    if($num_produk > 10 && @$detail != "true"){
        if(empty($lastID)){	?>
        <div style="text-align:center">
            <a href='javascript:void()' onclick="next_product('<?php echo $id_coin; ?>')" class='btn  btn-primary' style="width:100%"><i class="fa fa-arrow-circle-o-right"></i> SELANJUTNYA</a>
        <br clear="all" />
        </div>
    <?php }
    } ?>
    <br clear="all" />
</div><!--features_items-->
