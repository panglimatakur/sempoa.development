<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(!empty($_REQUEST['id_merchant'])) { $id_merchant 	= $sanitize->number($_REQUEST['id_merchant']); 	}
if(!empty($_REQUEST['statlun'])) 	 { $statlun 		= $sanitize->number($_REQUEST['statlun']); 		}
?>
