<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($bln)){ $bln = date('m'); }
if(empty($thn)){ $thn = date('Y'); }


$condition_cat	= "";
$debcre = "Hutang Piutang";	
if(!empty($parent_id)){
	$condition_cat	= " AND ID_CASH_TYPE='".$parent_id."'";
	$in_out 		= $db->fob("IN_OUT",$tpref."cash_type"," WHERE ID_CASH_TYPE='".$parent_id."'");
	if($in_out == 1){
		$debcre = "Piutang";	
	}else{
		$debcre = "Hutang";	
	}
}
//CHART PENJUALAN
if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
	$parameter			= array(1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
	$ticks_src			= array(1=>"01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31");
	$ticks				= "";
	$label				= "BULAN ".$dtime->nama_bulan($bln)." TAHUN ".$thn;
	$jml_data			= $dtime->daysamonth($bln,$thn);
	$s 					= 0;
	$t 					= 0;
	$condition_periode 	= " AND MONTH(TGLUPDATE) = ".$bln." AND YEAR(TGLUPDATE) = ".$thn." ";	
}

if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
	$parameter			= array(1=>1,2,3,4,5,6,7,8,9,10,11,12);
	$ticks_src			= array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$ticks				= "";
	$label				= "TAHUN ".$thn;
	$jml_data			= count($parameter);
	$s 					= 0;
	$t 					= 0;
	$condition_periode 	= " AND YEAR(TGLUPDATE) = ".$thn." ";	
}

if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
	$jml_data			= $thn2;
	$ticks				= "";
	$label				= "TAHUN ".$thn." S/D TAHUN ".$thn2;
	$s 					= $thn;
	$t 					= 0;
	$condition_periode 	= " AND YEAR(TGLUPDATE) BETWEEN ".$thn." AND ".$thn2."";	
}

$query_str	= "SELECT 
					SUM(PAID) AS PAID,
					SUM(REMAIN) AS REMAIN
			   FROM 
			   		".$tpref."cash_flow
			   WHERE 
			   		ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"")." ";

//echo $query_str." ".@$condition_cat." ".@$condition_periode."";
//CHART PENJUALAN//
$data_jual		= "";
$data_debcre	= "";

while($s<$jml_data){
	$s++;
	$t++;
	
	if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
		if(strlen($t) == 1){ $t='0'.$t; }
		$penjualan_cond[$s] = "AND DAY(TGLUPDATE) = '".$t."' AND MONTH(TGLUPDATE) = '".$bln."' AND YEAR(TGLUPDATE) = '".$thn."'";
	}
	
	if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
		if(strlen($t) == 1){ $t='0'.$t; }
		$penjualan_cond[$s] = "AND MONTH(TGLUPDATE) = '".$t."' AND YEAR(TGLUPDATE) = '".$thn."' ";
	}
	
	if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
		$penjualan_cond[$s] = "AND YEAR(TGLUPDATE) = '".$s."'";
		$parameter[$s]		= $s;
		$ticks_src[$s]		= $s;
	}
	$q_penjualan			= $db->query($query_str." ".@$condition_cat." ".$penjualan_cond[$s]);
	//echo $query_str." ".@$condition_cat." ".$penjualan_cond[$s]."<br>";
	$dt_total_penjualan		= $db->fetchNextObject($q_penjualan);
	$data_penjualan[$s] 	= @$dt_total_penjualan->PAID;
	$data_hutpiut[$s] 		= @$dt_total_penjualan->REMAIN;
	
	if(empty($data_penjualan[$s]))	{ $data_penjualan[$s] 	= 0; 	}
	if(empty($data_hutpiut[$s]))	{ $data_hutpiut[$s] 	= 0; 	}
	
	$data_jual 		.= '['.$parameter[$s].','.$data_penjualan[$s].'],';
	$data_debcre 	.= '['.$parameter[$s].','.$data_hutpiut[$s].'],';
	
	$ticks	   		.= '['.$parameter[$s].',"'.$ticks_src[$s].'"],';
}


$data['ticks']		 = $ticks;
$data['content'] = '
{
	  label: "Riwayat Keuangan",
	  data: ['.$data_jual.'],
	  lines: { show: true,lineWidth: 2,fill: true },
	  points: 
	  {
			show: true,
			radius: 3,
			symbol: "circle",
			fill: true,
			colors: "#FFCC00"
	  }
},{
	  label: "Riwayat '.@$debcre.'",
	  data: ['.$data_debcre.'],
	  lines: { show: true,lineWidth: 2,fill: true },
	  points: 
	  {
			show: true,
			radius: 3,
			symbol: "circle",
			fill: true,
			colors: "#666666"
	  }
}';

$qlink 	= $db->query("SELECT * FROM ".$tpref."cash_type WHERE (ID_CLIENT='".$_SESSION['cidkey']."' OR ID_CLIENT='0') AND ID_PARENT='0' ORDER BY IN_OUT ASC");
?>