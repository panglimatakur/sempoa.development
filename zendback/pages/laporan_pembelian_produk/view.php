<?php defined('mainload') or die('Restricted Access'); ?>
<?php 
if((empty($direction) || (!empty($direction) && $direction != "show"))){ 
	$display = 'display:none;'; 
}
if(substr_count($page,"laporan") == 1){
	$display = ''; 
} 
?>  
<div class="row-fluid" style=" <?php echo @$display; ?> background:#FFF" id='div_form_pembelian'>
    <div class="ibox-title">
        <h4>Form Seleksi Pencarian Data</h4>
    </div>
    <div style='padding:8px; margin:2px 5px 2px 5px;'>	       
        <form method="post" action="" >
            <div class="col-md-6">
                <div class="form-group">
                    <div class="col-md-6">
                        <label>Tanggal Awal</label>
                        <div class="input-append date" id="dpStart" data-date-format="dd/mm/yyyy" data-date="<?php echo @$tgl_1_new; ?>">
                            <input class="span8"  value="<?php echo @$tgl_1; ?>" readonly="" type="text" id="tgl_1" name="tgl_1">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Akhir</label>
                        <div class="input-append date" id="dpEnd" data-date-format="dd/mm/yyyy" data-date="<?php echo @$tgl_2_new; ?>">
                            <input class="span8" value="<?php echo @$tgl_2; ?>" readonly="" type="text" id="tgl_2" name="tgl_2">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                  <label>No Faktur</label>
                  <input type="text" id="faktur" name="faktur" value="<?php echo @$faktur; ?>"  class="form-control mousetrap"/>
                </div>
                <div class="form-group">
                  <label>Harga Beli</label>
                  <input type="text" id="harga_pokok" name="harga_pokok" value="<?php echo @$harga_pokok; ?>" class="form-control  mousetrap"/>
                </div>
                <div class="form-group">
                  <label>Jumlah </label>
                  <input type="text" id="stock" name="stock" value="<?php echo @$stock; ?>" class="form-control  mousetrap" />
                </div>
                <div class="form-group">
                  <label>Harga Jual</label>
                  <input type="text" id="harga" name="harga" value="<?php echo @$harga; ?>" class="form-control  mousetrap" />
                </div>
                <div class="form-group">
                  <label>Total Bayar </label>
                  <input type="text" id="total" name="total" value="<?php echo @$total; ?>" class="form-control mousetrap"/>
                </div>
                <div class="form-group">
                  <label>Status Bayar</label>
                <select id="lunas" name="lunas" class="form-control  mousetrap">
                      <option value=''>-- STATUS BAYAR--</option>
                      <option value='2' <?php if(!empty($lunas) && $lunas == "2"){?>selected<?php } ?>>Lunas</option>
                      <option value='1' <?php if(!empty($lunas) && $lunas == "1"){?>selected<?php } ?>>Hutang</option>
                  </select>
                </div>
            </div>
            <div class="col-md-6">
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
                <?php if(!empty($id_type_report)){ include $call->inc("modules/laporan_pembelian_produk/ajax","data.php"); } ?>
            </span>
            <div class="form-group">
              <label>Code</label>
              <input type="text" id="code" name="code" value="<?php echo @$code; ?>"  class="form-control mousetrap"/>
            </div>
            <div class="form-group">
               <label>Nama</label>
              <input id="nama" name="nama" type="text" value="<?php echo @$nama; ?>" class="form-control mousetrap" />
            </div>
            <div class="form-group">
              <label>Deskripsi</label>
              <input type='text' id="deskripsi" name="deskripsi" class="form-control mousetrap" value='<?php echo @$deskripsi; ?>'>
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
<div class="ibox-title">
        Jumlah Data : 
        <select size="1" name="pagesize" aria-controls="dt_colVis_Reorder" onchange="more_page(this)" style="width:100px">
            <option value="10" <?php if(!empty($pagesize) && $pagesize == 10){?> selected <?php } ?>>10</option>
            <option value="25" <?php if(!empty($pagesize) && $pagesize == 25){?> selected <?php } ?>>25</option>
            <option value="50" <?php if(!empty($pagesize) && $pagesize == 50){?> selected <?php } ?>>50</option>
            <option value="100" <?php if(!empty($pagesize) && $pagesize == 100){?> selected <?php } ?>>100</option>
        </select>
        <div class="pull-right">
            <div class="toggle-group">
                
                <ul class="dropdown-menu">
                    <li>
                    	<a href="modules/laporan_pembelian_produk/includes/print.php?prompt=true" class="fancybox fancybox.ajax">
                        <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                        </a>
                    </li>
                    <li>
                    	<a href="modules/laporan_pembelian_produk/includes/print.php?prompt=true&export=true" class="fancybox fancybox.ajax">
                        <i class="icsw16-excel-document"style="margin:-2px 4px 0 0"></i>Export Ke Excel
                        </a>
                    </li>
                    <?php if(allow('delete') == 1){?>
                    <li>
                        <a href="javascript:void()" id="select_rows_2">
                        <i class="icon-check" style="margin:0 4px 0 0"></i>
                            Pilih Semua
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void()" id="delete_picked">
                        <i class="icsw16-trashcan" style="margin:-2px 4px 0 0"></i>
                            Hapus Yang Di Pilih
                        </a>
                    </li>
                   <?php } ?>
                </ul>
            </div>
        </div>
