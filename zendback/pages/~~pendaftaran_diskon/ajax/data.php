<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
	}else{
		defined('mainload') or die('Restricted Access');	
	}
	$direction 	= isset($_REQUEST['direction']) 	? $sanitize->str($_REQUEST['direction']) 	: "";
	$id_diskon 	= isset($_REQUEST['id_diskon'])   	? $_REQUEST['id_diskon'] 	: "";
	$id_product = isset($_REQUEST['id_product']) 	? $sanitize->number($_REQUEST['id_product']): "";
	$pattern 	= isset($_REQUEST['pattern']) 		? $sanitize->str($_REQUEST['pattern']) 		: "";
	$lastID 	= isset($_REQUEST['lastID']) 		? $sanitize->number($_REQUEST['lastID']) 	: "";
	
}else{
	defined('mainload') or die('Restricted Access');
}
	
	if((!empty($direction) && $direction == "show_pattern") || !empty($direction) && !empty($pattern)){
		   
		switch($pattern){
			case "2":
	?>
            <div class="form-group col-md-4">  
                <label class="req">Minimal Pembelian</label>
                <div class="input-group">
                    <span class="input-group-addon">Rp.</span>
                	<input type="number" name="nilai" id="nilai" class='form-control' value='<?php echo @$nilai; ?>'/> 
           			<span class="input-group-addon">,00</span>
                </div>
            </div>
    <?php
			break;
			case "1":
	?>
            <div class="form-group col-md-4">  
                <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Diskon ini, berlaku untuk beberapa item kah? atau untuk semua item (all item) ?">Jumlah Item</label>
                <select name="nilai" id="nilai" class="form-control" 
                		onchange="open_item(this);">
                    <option value='all' 
						<?php if(!empty($nilai) && $nilai == "all"){?>selected<?php } ?>>
                    	Semua Item
                    </option>
                    <option value='few' 
						<?php if(!empty($nilai) && $nilai != "all"){?>selected<?php } ?>>Beberapa Item</option>
                </select>
             </div>
    <?php
			break;
		}
	}
	
	
	if(!empty($direction) && $direction == "show_item_list"){
		if(!empty($lastID)){ $condition = " AND ID_PRODUCT < '".$lastID."'"; }
		$query_str	= "SELECT * FROM ".$tpref."products WHERE ID_CLIENT='".$_SESSION['cidkey']."' ".@$condition." ORDER BY ID_PRODUCT DESC ";
		$q_produk 	= $db->query($query_str." LIMIT 0,10");
		$num_produk	= $db->recount($query_str);
		if($num_produk > 0){
			while($dt_produk = $db->fetchNextObject($q_produk)){ 
				$id_product = $dt_produk->ID_PRODUCT;
				@$photo		= $db->fob("PHOTOS",$tpref."products_photos"," 
										WHERE ID_PRODUCT = '".$dt_produk->ID_PRODUCT."'");
				@$unit		= $db->fob("NAME",$tpref."products_units"," 
										WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'");
				@$stock		= $db->fob("STOCK",$tpref."products_stocks"," 
										WHERE 
											ID_PRODUCT='".$dt_produk->ID_PRODUCT."' AND 
											ID_CLIENT='".$_SESSION['cidkey']."' "); 
				if(empty($stock)){ $stock = 0; }
				$st_status	= $dt_produk->ID_STATUS;
				@$kategori	= $db->fob("NAME",$tpref."products_categories"," 
										WHERE ID_PRODUCT_CATEGORY='".$dt_produk->ID_PRODUCT_CATEGORY."'");
	
		  ?>
				<div class="wrdLatest col-md-12" data-info='<?php echo $id_product; ?>' id="pr_<?php echo $id_product; ?>">
					<span id="msg_<?php echo $id_product; ?>"></span>
					<div class="col-md-3">
						<div class="thumbnail md">
							<?php if(!empty($photo) && 
                                  is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                <img src='<?php echo $dirhost; ?>/<?php echo $prod_dir; ?>/thumbnails/<?php echo $photo; ?>' style="width:100%" class="potrait"/>
                            <?php }else{ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' style="width:100%" class="potrait"/>
                            <?php } ?>
						</div>
					</div>
	
					<div class="col-md-9">
						<table width="100%" class="table table-bordered table-striped">
							<tr>
								<td colspan='2'><b><?php echo $dt_produk->CODE; ?> - <?php echo cutext($dt_produk->NAME,20); ?></b></td>
							</tr>
							<tr>
								<td width="13%"><strong>Stok</strong></td>
								<td width="87%"><?php echo $stock; ?> <?php echo $unit; ?></td>
							</tr>
							<tr>
								<td><b>Harga</b></td>
								<td>
									<?php echo @money("Rp.",$dt_produk->SALE_PRICE); ?>
									<?php if(!empty($unit)){?>
											/ <?php  echo @$unit; ?>
									<?php } ?>
								</td>
							</tr>
						</table>
						<button type="button" class="btn btn-white btn-sm btn-pick-item" 
								onclick="pick_item('<?php echo $id_product; ?>')">
								<i class="fa fa-check"></i> Pilih Item
						</button>
						
					</div>
					<div class="col-md-12 divider_7"></div>
					<div class="clearfix"></div> 
				</div>
			<?php } 
	?>
    		<div class="clearfix"></div> 
    <?php
		}else{
			echo "finish";
		}
		
	}
	
	
	if(!empty($direction) && $direction == "get_picked_item"){
		$query_str	 	= " SELECT a.ID_CLIENT,
								   a.CODE,
								   a.NAME,
								   b.PHOTOS
							FROM 
								".$tpref."products a,
								".$tpref."products_photos b
							WHERE 
								a.ID_PRODUCT = b.ID_PRODUCT AND
								a.ID_PRODUCT = '".$id_product."' ";
		$q_produk 	 	= $db->query($query_str);
		@$dt_produk 	= $db->fetchNextObject($q_produk);
		@$id_client		= $dt_produk->ID_CLIENT;
		@$code_product	= $dt_produk->CODE;
		@$nm_product  	= $dt_produk->NAME;
		@$photo		 	= $dt_produk->PHOTOS;
	?>
    	<div class="col-md-3" id="picked_pr_<?php echo $id_product; ?>">
        	<input type="hidden" name="id_products[]" value="<?php echo $id_product; ?>" />
            <div class="ibox float-e-margins" >
                <div class="ibox-title">
                    <h5 ><?php echo $code_product ?></h5>
                    <div class="ibox-tools">
                        <a href="javascript:void()" 
                           onclick="del_picked_preview_item('<?php echo $id_product; ?>')">
                           <i class="fa fa-trash"></i>
                         </a>
                    </div>
                </div>
                <div class="ibox-content" >
                    <div class="thumbnail md">
						<?php if(!empty($photo) && 
                              is_file($basepath."/files/images/products/".$id_client."/".$photo)){ ?>
                            <img src='<?php echo $dirhost; ?>/<?php echo $prod_dir; ?>/<?php echo $photo; ?>' style="width:100%" class="potrait"/>
                        <?php }else{ ?>
                            <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' style="width:100%" class="potrait"/>
                        <?php } ?>
                    </div>
                </div>
        	</div>
            <div class="clearfix"></div>
       </div>
	<?php
	}
	
	if(!empty($direction) && $direction == "show_item_discount_list"){
		$item_str		= "SELECT 
								DISCOUNT_VALUE 
						   FROM 
						   		".$tpref."clients_discounts 
						   WHERE 
						   		ID_CLIENT='".$_SESSION['cidkey']."' AND 
								ID_DISCOUNT = '".$id_diskon."' ";
		$q_item_disc 	= $db->query($item_str);
		$dt_item_disc 	= $db->fetchNextObject($q_item_disc);
		$neutralize 	= str_replace(";","",$dt_item_disc->DISCOUNT_VALUE);
		$datas 			= explode(",",$neutralize);
		foreach($datas as &$data){ 
			if(!empty($data)){
				$query_str	= "SELECT * FROM ".$tpref."products 
							   WHERE 
									ID_CLIENT='".$_SESSION['cidkey']."' AND 
									ID_PRODUCT = '".$data."' ";
				$q_produk 	= $db->query($query_str);
				$dt_produk 	= $db->fetchNextObject($q_produk);
				$id_product = $dt_produk->ID_PRODUCT;
				@$photo		= $db->fob("PHOTOS",$tpref."products_photos"," 
										WHERE ID_PRODUCT = '".$id_product."'");
				@$unit		= $db->fob("NAME",$tpref."products_units"," 
										WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'");
				@$stock		= $db->fob("STOCK",$tpref."products_stocks"," 
										WHERE 
											ID_PRODUCT='".$id_product."' AND 
											ID_CLIENT='".$_SESSION['cidkey']."' "); 
				if(empty($stock)){ $stock = 0; }
				$st_status	= $dt_produk->ID_STATUS;
				@$kategori	= $db->fob("NAME",$tpref."products_categories"," 
										WHERE ID_PRODUCT_CATEGORY='".$dt_produk->ID_PRODUCT_CATEGORY."'");
	
	  ?>
                <div class="wrdLatest col-md-12" data-info='<?php echo $id_product; ?>' id="pr_<?php echo $id_product; ?>">
                    <span id="msg_<?php echo $id_product; ?>"></span>
                    <div class="col-md-3">
                        <div class="thumbnail md">
							<?php if(!empty($photo) && 
                                  is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                <img src='<?php echo $dirhost; ?>/<?php echo $prod_dir; ?>/thumbnails/<?php echo $photo; ?>' style="width:100%" class="potrait"/>
                            <?php }else{ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' style="width:100%" class="potrait"/>
                            <?php } ?>
                        </div>
                    </div>
    
                    <div class="col-md-9">
                        <table width="100%" class="table table-bordered table-striped">
                            <tr>
                                <td colspan='2'><b><?php echo $dt_produk->CODE; ?> - <?php echo cutext($dt_produk->NAME,20); ?></b></td>
                            </tr>
                            <tr>
                                <td width="13%"><strong>Stok</strong></td>
                                <td width="87%"><?php echo $stock; ?> <?php echo $unit; ?></td>
                            </tr>
                            <tr>
                                <td><b>Harga</b></td>
                                <td>
                                    <?php echo @money("Rp.",$dt_produk->SALE_PRICE); ?>
                                    <?php if(!empty($unit)){?>
                                            / <?php  echo @$unit; ?>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                        <button type="button" class="btn btn-white btn-sm btn-pick-item" 
                                onclick="del_picked_item('<?php echo $id_diskon; ?>','<?php echo $id_product; ?>')">
                                <i class="fa fa-trash"></i> Hapus Item
                        </button>
                        
                    </div>
                    <div class="col-md-12 divider_7"></div>
                    <div class="clearfix"></div> 
                </div>
		<?php }
		}
	?>
        <div class="clearfix"></div> 
    <?php
	}
	
?>
