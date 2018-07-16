<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
if(!empty($prompt) && $prompt == "true"){
?>
<form id="form_print" method="post" action="<?php echo $dirhost; ?>/modules/laporan_keuangan/includes/print_history.php" target="_new" class="">
	<div class="form-group">
    <label>Jumlah Data Ditampilkan :</label>
    <small class='code'>NB : Kosongkan Untuk Menampilkan Seluruh Data</small>
    <br>
    <input type="text" id="show_data">
    </div>
	<div class="form-group">
    <span id="print_container"></span>
    <button type="button" id="button_show" class="btn btn-sempoa-1" onClick="print_r();"><i class="icsw16-printer icsw16-white"></i>Cetak Data</button>
    </div>
</form>
<?php }else{
	
	$show_data 		= isset($_REQUEST['show_data']) ? $_REQUEST['show_data'] 	: "";
	$limit			= "";
	if(!empty($show_data))					{ $limit 		= " LIMIT 0,".$show_data."";							}
	
	$parent_id		=	isset($_REQUEST['parent_id']) 	? $_REQUEST['parent_id']:"";
	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}

	$condition	= "";
	if(!empty($tgl_1) && 
		!empty($tgl_2))					{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 		.= " AND TGLUPDATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 	
	}

	$query_str		= "SELECT * FROM ".$tpref."cash_flow WHERE ID_CASH_TYPE='".$parent_id."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." ORDER BY ID_CASH_FLOW DESC";
	$inout			= $db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE = '".$parent_id."'");
	$q_transaksi	= $db->query($query_str." ".$limit);
	

?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Laporan Sejarah Keuangan ".$inout.""); ?>
		<div id="print_content">
            <table width="100%" class="table-striped rt cf"id="rt2" >
              <thead class="cf">
                <tr>
                    <th width="19%">Tanggal</th>
                    <th width="9%">Kode</th>
                    <th width="16%">Total</th>
                    <th width="14%"><b>Bayar</b></th>
                    <th width="13%">Sisa</th>
                    <th width="11%">Status</th>
                  </tr>
              </thead>
                <tbody>
			  <?php while($dt_transaksi = $db->fetchNextObject($q_transaksi)){ 
                    $id_cash_flow 	= 	$dt_transaksi->ID_CASH_FLOW;
                    if(strlen($id_cash_flow) == 1){ $id_transaction = '0'.$id_cash_flow; }else{ $id_transaction = $id_cash_flow; }
              ?>
                  <tr>
                    <td>
                        <?php echo $dtime->date2indodate($dt_transaksi->TGLUPDATE); ?> 
                        <?php echo $dt_transaksi->WKTUPDATE; ?>
                    </td>
                    <td>TR<?php echo $id_transaction; ?></td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_1">
                        <?php echo money("Rp.",$dt_transaksi->CASH_VALUE); ?>
                    </td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_2">
                        <?php if(!empty($dt_transaksi->PAID)){ echo money("Rp.",$dt_transaksi->PAID); }else{ echo "0"; } ?>
                    </td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_3">
                        <?php if(!empty($dt_transaksi->REMAIN)){ echo money("Rp.",$dt_transaksi->REMAIN); }else{ echo "0"; } ?>
                    </td>
                    <td id="div_cash_<?php echo $id_cash_flow; ?>_4">
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
                  </tr>
              <?php } ?>
                </tbody>
            </table>
        <br>
		<?php
            $rekap_str 		= 	"SELECT SUM(PAID) AS TOTAL_ALL,COUNT(ID_CASH_FLOW) AS COUNT_ALL FROM ".$tpref."cash_flow WHERE ID_CASH_TYPE='".$parent_id."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." ORDER BY ID_CASH_FLOW DESC";
        
            $q_rekap	= $db->query($rekap_str);
            $dt_rekap	= $db->fetchNextObject($q_rekap);
            $total_all	= $dt_rekap->TOTAL_ALL;
            $jumlah_all	= $dt_rekap->COUNT_ALL;
        ?> 
        <table width="100%" class="table-long table-striped" id="table_data">  
          <thead>
              <tr>
                <th style="text-align:center"><strong>JUMLAH TRANSAKSI</strong></th>
                <th style="text-align:center"><strong>TOTAL</strong></th>
              </tr>
          </thead>
          <tbody>
              <tr>
                <td style="text-align:center"><?php echo @$jumlah_all; ?></td>
                <td style="text-align:center"><?php echo money("Rp.",@$total_all); ?></td>
              </tr>
          </tbody>
        </table>

</div>
		<div id="footer"><?php echo print_footer(); ?></div>
	</div>
</body>


<script language="javascript">
	window.print();
</script>
<?php }
}
?>
