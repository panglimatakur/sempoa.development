<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$tblnya = "system_pages_discoin";
if(isset($_REQUEST['is_default'])) 		 { $is_default   = $sanitize->str($_REQUEST['is_default']); 			}
if(isset($_REQUEST['parent_id'])) 		  { $parent_id 	= $sanitize->str($_REQUEST['parent_id']);	 		}
if(isset($_REQUEST['idhalaman'])) 		  { $idhalaman 	= $sanitize->str($_REQUEST['idhalaman']);			}

if(isset($_REQUEST['nama'])) 			   { $nama 		 = $sanitize->str($_REQUEST['nama']);				}
if(isset($_REQUEST['judul'])) 			  { $judul 	    = $sanitize->str($_REQUEST['judul']); 			   }
if(isset($_REQUEST['keywords'])) 		   { $keywords 	 = $sanitize->str($_REQUEST['keywords']); 			}
if(isset($_REQUEST['description'])) 		{ $description  = $sanitize->str($_REQUEST['description']); 		 }
if(isset($_REQUEST['isi'])) 				{ $isi 	  	  = $_REQUEST['isi']; 		 					     }
if(isset($_REQUEST['halaman'])) 			{ $halaman 	  = $sanitize->str($_REQUEST['halaman']); 			 }
if(isset($_REQUEST['posisi'])) 			 { $posisi 	   = $sanitize->str($_REQUEST['posisi']); 			  }
if(isset($_REQUEST['is_folder'])) 		  { $is_folder    = $sanitize->str($_REQUEST['is_folder']); 	 	   }
if(isset($_REQUEST['contenttype'])) 		{ $contenttype  = $sanitize->str($_REQUEST['contenttype']); 		 }

if(isset($_REQUEST['status'])) 			 { $status 	   = $sanitize->str($_REQUEST['status']); 			  }
if(isset($_REQUEST['depth'])) 			  { $depth 	    = $sanitize->str($_REQUEST['depth']); 			   }
if(!empty($_FILES['icon']['tmp_name'])) 	{ $icon 		 = $_FILES['icon']['name']; 						  }

if(!empty($direction) && ($direction == "save" || $direction == "insert")){
	if(!empty($nama) && !empty($judul) && !empty($posisi)){
		$halaman = permalink($nama,"_");
		if(!empty($parent_id)){  $depth = $db->fob("DEPTH",$tblnya,"where ID_PAGE_DISCOIN='".$parent_id."'")+1; }else{ $depth='1'; }
		if(empty($contenttype)){ $contenttype = "folder"; }
		$enc	= substr(md5(rand(0,100)),0,8);
		
		if($direction == "insert"){
			$seri 	= $db->last("SERI",$tblnya,"where DEPTH='".$depth."'")+1;
			if(!empty($icon)){
				$filename = $file_t."-".$icon;
				move_uploaded_file($_FILES['icon']['tmp_name'],$basepath."/files/images/icons/".$filename);	
			}else{
				$filename = "";
			}
			$content = array(1=>
						array("TYPE",$contenttype),
						array("SERI",$seri),
						array("TITLE",$judul),
						array("KEYWORDS",$keywords),
						array("DESCRIPTIONS",$description),
						array("CONTENT",mysql_real_escape_string($isi)),
						array("POSITION",$posisi),
						array("DEPTH",$depth),
						array("NAME",$nama),
						array("PAGE",$halaman),
						array("ENC_PAGE",$enc),
						array("ID_PARENT",@$parent_id),
						array("IS_FOLDER",$is_folder),
						array("ICON",$filename),
						array("STATUS",@$status),
						array("TGLUPDATE",date("Y-m-d G:i:s")));
			$db->insert($tblnya,$content);
			$newid 		=  	$db->last("ID_PAGE_DISCOIN",$tblnya,"");
			if(!empty($idhalaman)){ $idhal		=	$idhalaman.$newid."-"; }
			else{ $idhal		=	"-".$newid."-"; }
			
			$mode = chmod($basepath."/pages/",0777);
			if($is_folder == 2 && $contenttype != "statis"){ mkdir($basepath."/pages/".$halaman);}
			
			$content_2 = array(1=>array("ID_PAGE",$idhal));
			$db->update($tblnya,$content_2,"WHERE ID_PAGE_DISCOIN='".$newid."'");
			echo msg("Data Link Berhasil Disimpan","success");
		}	
			

		if($direction == "save"){	
			@$oldhalaman 	= $db->fob("PAGE",$tblnya,"where ID_PAGE_DISCOIN = '".$no."'"); 
			if(is_dir($basepath."/pages/".$oldhalaman) && $contenttype != "statis"){ 
				rename($basepath."/pages/".$oldhalaman,$basepath."/pages/".$halaman); 
			} 

			$iconori = $db->fob("ICON",$tblnya,"where ID_PAGE_DISCOIN='".$no."'");
			if(!empty($icon)){
				unlink($basepath."/files/images/icons/".$iconori);
				$filename = $file_t."-".$icon;
				move_uploaded_file($_FILES['icon']['tmp_name'],$basepath."/files/images/icons/".$filename);	
			}
			else{ $filename = $iconori; }
			
			$content = array(1=>
						array("TYPE",$contenttype),
						array("TITLE",$judul),
						array("KEYWORDS",$keywords),
						array("DESCRIPTIONS",$description),
						array("CONTENT",$isi),
						array("POSITION",$posisi),
						array("DEPTH",$depth),
						array("NAME",$nama),
						array("PAGE",$halaman),
						array("ENC_PAGE",$enc),
						array("ID_PARENT",@$parent_id),
						array("IS_FOLDER",$is_folder),
						array("ICON",$filename),
						array("STATUS",@$status),
						array("TGLUPDATE",date("Y-m-d G:i:s"))
					   );
			$db->update($tblnya,$content,"WHERE ID_PAGE_DISCOIN='".$no."'");
			echo msg("Data Link Berhasil Disimpan dan Di Perbaiki","success");
		}
	}
	else{
		echo msg("Pengisian Form Belum Lengkap","error");	
	}
}

?>
