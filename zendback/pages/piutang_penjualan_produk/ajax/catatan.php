<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$page 			= isset($_REQUEST['page']) ? $_REQUEST['page'] : "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] : "";
	$no 			= isset($_REQUEST['no']) ? $_REQUEST['no'] : "";
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
	
	$query_str_show	= " SELECT 
							*,SUM(b.TOTAL) AS TOTAL
						FROM 
							".$tpref."factures a,".$tpref."products_sales b 
						WHERE 
							a.ID_CLIENT='".$_SESSION['cidkey']."' AND 
							a.ID_FACTURE = b.ID_FACTURE AND 
							a.ID_FACTURE = '".$no."' ";

	$q_produk 		= $db->query($query_str_show);
	$dt_produk 		= $db->fetchNextObject($q_produk);
	@$no_faktur		= $dt_produk->FACTURE_NUMBER;
	@$no_po			= $dt_produk->PO_NUMBER;
	$id_facture		= $dt_produk->ID_FACTURE;
	@$termin		= $dt_produk->TERMS;
	$total_bayar	= $dt_produk->TOTAL;	
	$bayar			= $dt_produk->PAID;
	$sisa			= $total_bayar-$bayar;
	$tgl_bayar		= $dtime->date2indodate($tglupdate);
?>
<input type='hidden' id='piutang_config' value='"id_facture":"<?php echo @$id_facture; ?>","id_product_sale":"<?php echo $no; ?>"'/>
<div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Piutang Pejualan Produk / Bahan</h4><br />
        </div>
        <div class="ibox-content">
        	<div id="msg"></div>
            <table style="width:100%" class="popup table table-striped">
            <thead>
                    <tr>
                        <th width="15%">No Faktur</th>
                        <th width="19%" style="text-align:center">No PO</th>
                        <th width="18%" style="text-align:center"><b>Total Penjualan</b></th>
                        <th width="22%" style="text-align:center">Total Bayar</th>
                        <th width="26%" style="text-align:center">Sisa</th>
              </tr>
              </thead>
                <tbody>
                  <?php 
                        $id_product			= $dt_produk->ID_PRODUCT;
                        @$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
                      ?>
                      <tr>
                        <td>
						<?php if(!empty($no_faktur)){?>
                            <?php echo @$no_faktur; ?> 
                        <?php } ?>
                        </td>
                        <td style="text-align:center">
                        <?php if(!empty($no_po)){?>
                            <?php echo @$no_po; ?>
                        <?php } ?>
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
							<span id="sisa_bayar_label"><?php echo money("Rp.",$dt_produk->REMAIN); ?></span>
                        	<input type="hidden" id="sisa_bayar" value="<?php echo $dt_produk->REMAIN; ?>"/>
                        </td>
                    </tr>
                      <tr>
                        <td colspan="5">
                        <span id='load'></span>
                        <table width="100%" border="0" class='form_input'>
                          <thead>
                              <tr>
                                <td><b><?php echo @$termin; ?> TERMIN</b></td>
                                <td><b>JML Bayar</b></td>
                                <td><b>TGL Bayar</b></td>
                                <td><b>TGL Tempo</b></td>
                                <td><strong>Keterangan</strong></td>
                              </tr>
                          </thead>
                          <tbody>
                          <?php
						  $q_piutang = $db->query("SELECT * FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$dt_produk->ID_CASH_FLOW."' AND PLUS_MINUS = '-' ORDER BY ORDINAL ASC");
						  $pembayaran_2 = 0;
						  while($dt_piutang = $db->fetchNextObject($q_piutang)){
							  $pembayaran_2++;
							  $tgl_tempo = "";
							  $tgl_tempo = $db->fob("REMINDER_DATE",$tpref."debt_credit_reminder","WHERE ID_CASH_FLOW='".$dt_produk->ID_CASH_FLOW."' AND DEBT_CREDIT = '3' AND ORDINAL = '".$dt_piutang->ORDINAL."'");
							  
							  $next_ordinal = $dt_piutang->ORDINAL+1;
							  $next_tgl_tempo = $db->fob("REMINDER_DATE",$tpref."debt_credit_reminder","WHERE ID_CASH_FLOW='".$dt_produk->ID_CASH_FLOW."' AND DEBT_CREDIT = '3' AND ORDINAL = '".$next_ordinal."'");
						  ?>
                          <tr>
                          	<td width="17%"><b>Pembayaran <?php echo $dt_piutang->ORDINAL; ?></b></td>
                            <td width="17%"><?php echo money("Rp.",$dt_piutang->AMOUNT); ?></td>
                            <td width="15%"><?php echo $dtime->date2indodate($dt_piutang->PAY_DATE); ?></td>
                            <td width="15%"><?php echo $dtime->date2indodate($tgl_tempo); ?></td>
                            <td width="36%"><?php echo $dt_piutang->NOTE; ?></td>
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
                                <span class="input-append date" data-date="<?php echo @$next_tgl_tempo; ?>" data-date-format="dd-mm-yyyy">
                                  <input class="mousetrap" value="<?php echo @$next_tgl_tempo; ?>" id="tgl_tempo" readonly="readonly" type="text"/>
                                  <span class="add-on"><i class="icsw16-day-calendar"></i></span> </span>
                               </td>
                            </tr>
                            <tr>
                              <td class='req'>Tanggal Bayar</td>
                              <td colspan="4">
							    <?php if(empty($tgl_bayar)){ $tgl_bayar = date("d-m-Y"); } ?>
                                <span class="input-append date" id="dp2" data-date="<?php echo $tgl_bayar; ?>" data-date-format="dd-mm-yyyy">
                                  <input class="mousetrap" size="16" value="<?php echo $tgl_bayar; ?>" readonly="readonly" type="text" id="tgl_bayar" />
                                  <span class="add-on"><i class="icsw16-day-calendar"></i></span> </span>
                                  
                               </td>
                            </tr>
                            <tr>
                              <td class='req'>Jumlah Bayar</td>
                              <td colspan="4"><span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                                <input type="text" id="jml_bayar" value="" class="mousetrap" onkeyup="count_pay(this)" onblur='count_pay(this)'/>
                                <span class="add-on">,00</span> </span></td>
                            </tr>
                            <tr>
                              <td style="vertical-align:top">Keterangan</td>
                              <td colspan="4"><textarea name="desc" id="desc" class="mousetrap" style="width:90%"></textarea></td>
                            </tr>
                            <tr>
                              <td >&nbsp;</td>
                              <td colspan="4">
                              <input type="hidden" id="termin" value="<?php echo @$next_ordinal; ?>"/>
                              <button type="button" class="btn btn-sempoa-1" id="btn_direction"  style='float:left'>Bayar</button>
                                
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        
                        </td>
                      </tr>
              </tbody>
        </table>
        <?php
        }
        ?>

        </div>
    </div>
</div>
<?php } ?>