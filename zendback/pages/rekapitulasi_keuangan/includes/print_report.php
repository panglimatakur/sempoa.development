<?php
session_start(); 
if(!empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");

	if(!empty($_REQUEST['periode']))	{ $periode 	= $sanitize->str($_REQUEST['periode']); 		}
	if(!empty($_REQUEST['tgl']))		{ $tgl 		= $sanitize->str($_REQUEST['tgl']); 			}
	if(!empty($_REQUEST['bln']))		{ $bln 		= $sanitize->str($_REQUEST['bln']); 			}
	if(!empty($_REQUEST['thn']))		{ $thn 		= $sanitize->str($_REQUEST['thn']); 			}
	if(!empty($_REQUEST['thn2']))		{ $thn2 	= $sanitize->str($_REQUEST['thn2']); 			}
	
	if(!empty($periode) && $periode == "harian"){
		$links			= $thn."-".$bln."-".$tgl;
		$condition		= "AND TGLUPDATE='".$links."'";
		$label			= "HARIAN Tanggal ".$tgl." ".$dtime->nama_bulan($bln)." ".$thn;
	}
	if(!empty($periode) && $periode == "bulanan"){
		$condition		= "AND MONTH(TGLUPDATE) = '".$bln."' AND YEAR(TGLUPDATE) = '".$thn."'";
		$label			= "BULANAN ".$dtime->nama_bulan($bln)." ".$thn;
	}
	if(!empty($periode) && $periode == "tahunan"){
		$condition		= "AND YEAR(TGLUPDATE) BETWEEN '".$thn."' AND '".$thn2."'";
		$label			= "TAHUNAN Tahun ".$thn." S/D Tahun ".$thn2;
	}	
	
	$query_str		= "SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC";
	$q_transaksi 	= $db->query($query_str);
	
	function transaction_type($parent){
		global $db;
		global $id_root;
		global $lparam;
		global $tpref;
		global $periode;
		global $link;
		global $condition;
		$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') ORDER BY ID_CASH_TYPE ASC");
		
		$jml 	= $db->numRows($qlink);
		if($jml >0){
	?>
			<ul style="list-style:none">
	<?php
			while($dt = $db->fetchNextObject($qlink)){
			$close = ""; 
			if($dt->ID_CASH_TYPE == 1 || $dt->ID_CASH_TYPE == 2){ 
				$close 			= "display:none";
			}
			$style			= "style='border-bottom:1px dashed #000000; margin-bottom:4px;'";
			$total_t		= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".@$condition."");
			?>
				<li>
					<div id='link_list' <?php echo $style; ?> >
						<p class='link1' style="float:left">
								<?php echo $dt->NAME; ?>
						</p>
						<p class='buttons1' style="float:right; margin-right:5px; <?php echo $close; ?>">
						<?php echo money("Rp.",$total_t); ?>
						</p>
					</div>		
					<br clear="all" />  
					<?php echo transaction_type($dt->ID_CASH_TYPE); ?>
				</li>
			<?php
			}
	?>
		</ul>
	<?php
		}
	}
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Detail Rekapitulasi Keuangan<br>".$label); ?>
		<div id="print_content">
        <ul id="browser" class="filetree" style="margin:0; list-style:none">
            <?php
            while($dt 	= $db->fetchNextObject($q_transaksi)){
				$close = ""; 
				if($dt->ID_CASH_TYPE == 1 || $dt->ID_CASH_TYPE == 2){ 
					$close 			= "display:none";
				}
				$style			= "style='border-bottom:1px dashed #000000; margin-bottom:4px;'";
                @$total_t		= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".@$condition."");
            ?>
                <li >
                    <div id='link_list' <?php echo $style; ?>>
                        <p class='link1' style="float:left">
                            <?php echo $dt->NAME; ?>
                        </p>
                        <p class='buttons1' style="float:right; margin-right:5px; <?php echo $close; ?>">
                                <?php echo money("Rp.",$total_t); ?>
                        </p>
                     </div>
                     <br clear="all" />
                    <?php echo transaction_type($dt->ID_CASH_TYPE); ?>
                </li>
            <?php } ?>
            <li style="background:#f6f6f6">
            	<p class='link1' style="float:left">
                	<b>TOTAL</b>
                </p>
				<?php
                $q_total 	= $db->query("SELECT SUM(PAID) JUMLAHNYA FROM ".$tpref."cash_flow WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition." ");
                $dt_total	= $db->fetchNextObject($q_total); 
                    if(empty($dt_total->JUMLAHNYA)){
                        $total = 0;	
                    }else{
                        $total = $dt_total->JUMLAHNYA;	
                    }
                ?>
                <p class='buttons1' style="float:right; margin-right:5px;">
                    <b><?php echo money("Rp.",$total); ?></b>
                </p>
                <br clear="all" />
            </li>
            <br clear="all" />
        </ul>
		</div>
		<div id="footer"><?php echo print_footer(); ?></div>
	</div>
</body>


<script language="javascript">
	window.print();
</script>
<?php } ?>