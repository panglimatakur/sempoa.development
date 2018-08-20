var apiDir		= $("#apiDir").val();
var id_merchant	 	= $("#id_merchant").val();
var id_customer	 	= $("#id_user").val();
function open_page(){
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");
	$.ajax({
		url 	: apiDir+"/message/model.php",
		type 	: "POST",
		data 	: {"direction":"load","id_merchant":id_merchant,"id_customer":id_customer,"sempoakey":"99765"},
        dataType: "jsonp",
        jsonp	: "mycallback",
		success:function(result){
			if(result.io_log != "" && result.msg_log != ""){
				bootbox.alert(result.msg_log,function(){
					location.href = "index.html";
				});
			}else{
				$("#page_content").html(result.content);
				tinggi = $("#page_content").height();
				if(tinggi > "320"){
					$("#page_content").css({"height":320,"overflow-y":"scroll"});
					$("#page_content").animate({scrollTop: $("#page_content")[0].scrollHeight }, 1600);
				}
			}	
        },
		error: function (xhr, status, errorThrown) {
			alert(xhr.responseText+" "+xhr.status+" "+errorThrown); 
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	})	
}

function next_message(id_client){ 
	
}



$(document).ready(function(){
	open_page();
	$("#form_chat").on("submit",function(){
		chat 		= $("#ch-message").val();
		id_merchant = $("#id_merchant").val();
		id_user 	= $("#id_user").val();
		if(chat != ""){
			$.ajax({
				url 	: apiDir+"/message/controller.php",
				type	: "POST",
				dataType: "jsonp",
				jsonp	: "mycallback",
				data	: {"direction":"send_chat","id_merchant":id_merchant,"id_customer":id_customer,"isi":chat,"sempoakey":"99765"},
				success : function(result){
					$(".chat-item:last").after(result.content);
					$("#page_content").animate({scrollTop: $("#page_content")[0].scrollHeight }, 1600);
					$("#ch-message").val("");

					time  = wktupdate().substr(0,5);
					date  = moment().format("DD-MM-YYYY");
					datas = '{"id_customer":"'+id_user+'","msg":"'+chat+'","date":"'+date+'","time":"'+time+'"}';
					adapter.publish("/note_chat_merchant_"+id_merchant,datas);
					adapter.publish("/update_subject_merchant_"+id_merchant,datas);
					adapter.publish("/to_chat_merchant_"+id_merchant,datas);
				},
				error: function (xhr, status, errorThrown) {
					bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
						location.href = "index.html";
					});
				}
			});
		}
	})
})