<?php defined('mainload') or die('Restricted Access'); ?>
<form class="formular form_multi" id="formID" action="" method="POST" enctype="multipart/form-data">
<div class='col-md-7'>
<table width="100%" class="table table-striped table-bordered table-condensed table-hover" id="table_list">
    <thead>
        <tr>
          <td colspan="5">
              <div class='col-md-4'>
                <label class='req'>Tanggal Jual</label>
                    <?php $tgl_jual_multi = date("d-m-Y"); ?>
                    <span class="input-append date" id="dp2" data-date="<?php echo $tgl_jual_multi; ?>" data-date-format="dd-mm-yyyy">
                        <input size="16" value="<?php echo $tgl_jual_multi; ?>" readonly="" type="text" id="tgl_jual_multi" name="tgl_jual_multi" class="form-control validate[required] text-input">
                        <span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
                    </span>                        
              </div>
              <div class='col-md-4'>
                <label class='req'>Total Jumlah Penjualan</label>
                <input type="text" id="jumlah_multi" name="jumlah_multi" value="<?php echo @$jumlah_multi;?>"  onkeyup="calculate_multi('insert','jumlah_multi','multi')" onblur="calculate_multi('insert','jumlah_multi','multi')" class="col-md-12 validate[required] text-input" readonly/>
              </div>
              <div class='col-md-4'>
                <label class='req'>Total Penjualan</label>
                <span class="input-prepend input-append">
                <span class="add-on">Rp.</span>
                <input type="text" id="harga_label_multi" value="<?php if(!empty($harga_multi)){ echo money("",@$harga_multi); } ?>"  class="form-control validate[required] text-input" readonly style="padding:7px"/>
                </span>
                <input type="hidden" id="harga_multi" name="harga_multi" value="<?php echo @$harga_multi;?>">
                <input type="hidden" id="new_harga_multi" name="new_harga_multi" value="<?php echo @$new_harga_multi;?>">
              </div>
          </td>
        </tr>
          <tr class='editable'>
            <th colspan="5">
			<?php
                if($_SESSION['ulevelkey'] == 5){
                    $id_sale = $_SESSION['uidkey'];	
                    $nm_sale = $db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$_SESSION['uidkey']."'");	
                }
            ?>
            </th>
        </tr>
        <tr>
            <th colspan="5">
            <?php if(allow('insert') == 1){?>
                <input type="text" size="16" id="searching" placeholder='Pencarian Produk' style="width:30%;">
                <select style="width:20%;" id="filter">
                    <option value="code">Kode Item</option>
                    <option value="nama">Nama Item</option>
                    <option value="deskripsi">Deskripsi</option>
                </select>
                <select name="item_type" id="item_type" style="width:30%;">
                    <?php
                    $query_type = $db->query("SELECT * FROM ".$tpref."products_types ORDER BY ID_PRODUCT_TYPE ASC");
                    while($data_type = $db->fetchNextObject($query_type)){
                    ?>
                        <option value='<?php echo $data_type->ID_PRODUCT_TYPE ?>' <?php if(!empty($item_type) && $item_type == $data_type->ID_PRODUCT_TYPE){?> selected<?php } ?>><?php echo $data_type->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            	<button type="button" class='btn btn-beoro-3' id="btn_pick" style="margin:-10px 0 0 0">Pilih</button> 
            <?php } ?>
            </th>
        </tr>
        <tr>
            <th width="16%">&nbsp;</th>
            <th width="22%" style='text-align:right'>Harga Jual</th>
            <th width="14%" style='text-align:center'>Jumlah</th>
            <th width="14%" style='text-align:right'>Total</th>
            <th width="14%" style='text-align:center'>ACTION</th>
        </tr>
    </thead>
    <tbody>
      <tr style="display:none;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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
				$harga_single_label	= money("Rp.",$harga[$f]);
				$total_single_label = money("Rp.",$total[$f]);
        ?>
            <tr class='data_<?php echo $id_prod[$f]; ?>'>
              <td colspan='5'><b style='color:#CC0000'><?php echo $code; ?></b> - <?php echo $product_name; ?></td>
            </tr>
            <tr class='data_<?php echo $id_prod[$f]; ?>'>
                <td style='text-align:center;'>
                    <a href='<?php echo $photo_big; ?>' class='fancybox'>
                        <img src='<?php echo $photo; ?>' class='photo' style='width:90%; margin-right:5px'>
                    </a>
                </td>
                <td style='text-align:right'><?php echo $harga_single_label; ?></td>
                <td style='text-align:center'><?php echo $jumlah[$f]; ?></td>
                <td style='text-align:right'><?php echo $total_single_label; ?></td>
                <td style='text-align:center'>
                    <a href='javascript:void()'  onclick="cancel_pick('<?php echo $id_product[$f]; ?>')" class='btn btn-mini'>
                    <i class='icon-trash'></i>
                    </a>
        <input type='hidden' id='product_<?php echo $id_product[$f]; ?>'  	name='id_product[]'  value='<?php echo $id_product[$f]; ?>'>
        <input type='hidden' id='new_jumlah_<?php echo $id_product[$f]; ?>' name='jumlah[]' 	 value='<?php echo $jumlah[$f]; ?>'>
        <input type='hidden' id='new_price_<?php echo $id_product[$f]; ?>'  name='harga[]'  	 value='<?php echo $harga[$f]; ?>'>
        <input type='hidden' id='new_diskon_<?php echo $id_product[$f]; ?>' name='diskon[]'  	 value='<?php echo $diskon[$f]; ?>'>
        <input type='hidden' id='stock_<?php echo $id_product[$f]; ?>' 	 	name='stock[]'  	 value='<?php echo $stock[$f]; ?>'>
        <input type='hidden' id='new_total_<?php echo $id_product[$f]; ?>'  name='total[]'  	 value='<?php echo $total[$f]; ?>'>
                </td>
            </tr>
        <?php	
            }
        }
        ?>
	</tbody>
