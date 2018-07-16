$(document).ready(function(){
	$("#destiny").on("change",function(){
		$("#participants").empty();
		$("#div_destiny").empty();
		$("#search").val("");
		data_page = $("#data_page").val();
		destiny 	= $(this).val();
		if(destiny != "umum"){
			$("#id_search").fadeIn(300)
			$("#search").attr("placeholder","CARI NAMA "+destiny.toUpperCase());
		}else{
			$("#id_search").fadeOut(300);
		}
	})
	
	$("#btn_search").on("click",function(){
		seek 	= $("#search").val();
		destiny = $("#destiny").val();
		if(seek != ""){
			$("#div_destiny").html("<br><img src='files/images/loading-bars.gif' style='margin-left:7px'>");
			$.ajax({
				url		: data_page,
				type	: "POST",
				data 	: {"show":"destiny","destiny":destiny,"search":seek},
				success	: function(response){
					$("#div_destiny").html("<br clear='all'>"+response);
				}
			})
		}else{
			$("#div_destiny").empty();
		}
	})
	$("#open_post").on("click",function(){
		$("#n_wysiwg").fadeIn(200);
		$("#n_wysiwg button").val("insert");
		$("#n_wysiwg input").val("")
	}) 
	
	$("#del_pic").on("click",function(){
		var id			= $(this).val();
		bootbox.confirm("Anda Yakin Menghapus Cover Tulisan Ini?",function(confirmed){
			if(confirmed == true){
				proses_page = $("#proses_page").val();
				$.ajax({
					url		: proses_page,
					type	: "POST",
					data 	: {"direction":"delete_pic","no":id},
					success	: function(response){
						$("#pic_fr").fadeOut(500);	
					}
				})
			}
		})
	 })
	 
     //sempoa_wysiwg.init();
	 if($("#id_post_request").val() != ""){
		 var id_post = $("#id_post_request").val();
		 view_post(id_post);
	 }
});

sempoa_wysiwg = {
	init: function() {
		if($('#question').length) { 
			CKEDITOR.replace( 'question', {
				toolbar: 'Standard'
			});
		}    
	}
};

function removal(id){
	bootbox.confirm("Anda Yakin Menghapus Tulisan Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(){
					$("#tr_"+id).fadeOut(500);	
				}
			})
		}
	})
}

function view_post(id_post){
	data_page = $("#data_page").val();
	$("#n_wysiwg").fadeOut(200);
	$("#n_wysiwg button").val("reply");
	$("#n_wysiwg input").val("")
	$("#post_container").slideUp(500);
	$("#post_detail_container").html("<div style='text-align:center; background:#FFF'><img src='files/images/loader.gif'></div>");
	var conf 	= JSON.parse("{"+$("#config").val()+"}");
	$.ajax({
		url		: data_page,
		type	: "POST",
		data 	: {"page":conf.page,"show":"data_reply","id_post":id_post},
		success	: function(response){
			$("#post_detail_container").html(response);
		}
	})
}

function open_reply(id_parent,id_post,subject){
	$("#n_wysiwg").fadeIn(200);
	$("#n_wysiwg #id_parent").val(id_parent);
	$("#n_wysiwg #id_post").val(id_post);
	$("#n_wysiwg #reply").val("true");
	$("#n_wysiwg #subject").val(subject);
	$("#div_dest").hide();
	$(".post_reply").animate({scrollTop: $(".post_reply")[0].scrollHeight }, 600);
}

function back(){
	$("#post_detail_container").empty();
	$("#n_wysiwg input").val("")
	$("#post_container").slideDown(200);	
}
function notify(msg,direction){
	if(direction == "redirect"){
		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		location.href = conf.dirhost+"?page=forum";
	}
	if(direction == ""){
		bootbox.alert(msg);	
	}
}
function notify_reply(msg,id_post,direction){
		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		data_page 		= $("#data_page").val();
		if(direction == "reply"){
			loader 	= "rloader";
			show	= "new_reply";	
		}
		if(direction == "insert"){
			$("#noPost").remove();
			loader 	= "ploader";
			show	= "data_post";	
		}
		$('#'+loader).html('<div style="text-align:center; padding:5px"><img src="'+conf.dirhost+'/files/images/loading-bars.gif"><br>Mengambil Data...</div>');
		$.ajax({
			url 	: data_page,
			type	: "POST",
			data	: {"show":show,"id_post":id_post},
			success : function(data){
				if(direction == "insert"){
					$("#listForum tbody tr:first").before(data);
				}else{
					$('#'+loader).before(data);
				}
				$('#'+loader).empty();
				$("#subject").val();
				$("#n_wysiwg").slideUp(200);				
				$('.cke_wysiwyg_frame').contents().find("html body").html("");
				$("#n_wysiwg input").val("");
				$("#participants").empty();
				$("#div_destiny").empty();
				$("#search").val("");

			}
		});
}

function lastReply(id_post){ 
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	data_page 		= $("#data_page").val();
	$('div#lastReplyLoader').html('<div style="text-align:center; margin:10px"><img src="'+conf.dirhost+'/files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"show":"data_reply","lastId":lastId,"id_post":id_post},
		success : function(data){
			if(data != ""){
				$('#replyFooter').remove();
				$("#lastReplyLoader").before(data);
			}
			$('div#lastReplyLoader').empty();
			
		}
	});
}

function lastPost(){ 
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	data_page 		= $("#data_page").val();
	$('div#lastPostLoader').html('<div style="text-align:center; margin:10px"><img src="'+conf.dirhost+'/files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastPost			= $(".wrdPostLatest:last").attr("data-info");
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"show":"data_post","lastPost":lastPost},
		success : function(data){
			if(data != ""){
				$('#postFooter').remove();
				$("#lastPostLoader").before(data);
			}
			$('div#lastPostLoader').empty();
			
		}
	});
}

function pick_this(tipe,id){
	if(tipe == "komunitas"){
		var values 	= $("#val_comm_"+id).val();
		values		= JSON.parse("{"+values+"}");
		content = 
		"<div class='col-md-4 dest_list comm_list' id='id_comm_"+id+"' style='margin:4px 4px 0 0;' onclick='cancel_this(\"komunitas\",\""+id+"\")'>"+
			"<b>"+values.nama+"</b>"+
			"<div style='float:right'><i class='icon-remove'></i></div>"+
			"<input type='hidden' name='comm[]' id='id_community_"+id+"' value='"+id+"'>"+
		"</div>";
		$("#id_comm_"+id).fadeOut(200);
	}
	if(tipe == "personal"){
		var values 	= $("#val_personal_"+id).val();
		values		= JSON.parse("{"+values+"}");
		content = 
		"<div class='col-md-4 dest_list user_list' id='id_per_"+id+"' style='margin:4px 4px 0 0;' onclick='cancel_this(\"komunitas\",\""+id+"\")'>"+
			"<b>"+values.nama+"</b><br />"+
			"<b class='code'>"+values.merchant+"</b>"+
			"<div style='float:right'><i class='icon-remove'></i></div>"+
			"<input type='hidden' name='user_person[]' id='id_personal_"+id+"' value='"+id+"'>"+
		"</div>";
		$("#id_user_"+id).fadeOut(200);
	}
	$("#participants").append(content);
}
function cancel_this(tipe,id){
	if(tipe == "komunitas"){
		$("#id_comm_"+id).remove();
		$("#id_comm_"+id).fadeIn(200);
	}
	if(tipe == "personal"){
		$("#id_per_"+id).remove();
		$("#id_user_"+id).fadeIn(200);
	}
}