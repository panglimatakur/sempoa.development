<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../includes/config.php");
	include_once("../../../includes/classes.php");
	include_once("../../../includes/functions.php");
	include_once("../../../includes/declarations.php");
	$id_product 	 = isset($_REQUEST['id_product']) 	? $_REQUEST['id_product'] 	: "";
	$jumlah_order 	 = isset($_REQUEST['jumlah_order']) ? $_REQUEST['jumlah_order'] : "1";
	?>
    <style type="text/css">
		#contact-pop	{ width:700px; 			}
		.cart_content	{ background:#FFF;  	} 
		.check_out		{ margin-left:40px;		}
		@media only screen and (max-width: 368px) {
			#contact-pop{
				width:auto; 
			}
		}
	</style>
    <?php
	function client_address($id_merchant){
		global $db;
		global $tpref;
		$q_loc 		 		 = $db->query("SELECT CLIENT_PROVINCE,CLIENT_CITY,CLIENT_DISTRICT,CLIENT_SUBDISTRICT,CLIENT_ADDRESS FROM ".$tpref."clients WHERE ID_CLIENT = '".$id_merchant."'");
		$dt_loc				= $db->fetchNextObject($q_loc);
		$result['propinsi']   	= $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_PROVINCE."'");
		$result['kota']   		= $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_CITY."' AND PARENT_ID = '".$dt_loc->CLIENT_PROVINCE."'");
		$result['kecamatan']   = $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_DISTRICT."' AND PARENT_ID = '".$dt_loc->CLIENT_CITY."'");
		$result['kelurahan']   = $db->fob("NAME","system_master_location","WHERE ID_LOCATION = '".$dt_loc->CLIENT_SUBDISTRICT."' AND PARENT_ID = '".$dt_loc->CLIENT_DISTRICT."'");
		$result['alamat']   = $dt_loc->CLIENT_ADDRESS;
		return $result;
	}
	function discount($id_merchant){
		global $db;
		global $tpref;
		$discount = $db->fob("VALUE",$tpref."client_discounts"," WHERE ID_DISCOUNT_PATTERN = '1' AND COMMUNITY_FLAG = '1'");	
		return $discount;
	}


	if(!empty($_SESSION['customer_id'])){?>
    
	<?php if(!empty($id_product)){
        $q_product	  	= $db->query("SELECT ID_CLIENT,NAME,SALE_PRICE FROM ".$tpref."products WHERE ID_PRODUCT = '".$id_product."'");
        $dt_product	 	= $db->fetchNextObject($q_product);
        $id_merchant 	= $dt_product->ID_CLIENT;
        $nm_product	 	= $dt_product->NAME;
        $pr_price	   	= $dt_product->SALE_PRICE;
        
        if(!empty($coin_number) && $num_coin > 0){
            $discount 		= discount($id_merchant);
            if(!empty($discount)){
                $disc_price	= ($pr_price/100)*discount($id_merchant);
                $pr_price	= $pr_price-$disc_price;
            }
        }			
        $ttl_price  	= $pr_price*$jumlah_order;

        $ch_order 	 = $db->recount("SELECT ID_PRODUCT FROM ".$tpref."customers_carts WHERE ID_PRODUCT = '".$id_product."' AND ID_CUSTOMER = '".$_SESSION['customer_id']."' AND STATUS = '0'");	
        if($ch_order == 0){
            $container 		= array(1=>
                                    array("ID_CLIENT",$id_merchant),
                                    array("ID_PRODUCT",$id_product),
                                    array("ID_CUSTOMER",$_SESSION['customer_id']),
                                    array("AMOUNT",$jumlah_order),
                                    array("PRICE",@$pr_price),
                                    array("DISCOUNT",@$discount),
                                    array("TOTAL_PRICE",@$ttl_price),
                                    array("STATUS","0"),
                                    array("TGLUPDATE",$tglupdate." ".$wktupdate));
            $db->insert($tpref."customers_carts",$container);
        ?>
        <script type="text/javascript">
            /*jml 		= $("#badge_cart").html();
            new_jml	= +jml + 1;
            $("#badge_cart").html(new_jml);*/
        </script>
        <?php 
        }
    } ?>
 
    <?php 
    $total_bayar = $db->sum("TOTAL_PRICE",$tpref."customers_carts"," WHERE  ID_CUSTOMER = '".$_SESSION['customer_id']."' AND STATUS = '0'");
    
    $cart_string   	= "SELECT * FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$_SESSION['customer_id']."'  AND STATUS = 0 ORDER BY ID_CART DESC";
    //echo $cart_string;
    $q_cart 	   	= $db->query($cart_string);
    $num_cart		= $db->numRows($q_cart); ?>
    <div class='cart_content'></div>
    <div class="table-responsive cart_info">
    <input type="hidden" id="proses_page_cart" value="<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/ajax/proses.php"/>
    
      <?php if($num_cart > 0){?>
      <table class="table" id="responsive-example-table" style="width:98%; margin:9px ">
            <thead>
                <tr class="cart_menu">
                    <td width="25%" align="center" class="image">Item</td> 
                    <td width="19%" class="description">Deskripsi</td>
                    <td width="16%" align="right" class="price">Harga</td>
                    <td width="24%" align="center" class="quantity">Jumlah</td>
                    <td width="8%" align="right" class="total">Total</td>
                    <td width="8%" align="right" class="total">&nbsp;</td>
                </tr>
            </thead>
            <tbody>
            <?php
            
            while($dt_cart = $db->fetchNextObject($q_cart)){
                $hrg_product	= $dt_cart->PRICE;
                $total_hrg		= $dt_cart->TOTAL_PRICE;
                $id_merchants	= $dt_cart->ID_CLIENT;
                $jumlah_order	= $dt_cart->AMOUNT;
                if(empty($jumlah_order)){ $jumlah_order = 1; } 
                @$nm_merchant	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_merchant."'");
                // JIKA ISI CART PEMBELIAN PRODUCT
                if(!empty($dt_cart->ID_PRODUCT)){
                    $id_product 	= $dt_cart->ID_PRODUCT;
                    $product        = get_product_info($id_product,"60%");
                    $unit_product   = $product['unit'];
                    $real_price 	= $product['price'];
                }
                $sub_total 			= $hrg_product * $jumlah_order;
                //INFORMASI PRODUCT		
        ?>
                <tr class="tcart_<?php echo $dt_cart->ID_CART; ?> ">
                    <td align="center" class="cart_product" style="margin:0">
                        <p>Kode Item : <?php echo $product['code']; ?></p>
                        <?php if(!empty($product['image']) && is_file($basepath."/files/images/products/".$id_merchants."/thumbnails/".$product['image'])){ ?>
                       
                            <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_merchants; ?>/thumbnails/<?php echo $product['image']; ?>' width="60%"/>
                        <?php }else{ ?>
                            <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' width="60%"/>
                        <?php } ?>
                        <input type="hidden" class="id_cart" value='<?php echo $dt_cart->ID_CART; ?>' />
                    </td>
                    <td valign="top" class="cart_description">
                      <a href=""><?php echo $product['name']; ?></a>
                      <?php echo $product['description']; ?>
                  </td>
                    <td align="right" valign="top" class="cart_price"> 
                      <p><?php if(!empty($dt_cart->PRICE)){ echo money("Rp.",@$hrg_product); } ?></p>
                        <input type="hidden" class="price_<?php echo $id_product; ?>" id="price_<?php echo $id_product; ?>" value="<?php echo $hrg_product; ?>" />
                    </td>
                    <td valign="top" class="cart_quantity">
                        <div class="cart_quantity_button">
                            <a class="cart_quantity_up" href="javascript:void()" data-id="<?php echo $id_product; ?>"> + </a>
                            <input class="cart_quantity_input jumlah_order_<?php echo $id_product; ?>" type="text" id="jumlah_order_<?php echo $id_product; ?>" value="<?php echo @$jumlah_order; ?>" size="3" > 
                            <a class="cart_quantity_down" href="javascript:void()" data-id="<?php echo $id_product; ?>"> - </a>
                        </div>
                    </td>
                    <td align="right" valign="top" class="cart_total">
                        <p class="cart_total_price cart_total_price_<?php echo $id_product; ?>">
                            <?php echo money("Rp.",$total_hrg); ?>
                        </p>
                        <input type="hidden" class="ttl_price_<?php echo $id_product; ?> ttl_price_sum" id="ttl_price_<?php echo $id_product; ?>" value="<?php echo $total_hrg; ?>" />
                    </td>
                    <td align="right" valign="top" class="cart_total">&nbsp;</td>
              </tr>
              <thead class="tcart_<?php echo $dt_cart->ID_CART; ?>">
              <tr class="cart_menu">
                <td colspan="6">
                    <button class="btn btn-default cancel_cart" value='<?php echo $dt_cart->ID_CART; ?>'>
                        <i class="fa fa-trash-o"></i> Batal
                    </button>
                    <span class="cancel_carting_<?php echo $dt_cart->ID_CART; ?>" ></span>
                </td>
              </tr>
              </thead>
            <?php } ?>
            </tbody>
        </table>
        
        <?php } ?>
        <section id="do_action">
            <div class="col-sm-10">
              
              <div class="total_area">
                <div class='purchase_loader'></div>
                <ul>
                    <li>Shipping Cost <span>Free</span></li>
                    <li>Total : <span id="xum_all"> <?php echo money("Rp.",$total_bayar); ?></span></li>
                    <input type="hidden" id="total_all" value="<?php echo $total_bayar; ?>"/>
               </ul>
                <button class="btn btn-default check_out" id="check_out" href="javascript:void()">
                    <i class="fa fa-check-circle"></i> Ke Pembayaran
                </button>
              </div>
            </div>
        </section><!--/#do_action-->
        <?php include $call->lib("tblresponsive"); ?>
        <script>
          $('#responsive-example-table').stacktable({myClass:'stacktable small-only'});
        </script>
    </div>
    
    
