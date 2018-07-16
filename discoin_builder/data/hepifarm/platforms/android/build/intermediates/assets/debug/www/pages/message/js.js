var apiDir			= $("#apiDir").val();
var id_merchant	 	= getCookie("csidkey");
var id_customer	 	= getCookie("sidkey");
function open_page(start_row,start_row_flag){
	$("#chat_loader").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");
	$.ajax({
		url 	: apiDir+"/message/model.php",
		type 	: "POST",
		data 	: {"direction":"load","id_merchant":id_merchant,"id_customer":id_customer,"start_row":start_row,"sempoakey":"99765"},
        dataType: "jsonp",
        jsonp	: "mycallback",
		success:function(result){
			if(result.io_log != "" && result.msg_log != ""){
				bootbox.alert(result.msg_log,function(){
					location.href = "index.html";
				});
			}else{
				$("#chat_list").after(result.content);
				tinggi = $("#page_content").height();
				if(tinggi > "320"){ $("#page_content").css({"height":320,"overflow-y":"scroll"});}
				if(start_row_flag == false){
					$("#page_content").animate({scrollTop: $("#page_content")[0].scrollHeight }, 1200);
				}else{
					$("#page_content").animate({scrollTop:0});
				}
		}
			$("#chat_loader").empty();
        },
		error: function (xhr, status, errorThrown) {
			alert(xhr.responseText+" "+xhr.status+" "+errorThrown); 
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	})	
}

$(document).ready(function(){
	open_page(0,false);
	$("#page_content").scroll(function() {
		var start_row 	= $("#start_row").val();
		var scroll 		= $(this).scrollTop();
		if(start_row != 0 && scroll == 0){
			$("#start_row").remove();
			open_page(start_row,true);
		}
	});


	$("#form_chat").on("submit",function(){
		//chat 		= $("#ch-message").val();
		chat  		= $(".emojionearea-editor").html();
		$(".alert-chat").remove();
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
					$("#ch-message").val("").focus();
					$(".emojionearea-editor").empty();
					
					$("#onwrite").val("");
					$("#write_status").empty();
	
					time  = wktupdate().substr(0,5);
					date  = moment().format("DD-MM-YYYY");
					
					datas = '{"id_customer":"'+id_customer+'","msg":"'+chat+'","date":"'+date+'","time":"'+time+'"}';
					adapter.publish("/update_subject_merchant_"+id_merchant,datas);
					adapter.publish("/to_chat_merchant_"+id_merchant,datas);

					note_data = {"direction":"note_bell_chat","id_page":"209","notif_type":"chat","id_merchant":id_merchant,"id_customer":id_customer};
					adapter.publish("/note_bell",note_data);
					
				},
				error: function (xhr, status, errorThrown) {
					bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
						location.href = "index.html";
					});
				}
			});
		}
	})
	$("#ch-message").on("keyup",function(){
		var onwrite		= $("#onwrite").val();
		user_name 		= getCookie("cust_name");
		var isi 		= $(this).val();
		if(isi != ""){
			if(onwrite == ""){ 
				adapter.publish("/write_chat_merchant_"+id_merchant,{"id_customer":""+id_customer+"","name":""+user_name+"","flag":"2"});
				$("#onwrite").val("2");	
			}
		}
		if(isi == ""){
			adapter.publish("/write_chat_merchant_"+id_merchant,{"id_customer":""+id_customer+"","name":""+user_name+"","flag":"1"});
			$("#onwrite").val("");	
		}
	})
	
})
