<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 		= isset($_POST['direction']) 		? $_POST['direction'] : "";
	$id_root 		= isset($_REQUEST['id_root'])		? $sanitize->number($_REQUEST['id_root']) :"";
	$termin 		= isset($_REQUEST['termin'])			? $sanitize->number($_REQUEST['termin']) :"";
	$parent_id 		= isset($_REQUEST['parent_id'])		? $sanitize->number($_REQUEST['parent_id']) :"";
	$id_cash_flow 	= isset($_REQUEST['id_cash_flow'])	? $sanitize->number($_REQUEST['id_cash_flow']) :"";
	$tgl_tempo 		= isset($_REQUEST['tgl_tempo']) 	? $sanitize->str($_REQUEST['tgl_tempo']) :"";
	if(!empty($tgl_tempo)){
		$tgl_tempo		= $dtime->indodate2date(@$tgl_tempo);
	}
	$tgl_bayar 		= isset($_REQUEST['tgl_bayar']) 	? $sanitize->str($_REQUEST['tgl_bayar']) :"";
	if(!empty($tgl_bayar)){
		$tgl_bayar		= $dtime->indodate2date(@$tgl_bayar);
	}
	$keterangan 	= isset($_REQUEST['keterangan']) 	? $sanitize->str($_REQUEST['keterangan']) :"";
	$bayar 			= isset($_REQUEST['bayar']) 		? $sanitize->str($_REQUEST['bayar']) 	:"";
	$total 			= isset($_REQUEST['total']) 		? $sanitize->str($_REQUEST['total']) :"";
	$query_str_show	= "SELECT * FROM ".$tpref."cash_flow WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW = '".$id_cash_flow."' ";
	$q_transaksi 	= $db->query($query_str_show);
	$dt_transaksi 	= $db->fetchNextObject($q_transaksi);
	$paid			= $dt_transaksi->PAID;
	$lunas			= $dt_transaksi->PAID_STATUS;
	$kredit			= $dt_transaksi->REMAIN;
	$id_cash_flow 	= $dt_transaksi->ID_CASH_FLOW;
	$id_cash_src 	= $dt_transaksi->ID_CASH_SOURCE;
	if(strlen($id_cash_flow) == 1)	{ $id_transaction = '0'.$id_cash_flow; 	}
	else							{ $id_transaction = $id_cash_flow; 		}

	if(!empty($direction) && $direction == "save"){

		if($id_root == 1){
			$id_direction	= "17";
			$id_debcre		= "3";
		}
		if($id_root == 2){
			$id_direction	= "16";
			$id_debcre		= "1";
		}
		$status_lunas		= $lunas;
		if($bayar >= $total){
			$status_lunas	= "2";	
			$reminder_content	= array(1=>array("STATUS",1));
			$db->update($tpref."debt_credit_reminder",$reminder_content," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW = '".$id_cash_flow."'");
			$notification_content	= array(1=>array("NOTIFICATION_STATUS",1));
			$db->update($tpref."notifications",$notification_content," WHERE FOR_ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW = '".$id_cash_flow."'");
		}
		$new_bayar			= $paid+$bayar;
		$sisa				= $total-$new_bayar;
		insert_decre($id_cash_flow,$id_debcre,"-",$bayar,$sisa,$tgl_bayar);
		save_cash($id_cash_flow,$id_cash_src,$total,$new_bayar,$id_direction);
	}
	if((!empty($direction) && ($direction == "save" || $direction == "delete"))){
		if(!empty($direction) && ($direction == "insert" || $direction == "save")){
			
			if($status_lunas == "2"){
				$paid_status = "LUNAS";	
			}else{
				if($id_root == 2){
					$paid_status = "HUTANG";	
				}
				if($id_root == 1){
					$paid_status = "PIUTANG";	
				}
			}
			if($status_lunas != "2"){
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
    <?php	
			}
		}
	}
}
?>