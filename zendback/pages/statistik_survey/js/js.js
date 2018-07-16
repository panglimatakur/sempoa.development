$(document).ready(function(){		
	data 		= $("#data_page").val();
	$('#periode').on('change',function(){
		periode = $(this).val();
		$("#div_periode").html("<img src='files/images/loading-bars.gif' style='margin-left:4px'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"periode","periode":periode},
			success	: function(response){
				$("#div_periode").html(response);
			}
		})
	})
});