<?php defined('mainload') or die('Restricted Access'); ?>

<?php
$notification_type_ids = array(16);
update_notification($_SESSION['cidkey'],$notification_type_ids);

function transaction_type_list_2($parent){
	global $db;
	global $id_client;
	global $id_root;
	global $lparam;
	global $tpref;
	global $condition;
	global $first_condition;
	global $status_lunas;
	$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_PARENT='".$parent."' AND (ID_CLIENT='".$_SESSION['ori_cidkey']."' OR ID_CLIENT='0') ".$first_condition." ORDER BY ID_CASH_TYPE ASC");
	$jml 	= $db->numRows($qlink);
	if($jml >0){
?>
		<ul>
<?php
		$t = 0;
		while($dt = $db->fetchNextObject($qlink)){
			$t++;
			$close = ""; 
			if($dt->IS_FOLDER == 2){ $close = "display:none"; }
			$total_t			= $db->sum("CASH_VALUE",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND ID_CLIENT='".$id_client."' ".@$condition."");
		?>
			<li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
				<div id='link_list'>
					<p class='link1' style="float:left">
						<a href="<?php echo $lparam; ?>&status_lunas=<?php echo $status_lunas; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>" class="folder">
							<?php echo $dt->NAME; ?>
						</a>
					</p>
					<p class='buttons1' style="float:right; margin-right:5px; <?php echo @$close; ?>" >
					<a href="<?php echo $lparam; ?>&status_lunas=<?php echo $status_lunas; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>"><?php echo money("Rp.",$total_t); ?></a>
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
$condition			= "";
if(!empty($tgl_1) && 
	!empty($tgl_2))					{ 
	$tgl_1_new		= $dtime->date2sysdate($tgl_1);
	$tgl_2_new		= $dtime->date2sysdate($tgl_2);
	$condition 		.= " AND TGLUPDATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 							
}


if(!empty($parent_id)){
	$ref_cond		= "";
	if(!empty($noref)){
		$ref_cond = " AND ID_CASH_FLOW=".$noref;	
	}
	$in_out 	= $db->fob("IN_OUT",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$parent_id."'");
	if($in_out == 2){ $status_lunas = "1"; }
	if($in_out == 1){ $status_lunas = "3"; }
	$condition 		= " AND PAID_STATUS='".$status_lunas."'";

	$query_str		= "SELECT * FROM ".$tpref."cash_flow WHERE ID_CASH_TYPE='".$parent_id."' AND ID_CLIENT='".$_SESSION['cidkey']."' ".$condition." ".$ref_cond." ORDER BY ID_CASH_FLOW DESC";
	$inout			= $db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE = '".$parent_id."'");
	$q_transaksi	= $db->query($query_str." ".$limit);
	$num_transaksi	= $db->numRows($q_transaksi);
	if($parent_id == 3 && $parent_id == 4){ $open_process = "open"; }	
	
}else{
	if($status_lunas == 1)	{ $in_out	= "2";	}
	else					{ $in_out	= "1"; 	}
	
	$first_condition = " AND IN_OUT='".$in_out."' ";
	$query_str = "SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ".$first_condition." ORDER BY IN_OUT ASC";
	$qlink 		= $db->query($query_str);
	$lcondition	= "?in_out=".$in_out."&tgl_1=".$tgl_1."&tgl_2=".$tgl_2;
}
?>