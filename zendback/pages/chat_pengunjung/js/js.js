$(document).ready(function(){	
	var info 			= JSON.parse("{"+$("#sender_info").val()+"}");
	var user_photo		= info.user_photo; 
	var user_name		= info.user_name; 
	var wkt_chat		= info.wkt_chat;
	var tgl_chat		= info.tgl_chat;
	
	$("#ch-message-send").on("click",function(){
		var messageInput 	= $('.ch-message-input');
		var id_merchant 	= $("#ch-message-send").val();
		var id_customer 	= $("#id_customer").val();
		var messageVal 		= messageInput.val();
		var messageVal 		= messageVal.replace(/^\s+/, '').replace(/\s+$/, '');
		if(messageVal != ''){
  			var conf 		= JSON.parse("{"+$("#config").val()+"}");
			chat_list 		= $("#chat_list").val();
			$("#chat_load").html("<img src='"+conf.dirhost+"/files/images/loading-bars.gif'><br>");
			$.ajax({
				url 	: chat_list,
				type	: "POST",
				data 	: {"direction":"send_chat","username":user_name,"id_customer":id_customer,"pesan":messageVal}, 
				success : function(response){
					result = JSON.parse(response);
					$("#chat_load").empty();
					if(conf.realtime == 1){ 
						container   	= {"src"		:"USER",
										   "id_client"	:id_merchant,
										   "id_chat"	:result.id_chat,
										   "uidkey"		:conf.uidkey,
										   "user_photo"	:""+user_photo+"",
										   "user_name"	:""+user_name+"",
										   "wkt_chat"	:""+wkt_chat+"",
										   "tgl_chat"	:""+tgl_chat+"",
										   "msg"		:""+messageVal+""};	
						tulcom.publish("/note_chat_customer_"+id_customer,{"note":"1"});
						tulcom.publish("/to_chat_customer_"+id_customer,container);
					}
					$('.ch-messages').append(result.content);
					$("#alert_"+id_customer).remove();
					$("#subject_"+id_customer).removeClass("ch-topic-item").addClass("ch-topic-item-new");
					$('.ch-messages-added').attr('id','').removeClass('ch-messages-added').show();
					$(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
					messageInput.val('').focus();
					$("#onwrite").val("");
				}
			})	
		} else {
			$('.ch-message-input').closest('.control-group').addClass('error');
		}
	})

	if($(".ch-messages").length > 0){
		$(".ch-messages").animate({ scrollTop: $(".ch-messages")[0].scrollHeight }, 600);	
	}
	
	$('#search_button').on('click',function(){
		var conf 	= JSON.parse("{"+$("#config").val()+"}");
		page		= conf.page;
		searching 	= $("#searching").val();
		filter		= $("#filter").val();	
		item_type	= $("#item_type").val();	
		product_list= $("#product_list").val();
		if(item_type != "" || searching !=""){
			$("#dt_list").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
			$.ajax({
				url 	: product_list,
				type	: "POST",
				data 	: {"page":page,"direction":"search_produk","searching":searching,"filter":filter,"item_type":item_type}, 
				success : function(response){
					$("#dt_list").html(response);
				}
			})	
		}else{
			window.location.reload();	
		}
	});
	

	$("#ch-message-input").on("keyup",function(){
		var onwrite		= $("#onwrite").val();
		var id_customer	= $("#id_customer").val();
		var isi 		= $(this).val();
		if(isi != ""){
			if(onwrite == ""){ 
				tulcom.publish("/write_chat_customer_"+id_customer,{"name":""+user_name+"","flag":"2"});
				$("#onwrite").val("2");	
			}
		}
		if(isi == ""){
			tulcom.publish("/write_chat_customer_"+id_customer,{"name":""+user_name+"","flag":"1"});
			$("#onwrite").val("");	
		}
	})
	
	
});

function show_chat(id_customer){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	var chat_data	= $("#chat_list").val();
	$(".ch-messages-loader").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
	$.ajax({
		url 	: chat_data,
		type	: "POST",
		data 	: {"direction":"show_chat","id_customer":id_customer}, 
		success : function(response){
			$(".chat-list").html(response);
			$("#id_customer").val(id_customer);
			$(".ch-messages-loader").empty();
			$(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
		}
	})		
}

function remove_next(src,direction,id){
	proses_page = $("#proses_page").val();
	$.ajax({
		url 	: proses_page,
		type	: "POST",
		data 	: {"direction":direction,"id":id}, 
		success : function(response){
			if(src == "subject"){ $("#subject_"+id).fadeOut(200); }
			else				{ $("#chat_"+id).fadeOut(200); }
		}
	})	
}
function remove_chat(src,id){
	if(src == "subject"){
		id_subject = $("#id_subject").val();
		bootbox.confirm("Anda yakin menghapus subjek pesan ini? karena akan menghapus isi percakapan sebelumnya",function(confirmed){
			if(confirmed == true){
				remove_next(src,"remove_subject",id);
				if(id_subject == id){
					$("#ch-messages").slideUp(200);	
				}
			}
		})
	}else{
		bootbox.confirm("Anda yakin menghapus percakapan ini",function(confirmed){
			if(confirmed == true){
				remove_next(src,"remove_chat",id);
			}
		})
	}
}
//}



