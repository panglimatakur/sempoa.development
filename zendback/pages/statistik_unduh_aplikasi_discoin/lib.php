<?php defined('mainload') or die('Restricted Access'); ?>
<!-- chartJS -->
<script src="<?php echo $web_btpl_dir; ?>js/plugins/chartJs/Chart.min.js"></script>
<?php $num_month = $dtime->daysamonth($cur_month,$cur_year); ?>
<script>
var data_page = $("#data_page").val();
function open_chart(periode,cur_month,cur_year,cur_year2){
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"direction"	:"get_statistik",
				   "periode"	:periode,
				   "cur_month"	:cur_month,
				   "cur_year"	:cur_year,
				   "cur_year2"	:cur_year2},
		success	: function(result){
			data = JSON.parse(result);
			$("#label_periode").html(result.label_periode);
			var lineData = {
				labels: data.label,
				datasets: [
					{
						label: "Kunjungan Toko Online",
						color: "#09355C",
						fillColor: "rgba(26,179,148,0.3)",
						strokeColor: "rgba(26,179,148,0.7)",
						pointColor: "rgba(26,179,148,1)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(26,179,148,1)",
						data: data.kunjungan
					},
					{
						label: "Pemesanan Toko Online (Belum di bayar)",
						color: "#e8d440",
						fillColor: "rgba(244, 223, 66,0.3)",
						strokeColor: "rgb(232, 212, 64)",
						pointColor: "rgb(232, 212, 64)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(26,179,148,1)",
						data: data.pemesanan
					},
					{
						label: "Penjualan Toko Online",
						color: "#e23175",
						fillColor: "rgba(247, 74, 140,0.3)",
						strokeColor: "rgba(232, 64, 129,1)",
						pointColor: "rgb(232, 64, 129)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(26,179,148,1)",
						data: data.penjualan
					}
				]
			};
		
			var lineOption = {
				scaleShowGridLines: true,
				scaleGridLineColor: "rgba(0,0,0,.05)",
				scaleGridLineWidth: 1,
				bezierCurve: true,
				bezierCurveTension: 0.4,
				pointDot: true,
				pointDotRadius: 4,
				pointDotStrokeWidth: 1,
				pointHitDetectionRadius: 20,
				datasetStroke: true,
				datasetStrokeWidth: 2,
				datasetFill: true,
				responsive: true,
				legend: {}
			};
		
		
			var ctx = document.getElementById("lineChart").getContext("2d");
			var myNewChart = new Chart(ctx).Line(lineData, lineOption);
			document.getElementById('js-legend').innerHTML = myNewChart.generateLegend();
			
		}
	})
}

$(function () {
	open_chart("harian","<?php echo $cur_month; ?>","<?php echo $cur_year; ?>","");
})
</script>
    
