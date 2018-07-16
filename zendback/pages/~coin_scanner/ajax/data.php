<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$coin 		= isset($_POST['coin']) 		? $_POST['coin'] : "";
	$id_topic 	= isset($_REQUEST['id_topic']) 	? $_REQUEST['id_topic'] : "";	
	//print_r($_SESSION);
	function merchant_com($id_merchant){
		global $db;
		global $tpref;
		$join		= array();
		$q_com 		= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$id_merchant."'"); 
		while($merchant_join	= $db->fetchNextObject($q_com)){
			$join[] = $merchant_join->ID_COMMUNITY;
		}
		return $join;
	}
	
	if(!empty($direction) && $direction == "check"){
		$str_coin 			= "SELECT * FROM ".$tpref."customers WHERE COIN_NUMBER 	= '".$coin."' AND CUSTOMER_STATUS = '3'";
		//echo $str_coin;
		$q_coin 			= $db->query($str_coin);
		$num_coin			= $db->numRows($q_coin);
		if($num_coin > 0){
			$dt_coin		= $db->fetchNextObject($q_coin);
			$id_merchant	= $dt_coin->ID_CLIENT;
			$coin_type		= $dt_coin->INTERNAL_FLAG; 
			$pelanggan 		= "false";
			if($id_merchant == $_SESSION['cidkey']){ $pelanggan = "true"; }
			
			$merchant_com	= merchant_com($id_merchant);
			$client_com		= merchant_com($_SESSION['cidkey']);
			$result 		= count(array_intersect($client_com, $merchant_com));
			
			if($result > 0 || $pelanggan == "true"){
				//CURRENT CUSTOMER;
				$nm_customer 	= $dt_coin->CUSTOMER_NAME;	
				@$user_foto 	= $dt_coin->CUSTOMER_PHOTO;
				if(empty($user_foto)){
					$user_foto = $dirhost."/files/images/noimage-m.jpg";
				}else{
					$user_foto 		= $dirhost."/files/images/members/".$user_foto;	
					$user_foto_big 	= $dirhost."/files/images/members/big/".$dt_coin->CUSTOMER_PHOTO;	
				}
				
				//CURRENT CUSTOMER MERCHANT
				$q_partner		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_coin->ID_CLIENT."'");
				$dt_partner		= $db->fetchNextObject($q_partner);
				$nm_merchant 	= $dt_partner->CLIENT_NAME;
				$alamat_partner = $dt_partner->CLIENT_ADDRESS;
				$desc_partner 	= $dt_partner->CLIENT_DESCRIPTIONS;
				$state_partner 	= $dt_partner->CLIENT_STATEMENT;
				$url_partner 	= $dt_partner->CLIENT_URL;
				$phone_partner 	= $dt_partner->CLIENT_PHONE;
				$partner_logo	= $dt_partner->CLIENT_LOGO;
				if(empty($partner_logo)){
					$partner_logo = $dirhost."/files/images/no_image.jpg";
				}else{
					$partner_logo = $dirhost."/files/images/logos/".$partner_logo;	
				}
				//CURRENT MERCHAT
				$q_merchant		= $db->query("SELECT CLIENT_NAME,CLIENT_LOGO FROM ".$tpref."clients WHERE ID_CLIENT='".$_SESSION['cidkey']."'");
				$dt_merchant	= $db->fetchNextObject($q_merchant);
				$from_merchant	= $dt_merchant->CLIENT_NAME;
				$merchant_logo	= $dt_merchant->CLIENT_LOGO;
					
				if($coin_type == 2 && $id_merchant == 1){ $coin_type_name = "Titanium COIN"; 				}
				if($coin_type == 1 && $id_merchant == 1){ $coin_type_name = "Purple COIN"; 					}
				if($coin_type != 1 && $id_merchant != 1){ $coin_type_name = $nm_merchant." COIN"; 		}

				if(empty($merchant_logo)){
					$merchant_logo = $dirhost."/files/images/no_image.jpg";
				}else{
					$merchant_logo = $dirhost."/files/images/logos/".$merchant_logo;	
				}
	?>
        <div class="row-fluid">
            <div class="col-md-2">
                <div class="img-holder">
                    <a href='<?php echo $user_foto_big; ?>' class='fancybox'>
					<?php 
						echo getmemberfoto($dt_coin->ID_CUSTOMER,"id='cust_photo' class='thumbnail' style='width:60px; margin-top:5px'"); ?>
                    </a>
                </div>
            </div>
            <div class="col-md-10">
                <p class="form-group" style="margin:0">
                	<span class="label label-success"><?php echo $coin_type_name; ?></span>
                </p>
                <p class="form-group" style="margin:0">
                	<small class="muted">Nama:</small> <br />
					<?php echo @$dt_coin->CUSTOMER_NAME; ?>&nbsp;
                </p>
                <p class="form-group" style="margin:0">
                	<small class="muted">Bergabung:</small> <br />
					<?php echo $dtime->now2indodate2($dt_coin->TGLUPDATE); ?>&nbsp;
                </p>
				<?php if($dt_coin->ID_CLIENT != "1"){?>               
                    <p class="form-group" style="margin:0">
                        <small class="muted">Pelanggan Dari:</small> <br />
                        <?php echo @$nm_merchant; ?>
                    </p>
                <?php } ?>
           </div>
     </div>
     <div class="row-fluid" style="padding-top:0; margin-top:0">
           <?php if($dt_coin->ID_CLIENT != "1"){?>
            <div class="col-md-2">
                <div class="img-holder" style="margin-bottom:5px">
                    <img src="<?php echo $partner_logo; ?>" id='cust_photo' class='thumbnail' style='width:80px'/>
                </div>
            </div>
           <div class="col-md-10"> 
           			<?php if(!empty($alamat_partner)){?>   
                		<p class="form-group" style="margin:0">
                        	<small class="muted">Alamat Merchant :</small> <br />
							<?php echo @$alamat_partner; ?>&nbsp;
                        </p>
                    <?php } ?>
					<?php if(!empty($url_partner) && $validate->url($url_partner)){?>
                        <p class="form-group" style="margin:0">
                        	<small class="muted">Website:</small><br /> 
                        	<a href='<?php echo $url_partner; ?>' target="_blank">
								<?php echo @$url_partner; ?>&nbsp;
                           </a>
                         </p>
                    <?php } ?>
                    <?php if(!empty($phone_partner)){?>
                        <p class="form-group" style="margin:0">
                        	<small class="muted">Telephone:</small> <br />
							<?php echo @$phone_partner; ?>&nbsp;
                        </p>
                    <?php } ?>
                    <?php if(!empty($desc_partner)){?>
                        <p class="form-group" style="margin:0">
                        	<small class="muted">Deskripsi:</small><br />
							<?php echo @$desc_partner; ?>&nbsp;
                        </p>
                    <?php } ?>
                    <?php if(!empty($state_partner)){?>
                    <p class="form-group" style="margin:0">
                    	<small class="muted">Promo:</small> <br />
                        <span class='code'><?php echo @$state_partner; ?>&nbsp;</span>
                    </p>
                    <?php } ?>
                <?php } ?>
                <p class="form-group">
                	<button type='button' id='send_visit' class='btn btn-beoro-2' value='"cust_photo":"<?php echo $user_foto; ?>","id_customer":"<?php echo @$dt_coin->ID_CUSTOMER; ?>","nm_customer":"<?php echo @$nm_customer; ?>","id_merchant":"<?php echo @$id_merchant; ?>","merchant_logo":"<?php echo @$merchant_logo; ?>","nm_merchant":"<?php echo @$nm_merchant; ?>","from_id_merchant":"<?php echo @$from_merchant; ?>","wkt_visit":"<?php echo $dtime->date2indodate(@$tglupdate); ?>"'>
                    	<i class="icsw16-megaphone icsw16-white"></i>
                        Kirim Notifikasi Kunjungan
                     </button>
                </p>
            </div>
        </div>
    <?php
			}else{?>
                <div class='form-group'>
                    <label>&nbsp;</label>
                    <div class='alert alert-error' >Maaf, COIN ini tidak berlaku di merchant ini</div>
                </div>
			<?php }
		}else{
	?>
    	<div class='form-group'>
        	<label>&nbsp;</label>
			<div class='alert alert-error' >Maaf, Nomor COIN ini tidak di temukan</div>
        </div>
    <?php	
		}
	}
	
	if(!empty($direction) && $direction == "send_visit"){
		$id_customer 	= isset($_REQUEST['id_customer']) 	? $_REQUEST['id_customer'] : "";
		$id_merchant 	= isset($_REQUEST['id_merchant']) 	? $_REQUEST['id_merchant'] : "";	
		$num_visit 		= $db->recount("SELECT ID_CUSTOMER FROM ".$tpref."clients_visitors WHERE ID_CUSTOMER='".$id_customer."' AND TGLUPDATE='".$tglupdate."' ");
		if($num_visit == 0){
			$chat			= array(1=>
								array("ID_CLIENT",$_SESSION['cidkey']),
								array("CUSTOMER_ID_CLIENT",$id_merchant),
								array("ID_CUSTOMER",$id_customer),
								array("TGLUPDATE",$tglupdate));
			$db->insert($tpref."clients_visitors",$chat);
		}
		$merchant_com	= merchant_com($id_merchant);
		$client_com		= merchant_com($_SESSION['cidkey']);
		$joins = array_intersect($client_com, $merchant_com);
		
		/*echo $id_merchant."<br>";
		print_r($merchant_com);
		echo "<br>";
		print_r($client_com);
		echo "<br>";
		print_r($joins);*/
		echo json_encode($joins);
	}
}
?>
