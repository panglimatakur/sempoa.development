<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$id_coin 	= isset($_REQUEST['id_coin']) 	? $_REQUEST['id_coin'] 	: "";	
	
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
							a.ID_CLIENT = '".$id_coin."' AND 
							a.ID_PRODUCT = b.ID_PRODUCT
							".$next_list."
						ORDER BY a.ID_PRODUCT DESC";
						//echo $query_str;
		$num_produk	= $db->recount($query_str);
		$discount 	= $db->fob("VALUE",$tpref."client_discounts","WHERE ID_CLIENT = '".$id_coin."' AND COMMUNITY_FLAG != '0' AND REQUEST_BY_ID_CUSTOMER = ''");
		if($num_produk > 0){
			$q_produk 	= $db->query($query_str."  LIMIT 0,10");
			while($dt_produk = $db->fetchNextObject($q_produk)){
				$harga_diskon	= "";
				@$photo 		= $dt_produk->PHOTOS;
				@$harga			= $dt_produk->SALE_PRICE;
		?>
		<li style="list-style:none; padding:3px; " class="span4 wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>'>
			<div class='gallery_pic'>
				<a href="<?php echo $dirhost; ?>/files/coin/download/ajax/detail.php?id_product=<?php echo $dt_produk->ID_PRODUCT; ?>&id_coin=<?php echo $id_coin; ?>" class="fancybox fancybox.ajax" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>">
				<?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$id_coin."/thumbnails/".$photo)){ ?>
			   
					<img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $id_coin; ?>/thumbnails/<?php echo $photo; ?>' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>"/>
				<?php }else{ ?>
					<img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' alt="Potongan Harga <?php echo ucwords($dt_produk->NAME); ?>" title="Diskon <?php echo ucwords($dt_produk->NAME); ?>"/>
				<?php } ?>
				</a>
			</div>
			
			
			<div class='gallery_info'>
				<?php echo ucwords($dt_produk->NAME); ?><br />
				<?php if(!empty($dt_produk->SALE_PRICE)){
						$min 		= ($dt_produk->SALE_PRICE/100) * $discount;
						$new_price	= $dt_produk->SALE_PRICE - $min;                           
						if(empty($discount)){
							echo money("Rp.",@$harga);
						}else{?>
							<i class='code'><?php echo money("Rp.",@$harga); ?></i>
							<br />
							<span style="color:#F90;">( Disc <?php echo @$discount; ?>% )</span>
							<br />
							<b style="Diskon <?php echo ucwords($dt_produk->NAME); ?>">
								Member Discoin Komunitas.
							 </b>
							<br />
							<?php echo money("Rp.",@$new_price); ?>
						<?php }
					?>
					<br />
			<?php } ?>
			</div>
			<div style='clear:both'></div>
		</li>
		<?php
			}
		}
		?>
		<?php if(empty($lastID)){?><div id="lastPostsLoader"></div> <?php } ?>
                
<?php
}
?>