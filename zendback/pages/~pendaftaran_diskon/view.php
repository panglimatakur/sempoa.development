<?php defined('mainload') or die('Restricted Access'); ?>
<div class="row-fluid">
<style type="text/css">
.onwrite{
	padding:2px;
	margin:3px;
	font-size:11px;
}	
.pframe{
	-webkit-box-shadow: 0 1px 2px #999;
	-moz-box-shadow: 0 1px 2px #999;
	-ms-box-shadow: 0 1px 2px #999;
	-o-box-shadow: 0 1px 2px #999;
	box-shadow: 0 1px 2px #999;
	padding:4px; 
	margin-right:2px; 
	float:left; 
	text-align:center;
	background:#FFF;
}
.pframe label .code{
	font-size:2vmin;
}

.elm{
	-webkit-box-shadow: 0 1px 2px #999;
	-moz-box-shadow: 0 1px 2px #999;
	-ms-box-shadow: 0 1px 2px #999;
	-o-box-shadow: 0 1px 2px #999;
	box-shadow: 0 1px 2px #999;
	padding:0; 
}
</style>

<div class="col-md-12">
    <div class="ibox float-e-margins" >
        <div class="ibox-title">
            <h4>Promo Diskon Reguler</h4>
        </div>
        <div class="ibox-content" >
            <p class='alert alert-info'>
                <span style="color:#900; font-weight:bold; ">Catatan : </span><br />
                Pola diskon yang berlaku di Discoin Community antara lain
                <?php
                    while($dt_discount_pattern = $db->fetchNextObject($q_discount_pattern)){
                ?>
                        <?php echo $dt_discount_pattern->DESCRIPTION; ?>,
                <?php } ?> , dan minimal masing-masing diskon yang di tawarkan lebih besar dari 10%, silahkan tentukan salah satu atau lebih penawaran diskon anda, sesuai dengan kemampuan anda.
            </p>
            <p class='alert alert-warning'>
                <span style="color:#900; font-weight:bold;">Peringatan :</span><br />
                Mohon formulir diskon ini selalu terisi atau jangan di biarkan kosong, dikarenakan, jika Sistem Sempoa Discoin tidak menemukan 1 (Satu) atau lebih informasi diskon yang anda berlakukan dan berikan kepada pelanggan komunitas anda, maka merchant anda akan secara otomatis tersembunyi di antara komunitas anda,  dan hanya bisa di akses oleh pelanggan anda sendiri, dan cluster anda di dalam komunitas berpotensi di ambil alih oleh merchant lain.
            </p>
    
            <p class='alert alert-info' style="">
                <span style="color:#900; font-weight:bold; ">Contoh :</span><br />
                <img src="<?php echo $dirhost; ?>/files/images/sample_diskon.png" />
            </p>
        </div>
    </div>  
