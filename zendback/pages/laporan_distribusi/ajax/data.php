<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
	session_start();
	if(!empty($_SESSION['cidkey'])){
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
	$id_type_report = isset($_REQUEST['id_type_report']) 	? $_REQUEST['id_type_report'] : "";
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');	
}

if((!empty($display) && $display == "kategori_report") || !empty($id_type_report)){
    $query_kategori_report 	= $db->query("SELECT * FROM ".$tpref."products_categories WHERE ID_PRODUCT_TYPE='".$id_type_report."' AND ID_PARENT = '0' AND ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY NAME ASC");
	$num_kategori_report 	= $db->numRows($query_kategori_report);
	if($num_kategori_report > 0){
?>
<div class="form-group form-control">
    <label>Kategori</label>
    <input type="hidden" name="id_kategori" id="id_kategori" class="form-control mousetrap" value="<?php echo @$id_kategori; ?>">
    <ul class="kategori_list">
        <?php
        while($data_kategori = $db->fetchNextObject($query_kategori_report)){
            $class_selected = "";
            if(!empty($id_kategori) && $id_kategori == $data_kategori->ID_PRODUCT_CATEGORY){
                $class_selected = "class='class_selected' style='border:1px solid #F9ECF7;'";	
            }
        ?>	
            <li id="cat_<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>" <?php echo @$class_selected; ?>>
                <img src="<?php echo $dirhost; ?>/files/images/icons/bullet_go.png" />
                <a href='javascript:void()' onclick="select_category('<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>')">
                    <?php echo $data_kategori->NAME; ?>
                </a>
                <?php echo category_list($data_kategori->ID_PRODUCT_CATEGORY); ?>
            </li>
        <?php } ?>
    </ul>
</div>
<?php 
	}
} 
?>

<?php
if((!empty($display) && $display == "list_report")){
	@$lastID 	= $_REQUEST['lastID'];
	if(!empty($_REQUEST['id_branch']))		{ $id_branch 		= $sanitize->number($_REQUEST['id_branch']); 		}
	if(!empty($_REQUEST['keterangan']))		{ $keterangan 		= $sanitize->str($_REQUEST['keterangan']); 			}
	if(!empty($_REQUEST['shipp_direction'])){ $shipp_direction 	= $sanitize->number($_REQUEST['shipp_direction']); 	}
	
	if(!empty($id_branch))		{ $condition 	.= " AND ID_BRANCH				= '".$id_branch."'";				}
	if(!empty($keterangan))		{ $condition 	.= " AND DESCRIPTION 			LIKE '%".$keterangan."%'";			}
	if(!empty($shipp_direction)){ $condition 	.= " AND ID_DISTRIBUTION_STATUS = '".$shipp_direction."'";			}
	
	$str_shipping_branch	= 
	"SELECT *
	FROM 
		".$tpref."products_distributions
	WHERE 
		(ID_BRANCH IS NOT NULL OR ID_BRANCH != '0')
		".$condition."
	ORDER BY 
		ID_PRODUCT_DISTRIBUTION DESC";
	$q_shipping_branch = $db->query($str_shipping_branch." 0,10");

	  while($dt_shipping_branch = $db->fetchNextObject($q_shipping_branch)){ 
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
			<table width="100%" >
			<thead>
				<tr>
				  <th width="7%">&nbsp;</th>
				  <th width="47%">Code</th>
				  <th width="27%">Jumlah</th>
				  <th width="19%">&nbsp;</th>
				</tr>
			 </thead>
			 <tbody>
			 <?php
				$q_kolektif = $db->query("SELECT * FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_DISTRIBUTION = '".$dt_shipping_branch->ID_PRODUCT_DISTRIBUTION."' AND (ID_DIRECTION != '12' AND ID_DIRECTION != '9')");
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
						<?php if(allow('edit') == 1 && empty($closed)){ ?>
						<a href='<?php echo $dirhost; ?>/modules/input_distribusi/ajax/catatan.php?page=input_distribusi&direction=edit&type=<?php echo $type; ?>&id_stock=<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Perbaiki Permintaan <?php echo $code; ?>">
						<i class="icsw16-pencil"></i>
						</a>
						<?php } ?>
						<?php if(allow('delete') == 1 && empty($closed)){?>
						<a href='javascript:void()' onclick="removal_single('request','<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>','<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>')" class="btn btn-mini" title="Hapus Pengiriman">
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
			<?php if(allow('edit') == 1 && empty($closed)){?>
			<a href='<?php echo $dirhost; ?>/modules/input_distribusi/ajax/status.php?id_product_distribution=<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>&type=<?php echo $type; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Ubah Status Permintaan">
			<i class="icsw16-create-write"></i>
			</a>
			<?php } ?>
	</td>
  </tr>
  <?php } 
}
?>