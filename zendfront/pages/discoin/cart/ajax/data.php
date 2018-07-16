<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','ITSHIJAB',true); }
	include_once("../../../../../includes/config.php");
	include_once("../../../../../includes/classes.php");
	include_once("../../../../../includes/functions.php");
	include_once("../../../../../includes/declarations.php");
	$direction 			= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$propinsi 			= isset($_REQUEST['propinsi']) 		? $_REQUEST['propinsi'] 	: "";
	$lokasi 			= isset($_REQUEST['lokasi']) 		? $_REQUEST['lokasi'] 	: "";
	$plg				= isset($_REQUEST['plg']) 			? $_REQUEST['plg'] 	: "";
	$cust_email			= isset($_REQUEST['cust_email']) 	? $sanitize->email($_REQUEST['cust_email'])			: "";
	$cust_password		= isset($_REQUEST['cust_password']) ? $_REQUEST['cust_password'] 						: "";
	
	if(!empty($direction) && $direction == "check_new_cust"){
		if($plg == "baru"){
			$q_user 	= $db->query("SELECT ID_CUSTOMER FROM ".$tpref."customers WHERE CUSTOMER_EMAIL = '".$cust_email."' AND CUSTOMER_STATUS = '3' ");
			$num_user 	= $db->numRows($q_user);
			if($num_user == 0){
				echo "2";
			}else{
				echo "<div class='alert alert-danger'>Akun ".$cust_email." sudah terdaftar, silahkan pilih jenis \"Saya sudah terdaftar\" atau <a href='#'>Lupa Password?</a>, jika anda melupakan password akun anda";	
			}
		}else{
			$q_user 	= $db->query("SELECT ID_CUSTOMER FROM ".$tpref."customers WHERE CUSTOMER_EMAIL = '".$cust_email."' AND CUSTOMER_PASS = '".$cust_password."' AND CUSTOMER_EMAIL = '3' ");
			$dt_user 	= $db->fetchNextObject($q_user);
			$num_user 	= $db->numRows($q_user);
			if($num_user > 0){
				echo "2";
			}else{
				echo "<div class='alert alert-danger'>Maaf, akun ".$website_name." anda ini belum terdaftar, silahkan pilih jenis \"Saya belum terdaftar\", atau gunakan email yang sudah terdaftar";	
			}
		}
	}
	
	
	if(!empty($direction) && $direction == "get_location"){
    $q_kota = $db->query("SELECT * FROM system_master_location 
						  WHERE PARENT_ID = '".$propinsi."' 
                          ORDER BY NAME ASC");
?>
	<div class="form-group col-md-6 no-padding-l">
        <label class="req">Lokasi Penerima</label>
        <select class="form-control" id="lokasi" name="lokasi" onchange="get_delivery_type(this)">
            <option value="">-PILIH LOKASI--</option>
			<?php
            while($dt_kota = $db->fetchNextObject($q_kota)){?>
            <option value="<?php echo $dt_kota->ID_LOCATION; ?>">
                <?php echo $dt_kota->NAME; ?>
            </option>
            <?php } ?>
        </select>
    </div>
<?php
	}	
	
	if(!empty($direction) && $direction == "get_delivery_type"){?>
        <div class="form-group col-md-6 no-padding-l">
            <label class="req">Penerimaan Item</label>
            <select class="form-control" id="delivery_type" name="delivery_type" onchange="get_courier(this)">
                <option value="cod" selected>Cash On Delivery</option>
                <option value="courier">Layanan Kurir</option>
            </select>
        </div>
	<?php }
	
	if(!empty($direction) && $direction == "get_courier"){?>
    	<ul class="list-group col-md-6">
        <li><b>Lokal</b></li>
        <?php 
		$q_courier = $db->query("SELECT * FROM ".$tpref."courier WHERE LOCAL_FLAG = '1' ORDER BY NAME ASC"); 
            while($dt_courier = $db->fetchNextObject($q_courier)){?>
            <li data-value="<?php echo $dt_courier->ID_COURIER; ?>" class="list-group-item">
                <input type="radio" 
                	   name="courier_service" 
                       id="courier_service" 
                       value="<?php echo $dt_courier->ID_COURIER; ?>"/> 
					   <?php echo $dt_courier->NAME; ?>
            </li>
        <?php } ?>
        </ul>
    	<ul class="list-group col-md-6">
        <li><b>Domestik</b></li>
        <?php 
		$q_courier = $db->query("SELECT * FROM ".$tpref."courier WHERE DOMESTIC_FLAG = '1' ORDER BY NAME ASC"); 
            while($dt_courier = $db->fetchNextObject($q_courier)){?>
            <li data-value="<?php echo $dt_courier->ID_COURIER; ?>" class="list-group-item">
                <input type="radio" 
                	   name="courier_service" 
                       id="courier_service" 
                       value="<?php echo $dt_courier->ID_COURIER; ?>"/> 
					   <?php echo $dt_courier->NAME; ?>
            </li>
        <?php } ?>
        </ul>
        <div class="clearfix"></div>
    	<ul class="list-group col-md-6">
        <li><b>Internasional</b></li>
        <?php 
		$q_courier = $db->query("SELECT * FROM ".$tpref."courier WHERE INTERNATIONAL_FLAG = '1' ORDER BY NAME ASC"); 
            while($dt_courier = $db->fetchNextObject($q_courier)){?>
            <li data-value="<?php echo $dt_courier->ID_COURIER; ?>" class="list-group-item">
                <input type="radio" 
                	   name="courier_service" 
                       id="courier_service" 
                       value="<?php echo $dt_courier->ID_COURIER; ?>"/> 
					   <?php echo $dt_courier->NAME; ?>
            </li>
        <?php } ?>
        </ul>
     <?php 
	 }
	
	if(!empty($direction) && $direction == "get_package"){
		$q_rate 	= $db->query("SELECT * FROM ".$tpref."courier_jne_tarif WHERE DESTINATION_CODE = '".$lokasi."' ORDER BY DESTINATION ASC");
		$dt_rate 	= $db->fetchNextObject($q_rate);
		@$reg_tarif = $dt_rate->REG_TARIF;
		@$reg_est 	= $dt_rate->REG_EST;
		@$ok_tarif 	= $dt_rate->OK_TARIF;
		@$ok_est 	= $dt_rate->OK_EST;
		@$yes_tarif = $dt_rate->YES_TARIF;
		@$yes_est 	= $dt_rate->YES_EST;
?>
	<div class="form-group col-md-6 no-padding-l">
        <label class="req">Paket Pengiriman</label>
        <select class="paket form-control" id="paket" onchange="add_tarif(this)">
        	<option value="" data-info="">-- PILIH PAKET --</option>
			<?php if(!empty($reg_tarif)){ ?>
                <option value="REG" data-info="<?php echo $reg_tarif; ?>">
                REGULER - 
                <?php echo money("Rp.",$reg_tarif); ?> - <?php if(!empty($reg_est)){?>(<?php echo @$reg_est; ?> Hari)<?php } ?> 
                </option>
            <?php } ?>
            <?php if(!empty($ok_tarif)){ ?>
                <option value="OK" data-info="<?php echo @$ok_tarif; ?>">
                OK - 
                <?php echo money("Rp.",$ok_tarif); ?> - 
                <?php if(!empty($ok_est)){?>(<?php echo @$ok_est; ?> Hari)<?php } ?>
                </option>
            <?php } ?>
            <?php if(!empty($yes_tarif)){ ?>
                <option value="YES" data-info="<?php echo @$yes_tarif; ?>">
                YES - 
                <?php echo money("Rp.",$yes_tarif); ?> - 
                <?php if(!empty($yes_est)){?>(<?php echo @$yes_est; ?> Hari)<?php } ?>
                </option>
            <?php } ?>
        </select>
	</div>
<?php
	}
}
?>