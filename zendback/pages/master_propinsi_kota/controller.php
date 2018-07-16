<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$tblnya = "system_master_location";
if(!empty($_REQUEST['propinsi'])) 			{ $propinsi 	= $sanitize->number($_REQUEST['propinsi']);		}
if(!empty($_REQUEST['kota'])) 				{ $kota 		= $sanitize->number($_REQUEST['kota']);			}
if(!empty($_REQUEST['kecamatan'])) 			{ $kecamatan 	= $sanitize->number($_REQUEST['kecamatan']);	}
if(!empty($_REQUEST['nama'])) 				{ $nama 		= $sanitize->str($_REQUEST['nama']);			}


if(!empty($direction)){
	echo $direction;
	if(($direction == "save" || $direction == "insert")){
		if(!empty($propinsi))	{ $parent_id = $propinsi; 	}
		if(!empty($kota))		{ $parent_id = $kota; 		}
		if(!empty($kecamatan))	{ $parent_id = $kecamatan; 	}
		
		if(!empty($nama)){
			if($direction == "insert"){
					$last_id	= $db->last("ID_LOCATION",$tblnya,"");
					$sql_insert = array(1=>
						array("ID_LOCATION",@$last_id),
						array("NAME",@$nama),
						array("PARENT_ID",@$parent_id));
					$db->insert($tblnya,$sql_insert);
					redirect_page($lparam."&msg=1");
			}	
			if($direction == "save"){	
				$sql_update = array(1=>
					array("NAME",@$nama),
					array("PARENT_ID",@$parent_id));
				$db->update($tblnya,$sql_update,"where ID_LOCATION='".$no."'");	
				redirect_page($lparam."&msg=2");
			}
		}
		else{
			$msg = '3';
		}
	}
}
?>
