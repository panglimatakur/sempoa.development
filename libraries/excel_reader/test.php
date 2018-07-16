<?php
/**
 * XLS parsing uses php-excel-reader from http://code.google.com/p/php-excel-reader/
 */
	header('Content-Type: text/html');

	if (isset($argv[1])){
		$Filepath = $argv[1];
	}
	elseif (isset($_FILES['file'])){
		$Filepath = $_FILES['file']["name"];
	}
	else{
		if (php_sapi_name() == 'cli'){
			echo 'Please specify filename as the first argument'.PHP_EOL;
		}
		else{
			echo 'Please specify filename as a HTTP GET parameter "File", e.g., "/test.php?File=test.xlsx"';
		}
		exit;
	}

	// Excel reader from http://code.google.com/p/php-excel-reader/
	require('php-excel-reader/excel_reader2.php');
	require('SpreadsheetReader.php');

	date_default_timezone_set('UTC');

	$StartMem = memory_get_usage();
	try{
		$Spreadsheet 	= new SpreadsheetReader($Filepath);
		$BaseMem 		= memory_get_usage();

		$Sheets 		= $Spreadsheet -> Sheets();
		foreach ($Sheets as $Index => $Name){
			echo '---------------------------------'.PHP_EOL;
			echo '*** Sheet '.$Name.' ***'.PHP_EOL;
			echo '---------------------------------'.PHP_EOL;

			$Time = microtime(true);

			$Spreadsheet -> ChangeSheet($Index);
			$nama_siswa	= "";
			foreach ($Spreadsheet as $Key => $Row){
				if($nama_siswa != $Row[0]){
					echo $Key.": ".$Row[0]." | ".$Row[1]." | ".(string)$Row[2]."<br>"; 
				}
				$nama_siswa = $Row[0];
			}
		}
	}
	catch (Exception $E)
	{
		echo $E -> getMessage();
	}
?>
