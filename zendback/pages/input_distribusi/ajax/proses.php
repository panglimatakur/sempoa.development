<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!defined('mainload')) { define('mainload','kataloku',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 				= isset($_POST['direction']) 				? $_POST['direction'] 					: "";
	$type					= isset($_POST['type']) 					? $_POST['type'] 						: "";
	$id_product_distribution= isset($_POST['id_product_distribution']) 	? $_POST['id_product_distribution'] 	: "";
	$id_product_stock		= isset($_POST['id_product_stock']) 		? $_POST['id_product_stock'] 			: "";
	$id_branch				= isset($_POST['id_branch']) 				? $_POST['id_branch'] 					: "";
	$jumlah					= isset($_POST['jumlah']) 					? $_POST['jumlah'] 						: "";
	$note					= isset($_POST['note']) 					? $_POST['note'] 						: "";
	
	if(!empty($direction)){
		$save_history	= "";
		$show_list 		= isset($_REQUEST['show_list']) 		? $sanitize->number($_REQUEST['show_list']) :"";
		$item_type 		= isset($_REQUEST['item_type']) 		? $sanitize->number($_REQUEST['item_type']) :"";

		if(!empty($direction) && $direction == "update_status"){			
			$db->query("UPDATE ".$tpref."products_distributions SET ID_DISTRIBUTION_STATUS='".$item_type."',DESCRIPTION='".$note."' WHERE ID_PRODUCT_DISTRIBUTION='".$id_product_distribution."'");
			
			$status_nm		= $db->fob("NAME",$tpref."distribution_status"," WHERE ID_DISTRIBUTION_STATUS=".$item_type."");
			$note            = "Perubahan Status Distribusi Menjadi ".$status_nm;
			send_notification($id_branch,8,$note);
				
				$q_status		= $db->query("SELECT NAME,NOTE FROM ".$tpref."distribution_status WHERE ID_DISTRIBUTION_STATUS='".$item_type."'");
				$dt_status		= $db->fetchNextObject($q_status);
				$status			= $dt_status->NAME;
				$note 			= $dt_status->NOTE;
				if($item_type == 5){
					$q_stock 	= $db->query("SELECT * FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_DISTRIBUTION='".$id_product_distribution."'");
					while($dt_stock	= $db->fetchNextObject($q_stock)){
						$id_product = $dt_stock->ID_PRODUCT;
						$stock 		= $dt_stock->STOCK;
						$prod_stock = array(1=>
										  array("ID_CLIENT",$dt_stock->ID_CLIENT),
										  array("ID_PRODUCT",$id_product),
										  array("STOCK",$stock),
										  array("ENTER_DATE",@$dt_stock->ENTER_DATE),
										  array("BY_ID_USER",$_SESSION['uidkey']),
										  array("TGLUPDATE",@$tglupdate),
										  array("WKTUPDATE",@$wktupdate));
						$db->insert($tpref."products_stocks",$prod_stock);
					}
				}else{
					$show_list	= 1;
				}
		}
		
		if(!empty($direction) && $direction == "edit_stock"){
			$q_stock 	= $db->query("SELECT ID_CLIENT,ID_PRODUCT,STOCK FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_STOCK='".$id_product_stock."'");
			$dt_stock	= $db->fetchNextObject($q_stock);
			$id_product = $dt_stock->ID_PRODUCT;
			$stock 		= $dt_stock->STOCK;
			
			$update 	= 0;
			if($jumlah < $stock){
				$new_stock	= $stock - $jumlah;
				$sets 		= "STOCK = (STOCK+".trim($new_stock).")";	
				$update 	= 1;
			}
			if($jumlah > $stock){
				$new_stock	= $jumlah - $stock;
				$sets 	= "STOCK = (STOCK-".trim($new_stock).")";	
				$update = 1;
			}
			
			if($update == 1){
			$db->query("UPDATE ".$tpref."products_stocks SET ".$sets." WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$id_client."'");	
			$db->query("UPDATE ".$tpref."products_stocks_history SET STOCK = '".$jumlah."' WHERE ID_PRODUCT_STOCK='".$id_product_stock."' AND ID_PRODUCT_DISTRIBUTION='".$id_product_distribution."' AND ID_CLIENT='".$dt_stock->ID_CLIENT."'");
			$show_list	= 1;
			}
		}
		
		if(!empty($direction) && $direction == "delete"){
			$db->delete($tpref."products_distributions"," WHERE ID_PRODUCT_DISTRIBUTION='".$id_product_distribution."'");
			$db->delete($tpref."products_stocks_history"," WHERE ID_PRODUCT_DISTRIBUTION='".$id_product_distribution."'");
		}
		
		if(!empty($direction) && $direction == "delete_single"){
			$q_stock 		= $db->query("SELECT ID_CLIENT,ID_PRODUCT,STOCK,ID_PRODUCT_DISTRIBUTION FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_STOCK='".$id_product_stock."'");
			$dt_stock		= $db->fetchNextObject($q_stock);

			$q_product		= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$dt_stock->ID_PRODUCT."'");
			$dt_product		= $db->fetchNextObject($q_product);
			$code 			= $dt_product->CODE;
			$name 			= $dt_product->NAME;

			if($type == "request")	{ $product_direction = 12; 	$label = "Permintaan"; }
			if($type == "shipping")	{ $product_direction = 9; 	$label = "Pengiriman"; }
			if(!empty($note)){			
				$new_note = "Menghapus ".$label." Produk ".$code." ".$name." Karena : ".$note;  
			}
			$db->query("UPDATE ".$tpref."products_stocks SET STOCK=(STOCK+".$dt_stock->STOCK.") WHERE ID_PRODUCT='".$dt_stock->ID_PRODUCT."' AND ID_CLIENT='".$id_client."'");
			
			$db->query("UPDATE ".$tpref."products_stocks_history SET ID_DIRECTION='".$product_direction."',NOTE='".@$new_note."' WHERE  ID_PRODUCT_STOCK='".$id_product_stock."' AND ID_CLIENT='".$dt_stock->ID_CLIENT."'");
		
			$ch_product_load = $db->recount("SELECT ID_PRODUCT_DISTRIBUTION FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_DISTRIBUTION='".$dt_stock->ID_PRODUCT_DISTRIBUTION."' AND ID_CLIENT='".$dt_stock->ID_CLIENT."' AND (ID_DIRECTION != '12' AND ID_DIRECTION != '9')");
			
			if($ch_product_load > 0){
				$db->query("UPDATE ".$tpref."products_distributions SET QUANTITY=(QUANTITY-".$dt_stock->STOCK.") WHERE ID_PRODUCT_DISTRIBUTION='".$dt_stock->ID_PRODUCT_DISTRIBUTION."' AND ID_CLIENT='".$id_client."'");
			}else{
				$db->delete($tpref."products_distributions","WHERE ID_PRODUCT_DISTRIBUTION='".$dt_stock->ID_PRODUCT_DISTRIBUTION."' AND ID_CLIENT='".$id_client."'");
				echo "delete_all";
			}
		}
			
			
			
			
		if($show_list == 1){
			$str_shipping_branch	= 
			"SELECT *
			FROM 
				".$tpref."products_distributions
			WHERE 
				(ID_BRANCH IS NOT NULL OR ID_BRANCH != '0')
				AND ID_PRODUCT_DISTRIBUTION = '".$id_product_distribution."'
			ORDER BY 
				ID_PRODUCT_DISTRIBUTION DESC";
				//echo $str_shipping_branch;
			$q_shipping_branch 		= $db->query($str_shipping_branch);

            $dt_shipping_branch = $db->fetchNextObject($q_shipping_branch);
			  	$type 			= $dt_shipping_branch->DISTRIBUTION_TYPE;
				if($type == "request")	{ 
					if($_SESSION['uclevelkey'] == 2){ 
						$fromfor = "Dari"; 
					}else{ 
						$fromfor = "Untuk";
					}
				}
										
				if($type == "shipping")	{ 
					if($_SESSION['uclevelkey'] == 2){ 
						$fromfor = "Untuk"; 
					}else{ 
						$fromfor = "Dari";
					}
				}
			
				if($_SESSION['uclevelkey'] == 2){ $target_condition = "WHERE ID_CLIENT='".$dt_shipping_branch->ID_BRANCH."'"; }
				else							{ $target_condition = "WHERE ID_CLIENT='".$dt_shipping_branch->ID_CLIENT."'"; }
				$client			= $db->fob("CLIENT_NAME",$tpref."clients",$target_condition);
				$q_status		= $db->query("SELECT NAME,NOTE FROM ".$tpref."distribution_status WHERE ID_DISTRIBUTION_STATUS='".$dt_shipping_branch->ID_DISTRIBUTION_STATUS."'");
				$dt_status		= $db->fetchNextObject($q_status);
				$status			= $dt_status->NAME;
				$note 			= $dt_status->NOTE;
				$closed			= "";
				if(($dt_shipping_branch->ID_DISTRIBUTION_STATUS == 5 && $_SESSION['uclevelkey'] == 2) || $_SESSION['uclevelkey'] != 2){ 
					$closed = 1; 
				}
          ?>
          <tr id="tr_req_<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>">
            <td style="vertical-align:top; position:relative;" >
            
        
            <span class='code'>
                <strong><?php echo $fromfor; ?></strong> : <?php echo $client; ?>
            </span>
            <br />
            <small><i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_shipping_branch->DISTRIBUTION_DATE); ?></small>
            <br />
            <div class='invoice_preview ' style='margin-top:17px;'>
                <div class="inv_notes">
                    <span class="label label-info ptip_ne" >Status : <?php echo $status; ?></span>
                    <?php if(!empty($dt_shipping_branch->DESCRIPTION)){?>
                        <?php echo $dt_shipping_branch->DESCRIPTION; ?>
                    <?php } ?>
                </div>
            </div>
            <br clear="all" />     
            
            
            <div style='border:1px solid #d5afc5; width:98%; max-height:200px; overflow:scroll;'>
                <table width="100%">
                    <thead>
                        <tr>
                          <th width="8%">&nbsp;</th>
                          <th width="46%">Code</th>
                          <th width="27%">Jumlah</th>
                          <th width="19%">&nbsp;</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php
                        $q_kolektif = $db->query("SELECT * FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_DISTRIBUTION = '".$id_product_distribution."' AND (ID_DIRECTION != '12' AND ID_DIRECTION != '9')");
                        while($dt_kolektif = $db->fetchNextObject($q_kolektif)){
                            $q_product		= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$dt_kolektif->ID_PRODUCT."'");
                            $dt_product		= $db->fetchNextObject($q_product);
                            $code 			= $dt_product->CODE;
                            $photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_product->ID_PRODUCT."'");
                            @$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_product->ID_PRODUCT_UNIT."'"); 
                            
                     ?>
                     
                        <tr id="td_<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>">
                            <td style="padding:0 10px 0 10px; ">
                               
                                <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                    <a href='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/<?php echo $photo; ?>' class="fancybox">
                                    <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="thumbnail" style="width:100%">
                                    </a>
                                <?php }else{ ?>
                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="thumbnail" style="width:100%">
                                <?php } ?>
                            </td>
                            <td>
                                <span class='code'><b><?php echo $code; ?></b></span>
                                <br />
                                <?php echo $dt_product->NAME; ?>
                            </td>
                            <td><?php echo $dt_kolektif->STOCK; ?> <?php echo $unit; ?>&nbsp;</td>
                            <td >
                                <?php if(allow('edit') == 1){ ?>
                                <a href='<?php echo $dirhost; ?>/modules/input_distribusi/ajax/catatan.php?page=input_distribusi&direction=edit&type=<?php echo $type; ?>&id_stock=<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Perbaiki <?php echo $code; ?>">
                                <i class="icsw16-pencil"></i>
                                </a>
                                <?php } ?>
                                <?php if(allow('delete') == 1){?>
                                <a href='javascript:void()' onclick="removal_single('<?php echo $type; ?>','<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>','<?php echo $q_kolektif->ID_PRODUCT_STOCK; ?>')" class="btn btn-mini" title="Hapus Pengiriman">
                                    <i class="icon-trash"></i>
                                </a>
                                <?php } ?>
                            </td>
                        </tr>
                    
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <br clear="all" />                
            </td>
            <td style="vertical-align:top; position:relative;" >
            
					<?php if(allow('edit') == 1){?>
                    <a href='<?php echo $dirhost; ?>/modules/input_distribusi/ajax/status.php?id_product_distribution=<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>&type=<?php echo $type; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Perbaiki Status Pengiriman">
                    <i class="icsw16-create-write"></i>
                    </a>
                    <?php } ?>
            </td>
          </tr>
        <?php
		}
	}
	
	if(!empty($direction) && $direction == "search_produk"){
		include $call->inc("modules/".$page."/includes","product_list.php"); 
	}
	
}
?>