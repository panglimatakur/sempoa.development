<?php defined('mainload') or die('Restricted Access'); ?>
<?php
		if(!empty($_REQUEST['subject_report']))		{ $subject_report 	= $sanitize->str($_REQUEST['subject_report']); 	}
		if(!empty($_REQUEST['question_report']))	{ $question 		= $_REQUEST['question_report']; 				}
?>