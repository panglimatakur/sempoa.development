$(document).ready(function(){	
	 
	$("#ch-topic-send").on("click",function() {
		var messageInput = $('.ch-topic-input');
		var messageVal = messageInput.val();
		var messageVal = messageVal.replace(/^\s+/, '').replace(/\s+$/, '');
		if( messageVal != '' ) {
  			var conf 		= JSON.parse("{"+$("#config").val()+"}");
			chat_list 		= $("#topic_list").val();
			var info 		= JSON.parse("{"+$("#sender_info").val()+"}");
			user_photo		= info.user_photo; 
			user_name		= info.user_name; 
			wkt_chat		= info["wkt_chat"];
			tgl_chat		= info.tgl_chat;
			$("#topic_load").html("<div style='text-align:center; margin:6px 0 0 0'><img src='files/images/loading-bars.gif'></div>");
			$.ajax({
				url 	: chat_list,
				type	: "POST",
				data 	: {"pesan":messageVal}, 
				success : function(response){
					
					result = JSON.parse(response);
					if(conf.realtime == 1){
						container   	= '{"src":"USER","tipe":"public","id_subject":"'+result.id_topic+'","cidkey":"'+conf.cidkey+'","uidkey":"'+conf.uidkey+'","user_photo":"'+user_photo+'","user_name":"'+user_name+'","wkt_chat":"'+wkt_chat+'","tgl_chat":"'+tgl_chat+'"}';
						pushit("/subject_merchant_"+id_merchant,messageVal,container);
						pushit("/note_sound_merchant","subject_merchant","1");
					}
					$("#topic_load").html("");
					$('.ch-topic').prepend(result.content);
					messageInput.val('');
					last_subject=$("#id_subject").val();
					if(last_subject == "" || last_subject == 0){
						$("#id_subject").val(result.id_topic);	
						show_chat(result.id_topic);
					}
				}
			})	
		} else {
			$('.ch-topic-input').closest('.control-group').addClass('error');
		}
	})
	$("#ch-message-send").on("click",function(){
		var messageInput 	= $('.ch-message-input');
		var id_merchant 	= $("#ch-message-send").val();
		var id_customer		= $("#id_customer").val();
		var messageVal 		= messageInput.val();
		var messageVal 		= messageVal.replace(/^\s+/, '').replace(/\s+$/, '');
		if(messageVal != ''){
  			var conf 		= JSON.parse("{"+$("#config").val()+"}");
			chat_list 		= $("#chat_list").val();
			id_subject		= $("#id_subject").val();
			$("#chat_load").html("<img src='"+conf.dirhost+"/files/images/loading-bars.gif'><br>");
			var info 		= JSON.parse("{"+$("#sender_info").val()+"}");
			user_photo		= info.user_photo; 
			user_name		= info.user_name; 
			wkt_chat		= info.wkt_chat;
			tgl_chat		= info.tgl_chat;
			$.ajax({
				url 	: chat_list,
				type	: "POST",
				data 	: {"direction":"send_chat","id_topic":id_subject,"username":user_name,"id_customer":id_customer,"pesan":messageVal}, 
				success : function(response){
					result = JSON.parse(response);
					$("#chat_load").empty();
					if(conf.realtime == 1){ 
						container   	= '{"src":"USER","id_client":"'+id_merchant+'","id_chat":"'+result.id_chat+'","uidkey":"'+conf.uidkey+'","user_photo":"'+user_photo+'","user_name":"'+user_name+'","wkt_chat":"'+wkt_chat+'","tgl_chat":"'+tgl_chat+'"}';	
						pushit("/chat_merchant_"+id_subject,messageVal,container);
						pushit("/note_sound_merchant","chat_merchant","1");
					}
					$('.ch-messages').append(result.content);
					messageInput.val('');
					$('.ch-messages-added').attr('id','').removeClass('ch-messages-added').show();
					$(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
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
});

function show_chat(id_topic,id_customer){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	var chat_data	= $("#chat_list").val();
	$("#ch-messages").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
	$.ajax({
		url 	: chat_data,
		type	: "POST",
		data 	: {"direction":"show_chat","id_topic":id_topic}, 
		success : function(response){
			$("#ch-messages").html(response);
			$("#id_subject").val(id_topic);
			$("#id_customer").val(id_customer);
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

//function onWrite(id_cust){
$("#ch-message-input").on("keyup",function(){
	id_cust			= $(this).attr("data-id");
	var onwrite		= $("#onwrite").val();
	var info 		= JSON.parse("{"+$("#sender_info").val()+"}");
	user_name		= info.user_name; 
	var id_subject	= $("#id_subject").val();
	var isi 		= $("#ch-message-input").val();
	container   	= '{"id_cust":"'+id_cust+'","name":"'+user_name+'"}';
	if(isi != ""){
		if(onwrite == ""){ 
			onwrite = "2"; pushit("/write_merchant_"+id_subject,onwrite,container); 
		}
		$("#onwrite").val("2");	
	}
	if(isi == "" && onwrite == "2"){
		onwrite = "1";
		pushit("/write_merchant_"+id_subject,onwrite,container);
		$("#onwrite").val("");	
	}
})
//}



