<?php defined('mainload') or die('Restricted Access'); ?>
    <div class="ibox float-e-margins">
        <div class="ibox-content">
        <form method="post" action="" >
            <div class="form-group col-md-6">
                <label>Status Lunas</label>
                <select name="statlun" class="form-control">
                    <option value='0' <?php if(empty($statlun)){?> selected <?php } ?>>DILIHAT</option>
                    <option value='2' <?php if(!empty($statlun) && $statlun == '2'){?> selected <?php } ?>>KONFIRMASI</option>
                    <option value='3' <?php if(!empty($statlun) && $statlun == '3'){?> selected <?php } ?>>LUNAS</option>
                    <option value='4' <?php if(!empty($statlun) && $statlun == '4'){?> selected <?php } ?>>DITERIMA</option>
                    <option value='5' <?php if(!empty($statlun) && $statlun == '5'){?> selected <?php } ?>>DIKIRIM</option>
                </select>
            
            </div>
            <div class="form-group col-md-6">
            	<label>&nbsp;</label><br />
                <button type="submit" class="btn btn-sempoa-1"><i class="fa fa-eye"></i> Lihat Data</button>
            </div>
            <div class="clearfix"></div> 
        </form>
    	</div>
		<div id="vera"></div> 
    </div>
    
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Pembayaran Online</h4>
        </div>
        <div class="ibox-content">
		<?php if($num_pur > 0){ ?>
            <table width="100%" class="table table-bordered table-long table-striped" id="table_data">
                <thead>
                    <tr>
                      <th width="13" class="table_checkbox" style="width:13px; text-align:center">
                        
                      </th>
                      <th width="63">
                      </th>
                      <th width="129" style="text-align:left">Konsumen</th>
                      <th width="177" style="text-align:left">Transaksi</th>
                      <th width="145">Info Bank</th>
                      <th width="221">Alamat Antar</th>
                      <th width="222" style='text-align:center'>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                while($dt_pur = $db->fetchNextObject($q_purchase)){
                    @$nm_propinsi	= $db->fob("NAME","system_master_location"," WHERE ID_LOCATION = '".$dt_pur->TO_ID_PROVINCE."' AND PARENT_ID = '0'");
                    @$nm_kota		= $db->fob("NAME","system_master_location"," WHERE PARENT_ID = '".$dt_pur->TO_ID_PROVINCE."' ");
                    @$nm_kecamatan	= $db->fob("NAME","system_master_location"," WHERE PARENT_ID = '".$dt_pur->TO_ID_CITY."' ");
                    @$nm_kelurahan	= $db->fob("NAME","system_master_location"," WHERE PARENT_ID = '".$dt_pur->TO_ID_DISTRICT."' ");
                    
                    $q_cust 	= $db->query("SELECT CUSTOMER_PHOTO,CUSTOMER_NAME,CUSTOMER_EMAIL,CUSTOMER_PHONE,CUSTOMER_ADDRESS FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$dt_pur->ID_CUSTOMER."'");
                    $dt_cust 	= $db->fetchNextObject($q_cust);
                    
                    $ori_price	= $dt_pur->PAID;
            ?>
                      <tr id="div_<?php echo $dt_pur->ID_PURCHASE; ?>" style="text-align:center">
                        <td height="93">
                        </td>
                        <td>
                            <?php if(is_file($basepath."/files/images/members/".$dt_cust->CUSTOMER_PHOTO)){?>
                                <img src="<?php echo $dirhost; ?>/files/images/members/<?php echo $dt_cust->CUSTOMER_PHOTO; ?>"  class='thumbnail' width="90%"/>
                            <?php }else{ ?>
                                <img src="<?php echo $dirhost; ?>/files/images/noimage-m.jpg" class='thumbnail' width="90%"/>
                            <?php }?>
                            
                        </td>
                        <td style="text-align:left">
                            <?php echo $dt_cust->CUSTOMER_NAME; ?><br>
                            <?php echo $dt_cust->CUSTOMER_EMAIL; ?><br>
                            <?php echo $dt_cust->CUSTOMER_PHONE; ?><br>
                            <?php echo $dt_cust->CUSTOMER_ADDRESS; ?><br>
                            <a href='<?php echo $ajax_dir; ?>/data.php?id_purchase=<?php echo $dt_pur->ID_PURCHASE; ?>' class="fancybox fancybox.ajax">
                               <button type='button' class='btn btn-small'><i class="icsw16-shopping-cart-3"></i> Lihat Belanja</button>
                            </a>
                        </td>
                        <td style="text-align:left">
                          <b>Kode Bayar :</b> <br />
                            <div style="margin-left:10px"><?php echo $dt_pur->PAYMENT_CODE; ?></div>
                          <b>Jumlah Bayar :</b> <br />
                                <div style="margin-left:10px"><?php echo money("Rp.",$ori_price); ?></div>
                          <b>Tanggal Bayar :</b> <br />
                            <div style="margin-left:10px"><?php echo $dt_pur->PAID_DATETIME; ?></div>
                        </td>
                        <td>
                            <?php echo $dt_pur->BANK_NAME; ?><br />
                            <?php echo $dt_pur->BANK_ACCOUNT_NAME; ?><br />
                            <?php echo $dt_pur->BANK_ACCOUNT_NUMBER; ?>
                        </td>
                        <td>
                            <?php echo $dt_pur->TO_ADDRESS." - ".$nm_kelurahan." - ".$nm_kecamatan." - ".$nm_kota." - ".$nm_propinsi; ?>
                        </td>
                        <td style="text-align:left">
                            <span id="v_loader_<?php echo $dt_pur->ID_PURCHASE; ?>"></span>
                            <select id="st_<?php echo $dt_pur->ID_PURCHASE; ?>" onchange="set_status('<?php echo $dt_pur->ID_PURCHASE; ?>')" style='margin-top:6px;'>
                                <option value='0' <?php if(empty($dt_pur->PAID_STATUS)){?> selected <?php } ?>>DILIHAT</option>
                                <option value='2' <?php if($dt_pur->PAID_STATUS == '2'){?> selected <?php } ?>>KONFIRMASI</option>
                                <option value='3' <?php if($dt_pur->PAID_STATUS == '3'){?> selected <?php } ?>>LUNAS</option>
                                <option value='4' <?php if($dt_pur->PAID_STATUS == '4'){?> selected <?php } ?>>DITERIMA</option>
                                <option value='5' <?php if($dt_pur->PAID_STATUS == '5'){?> selected <?php } ?>>DIKIRIM</option>
                            </select>
                            <div>
                            <input type="checkbox" id="customer_st_<?php echo $dt_pur->ID_PURCHASE; ?>" value="1"/> 
                            Notif Ke Pembeli
                            <br />
                            <input type="checkbox" id="user_st_<?php echo $dt_pur->ID_PURCHASE; ?>" value="1"/>
                            Notif Ke Penjual
                            </div>
                            
                        </td>
                      </tr>
            <?php } ?>
                </tbody>
            </table>
            
            <input type="hidden" id="data_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php"/>
            <input type="hidden" id="proses_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php"/>
            <div class="ibox-title">
                <form id="form_paging" class="formular" action="#report" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="direction" value="show" />
                <?php //echo pfoot($str_query,$link_str); ?>       
                </form>
            </div>
    
        <?php }else{ ?>
            <div class="alert alert-danger">Tidak ada Pembayaran yang terjadi saat ini</div>
        <?php }?>
        </div>
    </div>
