$(document).ready(function(){		
	$("#next_friend").live("click",function(){
		data_page	= $("#data_page").val();
		last_id 	= $("#next_merchant").attr("data-info");
		$("#next_merchant").html("<div style='text-align:center'><img src='files/images/loader_v.gif'></div>");
		$.ajax({
			url 	: data_page,
			type	: "POST",
			data 	: {"direction":"next_merchant","last_id":last_id}, 
			success: function(response){
				$("#next_merchant").remove();
				$(".client_list:last").after(response);
			}
		});
	})
});

