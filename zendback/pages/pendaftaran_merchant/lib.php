	<!-- colorpicker -->
    <link href="<?php echo $web_btpl_dir; ?>css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
    <script src="<?php echo $web_btpl_dir; ?>js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>
	<script language="javascript">
    $(document).ready(function(){
		if($('#color_1').length) {
			$('#color_1').colorpicker({
				format: 'hex'
			})
		}
		if($('#color_2').length) {
			$('#color_2').colorpicker({
				format: 'hex'
			})
		}
	})
	</script>
    