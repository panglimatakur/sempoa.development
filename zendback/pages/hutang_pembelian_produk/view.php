<?php defined('mainload') or die('Restricted Access'); ?>
<div class="row-fluid" style="background:#FFF" >
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
                <?php if(!empty($id_type_report)){ include $call->inc("modules/".$page."/ajax","data.php"); } ?>
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
                    	<a href="<?php echo $inc_dir; ?>/print.php?prompt=true" class="fancybox fancybox.ajax">
                        <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                        </a>
                    </li>
                    <li>
                    	<a href="<?php echo $inc_dir; ?>/print.php?prompt=true&export=true" class="fancybox fancybox.ajax">
                        <i class="icsw16-excel-document"style="margin:-2px 4px 0 0"></i>Export Ke Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
</div>
<div class="ibox-content">
<a name="report"></a>
<input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
<input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />

<table width="100%" class="table-long table-striped" id="table_data">
    <thead>
        <tr>
          <th width="73%">&nbsp;</th>
        <th width="15%" style='text-align:center'>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <tr style="display:none">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      <?php
          while($dt_buy = $db->fetchNextObject($q_buy)){ 
            $photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_buy->ID_PRODUCT."'");
            @$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_buy->ID_PRODUCT_UNIT."'"); 
            $total_buy		= "";
			$num_kolektif	= "";
			$kolektif		= "";
			$paid_status	= "";
            $total_buy		= $dt_buy->SUMMARY;
			if($dt_buy->PAID_STATUS == "2"){
                $paid_status = "LUNAS";	
            }else{
                $paid_status = "HUTANG";	
            }
			$new_bayar 	= $dt_buy->PAID;
			$sisa		= $dt_buy->REMAIN;
      ?>
      <tr class="wrdLatest" data-info='<?php echo $dt_buy->ID_FACTURE; ?>' id="tr_<?php echo $dt_buy->ID_FACTURE; ?>">
        <td style="vertical-align:top;">
            <span class='code'>
				<b>NO FAKTUR : <?php echo $dt_buy->FACTURE_NUMBER; ?></b>
            </span>
            <br>
            <small>
				<i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_buy->TRANSACTION_DATE); ?> 
            </small>
			<?php include $call->inc($inc_dir,"list.php"); ?>
            <br clear="all" />
        </td>
        <td style='text-align:center'>
        <?php if(allow('insert') == 1){?>
                <a href="<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>&direction=edit&no=<?php echo $dt_buy->ID_FACTURE; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Bayar">
                    <i class="icsw16-money"></i>
                </a>
        <?php } ?>
        <?php if(allow('delete') == 1){?>
                <a href="javascript:void()" class="btn btn-mini" title="Hapus" onclick="del_debcre()">
                    <i class="icsw16-trashcan"></i>
                </a>
        <?php } ?>
        </td>
      </tr>
      <?php } ?>
    </tbody>
</table>
<div id="lastPostsLoader"></div>
</div>
<div class="ibox-title" style="text-align:center">
	<?php if($num_buy > 10){ ?>
	<a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
	<?php } ?>
    <br clear="all" />
</div>