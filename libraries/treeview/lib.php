<?php $thispath = $dirhost."/libraries/treeview/"; ?>
<link rel="stylesheet" href="<?php echo $thispath; ?>css/jquery.treeview.css" />
<script src="<?php echo $thispath; ?>js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo $thispath; ?>js/jquery.treeview.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$("#browser").treeview({
		animated:"normal",
		persist: "cookie"
	});
});
</script>
