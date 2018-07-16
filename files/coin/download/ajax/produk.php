<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../includes/config.php");
include_once("../../../../includes/classes.php");
include_once("../../../../includes/functions.php");
include_once("../../../../includes/declarations.php");

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {?>
	<style type="text/css">
    html,body{margin:0;}
	ul#gallery li{
		list-style:none;
		margin:0;
		font-family:Verdana, Geneva, sans-serif;
		text-align:center;
		padding:0;
	}
	.gallery_pic{ 
		border:2px solid #FFF;
		border-radius:2px;
		-moz-border-radius:2px;
		-webkit-border-radius:2px;
		box-shadow:#333 2px 2px 4px;
		margin-bottom:5px;
		width:95%;
		height:200px;
		overflow:hidden;
	}
	.gallery_pic img{
		width:100%;
	}
	.gallery_info{ padding:4px; border:1px solid #D4D4D4; background:#F2F2F2; width:95%; text-align:center; font-weight:bold; }
	.next_button{ color:#D0AA62; font-weight:bold; text-decoration:none; font-family:Verdana, Geneva, sans-serif;}
    </style>
	<?php
    $parent_id	= isset($_REQUEST['parent_id']) ? $_REQUEST['parent_id'] 	: "";
    $detail		= isset($_REQUEST['detail']) 	? $_REQUEST['detail'] 		: "";
	if(!empty($direction) && $direction == "show_detail"){
		$no 		= isset($_REQUEST['no']) 		? $_REQUEST['no'] : "";
		$q_produk 	= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$no."' AND ID_CLIENT='".$parent_id."'");
		$dt_produk	= $db->fetchNextObject($q_produk);
	?>
		<h3 class="w-box-header"><?php echo @$dt_produk->CODE; ?> : <?php echo @$dt_produk->NAME; ?></h3>
		<div class='span5' style="margin:0">
			<?php
			$q_photos 	= $db->query("SELECT * FROM ".$tpref."products_photos WHERE ID_PRODUCT='".$no."' ORDER BY ID_PRODUCT_PHOTO ASC LIMIT 0,1");
			while($dt_photos = $db->fetchNextObject($q_photos)){
				if(!empty($dt_photos->PHOTOS) || is_file($basepath."/files/images/products/".$parent_id."/".$dt_photos->PHOTOS)){
			?>
			<div class='photo' style="margin:5px; width:100%;">
				<img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $parent_id; ?>/<?php echo $dt_photos->PHOTOS; ?>' style='width:99%;'/>
			</div>
			<?php 
				}
			}
			?>
			<br clear="all" />
		</div>
		<div class='span5'>
			<div style="margin-top:5px;">
				<?php if(!empty($dt_produk->NAME)){?>
					<b>Nama : </b>
					<?php echo @$dt_produk->NAME; ?>
					<br />
				<?php } ?>
				
				<?php if(!empty($dt_produk->SALE_PRICE)){?>
					<b>Harga : </b>
					<?php echo money("Rp.",@$dt_produk->SALE_PRICE); ?>
					<br />
				<?php } ?>
				
				<?php if(!empty($dt_produk->DISCOUNT)){?>
					<b>Diskon : </b><?php echo @$dt_produk->DISCOUNT; ?>%
					<br />
				<?php } ?>
				<?php if(!empty($dt_produk->DESCRIPTION)){?>
					<b>Deskripsi : </b><br />
					<?php echo @$dt_produk->DESCRIPTION; ?>
					<br />
				<?php } ?>
			</div>
		</div>
<?php }?>

	<?php if(!empty($lastID)){?>
	<script language="javascript">
        function next_product(id_client){ 
            data_page 		= $("#data_page").val();
            $('div#lastPostsLoader').html('<div style="text-align:center; margin:10px; font-family:Verdana, Geneva, sans-serif;"><img src="<?php echo $dirhost; ?>/files/images/loading-bars.gif"><br>Mengambil Data...</div>');
            lastId			= $(".wrdLatest:last").attr("data-info");
            $.ajax({
                url 	: data_page,
                type	: "POST",
                data	: {"parent_id":"<?php echo $parent_id; ?>","lastID":lastId,"display":"list_product"},
                success : function(data){
                    data = $.trim(data);
                    if (data != "") {
                        $("ul#gallery li.wrdLatest:last").after(data);
                        $('html, body').animate({scrollTop:$("#gallery").offset().top}, 50);
                        $("#gallery").animate({scrollTop: $("#gallery")[0].scrollHeight}, 1500);
                    }
                    if(lastId == ""){
                        $('div#lastPostsLoader').remove();
                    }
                    $('div#lastPostsLoader').empty();
                    
                }
            });
        };
    </script>
    <div class="features_items"><!--features_items-->
		<?php
        @$lastID 	= isset($_REQUEST['lastID']) ? $_REQUEST['lastID'] : "";
        $display	= isset($_REQUEST['display']) ? $_REQUEST['display'] : "";
        $next_list	= "";
        if(!empty($display)){
            $next_list = " AND a.ID_PRODUCT < ".$lastID." "; 
        }
        $query_str	= " SELECT 
                            a.*,b.PHOTOS 
                        FROM 
                            ".$tpref."products a,".$tpref."products_photos b 
                        WHERE 
                            a.ID_CLIENT = '".$parent_id."' AND 
                            a.ID_PRODUCT = b.ID_PRODUCT
                            ".$next_list."
                        ORDER BY a.ID_PRODUCT DESC";
                        //echo $query_str;
        $num_produk	= $db->recount($query_str);
        if($num_produk > 0){
            $q_produk 	= $db->query($query_str."  LIMIT 0,10");
            while($dt_produk = $db->fetchNextObject($q_produk)){
                $harga_diskon	= "";
                @$photo 		= $dt_produk->PHOTOS;
                @$harga			= $dt_produk->SALE_PRICE;
        ?>
            <div class="col-sm-4">
                <div class="product-image-wrapper wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>'>
                    <div class="single-products">
                        <div class="productinfo text-center">
                        <?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$parent_id."/thumbnails/".$photo)){ ?>
                       
                            <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $parent_id; ?>/thumbnails/<?php echo $photo; ?>'/>
                        <?php }else{ ?>
                            <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg'/>
                        <?php } ?>
                            <h2><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                            <p><?php echo ucwords($dt_produk->NAME); ?></p>
                        </div>
                    
                        <div class="product-overlay">
                            <div class="overlay-content">
                                <h2><?php if(!empty($dt_produk->SALE_PRICE)){ echo money("Rp.",@$harga);  } ?></h2>
                                <p><?php echo ucwords($dt_produk->NAME); ?></p>
                                <a href="#" class="btn btn-default add-to-cart">
                                	<i class="fa fa-shopping-cart"></i>Simpan Item
                                </a>
                            </div>
                        </div>	
                    </div>
                    <div class="choose">
                        <ul class="nav nav-pills nav-justified">
                            <li><a href="#"><i class="fa fa-shopping-cart"></i>Simpan Item</a></li>
                            <li><a href="#"><i class="fa fa-search"></i>Lihat Item</a></li>
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
		<div class="w-box-footer" style="text-align:center">
			<a href='javascript:void()' onclick="next_product('<?php echo $parent_id; ?>')" class='next_button myButton orange'>
				SELANJUTNYA
			</a>
			<br clear="all" />
		</div>
		<?php }
		} ?>
		<?php } ?>
    </div>
    
    	
<?php } ?>