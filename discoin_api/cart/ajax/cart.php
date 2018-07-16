<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['sidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../../includes/config.php");
		include_once("../../../../../includes/classes.php");
		include_once("../../../../../includes/functions.php");
		include_once("../../../../../includes/declarations.php");
		include_once("../../../includes/config.php");
		include_once('../../../includes/declarations.php');
		$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] : "";
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
if((!empty($display) && $display == "show_order")){?>

<style type="text/css">
.deal_tbl td.overflow{
	max-height:80px;
	overflow:scroll;	
}
ul.content{
	margin-bottom:10px;
	position:relative;
	list-style:none;
	margin:0;
	border-radius:3px;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
}
ul.content li{
	height:auto;
	font-weight:bold;
}
ul.content li .value{ font-weight:100; margin:0; padding:0 0 0 15px; width:90%; border:1px dashed #CCCCCC; }
</style>
<script language="javascript">
function cancel_this(id_deal){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	proses_page 	 = $("#proses_page_cart").val();
	$("#v_loader_"+id_deal).html('<img src="'+conf.dirhost+'/files/images/loader_v.gif"><br>');
	$.ajax({
		url	 : proses_page,
		type 	: "POST",
		data	: {"direction":"cancel_deal","id_deal":id_deal},
		success	: function(response){
			result = JSON.parse(response);
			$("#new_ttl_pay").html(result.ttl_cart);
			$("#total_bayar").val(result.ttl_cart_data);
			$("#div_"+id_deal).slideUp(200);
			jml 		= $("#badge_cart").html();
			new_jml	= +jml - 1;
			$("#badge_cart").html(new_jml);
		}
	})
}
function cancel_voucher(){
	reset_money();
	$("#total_biaya,#total_jarak").val("");
	$("#city_loader,#kec_loader,#kab_loader ,#kurir_loader").empty();	
}

function reset_money(){
	total_bayar = $("#total_bayar").val();
	newmoney = accounting.formatMoney(total_bayar,"Rp.",2,".",","); // €4.999,99
	ori_money_cap = "<b>Total Belanja : <span class='code' >"+newmoney+"</span></b>";	
	$("#new_total").html(ori_money_cap);
}


function save_jml(id,id_deal){
	besar 		= $(id).attr("id");
	new_besar 	= document.getElementById(besar).value.replace(/[^0-9]/g,'');
	$(id).val(new_besar);
	if(new_besar != ""){
		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		$(id).after('<img src="'+conf.dirhost+'/files/images/loading.gif" id="lod_div_'+id_deal+'" style="float:right; width:12%">');
		proses_page 	= $("#proses_page_cart").val();
		$.ajax({
			url	 : proses_page,
			type 	: "POST",
			data	: {"direction":"save_jml","id_deal":id_deal,"jml_voucher":new_besar},
			success : function(response){
				//alert(response);
				result = JSON.parse(response);
				$("#new_ttl_pay").html(result.ttl_cart);
				$("#new_pay_"+id_deal).html(result.ttl_price);
				$("#total_bayar").val(result.ttl_cart_data);
				$("#lod_div_"+id_deal).remove();
			},
			error: function (request, status, error) {
       			 $(id).after("<div class='alert alert-error' style='padding:0'>"+request.responseText+"</div>");
   		 	}
		})
	}
}

function ch_city(){
	var conf 	 = JSON.parse("{"+$("#config").val()+"}");
	$("#city_loader").html('<img src="'+conf.dirhost+'/files/images/loader_v.gif" >');
	id_prop   	  = $("#propinsi").val();
	data_page 	= $("#data_page_cart").val();
	$("#kec_loader").html("");
	$("#kab_loader").html("");
	$("#kurir_loader").html("");
	reset_money();
	if(id_prop != ""){
		$.ajax({
			url	 : data_page,
			type 	: "POST",
			data	: {"direction":"get_city","id_prop":id_prop},
			success : function(response){
				$("#city_loader").html(response);
			}
		})
	}else{
		$("#city_loader").html("");
	}
}
function ch_kec(){
	var conf 	 = JSON.parse("{"+$("#config").val()+"}");
	$("#kec_loader").html('<img src="'+conf.dirhost+'/files/images/loader_v.gif" >');
	id_city   	  = $("#kota").val();
	data_page 	= $("#data_page_cart").val();
	$("#kab_loader").html("");
	$("#kurir_loader").html("");
	reset_money();
	$.ajax({
		url	 : data_page,
		type 	: "POST",
		data	: {"direction":"get_kec","id_city":id_city},
		success : function(response){
			$("#kec_loader").html(response);
		}
	})
}
function ch_kab(){
	var conf 	= JSON.parse("{"+$("#config").val()+"}");
	$("#kab_loader").html('<img src="'+conf.dirhost+'/files/images/loader_v.gif" >');
	id_kec   	  = $("#kecamatan").val();
	data_page   = $("#data_page_cart").val();
	$("#kurir_loader").html("");
	reset_money();
	$.ajax({
		url	 : data_page,
		type 	: "POST",
		data	: {"direction":"get_kab","id_kec":id_kec},
		success : function(response){
			$("#kab_loader").html(response);
		}
	})
}
function ch_kurir(){
	var conf 	 = JSON.parse("{"+$("#config").val()+"}");
	$("#kurir_loader").html('<img src="'+conf.dirhost+'/files/images/loader_v.gif" style="margin:10px 0 0 0">');
	id_prop   	  = $("#propinsi").val();
	id_city   	  = $("#kota").val();
	id_kec   	   = $("#kecamatan").val();
	id_kab   	   = $("#kabupaten").val();
	reset_money();
	confirmation_page  = $("#data_page_cart").val();
	$.ajax({
		url	 : confirmation_page,
		type 	: "POST",
		data	: {"direction":"get_kurir","id_prop":id_prop,"id_city":id_city,"id_kec":id_kec,"id_kab":id_kab},
		success : function(response){
			$("#kurir_loader").html(response);
		}
	})
}
function ch_kurir_price(){
	var conf 	 = JSON.parse("{"+$("#config").val()+"}");
	$("#new_total").html('<img src="'+conf.dirhost+'/files/images/loader_v.gif" style="margin:10px 0 0 0">');
	id_prop   	   = $("#propinsi").val();
	id_city   	   = $("#kota").val();
	id_kec   	    = $("#kecamatan").val();
	id_kab   	    = $("#kabupaten").val();
	id_kurir  	  = $("#id_kurir").val();
	total_bayar = $("#total_bayar").val();
	data_page     = $("#data_page_cart").val();
	$.ajax({
		url	 : data_page,
		type 	: "POST",
		data	: {"direction":"get_price","total":total_bayar,"id_prop":id_prop,"id_city":id_city,"id_kec":id_kec,"id_kab":id_kab,"id_kurir":id_kurir},
		success : function(response){
			result = JSON.parse(response);
			$("#total_bayar").val(result.total);
			newmoney = accounting.formatMoney(result.total,"Rp.",2,".",","); // €4.999,99	
			ori_money_cap = "<b>Total Belanja : <span class='code' >"+newmoney+"</span></b>";	
			$("#new_total").html(ori_money_cap);
		}
	})
}
function purchase(pos){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	var total_bayar = $("#total_bayar").val();
	biaya_antar   	= $("#total_biaya");
	id_merchants	= $("#id_merchants").val();
	proses_page 	= $("#proses_page_cart").val();
	id_city   	    = $("#kota");
	id_kec   	    = $("#kecamatan");
	id_kel   	    = $("#kelurahan");
	alamat   	    = $("#alamat");
	//id_kurir   	= $("#id_kurir");
	pros 			  = 3;	
	if((id_city.length > 0 && id_city.val() != "")){
		if((id_kel.length > 0 && id_kel.val() != "") && (alamat.length > 0 && alamat.val() != "")){
			pros = 3;	 
		}else{
			pros = 0;		
		}
	}
	//alert(pros+" = "+id_city.val());
	if(pros == 3){
		$("#purchase_loader_"+pos).html('<img src="'+conf.dirhost+'/files/images/loader_v.gif"><br>');
		deliveries 	= [];
		delivery_fees = [];
		$(".delivery").each(function(){
			if($(this).is(":checked")){
				deliveries.push($(this).val());
				delivery_fees.push($(this).attr("data-info"));
			}
		});
		
		$.ajax({
			url	 : proses_page,
			type 	: "POST",
			data	: {"direction":"purchase","deliveries":deliveries,"delivery_fees":delivery_fees,"id_merchants":id_merchants,"total_bayar":total_bayar,"biaya_antar":biaya_antar.val(),"id_kelurahan":id_kel.val(),"alamat":alamat.val()},
			success	: function(response){
				alert(conf.realtime);
				if(conf.realtime == 1){
					merchant_list 	= id_merchants.split(";");
					for (var i = 0; i < merchant_list.length; i++) {
						if(merchant_list[i] != ""){
							alert("Kirim ke /online_order_"+merchant_list[i]);
							pushit("/online_order_"+merchant_list[i],1,"Online Order");
						}
					}
				}
				num_msg 	  = $("#badge_pesan").html();
				new_num_msg  = +num_msg + 1;
				$("#badge_pesan").html(new_num_msg);
				$("#badge_pesan").show();
				$("#badge_cart").empty();
				$("#purchase_loader_"+pos).html(response+"<br>");
				$.fancybox.close();
			}
		})
	}else{
		$("#purchase_loader_"+pos).html('<div class="alert alert-error">Pengisian Form Belum Lengkap</div>');	
	}
}

function show_deliver(el){
	checked = $(".delivery:checkbox:checked").length;
	//alert(checked);
	if(checked > 0){
		$("#delivery").show();
	}else{
		$("#delivery").hide();	
	}
}

</script>
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
		if($_SESSION['csidkey'] == $id_merchant){
			$discount = $db->fob("VALUE",$tpref."client_discounts"," WHERE ID_DISCOUNT_PATTERN = '1' AND CUSTOMER_FLAG = '1'");	
		}
		return $discount;
	}

	$id_product 	 = isset($_REQUEST['id_product']) 	? $_REQUEST['id_product'] 	: "";
	if(!empty($id_product)){
		$jumlah_order   = 1;
		$q_product	  	= $db->query("SELECT ID_CLIENT,NAME,SALE_PRICE FROM ".$tpref."products WHERE ID_PRODUCT = '".$id_product."'");
		$dt_product	 	= $db->fetchNextObject($q_product);
		$id_merchant 	= $dt_product->ID_CLIENT;
		$nm_product	 	= $dt_product->NAME;
		$pr_price	   	= $dt_product->SALE_PRICE;
		$discount 		= discount($id_merchant);
		if(!empty($discount)){
			$disc_price	= ($pr_price/100)*discount($id_merchant);
			$pr_price	= $pr_price-$disc_price;
		}
		$ttl_price  	= $pr_price*$jumlah_order;
		
		$ch_order 	 = $db->recount("SELECT ID_PRODUCT FROM ".$tpref."customers_carts WHERE ID_PRODUCT = '".$id_product."' AND ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0'");	
		if($ch_order == 0){
			$container 		= array(1=>
									array("ID_CLIENT",$id_merchant),
									array("ID_PRODUCT",$id_product),
									array("ID_CUSTOMER",$_SESSION['sidkey']),
									array("AMOUNT",$jumlah_order),
									array("PRICE",@$pr_price),
									array("DISCOUNT",trim($discount)),
									array("TOTAL_PRICE",@$ttl_price),
									array("STATUS","0"),
									array("TGLUPDATE",$tglupdate." ".$wktupdate));
			$db->insert($tpref."customers_carts",$container);
		?>
        <script type="text/javascript">
			jml 		= $("#badge_cart").html();
			new_jml	= +jml + 1;
			$("#badge_cart").html(new_jml);
		</script>
        <?php 
		}
	}
	
$total_bayar = $db->sum("TOTAL_PRICE",$tpref."customers_carts"," WHERE  ID_CUSTOMER = '".$_SESSION['sidkey']."' AND STATUS = '0'");

	$pur_string   	= "SELECT * FROM ".$tpref."customers_carts WHERE STATUS = 0 AND ID_CUSTOMER = '".$_SESSION['sidkey']."' GROUP BY ID_CLIENT ORDER BY ID_PURCHASE_DETAIL ASC";
	//echo $pur_string;
	$q_purchase 	 = $db->query($pur_string);
	$num_cart		= $db->numRows($q_purchase);
	$id_merchants	= "";
	
	if($num_cart > 0){?>
    <table width="100%">
    <?php
	$b = 0;
	while($dt_pur 		 = $db->fetchNextObject($q_purchase)){
		$b++;
		$id_merchants	.= ";".$dt_pur->ID_CLIENT;
		$q_merchant	= $db->query("SELECT CLIENT_LOGO,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT = '".$dt_pur->ID_CLIENT."'");
		$dt_merchant 	= $db->fetchNextObject($q_merchant);
		@$nm_merchant	= $dt_merchant->CLIENT_NAME;
		@$logo_merchant  = $dt_merchant->CLIENT_LOGO;

		$cart_string   	= "SELECT * FROM ".$tpref."customers_carts WHERE ID_CLIENT = '".$dt_pur->ID_CLIENT."' AND ID_CUSTOMER = '".$_SESSION['sidkey']."'  AND STATUS = 0 ORDER BY ID_CART DESC";
		//echo $deal_string;
		$q_cart 	   	= $db->query($cart_string);
		@$client_address =  client_address($dt_pur->ID_CLIENT);
?>
		<tr>
        	<td style="text-align:center" class="w-box-header" >
			<div style="width:10%; float:left; height:70%; overflow:hidden; background:#FFF; margin:2% 4px 2% 0" class='merchant_logo'>
			<?php if(is_file($basepath."/files/images/logos/".$logo_merchant)){?>
                <img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant; ?>" width="100%"/>
            <?php }?>
            </div>
            <div style="margin-top:4%;width:70%; float:left; text-align:left; line-height:13px">
            	<b><?php echo $nm_merchant; ?></b><br />
				<?php echo cutext($client_address['alamat'],200); ?>
                <!--Metode Belanja<br />
                <input type="checkbox" class='delivery' value="<?php //echo $dt_pur->ID_CLIENT; ?>" id="biaya_antar_<?php echo $b; ?>"  onclick="show_deliver(this)"/> Diantar
                <input type="hidden" class="client_add" 
                value='<?php //echo $client_address['kelurahan']; ?>,<?php //echo $client_address['kecamatan']; ?>,<?php //echo $client_address['kota']; ?>,<?php //echo $client_address['propinsi']; ?>,Indonesia' data-info='<?php //echo $b; ?>'/>-->
            </div>
            <br clear="all" />
            </td>
        </tr>
		<tr>
        	<td width="71%">
                <table style="width:100%; border:1px solid #E5E5E5; background:#FFF; " class="deal_tbl">
                <?php
                    while($dt_cart = $db->fetchNextObject($q_cart)){
                        $expiration_date= "";
                        $hrg_product 	= "";
                        $image_list	 	= "";
                        $image_array	= "";
                        $hrg_product	= $dt_cart->PRICE;
					    @$nm_merchant	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$id_merchant."'");
                        // JIKA ISI CART PEMBELIAN PRODUCT
                        if(!empty($dt_cart->ID_PRODUCT)){
                            $id_product 	 = $dt_cart->ID_PRODUCT;
                            $id_merchant    = $db->fob("ID_CLIENT",$tpref."products"," WHERE ID_PRODUCT = '".$id_product."'");
                            $product        = get_product_info($id_product,"80%");
                            $unit_product   = $product['unit'];
							$real_price 	= $product['price'];
                            $image_list   .="
                            <div class='img_box'>
                                <div class='img_box_inline'>
                                    ".$product['photo']."
                                </div>
                            </div>";
                            $jml_prod	  = 1;
                        }
                        //INFORMASI PRODUCT		
                ?>
                  <tr id="div_<?php echo $dt_cart->ID_CART; ?>">
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td colspan ='2' class="w-box-header" >
                                    <div style="font-weight:100; line-height:15px;">
                                        <?php echo ucwords(strtolower($product['name'])); ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%"  align="center" valign="top" style="padding:4px; " >
                                     <?php echo $image_list; ?>
                                </td>
                                <td style="padding-left:5px; vertical-align:top">
                                    <ul class="content">
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
                                                <span style="text-decoration:line-through" class="code"><?php echo money("Rp.",$real_price); ?></span> &nbsp;<br />
                                                <?php } ?>
                                                <b><?php echo money("Rp.",$hrg_product); ?></b>
                                            </div>
                                        </li>
                                        <li>
                                            Jumlah Dibeli
                                            <div class='value'>
                                                <span id="current_deal">
                                                    <input type='text' value='<?php echo @$dt_cart->AMOUNT; ?>'  id="jmlh_<?php echo $dt_cart->ID_CART; ?>" onkeyup="save_jml(this,'<?php echo $dt_cart->ID_CART; ?>')" style=" position:relative; width:15%; margin:0; padding:2px; text-align:center"/>
                                                </span>
                                            </div>
                                        </li>
                                        <li>
                                            Total Bayar
                                            <div class='value'>
                                                <span class="code " id="new_pay_<?php echo $dt_cart->ID_CART; ?>">
													<?php echo money("Rp.",@$dt_cart->TOTAL_PRICE); ?>
                                                </span>
                                            </div>
                                        </li>
                                    </ul>
                              	</td>
                            </tr>
                            <tr>
                                <td colspan='2' class="w-box-footer" style="clear:both; padding:4px; border-top:1px solid #CCC" > 
                                      <span id="v_loader_<?php echo $dt_cart->ID_CART; ?>"></span>
                                      <a href="javascript:void()" onclick="cancel_this('<?php echo $dt_cart->ID_CART; ?>')" >
                                          <button type="button" class="btn btn-beoro-2 span12"><!--<i class="icsw16-trashcan"></i>--> Batal</button>
                                      </a> 
                                      <input type="hidden" id="voucher_val_<?php echo $dt_deal->ID_CART; ?>"  value="<?php echo $voucher_price; ?>"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                  </tr>
                <?php } ?>
                </table>
            </td>
        </tr>
	<?php } ?>
    </table>
    <br />
    <div class="summary" style="background:#FFF; border:1px solid #CCC">
      <div class="formSep">
      <?php if(!empty($total_bayar) && $total_bayar > 200000){?>
	  	<div  id="delivery" style="display:none">
            <div id="kurir_loader_id_kurir_top"></div>
            <span style="color:#990000">NB : Kosongkan atau tentukan lokasi pengantaran</span>
            <div class="formSep" style="padding:0 " >
                <label>Propinsi</label>
                <select id="propinsi" onchange="ch_city()">
                    <option value="">--PILIH PROPINSI--</option>
                    <?php
                        $q_prop  = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
                        while($dt_prop = $db->fetchNextObject($q_prop)){
                    ?>
                        <option value="<?php echo $dt_prop->ID_LOCATION; ?>"><?php echo $dt_prop->ID_LOCATION; ?> - <?php echo $dt_prop->NAME; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="formSep"  style="padding:0;">
                <label>Kota</label>
                <select id="kota" onchange="ch_kec()">
                    <option value="">--PILIH KOTA--</option>
                    <?php
                       $q_city  = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '32' AND ID_LOCATION = '3273' ORDER BY NAME ASC");
                       while($dt_city = $db->fetchNextObject($q_city)){
                    ?>
                        <option value="<?php echo $dt_city->ID_LOCATION; ?>"><?php echo $dt_city->NAME; ?></option>
                    <?php } ?>
                </select>
            </div>
            <span id="city_loader"></span>
            <span id="kec_loader"></span>
            <span id="kab_loader"></span>
            <span id="kurir_loader"></span>
            <div id="outputDiv"></div>
		</div>
      <?php } ?>
            <span id="purchase_loader_top"></span>
            <div id="new_total">
                Total Pembayaran : 
                <span class='code moneys' id="new_ttl_pay"><?php echo money("Rp.",@$total_bayar); ?></span>
                <br />
                <br />
            </div>
            <div class="formSep" style="padding:0 " >
              <button type="button" class='btn btn-beoro-1 span12' id="next_proses" value="no" onclick="purchase('top')">
                    Ke Pembayaran
              </button>
             <!-- <button type='button' class='btn btn-beoro-3' style='font-size:2vmin; margin-left:5px' onclick='cancel_voucher()'>Reset</button>-->
                <input type="hidden" id="id_merchants" value="<?php echo $id_merchants; ?>"/>
                <input type="hidden" id="total_bayar" value="<?php echo $total_bayar; ?>"/>
                <input type="hidden" id="data_page_cart" value="<?php echo $producthost; ?>/pages/purchase/ajax/confirmation.php"/>
                <input type="hidden" id="proses_page_cart" value="<?php echo $producthost; ?>/pages/purchase/ajax/proses.php"/>
            </div>
      </div>
    </div>
<?php }else{ ?>
	<div class="alert alert-error" style="margin:10px;">Tidak ada Pembelian yang terjadi saat ini</div>
<?php }

} ?>

