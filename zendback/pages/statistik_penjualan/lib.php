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
				 d1 = [<?php echo $data_1; ?>]
					
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
					 ticks: [<?php echo $ticks; ?>]
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
			},
			sales	: function() {
				if($('#ch_sales').length) {
					var elem = $('#ch_sales');
					var rawData = [
						<?php 
						$b = 0;
						while($b<$jml_marketing){
							$b++;
						?>
						["<?php echo $total_sale[$b]; ?>",<?php echo $b; ?>],
						<?php 
						} 
						?>
					];
					
					var dataSet = [
						{ label: "Total Penjualan Sales", data: rawData, color: "#9B1E66" }
					];
					
					var ticks = [
						<?php 
						$c = 0;
						while($c<$jml_marketing){
							$c++;
						?>
							[<?php echo $c; ?>, "<?php echo $nm_marketing[$c]; ?>"],
						<?php 
						} 
						?>
					];
					
					
					var options = {
						series: {
							bars: {
								show: true
							}
						},
						bars: {
							align: "center",
							barWidth: 0.5,
							horizontal: true,
							fillColor: { colors: [{ opacity: 0.5 }, { opacity: 1}] },
							lineWidth: 1
						},
						xaxis: {
							axisLabel: "Total (Rp)",
							axisLabelUseCanvas: true,
							axisLabelFontSizePixels: 12,
							axisLabelFontFamily: 'Verdana, Arial',
							axisLabelPadding: 10,
							max: <?php echo $max_paid; ?>,
							tickColor: "#E8E8E8",                        
							tickFormatter: function (v, axis) {
								return $.formatNumber(v, { format: "#,###", locale: "id" });                        
							},
							color:"black"
						},
						yaxis: {
							axisLabel: "Nama Marketing",
							axisLabelUseCanvas: true,
							axisLabelFontSizePixels: 12,
							axisLabelFontFamily: 'Verdana, Arial',
							axisLabelPadding: 3,
							tickColor: "#E8E8E8",        
							ticks: ticks, 
						},
						legend: {
							noColumns: 0,
							labelBoxBorderColor: "#666",
							position: "ne"
						},
						grid: {
							hoverable: true,
							labelMargin: 10,
							axisMargin: 0,
							borderWidth: 1, 
							color: "#666",      
						}
					};
					
					$.plot($("#ch_sales"), dataSet, options);  
					$("#ch_sales").UseTooltip();
					
				}
			},
			branch 	: function() {
			<?php if(!empty($_SESSION['childkey'])){?>
				if($('#ch_branch').length) {
					var elem 	= $('#ch_branch');
					var ds 		= new Array();
					<?php 
					$f = 0;
					$q_branch 	= $db->query("SELECT * FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$_SESSION['cidkey'].",%' ");
					while($dt_branch = $db->fetchNextObject($q_branch)){
					$f++;
						$q_branch_sale			= $db->query($query_str." ".@$condition_cat." ".@$condition_periode." AND a.ID_CLIENT='".$dt_branch->ID_CLIENT."'");
						$dt_total_branch_sale	= $db->fetchNextObject($q_branch_sale);
						$penjualan[$f] 			= $dt_total_branch_sale->PAID;
						if(empty($penjualan[$f])){ $penjualan[$f] = 0; }
					?>
						var d_<?php echo $f; ?> = [[<?php echo $f; ?>,<?php echo $penjualan[$f]; ?>]];
						for (var i = 0; i < d_<?php echo $f; ?>.length; ++i) {d_<?php echo $f; ?>[i][0] += 60 * 120 * 1000};
						
		
						ds.push({
							label: "&nbsp; <?php echo $dt_branch->CLIENT_NAME; ?>",
							data:d_<?php echo $f; ?>,
							bars: {
								show: true,
								order: 1,
								lineWidth : 2,
								fill: 1
							}
						});
					<?php 
					} 
					?>
					
					var ticks = [
						<?php 
						$c=0;
						while($c<4){
							$c++;
						?>
							[<?php echo $c; ?>, "Bar <?php echo $c; ?>"],
						<?php 
						} 
						?>
					];
					var options = {
						yaxis: {
							tickFormatter: function (v, axis) {
								return accounting.formatMoney(v,"",2,".",",");
							}
						},
						xaxis: {
							ticks:ticks 
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
						legend: {
							noColumns: 0,
							labelBoxBorderColor: "#666",
							position: "ne"
						},
						
						grid: {
							hoverable: true,
							borderWidth: 0,
							color: "#666",
							labelMargin: 10,
							axisMargin: 0,
							mouseActiveRadius: 10
						},
						colors: ["#0094bb","#86ae00","#33FF66","#ffad33","#660033"]
					};
					$.plot(elem,ds,options);
				}
			<?php } ?>
			},
			product	: function() {
				if($('#ch_product').length) {
					var data 		= [<?php echo $data_pie; ?>];
					var container 	= $('#ch_product');
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
							colors: [<?php echo $color_pie; ?>]
						}
					);
				}
			},
			geografis	: function() {
				if($('#ch_geografis').length) {
					var data 		= [<?php echo $data_geografi; ?>];
					var container 	= $('#ch_geografis');
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
							colors: [<?php echo $color_geografi; ?>]
						}
					);
				}
			},
		};
		statistic.sale();
		statistic.sales();
		statistic.branch();
		statistic.product();
		statistic.geografis();
    });

    //* charts
	
	
	
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