<?php defined('mainload') or die('Restricted Access'); ?>
<?php 
	if(!empty($msg)){
		switch ($msg){
			case "1":
				echo msg("Data Berhasil Disimpan","success");
			break;
			case "2":
				echo msg("Pengisian Form Belum Lengkap","error");
			break;
		}
	}
?>
<input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
 <input id="kategori_page" type="hidden"  value="<?php echo $ajax_dir; ?>/kategori.php" />
<div class="ibox float-e-margins">
    <div id="div_msg"></div>
    <div class="ibox-content">
        <div class="tabbable tabbable-bordered" style="margin:9px 9px 9px 9px">
            <ul class="nav nav-tabs">
                <li class="<?php echo @$class_proses; ?>"><a data-toggle="tab" href="#tb1_a">Pendaftaran Item</a></li>
                <li class="<?php echo @$class_report; ?>"><a data-toggle="tab" href="#tb1_b">Form Filter Pencarian</a></li>
                <!--<li class="<?php echo @$class_export; ?>"><a data-toggle="tab" href="#tb1_c">Export</a></li>-->
            </ul>
            <div class="tab-content">
                <div id="tb1_a" class="tab-pane <?php echo @$class_proses; ?>">
                	<br /><br />
					<?php include $call->inc($page_dir."/includes","form_proses.php"); ?>
                </div>
                <div id="tb1_b" class="tab-pane <?php echo @$class_report; ?>">
                	<br /><br />
					<?php include $call->inc($page_dir."/includes","form_report.php"); ?>
                </div>
                <!--<div id="tb1_c" class="tab-pane <?php //echo @$class_export; ?>">
                    <div class="ibox-title">
                        <h4>Export Data Produk</h4>
                    </div>
                    <p><?php //include $call->inc($page_dir."/includes","form_export.php"); ?></p>
                </div>-->
            </div>
           
        </div>
    </div>
    <div class="clearfix"></div>
</div>


<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Daftar Item</h5>
        <?php if(allow('delete') == 1){?>
        <div class="ibox-tools">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-wrench"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li>
                <a href="javascript:void()" id="select_rows_2">
                <i class="fa fa-check-square-o"></i>
                    Pilih Semua
                </a>
                <li>
                    <a href="javascript:void()" id="delete_picked">
                    <i class="fa fa-trash"></i>
                        Hapus Yang Di Pilih
                    </a>
                </li>
            </ul>
        </div>
        <?php } ?> 
    </div>
    <div class="ibox-content">
        <a name="report"></a>
        <table width="100%" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="20" class="table_checkbox" style="width:13px">
                        <input type="checkbox" id="select_rows" class="select_rows"/>
                    </th>
                    <th width="80">Gambar</th>
                  <th width="744">Specifikasi</th>
                    <th width="167" style="text-align:center">Actions</th>
                </tr>
            </thead>
            <tbody>
                  <?php while($dt_produk = $db->fetchNextObject($q_produk)){ 
                    @$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_produk->ID_PRODUCT."'");
                    @$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_produk->ID_PRODUCT_UNIT."'");
                    @$stock				= $db->fob("STOCK",$tpref."products_stocks"," WHERE ID_PRODUCT='".$dt_produk->ID_PRODUCT."' AND ID_CLIENT='".$_SESSION['cidkey']."' "); 
                    if(empty($stock)){ $stock = 0; }
                    $st_status			= $dt_produk->ID_STATUS;
					
					@$kategori	= $db->fob("NAME",$tpref."products_categories"," 
						WHERE ID_PRODUCT_CATEGORY='".$dt_produk->ID_PRODUCT_CATEGORY."'");

                  ?>
                  <tr class="wrdLatest" data-info='<?php echo $dt_produk->ID_PRODUCT; ?>' id="tr_<?php echo $dt_produk->ID_PRODUCT; ?>">
                    <td class="align-top"><input type="checkbox" name="row_sel" class="row_sel" value="<?php echo $dt_produk->ID_PRODUCT; ?>"/></td>
                    <td  class="align-top" style="width:60px">
                        <a href="javascript:void()"
                       	   modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/produk.php?no=<?php echo $dt_produk->ID_PRODUCT; ?>","size":"modal-lg"' 
                           onclick="modal_ajax(this)">                    	
                           <div class="thumbnail sm">
                        <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>

                            <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' style="width:100%"/>
                        <?php }else{ ?>
                            <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' style="width:100%"/>
                        <?php } ?>
                        </div>
                        </a>
                    </td>
                    <td  class="align-top">
                    <table width="100%" class="table table-bordered table-striped">
                      <tr>
                        <td width="13%"><b>Kategori </b></td>
                        <td width="87%"><?php echo $kategori; ?></td>
                      </tr>
                      <tr>
                        <td width="13%"><b>Kode</b></td>
                        <td width="87%"><?php echo $dt_produk->CODE; ?></td>
                      </tr>
                      <tr>
                        <td><strong>Stok</strong></td>
                        <td><?php echo $stock; ?> <?php echo $unit; ?></td>
                      </tr>
                      <tr>
                        <td><b>Nama</b></td>
                        <td><?php echo $dt_produk->NAME; ?></td>
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
                    </td>
                </tr>
                <?php } ?>    
            </tbody>
        </table>
        <div id="lastPostsLoader"></div>
        <div class="ibox-title" style="text-align:center">
            <?php if($num_produk > 10){?>
                <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
            <?php } ?>
            <br clear="all" />
        </div>       
    </div>
</div>
