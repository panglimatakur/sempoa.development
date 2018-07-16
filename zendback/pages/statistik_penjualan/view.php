<?php defined('mainload') or die('Restricted Access'); ?>
<!-- main content -->
<div class="row-fluid" style=" <?php echo @$display; ?> background:#FFF" id='div_form_penjualan'>
    <div class="ibox-title">
        <h4>Form Seleksi Pencarian Data</h4>
    </div>
                    
    <div style='padding:8px; margin:2px 5px 2px 5px;'>
        <form method="post" action="" >
            <div class="col-md-6">
                <div class='form-group'>
                    <label>Periode</label>
                    <select name="periode" id="periode" class='col-md-6'>
                        <option value="">--PERIODE--</option>
                        <option value="harian" <?php if(!empty($periode) && $periode=="harian"){?>selected<?php } ?>>HARIAN</option>
                        <option value="bulanan" <?php if(!empty($periode) && $periode=="bulanan"){?>selected<?php } ?>>BULANAN</option>
                        <option value="tahunan" <?php if(!empty($periode) && $periode=="tahunan"){?>selected<?php } ?>>TAHUNAN</option>
                    </select>
                    <span id="div_periode"><?php if(!empty($periode)){ include $call->inc($ajax_dir,"data.php"); } ?></span>
                </div>
                <div class="form-group">
                    <label>Tipe Produk</label>
                    <select name="id_type_report" id="id_type_report" class="form-control mousetrap">
                        <option value=''>-- TIPE  PRODUK --</option>
                        <?php
                        $query_type = $db->query("SELECT * FROM ".$tpref."products_types ORDER BY ID_PRODUCT_TYPE ASC");
                        while($data_type = $db->fetchNextObject($query_type)){
                        ?>
                            <option value='<?php echo $data_type->ID_PRODUCT_TYPE ?>' <?php if(!empty($id_type_report) && $id_type_report == $data_type->ID_PRODUCT_TYPE){?> selected<?php } ?>><?php echo $data_type->NAME; ?>
                            </option>
                    <?php } ?>
                    </select>
                </div>
                <span id="div_kategori_report">
                    <?php if(!empty($id_type_report)){ include $call->inc($ajax_dir,"data.php"); } ?>
                </span>
                <div class="form-group">
                  <label>Code</label>
                  <input type="text" name="code" id="code" value="<?php echo @$code; ?>"  class="form-control mousetrap"/>
                </div>
                <div class="form-group">
                    <label>Lokasi Penjualan</label>
                    <a href='javascript:void()' id='location_open' onclick="open_location('province')">+ Tambah</a>
                    <a href='javascript:void()' id='location_close' onclick="close_location()" style="display:none">- Tutup</a>
                    <br />
                    <select name="propinsi" id="propinsi" onchange="open_location('city')" <?php if(empty($propinsi)){?> style="display:none;" <?php } ?>>
                        <option value=''>--PILIH PROPINSI--</option>
                        <?php
                        $query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
                        while($data_propinsi = $db->fetchNextObject($query_propinsi)){
                        ?>
                            <option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
                            </option>
                    <?php } ?>
                    </select>
                    <span id="div_kota" style="pading-left:3px">
                        <?php if(!empty($kota)){ include $call->inc($ajax_dir,"city.php");} ?>
                    </span>
                    <span id="div_kecamatan" style="pading-left:3px">
                        <?php if(!empty($kecamatan)){ include $call->inc($ajax_dir,"district.php");} ?>
                    </span>
                    <span id="div_kelurahan" style="pading-left:3px">
                        <?php if(!empty($kelurahan)){ include $call->inc($ajax_dir,"subdistrict.php");} ?>
                    </span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Harga</label>
                    <input type="text" name="harga" id="harga" value="<?php echo @$harga; ?>" class="form-control mousetrap" onkeyup="numeric(this)" onblur="numeric(this)"/>
                </div>
                <div class="form-group">
                    <label>Diskon</label>
                    <input type="text" name="diskon" id="diskon" value="<?php echo @$diskon; ?>" class="form-control mousetrap" /> %
                </div>
                <div class="form-group">
                  	<label>Status Bayar</label>
                    <select id="lunas" name="lunas" class="form-control  mousetrap">
                          <option value=''>-- STATUS BAYAR--</option>
                          <option value='2' <?php if(!empty($lunas) && $lunas == "2"){?>selected<?php } ?>>Debit</option>
                          <option value='1' <?php if(!empty($lunas) && $lunas == "1"){?>selected<?php } ?>>Kredit</option>
                      </select>
                </div>
                <div class="form-group">
                    <br />
                    <button type="submit" class="btn btn-sempoa-1 col-md-6" name="direction" value="show" style='margin-left:0'>
                    <i class="icsw16-info-about icsw16-white"></i>Lihat Data
                    </button>
                </div>
            </div>
        </form>
        <br clear="all" />
    </div>
</div>
<br />
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
<input type="hidden" id="locations" value='"city":"<?php echo $ajax_dir; ?>/city.php","district":"<?php echo $ajax_dir; ?>/district.php","subdistrict":"<?php echo $ajax_dir; ?>/subdistrict.php"' /> 
<div class="row-fluid">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Penjualan PERIODE <?php echo $label; ?> <?php echo $label_geo; ?></h4>
            </div>
            <div class="ibox-content">
                    <div id="ch_sale" class="chart_a"></div>
            </div>
        </div>
    </div>
</div>
<?php if(!empty($_SESSION['childkey'])){?>
<div class="row-fluid">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Penjualan Per Cabang <?php echo $label; ?> <?php echo $label_geo; ?></h4>
            </div>
            <div class="ibox-content">
                    <div id="ch_branch" class="chart_a"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
    

<div class="row-fluid">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Penjualan Sales <?php echo $label; ?> <?php echo $label_geo; ?></h4>
            </div>
            <div class="w-box-content cnt_b" >
                <div id="ch_sales" class="chart_a" style='height:500px' ></div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
        	<div class='col-md-6'>
                <div class="ibox-title">
                    <h4>Trend Produk <?php echo $label; ?> <?php echo $label_geo; ?></h4>
                </div>
                <div class="w-box-content cnt_b" >
                    <div id="ch_product" class="chart_a" style='height:500px'></div>
                </div>
            </div>
        	<div class='col-md-6'>
                <div class="ibox-title">
                    <h4>Trend Lokasi <?php echo $label; ?></h4>
                </div>
                <div class="w-box-content cnt_b" >
                    <div id="ch_geografis" class="chart_a" style='height:500px'></div>
                </div>
            </div>
        </div>
    </div>
</div>