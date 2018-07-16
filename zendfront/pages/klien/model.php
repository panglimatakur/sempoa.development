<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$q_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
	
	$condition 			= "";
	if(!empty($search))		{ $condition .= " AND 
												(CLIENT_DESCRIPTIONS 	LIKE '%".$search."%' OR 
												 CLIENT_NAME  			LIKE '%".$search."%' OR 
												 META_DESCRIPTION 		LIKE '%".$search."%' OR
												 META_KEYWORDS 			LIKE '%".$search."%' OR
												 META_TITLE 			LIKE '%".$search."%'
												)
											"; 		
							}
	if(!empty($propinsi))	{ $condition .= " AND CLIENT_PROVINCE = '".$propinsi."'"; 	}
	if(!empty($kota))		{ $condition .= " AND CLIENT_PROVINCE = '".$propinsi."' 
											  AND CLIENT_CITY 	 = '".$kota."'"; 		}
											  
	$str_merchants = "SELECT CLIENT_APP,CLIENT_LOGO,CLIENT_NAME,CLIENT_ADDRESS,CLIENT_DESCRIPTIONS 
					  FROM 
					  	".$tpref."clients 
					  WHERE 
					  	ID_CLIENT IS NOT NULL AND 
						(CLIENT_LOGO != '' AND CLIENT_LOGO IS NOT NULL) 
						".@$condition." 
						ORDER BY ID_CLIENT DESC";
	//echo $str_merchants;
	$q_merchants = $db->query($str_merchants);
?>