</div>
<div class="ibox-content">
<a name="report"></a>
<input id="proses_page" type="hidden"  value="modules/input_pembelian_produk/ajax/proses.php" />
<input id="data_page" type="hidden"  value="modules/laporan_pembelian_produk/ajax/data.php" />

<table width="100%" class="table-long table-striped" id="table_data">
    <thead>
        <tr>
          <th width="20" class="table_checkbox" style="width:13px; text-align:center">
            <input type="checkbox" id="select_rows" class="select_rows"/>
          </th>
          <th width="879" id="content_load">&nbsp;</th>
        <th width="107" style='text-align:center'>ACTION</th>
        </tr>
    </thead>
    <tbody>
      <tr style="display:none;">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
       </tr>
      <?php
		$total_all		= 0;
		$jumlah_all		= 0;
		$hutang_all		= 0;
          while($dt_buy = $db->fetchNextObject($q_buy)){ 
		    if($dt_buy->PAID_STATUS == "2"){
                $paid_status = "LUNAS";	
            }else{
                $paid_status = "<a href='".$dirhost."/?page=hutang_pembelian_produk&id_buy=".$dt_buy->ID_PRODUCT_BUY."'>HUTANG</a>";	
            }
      ?>
      <tr class="wrdLatest" data-info='<?php echo $dt_buy->ID_CASH_FLOW; ?>'  id="tr_<?php echo $dt_buy->ID_CASH_FLOW; ?>">
        <td style="vertical-align:top; text-align:center">
        	<input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_buy->ID_CASH_FLOW; ?>'/>
        </td>
        <td style="vertical-align:top; position:relative;" >
            <span class='code'>
				<b>NO FAKTUR : <?php echo $dt_buy->FACTURE_NUMBER; ?></b>
                <?php if(!empty($dt_buy->ID_PARTNER)){?>
                    <br />
                	Pembelian Dari : <?php echo $db->fob("PARTNER_NAME",$tpref."partners"," WHERE ID_PARTNER='".$dt_buy->ID_PARTNER."'"); ?>
                <?php } ?>
            </span>
            <br>
            <small>
				<i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_buy->TRANSACTION_DATE); ?> 
            </small>
			<?php 
			include $call->inc("modules/laporan_pembelian_produk/includes","list_kolektif.php");
			$total_all 	= $dt_buy->PAID	+$total_all;
            $jumlah_all	= $dt_buy->JML	+$jumlah_all;
			$hutang_all	= $dt_buy->HUTANG+$hutang_all;
            ?>
            <br style="clear:both">
        </td>
        <td style='text-align:center'>
        <?php if(allow('delete') == 1){?>
                <a href='javascript:void()' onclick="removal('<?php echo $dt_buy->ID_CASH_FLOW; ?>','<?php echo $dt_buy->ID_PRODUCT_BUY; ?>')" class="btn btn-mini" title="Delete">
                    <i class="icon-trash"></i>
                </a>
        <?php } ?>
        </td>
      </tr>
      <?php } ?>
      <tr>
        <td style="vertical-align:top; text-align:center">&nbsp;</td>
        <td style="vertical-align:top; position:relative;" >
            <table width="100%" class="table-long table-striped" id="table_data">  
              <thead>
                  <tr>
                    <th style="text-align:center"><strong>TTL PEMBELIAN</strong></th>
                    <th style="text-align:center">TTL HUTANG</th>
                    <th style="text-align:center"><strong>TOTAL</strong></th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                    <td style="text-align:center" id="jumlah_all"><?php echo @$jumlah_all; ?></td>
                    <td style="text-align:center" id="hutang_all"><?php echo money("Rp.",@$hutang_all); ?></td>
                    <td style="text-align:center" id="total_all"><?php echo money("Rp.",@$total_all); ?></td>
                  </tr>
              </tbody>
            </table>
            <input type="hidden" id="jumlah_all_num" value="<?php echo @$jumlah_all; ?>"/>
            <input type="hidden" id="hutang_all_num" value="<?php echo @$hutang_all; ?>"/>
            <input type="hidden" id="total_all_num" value="<?php echo @$total_all; ?>"/>
        </td>
        <td style='text-align:center'>&nbsp;</td>
      </tr>
    </tbody>
</table>
<div id="lastPostsLoader"></div>
</div>
<div class="ibox-title" style="text-align:center">
	<?php if($num_buy > 10){?>
    	<a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
	<?php } ?>
    <br clear="all" />
</div>