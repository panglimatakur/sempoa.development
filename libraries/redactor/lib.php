<?php //defined('mainload') or die('Restricted Access');  ?>
<?php if(!empty($dirhost)){ @$redactor = @$dirhost."/libraries/redactor/"; } ?>

	<link rel="stylesheet" type="text/css" href="<?php echo @$redactor; ?>css/style.css" />
	<!--<script type="text/javascript" src="<?php echo @$redactor; ?>js/jquery-1.9.0.min.js"></script>-->
	<link rel="stylesheet" href="<?php echo @$redactor; ?>css/redactor.css" />
	<script src="<?php echo @$redactor; ?>js/redactor.js"></script>

<!--	<textarea id="redactor_content" name="content"></textarea>
	<script type="text/javascript">
	$(document).ready(
		function(){
			$('#redactor_content').redactor({
				imageUpload: '<?php echo @$redactor; ?>scripts/image_upload.php'
			});
		}
	);
	</script>
-->