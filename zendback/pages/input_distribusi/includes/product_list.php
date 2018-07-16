<?php 
if(!empty($_SESSION['cidkey']) && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");

	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$id_product_distribution 	= isset($_REQUEST['id_product_distribution']) 	? $_REQUEST['id_product_distribution'] 	: "";
	$id_product_stock 			= isset($_REQUEST['id_stock']) 					? $_REQUEST['id_stock'] 	: "";
	$str_shipping	= 
	"SELECT 
		*
	FROM 
		".$tpref."products_stocks_history a,
		".$tpref."products b
	WHERE 
		a.ID_PRODUCT = b.ID_PRODUCT AND
		a.ID_PRODUCT_STOCK = '".$id_product_stock."'";
	$q_produk 	= $db->query($str_shipping);
	//echo $str_shipping;
?>
<table width="100%" class="popup table table-striped">
<thead>
        <tr>
            <th width="18%">&nbsp;</th>
            <th width="42%"><b>Kode Item</b></th>
            <th width="29%"><span id='load'></span></th>
            <th width="11%">Actions</th>
  </tr>
  </thead>
    <tbody>
          <?php while($dt_produk = $db->fetchNextObject($q_produk)){ 

			$id_product			= $dt_produk->ID_PRODUCT; 
			@$tgl_kirim			= $dtime->date2indodate($tgl_kirim[0]);
			@$jumlah			= $dt_produk->STOCK;
			@$keterangan		= $dt_produk->DESCRIPTION;
            
			@$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
            $unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'"); 
            @$ori_stock			= $db->fob("STOCK",$tpref."products_stocks"," WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
            if(empty($ori_stock)){
                $ori_stock 		= 0;	
                $ori_stock_real	= "";
            }else{
                $ori_stock_real		= $ori_stock+@$jumlah;
            }
          ?>
          <tr id="tr_<?php echo $id_product; ?>">
            <td>
                <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                    <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="photo" style="width:98%"/>
                <?php }else{ ?>
                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class='photo' style="width:98%"/>
                <?php } ?>
            </td>
            <td>
				<span class='code'><?php echo $dt_produk->CODE; ?></span>
                <br />
            	<?php echo $dt_produk->NAME; ?>
            </td>
            <td style="vertical-align:top">
            Jumlah : <br />
            <input type="text" id="jumlah" value='<?php echo @$jumlah; ?>' />
            <br />
            Keterangan : <br />
            <textarea id='keterangan' ><?php echo @$keterangan; ?></textarea>
            </td>
            <td style="text-align:center">
			<?php if(allow('edit') == 1 && $direction == "edit"){?> 
                <div class="btn-group">
                    <a href="javascript:void()" class="btn btn-mini" title="Edit" id="btn_direction_edit"  onclick="edit_stock()"  >
                        <i class="icon-pencil" ></i>
                    </a>
                </div>
            <?php } ?>
            
            
            </td>
        </tr>
        <?php } ?>    
    </tbody>
</table>
<?php }else{
	defined('mainload') or die('Restricted Access');
}
?>