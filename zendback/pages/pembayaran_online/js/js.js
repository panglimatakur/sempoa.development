$(document).ready(function(){
	$(".send_notif").on("click",function(){
		id_deal = $(this).val();
		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		proses_page 	 = $("#proses_page").val();
		$("#v_loader_"+id_deal).html('<img src="'+conf.dirhost+'/files/images/loader_v.gif"><br>');
		st	= $("#st_"+id_deal).val();
		$.ajax({
			url	 : proses_page,
			type 	: "POST",
			data	: {"direction":"set_status","id_deal":id_deal},
			success	: function(response){
				$("#v_loader_"+id_deal).html("<div class='alert alert-success'>Berhasil Dikirim</div>");
			}
		})
	})
})
function set_status(id_deal){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	proses_page 	 = $("#proses_page").val();
	$("#v_loader_"+id_deal).html('<img src="'+conf.dirhost+'/files/images/loader_v.gif"><br>');
	st	= $("#st_"+id_deal).val();
	if($("#customer_st_"+id_deal).is(":checked") == true){
		send_st_cust = "1";	
	}else{
		send_st_cust = "0";
	}
	if($("#user_st_"+id_deal).is(":checked") == true){
		send_st_user = "1";
	}else{
		send_st_user = "0";
	}
	$.ajax({
		url	 : proses_page,
		type 	: "POST",
		data	: {"direction":"set_status","id_deal":id_deal,"st":st,"send_st_cust":send_st_cust,"send_st_user":send_st_user},
		success	: function(response){
			//$("#vera").html(response);
			$("#v_loader_"+id_deal).html("<div class='alert alert-success'>Berhasil Dikirim</div>");
		}
	})
}
