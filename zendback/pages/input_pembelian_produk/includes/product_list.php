<?php 
if(!empty($_SESSION['cidkey']) && !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$no 			= isset($_REQUEST['no']) ? $_REQUEST['no'] : "";

	$query_str	= "
				SELECT 
					*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,SUM(a.REMAIN) AS HUTANG  
	 			FROM 
					".$tpref."factures a,".$tpref."products_buys b 
				WHERE 
					a.ID_CLIENT='".$_SESSION['cidkey']."' AND 
					a.ID_FACTURE = b.ID_FACTURE AND
					b.ID_PRODUCT_BUY = '".$no."'";
	$direction	= "save";
	$q_produk 		= $db->query($query_str);
	$num_produk		= $db->numRows($q_produk);
	
	$dt_produk = $db->fetchNextObject($q_produk); 	
	$id_product			= $dt_produk->ID_PRODUCT;
	$id_product_buy		= $dt_produk->ID_PRODUCT_BUY;
	$id_partner			= $dt_produk->ID_PARTNER;
	$id_facture			= $dt_produk->ID_FACTURE;
	$harga_beli			= $dt_produk->BUY_PRICE;
	$jumlah				= $dt_produk->QUANTITY;
	$harga_jual			= $dt_produk->SALE_PRICE;
	$total_bayar		= $dt_produk->TOTAL;
	$tgl_beli			= explode(" ",$dt_produk->TRANSACTION_DATE);
	$tgl_beli			= $dtime->date2indodate($tgl_beli[0]);
	
	@$id_cash_flow		= $dt_produk->ID_CASH_FLOW;
	$no_faktur			= $dt_produk->FACTURE_NUMBER;
	$status_lunas		= $dt_produk->PAID_STATUS;
	$nopo				= $dt_produk->PO_NUMBER;
	$keterangan_edit	= $dt_produk->NOTE;

	@$downpay			= $dt_produk->PAID;
	@$termin			= $dt_produk->TERMS;
	@$kredit			= $dt_produk->REMAIN;
	@$real_total_beli	= $dt_produk->SUMMARY; 
	@$real_total_bayar	= $db->sum("BUY_PRICE*QUANTITY",$tpref."products_buys"," WHERE ID_FACTURE = '".$id_facture."'"); 
	if(empty($status_lunas)){ $status_lunas = 2; 	}
	
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
		$ori_stock = 0;	
	}
	if($kredit > 0){
		$status_lunas = 1;	
	}
	$num_collective = $db->recount("SELECT ID_FACTURE FROM ".$tpref."products_buys WHERE ID_FACTURE='".$id_facture."'");
	?>
    <table width="100%" class="popup table table-striped form_msg">
    <thead>
        <tr>
            <th width="16%">&nbsp;</th>
            <th width="18%"><b>Kode Item</b></th>
            <th width="32%"><b>Nama</b></th>
            <th width="21%" style="text-align:center"><b>Stok </b></th>
            <th width="13%" style="text-align:center">Actions</th>
      </tr>
      </thead>
        <tbody>
        	  <input type="hidden" 	id="num_collective" value="<?php echo @$num_collective; ?>" />
              <input type="hidden" 	id="id_cash_flow" value="<?php echo @$id_cash_flow; ?>" />
              <input type="hidden" 	id="id_product_buy" value="<?php echo @$id_product_buy; ?>" />
              <input type="hidden" 	id="real_total_beli" value="<?php echo @$real_total_beli; ?>"/>
              <input type="hidden" 	id="real_total_bayar" value="<?php echo @$real_total_bayar; ?>"/>
              <input type="hidden" 	id="first_total_bayar" value="<?php echo @$downpay; ?>"/>
              <input type="hidden" 	id="new_total_bayar" value="<?php echo @$real_total_bayar; ?>"/>
              <tr id="tr_<?php echo $id_product; ?>">
                <td>
                    <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                        <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $_SESSION['cidkey']; ?>/thumbnails/<?php echo $photo; ?>' class="photo"/>
                    <?php }else{ ?>
                        <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="photo"/>
                    <?php } ?>
                </td>
                <td><?php echo $code; ?></td>
                <td><?php echo $product_name; ?></td>
                <td style="text-align:center">
                    <span id="stock_label_edit"><?php echo @$ori_stock; ?></span> <?php echo @$unit; ?>
                    <input type='hidden' id='ori_stock_edit' value='<?php echo @$ori_stock; ?>' />
                </td>
                <td style="text-align:center">
                    <div class="btn-group">
					<?php if($direction == "save" &&  allow('edit') == 1){?>
                            <a href="javascript:void()" class="btn btn-mini" title="Edit" onclick="show_form()">
                                <i class="icon-pencil icon_edit" ></i>
                                <i class="icon-off icon_edit" style='display:none'></i>
                            </a>
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
                    <b>Peringatan</b> : Data pembelian ini merupakan data hasil perekaman data secara kolektif, jika anda mengubah nilai <b>SELAIN "Harga Beli, Jumlah dan Harga Jual" </b>, maka seluruh data pembelian 
					<?php if(!empty($no_faktur)){ ?>
                    	dengan No Faktur <b><?php echo $no_faktur; ?> </b>
					<?php }else{ ?>
						yang di rekam bersamaan dengan data pembelian ini secara kolektif 
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
                      <span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                        <input type="text" id="harga_jual_edit" value="<?php echo @$harga_jual; ?>" class="mousetrap" onkeyup="numeric(this)" onblur='numeric(this)'/>
                        <span class="add-on">,00</span> 
                      </span>
                      </td>
                    </tr>
                    <tr>
                      <td class='req'>Harga Beli</td>
                      <td><span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                        <input type="text" id="harga_beli_edit" value="<?php echo @$harga_beli; ?>" class="mousetrap"  onkeyup="calculate_edit('<?php echo $direction; ?>','harga_beli_edit')" onblur="calculate_edit('<?php echo $direction; ?>','harga_beli_edit')"/>
                        <span class="add-on">,00</span> </span></td>
                    </tr>
                    <tr>
                      <td class='req'>Jumlah </td>
                      <td><input type="text" id="jumlah_edit" value="<?php echo @$jumlah; ?>" class="mousetrap" onkeyup="calculate_edit('<?php echo $direction; ?>','jumlah_edit')" onblur="calculate_edit('<?php echo $direction; ?>','jumlah_edit')"/></td>
                    </tr>
                    <tr>
                      <td>Total Harga Beli</td>
                      <td>
                      <span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                        <input type="text" id="total_bayar_label_edit" value="<?php echo money("",@$total_bayar); ?>" readonly="readonly"/>
                        <input type="hidden" id="total_bayar_edit" value="<?php echo @$total_bayar; ?>"/>
                      </span>
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2"><b>Informasi Pembelian <span class='code'>No Faktur : <?php echo @$no_faktur; ?></span></b></td>
                    </tr>
                    <tr>
                      <td class='req'>Tanggal Beli</td>
                      <td>
					  <?php if(empty($tgl_beli) || $tgl_beli = "00-00-0000"){ $tgl_beli = date("d-m-Y"); } ?>
                        <span class="input-append date" id="dp2_edit" data-date="<?php echo $tgl_beli; ?>" data-date-format="dd-mm-yyyy">
                          <input class="mousetrap" size="16" value="<?php echo $tgl_beli; ?>" readonly="readonly" type="text" id="tgl_beli_edit" />
                          <span class="add-on"><i class="icsw16-day-calendar"></i></span> </span>
                      </td>
                    </tr>
                    <tr>
                      <td class='req'>No Faktur</td>
                      <td>
                      <input type="text" id="no_faktur_edit" value="<?php echo @$no_faktur; ?>"  class="mousetrap" style='text-transform:uppercase'/>
                      <input type="hidden" id="id_facture_edit" value="<?php echo @$id_facture; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td>Supplier</td>
                      <td>
                      <div class="category" id='id_cat_edit'>
                        <select name="id_partner_edit" id='id_partner_edit' style='margin:0'>
                          <option value=''>--PILIH SUPPLIER--</option>
                          <?php 
                                    $q_partner = $db->query("SELECT * FROM ".$tpref."partners WHERE ID_CLIENT='".$_SESSION['cidkey']."'");  
                                    while($dt_partner = $db->fetchNextObject($q_partner)){
                                ?>
                          <option value='<?php echo $dt_partner->ID_PARTNER; ?>' <?php if(!empty($id_partner) && $id_partner == $dt_partner->ID_PARTNER){ ?>selected<?php } ?>> <?php echo $dt_partner->PARTNER_NAME; ?> </option>
                          <?php 	
                                    }
                                ?>
                        </select>
                        <a href="javascript:void()" class='btn new_cat'> <i class="icon-plus"></i>Tambah Supplier </a> </div>
                        <div class="category" style="display:none"  id="div_category_edit">
                          <input type='text' id='category_edit' style='text-transform:capitalize; margin:3px 3px 0 0;' placeholder='Nama Supplier' />
                          <a href='javascript:void()' class='btn cancel_cat' data-info='<?php echo $id_product; ?>' style='margin:0 3px 0 0'> <i class='icon-remove'></i> Batal </a> <a href='javascript:void()' class='btn save_cat' data-info='<?php echo $id_product; ?>'> <i class='icon-ok'></i> Simpan </a> </div>
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
                      <td class='req'>Total Pembelian </td>
                      <td>
                      <div  class='form_test'>
                      <span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                        <input type="text" id="new_total_pembelian" value="<?php echo money("",@$real_total_bayar); ?>" readonly="readonly"/>
                      </span>
                      </div>
                      </td>
                    </tr>
                    <tr>
                      <td class='req'>Jumlah Bayar</td>
                      <td>
                      <div  class='form_test'>
                      <span class="input-prepend input-append"> <span class="add-on">Rp.</span>
                        <input type='text' id='downpay_edit' value="<?php echo @$downpay; ?>" class='mousetrap' onkeyup="calculate_edit('<?php echo $direction; ?>','downpay_edit')"  onblur="calculate_edit('<?php echo $direction; ?>','downpay_edit')"/>
                        <span class="add-on">,00</span> 
                       </span>
                      </div>
                    </tr>
                    <tr>
                      <td>Sisa</td>
                      <td>
                      <span class="input-prepend input-append"> 
                      	<span class="add-on">Rp.</span>
                        <input type='text' id='kredit_label_edit' value='<?php echo money("",@$kredit); ?>' class='mousetrap' readonly="readonly" style="margin:0"/>
                      </span>
                        <input type='hidden' id='kredit_edit' value='<?php echo @$kredit; ?>'/>
                        <input type='hidden' id='first_kredit' value='<?php echo @$kredit; ?>'/>
                      </td>
                    </tr>
                    <tr>
                      <td class='req'>Status Bayar</td>
                      <td>
                      <select name="status_lunas_edit_label" class="mousetrap"  id="status_lunas_edit_label" onchange="show_kredit('edit')" disabled="disabled">
                        <option value='2' <?php if(!empty($status_lunas) && $status_lunas == "2"){?>selected<?php } ?>>Lunas</option>
                        <option value='1' <?php if(!empty($status_lunas) && $status_lunas == "1"){?>selected<?php } ?>>Hutang</option>
                      </select>
                      <input type='hidden' id='status_lunas_edit' name='status_lunas_edit' value='<?php echo $status_lunas; ?>' />
                      </td>
                    </tr>
                    <?php if(!empty($status_lunas) && $status_lunas == 2){ $display_lunas = "style='display:none'"; }else{ $display_lunas = ""; } ?>
                    <tr class="div_kredit_edit" <?php echo @$display_lunas; ?>>
                      <td>No PO</td>
                      <td>
                      <input type='text' id='nopo_edit' value='<?php echo @$nopo; ?>' class='mousetrap' style='text-transform:uppercase'/>
                      </td>
                    </tr>
                      <tr class="div_kredit_edit" <?php echo @$display_lunas ?>>
                        <td>Termin Pembayaran</td>
                        <td>
                        <input type='text' id='termin_edit' value='<?php echo @$termin; ?>' class='mousetrap' style='text-transform:uppercase'readonly/>
                        <button class='btn' id='btn_termin'><i class="icon-plus"></i></button>
                        </td>
                      </tr>
                      <?php 
					  if(!empty($termin)){
						$termin_cond = "";
						if(!empty($downpay)){ $termin_cond = " AND ORDINAL != '1'"; }
						$q_termin = $db->query("SELECT * FROM ".$tpref."debt_credit_reminder WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CASH_FLOW='".$id_cash_flow."' AND DEBT_CREDIT='1' ");
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
                            <?php if($dt_termin->STATUS != 1){ ?>
								<script language="javascript">
                                    $('#dp_edit_<?php echo $dt_termin->ORDINAL; ?>').datepicker();
                                </script>
                            	<button class='btn beoro-btn' id='cancel_more' style='margin-left:3px' onclick="remove_tempo('<?php echo $dt_termin->ORDINAL; ?>','<?php echo $id_cash_flow; ?>')"><i class="icsw16-trashcan"></i></button>
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
                        <td>
                        <textarea id="keterangan_edit" class='mousetrap col-md-6' ><?php echo @$keterangan_edit; ?></textarea>
                        </td>
                      </tr>
                    <tr>
                      <td >&nbsp;</td>
                      <td><button type="button" class="btn btn-sempoa-1" id="btn_direction_edit"  style='float:left' onclick="do_direction('<?php echo $direction; ?>','<?php echo $id_product; ?>')">Simpan Data</button>
                        <span id='load_edit'></span>
                        <input type='hidden' id="tgljs" value='<?php echo date("d-m-Y"); ?>'/>
                        </td>
                    </tr>
                  </tbody>
                </table>
                </td>
              </tr>
    </table>
<?php }else{
	defined('mainload') or die('Restricted Access');
}
 ?>