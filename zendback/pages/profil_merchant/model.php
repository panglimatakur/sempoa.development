<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$qcont				=	$db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$_SESSION['cidkey']."' ");
	$dtedit				=	$db->fetchNextObject($qcont);
	$nama				=	$dtedit->CLIENT_NAME;
	$photo				=	$dtedit->CLIENT_LOGO;
	$propinsi 			= 	$dtedit->CLIENT_PROVINCE; 	
	$kota 				= 	$dtedit->CLIENT_CITY; 		
	$alamat 			= 	$dtedit->CLIENT_ADDRESS; 			
	$tlp 				= 	$dtedit->CLIENT_PHONE; 		
	$kontak 			= 	$dtedit->CLIENT_PERSON_CONTACT; 		
	$email 				= 	$dtedit->CLIENT_EMAIL; 	
	@$colour 			= 	explode(";",$dtedit->COLOUR); 
	@$w[1]				= 	$colour[0];
	@$w[2]				= 	$colour[1];
	$app 				= 	$dtedit->CLIENT_APP; 
	if(empty($app)){
		$app_name		=  	$sanitize->str(strtolower(str_replace(" ","",$nama)));
		$count_app		= $db->recount("SELECT CLIENT_APP FROM ".$tpref."clients WHERE CLIENT_APP='".$app_name."'");
		if($count_app > 0){
			@$app 		= $app_name."".md5(substr($app_name,0,3));
		}else{
			@$app 		= $app_name;
		}
	}
	$website 			= 	$dtedit->CLIENT_URL; 
	if(empty($dtedit->CLIENT_URL)){ $website = $dirhost."/".$app.".coin"; }		
	$deskripsi			=	$dtedit->CLIENT_DESCRIPTIONS;
	$desc_len 			=	400-strlen(trim($deskripsi));
	$meta_title			=	$dtedit->META_TITLE;
	$meta_title_len 	=	57-strlen(trim($meta_title));
	$meta_description	=	$dtedit->META_DESCRIPTION;
	$meta_description_len =	160-strlen(trim($meta_description));
	
	$meta_keywords		=	$dtedit->META_KEYWORDS;
	$meta_keywords_exp  =	explode(",",$meta_keywords);
	$meta_keywords_len  =	count($meta_keywords_exp);
	
	$q_client_map		=	$db->query("SELECT * FROM ".$tpref."clients_maps 
										WHERE 
											ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY ID_CLIENT_MAP ASC");
	$coordinate	= "";
	while($dt_client_map =	$db->fetchNextObject($q_client_map)){
		$latlng 	 = $dt_client_map->COORDINATES;
		$coordinate .= "[".$dt_client_map->COORDINATES.",'".$dt_client_map->MARKER_LABEL."'],";
	}
	@$coordinate 	 = substr($coordinate,0,-1);
	@$latlng 		 = explode(",",$latlng);
	@$lat 			 = $latlng[0];
	@$lng 			 = $latlng[1];
?>
