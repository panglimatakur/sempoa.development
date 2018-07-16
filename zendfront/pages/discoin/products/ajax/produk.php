<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../../includes/config.php");
include_once("../../../../../includes/classes.php");
include_once("../../../../../includes/functions.php");
include_once("../../../../../includes/declarations.php");
$parent_id	= isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] 	: "";
$detail		= isset($_REQUEST['detail']) 	? $_REQUEST['detail'] 		: "";
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {?>
	
	<style type="text/css">
	.next_button{ color:#D0AA62; font-weight:bold; text-decoration:none; font-family:Verdana, Geneva, sans-serif;}
    </style>
	<script language="javascript">
        function next_product(id_client){ 
            data_page 		= $("#data_page").val();
			var config 		= JSON.parse("{"+$("#config").val()+"}");
			var dirhost		= config.dirhost;		
			$('div#lastPostsLoader').html('<div style="clear:both; text-align:center; margin:10px; font-family:Verdana, Geneva, sans-serif;"><img src="'+dirhost+'/files/images/loader_v.gif"><br>Mengambil Data...</div>');
            lastId			= $(".wrdLatest:last").attr("data-info");
            $.ajax({
                url 	: data_page,
                type	: "POST",
                data	: {"id_coin":"<?php echo $parent_id; ?>","lastID":lastId,"display":"list_product_pop"},
                success : function(data){
                    data = $.trim(data);
                    if (data != "") {
                       $("div.wrdLatest:last").after(data);
                        /*$('html, body').animate({scrollTop:$("#gallery").offset().top}, 50);
                        $("#gallery").animate({scrollTop: $("#gallery")[0].scrollHeight}, 1500);*/
                    }
					if(data == ""){
						$('div#lastPostsLoader').remove();
					}
					$('div#lastPostsLoader').empty();
                }
            });
        };
    </script>
    <input type="hidden" id="data_page" value="<?php echo $dirhost; ?>/zendfront/pages/discoin/products/ajax/data.php">
    <div class="features_items"><!--features_items-->
		<?php
        $query_str	= " SELECT 
                            a.*,b.PHOTOS 
                        FROM 
                            ".$tpref."products a,".$tpref."products_photos b 
                        WHERE 
                            a.ID_CLIENT = '".$parent_id."' AND 
                            a.ID_PRODUCT = b.ID_PRODUCT
                        ORDER BY a.ID_PRODUCT DESC";
                        //echo $query_str;
        $num_produk	= $db->recount($query_str);
        if($num_produk > 0){
            $q_produk 	= $db->query($query_str."  LIMIT 0,8");
            while($dt_produk = $db->fetchNextObject($q_produk)){
                $harga_diskon= "";
                @$photo 	 = $dt_produk->PHOTOS;
                @$harga		 = $dt_produk->SALE_PRICE;
				@$file_name   = $db->fob("CLIENT_APP",$tpref."clients","WHERE ID_CLIENT='".$dt_produk->ID_CLIENT."'");
        ?>
            <div class="col-sm-3 wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>'>
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                        <div class="overflow_height_1">
                        <a href="<?php echo $dirhost; ?>/<?php echo $filename; ?>.coin/detail/<?php echo $dt_produk->ID_PRODUCT; ?>/<?php echo permalink($dt_produk->NAME,""); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>">
						<?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$parent_id."/thumbnails/".$photo)){ ?>
                       
                            <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $parent_id; ?>/thumbnails/<?php echo $photo; ?>'/>
                        <?php }else{ ?>
                            <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg'/>
                        <?php } ?>
                        </a>
                       	</div>
                            <h2 style="height:23px"><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                            <p><?php echo ucwords($dt_produk->NAME); ?></p>
                        </div>
						<?php if(!empty($dt_produk->SALE_PRICE)){?>
                        <!--<div class="product-overlay">
                            <div class="overlay-content">
                                <h2><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                                <p><?php echo ucwords($dt_produk->NAME); ?></p>
                                <a href="javascript:void()" onclick="ajax_fancybox('<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>')" class="btn btn-default add-to-cart">
                                	<i class="fa fa-shopping-cart"></i>Simpan Item
                                </a>
                            </div>
                        </div>	-->
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
		if($num_produk > 10 && @$detail != "true"){
			if(empty($lastID)){	?>
            <div style="text-align:center">
                <a href='javascript:void()' onclick="next_product('<?php echo $parent_id; ?>')" class='btn  btn-primary' style="width:100%"><i class="fa fa-arrow-circle-o-right"></i> SELANJUTNYA</a>
            <br clear="all" />
            </div>
		<?php }
		} 
		?>
    </div>
<?php } ?>