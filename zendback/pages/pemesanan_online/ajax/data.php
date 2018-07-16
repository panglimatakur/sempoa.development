<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	
}else{
	defined('mainload') or die('Restricted Access');
}
	$id_purchase  = isset($_REQUEST['id_purchase']) ? $_REQUEST['id_purchase'] : "";
	$condition 	= '';
	if($_SESSION['ulevelkey'] != 1 && $_SESSION['uclevelkey'] != 1){
		$condition = " AND ID_CLIENT = '".$_SESSION['cidkey']."'";
	}
	$pur_string   	= "SELECT * FROM ".$tpref."customers_purchases WHERE ID_PURCHASE 	= '".$id_purchase."' ORDER BY ID_PURCHASE ASC";
	//echo $pur_string;
	$q_purchase 	= $db->query($pur_string);
	$dt_purchase   = $db->fetchNextObject($q_purchase);

	$cart_list	 = $dt_purchase->ID_CARTS;
	$id_carts	  = explode(",",$cart_list);
	$r = 0;
	foreach($id_carts as &$id_cart){
		$id_cart 	= str_replace(";","",$id_cart);
		$cart_string   = "SELECT * FROM ".$tpref."customers_carts WHERE ID_CART = '".$id_cart."' ORDER BY ID_CART DESC"; 
		$q_cart 	   	= $db->query($cart_string);
	
?>
<table style="width:100%; border:1px solid #E5E5E5; background:#FFF" class="deal_tbl">
<?php
    $list_cart = "";
    while($dt_cart = $db->fetchNextObject($q_cart)){
        $list_cart	  .= ";".$dt_cart->ID_CART.",";
        $expiration_date= "";
        $mode		   = "";
        $hrg_product 	= "";
        $new_price  	  = "";
        $new_disc	   = "";
        $image_list	 = "";
        $image_array	= "";
        
        // JIKA ISI CART VOUCHER DISKON
        if(!empty($dt_cart->ID_DISCOUNT)){
            $id_discount 	= $dt_cart->ID_DISCOUNT;
            $q_discount 	 = $db->query("SELECT ID_CLIENT,ID_PRODUCTS,VOUCHER_NUM,EXPIRATION_DATE FROM ".$tpref."client_discounts WHERE ID_DISCOUNT ='".$id_discount."'");
            $dt_discount	= $db->fetchNextObject($q_discount);
            $expiration_date= $dt_discount->EXPIRATION_DATE;
            $id_merchant	= $dt_discount->ID_CLIENT;
            $total_voucher  = $dt_discount->VOUCHER_NUM;
            $array		  = array(";",",");
            $list_products  = $dt_discount->ID_PRODUCTS;			
            $jml_prod 	   = substr_count($list_products,";");
            
            // INFO VOUCHER DEAL //					
            $q_cust_deal	  = $db->query("SELECT PRICE,AMOUNT FROM ".$tpref."customers_carts WHERE ID_DISCOUNT = '".$id_discount."' AND STATUS = '3'");
            $dt_cust_deal	 = $db->fetchNextObject($q_cust_deal);
            @$rest_voucher	= $total_voucher - $dt_cust_deal->AMOUNT;
            if(empty($rest_voucher)){ $rest_voucher = 0; }
            @$voucher_price   = $dt_cust_deal->PRICE;
            //END OF INFO VOUCHER DEAL //
            
            $id_products	= explode(";",$list_products);
            foreach($id_products as &$id_product){
                $id_product	 = str_replace($array,"",$id_product);
                if(!empty($id_product)){
                    $product  	 	= get_product_info($id_product,"80%");
                    $image_list 	.="
                    <div class='img_box'>
                        <div class='img_box_inline'>
                            ".$product['photo']."
                        </div>
                    </div>";
                }
            }
            $mode		  = "voucher";					
        }
        // JIKA ISI CART PEMBELIAN PRODUCT
        if(!empty($dt_cart->ID_PRODUCT)){
            $id_product 	 = $dt_cart->ID_PRODUCT;
            $id_merchant    = $db->fob("ID_CLIENT",$tpref."products"," WHERE ID_PRODUCT = '".$id_product."'");
            $product        = get_product_info($id_product,"80%");
            $unit_product   = $product['unit'];
            //$discount_pr	= $product['discount'];
            if(!empty($hrg_product)){
                $new_disc	   = ($hrg_product/100)*$dt_cart->DISCOUNT;
                $new_price	   = $hrg_product-$new_disc;
            }
            $image_list   .="
            <div class='img_box'>
                <div class='img_box_inline'>
                    ".$product['photo']."
                </div>
            </div>";
            $jml_prod	  = 1;
            $mode		  = "product";
        }
        $hrg_product	= $dt_cart->PRICE;
        if(!empty($hrg_product)){
            $new_disc	   = ($hrg_product/100)*$dt_cart->DISCOUNT;
            $new_price	   = $hrg_product-$new_disc;
        }
        @$nm_merchant	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_merchant."'");
        //INFORMASI PRODUCT		
?>
  <tr id="div_<?php echo $dt_cart->ID_CART; ?>">
    <td>
        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-striped">
            <tr>
                <td colspan ='2' class="ibox-title" >
                    <div style="font-weight:100; line-height:15px; font-size:2vmin;">
                        <?php echo ucwords(strtolower($product['name'])); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="10%"  align="center" valign="top" style="padding:4px; " class="overflow">
                     <?php echo $image_list; ?>
                     
                </td>
                <td width="90%"  class="overflow" style="padding-left:5px; vertical-align:top">
                    <ul class="content">
                        <li>
                            Merchant
                            <div class='value'><?php echo $nm_merchant; ?>&nbsp;</div>
                        </li>
                        <?php if(!empty($expiration_date) && $expiration_date != "0000-00-00"){?>
                            <li>
                                Masa Berlaku
                                <div class='value'>
                                    <b class='code'>Hingga : <?php echo $dtime->now2indodate2($expiration_date); ?></b>
                                </div>
                            </li>
                        <?php }?>
                        <?php if(!empty($dt_cart->DISCOUNT)){ ?>
                        <li>
                            Diskon (%)
                            <div class='value'><?php echo $dt_cart->DISCOUNT; ?> %</div>
                        </li>
                        <?php } ?>
                        <li>
                            Harga
                            <div class='value'>
                            <?php if(!empty($dt_cart->DISCOUNT)){ ?>
                                <span style="text-decoration:line-through" class="code"><?php echo money("Rp.",$hrg_product); ?></span> &nbsp;<br />
                                <?php } ?>
                                <b><?php echo money("Rp.",$new_price); ?></b>
                            </div>
                        </li>
                        <li>
                            Jumlah Dibeli
                            <div class='value'>
                                <span id="current_deal">
									<?php echo @$dt_cart->AMOUNT; ?>
                                </span>
                                <?php if($mode == "voucher"){ ?>
                                    / <?php echo $rest_voucher; ?> Voucher
                                    <?php }else{ ?>
                                    <?php echo @$unit_product; ?>
                                <?php } ?>
                            </div>
                        </li>
                        <li>
                            Total Bayar
                            <div class='value'>
                                <span class="code " id="new_pay"><?php echo money("Rp.",@$dt_cart->TOTAL_PRICE); ?></span>
                            </div>
                        </li>
                    </ul>
              </td>
            </tr>
        </table>
    </td>
  </tr>
<?php } ?>
</table>
	<?php } ?>
