<?php
session_start(); 
if(!empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$id_facture 	= isset($_REQUEST['id_facture']) 		? $sanitize->number($_REQUEST['id_facture']) :"";
	$query_str		= "SELECT * FROM ".$tpref."factures WHERE ID_FACTURE='".$id_facture."' AND ID_CLIENT='".$_SESSION['cidkey']."'";
	$q_facture		= $db->query($query_str);
	$num_facture 	= $db->numRows($q_facture);
?>
<!-- bootstrap framework css -->
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>bootstrap/css/bootstrap-responsive.min.css">
<!-- iconSweet2 icon pack (16x16) -->
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>img/icsw2_16/icsw2_16.css">
<!-- splashy icon pack -->
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>img/splashy/splashy.css">
<!-- flag icons -->
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>img/flags/flags.css">
<!-- power tooltips -->
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>js/lib/powertip/jquery.powertip.css">
<!-- google web fonts -->
    <link href='https://fonts.googleapis.com/css?family=Coda' rel='stylesheet' type='text/css'>
<!-- main stylesheet -->
    <link rel="stylesheet" href="<?php echo $web_tpl_dir; ?>css/beoro.css">
<?php
	if($num_facture > 0){
	$dt_facture		= $db->fetchNextObject($q_facture);
	$paid			= $dt_facture->PAID;
	$remain			= $dt_facture->REMAIN;
	$status			= $dt_facture->PAID_STATUS;
	$note			= $dt_facture->NOTE;
	$id_customer	= $dt_facture->ID_CUSTOMER;
	if($status != 2){
		if($status == 1){
			$status_lunas = "Hutang";
		}else{
			$status_lunas = "Piutang";
		}
	}else{
			$status_lunas = "Lunas";
	}
	
	$q_client			= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
	$dt_client			= $db->fetchNextObject($q_client);
	$client_name		= $dt_client->CLIENT_NAME;
	$client_address		= $dt_client->CLIENT_ADDRESS; 
	$client_phone		= $dt_client->CLIENT_PHONE; 
	
	$q_customer			= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER='".$id_customer."'");
	$dt_customer		= $db->fetchNextObject($q_customer);
	$customer_name		= $dt_customer->CUSTOMER_NAME;
	$customer_address	= $dt_customer->CUSTOMER_ADDRESS; 
	$customer_phone		= $dt_customer->CUSTOMER_PHONE; 
?>
<style type="text/css">
	.col-md-12{
		/*font-family:Verdana, Geneva, sans-serif;	*/
	}
</style>
<body style='padding:0'>
    <div class="row-fluid" >
        <div class="col-md-12">
            <div class="w-box w-box-blue">
                <div class="w-box-content cnt_a invoice_preview">
                    <h1><span>Invoice #<?php echo $dt_facture->FACTURE_NUMBER; ?></span></h1>
                    <div class="row-fluid">
                        <div class="col-md-4">
                            <p class="sepH_a"><span class="muted">Nomor Invoice</span>: <?php echo $dt_facture->FACTURE_NUMBER; ?></p>
                            <p class="sepH_a"><span class="muted">Tanggal Invoice</span>: <?php echo $dtime->now2indodate2($tglupdate); ?></p>
                            <p class="sepH_a"><span class="muted">Status Bayar</span>: <?php echo $status_lunas; ?></p>
                        </div>
                        <div class="col-md-4">
                            <strong class="muted">Dari</strong>
                            <address>
                                <strong><?php echo @$client_name; ?></strong><br>
                                <?php if(!empty($client_address)){ echo @$client_address."<br>"; } ?>
                                <?php if(!empty($client_phone)){?>
                                <abbr title="Phone"><i class="icsw16-phone"></i>:</abbr> <?php echo @$client_phone; ?>
                                <?php } ?>
                            </address>
                        </div>
                        <?php if(!empty($id_customer)){?>
                        <div class="col-md-4"><strong class="muted">Kepada</strong>
                          <address>
                                <strong><?php echo @$customer_name; ?></strong><br>
                                <?php if(!empty($customer_address)){ echo @$customer_address."<br>"; } ?>
                                <?php if(!empty($customer_phone)){?>
                                <abbr title="Phone"><i class="icsw16-phone"></i>:</abbr> <?php echo @$customer_phone; ?>
                            <?php } ?>
                            </address>	
                        </div>
                        <?php } ?>
                    </div>
                    <div class="row-fluid">
                        <div class="col-md-12">
                            <table class="table invE_table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Harga /unit</th>
                                        <th style='text-align:center'>Jumlah</th>
                                        <th style='text-align:center'>Diskon</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
									$query_str		= "SELECT * FROM 
														".$tpref."products_sales a,".$tpref."products b
													   WHERE 
													    a.ID_PRODUCT = b.ID_PRODUCT AND
													   	a.ID_FACTURE='".$id_facture."'";
									//echo $query_str;
									$total_all		= "";
									$q_sale			= $db->query($query_str);
									while($dt_sale	= $db->fetchNextObject($q_sale)){
										@$unit		= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_sale->ID_PRODUCT_UNIT."'"); 
										
								?>
                                    <tr>
                                        <td><?php echo $dt_sale->CODE; ?> <small>(<?php echo $dt_sale->NAME; ?>)</small></td>
                                        <td><?php echo money("",$dt_sale->PRICE); ?></td>
                                        <td style='text-align:center'><?php echo $dt_sale->QUANTITY; ?> <?php echo @$unit; ?></td>
                                        <td style='text-align:center'><?php echo $dt_sale->DISCOUNT; ?>%</td>
                                        <td><?php echo money("",$dt_sale->TOTAL); ?></td>
                                    </tr>
                                <?php $total_all  = $dt_sale->TOTAL+$total_all; } ?>
                                    <tr class="last_row">
                                        <td colspan="3">&nbsp;</td>
                                        <td colspan="2">
                                            <p class="sepH_a"><span class="muted sepV_b">
                                            	Total</span><?php echo money("",$total_all); ?>
                                            </p>
                                            <p class="sepH_a"><span class="muted sepV_b">
                                            	Paid</span><?php echo money("",$paid); ?>
                                            </p>
                                            <?php if($status != 2){?>
                                            <p class="sepH_a"><span class="muted sepV_b">
                                            	Remain</span><?php echo money("",$remain); ?>
                                            </p>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>	
                        </div>
                    </div>
                    <?php if(!empty($note)){?>
                    <div class="row-fluid">
                        <div class="col-md-12">
                            <div class="cnt_a inv_notes">
                                <span class="label label-info">Note</span>
                                <?php echo @$note; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script language="javascript">
	window.print();
</script>
<?php 
	}else{
?>
	<div class='alert alert-error'>No Facture Di Temukan, Periksa Kembali Data Penjualan</div>
<?php		
	}
} ?>