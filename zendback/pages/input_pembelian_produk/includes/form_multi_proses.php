<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular form_multi" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class='col-md-7'>
    <table width="100%" class="table table-striped table-bordered table-condensed table-hover" id="table_list">
        <thead>
          <tr class='editable'>
            <th colspan="6">
                <?php if(allow('insert') == 1){?>
                    <div style='float:right; margin:3px 0 5px 0;'>
                    <a href='<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>' class='btn add_button fancybox fancybox.ajax' style=' <?php if(!empty($direction) && $direction == "show"){?>display:none<?php } ?> '>
                        <i class='icon-plus'></i>Tambah Produk
                    </a>
                  </div>
                <?php } ?>
            </th>
            </tr>
            <tr>
                <th width="20%">&nbsp;</th>
                <th width="19%" style='text-align:right'>Harga Beli</th>
                <th width="10%" style='text-align:center'>Jumlah</th>
                <th width="19%" style='text-align:right'>Total Harga</th>
                <th width="19%" style='text-align:right'>Harga Jual</th>
                <th width="13%" style='text-align:center'>ACTION</th>
            </tr>
        </thead>
        <tbody>
            <tr style='display:none'>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <?php
			if(!empty($id_product)){
				$f = -1;
				$jml_prod = count($id_product)-1;
				while($f < $jml_prod){
					$f++;
					$q_product			= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$id_product[$f]."'");
					$dt_product			= $db->fetchNextObject($q_product);
					$code 				= $dt_product->CODE;
					$product_name		= $dt_product->NAME;
					$id_product_unit	= $dt_product->ID_PRODUCT_UNIT;
					@$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_product->ID_PRODUCT_UNIT."'"); 
					$data_photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product[$f]."'");
					$photo_big		= "javascript:void()";
					if($data_photo != ""){
						$photo 		= "files/images/products/".$id_client."/thumbnails/".$data_photo;	
						$photo_big 	= "files/images/products/".$id_client."/".$data_photo;	
					}else{
						$photo = "files/images/noimage-m.jpg";	
					}
					$harga_beli_label	= money("Rp.",$harga_beli[$f]);
					$harga_jual_label	= money("Rp.",$harga_jual[$f]);
					$total_beli			= $harga_beli[$f]*$jumlah[$f];
					$total_beli_label 	= money("Rp.",$total_beli);
			?>
                <tr class='data_<?php echo $id_prod[$f]; ?>'>
                  <td colspan='6'><b style='color:#CC0000'><?php echo $code; ?></b> - <?php echo $product_name; ?></td>
                </tr>
                <tr class='data_<?php echo $id_prod[$f]; ?>'>
                    <td style='text-align:center;'>
                        <a href='<?php echo $photo_big; ?>' class='fancybox'>
                            <img src='<?php echo $photo; ?>' class='photo' style='width:90%; margin-right:5px'>
                        </a>
                    </td>
                    <td style='text-align:right'><?php echo $harga_beli_label; ?></td>
                    <td style='text-align:center'><?php echo $jumlah[$f]; ?></td>
                    <td style='text-align:right'><?php echo $total_beli_label; ?></td>
                    <td style='text-align:right'><?php echo $harga_jual_label; ?></td>
                    <td style='text-align:center'>
                        <a href='javascript:void()' onclick="cancel_pick('<?php echo $id_product[$f]; ?>')" class='btn btn-mini'>
                        <i class='icon-trash'></i>
                        </a>
        <input type='hidden' id='product_<?php echo $id_product[$f]; ?>' 	name='id_product[]' value='<?php echo $id_product[$f]; ?>'>
        <input type='hidden' id='new_beli_<?php echo $id_product[$f]; ?>'  	name='harga_beli[]' value='<?php echo $harga_beli[$f]; ?>'>
        <input type='hidden' id='new_jual_<?php echo $id_product[$f]; ?>'	name='harga_jual[]' value='<?php echo $harga_jual[$f]; ?>'>
        <input type='hidden' id='new_jumlah_<?php echo $id_product[$f]; ?>' name='jumlah[]' 	value='<?php echo $jumlah[$f]; ?>'>
        <input type='hidden' id='stock_<?php echo $id_product[$f]; ?>' 	 	name='stock[]'  	value='<?php echo @$stock[$f]; ?>'>
        <input type='hidden' id='new_total_<?php echo $id_product[$f]; ?>'  name='total[]'  	value='<?php echo $total_beli; ?>'>
                    </td>
                </tr>
            <?php	
				}
			}
			?>
        </tbody>
    </table>
    <div class='alert alert-info' style="margin:0" id="first_tutor">
        <b>Informasi</b> : Klik Tombol <a href='<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>' class='btn btn-mini add_button fancybox fancybox.ajax'>
                <i class='icon-plus'></i>Tambah Produk
            </a> untuk memilih Item yang akan di daftarkan sebagai data pembelian  
    </div>
