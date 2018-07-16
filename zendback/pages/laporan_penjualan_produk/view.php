<?php defined('mainload') or die('Restricted Access'); ?>
<?php 
if((empty($direction) || (!empty($direction) && $direction != "show"))){ 
	$display = 'display:none;'; 
}
if(substr_count($page,"laporan") == 1){
	$display = ''; 
} 
?> 
<div class="row-fluid" style=" <?php echo @$display; ?> background:#FFF" id='div_form_penjualan'>
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
                            <input class="span8"  value="<?php echo @$tgl_1; ?>" readonly="" type="text" name="tgl_1" id="tgl_1">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Tanggal Akhir</label>
                        <div class="input-append date" id="dpEnd" data-date-format="dd/mm/yyyy" data-date="<?php echo @$tgl_2_new; ?>">
                            <input class="span8" value="<?php echo @$tgl_2; ?>" readonly="" type="text" name="tgl_2" id="tgl_2">
                            <span class="add-on"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Nama Sales</label>
                    <select name="marketing" id="marketing" class="mousetrap form-control ">
                        <option value=''>--PILIH SALES--</option>
                        <?php 
                        $q_marketing = $db->query("SELECT * FROM system_users_client WHERE ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY USER_NAME ASC");
                        while($dt_marketing = $db->fetchNextObject($q_marketing)){
                        ?>
                        <option value='<?php echo $dt_marketing->ID_USER; ?>' <?php if(!empty($marketing) && $marketing == $dt_marketing->ID_USER){?>selected<?php } ?>>
                        <?php echo $dt_marketing->USER_NAME; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <select id="customer" name="customer" class="mousetrap form-control">
                        <option value=''>--PILIH PELANGGAN--</option>
                            <?php 
                            $q_customer = $db->query("SELECT * FROM ".$tpref."customers WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ORDER BY CUSTOMER_NAME ASC");
                            while($dt_customer = $db->fetchNextObject($q_customer)){
                            ?>
                            <option value='<?php echo $dt_customer->ID_CUSTOMER; ?>' <?php if(!empty($customer) && $customer == $dt_customer->ID_CUSTOMER){?>selected<?php } ?>>
                            <?php echo $dt_customer->ID_CUSTOMER; ?> - <?php echo $dt_customer->CUSTOMER_NAME; ?>
                            </option>
                            <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>No Faktur</label>
                    <input type="text" name="faktur" id="faktur" value="<?php echo @$faktur; ?>" class="form-control mousetrap"/>
                </div>
                <div class="form-group">
                    <label>Harga</label>
                    <input type="text" name="harga" id="harga" value="<?php echo @$harga; ?>" class="form-control mousetrap"/>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" name="jual" id="jual" value="<?php echo @$jual; ?>" class="form-control mousetrap"/>
                </div>
                <!--<div class="form-group">
                    <label>Diskon</label>
                    <input type="text" name="diskon" id="diskon" value="<?php echo @$diskon; ?>" class="form-control mousetrap"/> %
                </div>-->
                <div class="form-group">
                    <label>Total</label>
                    <input type="text" name="total_jual" id="total_jual" value="<?php echo @$total_jual; ?>" class="form-control mousetrap" >
                </div>
                <div class="form-group">
                  <label>Keterangan</label>
                  <textarea name="keterangan" id="keterangan" class="form-control mousetrap"><?php echo @$keterangan; ?></textarea>
                </div>
                <div class="form-group">
                  <label>Status Bayar</label>
                <select id="lunas" name="lunas" class="form-control  mousetrap">
                      <option value=''>-- STATUS BAYAR--</option>
                      <option value='2' <?php if(!empty($lunas) && $lunas == "2"){?>selected<?php } ?>>Lunas</option>
                      <option value='3' <?php if(!empty($lunas) && $lunas == "3"){?>selected<?php } ?>>Piutang</option>
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
                    <?php if(!empty($id_type_report)){ include $call->inc("modules/laporan_penjualan_produk/ajax","data.php"); } ?>
                </span>
                <div class="form-group">
                  <label>Code</label>
                  <input type="text" name="code" id="code" value="<?php echo @$code; ?>"  class="form-control mousetrap"/>
                </div>
                <div class="form-group">
                   <label>Nama</label>
                  <input name="nama" type="text" id="nama" value="<?php echo @$nama; ?>" class="form-control mousetrap" />
                </div>
                <div class="form-group">
                  <label>Deskripsi</label>
                  <input type='text' name="deskripsi" id="deskripsi" class="form-control mousetrap" value='<?php echo @$deskripsi; ?>'>
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
<div id="mes"></div>
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
                    	<a href="modules/laporan_penjualan_produk/includes/print.php?prompt=true" class="fancybox fancybox.ajax">
                        <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                        </a>
                    </li>
                    <li>
                    	<a href="modules/laporan_penjualan_produk/includes/print.php?prompt=true&export=true" class="fancybox fancybox.ajax">
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
<input id="proses_page" type="hidden"  value="modules/input_penjualan_produk/ajax/proses.php" />
<input id="data_page" type="hidden"  value="modules/laporan_penjualan_produk/ajax/data.php" />

<table width="100%" class="table-long table-striped" id="table_data">
    <thead>
        <tr>
          <th width="58" class="table_checkbox" style="width:13px; text-align:center">
            <input type="checkbox" id="select_rows" class="select_rows"/>
          </th>
          <th width="879" id="content_load">&nbsp;</th>
            <th width="107" style='text-align:center'>ACTION</th>
        </tr>
    </thead>
    <tbody>
      <tr style="display:none;">
        <td width="58"></td>
        <td>&nbsp;</td>
        <td width="107">&nbsp;</td>
       </tr>
      <?php
		$total_all		= 0;
		$jumlah_all		= 0;
		$piutang_all	= 0;
          while($dt_sale = $db->fetchNextObject($q_sale)){ 
            $total			= $dt_sale->PRICE*$dt_sale->QUANTITY;
			$q_marketing 	= $db->query("SELECT USER_NAME,USER_PHOTO FROM system_users_client WHERE ID_USER='".$dt_sale->ID_SALES."'");
			$dt_marketing 	= $db->fetchNextObject($q_marketing);
			@$nm_sales		= $dt_marketing->USER_NAME;
			@$pt_sales		= $dt_marketing->USER_PHOTO;
            if(!empty($diskon)){
                $diskon_new	= $total*($dt_sale->DISCOUNT/100);
                $total 		= $total-$diskon_new;
            }
			$paid_status	= "";
            if($dt_sale->PAID_STATUS == "2"){
                $paid_status = "LUNAS";	
            }else{
                $paid_status = "<a href='".$dirhost."/?page=piutang_penjualan_produk&id_sale=".$dt_sale->ID_PRODUCT_SALE."' >PIUTANG</a>";	
            }
      ?>
      <tr class="wrdLatest" data-info='<?php echo $dt_sale->ID_CASH_FLOW; ?>' id="tr_<?php echo $dt_sale->ID_CASH_FLOW; ?>">
        <td style="vertical-align:top; text-align:center"><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_sale->ID_CASH_FLOW; ?>'/></td>
        <td style="vertical-align:top; position:relative;" >
                
            <span class='code'>
				<b>NO FAKTUR : <?php echo $dt_sale->FACTURE_NUMBER; ?></b>
            </span>
            <br>
            <small>
				<i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_sale->TRANSACTION_DATE); ?> - 
                Oleh : <?php echo $nm_sales; ?><br />
            </small>
			<?php if(!empty($dt_sale->NOTE)){?>
                <div class="cnt_a invoice_preview" style="margin:7px; background:#FFF; font-size:12px">
                    <div class="inv_notes" >
                        <span class="label label-info">Notes</span>
                        <?php echo $dt_sale->NOTE; ?>
                    </div>
               </div>
          <?php } ?>
				<?php  
				include $call->inc("modules/laporan_penjualan_produk/includes","list_kolektif.php");
                $total_all 	= $dt_sale->PAID	+$total_all;
                $jumlah_all	= $dt_sale->JML		+$jumlah_all;
                $piutang_all= $dt_sale->PIUTANG	+$piutang_all;
                ?>
            <br clear="all" />
        </td>
        <td style='text-align:center; vertical-align:top'>
        		<?php if(!empty($pt_sales) && is_file($basepath."/".$user_foto_dir."/".$pt_sales)){?>
                <a href='<?php echo $dirhost."/".$user_foto_dir."/".$pt_sales; ?>' class="fancybox">
					<img src='<?php echo $dirhost."/".$user_foto_dir."/".$pt_sales; ?>' class='photo' width='70%' />
                </a>
                <br clear="all">
                <?php } ?>
                <?php if(allow('delete') == 1){?>
                <a href='javascript:void()' onclick="removal('<?php echo $dt_sale->ID_CASH_FLOW; ?>','<?php echo $dt_sale->ID_PRODUCT_SALE; ?>')" class="btn btn-mini" title="Delete">
                    <i class="icon-trash"></i>
                </a>
                <?php } ?>
                <a href='javascript:void()' onclick="window.open('<?php echo $dirhost; ?>/modules/laporan_penjualan_produk/includes/invoice.php?id_facture=<?php echo $dt_sale->ID_FACTURE; ?>','Invoice','width=700, height=700')" class="btn btn-mini" title="Print Invoice">
                    <i class="icsw16-cash-register"></i>
               </a>
                <br />
        </td>
      </tr>
      <?php } ?>
      <tr >
        <td style="vertical-align:top; text-align:center">&nbsp;</td>
        <td style="vertical-align:top; position:relative;" id="div_rekap">
            <table width="100%" class="table-long table-striped" id="table_data">  
              <thead>
                  <tr>
                    <th style="text-align:center"><strong>TTL PENJUALAN</strong></th>
                    <th style="text-align:center">TTL PIUTANG</th>
                    <th style="text-align:center"><strong>TOTAL</strong></th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                    <td style="text-align:center" id="jumlah_all"><?php echo @$jumlah_all; ?></td>
                    <td style="text-align:center" id="piutang_all"><?php echo money("Rp.",@$piutang_all); ?></td>
                    <td style="text-align:center" id="total_all"><?php echo money("Rp.",@$total_all); ?></td>
                  </tr>
              </tbody>
            </table>
            <input type="hidden" id="jumlah_all_num" 	value="<?php echo @$jumlah_all; ?>"/>
            <input type="hidden" id="piutang_all_num" 	value="<?php echo @$piutang_all; ?>"/>
            <input type="hidden" id="total_all_num" 	value="<?php echo @$total_all; ?>"/>
        </td>
        <td style='text-align:center; vertical-align:top'>&nbsp;</td>
      </tr>
    </tbody>
</table>
<div id="lastPostsLoader"></div>
</div>
<div class="ibox-title" style="text-align:center">
	<?php if($num_sale > 10){?>
	<a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
    <?php } ?>
	<br clear="all" />
</div>