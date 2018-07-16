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
	<?php if($_SESSION['admin_only'] == "true"){?>
        <div class="ibox-title">
            <h5>COIN Generator</h5>
            <div class="ibox-tools">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="javascript:void()" id="select_rows_2">
                            <i class="fa fa-check-square-o" ></i>
                            Pilih Semua
                        </a>
                    </li>  
                    <li>
                        <a href="javascript:void()" id="show_merchant">
                            <i class="fa fa-unlock" ></i>
                            Aktifkan
                        </a>
                    </li>                    
                </ul>
            </div>
       </div>
       <div class="ibox-content">
            <form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group col-md-2">
                    <label>Mode Form</label><br />
                    <input type="checkbox" 
                           name="ch_ref" 
                           class="i-switch" 
                           <?php if(!empty($ch_ref) && $ch_ref == 1){?> checked <?php } ?>> 
                </div>
                <div class="form-group col-md-4 filter">
                    <label>Daftar Merchant</label>
                    <select name="merchant_group_1" class="merchant_group form-control mousetrap" 
                            data-target = 'id_client_form'>
                        <option value=''>--PILIH MERCHANT--</option>
                        <?php while($data_branch = $db->fetchNextObject($query_branch)){?>
                            <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_client_form) && $id_client_form == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" 
                           name="id_client_form" id="id_client_form" 
                           value="<?php echo @$id_client_form; ?>" />
                </div>
                <div class="form-group col-md-4 filter">
                    <label>Status Aktifasi</label>
                    <select name="status" id="status" class="form-control mousetrap" >
                        <option value="">NON AKTIF</option>
                        <option value="1" <?php if(!empty($status) && $status == 1){?> selected <?php } ?>>EXPIRED</option>
                        <option value="3" <?php if(!empty($status) && $status == 3){?> selected <?php } ?>>AKTIF</option>
                    </select>
                </div> 
                <div class="form-group col-md-2 filter">
                    <label>&nbsp;</label><br />
                    <button name="direction" type="submit" value='show' class="btn btn-warning">
                        <i class="fa fa-eye"></i> Cari Kode 
                    </button>
                </div>
                
                <div class="form-group col-md-4 generate" style="display:none">
                    <label>Jumlah Cetak</label>
                    <input type="text" 
                           name="jml_cetak" 
                           class="form-control mousetrap" 
                           value="<?php echo @$jml_cetak; ?>">
                </div> 
                
                <div class="form-group col-md-4 generate" style="display:none">
                    <label>&nbsp;</label><br />
                    <button name="direction" type="submit" value='generate' class="btn btn-danger">
                        <i class="fa fa-check-square-o"></i> Generate
                    </button>
                </div>
            </form>
           <div class="clearfix"></div>
       </div>
       <div class="clearfix"></div>
   <?php }else{ ?>
       <div class="ibox-title">
            <h5>Filter Pencarian</h5>
       </div>
       <div class="ibox-content">
            <form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group col-md-4">
                    <label>Status Aktifasi</label>
                    <select name="status" id="status" class="form-control mousetrap" >
                        <option value="">NON AKTIF</option>
                        <option value="1" <?php if(!empty($status) && $status == 1){?> selected <?php } ?>>
                            EXPIRED
                        </option>
                        <option value="3" <?php if(!empty($status) && $status == 3){?> selected <?php } ?>>AKTIF</option>
                    </select>
                </div> 
                <div class="form-group col-md-4" >
                    <label>&nbsp;</label><br />
                    <button name="show" type="submit" value='1' class="btn  btn-sempoa-2">
                        <i class="fa fa-eye"></i> Lihat Kode Aktifasi
                    </button>
                </div>
            </form>
           <div class="clearfix"></div>
       </div>
       <div class="clearfix"></div>
   <?php } ?>


	<?php if($num_coin > 0){?>
        <div class="ibox-title">
            <h5>Daftar Kode Aktifasi</h5>
            <div class="ibox-tools">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-wrench"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                    	<a href="javascript:void();" id="print_r">
                    		<i class="fa fa-print"></i> Cetak Data
                    	</a>
                    <li>
                        <a href="javacsript:void()" id="print_excel">
                        	<i class="fa fa-file-excel-o" ></i> Export Ke Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="ibox-content print-table">
            <table width="100%" class="table table-bordered table-striped" id="tbl_data">
                <thead>
                    <tr>
                    	<?php if($_SESSION['admin_only'] == "true"){?>
                        <th width="59" class="table_checkbox" style="width:13px">
                            <input type="checkbox" id="select_rows" class="select_rows" data-tableid="dt_gal" />
                        </th>
                        <?php } ?>
                        <th width="25" style="text-align:center">No</th>
                        <th width="162" class="text-center">KODE AKTIFASI</th>
                        <th width="272">MERCHANT/PELANGGAN</th>
                        <th width="142" class="text-center">STATUS AKTIF</th>
                        <th width="172" class="text-center">TANGGAL CETAK</th>
                        <th width="161" class="text-center">TANGGAL AKTIF</th>
                    </tr>
                </thead>
                <tbody>
                      <?php 
                        $t = 0; 
                        while($dt_coin	= $db->fetchNextObject($q_coin)){ $t++; 
						$nm_merchant	= "";
						$label_merchant	= "";
						$nm_customer	= "";
						$label_customer	= "";
					    $lastID 		= $dt_coin->ID_DISCOIN_ACTIVATION_CODE;
						$activate_id	= $dt_coin->ACTIVATE_BY_ID_CLIENT;
                        
                        if(!empty($activate_id)){
                            $nm_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," 
                                                     WHERE ID_CLIENT='".$activate_id."'");
							$label_merchant 	= "<label class='label label-warning'>".$nm_merchant."</label>";			
                        }else{ $label_merchant 	= "<label class='label label-info'>Belum diaktifkan merchant</label>"; }
                        
                        if(!empty($dt_coin->ACTIVATE_BY_ID_CUSTOMER)){
                            $q_customer 	= $db->query("SELECT CUSTOMER_NAME,EXPIRATION_DATE FROM ".$tpref."customers
													   	  WHERE ID_CUSTOMER = '".$dt_coin->ACTIVATE_BY_ID_CUSTOMER."' 
														  AND ID_CLIENT='".$activate_id."'");
                            $dt_customer 	= $db->fetchNextObject($q_customer);
							$nm_customer 	= $dt_customer->CUSTOMER_NAME;
							$expired 	 	= $dtime->now2indodate2($dt_customer->EXPIRATION_DATE);
							$label_customer = "<label class='label label-info'>".$nm_customer."</label><br><br>
											   <label class='label label-danger'>Expired : ".$expired."</label>";						   
                        }else{ $label_customer = "<label class='label label-warning'>Belum diaktifkan pelanggan</label>"; }
                        $tgl_aktif = $dtime->now2indodate2(substr($dt_coin->ACTIVATE_DATETIME,0,10));
                        if(empty($dt_coin->ACTIVATE_DATETIME)){ 
                            $tgl_aktif = "<label class='label label-success'>None</label>"; 
                        }
                        switch($dt_coin->ACTIVATE_STATUS){ 
                            case "3":
                                $status_coin = "AKTIF"; 
                                $class_label = "label-success";
                            break;
                            default:
                                $status_coin = "NON AKTIF";
                                $class_label = "label-danger";
                            break;
                        }
                        
                      ?>
                      <tr class='wrdLatest' data-info='<?php echo @$lastID; ?>'>
                        <?php if($_SESSION['admin_only'] == "true"){?>
                        <td class="text-center align-top">
							<?php if(empty($activate_id)){?>
                        		<input type="checkbox" class="row_sel" value="<?php echo @$lastID; ?>"/>
                            <?php } ?>
                        </td>
                        <?php } ?>
                        <td class="text-center align-top label_no"><?php echo $t; ?></td>
                        <td class="align-top text-center">
                            <b class='code'>
                                <?php echo strtoupper(@$dt_coin->ACTIVATION_CODE); ?>
                            </b>
                        </td>
                        <td class="align-top">
                            <?php echo @$label_merchant; ?>
                            <br><br>
                            <?php echo @$label_customer; ?>
                        </td>
                        <td class="text-center align-top">
                            <label class="label <?php echo $class_label; ?>">
                                <?php echo @$status_coin; ?>
                            </label>
                        </td>
                        <td class="text-center align-top">
                            <b class='code'><?php echo $dtime->now2indodate2(@$dt_coin->UPDATEDATE); ?></b>
                        </td>
                        <td class="text-center align-top">
                            <b class='code'><?php echo @$tgl_aktif; ?></b>
                        </td>
                    </tr>
                    <?php } ?>    
                </tbody>
            </table>
            <div id="lastPostsLoader"></div>
            <div class="ibox-title" style="text-align:center">
                <input type="hidden" id="id_client_form" name="id_client_form" value="<?php echo @$id_client_form; ?>" />
                <input type="hidden" id="status" name="status" value="<?php echo @$status; ?>" />
                <input type="hidden" id="data_page" value="<?php echo @$ajax_dir; ?>/data.php" />
                <input type="hidden" id="proses_page" value="<?php echo @$ajax_dir; ?>/proses.php" />
            <?php if($num_coin > 100){?>
                <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
            <?php } ?>
            <br clear="all" />
            </div>                    
        </div>
        
        
	<?php } ?>      
</div>

<?php if($_SESSION['admin_only'] == "true"){?>
<div id="merchant_list" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Diaktifkan Untuk Merchant ?</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
            	<span id="load_activate"></span>
                <button type="button" id="activate" value="activate" class="btn btn-primary">
                    <i class="fa fa-unlock"></i> Aktifkan
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
