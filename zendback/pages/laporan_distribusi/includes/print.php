<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$export 		= isset($_REQUEST['export']) ? $_REQUEST['export'] 	: "";
	if($export == "true"){
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=laporan_distribusi.xls");	
	}
	$prompt 		= isset($_REQUEST['prompt']) ? $_REQUEST['prompt'] 	: "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
if(!empty($prompt) && $prompt == "true" && !empty($_SESSION['uidkey'])){
?>
<form id="form_print" method="post" action="<?php echo $dirhost; ?>/modules/laporan_distribusi/includes/print.php" target="_new" class="">
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
	
	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 	= $_REQUEST['tgl_1']; 									}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 	= $_REQUEST['tgl_2']; 									}
	
	if(!empty($_REQUEST['id_branch']))		{ $id_branch 		= $sanitize->number($_REQUEST['id_branch']); 	}
	if(!empty($_REQUEST['jumlah']))			{ $jumlah 			= $sanitize->number($_REQUEST['jumlah']); 		}
	if(!empty($_REQUEST['keterangan']))		{ $keterangan 		= $sanitize->str($_REQUEST['keterangan']); 		}
	if(!empty($_REQUEST['shipp_direction'])){ $shipp_direction 	= $sanitize->str($_REQUEST['shipp_direction']); }

	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 	}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 			}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 			}
	if(!empty($_REQUEST['satuan']))			{ $satuan 			= $sanitize->str($_REQUEST['satuan']); 			}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 		}
	
	$condition  = ""; 
	if( !empty($tgl_1) && 
		!empty($tgl_2))			{ 
		$tgl_1_new		= $dtime->date2sysdate($tgl_1);
		$tgl_2_new		= $dtime->date2sysdate($tgl_2);
		$condition 	.= " AND a.DISTRIBUTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; }
	if(!empty($id_kategori))	{ $condition 	.= " AND b.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
	if(!empty($code))			{ $condition 	.= " AND b.CODE 				= '".$code."' "; 			}
	if(!empty($nama))			{ $condition 	.= " AND b.NAME				LIKE '%".$nama."%' "; 			}
	if(!empty($deskripsi))		{ $condition 	.= " AND b.DESCRIPTION 		LIKE '%".$deskripsi."%' "; 		}
	if(!empty($id_branch))		{ $condition 	.= " AND a.ID_BRANCH			= '".$id_branch."'";		}
	if(!empty($jumlah))			{ $condition 	.= " AND a.QUANTITY  			= '".$jumlah."'";			}
	if(!empty($keterangan))		{ $condition 	.= " AND a.DESCRIPTION 		LIKE '%".$keterangan."%'";		}
	if(!empty($shipp_direction)){ $condition 	.= " AND a.ID_PRODUCT_DIRECTION = '".$shipp_direction."'";	}
	$str_shipping_branch	= 
	"SELECT 
		a.ID_CLIENT,
		a.ID_BRANCH,
		a.ID_PRODUCT,
		a.ID_PRODUCT_STOCK_HISTORY,
		a.QUANTITY,
		a.DESCRIPTION,
		a.DISTRIBUTION_DATE,
		a.TGLUPDATE,
		a.WKTUPDATE,
		b.CODE,
		b.NAME,
		b.ID_PRODUCT_UNIT,
		b.ID_PRODUCT
	FROM 
		".$tpref."products_distributions a,
		".$tpref."products b
	WHERE 
		a.ID_PRODUCT = b.ID_PRODUCT AND
		(a.ID_BRANCH IS NOT NULL OR a.ID_BRANCH != '0') AND
		a.ID_CLIENT = '".$_SESSION['cidkey']."'
		".$condition."
	ORDER BY 
		a.DISTRIBUTION_DATE DESC ";
	$q_shipping_branch = $db->query($str_shipping_branch." ".@$limit);
	
?>
<link href="<?php echo $templates; ?>/css/print.css" rel="stylesheet" type="text/css"/>
<body>
<div id="print_wrapper">
		<?php echo print_header("Laporan Pengiriman Stock Cabang"); ?>
		<div id="print_content">
            <table width="100%"> 
            <thead>
                <tr>
                  <th width="9%"><strong>Tanggal</strong></th>
                  <th width="13%">Cabang</th>
                  <th width="15%"><strong>Nama</strong></th>
                    <th width="7%" style='text-align:center'><strong>Jumlah</strong></th>
                    <th style='text-align:center'>Keterangan</th>
                </tr>
            </thead>
            <tbody>
              <?php
                  while($dt_shipping_branch = $db->fetchNextObject($q_shipping_branch)){ 
					$photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_shipping_branch->ID_PRODUCT."'");
					@$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_shipping_branch->ID_PRODUCT_UNIT."'"); 
					$client			= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$dt_shipping_branch->ID_BRANCH."'");
              ?>
              <tr>
                <td width="9%">
                    <?php echo $dtime->date2indodate($dt_shipping_branch->TGLUPDATE); ?>
                    <br />
                    <?php echo $dt_shipping_branch->WKTUPDATE; ?>
                </td>
                <td><?php echo $client; ?></td>
                <td>
                    <?php echo $dt_shipping_branch->CODE; ?>
                    <br />
                    <?php echo $dt_shipping_branch->NAME; ?>
                </td>
                <td style='text-align:center'><?php echo $dt_shipping_branch->QUANTITY; ?> <?php echo $unit; ?></td>
                <td width="13%"><?php echo $dt_shipping_branch->DESCRIPTION; ?></td>
              </tr>
              <?php } ?>
            </tbody>
        	</table>        
</div>
		<div id="footer"><?php echo print_footer(); ?></div>
	</div>
</body>
	<?php if(empty($export) || (!empty($export) && $export != "true")){?>
    <script language="javascript">
        window.print();
    </script>
    <?php } ?>
<?php }
}
?>
