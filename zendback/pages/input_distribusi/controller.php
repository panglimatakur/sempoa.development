<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$id_product_multi	= isset($_REQUEST['id_product']) 		? $_REQUEST['id_product']		:"";
$jumlah_multi 		= isset($_REQUEST['jumlah_multi']) 		? $_REQUEST['jumlah_multi']								:""; 
$tgl_kirim_multi 	= isset($_REQUEST['tgl_kirim_multi']) 	? $_REQUEST['tgl_kirim_multi']							:""; 
if(!empty($tgl_kirim_multi)){
	$tgl_kirim_multi		= $dtime->indodate2date(@$tgl_kirim_multi);
}
$id_dest_multi		= isset($_REQUEST['id_dest_multi']) 	? $sanitize->number($_REQUEST['id_dest_multi'])		:""; 
$keterangan_multi 	= isset($_REQUEST['keterangan_multi']) 	? $_REQUEST['keterangan_multi']							:""; 
$status_send_multi 	= isset($_REQUEST['status_send_multi']) ? $sanitize->number($_REQUEST['status_send_multi'])			:""; 


if(!empty($direction) && $direction == "insert_multi"){
	$jml_produk = count($id_product_multi);
		
	if(!empty($id_dest_multi) && !empty($status_send_multi) && $jml_produk > 0){		
		$product_direction	= "7";
		$e 					= 0;
		
		if($_SESSION['uclevelkey'] == 2){ 
			$id_origin 	= $_SESSION['cidkey']; 
			$id_branch  = $id_dest_multi; 	
			$nm_branch	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_branch."'");	
			$type 		= "shipping"; 
			$note       = "Pengiriman atau Perubahan Status Pengiriman Stock Oleh ".$_SESSION['cname']." Kepada ".$nm_branch;
			send_notification($id_dest_multi,$product_direction,$note);
		}
		else{ 
			$id_origin 	= $id_client; 			
			$id_branch 	= $_SESSION['cidkey']; 	
			$type 		= "request"; 
			$note       = "Permintaan Stock Oleh ".$_SESSION['cname']." ";
			send_notification($id_client,$product_direction,$note);
		}
		
		
		$prod_distribute 	= array(1=>
								  array("ID_CLIENT",$id_origin),
								  array("ID_BRANCH",@$id_branch),
								  array("QUANTITY",@$jumlah_multi),
								  array("DESCRIPTION",@$keterangan_multi),
								  array("DISTRIBUTION_DATE",@$tgl_kirim_multi),
								  array("ID_DISTRIBUTION_STATUS",$status_send_multi),
								  array("DISTRIBUTION_TYPE",$type),
								  array("ID_DIRECTION",$product_direction),
								  array("BY_ID_USER",$_SESSION['uidkey']),
								  array("TGLUPDATE",@$tglupdate),
								  array("WKTUPDATE",@$wktupdate));
		$db->insert($tpref."products_distributions",$prod_distribute);  
		$id_distribution	= mysql_insert_id();
				
		foreach($id_product_multi as &$item){
			
			$quantity 				= isset($_REQUEST['jumlah']) 		? $_REQUEST['jumlah']			:""; 	

		$db->query("UPDATE ".$tpref."products_stocks SET STOCK = (STOCK-".$quantity[$e]."),ENTER_DATE='".$tgl_kirim_multi."',BY_ID_USER='".$_SESSION['uidkey']."',TGLUPDATE='".$tglupdate."',WKTUPDATE='".$wktupdate."' WHERE ID_PRODUCT = '".$item."' AND ID_CLIENT='".$id_origin."'");

			$prod_stock_his = array(1=>
							  array("ID_CLIENT",$id_branch),
							  array("ID_PRODUCT_DISTRIBUTION",$id_distribution),
							  array("ID_PRODUCT",@$item),
							  array("STOCK",$quantity[$e]),
							  array("ENTER_DATE",@$tgl_kirim_multi),
							  array("ID_DIRECTION",@$product_direction),
							  array("BY_ID_USER",$_SESSION['uidkey']),
							  array("TGLUPDATE",@$tglupdate),
							  array("WKTUPDATE",@$wktupdate));
			$db->insert($tpref."products_stocks_history",$prod_stock_his);
			$e++;
		}
		redirect_page($lparam."&msg=3");
	}else{
		$msg = 2;	
	}
}
?>