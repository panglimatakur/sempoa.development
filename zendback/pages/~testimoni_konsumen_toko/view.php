<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-12" >
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
        }
    ?>
    <div class="ibox float-e-margins">
    	<div class="ibox-title"><h4>Input Testimonial</h4></div>
        <div class="ibox-content">
            <form method="post" action="" enctype="multipart/form-data" >
                <div class="col-md-6">
                    <div class="form-group">
                      <label class="req">Sumber Data Pelanggan</label>
                      <select name="test_src" id="test_src" class="form-control">
                        <option value="upload">Upload Baru</option>
                        <option value="data">Database User</option>
                      </select>
                	</div>
                    
                    <div class="form-group" id="data_user" style="display:none">
                        <label class="req">Member / User</label>
                        <?php while($dt_member = $db->fetchNextObject($q_member)){ ?>
                            <div style="font-size:11px; border:1px solid #EFEFEF; margin:5px 0 0 0; padding:5px">
                                <div class="col-md-2" style="padding:0;">
									<?php if(is_file($basepath."/files/images/members/".$dt_member->CUSTOMER_PHOTO)){?>
                                        <img src="<?php echo $dirhost; ?>/files/images/members/<?php echo $dt_member->CUSTOMER_PHOTO; ?>" class="thumbnail" style="width:100%"/>  
                                    <?php }else{ ?>
                                    <img src="<?php echo $dirhost; ?>/files/images/noimage-m.jpg" 
                                         class="thumbnail" 
                                         style="width:100%"/>	
                                    <?php } ?>
                                </div>
                                <div class="col-md-10">
                                    <b>Nama</b> 	: <?php echo $dt_member->CUSTOMER_NAME; ?><br />
                                    <b>Email</b> 	: <?php echo $dt_member->CUSTOMER_EMAIL; ?><br />
                                    <?php if(!empty($dt_member->CUSTOMER_PHONE) && $dt_member->CUSTOMER_PHONE != "none"){?>
                                        <b>Tlp</b> 	: <?php echo $dt_member->CUSTOMER_PHONE; ?><br />
                                    <?php } ?>
                                    <?php if(!empty($dt_member->CUSTOMER_ADDRESS)){?>
                                        <b>Alamat</b> 	: <?php echo $dt_member->CUSTOMER_ADDRESS; ?>
                                    <?php } ?>
                                    <br /><br />
                                    <button type="button" class="btn btn-sm btn-sempoa-3" value="<?php echo $dt_member->ID_CUSTOMER; ?>" onclick="show_form(this)"><i class="fa fa-check-square-o"></i> Tulis Testimonial</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        <?php } ?>
                    </div>
               
                </div>
                <div class="col-md-6">
                	<span  id="form_testi"></span>
                    <div class="form-group">
                        <label class="req">Nama Pelanggan</label>
                        <input type="text" name="nama"  id="nama" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class="req">Photo Pelanggan</label>
                        <span  id="photo_pelanggan"></span>
                        <input type="file" name="photo" type="file" id="photo"  />
                    </div>
                    <div class="form-group">
                      <label class="req">Isi Testimonial</label>
                      <textarea name="testimonial" id="testimonial" class="form-control validate[required] text-input"><?php echo @$testimonial; ?></textarea>
                    </div>
                    <div class="form-group">
                      <label >&nbsp;</label><br />
                        <button name="direction" type="submit" id="button_cmd" class="btn btn-sempoa-1" value="insert">
                        	<i class="fa fa-check-square-o"></i> Simpan Testimonial
                        </button>
                        <input type="hidden" id="id_customer" value="<?php echo $id_customer; ?>"/>
                        
                    </div>
                
                
                </div>
                <br clear="all">
            </form>
            <input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
            <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />

        </div>
    </div>
    
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Testimonial</h4>
            <div class="pull-right">
                <div class="toggle-group">
                    
                    <ul class="dropdown-menu">
                        <!--<li>
                            <a href="modules/supplier/includes/print.php?prompt=true" class="fancybox fancybox.ajax">
                            <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                            </a>
                        </li>-->
                        <?php if(allow('delete') == 1){?> 
                        <li>
                            <a href="javascript:void()" id="select_rows_2">
                            <i class="icon-check" style="margin:0 4px 0 0"></i>
                                Pilih Semua
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()" id="delete_picked">
                            <i class="icsw16-trashcan" style="margin:-2px 4px 0 0"></i>
                                Hapus Yang Di Pilih
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <a name="report"></a>
        <div class="ibox-content">
		<?php if($num_partner > 0){ ?>
        <table width="100%" class="table table-striped">
            <thead>
                <tr>
                    <th width="5%" class="table_checkbox" style="width:13px">
                    	<input type="checkbox" id="select_rows" class="select_rows"/>
                    </th>
                    <th width="10%">&nbsp;</th>
                    <th width="">Nama</th>
                    <th width="">Testimonial</th>
                    <th width="10%">Actions</th>
                </tr>
            </thead>
            <tbody>
                  <?php while($dt_partner	= $db->fetchNextObject($q_partner)){
					  
					  if(!empty($dt_partner->ID_CUSTOMER)){
						  $q_user_tbl 	= $db->query("SELECT CUSTOMER_PHOTO,CUSTOMER_NAME FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$dt_partner->ID_CUSTOMER."'");
						  $dt_user_tbl 	= $db->fetchNextObject($q_user_tbl);
						  @$photo 		= $dt_user_tbl->CUSTOMER_PHOTO;
						  @$name 		= $dt_user_tbl->CUSTOMER_NAME;
					  }else{
						  @$name 		= $dt_partner->NAME;
						  @$photo 		= $dt_partner->PHOTO;  
					  }
                  ?>
                  <tr id="tr_<?php echo $dt_partner->ID_CLIENT_TESTIMONIAL; ?>">
                    <td><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_partner->ID_CLIENT_TESTIMONIAL; ?>'/></td>
                    <td>
					<?php 
						if(is_file($basepath."/files/images/members/".$photo)){?>
                    		<img src="<?php echo $dirhost; ?>/files/images/members/<?php echo $photo; ?>" class="photo" style="width:90%; margin-left:5px"/>
					<?php }else{ ?>
                    		<img src="<?php echo $dirhost; ?>/files/images/noimage-m.jpg" class="photo" style="width:90%; margin-left:5px"/>
					<?php } ?>
                    <br />
                    </td>
                    <td style="padding:20px;">
					<?php echo @$name; ?><br />
					<?php echo @$db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_partner->ID_PROVINCE."'"); ?>
                    </td>
                    <td>
						<?php echo @$dt_partner->TESTIMONIAL; ?><br />
                    </td>
                    <td>
                        <div class="btn-group">
                        	<?php if(allow('edit') == 1){?> 
                                <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_partner->ID_CLIENT_TESTIMONIAL; ?>" class="btn btn-mini" title="Edit">
                                    <i class="icon-pencil"></i>
                                </a>
                            <?php } ?>
                            <?php if(allow('delete') == 1){?> 
                                <a href='javascript:void()' onclick="removal('<?php echo $dt_partner->ID_CLIENT_TESTIMONIAL; ?>')" class="btn btn-mini" title="Delete">
                                    <i class="icon-trash"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>    
            </tbody>
        </table>
        <?php }else{?>
            <div class="alert alert-danger">Tidak Ada Testimonial Yang Terdaftar</div>
        <?php } ?>
        </div>
        <div class="ibox-title">
            <form id="form_paging" class="formular" action="#report" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="direction" value="show" />
 			<?php echo pfoot($str_query,$link_str); ?>       
        	</form>
        </div>
    </div>
</div>