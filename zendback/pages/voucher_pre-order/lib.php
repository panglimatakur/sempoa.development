<?php defined('mainload') or die('Restricted Access'); ?>
<?php $num_month = $dtime->daysamonth(date('m'),date('Y')); ?>
<script language="javascript">
tulcom.subscribe("/chat_topic", function(message) {
	var conf = JSON.parse("{"+$("#config").val()+"}");
	var container 	= JSON.parse(message.nick);
	user_id			= container.uidkey; 
	if(message.msg != "" && (container.src == "USER" && conf.uidkey != user_id)){
		user_photo	= container.user_photo; 
		user_name	= container.user_name; 
		wkt_chat	= container.wkt_chat;
		response 	= 
		"<div class='ch-topic-item clearfix'>"+
			"<img src='"+user_photo+"' class='ch-image img-avatar' >"
			+"<div class='ch-content'>"
				+"<p class='ch-name'>"
					+"<strong>"+user_name+"</strong>"
					+"<span class='ch-time'>"+wkt_chat+"</span>"
				+"</p>"
				+message.msg
			+"</div>"
		+"</div>";
		$('.ch-topic').prepend(response);
	}
});
tulcom.subscribe("/chat", function(message) {
	var conf = JSON.parse("{"+$("#config").val()+"}");
	var container 	= JSON.parse(message.nick);
	user_id			= container.uidkey; 
	if(message.msg != "" && (container.src == "USER" && conf.uidkey != user_id)){
		user_photo	= container.user_photo; 
		user_name	= container.user_name; 
		wkt_chat	= container.wkt_chat;
		response 	= 
		"<div class='ch-message-item clearfix'>"+
			"<img src='"+user_photo+"' class='ch-image img-avatar' >"
			+"<div class='ch-content'>"
				+"<p class='ch-name'>"
					+"<strong>"+user_name+"</strong>"
					+"<span class='ch-time'>"+wkt_chat+"</span>"
				+"</p>"
				+message.msg
			+"</div>"
		+"</div>";
		$('#ch-messages').append(response);
		$("#ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
	}
});


tulcom.subscribe("/note_sound_user", function(message) {
	 var conf 		= JSON.parse("{"+$("#config").val()+"}");
	 $('<audio id="chatAudios">'+
		'<source src="'+conf.dirhost+'/files/audio/ting.ogg" type="audio/ogg">'+
		'<source src="'+conf.dirhost+'/files/audio/ting.mp3" type="audio/mpeg">'+
		'<source src="'+conf.dirhost+'/files/audio/ting.wav" type="audio/wav">'+
	'</audio>').appendTo('body');
	$('#chatAudios')[0].play();
});

</script>
