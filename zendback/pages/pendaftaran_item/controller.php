<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['id_type_report']))		{ $id_type_report 	= $sanitize->number($_REQUEST['id_type_report']); 	}
	if(!empty($_REQUEST['id_kategori_report']))	{ $id_kategori_report 	= $sanitize->number($_REQUEST['id_kategori_report']); 	}
	if(!empty($_REQUEST['code_report']))		{ $code_report 			= $sanitize->str($_REQUEST['code_report']); 			}
	if(!empty($_REQUEST['nama_report']))		{ $nama_report 			= $sanitize->str($_REQUEST['nama_report']); 			}
	if(!empty($_REQUEST['satuan_report']))		{ $satuan_report 		= $sanitize->str($_REQUEST['satuan_report']); 			}
	if(!empty($_REQUEST['deskripsi_report']))	{ $deskripsi_report 	= $sanitize->str($_REQUEST['deskripsi_report']); 		}

	if(!empty($_REQUEST['code']))				{ $code 				= $sanitize->str($_REQUEST['code']); 					}
	if(!empty($_REQUEST['nama']))				{ $nama 				= $sanitize->str(ucwords($_REQUEST['nama'])); 			}
	if(!empty($_REQUEST['satuan']))				{ $satuan 				= $sanitize->str($_REQUEST['satuan']); 					}
	if(!empty($_REQUEST['deskripsi']))			{ $deskripsi 			= $sanitize->str($_REQUEST['deskripsi']); 				}
	if(!empty($_REQUEST['harga']))				{ $harga 				= $sanitize->str($_REQUEST['harga']); 					}
	if(!empty($_REQUEST['id_type']))			{ $id_type 				= $sanitize->number($_REQUEST['id_type']); 				}
	if(!empty($_REQUEST['id_kategori']))		{ $id_kategori 			= $sanitize->number($_REQUEST['id_kategori']); 			}
	if(!empty($_REQUEST['counter']))			{ $counter 			= $sanitize->number($_REQUEST['counter']); 	}
	if(!empty($direction) && $direction != "edit" && $direction != "show"){
		if(!empty($id_type)){
			if($direction == "insert" || $direction == "save"){
				$upload_session	= $file_id;
				
				if($direction == "save"){
					$prod_content = array(1=>
									  array("ID_PRODUCT_CATEGORY",@$id_kategori),
									  array("ID_PRODUCT_TYPE",@$id_type),
									  array("CODE",@$code),
									  array("NAME",@$nama),
									  array("SALE_PRICE",@$harga),
									  array("ID_PRODUCT_UNIT",@$satuan),
									  array("DESCRIPTION",@$deskripsi),
									  array("ID_STATUS","3"),
									  array("BY_ID_USER",$_SESSION['uidkey']),
									  array("TGLUPDATE",@$tglupdate),
									  array("WKTUPDATE",@$wktupdate));
					$db->update($tpref."products",$prod_content," WHERE ID_PRODUCT='".$no."' AND ID_CLIENT = '".$_SESSION['cidkey']."'");					
					
					if(!is_dir($basepath."/files/images/products/".$id_client)){
						mkdir($basepath."/files/images/products/".$id_client,0777);	
					}
					if(!is_dir($basepath."/files/images/products/".$id_client."/thumbnails")){
						mkdir($basepath."/files/images/products/".$id_client."/thumbnails",0777);	
					}
				
					@$photo_ori	= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$no."'");
					if(!empty($_FILES['image']['name'][0])){
						if(is_file($basepath."/files/images/products/".$id_client."/".$photo_ori)){
							unlink($basepath."/files/images/products/".$id_client."/".$photo_ori);
							unlink($basepath."/files/images/products/".$id_client."/thumbnails/".$photo_ori);
						}
						$db->delete($tpref."products_photos"," WHERE ID_PRODUCT='".$no."'");
						
						$dest			= $basepath."/files/images/products/".$id_client;
						$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
						$filename		= $file_id."-".$_FILES['image']['name'][0];
						$src			= array($_FILES['image']['tmp_name'][0],$filename);
						$dest_thumb		= $basepath."/files/images/products/".$id_client."/thumbnails";
						$new_width		= '300';
						
						$cupload->upload($src,$dest,$extensions);
						$cupload->resizeupload($dest."/".$filename,$dest_thumb,$new_width);
						$image_content 	= array(1=>
											array("ID_PRODUCT",$no),
											array("PHOTOS",$filename));
						$db->insert($tpref."products_photos",$image_content);
					}
				}

				if($direction == "insert"){
					$x 			= 0;
					$counter_all = $counter;
					$dest			= $basepath."/files/images/products/".$id_client;
					$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
					$z = 0;
					for($y = 1; $y <= $counter; $y++){
						$x = $counter_all;
						//$z = $counter_all-1;
						if(!empty($_REQUEST['group'][$y]))			{ 
							$group[$y] 	= $sanitize->number($_REQUEST['group'][$y]); 					}
						if(!empty($group[$y])){
							if(!empty($_REQUEST['code_arr'][$z]))		{ 
								$code_arr[$z] = $sanitize->str($_REQUEST['code_arr'][$z]); 					}
							if(!empty($_REQUEST['nama_arr'][$z]))		{ 
								$nama_arr[$z] = $sanitize->str($_REQUEST['nama_arr'][$z]); 					}
							if(!empty($_REQUEST['deskripsi_arr'][$z]))	{ 
									$deskripsi_arr[$z] = $sanitize->str($_REQUEST['deskripsi_arr'][$z]); 	}
							if(!empty($_REQUEST['harga_arr'][$z]))		{ 
								$harga_arr[$z] = $sanitize->str($_REQUEST['harga_arr'][$z]); 				}
							if(!empty($_REQUEST['satuan_arr'][$z]))		{ 
								$satuan_arr[$z] = $sanitize->str($_REQUEST['satuan_arr'][$z]); 				}
						}
						
						if(!empty($group[$y])){
							//echo "Y ".$y." = ".$x." - ".@$_REQUEST['nama_arr'][$e]." - ".$_FILES['image']['name'][$x]."<br>";
							$prod_content = array(1=>
											  array("ID_CLIENT",$_SESSION['cidkey']),
											  array("ID_PRODUCT_TYPE",@$id_type),
											  array("ID_PRODUCT_CATEGORY",@$id_kategori),
											  array("CODE",@$code_arr[$z]),
											  array("NAME",@$nama_arr[$z]),
											  array("SALE_PRICE",@$harga_arr[$z]),
											  array("ID_PRODUCT_UNIT",@$satuan_arr[$z]),
											  array("DESCRIPTION",@$deskripsi_arr[$z]),
											  array("ID_STATUS","3"),
											  array("BY_ID_USER",$_SESSION['uidkey']),
											  array("UPLOAD_SESSION",$upload_session),
											  array("TGLUPDATE",@$tglupdate),
											  array("WKTUPDATE",@$wktupdate));
							$db->insert($tpref."products",$prod_content);
							$id_product[$z]	 = mysql_insert_id();
							if(!is_dir($basepath."/files/images/products/".$id_client)){
								mkdir($basepath."/files/images/products/".$id_client,0777);	
							}
							if(!is_dir($basepath."/files/images/products/".$id_client."/thumbnails")){
								mkdir($basepath."/files/images/products/".$id_client."/thumbnails",0777);	
							}
							
							
							$filename[$x]	= $file_id."-".$_FILES['image']['name'][$x];
							$src			= array($_FILES['image']['tmp_name'][$x],$filename[$x]);
							$dest_thumb		= $basepath."/files/images/products/".$id_client."/thumbnails";
							$new_width		= '300';
							
							$cupload->upload($src,$dest,$extensions);
							$cupload->resizeupload($dest."/".$filename[$x],$dest_thumb,$new_width);
							$image_content 	= array(1=>
												array("ID_PRODUCT",@$id_product[$z]),
												array("PHOTOS",@$filename[$x]));
							$db->insert($tpref."products_photos",$image_content);
							$z++;
						}
						$counter_all = $counter_all-1;
					}
				}
				redirect_page($lparam."&msg=1");
				?>
                	<script language="javascript">
                    	send_note('<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php','<?php echo $upload_session; ?>');
                    </script>
                <?php 
			}
		}else{
			$msg = 2;
		}
		$class_proses = "active";
	}
	
?>