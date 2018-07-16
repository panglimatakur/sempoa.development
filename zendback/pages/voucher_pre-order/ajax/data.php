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
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<div class="ibox-content">
<?php
	$id_deal 	= isset($_REQUEST['id_deal']) 	? $sanitize->number($_REQUEST['id_deal']) 	: "";
	$show 		= isset($_REQUEST['show']) 		? $sanitize->str($_REQUEST['show']) 		: "";
	
	if(!empty($show) && $show == "dealers"){
		$str_dealer = "
		SELECT a.PAID_STATUS,a.PAID_DATETIME,b.ID_CUSTOMER,b.CUSTOMER_NAME,b.CUSTOMER_PHOTO,b.ID_CLIENT FROM 
			".$tpref."customers_dealers a, 
			".$tpref."customers b 
		WHERE 
			a.ID_DISCOUNT = '".$id_deal."' AND 
			a.ID_CUSTOMER = b.ID_CUSTOMER";
		#echo $str_dealer;
		$q_dealer 	= $db->query($str_dealer);
			$id_dealhash	= transletNum($id_deal);
	?>
    	<div class='ibox-title'>Daftar Dealer Deal #<?php echo $id_dealhash; ?></div>
        <div style="width:90%; padding:5px;">
              <?php
                while($dt_dealer = $db->fetchNextObject($q_dealer)){
					$client_name = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$dt_dealer->ID_CLIENT."'");
					$paid_status = $dt_dealer->PAID_STATUS;
					switch($paid_status){
						case "1":
							$status_nm = "Menunggu Konfirmasi";
						break;
						case "2":
							$status_nm = "Konfirmasi";
						break;
						case "3":
							$status_nm = "Lunas";
						break;	
					}
            ?>
            <div>
                <div style='float:left; width:20%'>
					<?php echo getmemberfoto($dt_dealer->CUSTOMER_PHOTO," class='thumbnail' width='80%'"); ?>
                </div>
				<div style='float:left; width:80%'>
					<b class='code'><?php echo $dt_dealer->CUSTOMER_NAME; ?></b>
                    <br />
                    <span id="loader_<?php echo $id_deal; ?>_<?php echo $dt_dealer->ID_CUSTOMER; ?>"></span>
                	 Status Lunas Voucher
                    <br />
                    <select id="status_<?php echo $id_deal; ?>_<?php echo $dt_dealer->ID_CUSTOMER; ?>" onchange="set_status('<?php echo $id_deal; ?>','<?php echo $dt_dealer->ID_CUSTOMER; ?>')">
                    	<option value=''>--PILIH STATUS--</option>
                    	<option value='1' <?php if(@$paid_status == "1"){?> selected <?php } ?>>MENUNGGU</option>
                        <option value='2' <?php if(@$paid_status == "2"){?> selected <?php } ?>>KONFIRMASI</option>
                        <option value='3' <?php if(@$paid_status == "3"){?> selected <?php } ?>>LUNAS</option>
                    </select>
                </div>
            <?php } ?>
            <br clear="all" />
            </div>
        <br clear="all" />
        </div>
    <?php 
	}
?>
</div>