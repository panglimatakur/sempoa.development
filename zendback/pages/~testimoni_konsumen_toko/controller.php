<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_FILES['photo']['name'])){ $photo 		= $_FILES['photo']; 							}
	if(!empty($_REQUEST['nama']))		{ $nama 		= $sanitize->str($_REQUEST['nama']); 			}
	if(!empty($_REQUEST['testimonial'])){ $testimonial 	= $sanitize->str($_REQUEST['testimonial']); 	}
	$id_customer = isset($_REQUEST['id_customer']) ? $_REQUEST['id_customer'] : "";	
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama) && !empty($testimonial)){
			
			$dest			= $basepath."/files/images/members";
			$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
			
			if($direction == "save"){
				$photo_ori = $db->fob("PHOTO",$tpref."clients_testimonials"," WHERE ID_CLIENT_TESTIMONIAL = '".$no."'"); 
			}
			if(!empty($photo)){
				@$photo_name	= $file_id."-".$_FILES['photo']['name'];
				$src			= array($_FILES['photo']['tmp_name'],$photo_name);
				$dest_thumb		= $basepath."/files/images/members/";
				$new_width		= '500';
				
				if(!empty($photo_ori) && is_file($basepath."/files/images/members/".$photo_ori)){
					unlink($basepath."/files/images/members/".$photo_ori);	
				}
				
				$cupload->upload($src,$dest,$extensions);
				$cupload->resizeupload($dest."/".$photo_name,$dest_thumb,$new_width);
			}else{
				if($direction == "save"){
					@$photo_name	= $photo_ori;
				}
			}
			
			if(!empty($direction) && $direction == "insert"){ 
				$container = array(1=>
					array("ID_CLIENT",$_SESSION['cidkey']),
					array("ID_CUSTOMER",@$id_customer),
					array("NAME",@$nama),
					array("PHOTO",@$photo_name),
					array("TESTIMONIAL",@$testimonial),
					array("TGLUPDATE",$tglupdate));
				$db->insert($tpref."clients_testimonials",$container);
				redirect_page($lparam."&msg=1");
			}
			
			if(!empty($direction) && $direction == "save"){ 
				$container = array(1=>
					array("NAME",@$nama),
					array("PHOTO",@$photo_name),
					array("TESTIMONIAL",@$testimonial),
					array("TGLUPDATE",$tglupdate));
				$db->update($tpref."clients_testimonials",$container," WHERE ID_CLIENT_TESTIMONIAL='".$no."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
				redirect_page($lparam."&msg=1");
			}
		}else{
			$msg = 2;
		}
	}
?>