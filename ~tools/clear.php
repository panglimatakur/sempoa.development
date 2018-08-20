<?php
if(!defined('mainload')) { define('mainload','Master Web Card',true); }
include_once('../includes/config.php');
include_once('../includes/classes.php');
include_once('../includes/functions.php');
include_once('../includes/declarations.php');

if(!empty($_REQUEST['proses']) && $_REQUEST['proses'] == "2"){

mysql_query("TRUNCATE TABLE cat_cash_debt_credit");

mysql_query("TRUNCATE TABLE cat_products_buys");
mysql_query("TRUNCATE TABLE cat_products_buys_history");

mysql_query("TRUNCATE TABLE cat_products_stocks");
mysql_query("TRUNCATE TABLE cat_products_stocks_history");

mysql_query("TRUNCATE TABLE cat_products_sales");
mysql_query("TRUNCATE TABLE cat_products_sales_history");

mysql_query("TRUNCATE TABLE cat_factures");
mysql_query("TRUNCATE TABLE cat_products_distributions");
mysql_query("TRUNCATE TABLE cat_draft");
mysql_query("TRUNCATE TABLE cat_debt_credit_reminder");


mysql_query("DELETE FROM cat_cash_flow WHERE ID_CASH_TYPE !='76'");
mysql_query("DELETE FROM cat_cash_flow_history WHERE ID_CASH_TYPE !='76'");
echo "Berhasil Di Bersihkan";
}
?>
<form name="form1" method="post" action="">
  <button type="submit" name="proses" value="2">BOM SEMUA</button>
</form>
