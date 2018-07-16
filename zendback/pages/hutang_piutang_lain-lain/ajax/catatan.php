<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$page 				= isset($_REQUEST['page']) ? $_REQUEST['page'] : "";
	$direction 			= isset($_REQUEST['direction']) ? $_REQUEST['direction'] : "";
	$id_root 			= isset($_REQUEST['id_root']) ? $_REQUEST['id_root'] : "";
	$id_cash_flow 		= isset($_REQUEST['id_cash_flow']) ? $_REQUEST['id_cash_flow'] : "";
	if($id_root == 1){
		$label = "PIUTANG";	
	}
	if($id_root == 2){
		$label = "HUTANG";	
	}
}
?>
<script language="javascript">
	$(document).ready(function(){
		if($('#dp2').length) {
			$('#dp2').datepicker()
		}
	});
</script>
<style type="text/css">
	.form_input{
		border:1px solid #DADADA;	
	}
	.form_input td{
		padding:6px 0 6px 10px;
		vertical-align:middle;	
	}
	.form_input input{
		margin:0;	
	}
</style>
<?php
if(!empty($direction) && $direction == "edit"){
	$query_str_show	= "SELECT * FROM ".$tpref."cash_flow WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW = '".$id_cash_flow."' ";
	$q_cash 		= $db->query($query_str_show);
	$dt_cash 		= $db->fetchNextObject($q_cash);
	if(strlen($id_cash_flow) == 1){ $id_transaction = '0'.$id_cash_flow; }else{ $id_transaction = $id_cash_flow; }

	$tgl_transaksi	= $dt_cash->TGLUPDATE;
	$tgl_bayar		= $dtime->date2indodate($tglupdate);
	$bayar			= $dt_cash->PAID;
	$total_bayar	= $dt_cash->CASH_VALUE;
	
	$q_cash_type	=	$db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE='".$dt_cash->ID_CASH_TYPE."'");
	$dt_cash_type	=	$db->fetchNextObject($q_cash_type);
	$cash_type		= strtoupper($dt_cash_type->NAME);
	$cash_root		= $dt_cash_type->IN_OUT;
?>
<input type="hidden" id="id_cash_flow" value="<?php echo $id_cash_flow; ?>"/>
<div  style="width:100%">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5><?php echo $label; ?> Transaksi Biaya <?php echo $cash_type; ?></h4> <br />
        </div>
        <div class="ibox-content">
        	<div id="msg"></div>
            <table width="100%" class="popup table table-striped">
            <thead>
                    <tr>
                        <th width="46%"><b>Kode Transaksi</b></th>
                        <th width="18%" style="text-align:center"><b>Total Hutang</b></th>
                		<th width="22%" style="text-align:center">Total Bayar</th>
                        <th width="12%" style="text-align:center">Sisa</th>
              </tr>
              </thead>
                <tbody>
                      <tr>
                        <td>
							TR<?php echo $id_transaction; ?>
                        </td>
                        <td style="text-align:center">
                            <span style="text-align:center"><?php echo money("Rp.",$total_bayar); ?>
                                <input type="hidden" id="total_bayar" value="<?php echo $total_bayar; ?>"/>
                            </span>
                        </td>
                        <td style="text-align:center">
                            <span id="first_bayar_label"><?php echo money("Rp.",$bayar); ?></span>
                        	<input type="hidden" id="first_bayar" value="<?php echo $bayar; ?>"/>
                       </td>
                        <td style="text-align:center">
							<span id="sisa_bayar_label"><?php echo money("Rp.",$dt_cash->REMAIN); ?></span>
                        	<input type="hidden" id="sisa_bayar" value="<?php echo $dt_cash->REMAIN;; ?>"/>
                        </td>
                    </tr>
                      <tr>
                        <td colspan="4">
                        <table width="100%" border="0" class='form_input'>
                          <thead>
                            <tr>
                              <td width="24%"><b><?php echo @$termin; ?> TERMIN</b></td>
                              <td width="18%"><b>JML Bayar</b></td>
                              <td width="15%"><b>TGL Bayar</b></td>
                              <td width="15%"><b>TGL Tempo</b></td>
                              <td width="28%"><strong>Keterangan</strong></td>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
						  $q_hutang = $db->query("SELECT * FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$id_cash_flow."' AND PLUS_MINUS = '-' ORDER BY ORDINAL ASC");
						  $pembayaran_2 = 0;
						  while($dt_hutang = $db->fetchNextObject($q_hutang)){
							  $pembayaran_2++;
							  $tgl_tempo = "";
							  if($cash_root == 1){ $in_out = 3; }
							  if($cash_root == 2){ $in_out = 1; }
							  
							  @$tgl_tempo = $db->fob("REMINDER_DATE",$tpref."debt_credit_reminder","WHERE ID_CASH_FLOW='".$dt_hutang->ID_CASH_FLOW."' AND DEBT_CREDIT = '".@$in_out."' AND ORDINAL = '".$dt_hutang->ORDINAL."'");
							  
							  @$next_ordinal = $dt_hutang->ORDINAL+1;
							  @$next_tgl_tempo = @$db->fob("REMINDER_DATE",$tpref."debt_credit_reminder","WHERE ID_CASH_FLOW='".$dt_hutang->ID_CASH_FLOW."' AND DEBT_CREDIT = '".@$in_out."' AND ORDINAL = '".$next_ordinal."'");
						  ?>
                            <tr>
                              <td><b>Pembayaran <?php echo $dt_hutang->ORDINAL; ?></b></td>
                              <td ><?php echo money("Rp.",$dt_hutang->AMOUNT); ?></td>
                              <td><?php echo $dtime->date2indodate($dt_hutang->PAY_DATE); ?></td>
                              <td><?php echo $dtime->date2indodate($tgl_tempo); ?></td>
                              <td><?php echo $dt_hutang->NOTE; ?></td>
                            </tr>
                            <?php } ?>
                          	<tr>
                          	  <td colspan="5"><b>Form Pembayaran Termin Ke - <?php echo $next_ordinal; ?></b></td>
                       	    </tr>
                            <?php if(empty($next_tgl_tempo)){
								$next_tgl_tempo = $tgl_bayar;
								
							?>
								<script language="javascript">$('#dp1').datepicker();</script>
							<?php } ?>
                          	<tr>
                              <td>Tanggal Tempo</td>
                              <td colspan="4">
                                <span class="input-append date" id="dp1" data-date="<?php echo @$next_tgl_tempo; ?>" data-date-format="dd-mm-yyyy">
                                  <input class="mousetrap" value="<?php echo @$next_tgl_tempo; ?>" id="tgl_tempo" readonly="readonly" type="text"/>
                                  <span class="add-on"><i class="icsw16-day-calendar"></i></span> </span>
                               </td>
                            </tr>
                            <tr>
                              <td class='req'>Tanggal Bayar</td>
                              <td colspan="4"><?php if(empty($tgl_bayar)){ $tgl_bayar = date("d-m-Y"); } ?>
                                <span class="input-append date" id="dp2" data-date="<?php echo $tgl_bayar; ?>" data-date-format="dd-mm-yyyy">
                                  <input class="mousetrap" size="16" value="<?php echo $tgl_bayar; ?>" readonly="readonly" type="text" id="tgl_bayar" />
                                  <span class="add-on"><i class="icsw16-day-calendar"></i></span></span></td>
                            </tr>
                            <tr>
                              <td class='req'>Jumlah Bayar</td>
                              <td colspan="4"><span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                                <input type="text" id="jml_bayar" value="" class="mousetrap" onkeyup="count_pay(this)" onblur='count_pay(this)'/>
                                <span class="add-on">,00</span></span></td>
                            </tr>
                            <tr>
                              <td style="vertical-align:top">Keterangan</td>
                              <td colspan="4"><textarea name="desc" id="desc" class="mousetrap" style="width:90%"></textarea></td>
                            </tr>
                            <tr>
                              <td >&nbsp;</td>
                              <td colspan="4">
                              <input type="hidden" id="termin" value="<?php echo @$next_ordinal; ?>"/>
                              <button type="button" class="btn btn-sempoa-1" id="btn_direction"  style='float:left' onclick="save_hutang('<?php echo @$id_cash_flow; ?>')">Bayar</button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <span id='load'></span></td>
                      </tr>
              </tbody>
        </table>
        <?php
        }
        ?>

        </div>
    </div>
</div>