<?php 
	}else{?>
<div class="features_items" id="contact_pop" style="padding:10px;" ><!--features_items-->
  <input type="hidden" id="regist_page" value="<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/ajax/proses.php">
    <div class="contact-form">
        <h2 class="title text-center" style="margin-bottom:3px;">Masukan Email Anda</h2>
        <br />
      <span id="load_new_email"></span>
        <form id="main-contact-form" class="contact-form row" name="contact-form" method="post">
          <div class="form-group col-md-9" style="text-align:center">
                <input type="text" id="vis_nama" class="form-control" required placeholder="Masukan Nama Anda">
            </div>
            <div class="form-group col-md-9" style="text-align:center">
              <input type="text" id="vis_email" class="form-control" required placeholder="Masukan Email Anda">
            </div>
            <div class="form-group col-md-9" style="text-align:center">
                <input type="password" id="vis_pass" class="form-control" required placeholder="Masukan Kata Sandi">
            </div>
          <div class="form-group col-md-3">
                <button type="button" id="new_customer" class="btn btn-primary" style="margin:0" value="<?php echo $dirhost; ?>/zendfront/pages/discoin/cart/page.php?id_product=<?php echo $id_product; ?>">Lanjut Belanja</button>
            </div>
        </form>
    </div>
</div>
	<?php }
} ?>
