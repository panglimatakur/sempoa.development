<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";

	function transaction_type_list_2($parent){
		global $db;
		global $id_client;
		global $tpref;
		global $condition;
		global $first_condition;
		$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['ori_cidkey']."' OR ID_CLIENT='0') ".$first_condition." ORDER BY ID_CASH_TYPE ASC");
		$jml 	= $db->numRows($qlink);
		if($jml >0){
	?>
			<ul>
	<?php
			$t = 0;
			while($dt = $db->fetchNextObject($qlink)){
			$t++;
			$total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND ID_CLIENT='".$id_client."' ".@$condition."");
			?>
				<li >
					<div id='link_list'>
						<p class='link1' style="float:left">
								<?php echo $dt->NAME; ?>
						</p>
						<p class='buttons1' style="float:right; margin-right:5px;">
						<?php echo money("Rp.",$total_t); ?>
						</p>
					</div>		
					<br clear="all" />  
					<?php echo transaction_type_list_2($dt->ID_CASH_TYPE); ?>
				</li>
			<?php
			}
	?>
		</ul>
	<?php
		}
	}
	$in_out				=	isset($_REQUEST['in_out']) 		? $_REQUEST['in_out']	:"";
	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}
	$condition			= "";
	if(!empty($tgl_1) && 
		!empty($tgl_2))					{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 		.= " AND TGLUPDATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 							
	}
	if($in_out == 2){ $label = "HUTANG"; }
	if($in_out == 1){ $label = "PIUTANG"; }
	$first_condition 	= " AND IN_OUT='".$in_out."' ";
	$query_str 			= "SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ".$first_condition." ORDER BY IN_OUT ASC";
	$qlink 				= $db->query($query_str);
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
	<?php echo print_header("Laporan Sejarah ".$label." Keuangan "); ?>
    <div id="print_content">
        <ul class="general_ledger">
            <?php
            $t = 0;
            while($dt 	= $db->fetchNextObject($qlink)){
                $t++;
                $mox = $t%2;
				if($dt->ID_CASH_TYPE == 1 || $dt->ID_CASH_TYPE == 2){ 
					$close 			= "display:none";
				}
                $total_t			= $db->sum("PAID",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND ID_CLIENT='".$_SESSION['cidkey']."' ".@$condition."");
            ?>
                <li>
                    <div id='link_list'>
                        <p class='link1' style="float:left">
                            <?php echo $dt->NAME; ?>
                        </p>
                        <p class='buttons1' style="float:right; margin-right:5px; <?php echo $close; ?>">
							<?php echo money("Rp",$total_t); ?>
                        </p>
                     </div>
                     <br clear="all" />
                    <?php echo transaction_type_list_2($dt->ID_CASH_TYPE); ?>
                </li>
            <?php } ?>	
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