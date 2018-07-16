<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['type']))		{ $type 	= $sanitize->str($_REQUEST['type']); 		}
	if(!empty($_REQUEST['nama']))		{ $nama 	= $sanitize->str($_REQUEST['nama']); 		}
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
					array("PARTNER_NAME",$nama),
					array("PARTNER_URL",@$website),
					array("PARTNER_EMAIL",@$email),
					array("PARTNER_PHONE",@$tlp),
					array("PARTNER_PERSON_CONTACT",@$kontak),
					array("PARTNER_ADDRESS",$alamat),
					array("PARTNER_PROVINCE",$propinsi),
					array("PARTNER_CITY",$kota),
					array("TGLUPDATE",$tglupdate));
				$db->insert($tpref."partners",$container);
				redirect_page($lparam."&msg=1");
			}
			if(!empty($direction) && $direction == "save"){ 
				$container = array(1=>
					array("PARTNER_NAME",$nama),
					array("PARTNER_URL",@$website),
					array("PARTNER_EMAIL",@$email),
					array("PARTNER_PHONE",@$tlp),
					array("PARTNER_PERSON_CONTACT",@$kontak),
					array("PARTNER_ADDRESS",$alamat),
					array("PARTNER_PROVINCE",$propinsi),
					array("PARTNER_CITY",$kota),
					array("TGLUPDATE",$tglupdate));
				$db->update($tpref."partners",$container," WHERE ID_PARTNER='".$no."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
				redirect_page($lparam."&msg=1");
			}
		}else{
			$msg = 2;
		}
	}
?>