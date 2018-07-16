<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$tblnya = $tpref."products_categories";
if(isset($_REQUEST['parent_id'])) 			{ $parent_id 	= $sanitize->str($_REQUEST['parent_id']);	 		}
if(isset($_REQUEST['nama'])) 				{ $nama 		= $sanitize->str(ucwords($_REQUEST['nama']));		}
if(isset($_REQUEST['judul'])) 				{ $judul 		= $sanitize->str(ucwords($_REQUEST['judul'])); 		}
if(isset($_REQUEST['contenttype'])) 		{ $contenttype 	= $sanitize->str($_REQUEST['contenttype']); 		}
if(isset($_REQUEST['status'])) 				{ $status 		= $sanitize->str($_REQUEST['status']); 				}

if(!empty($direction) && ($direction == "save" || $direction == "insert")){
	if(!empty($nama) && !empty($judul) && !empty($contenttype)){
		$halaman = permalink($nama,"_");
		if(!empty($parent_id)){  $depth = $db->fob("SERI",$tblnya,"WHERE ID_PRODUCT_CATEGORY='".$parent_id."'")+1; }else{ $depth='1'; }
		
		$enc	= substr(md5(rand(0,100)),0,8);
		$seri 	= $db->last("SERI",$tblnya,"WHERE SERI='".$depth."'")+1;
		if($direction == "insert"){
			$content = array(1=>
						array("ID_PRODUCT_TYPE",$contenttype),
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("SERI",$seri),
						array("TITLE",$judul),
						array("NAME",$nama),
						array("PAGE",$halaman),
						array("ID_PARENT",@$parent_id),
						array("STATUS",@$status),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("TGLUPDATE",date("Y-m-d G:i:s"))
						);
			$db->insert($tblnya,$content);
			redirect_page($lparam."&msg=1");
		}	
			

		if($direction == "save"){	
			$content = array(1=>
						array("ID_PRODUCT_TYPE",$contenttype),
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("SERI",$seri),
						array("TITLE",$judul),
						array("NAME",$nama),
						array("PAGE",$halaman),
						array("ID_PARENT",@$parent_id),
						array("STATUS",@$status),
						array("BY_ID_USER",$_SESSION['uidkey']),
						array("TGLUPDATE",date("Y-m-d G:i:s"))
					   );
			$db->update($tblnya,$content,"WHERE ID_PRODUCT_CATEGORY = '".$no."'");
			redirect_page($lparam."&msg=2");
		}
	}
	else{
		echo msg("Pengisian Form Belum Lengkap","error");	
	}
}

?>
