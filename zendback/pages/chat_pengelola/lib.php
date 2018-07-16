<?php defined('mainload') or die('Restricted Access'); ?>
<script language="javascript">
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	id_merchant		= conf.cidkey;
	if($(".ch-messages").length > 0){
		$(".ch-messages").animate({ scrollTop: $(".ch-messages")[0].scrollHeight }, 600);	
	}
	tulcom.subscribe("/subject_merchant_"+id_merchant, function(message) {
		var container 	= JSON.parse(message.nick);
		user_id			= container.uidkey; 
		if((message.msg != "" && user_id != conf.uidkey && container.src == "USER") || 
		    (message.msg != "" && container.src == "CUSTOMER")
		   ){
			id_subject	= $.trim(container.id_subject); 
			if($("#subject_"+id_subject).length == 0){
				tipe		= container.tipe; 
				user_photo	= container.user_photo; 
				user_name	= container.user_name; 
				wkt_chat	= container.wkt_chat;
				tgl_chat	= container.tgl_chat;
				response 	= 
				"<div class='ch-topic-item-new clearfix' style='cursor:pointer;' id='subject_"+id_subject+"'>"+
					"<div class='img-box'><img src='"+user_photo+"' style='width:50px' ></div>"
					+"<div class='ch-content'>"
						+"<p class='ch-name'>"
							+"<strong>"+user_name+"</strong>"
							+"<span class='ch-time'>"+
								"<span class='"+tipe+"'>"+tipe+"</span> : "
								+wkt_chat+
							"</span>"
						+"</p>"
						+message.msg
						+"<br />"
						+"<small class='code'>"+tgl_chat+" : 0 Komentar</small>"+
						"<button class='btn btn-mini ptip_sw' onclick='show_chat(\""+id_subject+"\")' title='Pilih Subjek Pesan' style='float:right; margin-left:4px'>"+
							"<i class='icon-search'></i>"+
						"</button>"+
						"<button class='btn btn-mini removal' onclick='remove_chat(\"subject\",\""+id_subject+"\")' title='Hapus Subjek Pesan' >"+
							"<i class='icon-trash'></i>"+
						"</button>"
					+"</div>"
				+"</div>"; 
				$('.ch-topic').prepend(response);
				last_subject=$("#id_subject").val();
				if(last_subject == "" || last_subject == 0){
					$("#id_subject").val(id_subject);	
					show_chat(id_subject);
				}
			}
		}
	});
	tulcom.subscribe("/update_subject", function(message) {
		id_subject = message.msg;
		$("#subject_"+id_subject).clone(true).insertBefore(".cl_subject:first");
		$("#subject_"+id_subject).each(function () {
			var ids = $('[id=' + this.id + ']');
			if (ids.length > 1 && ids[0] == this) {
				$(ids[1]).remove();
			}
		});
		$('.ch-topic').animate({scrollTop: 0}, 'fast',function(){
			$("#subject_"+id_subject).fadeTo('slow', 0.5).fadeTo('slow', 1.0);
		});
	});
	tulcom.subscribe("/note_sound_merchant", function(message) {
		 var conf 		= JSON.parse("{"+$("#config").val()+"}");
		 $('<audio id="chatAudios">'+
			'<source src="'+conf.dirhost+'/files/audio/ting.ogg" type="audio/ogg">'+
			'<source src="'+conf.dirhost+'/files/audio/ting.mp3" type="audio/mpeg">'+
			'<source src="'+conf.dirhost+'/files/audio/ting.wav" type="audio/wav">'+
		'</audio>').appendTo('body');
		$('#chatAudios')[0].play();
	});
</script>