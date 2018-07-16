<input type="hidden" id="basepath" value="<?php echo $dirhost; ?>/<?php echo $pos; ?>/"/>
<script language="javascript" src="<?php echo $dirhost; ?>/<?php echo $pos; ?>/pages/discoin/cart/js/js.js"></script>
<script language="javascript" src="<?php echo $dicoin_tpl_dir; ?>/js/slimscroll/jquery.slimscroll.min.js"></script>
<?php
$id_products 	 	= isset($_REQUEST['id_products']) 	? $_REQUEST['id_products'] 	: "";
$quantities 	 	= isset($_REQUEST['quantities']) 	? $_REQUEST['quantities'] 	: "";
//print_r($quantities);
?>
<div class="login-cart" style="height:500px">
    <span id="msg_spot"></span>
	<div class="col-md-6" style="position:relative;">
        
        <form method="post" action="" name="form-x">
        <div><small>NB : Tanda <label class="req"></label> wajib di isi</small></div>
        <div id="tr_form_login">
            <div class="form-group col-md-6 no-padding-l">
                <label class="req">Email</label>
                <input type="email" name="cust_email" id="cust_email" class="form-control lowercase">
            </div>
            <div class="form-group col-md-6 no-padding-l" style="display:none;" id="key_pass">
                <label class="req">Password</label>
                <input type="password" name="cust_password" id="cust_password" class="form-control">
            </div>
            <div class="form-group col-md-12 no-padding-l">
                <input type="radio" name="pelanggan" class="plg" value="baru" onclick="ch_cust(this)" checked/> Saya belum terdaftar<br />
                <input type="radio" name="pelanggan" class="plg"value="lama" onclick="ch_cust(this)"/> Saya sudah terdaftar<br />
            </div>
            <div class="form-group col-md-6 no-padding-l">
                <input type="checkbox">&nbsp;Ingat saya - <a href="#">Lupa Password?</a>
            </div>
        </div>
        <div id="tr_form_location" class="new-cust-form" style="display:none;">
            <div class="form-group col-md-6 no-padding-l">
                <label class="req">Nama Penerima</label>
                <input type="email" name="cust_name" id="cust_name" class="form-control uppercase">
            </div>
            <div class="form-group col-md-6 no-padding-l">
                <label class="req">Email Penerima</label>
                <input type="email" name="cust_email" id="cust_email" class="form-control">
            </div>
            <div class="form-group col-md-6 no-padding-l">
                <label class="req">No.HP Penerima</label>
                <input type="number" name="cust_hp" id="cust_hp" class="form-control">
            </div>
            <div class="form-group col-md-6 no-padding-l">
                <label class="req">Propinsi Penerima</label>
                <select class="form-control" id="propinsi" name="propinsi" onchange="jne_propinsi(el)">
                    <option value="">-PILIH PROPINSI--</option>
                    <?php
                    $q_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' 
											  ORDER BY NAME ASC");
                    while($dt_propinsi = $db->fetchNextObject($q_propinsi)){?>
                    <option value="<?php echo $dt_propinsi->ID_LOCATION; ?>">
						<?php echo $dt_propinsi->NAME; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <span  id="div_lokasi"></span>
            <span  id="div_delivery_type"></span>
            <span id="div_courier"></span>
            <span id="div_package"></span>
            
            <div class="clearfix"></div>
            <div class="form-group col-md-6 no-padding-l">
                <label class="req">Alamat Lengkap Penerima</label>
                <textarea name="cust_add" id="cust_add" class="form-control uppercase"></textarea>
            </div>
            <div class="form-group col-md-6 no-padding-l">
                <label>Informasi Tambahan (optional)</label>
                <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
            </div>
            
            <div class="clearfix"></div>
        </div>
        
        <div class="clearfix"></div>
                
        </form>
        
    <div class="clearfix"></div>
    </div>
    <div class="col-md-6 purchase-item-list" style="margin:0;padding:0; max-height:600px;">
        <table width="100%" class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th width="18%" style="text-align:center">Photo</th>
                  <th width="22%" style="text-align:center">Qty</th>
                  <th width="37%" style="text-align:right">Harga</th>
                  <th width="23%" style="text-align:center">Action</th>
                </tr>
            </thead>
            <tbody>
<?php		$ttl_all 		= 0;
            $quantity_all	= 0;
			$a 				= 0;
            foreach(array_reverse($_SESSION['order_id_product']) as &$order_id_product){
			$jumlah_order	= 1;
			$str_product 	= " SELECT 
                                    a.ID_CLIENT,a.CODE,a.NAME,a.SALE_PRICE,a.DISCOUNT,b.PHOTOS 
                                FROM 
                                    ".$tpref."products a,
                                    ".$tpref."products_photos b
                                WHERE 
                                    a.ID_PRODUCT = b.ID_PRODUCT AND
                                    a.ID_PRODUCT = '".$order_id_product."'
                                ORDER BY b.PHOTOS ASC";
            //echo $str_product;
            $q_product	  	= $db->query($str_product);
            $dt_product	 	= $db->fetchNextObject($q_product);
			$id_client	 	= $dt_product->ID_CLIENT;
            $nm_product	 	= $dt_product->NAME;
            $pr_price	   	= $dt_product->SALE_PRICE;
            $discount 		= $dt_product->DISCOUNT;
			$ttl_price  	= $pr_price*$jumlah_order;
?>                   
            
                <tr>
                  <td colspan="4">
                        <input type="hidden" class="list_id" value="<?php echo $order_id_product; ?>"/>
                        <small><?php echo @$dt_product->CODE; ?> - <?php echo @$dt_product->NAME; ?></small>
                  </td>
                </tr>
                <tr id="tr_<?php echo @$order_id_product; ?>">
                    <td style="text-align:center" >
                        <div class="img-thumbnail">
                        <?php if(is_file($basepath."/files/images/products/".$id_client."/".$dt_product->PHOTOS)){?>
                            <img src="<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $dt_product->PHOTOS; ?>" style="width:100%;" class="potrait">
                        <?php }else{ ?>
                            <img src="<?php echo $dirhost; ?>/files/images/no_image.jpg" 
                            	style="width:100%;" class="potrait">
                        <?php } ?>
                        </div>
                    </td>
                    <td style="text-align:center">
					<?php echo $quantities[$a]; ?>
                    <input type="hidden" id="quantity_<?php echo $order_id_product; ?>" value="<?php echo @$quantities[$a]; ?>">
                    </td>
                    <td style="text-align:right">
                        <?php echo money("",@$dt_product->SALE_PRICE); ?>
                        <input type="hidden" id="price_<?php echo $order_id_product; ?>" 
                               value="<?php echo @$dt_product->SALE_PRICE; ?>" />
                    </td>
                    <td style="text-align:center">
                        <input type="hidden" class="list_id" value="<?php echo $order_id_product; ?>"/>
                        <button type="button" value="<?php echo $order_id_product; ?>" class="btn cancel-cart" style="margin:0 6px 0 0;" title="Batalkan"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>Subtotal</td>
                    <td></td>
                    <td style="text-align:right">
                        <?php echo money("Rp.",@$ttl_price); ?>
                        <input type="hidden" id="total_price_<?php echo $order_id_product; ?>" value="<?php echo @$ttl_price[$order_id_product]; ?>" />
                    </td>
                    <td></td>
                </tr>
<?php
			$ttl_all 		= $ttl_price 		+ $ttl_all;
			$quantity_all 	= $quantities[$a] 	+ $quantity_all;
            $a++;
            }
?>	
            </tbody>
        </table>
        <div class="footer_btn" >
            <div class="pull-left">
            <button type="button" 
                    class="btn btn-primary login-btn" 
                    value="login" 
                    style="margin-top:0;" onclick="login_btn()">
                    <i class="fa fa-hand-o-right"></i> Lanjutkan
            </button>
            <button type="button" 
                    class="btn btn-primary checkout-btn" 
                    value="checkout" 
                    style="display:none;margin-top:0;">
                    <i class="fa fa-shopping-cart"></i> Checkout
            </button> 
            <span id="load_spot"></span>
            
            </div>
            <div class="pull-right" style="padding-top:5px">
                <span>
                    <b>Total :  
                        <span style="font-size:17px" id="total_belanja">
                            <?php echo money("Rp.",@$ttl_all); ?> 
                        </span> 
                        (<span id="jumlah_belanja"><?php echo @$quantity_all; ?></span> ITEM)
                    </b>
                </span>
                <input type="hidden" id="real_total_belanja" value="<?php echo @$ttl_all; ?>"/>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>

<script language="javascript">
$(document).ready(function(){
	$("#tr_form_location").slimscroll({"height":"400px"});
})
</script>