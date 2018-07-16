<?php defined('mainload') or die('Restricted Access'); ?>
<script language="javascript">

	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	id_merchant		= conf.cidkey;
	if($(".ch-messages").length > 0){
		$(".ch-messages").animate({ scrollTop: $(".ch-messages")[0].scrollHeight }, 600);	
	}
	<?php if(!empty($last_customer)){?> 
		tulcom.subscribe("/to_chat_merchant_<?php echo $last_customer ?>", function(datas) {
			data 			= JSON.parse(datas);
			id_customer		= data.id_customer;
			message 		= data.msg; 
			<?php
				$q_user_subject		= $db->query("SELECT CUSTOMER_NAME,CUSTOMER_PHOTO FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$last_customer."'"); 
				$dt_user_subject	= $db->fetchNextObject($q_user_subject);
				@$user_foto_subject	= $dt_user_subject->CUSTOMER_PHOTO;
				@$user_name_subject	= $dt_user_subject->CUSTOMER_NAME;
			?>
			if(message != ""){
				response 	= 
				"<div class='ch-message-item clearfix' id='chat_"+id_customer+"'>"+
					"<div class='img-box'><img src='<?php echo $dirhost; ?>/files/images/members/<?php echo $user_foto_subject; ?>' style='width:50px'></div>"
					+"<div class='ch-content'>"
						+"<p class='ch-name'>"
							+"<strong><?php echo $user_name_subject; ?></strong>"
							+"<span class='ch-time'>"+data.date+" "+data.time+"</span>"
						+"</p>"
						+message+
						"<br>"+
					"</div>"+
				"</div>";
				$("#onwrite").val("");
				$("#alert_"+id_customer).remove();
				$("#subject_"+id_customer).removeClass("ch-topic-item").addClass("ch-topic-item-new");
				$('.ch-message-item:last').after(response);
				$(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
				if($("#cust_"+id_customer).length > 0){ $("#cust_"+id_customer).remove(); }
				
			}
		});
		tulcom.subscribe("/write_chat_merchant_<?php echo $last_customer; ?>", function(data) {
			if(data.flag == "2"){
				$("#write_status").html("&nbsp;"+data.name+" sedang menulis pesan....<br>");
			}else{
				$("#write_status").empty();
			}
		});
	<?php } ?>
	
	
	
	
	tulcom.subscribe("/update_subject_merchant_<?php echo $_SESSION['cidkey']; ?>", function(datas) {
		data 	= JSON.parse(datas);
		id_customer = data.id_customer;
		$("#subject_"+id_customer).clone(true).insertBefore(".cl_subject:first");
		$("#subject_"+id_customer).each(function () {
			var ids = $('[id=' + this.id + ']');
			if (ids.length > 1 && ids[0] == this) {
				$(ids[1]).remove();
			}
		});
		$('.ch-topic').animate({scrollTop: 0}, 'fast',function(){
			$("#subject_"+id_customer).fadeTo('slow', 0.5).fadeTo('slow', 1.0);
			$("#time_"+data.id_customer).html(data.time);
			$("#msg_"+data.id_customer).html(data.msg);
		});
	});
	
	tulcom.subscribe("/note_chat_merchant_<?php echo $_SESSION['cidkey']; ?>", function(message) {
		 var conf 		= JSON.parse("{"+$("#config").val()+"}");
		 $('<audio id="chatAudios">'+
			'<source src="'+conf.dirhost+'/files/audio/ting.ogg" type="audio/ogg">'+
			'<source src="'+conf.dirhost+'/files/audio/ting.mp3" type="audio/mpeg">'+
			'<source src="'+conf.dirhost+'/files/audio/ting.wav" type="audio/wav">'+
		'</audio>').appendTo('body');
		$('#chatAudios')[0].play();
	});
	
	
	
	
	
	
	
</script>