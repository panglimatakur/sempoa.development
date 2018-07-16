<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['show']))		{ $show 	= $sanitize->str($_REQUEST['show']); 		}
	if(!empty($_REQUEST['compose']))	{ $compose 	= $sanitize->str($_REQUEST['compose']); 	}
	if(!empty($_REQUEST['outcome']))	{ $outcome 	= $sanitize->str($_REQUEST['outcome']); 	}
	if(empty($show)){ $direction = "send"; }else{ $direction = "reply"; }
/*	if(!empty($_REQUEST['nama']))		{ $nama 	= $sanitize->str($_REQUEST['nama']); 		}
	if(!empty($_REQUEST['propinsi']))	{ $propinsi = $sanitize->str($_REQUEST['propinsi']); 	}
	if(!empty($_REQUEST['kota']))		{ $kota 	= $sanitize->str($_REQUEST['kota']); 		}
	if(!empty($_REQUEST['alamat']))		{ $alamat 	= $sanitize->str($_REQUEST['alamat']); 		}
	if(!empty($_REQUEST['tlp']))		{ $tlp 		= $sanitize->str($_REQUEST['tlp']); 		}
	if(!empty($_REQUEST['kontak']))		{ $kontak 	= $sanitize->str($_REQUEST['kontak']); 		}
	if(!empty($_REQUEST['email']))		{ $email 	= $sanitize->email($_REQUEST['email']); 	}
	if(!empty($_REQUEST['website']))	{ $website 	= $sanitize->url($_REQUEST['website']); 	}
	
	if(!empty($_REQUEST['nama_report']))		{ $nama_report 		= $sanitize->str($_REQUEST['nama_report']); 	}
	if(!empty($_REQUEST['propinsi_report']))	{ $propinsi_report 	= $sanitize->str($_REQUEST['propinsi_report']); }
	if(!empty($_REQUEST['kota_report']))		{ $kota_report 		= $sanitize->str($_REQUEST['kota_report']); 	}
	if(!empty($_REQUEST['alamat_report']))		{ $alamat_report 	= $sanitize->str($_REQUEST['alamat_report']); 	}
	if(!empty($_REQUEST['tlp_report']))			{ $tlp_report 		= $sanitize->str($_REQUEST['tlp_report']); 		}
	if(!empty($_REQUEST['kontak_report']))		{ $kontak_report 	= $sanitize->str($_REQUEST['kontak_report']); 	}
	if(!empty($_REQUEST['email_report']))		{ $email_report 	= $sanitize->email($_REQUEST['email_report']); 	}
	if(!empty($_REQUEST['website_report']))		{ $website_report 	= $sanitize->url($_REQUEST['website_report']); 	}

	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama) && !empty($propinsi) && !empty($kota) && !empty($alamat) && !empty($tlp) && !empty($kontak)){
			if(!empty($direction) && $direction == "insert"){ 
				$container = array(1=>
					array("ID_CLIENT",$_SESSION['cidkey']),
					array("CUSTOMER_NAME",@$nama),
					array("CUSTOMER_URL",@$website),
					array("CUSTOMER_EMAIL",@$email),
					array("CUSTOMER_PHONE",@$tlp),
					array("CUSTOMER_PERSON_CONTACT",@$kontak),
					array("CUSTOMER_ADDRESS",@$alamat),
					array("CUSTOMER_PROVINCE",@$propinsi),
					array("CUSTOMER_CITY",@$kota),
					array("TGLUPDATE",@$tglupdate));
				$db->insert($tpref."customers",$container);
				redirect_page($lparam."&msg=1");
			}
			if(!empty($direction) && $direction == "save"){ 
				$container = array(1=>
					array("CUSTOMER_NAME",@$nama),
					array("CUSTOMER_URL",@$website),
					array("CUSTOMER_EMAIL",@$email),
					array("CUSTOMER_PHONE",$tlp),
					array("CUSTOMER_PERSON_CONTACT",@$kontak),
					array("CUSTOMER_ADDRESS",@$alamat),
					array("CUSTOMER_PROVINCE",@$propinsi),
					array("CUSTOMER_CITY",@$kota),
					array("TGLUPDATE",@$tglupdate));
				$db->update($tpref."customers",$container," WHERE ID_CUSTOMER='".$no."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
				redirect_page($lparam."&msg=1");
			}
		}else{
			$msg = 2;
		}
	}*/
?>