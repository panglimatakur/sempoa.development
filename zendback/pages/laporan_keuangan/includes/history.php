<?php $id_root = $db->fob("IN_OUT",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$parent_id."'"); ?>
<input type='hidden' id='id_root' value='<?php echo @$id_root; ?>'>
<input type='hidden' id='parent_id' value='<?php echo @$parent_id; ?>'>

<div class="ibox-title">
    Jumlah Data : 
    <select size="1" name="pagesize" aria-controls="dt_colVis_Reorder" onchange="more_page(this)" style="width:100px">
        <option value="10" selected="selected">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="100">100</option>
    </select>
    <div class="pull-right">
        <div class="toggle-group">
            
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo $inc_dir; ?>/print_history.php?prompt=true" class="fancybox fancybox.ajax">
                    <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                    </a>
                </li>
                <?php if(!empty($open_process) && $open_process == "open"){?>
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
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="ibox-content">
<table width="100%" class="table-long table-striped" id="table_data">
    <thead>
      <tr>
        <th class="table_checkbox" ><input type="checkbox" id="select_rows" class="select_rows"/></td>
        <th>RIWAYAT TRANSAKSI <?php echo strtoupper($inout); ?></th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
  <?php 
  		while($dt_transaksi = $db->fetchNextObject($q_transaksi)){ 
        $id_cash_flow 	= 	$dt_transaksi->ID_CASH_FLOW;
        if(strlen($id_cash_flow) == 1){ $id_transaction = '0'.$id_cash_flow; }else{ $id_transaction = $id_cash_flow; }
  ?>
  <tr style="display:none">
    <td style="text-align:center">&nbsp;</td>
    <td>&nbsp;</td>
    <td style="text-align:center">&nbsp;</td>
  </tr>
  <tr id="tr_detail_<?php echo $dt_transaksi->ID_CASH_FLOW; ?>">
    <td width="3%" style="text-align:center">
    	<input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_transaksi->ID_CASH_FLOW; ?>'/>
    </td>
    <td width="85%">
    <span class='code'>
        <b>KODE TRANSAKSI : TR<?php echo $id_transaction; ?></b>
    </span>
    <br>
    <small>
    	<i class="icsw16-day-calendar"></i>
		<?php echo $dtime->date2indodate($dt_transaksi->TGLUPDATE); ?> 
        <?php echo $dt_transaksi->WKTUPDATE; ?>
    </small>
    <div style='height:auto; max-height:150px; overflow:scroll; border:1px solid #CCC;'>
            <table width="100%" class="table-striped rt cf"id="rt2" >
              <thead class="cf">
                <tr>
                  <th width="16%">&nbsp;</th>
                    <th width="16%">Total</th>
                    <th width="14%">Status</th>
                    <th width="14%"><b>Bayar</b></th>
                    <th width="13%">Sisa</th>
                  </tr>
              </thead>
                <tbody>
                  <tr>
                    <td >
						<?php if(!empty($dt_transaksi->NOTE)){?>
                            <i class="icsw16-create-write"></i><b><?php echo @$dt_transaksi->NOTE; ?></b>
                        <?php } ?>
                    </td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_1">
                        <?php echo money("Rp.",$dt_transaksi->CASH_VALUE); ?>
                    </td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_2"><?php 
                            switch ($dt_transaksi->PAID_STATUS){
                                case "1":
                                    echo "HUTANG";
									$status_name = "HUTANG";
                                break;
                                case "3":
                                    echo "PIUTANG";
									$status_name = "PIUTANG";
                                break;
                                default:
                                    echo "LUNAS";
                                break;	
                            }
                        ?></td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_3">
                        <?php if(!empty($dt_transaksi->PAID)){ echo money("Rp.",$dt_transaksi->PAID); }else{ echo "0"; } ?>
                    </td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_4">
                        <?php if(!empty($dt_transaksi->REMAIN)){ echo money("Rp.",$dt_transaksi->REMAIN); }else{ echo "0"; } ?>
                    </td>
                  </tr>
                </tbody>
            </table>
   </div>         
            
    </td>
    <td width="12%" style="text-align:center">
    <?php if(!empty($open_process) && $open_process == "open"){?>
        <div class="btn-group">
        <?php if(allow('edit') == 1){?> 
            <span class="direction_button_<?php echo $id_cash_flow; ?>">
                <a href="<?php echo $ajax_dir; ?>/form.php?page=<?php echo $page; ?>&show=form_value&id_cash_type=<?php echo @$parent_id; ?>&id_cash_flow=<?php echo $id_cash_flow; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Edit"><!-- onclick="edit_value('<?php echo $id_cash_flow; ?>')"-->
                    <i class="icon-pencil"></i>
                </a>
            </span>
        <?php } ?>
        <?php if(allow('delete') == 1){?> 
            <a href='javascript:void()' onclick="removal('<?php echo $id_cash_flow; ?>')" class="btn btn-mini" title="Delete">
                <i class="icon-trash"></i>
            </a>
        <?php } ?>
        </div>
    <?php } ?>
    </td>
  </tr>
   <?php } ?> 
  <tr>
    <td>&nbsp;</td>
    <td>
		<?php
            $rekap_str 		= 	"SELECT SUM(PAID) AS TOTAL_ALL,SUM(REMAIN) AS REMAIN_ALL,COUNT(ID_CASH_FLOW) AS COUNT_ALL FROM ".$tpref."cash_flow WHERE ID_CASH_TYPE='".$parent_id."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." ORDER BY ID_CASH_FLOW DESC";
        
            $q_rekap	= $db->query($rekap_str);
            $dt_rekap	= $db->fetchNextObject($q_rekap);
            $total_all	= $dt_rekap->TOTAL_ALL;
			$remain_all	= $dt_rekap->REMAIN_ALL;
            $jumlah_all	= $dt_rekap->COUNT_ALL;
        ?>
        <input type='hidden' id='jumlah_all' value='<?php echo @$jumlah_all; ?>' />
        <input type='hidden' id='total_all' value='<?php echo @$total_all; ?>' />
        <input type='hidden' id='remain_all' value='<?php echo @$remain_all; ?>' />
        <table width="100%" class="table-long table-striped" id="table_data">  
          <thead>
              <tr>
                <th style="text-align:center"><strong>TTL TRANSAKSI</strong></th>
                <th style="text-align:center">TTL <?php echo @$status_name; ?></th>
                <th style="text-align:center"><strong>TOTAL</strong></th>
              </tr>
          </thead>
          <tbody>
              <tr>
                <td style="text-align:center" id="div_jumlah_all"><?php echo @$jumlah_all; ?></td>
                <td style="text-align:center" id="div_remain_all"><?php echo money("Rp.",@$remain_all); ?></td>
                <td style="text-align:center" id="div_total_all"><?php echo money("Rp.",@$total_all); ?></td>
              </tr>
          </tbody>
        </table>
    </td>
    <td>&nbsp;</td>
  </tr>
  </tbody>
</table>
</div>

<div class="ibox-title">
    <form id="form_paging" action="#report" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="parent_id" value="<?php echo @$parent_id; ?>" />
        <input type="hidden" name="tgl_1" 		value="<?php echo @$tgl_1; ?>" />
        <input type="hidden" name="tgl_2" 		value="<?php echo @$tgl_2; ?>" />
        <input type="hidden" name="direction" 	value="show" />
        <input type="hidden" name="pagesize" id="pagesize" value="<?php echo @$pagesize; ?>" />
        <?php if($q_transaksi > 0){?>
            <?php echo pfoot($query_str,$link_str); ?>
        <?php } ?> 
    </form>
</div>

