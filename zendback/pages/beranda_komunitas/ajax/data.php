<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] : "";
	$id_diskon 	= isset($_REQUEST['id_diskon']) 	? $_REQUEST['id_diskon'] : "";
	
	
	if(!empty($direction) && $direction == "view_discount"){
			
		$q_diskon_list 	= $db->query("SELECT * FROM ".$tpref."clients_discounts WHERE ID_DISCOUNT = '".$id_diskon."' ");
		$dt_diskon_list = $db->fetchNextObject($q_diskon_list);
		
		@$id_discount	= $dt_diskon_list->ID_DISCOUNT;
		@$id_pattern	= $dt_diskon_list->ID_DISCOUNT_PATTERN;
		@$pattern 		= $db->fob("DESCRIPTION",$tpref."discount_patterns"," 
									WHERE ID_DISCOUNT_PATTERN = '".$id_pattern."'");
		$discount		= $dt_diskon_list->DISCOUNT;
		@$expiration	= $dt_diskon_list->EXPIRATION_DATE;
		$besar			= $dt_diskon_list->DISCOUNT_VALUE; 
		$piece			= $dt_diskon_list->DISCOUNT_UNIT;
		$sifat_jual		= $dt_diskon_list->SELLING_METHOD;
		@$jml_kupon		= $dt_diskon_list->SELLING_METHOD_PO_COUPON_QTY;
		@$pr_kupon		= $dt_diskon_list->SELLING_METHOD_PO_COUPON_PRICE;
		
		switch($id_pattern){
			case "1":
			if(substr_count($besar,";") > 0){ 
				$besar = "<label class='label label-default '>
							<a  href='javascript:void()' 
								onclick='item_discount_list(".$id_discount.")'>
								Lihat Daftar Item
							</a>
						  </label>"; 
			}
			if($besar == "all"){ $besar = "<label class='label label-warning'>Semua Item</label>"; }
			break;
			case "2":
				$besar = money("Rp.",$besar);
			break;
		}
		switch($sifat_jual){
			case "readystock": 
			$sifat_jual_label = 
				"<label class='label label-info'>Ready Stock</label>"; 
			break;
			case "preorder": 
			$sifat_jual_label = 
				"<a href='javascript:void' title='".$jml_kupon." Kupon / @".money("Rp.",$pr_kupon)."'>
					<label class='label label-warning'>Pre Order</label>
				</a>"; 
			break;
		}
	?>
        <div class="ibox-content">
            <table width="100%" class="table table-striped table-bordered no-margin-b">
                <thead>
                    <tr>
                        <th width="74" class="text-center">Diskon</th>
                        <th width="217">Pola Diskon</th>
                        <th width="109" class="text-center">Sifat Jual</th>
                        <th width="127">Syarat</th>
                        <th width="129" class="text-center">Masa Berlaku</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="disc_<?php echo $id_discount; ?>">
                        <td class="text-center"><?php echo $discount.$piece; ?></td>
                        <td><?php echo @$pattern; ?></td>
                        <td class="text-center"><?php echo @$sifat_jual_label; ?></td>
                        <td><?php echo @$besar; ?></td>
                        <td class="text-center">
                            <?php if(!empty($expiration) && $expiration != "0000-00-00"){
                                        echo $dtime->date2indodate($expiration); 
                                  }else{
                                        echo "<label class='label label-danger'>Unlimited</label>";  
                                  }
                            ?>
                        </td>
                     </tr>
            
                </tbody>
            </table>
    	
        
            <div class="clearfix"></div> 
            <span id="picked_item"></span>
            <div class="clearfix"></div> 
        </div>
	<?php
	}
	
	if(!empty($direction) && $direction == "show_item_discount_list"){
		$item_str		= "SELECT 
								DISCOUNT_VALUE,ID_CLIENT 
						   FROM 
						   		".$tpref."clients_discounts 
						   WHERE ID_DISCOUNT = '".$id_diskon."' ";
		$q_item_disc 	= $db->query($item_str);
		$dt_item_disc 	= $db->fetchNextObject($q_item_disc);
		$neutralize 	= str_replace(";","",$dt_item_disc->DISCOUNT_VALUE);
		$datas 			= explode(",",$neutralize);
	?>
    <div class="thumbnail" style="margin-top:10px; padding:9px" >
    <?php
		foreach($datas as &$data){ 
			if(!empty($data)){
				$query_str	= "SELECT * FROM ".$tpref."products 
							   WHERE ID_PRODUCT = '".$data."' ";
				$q_produk 	= $db->query($query_str);
				$dt_produk 	= $db->fetchNextObject($q_produk);
				$id_client	= $dt_item_disc->ID_CLIENT;
				$id_product = $dt_produk->ID_PRODUCT;
				@$photo		= $db->fob("PHOTOS",$tpref."products_photos"," 
										WHERE ID_PRODUCT = '".$id_product."'");
				@$unit		= $db->fob("NAME",$tpref."products_units"," 
										WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'");
				@$stock		= $db->fob("STOCK",$tpref."products_stocks"," 
										WHERE 
											ID_PRODUCT='".$id_product."' AND 
											ID_CLIENT='".$id_client."' "); 
				if(empty($stock)){ $stock = 0; }
				$st_status	= $dt_produk->ID_STATUS;
				@$kategori	= $db->fob("NAME",$tpref."products_categories"," 
										WHERE ID_PRODUCT_CATEGORY='".$dt_produk->ID_PRODUCT_CATEGORY."'");
	
	?>
                <div class="col-md-3">
                    <div class="thumbnail">
                        <div class="thumbnail-inner" style="height:99px;">
                            <?php if(!empty($photo) && 
                                  is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' style="width:100%"/>
                            <?php }else{ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' style="width:100%"/>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <table width="100%" class="table">
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
                    
                </div>
                <div class="col-md-12 divider_7"></div>
                <div class="clearfix"></div> 
	<?php 	}
		}
	?>
    <div class="clearfix"></div> 
    </div>
    <?php
	}
}
?>