</div>
<div class='col-md-5'>
    <div class="form-group">
        <label class='req'>Total Jumlah Pembelian</label>
        <input type="text" id="jumlah_multi" name="jumlah_multi" value="<?php echo $jumlah_multi; ?>"  onkeyup="calculate_multi('insert','jumlah_multi','multi')" onblur="calculate_multi('insert','jumlah_multi')" class="mousetrap form-control validate[required] text-input" readonly/>
    </div>
    <div class="form-group">
        <label class='req'>Total Pembelian </label>
        <span class="input-prepend input-append">
            <span class="add-on">Rp.</span>
                <input type="text" id="total_bayar_label_multi" value="<?php if(!empty($total_bayar_multi)){ echo money("",$total_bayar_multi); } ?>" readonly class="mousetrap form-control validate[required] text-input">
                <input type="hidden" id="total_bayar_multi" name="total_bayar_multi" value="<?php echo @$total_bayar_multi; ?>">
            </span>
        </span>
    </div>
  <div class="form-group">
    <label class='req'>Tanggal Beli</label>
        <?php $tgl_beli_multi = date("d-m-Y"); ?>
        <span class="input-append date" id="dp2" data-date="<?php echo $tgl_beli_multi; ?>" data-date-format="dd-mm-yyyy">
            <input size="16" value="<?php echo $tgl_beli_multi; ?>" readonly="" type="text" name="tgl_beli_multi" class="mousetrap form-control validate[required] text-input">
            <span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
        </span>                        
  </div>
  <div class="form-group">
    <label class='req'>No Faktur</label>
    <input type="text" name="no_faktur_multi" value="<?php echo @$no_faktur_multi; ?>" style='text-transform:uppercase' class="mousetrap form-control validate[required] text-input"/>
  </div>
  <div class="form-group">
    <label>Supplier</label>
    <div class="category" id='id_cat_multi'>
        <div class="col-md-7">
            <select id='id_partner_multi' name="id_partner_multi" style='margin:0' class="mousetrap col-md-12">
            <option value=''>-- PILIH SUPPLIER --</option>
            <?php 
                $q_partner = $db->query("SELECT * FROM ".$tpref."partners WHERE ID_CLIENT='".$_SESSION['cidkey']."'");  
                while($dt_partner = $db->fetchNextObject($q_partner)){
            ?>
                <option value='<?php echo $dt_partner->ID_PARTNER; ?>' <?php if(!empty($id_partner_multi) && $id_partner_multi == $dt_partner->ID_PARTNER){ ?>selected<?php } ?>>
                    <?php echo $dt_partner->PARTNER_NAME; ?>
                </option>
            <?php 	
                }
            ?>
            </select>
        </div>
        <div class="col-md-5">
            <a href="javascript:void()" class='btn new_cat'>
                <i class="icon-plus"></i>Tambah Supplier
            </a>
        </div>
    </div>
    <div class="category" style="display:none"  id="div_category_multi">
        <input type='text' id='category_multi' style='text-transform:capitalize; margin:3px 3px 0 0;' placeholder='Nama Supplier'>
        <a href='javascript:void()' class='btn cancel_cat' data-info='multi' style='margin:0 3px 0 0'>
            <i class='icon-remove'></i> Batal
        </a>
        <a href='javascript:void()' class='btn save_cat' data-info='multi'>
            <i class='icon-ok'></i> Simpan
        </a>
    </div> 
  </div>
  <div class="form-group">
    <label class='req'>Jumlah Bayar</label>
    <span class="input-prepend input-append">
    <span class="add-on">Rp.</span>
    <input type='text' id='downpay_multi' name='downpay_multi' value='<?php echo @$downpay_multi; ?>' class='span8 mousetrap validate[required] text-input' onkeyup="calculate_multi('insert','downpay_multi')" onblur="calculate_multi('insert','downpay_multi','multi')" />
     <span class="add-on">,00</span>
    </span>
  </div>
  <div class="form-group">
    <label>Sisa</label>
    <span class="input-prepend input-append">
    <span class="add-on">Rp.</span>
    <input type='text' id='kredit_label_multi' value='<?php if(!empty($kredit_multi)){ echo money("",@$kredit_multi); } ?>' class='mousetrap form-control' readonly/>
    <input type='hidden' id='kredit_multi' name="kredit_multi" value='<?php echo @$kredit_multi; ?>'/>
    </span>
  </div>
  <div class="form-group">
    <label class='req'>Status Bayar</label>
    <select id="status_lunas_multi_label" onchange="show_kredit('multi')" class="mousetrap validate[required] text-input" disabled="disabled">
      <option value='1' <?php if(empty($status_multi) || (!empty($status_multi) && $status_multi == 1)){?>selected<?php }?> >Hutang</option>
      <option value='2' <?php if(!empty($status_multi) && $status_multi == 2){?>selected<?php }?>>Lunas</option>
    </select>
    <input type='hidden' id='status_lunas_multi' name='status_multi' value='<?php if(empty($status_multi)){ $status_multi = 1; }else{ echo @$status_multi;} ?>' />
  </div>
  <?php if(empty($status_multi) || !empty($status_multi) && $status_multi == 2){ $display_lunas = "style='display:none'"; }else{ $display_lunas = ""; } ?>
 <div class='div_kredit_multi' <?php echo $display_lunas ?>>
    <div class="form-group">
        <label>No PO</label>
        <input type='text' id='nopo_multi' name='nopo_multi' value='<?php echo @$nopo_multi; ?>' class='mousetrap form-control' style='text-transform:uppercase'/>
    </div>
    <div class="form-group">
        <label>Termin Pembayaran</label>
        <input value="<?php echo @$termin_multi; ?>" type="text" id="termin_multi" name="termin_multi" class="mousetrap form-control">
    </div>
    <span id="div_termin_multi" <?php if(empty($termin_multi)){?> style="display:none" <?php } ?> class='form-control'>
    <?php 
	if(!empty($termin_multi)){ 
		$b = 0;
		foreach($tgl_tempo_multi as &$tgl_tempo_multis){
			$b++;
	?>
        <div class='form-group' style='padding-left:5px'>
        <label>Tanggal Jatuh Tempo <?php echo $b; ?></label>
            <span class='input-append date ttempo' id='dp_<?php echo $b; ?>' data-date='' data-date-format='dd-mm-yyyy'>
                <input size='16' value='<?php echo @$tgl_tempo_multis; ?>' readonly='' type='text' name='tgl_tempo_multi[]' id='tgl_tempo_multi_<?php echo $b; ?>'class='mousetrap form-control validate[required] text-input'>
                <span class='add-on'><i class='icsw16-day-calendar'></i></i></span>
            </span>                  
        </div>
    <?php 
		}
	} 
	?>
    </span>
  </div>
  <div class="form-group">
  	<label>Keterangan</label>
    <textarea name="keterangan_multi" id="keterangan_multi" class='mousetrap form-control' ><?php echo @$keterangan_multi; ?></textarea>
  </div>
<?php if(allow('insert') == 1){?>
<div class="form-group">
    <label>&nbsp;</label>
    <button type="submit" class="btn btn-sempoa-1 col-md-6" style='margin:0' name="direction" id="insert_multi_button" value="insert_multi">
        <i class="icsw16-box-incoming icsw16-white" style='margin-top:-2px'></i>Simpan Data
    </button>
</div>  
<?php } ?>
<input type="hidden" id="id_collective" value="root" />
</div>
              
          
</form>
