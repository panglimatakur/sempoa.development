<?php defined('mainload') or die('Restricted Access'); ?>

<script type="text/javascript">
$(document).ready(
	function(){
		$('#question').redactor({
			imageUpload: '<?php echo @$redactor; ?>scripts/image_upload.php'
		});
	}
);
</script>
