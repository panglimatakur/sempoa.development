
$(document).ready(function(){
	$("#save_com").on("click",function(){
		id_com 	= $("#id_com").val();
		number 	= $("#number").val();
		li		= $(".picked").length;
		if(id_com != ""){
			merchant_lists = [];
			$(".picked").each(function(){
				merchant_list 	= $(this).attr("data-info");
				merchant_lists.push(merchant_list);
			});
			
			$("#loader").html("<img src='files/images/loading-bars.gif'>");
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"enter","id_com":id_com,"merchant_lists":merchant_lists,"number":number},
				success	: function(response){
					$("#loader").html(response);
				}
			})
		}else{
			bootbox.alert("Pengisian Form Belum Lengkap");	
		}
	})
})
function pick(id){
	id_com 		= $("#id_com").val();
	if(id_com != ""){
		//$("#loader").html(response);
		data_page 	= $("#data_page").val();
		$.ajax({
			url		: data_page,
			type	: "POST",
			data 	: {"direction":"check_community","id_com":id_com,"id_merchant":id},
			success	: function(response){
				result = JSON.parse(response);
				if(result.io == "2"){
					$("#li_"+id).animate({"position":"absolute","opacity":"0","margin-right": "-200px","margin-top": "100px"},500,
					function(){ 
						$(this).clone().insertBefore("#merchant_list_2 div:first").css({"opacity":"2.2","margin-right": "0","margin-top": "0"}).attr("class","picked item-list");
						$(this).remove();
						$("#btn_"+id).html("<i class='icsw16-bended-arrow-left'></i>").attr("onclick","cancel_pick('"+id+"')");
					});
				}else{
					bootbox.alert(result.msg);	
				}
			}
		})
	}else{
		bootbox.alert("Tentukan Nama Komunitas terlebih dahulu..");	
	}
}
function cancel_pick(id){
	$("#li_"+id).animate({"position":"absolute","opacity":"0","margin-left": "-200px","margin-top": "-100px"},500,
	function(){ 
		$(this).clone().insertBefore("#merchant_list div:first").css({"opacity":"2.2","margin-left": "0","margin-top": "0"});
		$(this).remove();
		$("#btn_"+id).html("<i class='icsw16-go-back-from-screen'></i>").attr("onclick","pick('"+id+"')");
	});
}
