<?php
date_default_timezone_set('Asia/Jakarta');
session_start();
ob_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once('../includes/config.php');
include_once('../includes/classes.php');



?>
<form method="post" enctype="multipart/form-data" action="test.php">
    <input type="file" name="file" />
    <button name="direction" value="insert">Convert Data</button>
</form>