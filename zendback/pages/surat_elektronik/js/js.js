var files		= new Array();
var formdata 	= false;
if (window.FormData) {
	formdata = new FormData();
}

function remove_clip(id){
	$("#file_"+id).remove();
	files.splice(id,1);
	var r 	= 0;
	var r2 	= 0;
	$(".clip_delete").each(function(){
		r++;
		$(this).attr("onclick","remove_clip('"+r+"')");
	})
	$(".attach").each(function(){
		r2++;
		$(this).attr("id","file_"+r2+"");
	})
}

$(document).ready(function(){
	$("#compose").on("click",function(){
		$("#form_letter").slideDown(300);
		$("#subject_list").slideUp(300);
	})
	$("#close_letter").on("click",function(){
		$("#form_letter").slideUp(300);
		$("#subject_list").slideDown(300);
	})
	if($(".ch-messages").length > 0){
		$(".ch-messages").animate({ scrollTop: $(".ch-messages")[0].scrollHeight }, 600);	
	}
	if($('#kepada').length) {
		$("#kepada").select2({
			placeholder: "Add tags"
		});
	}
	/*UPLOAD FILE*/		
	var input 		= document.getElementById("attachment");
 	$("#attachment").on("change", function () {
 		var i 		= 0;
		var j 		= $(".attach").length + 1;
		var len 	= this.files.length; 
		var img;
		var reader;
		var file;
		for (;i < len; i++ ) {
			file = this.files[i];
			if ( window.FileReader ) {
				reader = new FileReader();
				reader.onloadend = function (e) { 
					var list 	= document.getElementById("fileList"),
						content = 	"<div class='alert alert-info attach' id='file_"+j+"' style='padding-right:3px'>"+
										"<i class='icsw16-paperclip'></i>"+
										file.name
										+"<i class='icsw16-trashcan clip_delete' onclick='remove_clip(\""+j+"\")'></i>"+
									"</div>";
					$("#fileList").before(content);
				};
				reader.readAsDataURL(file);
			}
			if (formdata) {
				files.push(file);
			}
		}
	});
	$("#ch-message-send").on("click",function(){
		proses		= $("#proses_page").val();
		$.each(files, function(key, file){
			formdata.append("attachment[]", file);
		});
		direction	= $("#direction").val();
		formdata.append("direction", direction);
		kepada		= $("#kepada").val();
		formdata.append("kepada", kepada.toString());
		subjek		= $("#subjek").val();
		formdata.append("subjek", subjek.toString());	
		id_topic	= $("#id_topic").val();
		formdata.append("id_topic", id_topic);	
		user_name	= $("#user_name").val();
		formdata.append("user_name", user_name);	
		pesan		= $(".cke_wysiwyg_frame").contents().find("body").html();
		formdata.append("pesan", pesan);
			
		text_pesan	= $(".cke_wysiwyg_frame").contents().find("body").text();
		if (kepada != "" && subjek != "" && text_pesan != "" && formdata) {
			$("#chat_load").html("<img src='files/images/loading-bars.gif' style='margin:2px 0 2px 2px'>");
			$.ajax({
				url: proses,
				type: "POST",
				data: formdata,
				processData: false,
				contentType: false,
				success: function(res){
					var conf 			= JSON.parse("{"+$("#config").val()+"}");
					if(conf.realtime == 1){
							container   = '{"id_client":"'+conf.id_client+'","uidkey":"'+conf.uidkey+'"}';
							pushit("/inbox",1,container);
					}
					messageVal		= $("#ch-message-input").val();
					$(".attach").remove();
					$(".cke_wysiwyg_frame").contents().find("body").empty();
					if(direction == "reply"){
						$("#chat_load").html(res); 
						$(".ch-messages").animate({ scrollTop: $(".ch-messages")[0].scrollHeight }, 600);	
					}else{
						location.href = "?page=pesan&outcome=1&msg=1";
					}
				}
			});
		}else{
			if(subjek == "")		{ bootbox.alert("Tentukan subjek surat"); 	}
			if(kepada == "")		{ bootbox.alert("Tentukan tujuan surat"); 	}
			if(text_pesan == "") 	{ bootbox.alert("Tuliskan pesan"); 			}
		}	
	})
	$("#open_file").on("click",function(){
		$("#attachment").trigger("click");
	})
    sempoa_wysiwg.init();
});

sempoa_wysiwg = {
	init: function() {
		if($('#ch-message-input').length) { 
			CKEDITOR.replace('ch-message-input', {
				toolbar: 'Standard'
			});
		}    
	}
};
function removal(id){
	bootbox.confirm("Anda Yakin Menghapus Data Polling Ini?",function(confirmed){
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

