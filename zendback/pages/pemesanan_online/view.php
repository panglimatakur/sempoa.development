<?php defined('mainload') or die('Restricted Access'); ?>

<div class="ibox float-e-margins">
    <div class="ibox-content">
    <form method="post" action="" >
        <?php if($_SESSION['uclevelkey'] == 1){?>
        <div class="form-group col-md-6">
            <label>Daftar Client</label>
            <select name="id_merchant" id="id_merchant" class="form-control" />
                <option value=''>--PILIH CLIENT--</option>
                <?php
                while($data_branch = $db->fetchNextObject($query_branch)){
                ?>
                    <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_merchant) && $id_merchant == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?>
                    </option>
            <?php } ?>
            </select>
        </div>
        <?php } ?>
        <div class="form-group col-md-6">
            <label>Status Proses Pesanan</label>
            <select name="statlun" class="form-control" >
                <option value='3' <?php if(!empty($statlun) && $statlun == '3'){?> selected <?php } ?>>MENUNGGU</option>
                <option value='4' <?php if(!empty($statlun) && $statlun == '4'){?> selected <?php } ?>>PESANAN DITERIMA</option>
                <option value='5' <?php if(!empty($statlun) && $statlun == '5'){?> selected <?php } ?>>PESANAN DIKIRIM</option>
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
        <h5>Daftar Pemesanan Online Pelanggan</h4>
    </div>
    <div class="ibox-content">
    <?php
        if($num_cart_cust > 0){
    ?>
            <table width="100%" class="table table-bordered table-long table-striped" id="table_data">
                <thead>
                    <tr>
                      <th width="88">
                      </th>
                      <th width="834">&nbsp;</th>
                      <th width="156" style='text-align:center'>ACTION</th>
                    </tr>
                </thead>
                <tbody>
            <?php
                while($dt_cart_cust = $db->fetchNextObject($q_cart_cust)){
            ?>
              <tr id="div_<?php echo $dt_cart_cust->ID_CUSTOMER; ?>">
                <td height="93" style="text-align:center">
                    <?php if(is_file($basepath."/files/images/members/".$dt_cart_cust->CUSTOMER_PHOTO)){?>
                        <img src="<?php echo $dirhost; ?>/files/images/members/<?php echo $dt_cart_cust->CUSTOMER_PHOTO; ?>"  class='thumbnail' width="90%"/>
                    <?php }else{ ?>
                        <img src="<?php echo $dirhost; ?>/files/images/noimage-m.jpg" class='thumbnail' width="90%"/>
                    <?php }?>
                    
                </td>
                <td valign="top">
                    <table width="100%">
                    <?php
                    $q_cart = $db->query("SELECT * FROM ".$tpref."customers_carts WHERE ID_CUSTOMER = '".$dt_cart_cust->ID_CUSTOMER."' AND ID_CLIENT = '".$dt_cart_cust->ID_CLIENT."'");
                    while($dt_cart = $db->fetchNextObject($q_cart)){
                        $dt_product = get_product_info($dt_cart->ID_PRODUCT,"90%");
                        ?>
                        <tr>
                           <td width="10%"><?php echo $dt_product['photo']; ?>&nbsp;</td>
                           <td width="90%" class="deal_tbl">
                                <b><?php echo $dt_product['name']; ?></b>
                                <table width="100%" class='tbl_content'>
                                    <tr>
                                        <td width="19%">Harga</td>
                                        <td width="81%">: <?php echo money("Rp.",$dt_product['price']); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah</td>
                                        <td>: <?php echo $dt_cart->AMOUNT; ?> <?php echo $dt_product['unit']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Discount</td>
                                        <td>: <?php echo $dt_cart->DISCOUNT; ?> %</td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td>: <?php echo money("Rp.",$dt_cart->TOTAL_PRICE); ?></td>
                                    </tr>
                                </table>
                           </td>
                        </tr>
                    <?php }?> 
                    </table>
                </td>
                <td>
                    <span id="v_loader_<?php echo $dt_cart_cust->ID_CUSTOMER; ?>"></span>
                    <select id="st_<?php echo $dt_cart_cust->ID_CUSTOMER; ?>" onchange="set_status('<?php echo $dt_cart_cust->ID_CUSTOMER; ?>','<?php echo @$dt_cart_cust->ID_CLIENT; ?>')" style='margin-top:6px;'>
                        <option value='3' <?php if($dt_cart_cust->STATUS == '3'){?> selected <?php } ?>>MENUNGGU</option>
                        <option value='4' <?php if($dt_cart_cust->STATUS == '4'){?> selected <?php } ?>>PESANAN DITERIMA</option>
                        <option value='5' <?php if($dt_cart_cust->STATUS == '5'){?> selected <?php } ?>>PESANAN DIKIRIM</option>
                    </select>
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
        <div class="alert alert-danger">Tidak ada Pemesanan yang terjadi saat ini</div>
    <?php }?>
    </div>
</div>