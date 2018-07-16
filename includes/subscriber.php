<script type="text/javascript">
	tulcom.subscribe("/online_order_<?php echo $_SESSION['cidkey']; ?>", function(message) {
	  var conf 		= JSON.parse("{"+$("#config").val()+"}");
	  if(message.msg != ""){
		 $('<audio id="chatAudio">'+
			'<source src="files/audio/ting.ogg" type="audio/ogg">'+
			'<source src="files/audio/ting.mp3" type="audio/mpeg">'+
			'<source src="files/audio/ting.wav" type="audio/wav">'+
		'</audio>').appendTo('body');
		$('#chatAudio')[0].play();
		cur_sum = $("#badge_order").html();
		new_sum = +cur_sum + +message.msg;
		$('#badge_order').show().html(new_sum);
	  }
	});
	
	tulcom.subscribe("/laporan_penjualan", function(message) {
	  var conf 		= JSON.parse("{"+$("#config").val()+"}");
	  var container = JSON.parse(message.nick);
	  
	  if(message.msg != "" && (conf.id_client == container.id_client || conf.cidkey == container.cidkey)){
		 $('<audio id="chatAudio">'+
			'<source src="files/audio/ting.ogg" type="audio/ogg">'+
			'<source src="files/audio/ting.mp3" type="audio/mpeg">'+
			'<source src="files/audio/ting.wav" type="audio/wav">'+
		'</audio>').appendTo('body');
		$('#chatAudio')[0].play();
		cur_sum = $("#badge_penjualan").html();
		new_sum = +cur_sum + +message.msg;
		$('#badge_penjualan').show().html(new_sum);
	  }
	});
	
	tulcom.subscribe("/laporan_distribusi", function(message) {
	  var conf 		= JSON.parse("{"+$("#config").val()+"}");
	  var container = JSON.parse(message.nick);
	  if(message.msg != "" && (conf.cidkey == container.for_id)){
		 $('<audio id="chatAudio">'+
			'<source src="files/audio/tale.ogg" type="audio/ogg">'+
			'<source src="files/audio/tale.mp3" type="audio/mpeg">'+
			'<source src="files/audio/tale.wav" type="audio/wav">'+
		'</audio>').appendTo('body');
		$('#chatAudio')[0].play();
		cur_sum = $("#badge_distribusi").html();
		new_sum = +cur_sum + +message.msg;
		$('#badge_distribusi').show().html(new_sum);
	  }
	});
	
	tulcom.subscribe("/inbox", function(message) {
	  var conf 		= JSON.parse("{"+$("#config").val()+"}");
	  var container = JSON.parse(message.nick);
	  if(message.msg != "" && (conf.uidkey != container.uidkey)){
		 $('<audio id="chatAudio">'+
			'<source src="files/audio/tale.ogg" type="audio/ogg">'+
			'<source src="files/audio/tale.mp3" type="audio/mpeg">'+
			'<source src="files/audio/tale.wav" type="audio/wav">'+
		'</audio>').appendTo('body');
		$('#chatAudio')[0].play();
		cur_sum = $("#badge_pesan").html();
		new_sum = +cur_sum + +message.msg;
		$('#badge_pesan').show().html(new_sum);
	  }
	});
</script>