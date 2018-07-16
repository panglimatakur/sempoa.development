<?php defined('mainload') or die('Restricted Access'); ?>
<div class="features_items"><!--category-tab-->
    <div class="col-sm-12">
    	<?php 
	if(empty($parameters) || (!empty($parameters) && substr_count($parameters,"cancel_subscribe") == 0)){
		
		$expired_date   = $dtime->tomorrow(365,date('d'),date('m'),date('Y'));
		if(!empty($done)){
			$nm_merchant 	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$id_merchant."'");
			$nama 			= $dt_customer->CUSTOMER_NAME;
			echo "
			<div class='status alert alert-success'>
				Terimakasih <b>".$nama."</b> Akun Discoin ".$nm_merchant." anda sudah berhasil diaktifkan untuk masa waktu 1 Tahun atau hingga ".$dtime->now2indodate2($expired_date).", <br><br>
				
				Anda sudah bisa menggunakan aplikasi Discoinmu untuk menikmati diskon belanja, di merchant-merchant yang terdapat didalamnya, setiap hari, baik untuk pembelian online maupun offline (langsung datang ke outletnya dengan menunjukan kode identitas member Discoin ".$nm_merchant."-mu)<br><br>
				
				Enjoy...dan Terimakasih <br><br>
				
			</div>";
			
			$from			= "Info Discoin Community <info@sempoa.biz>";
			$subject 		= "Aktifasi 2 COIN ".$nm_merchant;
			$subject_coin	= $nama." baru saja menjadi member Discoin ".$nm_merchant." ";
			$type			= "html";
			$msg_coin		= "
			<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
				".$nama." baru saja bergabung menjadi pelanggan ".$nm_merchant.", yang di support oleh Sempoa Discoin Community.
				<br>
				<br>
				Terimakasih<br><br>
				- info@sempoa.biz - <br><br>
				<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
				<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
			</div>		
			";
			$recipients 	= "thetakur@gmail.com"; //,indwic@gmail.com,junjungan70@gmail.com";
			sendmail($recipients,$subject_coin,$msg_coin,$from,$type);

			$recipient 	= "";
			$q_communities 	= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$id_merchant."'");
			while($dt_communities = $db->fetchNextObject($q_communities)){
				$q_communities_2 	= $db->query("
												SELECT 
													a.ID_CLIENT,b.CLIENT_EMAIL 
												FROM 
													".$tpref."communities_merchants a, 
													".$tpref."clients b
												WHERE 
													a.ID_CLIENT = b.ID_CLIENT AND
													a.ID_COMMUNITY = '".$dt_communities->ID_COMMUNITY."'");
				while($dt_communities_2 = $db->fetchNextObject($q_communities_2)){
					if(!empty($dt_communities_2->CLIENT_EMAIL)){
						$recipient = $dt_communities_2->CLIENT_EMAIL;
						sendmail($recipient,$subject_coin,$msg_coin,$from,$type);
					}
				}
				
			}
		}else{
			echo "
			<div class='status alert alert-danger'>
				Maaf, Link aktivasi anda tidak cocok, Aktivasi tidak bisa dilanjutkan. 
			</div>";
		}
		
	}else{
		if(!empty($done)){
			echo "
			<div class='status alert alert-success'>
				Terimakasih telah berlanggan buletin sempoa sebelumnya, sampai berjumpa kembali...
			</div>";
		}else{
			echo "
			<div class='status alert alert-danger'>
				Maaf, Link Pembatalan ini tidak cocok, pembatalan tidak bisa dilanjutkan. 
			</div>";
		}
	}
		?>
    </div>
</div><!--/category-tab-->
