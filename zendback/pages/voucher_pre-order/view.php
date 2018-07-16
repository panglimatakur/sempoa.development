<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h4>Deal Hari Ini </h4>
    </div>
    <div class="ibox-content">
        <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped">
            <?php
                while($dt_deal = $db->fetchNextObject($q_deal)){
                    $new_price	   = "";
                    @$status_deal   = $dt_deal->STATUS_DEAL;
                    $id_customer   = $dt_deal->ID_CUSTOMER;
                    @$nm_customer  = $db->fob("CUSTOMER_NAME",$tpref."customers"," WHERE ID_CUSTOMER='".$dt_deal->REQUEST_BY_ID_CUSTOMER."'");
                    if(empty($dt_deal->CURRENT_DEAL)){ $current_deal = 0; }
                    @$id_deal	   = transletNum($dt_deal->ID_DISCOUNT);
                    $array		   = array(";",",");
                    $id_product    = str_replace($array,"",$dt_deal->ID_PRODUCTS);
                                    
                    $q_product 	   = $db->query("SELECT NAME,SALE_PRICE FROM ".$tpref."products WHERE ID_PRODUCT='".$id_product."'");
                    $dt_product	   = $db->fetchNextObject($q_product);
                    @$nm_product   = $dt_product->NAME; 
                    @$hrg_product  = $dt_product->SALE_PRICE;
                    if(!empty($hrg_product)){
                        $new_disc	   = ($hrg_product/100)*$dt_deal->VALUE;
                        $new_price	   = $hrg_product-$new_disc;
                    }
                    $photo		   = $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$id_product."'");
                    @$current_paid    = $db->recount("SELECT ID_PRODUCT_DEAL FROM ".$tpref."customers_dealers WHERE ID_PRODUCT_DEAL = '".$dt_deal->ID_DISCOUNT."' AND PAID_STATUS = '3'");
                    if(empty($current_paid)){ $current_paid = 0; }
                    @$current_deal    = $db->fob("PAID_STATUS",$tpref."customers_dealers","WHERE ID_PRODUCT_DEAL = '".$dt_deal->ID_DISCOUNT."'");
            ?>
            <tr id="tr_<?php echo $dt_deal->ID_DISCOUNT; ?>">
                <td>

                <table width="100%" class="table">
                    <tr>
                      <td width="10%" rowspan="6" align="center" valign="top" style="padding:4px;">
                            <?php if(!empty($photo) && is_file($basepath."/".$img_dir."/products/".$dt_deal->ID_CLIENT."/thumbnails/".$photo)){ ?>
                            <a href="<?php echo $dirhost; ?>/pages/merchant/ajax/produk.php?no=<?php echo $dt_produk->ID_PRODUCT; ?>" class="fancybox fancybox.ajax">
                                <img src='<?php echo $dirhost; ?>/<?php echo $img_dir; ?>/products/<?php echo $dt_deal->ID_CLIENT; ?>/thumbnails/<?php echo $photo; ?>' class="thumbnail" style="width:100px;"/>
                            </a>
                            <?php }else{ ?>
                                <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="thumbnail" style="width:100px;"/>
                      <?php } ?>          
                      </td>
                      <td align="center">ID DEAL / DEALER</td>
                      <td>#<?php echo $id_deal; ?> / <?php echo @$nm_customer; ?></td>
                    </tr>
                    <tr>
                      <td width="22%" valign="top">Nama Produk</td>
                        <td width="68%" valign="top"><?php echo $nm_product; ?></td>
                    </tr>
                    <tr>
                      <td>Diskon (%)</td>
                      <td><?php echo $dt_deal->VALUE; ?> %</td>
                    </tr>
                    <tr>
                      <td width="22%" valign="top">Harga Produk</td>
                        <td width="68%" valign="top">
                            <?php if(!empty($hrg_product)){?>
                            <span style="text-decoration:line-through" class="code"><?php echo money("Rp.",$hrg_product); ?> </span> 
                            &nbsp; 
                            <b><?php echo money("Rp.",$new_price); ?> </b>
                            <?php }else{ echo "0"; } ?>
                        </td>
                    </tr>
                    <tr>
                      <td >Voucher</td>
                      <td >
                      <?php if(!empty($dt_deal->VOUCHER_NUM)){?>
                        <?php echo @$current_paid; ?> / <?php echo $dt_deal->VOUCHER_NUM; ?> Voucher
                      <?php }else{ echo "0"; } ?>
                      </td>
                    </tr>
                    <tr>
                      <td valign="top">Masa Berlaku</td>
                      <td valign="top">
                        <b class='code' style="font-size:12px">
                        <?php if(!empty($dt_deal->EXPIRATION_DATE) && $dt_deal->EXPIRATION_DATE != "0000-00-00"){?>
                            <?php echo $dtime->now2indodate2($dt_deal->EXPIRATION_DATE); ?>
                      <?php }else{ echo "Belum Ditentukan"; } ?>
                        </b>
                       </td>
                    </tr>
                    <tr class="ibox-title">
                      <td valign="top" >&nbsp;</td>
                      <td colspan="2" >
                      <?php if(empty($current_deal) || @$current_deal == 0){?>
                        <a href="#deal_<?php echo $dt_deal->ID_DISCOUNT; ?>" class="fancybox" id="btn_voucher_<?php echo $dt_deal->ID_DISCOUNT; ?>">
                            <button type="button" class="btn">
                                <i class="icsw16-tags-2"></i> Cetak Voucher
                            </button>
                        </a>
                        <button type="button" class="btn" onclick="refuse('<?php echo $dt_deal->ID_DISCOUNT; ?>','<?php echo $id_deal; ?>')">
                            <i class="icsw16-acces-denied-sign"></i> Tolak Deal
                        </button>
                        <span id="tr_loader_<?php echo $dt_deal->ID_DISCOUNT; ?>"></span>
                        <div id="deal_<?php echo $dt_deal->ID_DISCOUNT; ?>" style="display: none; text-align:center; font-size:11px" >
                            <b>Silahkan tentukan deal untuk produk / item ini;</b>
                            <span id="deal_loader_<?php echo $dt_deal->ID_DISCOUNT; ?>"></span>
                            <table width="100%" style="font-size:11px; text-align:left">
                                <tr>
                                    <td width="170" style="vertical-align:middle">Diskon</td>
                                    <td>
                                    <input type="text" id="disc_<?php echo $dt_deal->ID_DISCOUNT; ?>" style="width:50px; margin:0" onkeyup="just_num(this)" value="<?php echo @$dt_deal->VALUE; ?>"> %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle">Jumlah Voucher</td>
                                    <td>
                                    <input type="text" id="voucher_<?php echo $dt_deal->ID_DISCOUNT; ?>" style="width:50px; margin:0" onkeyup="just_num(this)" value="<?php echo @$dt_deal->VOUCHER_NUM; ?>"> Voucher
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:middle">Masa Berlaku Voucher</td>
                                    <td>
                                        <?php $expdate = date("d-m-Y"); ?>
                                        <span class="input-append date" id="exp_<?php echo $dt_deal->ID_DISCOUNT; ?>" data-date="<?php echo $expdate; ?>" data-date-format="dd-mm-yyyy">
                                            <input size="16" value="<?php echo $expdate; ?>" readonly="" type="text" id="expired_<?php echo $dt_deal->ID_DISCOUNT; ?>">
                                            <span class="add-on"><i class="icsw16-day-calendar"></i></i></span>
                                        </span>  
                                        <script language="javascript">
                                            if($("#exp_<?php echo $dt_deal->ID_DISCOUNT; ?>").length > 0){
                                                $("#exp_<?php echo $dt_deal->ID_DISCOUNT; ?>").datepicker();
                                            }
                                        </script>
                                                              
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td style="padding:4px 0 0 0">
                                        <button class="btn btn-sempoa-2" onclick="send_deal('<?php echo $id_customer; ?>','<?php echo $dt_deal->ID_DISCOUNT; ?>')"><i class="icsw16-facebook-like-2 icsw16-white"></i> Kunci Deal & Cetak Voucher</button>
                                    </td>
                                </tr>
                            </table>
                            
                        </div>
                       <?php }else{ ?>
                            <a href="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php?show=dealers&id_deal=<?php echo $dt_deal->ID_DISCOUNT; ?>" class="fancybox fancybox.ajax">
                                <button type="button" class="btn btn-sempoa-1">
                                    <i class="icsw16-magnifying-glass"></i> Lihat Dealer
                                </button>
                            </a>
                       <?php } ?>
                      </td>
                    </tr>
                </table>

                </td>
            </tr>
            <?php } ?>
        </table>
        <input type='hidden' id='proses_page' value='<?php echo $dirhost."/".$ajax_dir."/proses.php"; ?>' />
        <input type='hidden' id='data_page' value='<?php echo $dirhost."/".$ajax_dir."/data.php"; ?>' />
    </div>
</div>
