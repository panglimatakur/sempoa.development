<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($bln)){ $bln = date('m'); }
if(empty($thn)){ $thn = date('Y'); }

$condition			= "";
if(!empty($code))			{ $condition 		.= " AND b.CODE 				= '".$code."' "; 			}
if(!empty($harga))			{ $condition 		.= " AND a.PRICE				= '".$harga."'";			}
if(!empty($diskon))			{ $condition 		.= " AND a.DISCOUNT 			= '".$diskon."'";			}
if(!empty($id_type_report))	{ $condition 		.= " AND b.ID_PRODUCT_TYPE 		= '".$id_type_report."' "; 	}
if(!empty($id_kategori))	{ $condition_cat 	.= " AND b.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
if(!empty($lunas))			{ $condition 		.= " AND c.PAID_STATUS 			= '".$lunas."' "; 			}

$field_geo 	= " a.PROVINCE AS ID_PARENT,a.CITY  AS ID_LOCATION,";
$group_geo 	= " AND a.PROVINCE='".$propinsi."' GROUP BY a.PROVINCE,a.CITY";
$cond_geo	= "";
$label_geo	= "";

if(!empty($propinsi) && empty($kota))		{ 
	$cond_geo	= " AND a.PROVINCE='".$propinsi."'";
	$label_geo	= "PROPINSI ".$propinsi;
}
if(!empty($kota) && empty($kecamatan))		{ 
	$field_geo 	= " a.PROVINCE,a.CITY AS ID_PARENT,a.DISTRICT AS ID_LOCATION,"; 
	$group_geo 	= " GROUP BY a.DISTRICT";
	$cond_geo	= " AND a.PROVINCE='".$propinsi."' AND a.CITY='".$kota."'";
	$label_geo	= " KOTA ".$kota;
}
if(!empty($kecamatan) && empty($kelurahan))	{ 
	$field_geo 	= "a.PROVINCE,a.CITY,a.DISTRICT AS ID_PARENT,a.SUBDISTRICT AS ID_LOCATION,"; 
	$group_geo 	= " GROUP BY a.PROVINCE,a.CITY,a.DISTRICT,a.SUBDISTRICT";
	$cond_geo	= " AND a.PROVINCE='".$propinsi."' AND a.CITY='".$kota."' AND a.DISTRICT='".$kecamatan."'";
	$label_geo	= "KECAMATAN ".$kecamatan;
}
if(!empty($kecamatan) && empty($kelurahan))	{ 
	$cond_geo	= " AND a.PROVINCE='".$propinsi."' AND a.CITY='".$kota."' AND a.DISTRICT='".$kecamatan."' AND a.SUBDISTRICT='".$kelurahan."'";
	$label_geo	= "KELURAHAN ".$kelurahan;
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
	$condition_periode 	= " AND MONTH(c.TRANSACTION_DATE) = ".$bln." AND YEAR(c.TRANSACTION_DATE) = ".$thn." ";	
}

if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
	$parameter			= array(1=>1,2,3,4,5,6,7,8,9,10,11,12);
	$ticks_src			= array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	$ticks				= "";
	$label				= "TAHUN ".$thn;
	$jml_data			= count($parameter);
	$s 					= 0;
	$t 					= 0;
	$condition_periode 	= " AND YEAR(c.TRANSACTION_DATE) = ".$thn." ";	
}

if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
	$jml_data			= $thn2;
	$ticks				= "";
	$label				= "TAHUN ".$thn." S/D TAHUN ".$thn2;
	$s 					= $thn;
	$t 					= 0;
	$condition_periode 	= " AND YEAR(c.TRANSACTION_DATE) BETWEEN ".$thn." AND ".$thn2."";	
}

