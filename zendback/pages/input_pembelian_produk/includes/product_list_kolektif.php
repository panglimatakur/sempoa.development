<?php 
if(!empty($_SESSION['cidkey']) && !empty($_SESSION['uidkey'])) {
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
		}
		$query_str	= "SELECT * FROM ".$tpref."products WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".@$condition." ORDER BY NAME ASC LIMIT 0,20";
	$q_produk 	= $db->query($query_str);
		$num_produk	= $db->numRows($q_produk);
		?>
		<table width="100%" class="popup table table-striped">
		<thead>
				<tr>
					<th width="16%">&nbsp;</th>
					<th width="10%"><b>Kode Item</b></th>
					<th width="48%"><b>Nama</b></th>
					<th width="13%" style="text-align:center"><b>Stok </b></th>
					<th width="13%" style="text-align:center">Actions</th>
		  </tr>
		  </thead>
			<tbody>
			  <?php while($dt_produk = $db->fetchNextObject($q_produk)){ 
					$id_product			= $dt_produk->ID_PRODUCT;
					@$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
					@$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'"); 
					@$ori_stock			= $db->fob("STOCK",$tpref."products_stocks"," WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
					if(empty($ori_stock)){
						$ori_stock = 0;	
					}
				  ?>
				  <input type='hidden' id='ori_stock_<?php echo $id_product; ?>' value='<?php echo @$ori_stock; ?>' />
				  <tr id="item_<?php echo $id_product; ?>">
					<td style='padding-right:20px;'>
						<?php if(!empty($photo)){ ?>
							<img src='files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="photo" style="width:100px;"/>
						<?php }else{ ?>
							<img src='files/images/no_image.jpg' class='photo' style="width:100px;"/>
						<?php } ?>
					</td>
					<td><?php echo $dt_produk->CODE; ?></td>
					<td>
					  <div id="msg_<?php echo $id_product; ?>"></div>
					  <?php echo $dt_produk->NAME; ?>
					  <table width="100%" border="0" class='form_input'>
						<tbody>
						  <tr>
							<td class='req'>Harga Beli</td>
							<td>
							<span class="input-prepend input-append">
							<span class="add-on">Rp.</span>
							<input type="text" id="harga_beli_<?php echo $id_product; ?>" value="" class="mousetrap" onkeyup="calculate_multi('insert','harga_beli_<?php echo $id_product; ?>','<?php echo $id_product; ?>')"  onblur="calculate_multi('insert','harga_beli_<?php echo $id_product; ?>','<?php echo $id_product; ?>')"/>
							 <span class="add-on">,00</span>
							 </span>
							</td>
						  </tr>
						  <tr>
							<td class='req'>Jumlah </td>
							<td>
							<input type="text" id="jumlah_<?php echo $id_product; ?>" value="1" class="mousetrap" onkeyup="calculate_multi('insert','jumlah_<?php echo $id_product; ?>','<?php echo $id_product; ?>')" onblur="calculate_multi('insert','jumlah_<?php echo $id_product; ?>','<?php echo $id_product; ?>')"/>
							</td>
						  </tr>
						  <tr>
							<td class='req'>Harga Jual</td>
							<td>
							<span class="input-prepend input-append">
							<span class="add-on">Rp.</span>
							<input type="text" id="harga_jual_<?php echo $id_product; ?>" value="" class="mousetrap" onkeyup="numeric(this)" onblur='numeric(this)'/>
							 <span class="add-on">,00</span>
							 </span>
							</td>
						  </tr>
						</tbody>
					</table>
						
					</td>
					<td style="text-align:center">
						<span id="stock_label_<?php echo $id_product; ?>"><?php echo @$ori_stock; ?></span> <?php echo @$unit; ?>
					</td>
					<td style="text-align:center">
					<?php if(allow('insert') == 1){?>
						<div class="btn-group">
							<a href="javascript:void()" class="btn btn-mini" title="Edit" onclick="pic_item('<?php echo $id_product; ?>')">
								<i class="icon-plus" ></i>
							</a>
						</div>
						<input type="hidden" id="value_<?php echo $id_product; ?>" value='"id_product":"<?php echo $id_product; ?>","photo":"<?php echo @$photo; ?>","code":"<?php echo $dt_produk->CODE; ?>","name":"<?php echo $dt_produk->NAME; ?>"' />
				   <?php } ?>
					</td>
				</tr>
				<?php } ?>    
			</tbody>
		</table>
	<?php } ?>

<?php }else{
	defined('mainload') or die('Restricted Access');
}
?>