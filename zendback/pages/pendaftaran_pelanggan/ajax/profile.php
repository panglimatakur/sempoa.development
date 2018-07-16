<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$coin 			= isset($_REQUEST['coin']) ? $_REQUEST['coin']:"";
	$id_customer 	= isset($_REQUEST['id_customer']) ? $_REQUEST['id_customer']:"";
	
	$str_coin 		= "SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER='".$id_customer."'";
	$q_coin 		= $db->query($str_coin);
	$num_coin		= $db->numRows($q_coin);
	$dt_coin		= $db->fetchNextObject($q_coin);
	$cidkey 		= $dt_coin->ID_CLIENT;
	$id_customer 	= $dt_coin->ID_CUSTOMER;
	@$icust_name	= $dt_coin->CUSTOMER_NAME;
	@$icust_email	= $dt_coin->CUSTOMER_EMAIL;
	@$icust_phone	= $dt_coin->CUSTOMER_PERSON_CONTACT;
	$coin 			= $dt_coin->COIN_NUMBER;
	@$keterangan	= $dt_coin->ADDITIONAL_INFO; 
	@$active_id		= $dt_coin->CUSTOMER_STATUS;
	if(empty($active_id)){ $active_id = 0; }

	switch ($active_id){
		case "1":
			$cust_status = "PENGAJUAN";
		break;
		case "2":
			$cust_status = "REVIEW";
		break;
		case "3":
			$cust_status = "AKTIF";
		break;
		case "4":
			$cust_status = "DAFTAR HITAM";
		break;
		default:
			$cust_status = "NON AKTIF";
		break;
	}
?>
<div class="ibox float-e-margins" style="margin:0">
    <div class="ibox-title">
        <h4>Profil Pelanggan</h4>
    </div>
    <div class="ibox-content user_profile">
        <div class="col-md-3">
            <div class="thumbnail">
            	<div class="thumbnail-inner" style="mx-height:500px; overflow:hidden">
                <?php echo getmemberfoto($dt_coin->ID_CUSTOMER,"class='img-avatar' style='width:100%'"); ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group col-md-6">
                <label>Status Aktif:</label> <br />
                <span class='label label-success'><?php echo @$cust_status; ?></span>
            </div>
        <?php if(!empty($coin)){?>
            <div class="form-group col-md-6">
                <label>COIN:</label> <br />
                <b><?php echo @strtoupper($coin); ?></b>
            </div>
        <?php } ?>
        <?php if(!empty($icust_name)){?>
            <div class="form-group col-md-6">
                <label>Nama:</label> <br />
                <b><?php echo @$icust_name; ?></b>
            </div>
        <?php } ?>
        <?php if(!empty($icust_email)){?>
            <div class="form-group col-md-6">
                <label>Email:</label> <br />
                <b><?php echo @$icust_email; ?></b>
            </div>
        <?php } ?>
		<?php if(!empty($icust_phone)){?>
            <div class="form-group col-md-6">
                <label>Nomor HP:</label> <br />
                <b><?php echo @$icust_phone; ?></b>
             </div>
        <?php } ?>
        <?php if(!empty($dt_coin->CUSTOMER_ADDRESS)){?>
            <div class="form-group col-md-6">
                <label>Alamat:</label> <br />
                 <b><?php echo @$dt_coin->CUSTOMER_ADDRESS; ?></b>
            </div>
        <?php } ?>
        <div class="clearfix"></div>
    </div>
</div>

<?php
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<div class="clearfix"></div>