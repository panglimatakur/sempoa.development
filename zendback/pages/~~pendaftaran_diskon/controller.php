<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$id_products 	= isset($_REQUEST['id_products'])   ? $sanitize->number($_REQUEST['id_products']) 	: 0;
	$id_diskon 		= isset($_REQUEST['id_diskon'])   	? $sanitize->number($_REQUEST['id_diskon']) 	: "";
	$formember 		= isset($_REQUEST['formember'])		? $sanitize->str($_REQUEST['formember'])		: "";
	$besar 			= isset($_REQUEST['besar']) 	   	? $sanitize->number($_REQUEST['besar'])			: 0;
	$expiration 	= isset($_REQUEST['expiration'])	? $_REQUEST['expiration'] 						: "";
	$keterangan 	= isset($_REQUEST['keterangan'])	? $sanitize->str($_REQUEST['keterangan']) 		: "";
	$pattern 		= isset($_REQUEST['pattern']) 	   	? $sanitize->number($_REQUEST['pattern']) 		: 0;
	$nilai 			= isset($_REQUEST['nilai']) 	   	? $sanitize->str($_REQUEST['nilai']) 			: "";
	$sifat_jual 	= isset($_REQUEST['sifat_jual']) 	? $sanitize->str($_REQUEST['sifat_jual']) 		: 0;
	$num_kupon 		= isset($_REQUEST['num_kupon']) 	? $sanitize->number($_REQUEST['num_kupon']) 	: 0;
	$harga_kupon 	= isset($_REQUEST['harga_kupon']) 	? $sanitize->number($_REQUEST['harga_kupon']) 	: 0;
	$sifat_jual_valid = "true";
	
	if($sifat_jual == "preorder"){
		if(!empty($num_kupon) && !empty($harga_kupon))	{ $sifat_jual_valid = "true"; 	}  
		else											{ $sifat_jual_valid = "false"; 	}  
	}
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		
		
		@$product_list = count($_REQUEST['id_products']);
		//&& !empty($sifat_jual) && $sifat_jual_valid == "true"
		if(!empty($formember) && !empty($besar) && 
		   !empty($pattern)  &&
		   ((($product_list == 0 && !empty($nilai)) || $product_list > 0))){			
				
				if($besar >= 10){
				
					
					if(!empty($product_list) && $product_list > 1){
						$nilai  = "";
						foreach($id_products as &$id_product){
							$nilai .= ";".$id_product.",";
							//$db->query("UPDATE ".$tpref."products SET DISCOUNT = '".$besar."' WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_PRODUCT = '".$id_product."'");	 
						}
					}
					if(!empty($expiration)){ $disc_expiration	= $dtime->indodate2date($expiration); }
					
					if(!empty($direction) && $direction == "insert"){
						$container 	= array(1=>
											array("ID_CLIENT",$_SESSION['cidkey']),
											array("ID_DISCOUNT_PATTERN",@$pattern),
											array("DISCOUNT",@$besar),
											array("DISCOUNT_VALUE",@$nilai),
											array("DISCOUNT_UNIT","%"),
											array("DISCOUNT_STATEMENT",@$keterangan),
											array("DISCOUNT_SEGMENT",$formember),
											array("DISCOUNT_STATUS","3"),
											array("EXPIRATION_DATE",@$disc_expiration),
											array("SELLING_METHOD",@$sifat_jual),
											//array("SELLING_METHOD_PO_COUPON_QTY",@$num_kupon),
											//array("SELLING_METHOD_PO_COUPON_PRICE",@$harga_kupon),
											array("UPDATEDATETIME",$tglupdate." ".$wktupdate));
						$db->insert($tpref."clients_discounts",$container);
						redirect_page($lparam."&msg=1");
						
					}
					
					if(!empty($direction) && $direction == "save")	{
						$container 	= array(1=>
											array("ID_CLIENT",$_SESSION['cidkey']),
											array("ID_DISCOUNT_PATTERN",@$pattern),
											array("DISCOUNT",@$besar),
											array("DISCOUNT_VALUE",@$nilai),
											array("DISCOUNT_UNIT","%"),
											array("DISCOUNT_STATEMENT",@$keterangan),
											array("DISCOUNT_SEGMENT",$formember),
											array("DISCOUNT_STATUS","3"),
											array("EXPIRATION_DATE",@$disc_expiration),
											array("SELLING_METHOD",@$sifat_jual),
											//array("SELLING_METHOD_PO_COUPON_QTY",@$num_kupon),
											//array("SELLING_METHOD_PO_COUPON_PRICE",@$harga_kupon),
											array("UPDATEDATETIME",$tglupdate." ".$wktupdate));
						$db->update($tpref."clients_discounts",$container," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_DISCOUNT='".$id_diskon."'");
						redirect_page($lparam."&msg=1");
							
					}
				
				
				}else{
					$msg = "3"; 
				}
				
		}else{
			$msg = "2"; 
		}
	}
?>