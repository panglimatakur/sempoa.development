<?php defined('mainload') or die('Restricted Access'); ?>
<!-- Dashboard JS -->
<!-- jQuery UI -->
    <script src="<?php echo $web_tpl_dir; ?>js/lib/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.resize.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.pie.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.tooltip.js"></script>
        <?php $num_month = $dtime->daysamonth(date('m'),date('Y')); ?>

	<script language="javascript">
    $(document).ready(function() {
		
		<?php $f = 0; while($f<$num_polling){ $f++; ?>
		if($('#ch_polling_<?php echo $id_polling[$f]; ?>').length) {
			var data 		= [<?php echo $data_polling[$f]; ?>];
			var container 	= $('#ch_polling_<?php echo $id_polling[$f]; ?>');
			$.plot(container,data,
				{
					label: "&nbsp; Produk Favorit",
					series: {
						pie: {
							show: true,
							radius:"auto",
							highlight: {
								opacity: 0.2
							}
						}
					},
					legend: {
						show: false
					},
					grid: {
						hoverable: true,
						clickable: true
					},
					tooltip: true,
					tooltipOpts: {
						content: "%s - %p.2%",
						shifts: {
							x: 20,
							y: 0
						},
						defaultTheme: false
					},
					colors: ["#0094bb","#86ae00","#f2b705","#ffad33"]
				}
			);
		}
		<?php } ?>
    });

	</script>