<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function chsession($session){
	global $dirhost;
	if(empty($_SESSION['$session'])){ ?> <script language="javascript" >location.href='<?php echo $dirhost; ?>';</script> <?php }
}

function proses_icon($proses){
	global $dirhost;
	switch($proses){
		case "edit":
			$img_pros = "<img src='".$dirhost."/templates/images/edit-icon.png'>";
		break;
		case "insert":
			$img_pros = "<img src='".$dirhost."/templates/images/insert-icon.png'>";
		break;
		case "delete";
			$img_pros = "<img src='".$dirhost."/templates/images/delete-icon.png'>";
		break;
		case "view":
			$img_pros = "<img src='".$dirhost."/templates/images/view-icon.png'>";
		break;
		case "print":
			$img_pros = "<img src='".$dirhost."/templates/images/print-icon.png'>";
		break;
	}
	return $img_pros;
}

?>