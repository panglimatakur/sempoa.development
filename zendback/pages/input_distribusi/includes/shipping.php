<?php
	if($_SESSION['uclevelkey'] == 2){
		$req_condition = "AND ID_CLIENT = '".$_SESSION['cidkey']."' ";
		
	}else{
		$req_condition = "AND ID_BRANCH = '".$_SESSION['cidkey']."' ";
	}
	$str_shipping_branch	= 
	"SELECT *
	FROM 
		".$tpref."products_distributions
	WHERE 
		(ID_BRANCH IS NOT NULL OR ID_BRANCH != '0')
		AND (ID_DISTRIBUTION_STATUS != '15' AND ID_DISTRIBUTION_STATUS != '16' AND ID_DISTRIBUTION_STATUS != '5')
		".$req_condition."
		
	ORDER BY 
		ID_PRODUCT_DISTRIBUTION DESC";
		//echo $str_shipping_branch;
	$q_shipping_branch 		= $db->query($str_shipping_branch." ".$limit);
	$num_shipping_branch	= $db->numRows($q_shipping_branch);
?>
<div class="ibox-content">

    <table width="100%" class="table table-striped" id="table_shipp">
        <thead>
            <tr>
              <th width="93%">PENGIRIMAN</th>
              <th width="7%">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr style="display:none">
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          <?php
              while($dt_shipping_branch = $db->fetchNextObject($q_shipping_branch)){ 
                $client			= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$dt_shipping_branch->ID_BRANCH."'");
				$q_status		= $db->query("SELECT NAME,NOTE FROM ".$tpref."distribution_status WHERE ID_DISTRIBUTION_STATUS='".$dt_shipping_branch->ID_DISTRIBUTION_STATUS."'");
				$dt_status		= $db->fetchNextObject($q_status);
				$status			= $dt_status->NAME;
				$note 			= $dt_status->NOTE;
          ?>
          <tr id="tr_shipp_<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>">
            <td style="vertical-align:top; position:relative;" >
            
                <span class='code'>
                    <strong>Kepada</strong> : <?php echo $client; ?>
                </span>
                <br />
                <small><?php echo $dtime->now2indodate2($dt_shipping_branch->DISTRIBUTION_DATE); ?></small>
                <br />
                <span class="label label-info ptip_ne" title="<?php echo $note; ?>">Status : <?php echo $status; ?></span>
               	<br clear="all" />     
                    
                <div style='border:1px solid #CCC; width:98%; max-height:200px; overflow:scroll; '>
					<?php if(!empty($dt_shipping_branch->DESCRIPTION)){?>
                        Catatan : <?php echo $dt_shipping_branch->DESCRIPTION; ?>
                    <?php } ?>
                    <table width="100%" >
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
                        $q_kolektif = $db->query("SELECT * FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_DISTRIBUTION = '".$dt_shipping_branch->ID_PRODUCT_DISTRIBUTION."'");
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
                                <a href='<?php echo $ajax_dir; ?>/catatan.php?page=<?php echo $page; ?>&direction=edit&type=shipping&id_stock=<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Perbaiki Pengiriman <?php echo $code; ?> ">
                            	<i class="icsw16-pencil"></i>
                                </a>
                                <?php } ?>
                                <?php if(allow('delete') == 1){?>
                                <a href='javascript:void()' onclick="removal_single('shipping','<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>','<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>')" class="btn btn-mini" title="Hapus Permintaan">
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
                    <a href='<?php echo $ajax_dir; ?>/status.php?id_product_distribution=<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>&type=shipping' class="btn btn-mini fancybox fancybox.ajax" title="Perbaiki Status Pengiriman">
                    <i class="icsw16-create-write"></i>
                    </a>
                    <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
<div id="lastPostsLoader"></div>

</div>
<div class="ibox-title" style="text-align:center">
    <?php if($num_shipping_branch > 10){?>
    <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
    <?php } ?>
    <br clear="all" />
</div>
