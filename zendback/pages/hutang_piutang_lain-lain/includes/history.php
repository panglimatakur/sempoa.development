<?php defined('mainload') or die('Restricted Access'); ?>
<?php  
	$q_cash_type 		= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE = '".$parent_id."'");
	$dt_cash_type		= $db->fetchNextObject($q_cash_type);
	$cash_type_write	= $dt_cash_type->ID_CLIENT;
	$id_root 			= $dt_cash_type->IN_OUT; 
	$cash_type_name		= $dt_cash_type->NAME;
?>
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
<table width="100%" class="table-long table-striped" id="table_data">
    <thead>
      <tr>
        <th class="table_checkbox" ><input type="checkbox" id="select_rows" class="select_rows"/></td>
        <th>RIWAYAT TRANSAKSI BIAYA <?php echo strtoupper($cash_type_name); ?></th>
        <th>ACTION</th>
      </tr>
    </thead>
    <tbody>
      <tr style="display:none">
        <td style="text-align:center">&nbsp;</td>
        <td>&nbsp;</td>
        <td style="text-align:center">&nbsp;</td>
      </tr>
	  <?php while($dt_transaksi = $db->fetchNextObject($q_transaksi)){ 
            $id_cash_flow 	= 	$dt_transaksi->ID_CASH_FLOW;
            if(strlen($id_cash_flow) == 1)	{ $id_transaction = '0'.$id_cash_flow; 	}
			else							{ $id_transaction = $id_cash_flow; 		}
      ?>
      <tr id="tr_<?php echo $dt_transaksi->ID_CASH_FLOW; ?>">
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
                <table width="100%" class="table-striped rt cf"id="rt2" >
                  <thead class="cf">
                    <tr>
                        <th width="16%">Total</th>
                        <th width="14%">Status</th>
                        <th width="14%"><b>Bayar</b></th>
                        <th width="13%">Sisa</th>
                      </tr>
                  </thead>
                    <tbody>
                      <tr>
                        <td>
                            <?php echo money("Rp.",$dt_transaksi->CASH_VALUE); ?>
                        </td>
                        <td>
                            <?php 
                                switch ($dt_transaksi->PAID_STATUS){
                                    case "1":
                                        echo "HUTANG";
                                    break;
                                    case "3":
                                        echo "PIUTANG";
                                    break;
                                    default:
                                        echo "LUNAS";
                                    break;	
                                }
                            ?>
                        </td>
                        <td>
                            <?php if(!empty($dt_transaksi->PAID)){ echo money("Rp.",$dt_transaksi->PAID); }else{ echo "0"; } ?>
                        </td>
                        <td>
                            <?php if(!empty($dt_transaksi->REMAIN)){ echo money("Rp.",$dt_transaksi->REMAIN); }else{ echo "0"; } ?>
                        </td>
                      </tr>
                    </tbody>
                </table>
       <div style='height:auto; max-height:150px; overflow:scroll; border:1px solid #CCC;'>
                <table width="100%" border="0" class="table-striped rt cf"id="rt2">
                  <thead class="cf">
                    <tr>
                      <th>&nbsp;</th>
                      <th><b>JML Bayar</b></th>
                      <th><b>TGL Bayar</b></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                  $q_hutang = $db->query("SELECT * FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$id_cash_flow."' AND PLUS_MINUS = '-' ORDER BY ORDINAL ASC");
                  $pembayaran_2 = 0;
                  while($dt_hutang = $db->fetchNextObject($q_hutang)){
                  ?>
                    <tr>
                      <td width="27%"><b>Pembayaran <?php echo $dt_hutang->ORDINAL; ?></b></td>
                      <td width="28%"><?php echo money("Rp.",$dt_hutang->AMOUNT); ?></td>
                      <td width="45%"><?php echo $dtime->date2indodate($dt_hutang->TGLUPDATE); ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
       </div>         
                
        </td>
        <td width="12%" style="text-align:center">
            <div class="btn-group">
            <?php 
            if($cash_type_write != 0){
                if(allow('insert') == 1){?>
                    <a href="<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>&direction=edit&id_root=<?php echo $id_root; ?>&id_cash_flow=<?php echo $id_cash_flow; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Bayar">
                        <i class="icsw16-money"></i>
                    </a>
            <?php } ?>
            <?php if(allow('delete') == 1){?>
                    <a href="javascript:void()" class="btn btn-mini" title="Hapus" onclick="del_debcre()">
                        <i class="icsw16-trashcan"></i>
                    </a>
            <?php } 
            }
            ?>
            </div>
        </td>
  	   </tr>
  <?php } ?>    
  </tbody>
</table>
<div id="lastPostsLoader"></div>
</div>

<div class="ibox-title" style="text-align:center">
	<?php if($num_transaksi > 10){?>
    	<a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
	<?php } ?>
    <br clear="all" />
</div>
