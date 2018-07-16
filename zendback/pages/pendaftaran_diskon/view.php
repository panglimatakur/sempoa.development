<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-12">
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
                case "3":
                    echo msg("Mohon masukan besar diskon di atas 10%, Terimakasih","error");
                break;
            }
        }
    ?>
    <div class="ibox float-e-margins" >
        <div class="ibox-title">
            <h5>Promo Diskon Reguler</h5>
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="ibox-content" >
        
        	<form action="" method="POST" name="form-discount"> 
                <div id="diskon_load"></div>                       
                <div class='form-group col-md-4'> 
                    <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Berlaku untuk siapakah diskon yang akan anda rancang dibawah ini berlaku ? pelanggan dari merchant komunitas anda kah? atau untuk pelangan anda sendiri ?" >Target Diskon</label>
                    <select name="formember" class='form-control'>
                        <option value="community">Komunitas</option>
                        <option value="customer">Member <?php echo ucwords(strtolower($nm_merchant)); ?></option>
                    </select>  
                </div>
                <div class='form-group col-md-4'> 
                    <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Berapa besaran diskon yang ingin anda berlakukan untuk pelanggan ? minimal 10% ya.. :D">Diskon</label>
                    <div class="input-group">
                    <input type="number" name="besar" id="besar" class='form-control' placeholder="Besar Diskon (Min 10%)" value="<?php if(!empty($besar)){ echo @$besar; } ?>" maxlength="3"/>
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label>Berlaku Hingga</label>
                    <div class="input-group date">
                        <input type="text" class="form-control" name="expiration" value="<?php echo $expiration; ?>">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>                                  
                </div>
                <div class='form-group col-md-4'> 
                    <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Tentukan pola diskon yang ingin anda berlakukan, berdasarkan item kah ? atau berdasarkan jumlah total pembelian kah ?">Pola Diskon</label>
                    <select name="pattern" id="pattern" class='form-control'>
                        <option value=''>-- PILIH POLA DISKON --</option>
						<?php while($dt_discount_pattern = $db->fetchNextObject($q_discount_pattern)){
								$id_discount_pattern = $dt_discount_pattern->ID_DISCOUNT_PATTERN;
						?>
                        <option value="<?php echo $id_discount_pattern; ?>"
                        		<?php if(!empty($pattern) && $pattern == $id_discount_pattern){?>selected<?php } ?>  >
                            	<?php echo $dt_discount_pattern->DESCRIPTION; ?>
                        </option>
                        <?php } ?>                    
                   </select>
                </div>
                
                <span id="div_pattern" class="div_pattern">
                	<?php if(!empty($direction) && !empty($pattern)){
							include $call->inc($ajax_dir,"data.php");
					} ?>
                </span>
                <div class="form-group col-md-12" id="div_item">
                	<label class="btn-list-item-loader">&nbsp;</label><br />
                    <button type="button" class="btn btn-info btn-block btn-list-item"
                            title="Tombol ini berfungsi untuk memilih item/produk yang akan di berikan diskon">
                            <i class="fa fa-check"></i> 
                            Pilih Item
                    </button>
				</div>
                
                <span id="picked_item"></span>
                
                <div class="form-group col-md-4"> 
                	<?php if(empty($sifat_jual)){ $sifat_jual = 'readystock'; } ?>
                    <label class="req" data-container="body" data-toggle="popover" data-placement="top" data-content="Berapa kupon yang di jual untuk pembelian pre-order ini ?">Sifat Penjualan</label><br />
                    <input type="checkbox" id="sifat_jual_check" 
						   <?php if(!empty($sifat_jual) && $sifat_jual == "preorder"){?> checked <?php } ?>/>
                    <input type="hidden" name="sifat_jual" id="sifat_jual" value="<?php echo @$sifat_jual; ?>"/>
                </div>
                <div class='form-group col-md-4'>
                    <label>Keterangan</label>            
                    <input name="keterangan" class='form-control' placeholder="Keterangan" value="<?php echo $keterangan; ?>">
                </div>                                    
                
                <div class="form-group col-md-12">
                      <label >&nbsp;</label><br />
                        <?php
                        if(empty($direction) || 
                        (!empty($direction) && $direction == "insert")){ $prosesvalue = "insert";}
						
                        if(!empty($direction) && !empty($direction) && $direction == "edit"){ 
                            $prosesvalue = "save";
                            $addbutton = "
                                <a href='".$lparam."'>
                                    <button name='button' 
                                            type='button' 
                                            class='btn btn-danger' 
                                            value='Tambah Data'>
                                            <i class='fa fa-plus'></i>  Tambah Data 
                                    </button>
                                </a>";
                    ?>
                        <input type='hidden' name='id_diskon' id='id_diskon' value='<?php echo $id_diskon; ?>' />
                        <?php
                        }
                    ?>
                        <button name="submit" type="submit" id="button_cmd" class="btn btn-sempoa-1">
                            <i class="fa fa-check-square-o"></i> Simpan Diskon
                        </button>
                        <?php echo @$addbutton; ?>
                        <input type='hidden' name='direction' id='direction' value='<?php echo $prosesvalue; ?>' />
               	</div> 
                <div class="clearfix"></div> 
        	</form>
        </div>
    </div>  
    
    
    
    
    <div class="ibox float-e-margins" >
        <div class="ibox-content">
            <table width="100%" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="20" class="table_checkbox" style="width:13px">
                            <input type="checkbox" id="select_rows" class="select_rows"/>
                        </th>
                        <th width="121" class="text-center">Info Diskon</th>
                        <th width="74" class="text-center">Diskon</th>
                        <th width="217">Pola Diskon</th>
                        <th width="109" class="text-center">Sifat Jual</th>
                        <th width="127">Syarat</th>
                        <th width="129" class="text-center">Masa Berlaku</th>
                        <th width="77" class="text-center">Status </th>
                        <th width="117" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
					while($dt_diskon_list = $db->fetchNextObject($q_diskon_list)){
					@$id_discount	= $dt_diskon_list->ID_DISCOUNT;
					@$segment		= $dt_diskon_list->DISCOUNT_SEGMENT;
					if($segment == "community"){
						$label_segment = "<label class='label label-info'>Member Komunitas</label>";
					}
					if($segment == "customer"){
						$label_segment = "<label class='label label-danger'>Member ".$_SESSION['cname']."<label>";
					}
					@$id_pattern	= $dt_diskon_list->ID_DISCOUNT_PATTERN;
					@$pattern 		= $db->fob("DESCRIPTION",$tpref."discount_patterns"," 
												WHERE ID_DISCOUNT_PATTERN = '".$id_pattern."'");
					$discount		= $dt_diskon_list->DISCOUNT;
					@$expiration	= $dt_diskon_list->EXPIRATION_DATE;
					$besar			= $dt_diskon_list->DISCOUNT_VALUE; 
					$piece			= $dt_diskon_list->DISCOUNT_UNIT;
					$status			= $dt_diskon_list->DISCOUNT_STATUS;
					$sifat_jual		= $dt_diskon_list->SELLING_METHOD;
					@$jml_kupon		= $dt_diskon_list->SELLING_METHOD_PO_COUPON_QTY;
					@$pr_kupon		= $dt_diskon_list->SELLING_METHOD_PO_COUPON_PRICE;
					if($status == 3){ $status_label = "<label class='label label-success'>Aktif</label>"; 		}
					else			{ $status_label = "<label class='label label-warning'>Non Aktif</label>"; 	}
					
					switch($id_pattern){
						case "1":
						if(substr_count($besar,";") > 0){ 
							$besar = "<label class='label label-default '>
										<a  href='javascript:void()' 
											class='item_discount_list' 
										    data-id-discount = '".$id_discount."'>
											Lihat Daftar Item
										</a>
									  </label>"; 
						}
						if($besar == "all"){ $besar = "<label class='label label-warning'>Semua Item</label>"; }
						break;
						case "2":
							$besar = money("Rp.",$besar);
						break;
					}
					switch($sifat_jual){
						case "readystock": 
						$sifat_jual_label = 
							"<label class='label label-info'>Ready Stock</label>"; 
						break;
						case "preorder": 
						$sifat_jual_label = 
							"<a href='javascript:void' data-container='body' data-toggle='popover' data-placement='top' data-content='".$jml_kupon." Kupon / @".money("Rp.",$pr_kupon)." '>
								<label class='label label-warning'>Pre Order</label>
							</a>"; 
						break;
					}
					
				?>
                	<tr id="disc_<?php echo $id_discount; ?>">
                        <td>
                            <input type="checkbox" id="select_rows" class="select_rows"/>
                        </td>
                        <td class="text-center"><?php echo @$label_segment; ?></td>
                        <td class="text-center"><?php echo $discount.$piece; ?></td>
                        <td><?php echo @$pattern; ?></td>
                        <td class="text-center"><?php echo @$sifat_jual_label; ?></td>
                        <td><?php echo @$besar; ?></td>
                        <td class="text-center">
							<?php if(!empty($expiration) && $expiration != "0000-00-00"){
										echo $dtime->date2indodate($expiration); 
								  }else{
										echo "<label class='label label-danger'>Unlimited</label>";  
								  }
							?>
                        </td>
                        <td class="text-center"><?php echo @$status_label; ?></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <?php if(allow('edit') == 1){?> 
                                    <a href="<?php echo $lparam; ?>&direction=edit&id_diskon=<?php echo $id_discount; ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                <?php } ?>
                                <?php if(allow('delete') == 1){?> 
                                    <a href='javascript:void()' onclick="del_discount('<?php echo $id_discount; ?>')" class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                <?php } ?>
                            </div>
                        </td>
                     </tr>
                 <?php } ?>
                </tbody>
            </table>

        </div>
        
  	</div>  
</div>

<div id="modal-product-list" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content animated flipInY">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Daftar Item</h4>
            </div>
            <div class="modal-body no-padding-lr"></div>
            <div id="lastPostsLoader"></div>
            <div class="modal-footer">
                <a href='javascript:void()' onclick="lastPostFunc()" class='btn btn-white btn-block dd-paging'>
                	<i class='fa fa-chevron-down'></i>&nbsp;
                    Selanjutnya..
                  
                </a>
            </div>
        </div>
    </div>
</div>
<div id="modal-picked-sproduct-list" class="modal fade" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content animated flipInY">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Daftar Item</h4>
            </div>
            <div class="modal-body no-padding-lr"></div>
            <div class="modal-footer">
				 <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" 
       id="proses_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php" />
<input type="hidden" 
       id="data_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php" />
