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
                case "3":
					echo msg("Maaf, Kode COIN <b>".strtoupper($number_msg)."</b> ini, tidak terdaftar, 
							  maka tidak akan tersimpan sebagai Kode COIN untuk pelanggan <b>".ucwords($nama_msg)."</b>,  
							  namun data informasi <b>".ucwords($nama_msg)."</b> lainnya, tetap tersimpan","warning"); 
                break;
                case "4":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
			$class_proses = "active";
			$class_report = "";
        }
    ?>
    <div class="ibox float-e-margins">
    		<div class="ibox-title">
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>           
            </div>
         	<div class="ibox-content">   
                <div class="tabbable tabbable-bordered" style="margin:0 0 9px 0">
                    <ul class="nav nav-tabs">
                        <li class="<?php echo @$class_proses; ?>">
                        	<a data-toggle="tab" href="#tb1_a">Pendaftaran Pelanggan</a>
                        </li>
                        <li class="<?php echo @$class_report; ?>">
                        	<a data-toggle="tab" href="#tb1_b">Filter Pencarian Pelanggan</a>
                        </li>
                    </ul>
                    <div class="tab-content" style="background:#FFF">
                        <div id="tb1_a" class="tab-pane <?php echo @$class_proses; ?>">
                           <div class="ibox-content no-padding-lr">  
						   	<?php include $call->inc($page_dir."/includes","form_proses.php"); ?>
                           </div>
                        </div>
                        <div id="tb1_b" class="tab-pane <?php echo @$class_report; ?>">
                        	<div class="ibox-content no-padding-lr">  
                            <?php include $call->inc($page_dir."/includes","form_report.php"); ?>
                            </div>
                        </div>
                    </div>
                <div class="clearfix"></div>
                </div>
            </div>
        <input id="proses_page" type="hidden"  value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php" />
        <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
    </div>

    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Pelanggan</h5>
			<?php if(allow('delete') == 1){?>
            <div class="ibox-tools">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                    <a href="javascript:void()" id="select_rows_2">
                    <i class="fa fa-check-square-o"></i>
                        Pilih Semua
                    </a>
                    <li>
                        <a href="javascript:void()" id="delete_picked">
                        <i class="fa fa-trash"></i>
                            Hapus Yang Di Pilih
                        </a>
                    </li>
                </ul>
            </div>
            <?php } ?> 
        </div>
        <div class="ibox-content">
        <a name="report"></a>
		<?php if($num_customer > 0){ ?>
        <table width="100%" class="table table-bordered table-striped tbl_cust">
            <thead>
                <tr>
                    <th width="20" class="table_checkbox" style="width:13px">
                    	<input type="checkbox" id="select_rows" class="select_rows"/>
                    </th>
                    <th width="123">&nbsp;</th>
                    
                    <th width="678">Identitas</th>
                    <th width="184" style='text-align:center'>Actions</th>
                </tr>
            </thead>
            <tbody>
                  <?php while($dt_customer	= $db->fetchNextObject($q_customer)){ 
						$tgl				= "";
						$bln				= "";
						$thn				= "";
						@$nama_merchant	= $db->fob("CLIENT_NAME",$tpref."clients"," 
													WHERE ID_CLIENT = '".$dt_customer->ID_CLIENT."'");
						$status_id = $dt_customer->CUSTOMER_STATUS;
						switch ($status_id){
							case "1":
								$cust_status = "Masa Berlaku Habis";
								$class        = "label-warning";
							break;
							case "2":
								$cust_status = "Diteliti";
								$class        = "label-info";
							break;
							case "3":
								$cust_status = "Aktif";
								$class        = "label-success";
							break;
							case "4":
								$cust_status = "Daftar Hitam";
								$class        = "label-danger";
							break;
							default:
								$cust_status = "Non Aktif";
								$class       = "label-default";
							break;
						}
						if(!empty($dt_customer->EXPIRATION_DATE)){
							@$tgl_expired	= explode("-",$dt_customer->EXPIRATION_DATE);
							$tgl			= $tgl_expired[2];
							$bln			= $tgl_expired[1];
							$thn			= $tgl_expired[0];
						}
						@$propinsi 		= $db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_customer->CUSTOMER_PROVINCE."'");
						@$kota	   		= $db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_customer->CUSTOMER_CITY."'");           @$request_by	= $db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$dt_customer->REQUEST_BY_ID_USER."'");      
				?>
                  <tr class="wrdLatest" data-info='<?php echo $dt_customer->ID_CUSTOMER; ?>' id="tr_<?php echo $dt_customer->ID_CUSTOMER; ?>">
                    <td class="align-top"><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_customer->ID_CUSTOMER; ?>'/></td>
                    <td class="align-top">
                    	<a href='javascript:void()'onclick="modal_ajax(this)" modal-ajax-options='"url":"<?php echo $ajax_dir; ?>/profile.php?coin=<?php echo $dt_customer->COIN_NUMBER; ?>&id_customer=<?php echo $dt_customer->ID_CUSTOMER; ?>","size":"modal-lg"'>
						<div class="thumbnail">
                        	<div class="thumbnail-inner" style="height:80px; overflow:hidden">
								<?php echo getmemberfoto($dt_customer->ID_CUSTOMER,"style='width:100%'"); ?>						
                            </div>	
                        </div>
                        </a>
                    </td>
                    <td class="align-top">
						 <?php if($dt_customer->COIN_NUMBER == $dt_customer->CUSTOMER_USERNAME){?>
                          
                          <div class='clearfix alert alert-info col-md-12' style="margin-bottom:10px;">
                            Gunakan Nomor COIN sebagai Username & Password Perdana Pelanggan
                          </div>
                         <?php } ?>
                        <div class='form-group col-md-6'>
                        	<label class='code'>Nama Merchant </label><br />
							<?php echo strtoupper($nama_merchant); ?>
                        </div>
                        <div class='form-group col-md-6'>
                        	<label class='code'>COIN </label><br />
							<?php if(!empty($dt_customer->COIN_NUMBER)){
									echo strtoupper($dt_customer->COIN_NUMBER); 
								  }else{?>
                                      <div class='label label-warning' style="margin-bottom:10px;">
                                        Belum memiliki COIN
                                      </div>
							<?php  
								  }
							?>
                        </div>
                        <?php if(!empty($dt_customer->CUSTOMER_NAME)){?>
                        <div class='form-group col-md-6'>
                        	<label class='code' id="cust_name" data-info='<?php echo $dt_customer->CUSTOMER_NAME; ?>'>Nama</label><br />
							<?php echo $dt_customer->CUSTOMER_NAME; ?>
                        </div>
                        <?php } ?>
						<?php if(!empty($dt_customer->CUSTOMER_SEX)){?>
                        <div class='form-group col-md-6'>
                        	<label class='code'>Jenis Kelamin</label><br />
                        	<?php if($dt_customer->CUSTOMER_SEX == "L"){ echo "Laki-laki"; }?>
                            <?php if($dt_customer->CUSTOMER_SEX == "P"){ echo "Perempuan"; }?> 
                        </div>
						<?php } ?>
						<?php if(!empty($dt_customer->CUSTOMER_ADDRESS)){?>
                        <div class='form-group col-md-6'>
                            <label class='code'>Alamat</label><br />
                            <?php echo $dt_customer->CUSTOMER_ADDRESS;?> 
                            <?php echo @$kota;?> - <?php echo @$propinsi;?> 
                        </div>
                        <?php } ?>
                        <?php if(!empty($dt_customer->CUSTOMER_PERSON_CONTACT)){?>
                        <div class='form-group col-md-6'>
                            <label class='code'>No HP</label><br />
                            <?php echo $dt_customer->CUSTOMER_PERSON_CONTACT;?> 
                        </div>
                        <?php } ?>
                        <?php if(!empty($dt_customer->CUSTOMER_EMAIL)){?>
                        <div class='form-group col-md-6'>
                            <label class='code'>Email</label><br />
                            <?php echo $dt_customer->CUSTOMER_EMAIL;?> 
                        </div>
                        <?php } ?>
 
						<?php if(!empty($dt_customer->ADDITIONAL_INFO)){?>
                        <div class='form-group col-md-6'>
                            <label class='code'>Keterangan</label><br />
                            <?php echo $dt_customer->ADDITIONAL_INFO;?> 
                        </div>
                        <?php } ?>
					<?php if(!empty($request_by)){ ?>
                        <div class='form-group col-md-6'>
                            <label class='code'>Didaftarkan Oleh : </label><br />
                            <?php echo @$request_by; ?>
                        </div>
                    <?php  } ?>
                     
                     <div class='form-group col-md-12'>
                     	<label>Status Pelanggan</label><br />
                     	<small class="label <?php echo $class; ?>"><?php echo @$cust_status; ?></small>
                     </div>
                    </td>
                    <td class="text-center align-top">
                    <div class="btn-group">
						<?php if(allow('edit') == 1){?> 
                        <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_customer->ID_CUSTOMER; ?>" class="btn btn-sm btn-warning" title="Perbaiki Data Pelanggan">
                            <i class="fa fa-edit"></i>
                        </a>
                        <?php } ?>
                        <?php if(allow('delete') == 1){?> 
                        <a href='javascript:void()' onclick="removal('<?php echo $dt_customer->ID_CUSTOMER; ?>','table')" class="btn btn-sm btn-danger" title="Hapus Data Pelanggan">
                            <i class="fa fa-trash"></i>
                        </a>
                        <?php } ?>
                        <a href='javascript:void()' class="btn btn-sm btn-info" onclick="modal_ajax(this)" modal-ajax-options='"url":"<?php echo $ajax_dir; ?>/profile.php?coin=<?php echo $dt_customer->COIN_NUMBER; ?>&id_customer=<?php echo $dt_customer->ID_CUSTOMER; ?>","size":"modal-lg"' title="Lihat Profil Pelanggan">
                        <i class="fa fa-user"></i>
                        </a>
               		</div>
                    </td>
                </tr>
                <?php } ?>    
            </tbody>
        </table>
        <div id="lastPostsLoader"></div>
        <div class="ibox-title" style="text-align:center">
            <?php if($num_customer > 50){?>
                <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
            <?php } ?>
            <br clear="all" />
        </div>       
		<?php }else{
			echo "<br>";
            echo msg("Tidak Ada Pelanggan Yang Terdaftar","error");
        } ?>
        </div>
    </div>
</div>