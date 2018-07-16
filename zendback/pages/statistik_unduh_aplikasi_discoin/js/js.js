$(document).ready(function() {
    $("#periode").on("change",function(){
		$(".periode_options").hide();
		periode = $(this).val();
		switch(periode){
			case "harian":
				$('#div_month').show();
				$('#div_year').show();
			break;
			case "bulanan":
				$('#div_year').show();
			break;
			case "tahunan":
				$('#div_year2').show();
			break;	
		}
	})
	$("#show").on("click",function(){
		periode = $("#periode").val();
		switch(periode){
			case "harian":
				cur_month 	= $('#cur_month').val();
				cur_year 	= $('#cur_year').val();
				open_chart(periode,cur_month,cur_year,"");
				
			break;
			case "bulanan":
				cur_year 	= $('#cur_year').val();
				open_chart(periode,"",cur_year,"");
			break;
			case "tahunan":
				cur_year 	= $('#cur_year3').val();
				cur_year2 	= $('#cur_year4').val();
				open_chart(periode,"",cur_year,cur_year2);
			break;	
		}
	})
});