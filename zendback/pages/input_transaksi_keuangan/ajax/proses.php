<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 	= isset($_POST['direction']) 	? $sanitize->str($_POST['direction']) 		: "";
	$id_root	=	isset($_REQUEST['id_root']) ? $sanitize->number($_REQUEST['id_root'])	:"";
	$no 		= isset($_POST['no']) 			? $sanitize->number($_POST['no']) 			: "";
	if(!empty($direction) && $direction == "delete"){ 
		$db->delete($tpref."cash_type","WHERE ID_CASH_TYPE='".$no."'");
		$db->delete($tpref."cash_type","WHERE ID_PARENT='".$no."'");
	}	
	if(!empty($direction) && $direction == "insert"){ 
			$tgl_bayar 		= isset($_REQUEST['tgl_bayar']) 	? $sanitize->str($_REQUEST['tgl_bayar']) 			: "";
			if(!empty($tgl_bayar)){
				$tgl_bayar		= $dtime->indodate2date(@$tgl_bayar);
			}

			$parent_id 		= isset($_REQUEST['parent_id']) 	? $sanitize->number($_REQUEST['parent_id']) 		: "";
			$cash_value 	= isset($_REQUEST['cash_value']) 	? $sanitize->number($_REQUEST['cash_value']) 		: "";
			$sumber 		= isset($_REQUEST['sumber']) 		? $sanitize->number($_REQUEST['sumber']) 			: "";
			$keterangan 	= isset($_REQUEST['keterangan']) 	? $sanitize->str($_REQUEST['keterangan']) 			: "";
			$lunas 			= isset($_REQUEST['lunas']) 		? $sanitize->number($_REQUEST['lunas'])				:""; 
			$downpay 		= isset($_REQUEST['downpay']) 		? $sanitize->number($_REQUEST['downpay'])			:""; 
			$termin 		= isset($_REQUEST['termin']) 		? $sanitize->number($_REQUEST['termin']) 			: "";
			$kredit 		= isset($_REQUEST['kredit']) 		? $sanitize->number($_REQUEST['kredit'])			:""; 
			$tgl_tempo_multi= isset($_REQUEST['tgl_tempo_multi']) ? $_REQUEST['tgl_tempo_multi']	:""; ;
			$total			= $cash_value;
			if($lunas != 2){
				if(!empty($downpay)){
					$total 	= $downpay;
					$kredit	= $cash_value-$downpay;
				}else{
					$total   = 0;
					$kredit	= $cash_value;
				}
			}
			
			$terms   = $termin+1;
			$content = array(1=>
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("ID_CASH_TYPE",$parent_id),
						array("CASH_VALUE",@$cash_value),
						array("PAID",@$total),
						array("REMAIN",@$kredit),
						array("PAID_STATUS",@$lunas),
						array("ID_CASH_SOURCE",@$sumber),
						array("TERMS",@$terms),
						array("NOTE",@$keterangan),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("TGLUPDATE",$tglupdate),
						array("WKTUPDATE",$wktupdate)
						);
			$db->insert($tpref."cash_flow",$content);
			$id_cash_flow 	= mysql_insert_id();
			if($id_root == 1){
				$cash_residual_value = $cash+$total;
				$id_status_lunas	= 3;
			}else{
				$cash_residual_value = $cash-$total;
				$id_status_lunas	= 1;
			}
			
			if($lunas != 2){
				insert_decre($id_cash_flow,$lunas,"+",$cash_value,$cash_value,$tgl_bayar);	
				if(!empty($downpay)){
					insert_decre($id_cash_flow,$lunas,"-",$total,$kredit,$tgl_bayar);	
				}
				if(!empty($downpay)){ 
					$content = array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CASH_FLOW",$id_cash_flow),
								array("ORDINAL","1"),
								array("DEBT_CREDIT",$id_status_lunas),
								array("STATUS","1"),
								array("REMINDER_DATE",$tgl_bayar));
					$db->insert($tpref."debt_credit_reminder",$content);
					$id_ordinal = 1;
				}
				if(empty($id_ordinal)){ $id_ordinal = 0; }
				foreach($tgl_tempo_multi as &$tgl_tempo){
					if(!empty($tgl_tempo)){
						$id_ordinal++;
						$tgl_tempo		= $dtime->indodate2date(@$tgl_tempo);
						$content = array(1=>
									array("ID_CLIENT",$_SESSION['cidkey']),
									array("ID_CASH_FLOW",$id_cash_flow),
									array("ORDINAL",$id_ordinal),
									array("DEBT_CREDIT",$id_status_lunas),
									array("STATUS","0"),
									array("REMINDER_DATE",$tgl_tempo));
						$db->insert($tpref."debt_credit_reminder",$content);
					}
				}
			}
			$id_direction		 = 13;
			$content = array(1=>
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("ID_CASH_TYPE",$parent_id),
						array("CASH_VALUE",@$cash_value),
						array("PAID",@$total),
						array("REMAIN",@$kredit),
						array("PAID_STATUS",@$lunas),
						array("CASH_RESIDUAL_VALUE",@$cash_residual_value),
						array("ID_CASH_SOURCE",@$sumber),
						array("NOTE",@$keterangan),
						array("TERMS",@$termin),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("ID_DIRECTION",$id_direction),
						array("TGLUPDATE",$tglupdate),
						array("WKTUPDATE",$wktupdate)
						);
			$db->insert($tpref."cash_flow_history",$content);
	?>
        <div class='alert alert-success' style="margin:0">
        	Data Transaksi Berhasil Di Simpan
        </div>
    <?php			
	}
	
	if(!empty($direction) && $direction == "add_source"){ 
		$nama 		= isset($_POST['nama']) ? $sanitize->str(ucwords($_POST['nama'])) : "";
		$content = array(1=>
					array("ID_CLIENT",$_SESSION['cidkey']),
					array("NAME",$nama));
		$db->insert($tpref."cash_sources",$content);
	?>
    <div class="category" id='id_cat'>
        <select name="sumber" id="sumber" class="col-md-6 validate[required] text-input">
            <option value=''>--SUMBER DANA--</option>
            <?php 
                $q_src = $db->query("SELECT * FROM ".$tpref."cash_sources WHERE ID_CLIENT='".$_SESSION['cidkey']."'"); 
                while($dt_src = $db->fetchNextObject($q_src)){
            ?>
                <option value='<?php echo $dt_src->ID_CASH_SOURCE; ?>' <?php if(@$sumber == $dt_src->ID_CASH_SOURCE){ ?> selected <?php } ?>>
                    <?php echo $dt_src->NAME; ?>
                </option>
            <?php } ?>
         </select>
        <a href="javascript:void()" class='btn new_cat' style="margin:-12px 0 0 0">
            <i class="icon-plus"></i>Tambah Sumber Dana
        </a>
    </div>
    <?php
	}
	
}
else{  defined('mainload') or die('Restricted Access'); }
?>
