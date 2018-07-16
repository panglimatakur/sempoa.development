<?php defined('mainload') or die('Restricted Access'); ?>
<script language="javascript">

	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	id_merchant		= conf.cidkey;
	if($(".ch-messages").length > 0){
		$(".ch-messages").animate({ scrollTop: $(".ch-messages")[0].scrollHeight }, 600);	
	}
	tulcom.subscribe("/online_customer_chat", function(data) {
    	id_customer_sender	= data.id_customer;
		if(data.status == "online"){
			label = '<span class="pull-right label label-primary">Online</span>'
		}else{
			label = '<span class="pull-right label label-danger">Offline</span>'
		}
		$("#indicator_"+id_customer_sender).html(label);
	});
	
	tulcom.subscribe("/to_chat_merchant_"+id_merchant, function(datas) {
		
		data 				= JSON.parse(datas);
		id_customer_sender	= data.id_customer;
		message 			= data.msg;
		
		id_customer_page 	= $("#id_customer").val();		
		
		if(message != "" && (id_customer_sender == id_customer_page)){
			cust_photo		= $("#chat_target .target img").attr("src");
			cust_name		= $("#chat_target .target #chat-user-name").html();
	
			div				= "";
			last_data_id_sender = $("[data-id-sender]:last").attr("data-id-sender");
			if(last_data_id_sender == id_customer_page){ div = "<div></div>";}
			
			response 	= 
			div+
			'<div class="chat-message" data-id-sender="'+id_customer_sender+'">'+
				'<div class="message-avatar" style="width:50px;height:50px; overflow:hidden">'+
					'<img src="'+cust_photo+'">'+
				'</div>'+
				'<div class="message">'+
					'<a class="message-author" href="javascript:void();">'+
						cust_name+
					'</a>'+
					'<span class="message-date">'+data.time+'</span>'+
					'<span class="message-content">'+
					message+
					'</span>'+
				'</div>'+
			 '</div>';
			$('[data-id-sender]:last').after(response);
			
			$("#onwrite").val("");
			$("#write_status").empty();
			$("#alert_"+id_customer_sender).remove();
			$("#subject_"+id_customer_sender).removeClass("ch-topic-item").addClass("ch-topic-item-new");
			
			$(".chat-discussion").animate({scrollTop: $(".chat-discussion")[0].scrollHeight }, 600);
			
		}
	});
	tulcom.subscribe("/write_chat_merchant_"+id_merchant, function(data) {
		id_customer_sender	= data.id_customer;		
		id_customer_page 	= $("#id_customer").val();		
		if(id_customer_sender == id_customer_page){
			if(data.flag == "2"){
				$("#write_status").html("&nbsp;"+data.name+" sedang menulis pesan....<br>");
			}else{
				$("#write_status").empty();
			}
		}
	});
	
	
	tulcom.subscribe("/update_subject_merchant_"+id_merchant, function(datas) {
		data 	= JSON.parse(datas);
		id_customer = data.id_customer;
		$("#subject_"+id_customer).clone(true).insertBefore(".chat-user:first");
		$("#subject_"+id_customer).each(function () {
			var ids = $('[id=' + this.id + ']');
			if (ids.length > 1 && ids[0] == this) {
				$(ids[1]).remove();
			}
		});
		$('.chat-users').animate({scrollTop: 0}, 'fast',function(){
			$("#id_customer").val(id_customer);
			$("#subject_"+id_customer).removeClass("ch-item").addClass("ch-item-new").fadeTo('slow', 0.5).fadeTo('slow', 1.0);
		});
		chat_ring("light");
	});
	
</script>