<?php 
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$no 			= isset($_REQUEST['no']) 		? $_REQUEST['no'] : "";
	if(empty($direction) || (!empty($direction) && $direction != "edit")){
		$searching 		= isset($_POST['searching']) 	? $_POST['searching'] 	: "";
		$filter 		= isset($_POST['filter']) 		? $_POST['filter'] 		: "";
		$item_type 		= isset($_POST['item_type']) 	? $_POST['item_type'] 	: "";
		if(!empty($item_type)){
			$condition = " AND ID_PRODUCT_TYPE ='".$item_type."'";
		}
		switch ($filter){
			case "code":
				$condition = " AND CODE ='".$searching."'";
			break;
			case "nama":
				$condition = " AND NAME ='".$searching."'";
			break;
			case "deskripsi":
				$condition = " AND DESCRIPTION LIKE '%".$searching."%'";
			break;
		}
		if($_SESSION['ulevelkey'] == 5){
			$id_sale = $_SESSION['uidkey'];	
			$nm_sale = $db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$_SESSION['uidkey']."'");	
		}
		if($_SESSION['uclevelkey'] == '1'){
			$query_str	= "SELECT * FROM ".$tpref."products WHERE ID_CLIENT='".$id_client."' ".@$condition." ORDER BY NAME ASC LIMIT 0,20";
		}else{
			
			$query_str	= 
			"SELECT 
				a.ID_PRODUCT_UNIT,
				a.CODE,
				a.NAME,
				b.ID_PRODUCT
			FROM 
				".$tpref."products a,".$tpref."products_stocks b 
			WHERE 
				a.ID_PRODUCT = b.ID_PRODUCT AND
				a.ID_CLIENT  = b.ID_CLIENT AND
				a.ID_CLIENT  = '".$id_client."'
				".@$condition." 
			ORDER BY 
				a.NAME ASC LIMIT 0,20";
		}
		$q_produk 	= $db->query($query_str);
		$num_produk	= $db->numRows($q_produk);
		?>
		<table class="popup table table-striped">
		<thead>
				<tr>
				  <th>&nbsp;</th>
				  <th><b>Kode Item</b></th>
				  <?php if(allow('insert') == 1){?>
				  <th style="text-align:center"><b class='req'>Jumlah</b></th>
				  <?php } ?>
				  <th style="text-align:center"><b>Stok </b></th>
				  <th style="text-align:center">Actions</th>
		  </tr>
		  </thead>
			<tbody>
			  <?php while($dt_produk = $db->fetchNextObject($q_produk)){ 
					$id_product			= $dt_produk->ID_PRODUCT;
					@$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
					@$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'"); 
					@$ori_stock			= $db->fob("STOCK",$tpref."products_stocks"," WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$id_client."'");
					if(empty($ori_stock)){
						$ori_stock = 0;	
					}
				  ?>
				  <tr id="item_<?php echo $id_product; ?>">
					<td style='padding-right:20px;'>
						<?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
							<img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="photo" style="width:100px;"/>
						<?php }else{ ?>
							<img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class='photo' style="width:100px;"/>
							
						<?php $photo = ""; } ?>
					</td>
					<td>
						<span class='code'><?php echo $dt_produk->CODE; ?></span>
						<br />
						<?php echo $dt_produk->NAME; ?>
					</td>
					<?php if(allow('insert') == 1){?>
					<td style="text-align:center">
						<?php if($ori_stock > 0){?>
						<span id="jumlah_form_<?php echo $id_product; ?>">
							<input type="text" id="jumlah_<?php echo $id_product; ?>" value="1" onkeyup="calculate_multi('insert','jumlah_<?php echo $id_product; ?>','<?php echo $id_product; ?>')" class="mousetrap" style='width:80%'/>
						</span>
						<?php }  ?>
					</td>
					<?php } ?>
					<td style="text-align:center">
						<span id="stock_label_<?php echo $id_product; ?>"><?php echo @$ori_stock; ?></span> <?php echo @$unit; ?>
						<input type='hidden' id='ori_stock_<?php echo $id_product; ?>' value='<?php echo @$ori_stock; ?>' />
					</td>
					<td style="text-align:center">
					<?php if(allow('insert') == 1){?>
						<div class="btn-group">
						<?php if($ori_stock > 0){?>
							<a href="javascript:void()" class="btn btn-mini" title="Edit" onclick="pic_item('<?php echo $id_product; ?>')">
								<i class="icon-plus" ></i>
							</a>
						<?php }else{ ?>
						<a href="<?php echo $dirhost; ?>/?page=input_penjualan_produk" class="btn btn-mini" title="Tambah Stock">
							<i class="icsw16-shopping-cart-2"></i>
						</a>
						<?php } ?>
						</div>
						<input type="hidden" id="value_<?php echo $id_product; ?>" value='"id_product":"<?php echo $id_product; ?>","photo":"<?php echo @$photo; ?>","code":"<?php echo $dt_produk->CODE; ?>","name":"<?php echo $dt_produk->NAME; ?>","units":"<?php echo $unit; ?>"' />
				   <?php } ?>
					</td>
				</tr>
				<?php } ?>    
			</tbody>
		</table>
	<?php } 
}
?>