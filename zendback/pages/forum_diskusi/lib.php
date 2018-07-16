<?php defined('mainload') or die('Restricted Access'); ?>
<script src="<?php echo $web_tpl_dir; ?>js/lib/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $web_tpl_dir; ?>js/lib/datatables/js/jquery.dataTables.sorting.js"></script>
<script src="<?php echo $web_tpl_dir; ?>js/lib/datatables/js/jquery.dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(
	function(){
		$('#question').redactor({
			imageUpload: '<?php echo @$redactor; ?>scripts/image_upload.php'
		});
	}
);
</script>
