<?php defined('mainload') or die('Restricted Access'); ?>
<!-- Dashboard JS -->
<!-- jQuery UI -->
    <script src="<?php echo $web_tpl_dir; ?>js/lib/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.resize.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.pie.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.orderBars.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.tooltip.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.time.js"></script>
	<script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.stack.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.symbol.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.categories.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jshashtable-2.1.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.numberformatter-1.2.3.min.js"></script>
    <script src="<?php echo $web_tpl_dir; ?>js/lib/flot-charts/jquery.flot.axislabels.js"></script>
    <?php $num_month = $dtime->daysamonth(date('m'),date('Y')); ?>

	<script language="javascript">
    $(document).ready(function() {
		statistic = {
			sale 	: function() {
				var elem 	= $('#ch_sale');
				d1 = [<?php echo $data['content']; ?>]
				var options = {
					grid: {
						hoverable: true,
						borderWidth: 0,
						color: "#666",
						labelMargin: 10,
						axisMargin: 0,
						mouseActiveRadius: 10
					},
					series: {
						lines: { show: true,lineWidth: 2 },
						points: {
							show: true,
							radius: 3,
							symbol: "circle",
							fill: true
						}
					},
					tooltip: true,
					tooltipOpts: {
						content: "%s - %y",
						shifts: {
							x: 20,
							y: 0
						},
						defaultTheme: false
					},
					xaxis: {
					 ticks: [<?php echo $data['ticks']; ?>]
					},
					yaxis: {
						tickFormatter: function (v, axis) {
							return accounting.formatMoney(v,"",2,".",",");
						}
					},
					legend: {
						noColumns: 0,
						position: "ne"
					},
					colors: ["#0094bb","#86ae00","#f2b705","#ffad33"]
				};
				$.plot(elem,d1, options);
			}
		};
		statistic.sale();
    });

	
	
	var previousPoint = null, previousLabel = null;
	$.fn.UseTooltip = function () {
		$(this).bind("plothover", function (event, pos, item) {
			if (item) {
				if ((previousLabel != item.series.label) || 
					 (previousPoint != item.dataIndex)) {
					previousPoint = item.dataIndex;
					previousLabel = item.series.label;
					$("#tooltip").remove();
	
					var x = item.datapoint[0];
					var y = item.datapoint[1];
	
					var color = item.series.color;
					showTooltip(item.pageX,
							item.pageY,
							color,
							"<strong>" + item.series.label + "</strong><br>" + item.series.yaxis.ticks[y].label + 
							" : <strong> Rp." + $.formatNumber(x, { format: "#.###", locale: "id" })  + "</strong>");                
				}
			} else {
				$("#tooltip").remove();
				previousPoint = null;
			}
		});
	};
	
	function showTooltip(x, y, color, contents) {
		$('<div id="tooltip">' + contents + '</div>').css({
			position: 'absolute',
			display: 'none',
			top: y - 10,
			left: x + 10,
			border: '2px solid ' + color,
			padding: '3px',
			'font-size': '9px',
			'border-radius': '5px',
			'background-color': '#fff',
			'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
			opacity: 0.9
		}).appendTo("body").fadeIn(200);
	}  
	
	</script>