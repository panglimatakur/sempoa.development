<?php
session_start(); 
if(!empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");

if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}

if(empty($tgl_1)){ 
	$tgl_1_new 	= date("d/m/Y"); 
	$tgl_1_ex	= explode("-",$dtime->yesterday(7,date("d"),date("m"),date("Y")));
	$tgl_1		= $tgl_1_ex[2]."/".$tgl_1_ex[1]."/".$tgl_1_ex[0];
} 
if(empty($tgl_2)){ 
	$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
	$tgl_2_new 		=  date("d/m/Y", $dateformat);
	$tgl_2			= date("d/m/Y");
} 

$condition	= "";
if(!empty($tgl_1) && 
	!empty($tgl_2))					{ 
	$tgl_1_new		= $tgl_1;
	$tgl_2_new		= $tgl_2;
	$condition 		.= " AND TGLUPDATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 		
}


$qlink 		= $db->query("SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC");
$total_all	= $db->sum("PAID",$tpref."cash_flow"," WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".$condition."");
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<?php
function transaction_type($parent){
	global $db;
	global $tpref;
	global $condition;
	$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') ORDER BY ID_CASH_TYPE ASC");
	
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
		<ul>
<?php
		$t = 0;
		while($dt = $db->fetchNextObject($qlink)){
		$t++;
            $close = ""; 
            if($dt->ID_CASH_TYPE == 1 || $dt->ID_CASH_TYPE == 2){ 
                $close 			= "display:none";
            }
		$total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".@$condition."");
		?>
			<li>
				<div id='link_list'>
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

<div id="print_wrapper">
		<?php echo print_header("Laporan Keuangan ".$dtime->now2indodate2($tgl_1_new)." s/d ".$dtime->now2indodate2($tgl_2_new)); ?>
		<div id="print_content">
            <ul class="general_ledger">
                <?php
                $t = 0;
                while($dt 	= $db->fetchNextObject($qlink)){
                    $t++;
					$close = ""; 
					if($dt->ID_CASH_TYPE == 1 || $dt->ID_CASH_TYPE == 2){ 
						$close 			= "display:none";
					}
                    $total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ".@$condition."");
                ?>
                    <li>
                        <div id='link_list'>
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
                <li>
                    <div id='link_list'>
                        <p class='link1' style="float:left; font-weight:900">TOTAL</p>
                        <p class='buttons1' style="float:right; margin-right:5px; font-weight:900">
                                <?php echo money("Rp.",@$total_all); ?>
                        </p>
                     </div>
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