$query_str	= "SELECT 
					a.ID_PRODUCT_SALE,
					SUM(a.QUANTITY) AS QUANTITY,
					SUM(c.PAID) AS PAID
			   FROM 
			   		".$tpref."products_sales a,
			   		".$tpref."products b,
					".$tpref."factures c 
			   WHERE 
				a.ID_PRODUCT = b.ID_PRODUCT AND 
				a.ID_FACTURE = c.ID_FACTURE AND 
				c.MODULE = 'SALE' AND
				(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") ".$condition." ".$cond_geo." ";

//CHART PENJUALAN//
$data_jual		= "";
while($s<$jml_data){
	$s++;
	$t++;
	
	if(empty($periode) || (!empty($periode) && $periode == "harian" && !empty($bln) && !empty($thn))){
		if(strlen($t) == 1){ $t='0'.$t; }
		$penjualan_cond[$s] = "AND DAY(c.TRANSACTION_DATE) = '".$t."' AND MONTH(c.TRANSACTION_DATE) = '".$bln."' AND YEAR(c.TRANSACTION_DATE) = '".$thn."'";
	}
	
	if(!empty($periode) && $periode == "bulanan" && !empty($thn)){
		if(strlen($t) == 1){ $t='0'.$t; }
		$penjualan_cond[$s] = "AND MONTH(c.TRANSACTION_DATE) = '".$t."' AND YEAR(c.TRANSACTION_DATE) = '".$thn."' ";
	}
	
	if(!empty($periode) && $periode == "tahunan" && !empty($thn) && !empty($thn2)){
		$penjualan_cond[$s] = "AND YEAR(c.TRANSACTION_DATE) = '".$s."'";
		$parameter[$s]		= $s;
		$ticks_src[$s]		= $s;
	}
	$q_penjualan			= $db->query($query_str." ".@$condition_cat." ".$penjualan_cond[$s]);
	$dt_total_penjualan		= $db->fetchNextObject($q_penjualan);
	$data_penjualan[$s] 	= @$dt_total_penjualan->PAID;
	
	if(empty($data_penjualan[$s])){ $data_penjualan[$s] = 0; }
	$data_jual 	.= '['.$parameter[$s].','.$data_penjualan[$s].'],';
	$ticks	   	.= '['.$parameter[$s].',"'.$ticks_src[$s].'"],';
}

$data_1 = '
{
	  label: "Total Penjualan",
	  data: ['.$data_jual.'],
	  lines: { show: true,lineWidth: 2 },
	  points: 
	  {
			show: true,
			radius: 3,
			symbol: "circle",
			fill: true,
			colors: "#FFCC00"
	  }
}';
//END OF CHART PENJUALAN//
?>

<?php 
//CHART MARKETING / SALES
$a = 0;
$q_marketing 	= $db->query("SELECT * FROM system_users_client WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ORDER BY ID_USER DESC");
$jml_marketing	= $db->numRows($q_marketing);
$q_max_paid		= $db->query("
			   SELECT 
			   		MAX(MAXIMAL) AS HIGHEST,a.ID_SALES
			   FROM(
					   SELECT 
							SUM(c.PAID) AS MAXIMAL,a.ID_SALES
					   FROM 
							".$tpref."products_sales a,
							".$tpref."products b,
							".$tpref."factures c 
					   WHERE 
						a.ID_PRODUCT = b.ID_PRODUCT AND 
						a.ID_FACTURE = c.ID_FACTURE AND 
						c.MODULE = 'SALE' AND
						(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").")
						".$condition." ".@$condition_cat." ".@$condition_periode." GROUP BY a.ID_SALES
					)
				a GROUP BY a.ID_SALES ORDER BY HIGHEST DESC 
				");
$dt_max_paid	= $db->fetchNextObject($q_max_paid);
@$max_paid		= $dt_max_paid->HIGHEST+1000000;

while($dt_marketing = $db->fetchNextObject($q_marketing)){
	$a++;
	$q_marketing_sale			= $db->query($query_str." ".@$condition_cat." ".@$condition_periode." AND a.ID_SALES='".$dt_marketing->ID_USER."'");
	$dt_total_marketing_sale	= $db->fetchNextObject($q_marketing_sale);
	$total_sale[$a]				= @$dt_total_marketing_sale->PAID;
	$nm_marketing[$a] 			= $dt_marketing->USER_NAME;
}
//END OF CHART MARKETING / SALES
?>


<?php
//CHART PRODUCT TREND
$query_kat		= "SELECT * FROM ".$tpref."products_categories WHERE ID_PRODUCT_TYPE='1' ORDER BY NAME DESC ";
$q_kategori 	= $db->query($query_kat);
$c 				= 0;
$data_pie		= "";
$color_pie		= "";
$query_str		= str_replace(",SUM(c.PAID) AS PAID","",$query_str);
while($dt_kategori = $db->fetchNextObject($q_kategori)){
	$q_product = $db->query($query_str." ".@$condition_periode." AND b.ID_PRODUCT_CATEGORY = '".$dt_kategori->ID_PRODUCT_CATEGORY."'");
	$dt_product= $db->fetchNextObject($q_product);
	$result['label'] 	= $dt_kategori->NAME;
	$result['data']		= $dt_product->QUANTITY;
	if($result['data'] > 0){
		$c++;
		$data_pie .= '{"label":"'.$result['label'].'","data":'.$result['data'].'},';
		$color_pie.= "\"".random_color()."\",";
	}
}
//END OF CHART PRODUCT TREND
?>

<?php
//CHART GEOGRAFIS TREND
$query_geo			= "SELECT ".$field_geo."SUM(a.QUANTITY) AS JUMLAH FROM ".$tpref."products_sales a WHERE a.CITY !='' ".$cond_geo." ".$group_geo."";
$q_geografi 		= $db->query($query_geo);
$c 					= 0;
$data_geografi		= "";
$color_geografi		= "";
while($dt_geografi = $db->fetchNextObject($q_geografi)){
	$result['label'] 	= @$db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_geografi->ID_LOCATION."' AND PARENT_ID='".$dt_geografi->ID_PARENT."'");
	$result['data']		= $dt_geografi->JUMLAH;
	if($result['data'] > 0){
		$c++;
		$data_geografi .= '{"label":"'.$result['label'].'","data":'.$result['data'].'},';
		$color_geografi.= "\"".random_color()."\",";
	}
}
//END OF CHART GEOGRAFIS TREND
?>