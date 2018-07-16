<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../includes/config.php");
include_once("../../../../includes/classes.php");
include_once("../../../../includes/functions.php");
include_once("../../../../includes/declarations.php");
?>
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
	.detail_info b{
		font-weight:bold;
	}
    </style>
    <div class='row-fluid'>
        <?php
		$id_coin	= isset($_REQUEST['id_coin']) ? $_REQUEST['id_coin'] 	: "";
		$id_product 		= isset($_REQUEST['id_product']) 		? $_REQUEST['id_product'] : "";
		$q_produk 	= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$id_coin."'");
		$dt_produk	= $db->fetchNextObject($q_produk);
		
		$discount 	= $db->fob("VALUE",$tpref."client_discounts","WHERE ID_CLIENT = '".$id_coin."' AND COMMUNITY_FLAG != '0' AND REQUEST_BY_ID_CUSTOMER = ''");

	?>
		<h3 class="w-box-header"><?php echo @$dt_produk->CODE; ?> : <?php echo $dt_produk->NAME; ?></h3>
		<div class='span5' style="margin:0">
			<?php
			$q_photos 	= $db->query("SELECT * FROM ".$tpref."products_photos WHERE ID_PRODUCT='".$id_product."' ORDER BY ID_PRODUCT_PHOTO ASC LIMIT 0,1");
			while($dt_photos = $db->fetchNextObject($q_photos)){
				if(!empty($dt_photos->PHOTOS) || is_file($basepath."/files/images/products/".$id_coin."/".$dt_photos->PHOTOS)){
			?>
			<div class='photo' style="margin:5px; width:100%;">
				<img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $id_coin; ?>/<?php echo $dt_photos->PHOTOS; ?>' style='width:99%;'/>
			</div>
			<?php 
				}
			}
			?>
			<br clear="all" />
		</div>
		<div class='span5'>
			<div style="margin-top:5px;" class='detail_info'>
				<?php if(!empty($dt_produk->NAME)){?>
					<b >Nama : </b><br />
					<?php echo @$dt_produk->NAME; ?>
					<br />
				<?php } ?>
				
				<?php if(!empty($dt_produk->SALE_PRICE)){?>
					
					<?php if(!empty($discount)){?> 
                    
                    <b>Member Discoin Komunitas: </b><br />
                    	<span style="text-decoration:line-through">
							<?php echo money("Rp.",@$dt_produk->SALE_PRICE); ?>
                        </span>
                    	<span style="color:#F90;">( Disc <?php echo @$discount; ?>% )</span>
                        <br />
                        
					<?php 
							$min 		= ($dt_produk->SALE_PRICE/100) * $discount;
							$new_price	= $dt_produk->SALE_PRICE - $min;
							echo money("Rp.",@$new_price); 
					 }else{ ?>
						<b>Harga: </b><br />
                    	<?php echo money("Rp.",@$dt_produk->SALE_PRICE); ?>
					 <?php } ?>
					<br />
                    
				<?php } ?>
				
				<?php if(!empty($dt_produk->DESCRIPTION)){?>
					<b>Deskripsi : </b><br />
					<?php echo @$dt_produk->DESCRIPTION; ?>
					<br />
				<?php } ?>
			</div>
		</div>
        <br clear="all" />
        <br clear="all" />
    </div>	
