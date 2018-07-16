<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$no 		= isset($_REQUEST['no']) ? $_REQUEST['no'] : "";
	$q_produk 	= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$no."'");
	$dt_produk	= $db->fetchNextObject($q_produk);
	@$satuan 	= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'");
	@$kategori	= $db->fob("NAME",$tpref."products_categories"," 
							WHERE ID_PRODUCT_CATEGORY='".$dt_produk->ID_PRODUCT_CATEGORY."'");
							
	if($dt_produk->ID_STATUS == "3"){ $state_label = "<label class='label label-success'>Ditampilkan</label>"; }
	else							{ $state_label = "<label class='label label-danger'>Tidak Ditampilkan</label>"; }
?>
<div class="ibox float-e-margins">
	<h3 class="ibox-title"><?php echo @$dt_produk->CODE; ?> : <?php echo $dt_produk->NAME; ?></h3>
    <div class="ibox-content">
		<div class="col-md-6">
			<?php
            $q_photos 	= $db->query("SELECT * FROM ".$tpref."products_photos WHERE ID_PRODUCT='".$no."'");
            while($dt_photos = $db->fetchNextObject($q_photos)){
                if(!empty($dt_photos->PHOTOS) || is_file($basepath."/files/images/products/".$id_client."/".$dt_photos->PHOTOS)){ ?>
                <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/<?php echo $dt_photos->PHOTOS; ?>' class='photo' style='float:left; width:96%'/>
            <?php }
            }
            ?>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Status Tampil</label><br />
                <?php echo $state_label; ?>
            </div>
        	<?php if(!empty($dt_produk->ID_PRODUCT_CATEGORY)){?>
            <div class="form-group">
                <label>Kategori Produk</label><br />
                <?php echo @$kategori; ?>
            </div>
        	<?php } ?>
            <?php if(!empty($dt_produk->NAME)){?>
                <div class="form-group">
                    <label>Nama Produk</label><br />
                    <?php echo @$dt_produk->NAME; ?>
                </div>
            <?php } ?>
            
            <?php if(!empty($dt_produk->DESCRIPTION)){?>
                <div class="form-group">
                    <label>Deskripsi Produk</label><br />
                    <?php echo @$dt_produk->DESCRIPTION; ?>
                </div>
            <?php } ?>
            
            <?php if(!empty($dt_produk->SALE_PRICE)){?>
                <div class="form-group">
                    <label>Harga Jual</label><br />
                    <?php echo money("Rp.",$dt_produk->SALE_PRICE); ?>
					<?php if(!empty($satuan)){?>
                            / <?php  echo @$satuan; ?>
                    <?php } ?>
                    
                </div>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
     </div>   
</div>
<?php } ?>
<div class="clearfix"></div>