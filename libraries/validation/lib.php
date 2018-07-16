	<?php if(!empty($dirhost)){ $validation = $dirhost."/libraries/validation/"; }else{ $validation=''; }?>
	<link rel="stylesheet" href="<?php echo $validation; ?>css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo $validation; ?>js/languages/jquery.validationEngine-ind.js" type="text/javascript" charset="utf-8">
	</script>
	<script src="<?php echo $validation; ?>js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
	</script>
	<script>
		$.validationEngine.defaults.scroll = false;
		jQuery(document).ready(function(){
			// binds form submission and fields to the validation engine
			jQuery("#formID").validationEngine();
		});
		function checkHELLO(field, rules, i, options){
			if (field.val() != "HELLO") {
				// this allows to use i18 for the error msgs
				return options.allrules.validate2fields.alertText;
			}
		}
	</script>
