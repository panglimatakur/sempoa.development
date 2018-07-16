<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	include $call->clas("class.html2text");
	$id_type 		= isset($_REQUEST['id_type']) 			? $_REQUEST['id_type'] : "";
	$id_parent 		= isset($_REQUEST['id_parent']) 		? $_REQUEST['id_parent'] : "";
	$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
	$add_new 		= isset($_REQUEST['add_new']) 			? $_REQUEST['add_new'] : "";


	if((!empty($display) && $display == "list_report")){
		@$lastID 	= $_REQUEST['lastID'];
		
		$id_type_report 	= isset($_REQUEST['id_type_report']) 	? $sanitize->str($_REQUEST['id_type_report'])	:"";
		$id_kategori_report = 
			isset($_REQUEST['id_kategori_report']) ? $sanitize->number($_REQUEST['id_kategori_report'])				:"";
		$code_report 		= isset($_REQUEST['code_report']) 		? $sanitize->str($_REQUEST['code_report'])		:"";
		$nama_report 		= isset($_REQUEST['nama_report']) 		? $sanitize->str($_REQUEST['nama_report'])		:"";
		$deskripsi_report 	= isset($_REQUEST['deskripsi_report']) 	? $sanitize->str($_REQUEST['deskripsi_report'])	:"";
		
	
		$condition = "";
		if(!empty($id_type_report))		{ $condition 	.= "AND ID_PRODUCT_TYPE 	= '".$id_type_report."' "; 		}
		if(!empty($id_kategori_report))	{ $condition 	.= "AND ID_PRODUCT_CATEGORY = '".$id_kategori_report."' "; 		}
		if(!empty($code_report))		{ $condition 	.= "AND CODE 				= '".$code_report."' "; 			}
		if(!empty($nama_report))		{ $condition 	.= "AND NAME				LIKE '%".$nama_report."%' "; 		}
		if(!empty($deskripsi_report))	{ $condition 	.= "AND DESCRIPTION 		LIKE '%".$deskripsi_report."%' "; 	}
	
		$query_str	= "SELECT * FROM ".$tpref."products WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_PRODUCT < ".$lastID." ORDER BY ID_PRODUCT DESC ";
		$q_produk 	= $db->query($query_str."  LIMIT 0,10");
		$num_produk	= $db->recount($query_str);
		  while($dt_produk = $db->fetchNextObject($q_produk)){ 
			@$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_produk->ID_PRODUCT."'");
			@$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'");
			@$stock				= $db->fob("STOCK",$tpref."products_stocks"," WHERE ID_PRODUCT='".$dt_produk->ID_PRODUCT."' AND ID_CLIENT='".$_SESSION['cidkey']."' "); 
			if(empty($stock)){ $stock = 0; }
		  ?>
		  <tr class="wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>' id="tr_<?php echo $dt_produk->ID_PRODUCT; ?>">
			<td class="align-top"><input type="checkbox" name="row_sel" class="row_sel" value="<?php echo $dt_produk->ID_PRODUCT; ?>"/></td>
			<td  class="align-top" style="width:60px">
                <div class="thumbnail">
                    <div class="thumbnail-inner" style="width:60px;overflow:hidden">
                <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                <a href="javascript:void()"
                   modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/produk.php?no=<?php echo $dt_produk->ID_PRODUCT; ?>","size":"modal-lg"' 
                   onclick="modal_ajax(this)">
                    <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' style="width:100%"/>
                </a>
                <?php }else{ ?>
                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' style="width:100%"/>
                <?php } ?>
                    </div>
                </div>
			</td>
			<td  class="align-top">
                <table width="100%" class="table table-bordered table-striped">
                    <tr>
                        <td width="13%"><b>Kode Item </b></td>
                        <td width="87%"><?php echo $dt_produk->CODE; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Stock</strong></td>
                        <td><?php echo $stock; ?> <?php echo $unit; ?></td>
                    </tr>
                    <tr>
                        <td><b>Nama</b></td>
                        <td><?php echo $dt_produk->NAME; ?></td>
                    </tr>
                    <tr>
                        <td><b>Harga</b></td>
                        <td><?php echo money("Rp.",$dt_produk->SALE_PRICE); ?></td>
                    </tr>
                    <?php if(!empty($dt_produk->DESCRIPTION)){?>
                    <tr>
                        <td><b>Deskripsi </b></td>
                        <td><?php echo printtext($dt_produk->DESCRIPTION,50); ?></td>
                    </tr>
                    <?php } ?>
                </table>
		   </td>
			<td  class="align-top text-center">
                <input type="checkbox" 
                       id="st_prod_<?php echo $dt_produk->ID_PRODUCT; ?>" 
                       class="status_view"
                       onclick="set_status('<?php echo $dt_produk->ID_PRODUCT; ?>')"
                        <?php if(!empty($st_status) && $st_status == "2"){?> checked <?php } ?> value="2"/>
				<div class="btn-group">
					<?php if(allow('edit') == "1"){?>
                    <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_produk->ID_PRODUCT; ?>" class="btn btn-sm btn-sempoa-4" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <?php } ?>
                    <a href="javascript:void()" class="btn btn-sm btn-sempoa-3" 
                       modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/produk.php?no=<?php echo $dt_produk->ID_PRODUCT; ?>","size":"modal-lg"' 
                       onclick="modal_ajax(this)">
                        <i class="fa fa-eye"></i>
                    </a>
                    <?php if(allow('delete') == 1){?>
                    <a href='javascript:void()' onclick="removal('<?php echo $dt_produk->ID_PRODUCT; ?>')" class="btn btn-sm btn-danger" title="Delete">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php } ?>
				</div>
                <script language="javascript">
					$("#st_prod_<?php echo $dt_produk->ID_PRODUCT; ?>").bootstrapSwitch({
						on: 'Aktif',
						off: 'Tidak Aktif',
						size: 'sm',
						onClass: 'primary',
						offClass: 'default'
					});
				</script>
			</td>
		</tr>
		<?php } 
	}
	
}
?>
