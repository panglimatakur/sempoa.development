<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../includes/config.php");
include_once("../../../../includes/classes.php");
include_once("../../../../includes/functions.php");

if(!empty($_SESSION['uidkey'])){
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save" || $direction == "reply")){
		if(!empty($_REQUEST['subject']))		{ $subject 			= $sanitize->str($_REQUEST['subject']); 		}
		if(!empty($_REQUEST['question']))		{ $question 		= htmlentities($_REQUEST['question']); 			}
		if(!empty($_REQUEST['destiny']))		{ $destiny 			= $sanitize->str($_REQUEST['destiny']); 		}
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
		if(($direction == "send"  && ($destiny == "umum" || ($destiny != "umum" && !empty($participants))))){
			$done = "1";
		}
			
		if(!empty($subject) && !empty($question) && $done == 1){
			if(!empty($direction) && $direction == "insert"){ 

					$esubject 		= $subject;
					$from			= "Update Sempoa Community <support@".$website_name.">";
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
											Dear Admin ".@$nama_admin.",<br>
											".$question."
											<br><br>
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
											".$question."
											<br><br>
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
									Dear Admin ".@$nama_admin.",<br>
									".$question."
									<br><br>
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
?>