<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
	}else{
		defined('mainload') or die('Restricted Access');	
	}
	$id_box 	= isset($_REQUEST['id_box']) ? $sanitize->number($_REQUEST['id_box']) : "";
	$pattern 	= isset($_REQUEST['pattern']) ? $sanitize->str($_REQUEST['pattern']) : "";
	$show 		= isset($_REQUEST['show']) 		? $sanitize->str($_REQUEST['show']) 		: "";
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<?php
	
	if(!empty($show) && $show == "get_form"){
	$new_count 	= isset($_REQUEST['new_count']) 	? $sanitize->number($_REQUEST['new_count']) 		: "";
	$nm_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");
?>
	 	<div class="col-md-4 elm" style="margin:0 7px 5px 0;" id="pilihan_<?php echo $new_count; ?>">
            <div id="diskon_load_<?php echo $new_count; ?>" class="diskon_load"></div>
            <input type="hidden" class="index" value="<?php echo $new_count; ?>" />
            <input type="hidden" class='id_diskon' id="id_diskon<?php echo $new_count; ?>" value='<?php echo $id_diskon; ?>' />
            <label class="option w-box-header">Diskon Reguler <?php echo $new_count; ?></label>
            <div style="padding:5px;">
                <div class="form-group">
                    <label class="req">Diskon</label>
                    <input type="text" id="besar<?php echo $new_count; ?>" 
                           class='form-control besar' 
                           placeholder="Besar Diskon (Min 10%)" 
                           value="" 
                           onblur="check_discount(this)" 
                           maxlength="3"/>
                    <select id="satuan<?php echo $new_count; ?>" class='form-group satuan'>
                        <option value='persen'>
                            (%)
                        </option>
                    </select> 
                </div>  
                <div class="form-group">
                    <label class="req">Untuk Member</label>
                    <select id="formember<?php echo $new_count; ?>" class='form-control formember'>
                        <option value="community" data-id='<?php echo $new_count; ?>'>Komunitas</option>
                        <option value="customer" data-id='<?php echo $new_count; ?>'><?php echo ucwords(strtolower($nm_merchant)); ?></option>
                    </select>  
                </div>  
                <div class="form-group">
                    <label class="req">Pola Diskon</label>
                    <select id="pattern<?php echo $new_count; ?>" 
                    		class='form-control pattern' 
                    		onchange="change_pattern('<?php echo $new_count; ?>',this)">
                        <option value=''>-- PILIH POLA DISKON --</option>
                        <option value="1">Produk</option>
                        <option value="2">Pembelian</option>
                    </select>  
                </div>
                <span id="div_pattern<?php echo $new_count; ?>" class="div_pattern"></span>
                <div id="div_product<?php echo $new_count; ?>" class="div_product"  style="display:none">
                    <a href="<?php echo $dirhost; ?>/modules/diskon_reguler/ajax/product_list.php?page=conf.page+'&id_disc=<?php echo $new_count; ?>" class="btn ptip_ne bpick fancybox fancybox.ajax prod_location" title="Tombol ini berfungsi untuk memilih item/produk yang akan di berikan diskon" style="width:92%" id="prod_location<?php echo $new_count; ?>">
                        <i class="icsw16-image-2"></i> Pilih ITEM Diskon
                    </a>
                    <div style="padding:5px;">
                        <small class="code">
                            Contoh foto item/produk diskon ini boleh diisi jika pola diskon yang di berikan berbentuk barang dan diskon yang di berikan untuk 1 (satu) atau beberapa item/produk barang
                        </small>
                    </div>
                    <div class="prod_box">
                        <br clear="all" />
                        <span class="pic_c" id="pic_<?php echo $new_count; ?>"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>            
                    <textarea id="keterangan<?php echo $new_count; ?>" class='form-control keterangan' placeholder="Keterangan"></textarea>
                </div>
                <div class="form-group">
                <input type="hidden" id="for_community<?php echo $new_count; ?>" value="1"/>
                    <label>Berlaku Hingga :</label>
                    <select id="tgl_<?php echo $new_count; ?>" class="tgl" style="width:65px">
                        <option value="">TGL</option>
                        <?php 
                            $t = 0; $u = 0;
                            while($t<31){
                                $t++; $u++;
                                if(strlen($u) == 1){ $u = '0'.$u; }
                        ?>
                            <option value="<?php echo $t; ?>" >
                                <?php echo $u; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    <select id="bln_<?php echo $new_count; ?>" class="bln" style="width:65px">
                        <option value="">BLN</option>
                        <?php 
                            $t2 = 0; $u2 = 0;
                            while($t2<12){
                                $t2++; $u2++;
                                if(strlen($u2) == 1){ $u2 = '0'.$u2; }
                        ?>
                            <option value="<?php echo $t2; ?>">
                                <?php echo $u2; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>
                    <select id="thn_<?php echo $new_count; ?>" class="thn" style="width:100px">
                        <option value="">THN</option>
                        <?php 
                            $t3 = date('Y')-1;
                            while($t3<date('Y')+10){
                                $t3++;
                        ?>
                            <option value="<?php echo $t3; ?>">
                                <?php echo $t3; ?>
                            </option>
                        <?php
                            }
                        ?>
                    </select>   
                </div>                       
                <div class='form-group' style="padding:0; margin-top:5px;">
                    <?php if(empty($ch_deal) || $ch_deal == 0){?>
                    <button type="button" class='btn btn-sempoa-1' style="margin:0" onclick="insert_diskon('<?php echo $new_count; ?>')">
                    <i class="icsw16-box-outgoing icsw16-white"></i> Simpan Diskon
                    </button>
                    <button type="button" class="btn btn-sempoa-1" id="cancel_more" onclick="removal('<?php echo $dt_discount->ID_DISCOUNT; ?>','<?php echo $new_count; ?>')"><i class="icsw16-trashcan icsw16-white"></i>Hapus</button>
                    <?php }else{ ?>
                        <div class="alert alert-info" style="margin-bottom:0; padding:4px;">Terjual : <?php echo $ch_deal; ?> / <?php echo $jml_voucher_edit; ?> Voucher</div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php	
	}
	
	
	if(!empty($show) && $show == "pattern"){
		switch($pattern){
			case "2":
	?>
            <div class="form-group col-md-6">  
                <label class="req">Jumlah Pembelian</label>
                <input type="text" id="jumlah_beli<?php echo $id_box; ?>" class='form-control jumlah_beli' value='<?php echo @$jml_beli; ?>' onkeyup="jumlah_beli(this);" onblur="jumlah_beli(this);"/> 
            </div>
             <div class="form-group col-md-6">
             	<label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Apakah program diskon ini, bisa dibeli secara pre-order ?">Status Pre-Order</label>
                <br />
                <input type="hidden" id="pre_order_<?php echo $id_box; ?>"  />
                <input type		= "checkbox" 
                	   class	= "pre_order"
                       data-id 	= "<?php echo $id_box; ?>"
					   <?php if(!empty($pre_order) && $pre_order == 1){?> checked <?php }?>/> 
                       <!--onclick="open_min_order('<?php echo $id_box; ?>')"-->
            </div>
            <span id='label_order_<?php echo $id_box; ?>'>
				<?php if(!empty($satuan)){?>
                    <div class="form-group col-md-6">
                    <label class="req">Minimal Quota</label>
                    <input type="text" id="quota_<?php echo $id_box; ?>"  
                            class="form-control" value="<?php echo @$quota; ?>"/> 
                    </div>
                    <div class="form-group col-md-6">
                        <select class="form-control satuan" id="satuan_<?php echo $id_box; ?>">
                            <option value=''>--PILIH SATUAN--</option>
                            <?php
                            $query_unit = $db->query("SELECT * FROM ".$tpref."products_units ORDER BY NAME");
                            while($data_unit = $db->fetchNextObject($query_unit)){
                            ?>
                                <option value='<?php echo $data_unit->ID_PRODUCT_UNIT; ?>' <?php if(!empty($satuan) && $satuan == $data_unit->ID_PRODUCT_UNIT){?> selected<?php } ?>><?php echo $data_unit->NAME; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                <?php } ?>
            </span>
			<script language="javascript">
				$("[data-toggle=popover]").popover();
				$(".pre_order").bootstrapSwitch({
					on: 'Ya',
					off: 'Tidak',
					size: 'large',
					onClass: 'primary',
					offClass: 'default'
				}).on("change",function(){
					check_id 		= $(this).attr("data-id");
					check_status	= $(this).prop("checked");
					if(check_status == false){
						$("#pre_order_"+check_id).val("");
						open_min_order(check_id,false);
					}else{
						$("#pre_order_"+check_id).val("1");
						open_min_order(check_id,true);
					}
				})
            </script>
    <?php
			break;
			case "1":
	?>
            <div class="form-group col-md-6">  
                <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Diskon ini, berlaku untuk beberapa item kah? atau untuk semua item (all item) ?">Jumlah Item</label>
                <select id="jumlah_beli<?php echo $id_box; ?>" class="form-control jumlah_beli" 
                		onchange="open_product('<?php echo $id_box; ?>',this);"  
                        data-direction="<?php echo $dirhost; ?>/modules/diskon_reguler/ajax/product_list.php?page=diskon_reguler&id_disc=<?php echo $id_box; ?>">
                    <option value='all' <?php if(empty($id_product_discs)){?>selected<?php } ?>>Semua Item</option>
                    <option value='few' <?php if(!empty($id_product_discs)){?>selected<?php } ?>>Beberapa Item</option>
                </select>
             </div>
    <?php
			break;
		}
	}
	
	if(!empty($show) && $show == "order_quota"){?>
            <div class="form-group col-md-6">  
                <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Berapa kupon yang di jual untuk pembelian pre-order ini ?">Jumlah Kupon</label>
                <div class="input-group">
                	<span class="input-group-addon"><i class="fa fa-tags"></i></span>
                    <input type="text" id="quota_<?php echo $id_box; ?>" 
                    	   class="form-control" 
                           value="<?php echo @$quota; ?>"/>
                    <span class="input-group-addon">Lembar</span>
            	</div>
            </div>
	<?php }
?>