</table>
</div>
<div class='col-md-5'>
<div id="dt"></div>
<?php if($_SESSION['ulevelkey'] == 5){ $readonly["multi"] = "readonly"; }else{ $readonly["multi"] = ""; } ?>      
  <div class="col-md-12">
	<label class='req'>No Faktur</label>
	<input type="text" id="faktur_multi" name="faktur_multi" value="<?php echo @$faktur_multi; ?>" style='text-transform:uppercase' class="mousetrap form-control validate[required] text-input"/>
  </div>
  <div class="col-md-5">
	<label>Nama Sales</label>
	<span class="input-prepend input-append">
	<span class="add-on" style="padding:4px"><i class="icsw16-admin-user"></i></span>
	<?php if($_SESSION['ulevelkey'] == 5){?>
	<input type="hidden" id="id_sales_multi" name="id_sales_multi" value="<?php echo $_SESSION['uidkey']; ?>" />
	<input type="text" id="nm_sales_multi" value="<?php echo $nm_sale; ?>" <?php echo $readonly["multi"]; ?> class="mousetrap form-control validate[required] text-input"/>
	<?php }else{ ?>
	<select id="id_sales_multi" name="id_sales_multi" class="mousetrap form-control">
		<option value=''>--SALES--</option>
		<?php 
		$q_marketing = $db->query("SELECT * FROM system_users_client WHERE ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY USER_NAME ASC");
		while($dt_marketing = $db->fetchNextObject($q_marketing)){
		?>
		<option value='<?php echo $dt_marketing->ID_USER; ?>' <?php if((!empty($id_sales_multi) && $id_sales_multi == $dt_marketing->ID_USER) || ($_SESSION['uidkey'] == $dt_marketing->ID_USER)){?>selected<?php } ?>>
		<?php echo $dt_marketing->USER_NAME; ?>
		</option>
		<?php } ?>
	</select>
	<?php } ?>
	</span>
  </div>
  <div class="col-md-5">
	<label>Nama Pelanggan</label>
	<span class="input-prepend input-append">
	<span class="add-on" style="padding:4px"><i class="icsw16-users-2"></i></span>
	<select id="id_customer_multi" name="id_customer_multi" class="mousetrap form-control">
		<option value=''>--KONSUMEN--</option>
			<?php 
            $q_customer = $db->query("SELECT * FROM ".$tpref."customers WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ORDER BY CUSTOMER_NAME ASC");
            while($dt_customer = $db->fetchNextObject($q_customer)){
            ?>
            <option value='<?php echo $dt_customer->ID_CUSTOMER; ?>' <?php if(!empty($id_customer_multi) && $id_customer_multi == $dt_customer->ID_CUSTOMER){?>selected<?php } ?>>
            <?php echo $dt_customer->ID_CUSTOMER; ?> - <?php echo $dt_customer->CUSTOMER_NAME; ?>
            </option>
            <?php } ?>
	</select>
	</span>
  </div>
  <p class='cl'></p>
  <div class="col-md-5">
    <label class='req'>Jumlah Bayar</label>
    <span class="input-prepend input-append">
    <span class="add-on">Rp.</span>
    <input type='text' id='downpay_multi' name='downpay_multi' value='<?php echo @$downpay_multi; ?>' class='span8 mousetrap validate[required] text-input' onkeyup="calculate_multi('insert','downpay_multi','multi')" onblur="calculate_multi('insert','downpay_multi','multi')"/>
     <span class="add-on">,00</span>
    </span>
  </div>
  <div class="col-md-4">
    <label>Sisa</label>
    <span class="input-prepend input-append">
    <span class="add-on">Rp.</span>
    <input type='text' id='kredit_label_multi' value='<?php if(!empty($kredit_multi)){echo money("",@$kredit_multi); }?>' class='mousetrap col-md-12' readonly/>
    <input type='hidden' id='kredit_multi' name="kredit_multi" value='<?php echo @$kredit_multi; ?>'/>
    </span>
  </div>
  <p class='cl'></p>
  <div class="col-md-12">
	<label>Diskon</label>
	<span class="input-prepend input-append">
	<input type="text" id="diskon_multi" name="diskon_multi" value="" onkeyup="calculate_multi('insert','diskon_multi','multi')" onblur="calculate_multi('insert','diskon_multi','multi')" class="mousetrap form-control"/> 
	 <span class="add-on">%</span>
	</span>
  </div>
  <p class='cl'></p>
  <div class="col-md-12">
	<label class='req'>Status Bayar</label>
	<select id="status_lunas_multi_label" onchange="show_kredit('multi')" class="mousetrap validate[required] text-input" disabled="disabled">
	  <option value='3' <?php if(empty($status_multi) || (!empty($status_multi) && $status_multi == 3)){?> selected <?php }?>>Piutang</option>
      <option value='2' <?php if(!empty($status_multi) && $status_multi == 2){?>selected<?php }?> >Lunas</option>
	</select>
    <input type='hidden' id='status_lunas_multi' name='status_multi' value='<?php if(empty($status_multi)){ $status_multi = 1; }else{ echo @$status_multi;} ?>' />
  </div>
  <?php if(empty($status_multi) || !empty($status_multi) && $status_multi == 2){ $display_lunas = "style='display:none'"; }else{ $display_lunas = ""; } ?>
  
  <p class='div_kredit_multi' <?php echo $display_lunas ?>>
  <div class="col-md-12">
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
        <div class='col-md-12'>
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
  </p>
  <div class="col-md-12">
	<label>No PO</label>
	<input type='text' id='nopo_multi' name='nopo_multi' value='<?php echo @$nopo_multi; ?>' class='mousetrap form-control' style='text-transform:uppercase'/>
  </div>
  
  <div class="col-md-12">
  	<label>Keterangan</label>
    <textarea name="keterangan_multi" id="keterangan_multi" class='mousetrap form-control' ><?php echo @$keterangan_multi; ?></textarea>
  </div>
  <div class="col-md-12">
	<label>Lokasi Penjualan</label>
		<a href='javascript:void()' id='location_open_multi' onclick="open_location('province','multi')">+ Tambah</a>
		<a href='javascript:void()' id='location_close_multi' onclick="close_location('multi')" style="display:none">- Tutup</a>
		<br />
		<select name="propinsi_multi" id="propinsi_multi" onchange="open_location('city','multi')" <?php if(empty($propinsi)){?> style="display:none;" <?php } ?>>
			<option value=''>--PILIH PROPINSI--</option>
			<?php
			$query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
			while($data_propinsi = $db->fetchNextObject($query_propinsi)){
			?>
				<option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
				</option>
		<?php } ?>
		</select>
		<span id="div_kota_multi" style="pading-left:3px">
			<?php if(!empty($kota)){ include $call->inc($ajax_dir,"city.php");} ?>
		</span>
		<span id="div_kecamatan_multi" style="pading-left:3px">
			<?php if(!empty($kecamatan)){ include $call->inc($ajax_dir,"district.php");} ?>
		</span>
		<span id="div_kelurahan_multi" style="pading-left:3px">
			<?php if(!empty($kelurahan)){ include $call->inc($ajax_dir,"subdistrict.php");} ?>
		</span>
  </div>
  <?php if(allow('insert') == 1){?>
  <div class="form-group">
	<label>&nbsp;</label>
	<button type="button" class="btn btn-sempoa-1 col-md-6" style='margin:0' id="insert_multi_button">
		<i class="icsw16-box-incoming icsw16-white" style='margin-top:-2px'></i>Simpan Data
	</button>
  </div>
  <?php } ?>
</div>
</form>
