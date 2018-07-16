<?php 
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$page 			= isset($_REQUEST['page']) 		? $_REQUEST['page'] : "";
	$id_target 		= isset($_REQUEST['id_target']) ? $_REQUEST['id_target'] : "";
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$no 			= isset($_REQUEST['no']) ? $_REQUEST['no'] : "";

	$query_str		= "
		SELECT 	
			*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,SUM(a.REMAIN) AS PIUTANG  
		FROM 
			".$tpref."factures a ,".$tpref."products_sales b
		WHERE 
			b.ID_PRODUCT_SALE = '".$no."' AND
			a.ID_FACTURE = b.ID_FACTURE AND
			b.ID_CLIENT='".$_SESSION['cidkey']."'
			";
	$direction	= "save";
	$q_produk 	= $db->query($query_str);
	$num_produk	= $db->numRows($q_produk);
	if($num_produk > 0){
		//INFORMASI PENJUALAN PRODUK
		$dt_produk = $db->fetchNextObject($q_produk);
		@$id_product_sale	= $dt_produk->ID_PRODUCT_SALE;	
		@$id_product		= $dt_produk->ID_PRODUCT;
		@$id_sales			= $dt_produk->ID_SALES;	
		@$id_customer		= $dt_produk->ID_CUSTOMER;	
		@$id_facture		= $dt_produk->ID_FACTURE;
		@$harga_edit		= $dt_produk->PRICE;
		@$jual_edit			= $dt_produk->QUANTITY; 
		@$diskon_edit		= $dt_produk->DISCOUNT;
		if($diskon_edit == 0){ $diskon_edit =""; }
		@$total_jual		= $dt_produk->TOTAL;
		$propinsi			= $dt_produk->PROVINCE;
		$kota				= $dt_produk->CITY;
		$kecamatan			= $dt_produk->DISTRICT;
		$kelurahan			= $dt_produk->SUBDISTRICT;
		
		//INFORMASI FAKTUR
		@$id_cash_flow		= $dt_produk->ID_CASH_FLOW;
		@$nofaktur_edit		= $dt_produk->FACTURE_NUMBER;
		@$status_lunas		= $dt_produk->PAID_STATUS;
		@$nopo				= $dt_produk->PO_NUMBER;
		@$downpay			= $dt_produk->PAID;
		@$termin			= $dt_produk->TERMS;
		@$kredit			= $dt_produk->REMAIN;
		@$keterangan		= $dt_produk->NOTE;
		$tgl_jual			= explode(" ",$dt_produk->TRANSACTION_DATE);
		$tgl_jual			= $dtime->date2indodate($tgl_jual[0]);
	
		@$real_total_jual	= $dt_produk->SUMMARY; 
		@$real_total_bayar	= $db->sum("PRICE*QUANTITY",$tpref."products_sales"," WHERE ID_FACTURE = '".$id_facture."'"); 

		if(empty($status_lunas)){ $status_lunas = 2; 	}

		@$harga_jual		= $db->fob("SALE_PRICE",$tpref."products_buys"," WHERE ID_PRODUCT='".$id_product."'");
		if(empty($total_jual)){
			$total_jual			= $harga_jual;
		}
		
		//INFORMASI PRODUK
		$q_product			= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$id_product."'");
		$dt_product			= $db->fetchNextObject($q_product);
		$code 				= $dt_product->CODE;
		$product_name		= $dt_product->NAME;
		$id_product_unit	= $dt_product->ID_PRODUCT_UNIT;
		$photo				= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
		@$unit				= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_product->ID_PRODUCT_UNIT."'"); 
		@$ori_stock			= $db->fob("STOCK",$tpref."products_stocks"," WHERE ID_PRODUCT='".$id_product."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
		if(empty($ori_stock)){
			$ori_stock 		= 0;
		}
		if($kredit > 0){
			$status_lunas = 3;	
		}
		$num_collective = $db->recount("SELECT ID_FACTURE FROM ".$tpref."products_sales WHERE ID_FACTURE='".$id_facture."'");
	?>
<div  style="width:100%">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Produk</h4>
        </div>
        <div class="ibox-content">
            <table width="100%" class="popup table table-striped">
            <thead>
                    <tr>
                        <th width="16%">&nbsp;</th>
                        <th width="18%" ><b>Kode Item</b></th>
                        <th width="32%"><b>Nama</b></th>
                        <th width="21%" style="text-align:center"><b>Stok </b></th>
                        <th width="13%" style="text-align:center">Actions</th>
              </tr>
              </thead>
                <tbody>
                      <input type="hidden" 	id="num_collective" value="<?php echo @$num_collective; ?>" />
                      <input type="hidden" 	id="id_cash_flow" value="<?php echo @$id_cash_flow; ?>" />
                      <input type="hidden" 	id="id_product_sale" value="<?php echo @$id_product_sale; ?>" />
                      <input type="hidden" 	id="real_total_jual" value="<?php echo @$real_total_jual; ?>"/>
                      <input type="hidden" 	id="real_total_bayar" value="<?php echo @$real_total_bayar; ?>"/>
                      <input type="hidden" 	id="first_total_bayar" value="<?php echo @$downpay; ?>"/>
                      <input type="hidden" 	id="new_total_bayar" value="<?php echo @$real_total_bayar; ?>"/>
                      <tr id="tr_edit">
                        <td>
                            <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="photo" />
                            <?php }else{ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class='photo'/>
                            <?php } ?>
                        </td>
                        <td><?php echo $code; ?></td>
                        <td><?php echo $product_name; ?></td>
                        <td style="text-align:center">
                            <span id="stock_label_edit"><?php echo $ori_stock; ?></span> <?php echo $unit; ?>
                            <input type='hidden' id='ori_stock_edit' value='<?php echo $ori_stock; ?>' />
                        </td>
                        <td style="text-align:center">
                            <div class="btn-group">
                                <?php if($direction == "save" &&  allow('edit') == 1){?>
                                        <?php if($ori_stock > 0){?>
                                        <a href="javascript:void()" class="btn btn-mini" title="Edit" onclick="show_form()">
                                            <i class="icon-pencil icon_edit" ></i>
                                            <i class="icon-off icon_edit" style='display:none'></i>
                                        </a>
                                        <?php }else{ ?>
                                        <a href="<?php echo $dirhost; ?>/?page=<?php echo $page; ?>" class="btn btn-mini" title="Tambah Stock">
                                            <i class="icsw16-shopping-cart-2"></i>
                                        </a>
                                        <?php } ?>
                               <?php } ?>
                            </div>
                        </td>
                    </tr>
                      <tr id="form_edit" style='display:none;'>
                        <td colspan="5">
                            <?php
                            if($num_collective > 1){
                            ?>
                            <div class='alert alert-warning' style="margin:0">
                                <b>Peringatan</b> : Data penjualan ini merupakan data hasil perekaman data secara kolektif, jika anda mengubah nilai <b>SELAIN "Jumlah dan Diskon" </b>, maka seluruh data penjualan 
                                <?php if(!empty($no_faktur)){ ?>
                                    dengan No Faktur <b><?php echo $no_faktur; ?> </b>
                                <?php }else{ ?>
                                    yang di rekam bersamaan dengan data penjualan ini secara kolektif 
                                <?php } ?> 
                                akan berubah:
                            </div>
                            <br />
                            <?php	
                            }
                            ?>
                            <table width="100%" border="0" class='form_input'>
                            <tbody>
                            <tr>
                              <td colspan="2"><b>Informasi Produk <span class='code'><?php echo $code; ?></span></b></td>
                            </tr>
                              <tr>
                                <td class='req'>Harga Jual</td>
                                <td>
                                <span class="input-prepend input-append">
                                <span class="add-on">Rp.</span>
                                    <input type="text" id="harga_edit" value="<?php echo @$harga_jual; ?>"  onkeyup="calculate_edit('<?php echo $direction; ?>','harga_edit')" onblur="calculate_edit('<?php echo $direction; ?>','harga_edit')" class=' mousetrap' readonly/>
                                 <span class="add-on">,00</span>
                                 </span>
                                 <input type="hidden" 	id="harga_jual_edit" value="<?php echo @$harga_jual; ?>"/>
                                </td>
                              </tr>
                              <tr>
                                <td width="23%" class='req'>Jumlah</td>
                                <td width="77%"><input type="text" id="jumlah_edit" value="<?php echo @$jual_edit; ?>"  onkeyup="calculate_edit('<?php echo $direction; ?>','jumlah_edit')" onblur="calculate_edit('<?php echo $direction; ?>','jumlah_edit')" class=' mousetrap'/></td>
                              </tr>
                              <tr>
                                <td>Total Harga Jual</td>
                                <td>
                                <span class="input-prepend input-append">
                                <span class="add-on">Rp.</span>
                                <input type="text" id="total_jual_label_edit" value="<?php echo money("",@$total_jual); ?>" readonly>
                                 </span>
                                <input type="hidden" id="total_jual_edit" value="<?php echo @$total_jual; ?>">
                                <br />
                               
                                </td>
                              </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td colspan="2"><b>Informasi Penjualan <span class='code'>No Faktur : <?php echo @$nofaktur_edit; ?></span></b></td>
                            </tr>
                              <tr>
                                <td class='req'>Tanggal Jual</td>
                                <td>
                                    <?php if(empty($tgl_jual) || $tgl_jual == "00-00-0000"){ $tgl_jual = date("d-m-Y"); } ?>
                                    <span class="input-append date" id="dp2_edit" data-date="<?php echo $tgl_jual; ?>" data-date-format="dd-mm-yyyy">
                                        <input class="mousetrap" size="16" value="<?php echo $tgl_jual; ?>" readonly="" type="text" id="tgl_jual_edit">
                                        <span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
                                    </span>                        
                                </td>
                              </tr>
                              <tr>
                                <td class='req'>No Faktur</td>
                                <td>
                                    <input type="text" id="nofaktur_edit" value="<?php echo @$nofaktur_edit; ?>" style='text-transform:uppercase'/>
                                    <input type="hidden" id="id_facture_edit" value="<?php echo @$id_facture; ?>" />
                                </td>
                              </tr>
                            <?php if($_SESSION['ulevelkey'] == 5){ $readonly[$id_product] = "readonly"; }else{ $readonly[$id_product] = ""; } ?>
                              <tr>
                                <td>Nama Sales</td>
                                <td>
                                <span class="input-prepend input-append">
                                <span class="add-on"><i class="icsw16-admin-user"></i></span>
                                <?php if($_SESSION['ulevelkey'] == 5){?>
                                <input type="hidden" id="id_sales_edit" value="<?php echo $id_sale; ?>"  class='mousetrap' />
                                <input type="text" id="nm_sales_edit" value="<?php echo $nm_sale; ?>"  class='mousetrap' <?php echo $readonly[$id_product]; ?>/>
                                <?php }else{ ?>
                                <select id="id_sales_edit" name="id_sales">
                                    <option value=''>--PILIH SALES--</option>
                                    <?php 
                                    $q_marketing = $db->query("SELECT * FROM system_users_client WHERE ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY USER_NAME ASC");
                                    while($dt_marketing = $db->fetchNextObject($q_marketing)){
                                    ?>
                                    <option value='<?php echo $dt_marketing->ID_USER; ?>' <?php if(!empty($id_sales) && $id_sales == $dt_marketing->ID_USER){?>selected<?php } ?>>
                                    <?php echo $dt_marketing->USER_NAME; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <?php } ?>
                                 </span>
                                </td>
                              </tr>
                              <tr>
                                <td>Nama Pelanggan</td>
                                <td>
                                    <span class="input-prepend input-append">
                                    <span class="add-on" style="padding:4px"><i class="icsw16-users-2"></i></span>
                                    <select id="id_customer_edit" name="id_customer_edit" class="mousetrap">
                                        <option value=''>--PILIH PELANGGAN--</option>
                                        <?php 
                                        $q_customer = $db->query("SELECT * FROM ".$tpref."customers WHERE (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey']).") ORDER BY CUSTOMER_NAME ASC");
                                        while($dt_customer = $db->fetchNextObject($q_customer)){
                                        ?>
                                        <option value='<?php echo $dt_customer->ID_CUSTOMER; ?>' <?php if(!empty($id_customer) && $id_customer == $dt_customer->ID_CUSTOMER){?>selected<?php } ?>>
                                        <?php echo $dt_customer->ID_CUSTOMER; ?> - <?php echo $dt_customer->CUSTOMER_NAME; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                    </span>
                                </td>
                              </tr>
                            <tr>
                              <td colspan="2" id="form_msg" style="display:none;">
                                <div class='alert alert-error' style='margin:0 9px 9px 9px'>
                                    Jumlah Pembayaran Lebih Besar Dari Pada Total Bayar
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td class='req'>Total Penjualan </td>
                              <td>
                              <div  class='form_test'>
                              <span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                                <input type="text" id="new_total_penjualan" value="<?php echo money("",@$real_total_bayar); ?>" readonly="readonly"/>
                              </span>
                              </div>
                              </td>
                            </tr>
                            <tr>
                                <td>Diskon</td>
                                <td>
                                    <span class="input-prepend input-append">
                                    <input type="text" id="diskon_edit" value="<?php echo @$diskon_edit; ?>" onkeyup="calculate_edit('<?php echo $direction; ?>','jumlah_edit')"  onblur="calculate_edit('<?php echo $direction; ?>','jumlah_edit')" class=' mousetrap'/> 
                                     <span class="add-on">%</span>
                                     </span>
                                </td>
                            </tr>
                            <tr>
                                <td class='req'>Jumlah Bayar</td>
                                <td>
                                    <div  class='form_test'>
                                    <span class="input-prepend input-append">
                                    <span class="add-on">Rp.</span>
                                    <input type='text' id='downpay_edit' value='<?php echo @$downpay; ?>' class='mousetrap' onkeyup="calculate_edit('<?php echo $direction; ?>','downpay_edit')"  onblur="calculate_edit('<?php echo $direction; ?>','downpay_edit')"/>
                                     <span class="add-on">,00</span>
                                     </span>
                                    <input type='hidden' id='first_downpay_edit' value='<?php echo @$downpay; ?>' />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Sisa</td>
                                <td>
                                    <span class="input-prepend input-append">
                                    <span class="add-on">Rp.</span>
                                    <input type='text' id='kredit_label_edit' value='<?php echo money("",@$kredit); ?>' class='mousetrap' readonly/>
                                    <input type='hidden' id='kredit_edit' value='<?php echo @$kredit; ?>'/>
                                    <input type='hidden' id='first_kredit' value='<?php echo @$kredit; ?>'/>
                                    </span>
                                </td>
                            </tr>
                              <tr>
                                <td class='req'>Status Bayar</td>
                                <td>
                                <select class="mousetrap"  id="status_lunas_edit_label" onchange="show_kredit('edit')" disabled="disabled">
                                  <option value='2' <?php if(!empty($status_lunas) && $status_lunas == "2"){?> selected<?php } ?>>Lunas</option>
                                  <option value='3' <?php if(!empty($status_lunas) && $status_lunas == "3"){?> selected<?php } ?>>Piutang</option>
                                </select>
                                <input type='hidden' id='status_lunas_edit' name='status_lunas_edit' value='<?php echo $status_lunas; ?>' />
                                </td>
                              </tr>
                              <?php if(!empty($status_lunas) && $status_lunas == 2){ $display_lunas = "style='display:none'"; }else{ $display_lunas = ""; } ?>
                              <tr class="div_kredit_edit" <?php echo @$display_lunas ?>>
                                <td>No PO</td>
                                <td>
                                <input type='text' id='nopo_edit' value='<?php echo @$nopo; ?>' class='mousetrap' style='text-transform:uppercase'/>
                                </td>
                              </tr>                      
                              <tr class="div_kredit_edit" <?php echo @$display_lunas ?>>
                                <td>Termin Pembayaran</td>
                                <td>
                                <input type='text' id='termin_edit' value='<?php echo @$termin; ?>' class='mousetrap' readonly/>
                                <button class='btn' id='btn_termin'><i class="icon-plus"></i></button>
                                </td>
                              </tr>
                              <?php 
                              if(!empty($termin)){
                                $termin_cond = "";
                                $q_termin = $db->query("SELECT * FROM ".$tpref."debt_credit_reminder WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND DEBT_CREDIT='3' ");
                                $r = 0;
                                while($dt_termin = $db->fetchNextObject($q_termin)){
                                    $r++;
                                    $remider_date = $dtime->date2indodate($dt_termin->REMINDER_DATE);
                              ?>
                              <tr id='tr_edit_<?php echo $dt_termin->ORDINAL; ?>' class='tr_edit' data-list='old'>
                                <td class='option'>Tanggal Jatuh Tempo <?php echo $r; ?></td>
                                <td>
                                    <span class='input-append date' id='dp_edit_<?php echo $dt_termin->ORDINAL; ?>' data-date='' data-date-format='dd-mm-yyyy'>
                                        <input class='mousetrap date_input' size='16' value='<?php echo $remider_date; ?>' readonly='' type='text' id='tgl_tempo_edit_<?php echo $dt_termin->ORDINAL; ?>'>
                                        <span class='add-on'><i class='icsw16-day-calendar'></i></i></span>
                                    </span> 
                                    <?php if($dt_termin->STATUS != 1){?>
                                        <script language="javascript">
                                            $('#dp_edit_<?php echo $dt_termin->ORDINAL; ?>').datepicker();
                                        </script>
                                        <button class='btn beoro-btn' style='margin-left:3px' onclick="remove_tempo('<?php echo $dt_termin->ORDINAL; ?>','<?php echo $id_cash_flow; ?>')"><i class="icsw16-trashcan"></i></button>
                                        <?php }else{ 
                                        $last_pay = $db->fob("AMOUNT",$tpref."cash_debt_credit"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND ORDINAL = '".$dt_termin->ORDINAL."'");
                                    ?>
                                        <div class='alert alert-info' style='float:right; margin:0; padding:2px 8px 2px 10px; width:40%; overflow:hidden'>
                                            Di Bayar Sebesar <?php echo money("Rp.",$last_pay); ?>
                                        </div>
                                    <?php } ?>
                                    <input type='hidden' id='st_termin_<?php echo $dt_termin->ORDINAL; ?>' value='<?php echo $dt_termin->STATUS; ?>' /><span id='delete_tempo_loader_<?php echo $dt_termin->ORDINAL; ?>'></span>
                                </td>
                              </tr>
                              <?php
                                }
                              }
                              ?>
                              <tr id="div_termin_edit" style="display:none">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                              </tr>                   
                              <tr>
                                <td>Keterangan</td>
                                <td><textarea id="keterangan_edit" class='mousetrap col-md-6' ><?php echo @$keterangan; ?></textarea></td>
                              </tr>
                              <tr>
                                <td style="vertical-align:top">Lokasi Penjualan</td>
                                <td>
                                    <?php 
                                        if(empty($propinsi))	{ $style_close 	= "style='display:none'"; } 
                                        if(!empty($propinsi))	{ $style_open 	= "style='display:none'"; } 
                                    ?>
                                    <a href='javascript:void()' id='location_open_edit' onclick="open_location('province','edit')" <?php echo  @$style_open; ?>>+ Tambah</a>
                                    <a href='javascript:void()' id='location_close_edit' onclick="close_location('edit')" <?php echo  @$style_close; ?>>- Tutup</a>
                                    <br />
                                    <select id="propinsi_edit" onchange="open_location('city','edit')" <?php if(empty($propinsi)){?> style="display:none;" <?php } ?>>
                                        <option value=''>--PILIH PROPINSI--</option>
                                        <?php
                                        $query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
                                        while($data_propinsi = $db->fetchNextObject($query_propinsi)){
                                        ?>
                                            <option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
                                            </option>
                                    <?php } ?>
                                    </select>
                                    <div id="div_kota_edit">
                                        <?php if(!empty($kota)){ include $call->inc($ajax_dir,"city.php");} ?>
                                    </div>
                                    <div id="div_kecamatan_edit">
                                        <?php if(!empty($kecamatan)){ include $call->inc($ajax_dir,"district.php");} ?>
                                    </div>
                                    <div id="div_kelurahan_edit">
                                        <?php if(!empty($kelurahan)){ include $call->inc($ajax_dir,"subdistrict.php");} ?>
                                    </div>
                                </td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td>
                                <button type="button" class="btn btn-sempoa-1" id="btn_direction_edit" style='float:left'  onclick="do_direction('<?php echo $direction; ?>','<?php echo $id_product; ?>')">Simpan Data</button>
                                <span id='load_edit'></span>
                                <input type='hidden' id="tgljs" value='<?php echo date("d-m-Y"); ?>'/>
                                </td>
                              </tr>
                              </tbody>
                            </table>
                        </td>
                      </tr>
        
            </table>
        </div>
        <input type="hidden" id="id_target" value="<?php echo $id_target; ?>" />
        <input type="hidden" id="kolektif" value="<?php echo $kolektif; ?>" />
    </div>
</div>
    <?php }else{?>
    	<div class='alert alert-error' style="margin:4px;">
        	Belum Ada Pembelian Untuk Produk Yang Anda Cari, Lakukan Pembelian 
        	<a href='<?php echo $dirhost; ?>/?page=input_pembelian_produk' class="btn btn-sempoa-1">
       	 		<i class="icsw16-shopping-cart-2 icsw16-white"></i>
            </a>
            </div>
    <?php } ?>
<?php } ?>