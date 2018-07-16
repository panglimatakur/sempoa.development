<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	include $call->lib("tblresponsive");
	include $call->inc("zendfront/pages/".$page,"model.php"); 
	include $call->inc("zendfront/pages/".$page,"view.php"); 
?>
<script language="javascript">
 $('#responsive-example-table').stacktable({myClass:'stacktable small-only'});
</script>