</div>
<div id="msg"></div>    
<div id="ans_content">
    <?php
        $q_discount		= $db->query("SELECT * FROM ".$tpref."client_discounts WHERE ID_CLIENT='".$id_client."' AND REQUEST_BY_ID_CUSTOMER = '0' ");
        $num_discount	= $db->numRows($q_discount);
    if($num_discount > 0){
        $e = 0;
        while($dt_discount = $db->fetchNextObject($q_discount)){
        $e++;
        $tgl 				= "";
        $bln 				= "";
        $thn 				= "";
        @$id_product_discs 	= $dt_discount->ID_PRODUCTS;
        @$id_products 		= explode(";",str_replace(",","",$id_product_discs)); 
        @$id_diskon 		= $dt_discount->ID_DISCOUNT;
        @$jml_beli 			= $dt_discount->BUY_SUMMARY;
        @$besar_edit 		= $dt_discount->VALUE;
        @$satuan_edit 		= $dt_discount->PIECE;
        @$pre_order 		= $dt_discount->PRE_ORDER_STATUS;
        @$quota 			= $dt_discount->PO_QUOTA;
        @$satuan 			= $dt_discount->PO_ID_UNIT;
        @$pattern 			= $dt_discount->ID_DISCOUNT_PATTERN;
        @$keterangan_edit 	= $dt_discount->STATEMENT;
        @$expiration_edit 	= $dt_discount->EXPIRATION_DATE;
        if(!empty($expiration_edit) && $expiration_edit != "0000-00-00"){
            @$ex_date 			= explode("-",$expiration_edit);
            $tgl 				= $ex_date[2];
            $bln 				= $ex_date[1];
            $thn 				= $ex_date[0];
        }
        @$condition			= "";
        $show 				= "pattern";
        $id_box 			= $e;
        $freeze				= "";
    
        if((!empty($pre_order) && $pre_order == 1)){
            $freeze = $db->recount("SELECT ID_CART FROM ".$tpref."customers_carts WHERE ID_CLIENT = '".$id_client."' AND STATUS ='1' AND ID_DISCOUNT = '".$id_diskon."'");
        }
        
    ?>
        <div class="col-md-4">
            <div class='ibox float-e-margins  elm' id="pilihan_<?php echo $e; ?>" >
                <div class="ibox-title option">Diskon Komunitas</div>
                <div class="ibox-content">
                
                
                    <div id="diskon_load_<?php echo $e; ?>" class='diskon_load'></div>
                    <input type="hidden" class="index" value='<?php echo $e; ?>' />
                    <input type="hidden" class='id_diskon' 
                           id="id_diskon<?php echo $e; ?>" 
                           value='<?php echo $id_diskon; ?>' />
                    <input type="hidden" id="formember<?php echo $e; ?>" class='formember' value="community"/> 
                    <input type="hidden" 
                            id="satuan<?php echo $e; ?>" 
                           class='satuan' value='persen'> 
                           
                           
                    <?php if(!empty($freeze)){?> 
                    <div class="alert alert-warning">
                        Maaf, form Diskon Reguler 1 ini belum bisa di perbaiki, karena terdapat pembayaran pre-order konsumen yang masih menunggu, Terimakasih
                    </div>
                    <?php } ?>
                    <div class="form-group">  
                        <label class="req">Diskon</label>
                        <div class="input-group">
                        <input type="text" 
                               id="besar<?php echo $e; ?>" class='form-control besar'
                               placeholder="Besar Diskon (Min 10%)" value="<?php echo @$besar_edit; ?>" 
                               onblur="check_discount(this)" 
                               maxlength="3"/>
                               <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        </div>
                    </div>
                    <div class="form-group col-md-5 no-padding-l">  
                        <label class="req">Pola Diskon</label>
                        <select id="pattern<?php echo $e; ?>" class='form-control pattern' onchange="change_pattern('<?php echo $e; ?>',this)">
                            <option value=''>-- PILIH POLA DISKON --</option>
                            <option value="1" <?php if($pattern == 1){?> selected <?php } ?>>Produk</option>
                            <option value="2" <?php if($pattern == 2){?> selected <?php } ?>>Pembelian</option>
                        </select>  
                    </div>
                    <span id="div_pattern<?php echo $e; ?>" class="div_pattern">
                        <?php include $call->inc($ajax_dir,"data.php"); ?>
                    </span>
                    
                    <div class="clearfix"></div>
                    
                    <div id="div_product<?php echo $e; ?>" class="div_product" style=" <?php if(empty($id_product_discs)){?>display:none<?php } ?>">
                        <a href="javascript:void()" 
                           class="btn btn-block btn-sempoa-3 bpick prod_location" 
                           title="Tombol ini berfungsi untuk memilih item/produk yang akan di berikan diskon" id="prod_location<?php echo $e; ?>"  
                           onclick="open_product('<?php echo $last_index; ?>',this);" 
                           data-direction="<?php echo $dirhost; ?>/zendback/pages/diskon_reguler/ajax/product_list.php?page=<?php echo $page; ?>&id_disc=<?php echo $e; ?>">
                            <i class="fa fa-file-image-o"></i> Pilih ITEM Diskon
                        </a>
                        <div class="form-group">
                            <small class="code">
                                Contoh foto item/produk diskon ini boleh diisi jika pola diskon yang di berikan berbentuk barang dan diskon yang di berikan untuk 1 (satu) atau beberapa item/produk barang
                            </small>
                        </div>
                        <div class="prod_box">
                            <br clear="all" />
                            <span class="pic_c" id="pic_<?php echo $e; ?>">
                            <?php $r=0; 
                            foreach($id_products as &$id_product){
                                if(!empty($id_product)){
                                    $r++; 
                                    if($r == 1){ $op = "AND ("; }else{ $op = "OR"; }
                                    $condition .= $op." ID_PRODUCT = '".$id_product."'"; 
                                }
                            } 
                            $q_product = $db->query("SELECT * FROM ".$tpref."products WHERE ID_CLIENT = '".$dt_discount->ID_CLIENT."' ".$condition.")");
                            while($dt_product = $db->fetchNextObject($q_product)){
                                @$photo = $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_product->ID_PRODUCT."'");
                            ?>
                                <div class='pframe' id='pframe_<?php echo $id_product; ?>' style='margin-left:4px'>
                                    <a href='javascript:void()' style='float:right' onclick='del_pic("<?php echo $id_diskon; ?>","<?php echo $id_product; ?>","pilihan_<?php echo $id_diskon; ?>","pframe_<?php echo $id_product; ?>")'>
                                        <i class='icon-remove'></i>
                                    </a>
                                    <label><b><small class='code'><?php echo $dt_product->CODE; ?></small></b></label>
                                    <?php 
                                    if(is_file($basepath."/files/images/products/".$dt_product->ID_CLIENT."/".$photo)){ ?>
                                        <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $dt_product->ID_CLIENT; ?>/thumbnails/<?php echo $photo; ?>' class='photo' style='height:65px'/>
                                    <?php }else{ ?>
                                        <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class='photo' style='height:65px'/>
                                  <?php } ?>
                                    <input type='hidden' class='pic_h' id='id_product_<?php echo $id_diskon; ?>' value='<?php echo $id_product; ?>'>
                                    <br clear='all'>
                                </div>
                            <?php } ?>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>            
                        <textarea id="keterangan<?php echo $e; ?>" class='form-control keterangan' placeholder="Keterangan"><?php echo @$keterangan_edit; ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Berlaku Hingga</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="expiration" name="expiration" value="">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>                                  
                    </div>
                    <?php if(empty($freeze) && $freeze == 0){?>                         
                    <div class='form-group'>
                        <button type="button" class='btn btn-block btn-sempoa-1 insert-btn' style="margin:0" onclick="save_diskon('<?php echo $e; ?>')">
                        <i class="icsw16-box-outgoing icsw16-white"></i> Simpan Diskon</button>
                        <!--<button type="button" class="btn btn-beoro-2 cancel-btn" style="margin-left:4px" id="cancel_more" onclick="removal('<?php //echo $dt_discount->ID_DISCOUNT; ?>','<?php //echo $e;?>')"><i class="icsw16-trashcan icsw16-white"></i>Hapus</button>-->
                        
                    </div>
                    <?php } ?>
                    
                    
                    
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    <?php } 
        $last_index = $e+1;
    }else{
        $last_index = 1;	
    }
    if($last_index == 1){
    $tgl = date("d");
    $bln = date("m");
    $thn = date("Y");
    
    $item_discount = $db->recount("SELECT ID_CLIENT FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_DISCOUNT_PATTERN = '1' AND DISCOUNT_STATUS='3' AND COMMUNITY_FLAG = '1'  ");
    
    $buy_discount = $db->recount("SELECT ID_CLIENT FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_DISCOUNT_PATTERN = '2' AND DISCOUNT_STATUS='3' AND COMMUNITY_FLAG = '1'");

    ?>
    <div class="col-md-6">
        <div class='ibox float-e-margins  elm' id="pilihan_<?php echo $last_index; ?>" >
            <div class="ibox-title option">Diskon Reguler <?php echo $last_index; ?></div>
                <div class="ibox-content">
                
                    <div id="diskon_load_<?php echo $last_index; ?>" class='diskon_load'></div>
                    <input type="hidden" class="index" value='<?php echo $last_index; ?>' />
                    <input type="hidden" 
                           class='id_diskon' 
                           id="id_diskon<?php echo $last_index; ?>" 
                           value='<?php echo $id_diskon; ?>' />
                    <input type="hidden" 
                            id="satuan<?php echo $e; ?>" 
                           class='satuan' value='persen'> 
                           
                    <div class='form-group col-md-6'> 
                        <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Berlaku untuk siapakah diskon yang akan anda rancang dibawah ini berlaku ? pelanggan dari merchant komunitas anda kah? atau untuk pelangan anda sendiri ?" >Untuk Member</label>
                        <select id="formember<?php echo $last_index; ?>" class='form-control formember'>
                            <option value="community" data-id='<?php echo $last_index; ?>'>Komunitas</option>
                            <option value="customer"  data-id='<?php echo $last_index; ?>'><?php echo ucwords(strtolower($nm_merchant)); ?></option>
                        </select>  
                    </div>
                    <div class='form-group col-md-6'> 
                        <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Berapa besaran diskon yang ingin anda berlakukan untuk pelanggan ? minimal 10% ya.. :D">Diskon</label>
                        <div class="input-group">
                        <input type="text" id="besar<?php echo $last_index; ?>" class='form-control besar' placeholder="Besar Diskon (Min 10%)" value="" onblur="check_discount(this)" maxlength="3"/>
                            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                                    
                    <div class='form-group col-md-6'> 
                        <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Tentukan pola diskon yang ingin anda berlakukan, berdasarkan item kah ? atau berdasarkan jumlah total pembelian kah ?">Pola Diskon</label>
                        <select id="pattern<?php echo $last_index; ?>" 
                        		class='form-control pattern' 
                                onchange="change_pattern('<?php echo $last_index; ?>',this)">
                            <option value=''>-- PILIH POLA DISKON --</option>
                                <option value="1" >Berdasarkan Item </option>
                                <option value="2" >Berdasarkan Pembelian</option>
                        </select>
                    </div>
                    
                    <span id="div_pattern<?php echo $last_index; ?>" class="div_pattern"></span>
                    
                    <div id="div_product<?php echo $last_index; ?>" class="div_product" style="display:none">
                        <a href="<?php echo $dirhost; ?>/modules/diskon_reguler/ajax/product_list.php?page=<?php echo $page; ?>&id_disc=<?php echo $last_index; ?>" class="btn ptip_ne bpick fancybox fancybox.ajax prod_location" title="Tombol ini berfungsi untuk memilih item/produk yang akan di berikan diskon" style="width:92%" id="prod_location<?php echo $last_index; ?>">
                            <i class="icsw16-image-2"></i> Pilih Produk Diskon
                        </a>
                        <div style="padding:5px;">
                            <small class="code">
                                Contoh foto item/produk diskon ini boleh diisi jika pola diskon yang di berikan berbentuk barang dan diskon yang di berikan untuk 1 (satu) atau beberapa item/produk barang
                            </small>
                        </div>
                        <div class="prod_box">
                            <br clear="all" />
                            <span class="pic_c" id="pic_<?php echo $last_index; ?>"></span>
                        </div>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label>Berlaku Hingga</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="expiration" name="expiration" value="">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>                                  
                    </div>
                    <div class='form-group col-md-12'>
                        <label>Keterangan</label>            
                        <textarea id="keterangan<?php echo $last_index; ?>" class='form-control keterangan' placeholder="Keterangan" ></textarea>
                    </div>
                    <div class='form-group col-md-12'>
                        <?php if(empty($ch_deal) || $ch_deal == 0){?>
                        <button type="button" class='btn btn-sempoa-1 btn-block btn-lg insert-btn' style="margin:0" onclick="insert_diskon('<?php echo $last_index; ?>')">
                        <i class="fa fa-check-square-o"></i> Simpan Diskon</button>
                        <?php }else{ ?>
                            <div class="alert alert-info" style="margin-bottom:0; padding:4px;">Terjual : <?php echo $ch_deal; ?> / <?php echo $jml_voucher_edit; ?> Voucher</div>
                        <?php } ?>
                    </div>
                  	
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    <!--<button type="button" class="btn btn-beoro-3" style='padding:10px; width:20%; margin:8px 0 4px 0' id="add_more">
        <i class="icsw32-create-write icsw32-white"></i> <b>Tambah Form Diskon</b>
    </button>-->
    </div>
    <?php } ?>
    <div class="clearfix"></div>
</div>

<input type="hidden" 
       id="proses_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php" />
<input type="hidden" 
       id="data_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php" />
