<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	$no 		= isset($_POST['no']) 			? $_POST['no'] : "";
	
	if(!empty($direction) && $direction == "delete"){
		$db->delete($tpref."posts"," WHERE ID_POST='".$no."'");
	}
	if(!empty($direction) && $direction == "delete_pic"){
		@$cover_ori = $db->fob("POST_COVER",$tpref."posts"," WHERE ID_POST='".$no."'");
		if(!empty($cover_ori) && is_file($basepath."/files/images/".$cover_ori)){ 
			unlink($basepath."/files/images/".$cover_ori); 
		}
		$db->query("UPDATE ".$tpref."posts SET POST_COVER = '' WHERE ID_POST='".$no."'");
	}
}else{
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include $call->clas("class.html2text");
		include $call->clas("class.fileanddirectory");
		$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
		
		if(!empty($direction) && ($direction == "insert" || $direction == "save" || $direction == "reply")){
			if(!empty($_FILES['cover']))			{ $cover 			= $_FILES['cover']['name']; 					}
			if(!empty($_REQUEST['subject']))		{ $subject 			= $sanitize->str($_REQUEST['subject']); 		}
			if(!empty($_REQUEST['meta_title']))		{ $meta_title 		= $sanitize->str($_REQUEST['meta_title']); 		}
			if(!empty($_REQUEST['meta_keywords']))	{ $meta_keywords 	= $sanitize->str($_REQUEST['meta_keywords']); 		}
			if(!empty($_REQUEST['meta_description'])){ $meta_description= $sanitize->str($_REQUEST['meta_description']); 		}
			
			if(!empty($_REQUEST['question']))		{ $question 		= $_REQUEST['question']; 			}
			if(!empty($_REQUEST['id_parent']))		{ $id_parent 		= $sanitize->number($_REQUEST['id_parent']); 	}
			if(!empty($_REQUEST['id_post']))		{ $id_post 			= $sanitize->number($_REQUEST['id_post']); 		}
			if(!empty($_REQUEST['reply']))			{ $reply 			= $sanitize->str($_REQUEST['reply']); 			}
			if(!empty($_REQUEST['destiny']))		{ $destiny 			= $sanitize->str($_REQUEST['destiny']); 		}
			if(isset($_REQUEST['as_article'])){ $as_article = 1; }
			
			$participants	= "";
			switch($destiny){
				case "komunitas":
					$comm			= $_REQUEST['comm'];
					$participants .= ":".$_SESSION['comidkey'][0].";";
					foreach($comm as &$id_community){
						if(!empty($id_community)){
							$participants .= ":".$id_community.";";
						}
					}
				break;
				case "personal":
					$user_person	= $_REQUEST['user_person'];
					$participants .= ":".$_SESSION['uidkey'].";";
					foreach($user_person as &$id_user){
						if(!empty($id_user)){
							$participants .= ":".$id_user.";";
						}
					}
				break;
			}
			$done = "0";
			if($direction == "reply" || (($direction == "insert" || $direction == "save") && ($destiny == "umum" || ($destiny != "umum" && !empty($participants))))){
				$done = "1";
			}
				
			if(!empty($subject) && !empty($question) && $done == 1){
				if(!empty($direction) && ($direction == "insert" || $direction == "reply")){ 
					if(!empty($cover)){
						$filename 	= $file_id."-".$cover;
						$img 		= getimagesize($_FILES['cover']['tmp_name']);
						$img_width	= $img[0];
						move_uploaded_file($_FILES['cover']['tmp_name'],$basepath."/files/images/".$filename);
						if($img_width > 400){
							$cupload->resizeupload($basepath."/files/images/".$filename,$basepath."/files/images",400,$prefix = false);
						}
					}
					if(!empty($reply) && $reply == "true"){ 
						if(empty($id_parent) && !empty($id_post)){
							$id_parent = $id_post;
						}
						if(!empty($id_parent) && !empty($id_post)){
							$id_reply  = $id_post;
						}
					}else{
						$id_parent = "";	
					}
					if(!empty($id_parent)){
						$q_destiny 		= $db->query("SELECT DESTINY,PARTICIPANTS FROM ".$tpref."posts WHERE ID_POST='".$id_parent."'");
						$dt_destiny		= $db->fetchNextObject($q_destiny);	
						$destiny 		= $dt_destiny->DESTINY;
						$participants	= $dt_destiny->PARTICIPANTS;
					}
					$container = array(1=>
						array("POST_TITLE",ucwords(@$subject)),
						array("TITLE",ucwords(@$meta_title)),
						array("KEYWORDS",ucwords(@$meta_keywords)),
						array("DESCRIPTIONS",ucwords(@$meta_description)),
						array("POST_CONTENT",mysql_real_escape_string(@$question)),
						array("POST_COVER",@$filename),
						array("ID_POST_PARENT",@$id_parent),
						array("DESTINY",@$destiny),
						array("PARTICIPANTS",@$participants),
						array("ID_POST_REPLY",@$id_reply),
						array("AS_ARTICLE",@$as_article),
						array("ID_USER",$_SESSION['uidkey']),
						array("ID_CLIENT",$_SESSION['cidkey']),
						array("TGLUPDATE",@$tglupdate),
						array("WKTUPDATE",@$wktupdate));
					$db->insert($tpref."posts",$container);
					$id_post = mysql_insert_id();
					if($direction == "reply"){
				?>
                	<script language="javascript">
						window.parent.notify_reply("Tulisan Anda Berhasil Disimpan",<?php echo $id_post; ?>,"<?php echo $direction; ?>");
					</script>
                <?php
					}else{ 
						$esubject 		= "Forum Sempoa Tech, Tentang \"".$subject."\"";
						$from			= "Info Discoin Community <info@".$website_name.">";
						$type			= "html";
						switch($destiny){
							case "komunitas":
								$comm			= $_REQUEST['comm'];
								foreach($comm as &$id_community){
									if(!empty($id_community)){
										$str_comm = "
											SELECT 
												a.ID_CLIENT,b.CLIENT_EMAIL,b.CLIENT_NAME
											FROM 
												".$tpref."communities_merchants a,
												".$tpref."clients b
											WHERE 
												a.ID_COMMUNITY = '".$id_community."' AND
												a.ID_CLIENT = b.ID_CLIENT AND
												b.ID_CLIENT != 1";
												//echo $str_comm;
										$q_admin = $db->query($str_comm);
										while($dt_admin = $db->fetchNextObject($q_admin)){
											$nama_admin = $dt_admin->CLIENT_NAME;
											$email_admin = $dt_admin->CLIENT_EMAIL;
											
											$msg 			= "
												Dear Admin ".@$nama_admin.",<br><br>
												Tulisan terbaru di Forum Sempoa, berjudul \"".$subject."\" <br><br>
												".printtext($question,200)."
												Silahkan klik
												<a href='".$dirhost."/?page=forum' target='_blank'>disini</a> untuk melihat
												<br><br>";
												
												if(!empty($as_article) && $as_article == 1){
											$msg 			.= " Atau Klik Link Artikel
												<a href='".$dirhost."/website/artikel/".$id_post."' target='_blank'>disini</a> untuk melihat
												<br><br>";
												}
											$msg 			.= "
												Terimakasih<br><br>
												<img src='".$logo_path."'><br>
												
												";
											//echo "trim(@$email_admin),@$esubject,@$msg,@$from,@$type<br>";
											sendmail(trim($email_admin),$esubject,$msg,$from,$type);
											
										}
									}
								}
							break;
							case "personal":
								$user_person	= $_REQUEST['user_person'];
								foreach($user_person as &$id_user){
									if(!empty($id_user)){
										if($direction != "reply"){
											$email_user		= $db->fob("USER_EMAIL","system_users_client"," WHERE ID_USER = '".$id_user."'");
											$msg 			= "
												Dear ".@$nama_user.",<br>
												Seseorang mengajak anda mendiskusikan sesuatu di Forum Sempoa, berjudul \"".$subject."\" <br>
												Silahkan klik
												<a href='".$dirhost."/?page=forum' target='_blank'>disini</a> untuk melihat
												<br><br>";
												
												if(!empty($as_article) && $as_article == 1){
											$msg 			.= " Atau Klik Link Artikel
												<a href='".$dirhost."/website/artikel/".$id_post."' target='_blank'>disini</a> untuk melihat
												<br><br>";
												}
											$msg 			.= "
												Terimakasih<br><br>
												<img src='".$logo_path."'><br>
												
												";
											sendmail(trim($email_user),$esubject,$msg,$from,$type);
											#echo trim($email_user)."<br>".$esubject."<br>".$msg."<br>".$from."<br>".$type."<br>";
										}
									}
								}
							break;
							default:
								$q_admin = $db->query("SELECT CLIENT_EMAIL,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT != 1");
								while($dt_admin = $db->fetchNextObject($q_admin)){
									$nama_admin = $dt_admin->CLIENT_NAME;
									$email_admin = $dt_admin->CLIENT_EMAIL;
									$msg 			= "
										Dear Admin ".@$nama_admin.",<br><br>
										Tulisan terbaru di Forum Sempoa, berjudul \"".$subject."\" <br><br>
										".printtext($meta_title,200)."
										Silahkan klik
										<a href='".$dirhost."/?page=forum' target='_blank'>disini</a> untuk melihat
										<br><br>";
										
										if(!empty($as_article) && $as_article == 1){
									$msg 			.= " Atau Klik Link Artikel
										<a href='".$dirhost."/website/artikel/".$id_post."' target='_blank'>disini</a> untuk melihat
										<br><br>";
										}
									$msg 			.= "
										Terimakasih<br><br>
										<img src='".$logo_path."'><br>
										
										";
									sendmail(trim($email_admin),$esubject,$msg,$from,$type);
								}
							break;	
						}
				?>
                	<script language="javascript">
						window.parent.notify("Tulisan Anda Berhasil Disimpan","redirect");
					</script>
				<?php } 
				}
				if(!empty($direction) && $direction == "save"){ 
					@$cover_ori = $db->fob("POST_COVER",$tpref."posts"," WHERE ID_POST='".$no."'");
					if(!empty($cover)){
						if(!empty($cover_ori) && is_file($basepath."/files/images/".$cover_ori)){ 
							unlink($basepath."/files/images/".$cover_ori); 
						}
						$filename 	= $file_id."-".$cover;
						$img 		= getimagesize($_FILES['cover']['tmp_name']);
						$img_width	= $img[0];
						move_uploaded_file($_FILES['cover']['tmp_name'],$basepath."/files/images/".$filename);
						if($img_width > 400){
							$cupload->resizeupload($basepath."/files/images/".$filename,$basepath."/files/images",400,$prefix = false);
						}
					}else{
						$filename = $cover_ori;
					}
					$container = array(1=>
						array("POST_TITLE",ucwords(@$subject)),
						array("TITLE",ucwords(@$meta_title)),
						array("KEYWORDS",ucwords(@$meta_keywords)),
						array("DESCRIPTIONS",ucwords(@$meta_description)),
						array("POST_CONTENT",mysql_real_escape_string(@$question)),
						array("POST_COVER",@$filename),
						array("DESTINY",@$destiny),
						array("PARTICIPANTS",@$participants),
						array("ID_USER",$_SESSION['uidkey']),
						array("TGLUPDATE",@$tglupdate),
						array("WKTUPDATE",@$wktupdate));
					$db->update($tpref."posts",$container," WHERE ID_POST='".$id_post."' AND ID_CLIENT='".$_SESSION['cidkey']."' AND ID_USER = '".$_SESSION['uidkey']."'");
					//redirect_page($lparam."&msg=1");
				?>
                	<script language="javascript">
						window.parent.notify("Tulisan Anda Berhasil Disimpan","redirect");
					</script>
                <?php
				}
			}else{
				?>
                	<script language="javascript">
						window.parent.notify("Periksa Pengisan <b>Judul</b>, <b>Isi Tulisan</b>, atau <b>\"Siapa saja yang dapat melihat tulisan ini?\"</b>","");
					</script>
                <?php
			}
		}
	}
}
?>