
<?php
if(!defined('mainload')) { define('mainload','Master Web Card',true); }
include_once('../includes/config.php');
include_once('../includes/classes.php');
include_once('../includes/functions.php');
include_once('../includes/declarations.php');

	$table = $db->query("SHOW TABLES");
	while($dt_table = $db->fetchNextObject($table)){
		echo "<b>".$dt_table->Tables_in_db_sempoa."</b><br>";
		$field = $db->query("SELECT 
								column_name, column_type, column_default ,DATA_TYPE
							 FROM 
							 	information_schema.COLUMNS 
							 WHERE 
							 	TABLE_SCHEMA = 'db_sempoa' AND TABLE_NAME = '".$dt_table->Tables_in_db_sempoa."'");
		
		while($dt_field = $db->fetchNextObject($field)){
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$dt_field->column_name."<br>";
			
			if($dt_field->DATA_TYPE == "int"){ $default = '0'; 		}
			else				 			 { $default = 'NULL'; 	}
			
			$str_alter = "ALTER TABLE `".$dt_table->Tables_in_db_sempoa."` CHANGE `".$dt_field->column_name."` `".$dt_field->column_name."` ".$dt_field->column_type." NULL DEFAULT '".$default."'";
			$db->query($str_alter);
			
			echo "Data Type = ".$str_alter."<br>";
		}
	}
?>