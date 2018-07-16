$(document).ready(function(){
	$('#propinsi').on('change',function(){
		data 		= $("#data_page").val();
		propinsi 	= $(this).val();
		$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_city","propinsi":propinsi},
			success	: function(response){
				$("#div_kota").html(response);
			}
		})
	})
});
