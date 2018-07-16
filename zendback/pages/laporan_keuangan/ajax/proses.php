<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 	= isset($_POST['direction']) 	? $sanitize->str($_POST['direction']) 		: "";
	$no 		= isset($_POST['no']) 			? $sanitize->number($_POST['no']) 			: "";
	if(!empty($direction) && $direction == "delete"){ 
		$id_direction		 = 15;
		$cash_residual_value = delete_cash($no,$id_direction);
		echo $cash_residual_value;
	}
	
	if(!empty($direction) && $direction == "save"){ 
		$id_root 				= isset($_POST['id_root']) 				? $sanitize->number($_POST['id_root']) 				: "";
		$parent_id 				= isset($_POST['parent_id']) 			? $sanitize->number($_POST['parent_id']) 			: "";
		$cash_value 			= isset($_POST['cash_value']) 			? $sanitize->number($_POST['cash_value']) 			: "";
		$sumber 				= isset($_POST['sumber']) 				? $sanitize->number($_POST['sumber']) 				: "";
		$keterangan 			= isset($_POST['keterangan']) 			? $sanitize->str($_POST['keterangan']) 				: "";
		$first_value 			= isset($_POST['first_value']) 			? $sanitize->number($_POST['first_value']) 			: "";
		$status_lunas 			= isset($_POST['status_lunas']) 		? $sanitize->number($_POST['status_lunas']) 		: "";
		$downpay 				= isset($_POST['downpay']) 				? $sanitize->number($_POST['downpay']) 				: "";
		$kredit 				= isset($_POST['credit']) 				? $sanitize->number($_POST['credit']) 				: "";
		$first_credit 			= isset($_POST['first_credit']) 		? $sanitize->number($_POST['first_credit']) 		: "";

		$termin 				= isset($_REQUEST['termin']) 			? $sanitize->number($_REQUEST['termin']) 			:"";
		$tgl_tempo 				= isset($_REQUEST['tgl_tempo']) 		? $_REQUEST['tgl_tempo'] 							:"";
		$st_termin 				= isset($_REQUEST['st_termin']) 		? $sanitize->str($_REQUEST['st_termin']) 			:"";

		$jumlah_all 			= isset($_POST['jumlah_all']) 			? $sanitize->number($_POST['jumlah_all']) 			: "";
		$total_all 				= isset($_POST['total_all']) 			? $sanitize->number($_POST['total_all']) 			: "";
		$remain_all 			= isset($_POST['remain_all']) 			? $sanitize->number($_POST['remain_all']) 			: "";
		
		if($status_lunas != 2){
			if(!empty($downpay)){
				$paid = $downpay;
			}else{
				$paid   = 0;
				$kredit	= $cash_value;
			}
		}else{
			$paid = $cash_value;	
		}
		$id_direction			= 14;
		$continue 				= 1;
		if($id_root == 1){
			$tgl_update 	= $db->fob("TGLUPDATE",$tpref."cash_flow","WHERE ID_CASH_FLOW='".$no."'");
			$str_income		= "   SELECT 
									SUM(a.CASH_VALUE) AS INCOME
								  FROM 
									".$tpref."cash_flow a,
									".$tpref."cash_type b 
								  WHERE 
									b.IN_OUT = '1' AND 
									a.ID_CASH_TYPE=b.ID_CASH_TYPE AND 
									b.TGLUPDATE >= ".$tgl_update."";
			$q_income		= $db->query($str_income);
			$dt_income		= $db->fetchNextObject($q_income);
			$income_after	= $dt_income->INCOME;
			
			$str_outcome	= "	  SELECT 
									SUM(a.CASH_VALUE) AS OUTCOME
								  FROM 
									".$tpref."cash_flow a,
									".$tpref."cash_type b 
								  WHERE 
									b.IN_OUT = '2' AND 
									a.ID_CASH_TYPE=b.ID_CASH_TYPE AND 
									b.TGLUPDATE >= ".$tgl_update."";
			$q_outcome		= $db->query($str_outcome);
			$dt_outcome		= $db->fetchNextObject($q_outcome);
			$outcome_after	= $dt_outcome->OUTCOME;

			$due_cash		= ($income_after-$first_value)+$cash_value;	
			
			if(($outcome_after < $due_cash) || ($outcome_after == $due_cash)){
				$cash_residual_value 	= $due_cash-$outcome_after;	
				$continue 				= 2;
			}
			if($outcome_after > $due_cash){
				$continue 				= 1;	
				$cash_residual_value	= $outcome_after-$due_cash;
				$result['note'] 		= 
				"<p>
					Maaf, perubahan ini tidak dapat di proses, dikarenakan kas yang telah di keluarkan terhitung dari <b style='color:#289BF0'>".$dtime->now2indodate2($tgl_update)."</b> (Tanggal Transaksi <b style='color:#289BF0'>TR".$no."</b> dilakukan) melebihi jumlah kas masuk.
				</p>
					<br>
				<p>
					Karena pengaruh dari nilai uang yang anda perbaiki sekarang menyebabkan total kas masuk menjadi 
					<b style='color:#CC0000'>".money("Rp.",$due_cash)."</b>, dimana total pengeluaran kas anda sebesar 
					<b style='color:#CC0000'>".money("Rp.",$outcome_after)."</b>
				</p>
					<br>
				<p>
					Segera lakukan pengisian kas sebesar <b style='color:#CC0000'>".money("Rp.",$cash_residual_value)."</b> atau lebih, lalu lakukan perbaikan kembali, Terimakasih
				</p>";
				
			}
		}else{
			$new_cash						= $cash+$first_value;
			if(!empty($cash_value) && $cash_value <= $new_cash){
				$cash_residual_value		= $new_cash-$cash_value;
				$continue 					= 2;
			}else{
				$continue 					= 1;
				$result['note'] 			= 
				"<p>
					Maaf, perubahan ini tidak dapat di proses, dikarenakan nilai transaksi yang anda masukan melebihi nilai kas terakhir sebesar <b style='color:#CC0000'>".money("Rp.",$cash)."</b>
				</p>";
			}
		}
		$result['io']					= $continue;	
		
		if(!empty($continue) && $continue == 2){
			$tr = save_cash($no,@$sumber,@$cash_value,@$paid,$id_direction);
			$db->delete($tpref."debt_credit_reminder"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$no."' AND STATUS = '0'");
			$tempo 			= 0;
			$tgl_tempos 	= substr_count($tgl_tempo,";");
			$tgl_tempo_edit = explode(";",$tgl_tempo);
			$st_termins		= explode(";",$st_termin);
			$tgl_temponya ="";
			
			while($tempo < $tgl_tempos){
				$tempo++;
				if($st_termins[$tempo] != "1"){
				$tgl_tempo	= "";
				$tgl_tempo	= $dtime->indodate2date(@$tgl_tempo_edit[$tempo]);
				$content 	= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("ID_CASH_FLOW",$no),
								array("DEBT_CREDIT",$status_lunas),
								array("ORDINAL",$tempo),
								array("STATUS","0"),
								array("REMINDER_DATE",$tgl_tempo));
				$db->insert($tpref."debt_credit_reminder",$content);
				}
			}
			if($status_lunas == "2"){
				$paid_status = "LUNAS";	
			}else{
				if($status_lunas == "1"){
					$paid_status = "HUTANG";
					
				}else{
					$paid_status = "PIUTANG";
				}
			}
			$new_total_all 					= ($total_all - $first_value) + $paid;
			$new_remain_all					= ($remain_all - $first_credit) + $kredit;
			$result['note']					= "<div class='alert alert-success'>Data Berhasil Disimpan</div>";
			$result['id_cash_flow'] 		= $no;
			$result['cash_value_label'] 	= money("Rp.",$cash_value);
			$result['cash_value'] 			= $cash_value;
			$result['status_lunas_label'] 	= $paid_status;
			$result['paid_value_label'] 	= money("",$paid);
			$result['credit_value_label'] 	= money("",$kredit);
			$result['balance_label']		= "Balance : ".money("Rp.",$tr);
			$result['balance']				= $tr;
			
			$result['new_total_all']		= $new_total_all;
			$result['new_total_all_label']	= money("Rp.",$new_total_all);
			$result['new_remain_all']		= $new_remain_all;
			$result['new_remain_all_label']	= money("Rp.",$new_remain_all);
		}
		echo json_encode($result);
	}
	
	if(!empty($direction) && $direction == "add_source"){ 
		$nama 		= isset($_POST['nama']) ? $sanitize->str(ucwords($_POST['nama'])) : "";
		$content = array(1=>
					array("ID_CLIENT",$_SESSION['cidkey']),
					array("NAME",$nama));
		$db->insert($tpref."cash_sources",$content);
	?>
    <div class="category" id='id_cat'>
        <select name="sumber" id="sumber" class="col-md-3 validate[required] text-input">
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
