<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 			= isset($_REQUEST['direction']) 		? $_REQUEST['direction'] 		: "";
	if(!empty($_REQUEST['form_name'])){ $form_name 		= isset($_REQUEST['form_name']) ? $_REQUEST['form_name'] : ""; }
}else{
	defined('mainload') or die('Restricted Access');
}
	if((!empty($direction) && $direction == "get_city") || !empty($kota)){
		if(!empty($_REQUEST['propinsi'])){ $propinsi 		= isset($_REQUEST['propinsi']) 		? $_REQUEST['propinsi'] : ""; }
	?>
            <div class="form-group col-md-6">
                <label class="req">Kota</label>
                <select name="kota" id="kota" class="form-control validate[required] text-input">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' <?php if(!empty($kota) && $kota == $data_kota->ID_LOCATION){?> selected<?php } ?>>
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	
	
	if((!empty($direction) && $direction == "get_city2" || !empty($kota_report))){
		if(!empty($_REQUEST['propinsi'])){ $propinsi 		= isset($_REQUEST['propinsi']) 		? $_REQUEST['propinsi'] : ""; }
	?>
            <div class="form-group col-md-4">
                <label>Kota</label>
                <select name="kota_report" id="kota" class="form-control">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' <?php if(!empty($kota_report) && $kota_report == $data_kota->ID_LOCATION){?> selected<?php } ?>>
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	
	if(!empty($direction) && $direction == "get_user"){
		$cidkey 			= isset($_REQUEST['cidkey']) 		? $_REQUEST['cidkey'] 		: "";
		$query_user			= $db->query("SELECT * FROM system_users_client WHERE ID_CLIENT = '".$cidkey."' ORDER BY USER_NAME ASC"); 
		?>
        <label>Didaftarkan Oleh</label>
        <select name="reg_by" id="reg_by" class="form-control">
            <option value=''>--PILIH NAMA PENGGUNA--</option>
            <?php
            while($data_user = $db->fetchNextObject($query_user)){
            ?>
                <option value='<?php echo $data_user->ID_USER; ?>' <?php if(!empty($reg_by) && $reg_by == $data_user->ID_USER){?> selected<?php } ?>><?php echo $data_user->USER_NAME; ?>
                </option>
        <?php } ?>
        </select>
        <?php
	}
	
	
	if((!empty($direction) && $direction == "list_report")){
		@$lastID 	= $_REQUEST['lastID'];
		
		
		$id_client_report = isset($_REQUEST['id_client_report'])? $sanitize->number($_REQUEST['id_client_report'])	:"";
		$reg_by 		= isset($_REQUEST['reg_by']) 			? $sanitize->str($_REQUEST['reg_by'])				:"";
		$nama_report 	= isset($_REQUEST['nama_report']) 		? $sanitize->str($_REQUEST['nama_report'])			:"";
		$email_report 	= isset($_REQUEST['email_report']) 		? $sanitize->str($_REQUEST['email_report'])			:"";
		$kontak_report 	= isset($_REQUEST['kontak_report']) 	? $sanitize->str($_REQUEST['kontak_report'])		:"";
		$sex_report 	= isset($_REQUEST['sex_report']) 		? $sanitize->str($_REQUEST['sex_report'])			:"";
		$nocoin 		= isset($_REQUEST['nocoin']) 			? $sanitize->str($_REQUEST['nocoin'])				:"";
		$coin_stat 		= isset($_REQUEST['coin_stat']) 		? $sanitize->number($_REQUEST['coin_stat'])			:"";
		$id_member_report = isset($_REQUEST['id_member_report'])? $sanitize->str($_REQUEST['id_member_report'])		:"";
		$alamat_report 	= isset($_REQUEST['alamat_report']) 	? $sanitize->str($_REQUEST['alamat_report'])		:"";
		$propinsi_report = isset($_REQUEST['propinsi_report']) 	? $sanitize->number($_REQUEST['propinsi_report'])	:"";
		$kota_report 	= isset($_REQUEST['kota_report']) 		? $sanitize->number($_REQUEST['kota_report'])		:"";
		
	
		$condition						 = "";
		if($_SESSION['cidkey'] == 1 && $_SESSION['ulevelkey'] == 1){
			if(!empty($id_client_report)){
				$condition			.= " AND ID_CLIENT='".$id_client_report."'	";
			}
		}else{
			$condition				.= " AND ID_CLIENT='".$_SESSION['cidkey']."'	";
		}
	
		if(!empty($id_client_report))	{ $condition 	.= "AND ID_CLIENT 			= '".$id_client_report."' "; 		}
		if(!empty($reg_by))				{ $condition 	.= "AND REQUEST_BY_ID_USER 	= '".$reg_by."' "; 					}
		if(!empty($nama_report))		{ $condition 	.= "AND CUSTOMER_NAME 		LIKE '%".$nama_report."%' "; 		}
		if(!empty($email_report))		{ $condition 	.= "AND CUSTOMER_EMAIL		LIKE '%".$email_report."%' "; 		}
		if(!empty($kontak_report))	{ $condition 	.= "AND CUSTOMER_PERSON_CONTACT LIKE '%".$kontak_report."%' "; 		}
		if(!empty($sex_report))			{ $condition 	.= "AND CUSTOMER_SEX 		= '".$sex_report."' "; 				}
		if(!empty($nocoin))				{ $condition 	.= "AND COIN_NUMBER LIKE 	'%".$nocoin."%' "; 					}
		if(!empty($coin_stat))			{ $condition 	.= "AND CUSTOMER_STATUS 	= '".$coin_stat."' "; 				}
		if(!empty($id_member_report))	{ $condition 	.= "AND CUSTOMER_REG_ID		LIKE '%".$id_member_report."%' "; 	}
		if(!empty($alamat_report))		{ $condition 	.= "AND CUSTOMER_ADDRESS 	LIKE '%".$alamat_report."%' "; 		}
		if(!empty($propinsi_report))	{ $condition 	.= "AND CUSTOMER_PROVINCE 	= '".$propinsi_report."' "; 		}
		if(!empty($kota_report))		{ $condition 	.= "AND CUSTOMER_CITY 		= '".$kota_report."' "; 			}

		$str_query			= "SELECT * FROM ".$tpref."customers 
							   WHERE ID_CLIENT IS NOT NULL ".$condition." AND ID_CUSTOMER < ".$lastID." 
							   ORDER BY ID_CUSTOMER DESC"; 
							   
		$q_customer 		= $db->query($str_query." LIMIT 0,50");
		

  		while($dt_customer	= $db->fetchNextObject($q_customer)){ 
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
		@$propinsi  =$db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_customer->CUSTOMER_PROVINCE."'");
		@$kota	   	=$db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_customer->CUSTOMER_CITY."'");
		@$request_by=$db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$dt_customer->REQUEST_BY_ID_USER."'");      
?>
          <tr class="wrdLatest" data-info='<?php echo $dt_customer->ID_CUSTOMER; ?>' id="tr_<?php echo $dt_customer->ID_CUSTOMER; ?>">
            <td class="align-top"><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_customer->ID_CUSTOMER; ?>'/></td>
            <td class="align-top">
                <a href='javascript:void()' class="modal-ajax" modal-ajax-options='"url":"<?php echo $ajax_dir; ?>/profile.php?coin=<?php echo $dt_customer->COIN_NUMBER; ?>&id_customer=<?php echo $dt_customer->ID_CUSTOMER; ?>","size":"modal-lg"'>
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
                    <a href='javascript:void()' onclick="removal('<?php echo $dt_customer->ID_CUSTOMER; ?>')" class="btn btn-sm btn-danger" title="Hapus Data Pelanggan">
                        <i class="fa fa-trash"></i>
                    </a>
                    <?php } ?>
                    <a href='javascript:void()' class="btn btn-sm btn-info modal-ajax" modal-ajax-options='"url":"<?php echo $ajax_dir; ?>/profile.php?coin=<?php echo $dt_customer->COIN_NUMBER; ?>&id_customer=<?php echo $dt_customer->ID_CUSTOMER; ?>","size":"modal-lg"' title="Lihat Profil Pelanggan">
                    <i class="fa fa-user"></i>
                    </a>
                </div>
            </td>
        </tr>
<?php   } 
	}

?>