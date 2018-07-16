<?php defined('mainload') or die('Restricted Access'); ?> 
<div> 
<?php 
	$total_jual = $dt_sale->SUMMARY; 
?>
<table width="100%" id="rt2"  class="rt cf">
    <thead class="cf">
         <tr>
           <th width="17%">Total </th>
           <th width="14%">Status</th>
           <th width="20%">No PO</th>
           <th width="20%">Bayar</th>
           <th width="19%">Sisa</th>
         </tr>
    </thead>
    <tbody>
         <tr>
           <td><?php echo money("Rp.",$total_jual); ?></td>
           <td><?php echo @$paid_status; ?></td>
           <td><?php if(!empty($dt_sale->PO_NUMBER)){ echo $dt_sale->PO_NUMBER; }  ?>&nbsp;</td>
           <td><?php if(!empty($dt_sale->PAID)){ echo money("Rp.",$dt_sale->PAID);}else{ echo "0"; }  ?></td>
           <td><?php if(!empty($dt_sale->REMAIN)){ echo money("Rp.",$dt_sale->REMAIN); }else{ echo "0"; }?></td>
         </tr>
    </tbody>
</table>
</div>
<br clear="all">
<div style='border:1px solid #e8dbe3; width:98%; max-height:100px; overflow:scroll; position:absolute;'>
    <table width="100%" id="rt2" class="rt cf">
    <thead class="cf">
        <tr>
          <th width="5%">&nbsp;</th>
          <th width="17%">Code</th>
          <th width="12%">Harga</th>
          <th width="18%">Jumlah</th>
          <th width="18%">Diskon</th>
          <th width="17%">Total</th>
          <th width="13%">&nbsp;</th>
        </tr>
     </thead>
     <tbody>
     <?php
        $q_kolektif = $db->query("SELECT * FROM ".$tpref."products_sales WHERE ID_FACTURE = '".$dt_sale->ID_FACTURE."'");
        while($dt_kolektif = $db->fetchNextObject($q_kolektif)){
		  	$q_product		= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$dt_kolektif->ID_PRODUCT."'");
           	$dt_product		= $db->fetchNextObject($q_product);
			$code 			= $dt_product->CODE;
		    $photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_product->ID_PRODUCT."'");
            @$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_product->ID_PRODUCT_UNIT."'"); 
     ?>
     
        <tr id="td_<?php echo $dt_kolektif->ID_PRODUCT_SALE; ?>">
        	<td style="padding:0 10px 0 10px; ">
               
                <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                    <a href='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/<?php echo $photo; ?>' class="fancybox">
                    <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="thumbnail" style="width:100%">
                    </a>
                <?php }else{ ?>
                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="thumbnail" style="width:100%">
                <?php } ?>
            </td>
            <td><?php echo $code; ?>&nbsp;</td>
            <td><?php echo money("Rp.",$dt_kolektif->PRICE); ?>&nbsp;</td>
            <td><?php echo $dt_kolektif->QUANTITY; ?> <?php echo $unit; ?>&nbsp;</td>
            <td><?php echo $dt_kolektif->DISCOUNT; ?>%&nbsp;</td>
            <td><?php echo money("Rp.",$dt_kolektif->TOTAL); ?>&nbsp;</td>
            <td >
                <?php if(allow('edit') == 1){?>
                <a href="modules/input_penjualan_produk/ajax/product_list.php?page=input_penjualan_produk&direction=edit&no=<?php echo $dt_kolektif->ID_PRODUCT_SALE; ?>" class="btn btn-mini fancybox fancybox.ajax" title="Edit">
                    <i class="icon-pencil"></i>
                </a>
                <?php } ?>
                <?php if(allow('delete') == 1){?>
                <a href='javascript:void()' onclick="removal_single('<?php echo $dt_sale->ID_CASH_FLOW; ?>','<?php echo $dt_kolektif->ID_PRODUCT_SALE; ?>')" class="btn btn-mini" title="Delete">
                    <i class="icon-trash"></i>
                </a>
                <?php } ?>
            </td>
        </tr>
    
        <?php }?>
    </tbody>
    </table>
</div>
<div style="height:90px; clear:both"></div>
