function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			img_name = e.target.result;
			img_list = '<div id="img_list">'+
						'<table>'+
							'<tr>'+
								'<td>'+
									'<img src="'+img_name+'" class="thumbnail" style="width:36px;height:36px;">'+
									'<br><b>36x36px</b>'+
								'</td>'+
								'<td>'+
									'<img src="'+img_name+'" class="thumbnail" style="width:48px;height:48px;>'+
									'<br><b>48x48px</b>'+
								'</td>'+
								'<td>'+
									'<img src="'+img_name+'" class="thumbnail" style="width:72px;height:72px;">'+
									'<br><b>72x72px</b>'+
								'</td>'+
								'<td>'+
									'<img src="'+img_name+'" class="thumbnail" style="width:96px;height:96px;">'+
									'<br><b>96x96px</b>'+
								'</td>'+
								'<td>'+
									'<img src="'+img_name+'" class="thumbnail" style="width:144px;height:144px;">'+
									'<br><b>144x144px</b>'+
								'</td>'+
								'<td>'+
									'<img src="'+img_name+'" class="thumbnail" style="width:192px;height:192px;">'+
									'<br><b>192x192px</b>'+
								'</td>'+
							'</tr>'+
						'</table >'+
						'<button type="submit" class="btn btn-block btn-sempoa-1 btn-upload" name="build" value="build" id="save_application" onclick="build_apps()">'+
							'<i class="fa fa-upload"></i> Bangun Aplikasi'+
						'</button>'+
						'</div>';
			$('.pev_logo').html(img_list);
		};

		reader.readAsDataURL(input.files[0]);
	}
}
function first_status(){
	$("#count_status").empty();
	$("#detail").show();
	$(".buildiframe").nicescroll();
	$("#df").html("2%");
	$("#process").css("width","2%");
}
function build_apps(){ 
	$("#ouput").submit();
	/*$("#builder_container").block({ 
									message: "Proses perancangan aplikasi sedang berlangsung, silahkan menunggu sebentar..",
									css: {border:'none',padding:"10px",
										  '-webkit-border-radius':'10px',
										  '-moz-border-radius':'10px'} 
								  });*/
	$("#count_status").html("<div class='count_status'>Mengunduh kerangka kerja aplikasi Discoin</div>");
	$(".progress").show();
	$("#df").html("1%");
	$("#process").css("width","1%");
}
function set_bar(e){
	$("#df").html(e+"%");
	$("#process").css("width",e+"%");
}
function builder_finish(){
	$("#builder_container").unblock();
	$(".progress").hide();
	$("#detail").slideUp();
	bootbox.alert("Aplikasi Discoin berhasil di bangun",function(){
		//location.reload();
	});
	
}
$(document).ready(function(){	
	$("#logo_aplikasi").on("change",function(e){
		readURL(this)
		e.preventDefault();
	})
	
	$(".st_addons").bootstrapSwitch({
		on: 'Ya',
		off: 'Tidak',
		size: 'sm',
		onClass: 'primary',
		offClass: 'default'
	}).on("change",function(){
		proses_page	= $("#proses_page").val();
		data_id 	= $(this).attr("data-id"); 
		check_state = $(this).prop("checked");
		
		if(check_state == false){ status = "1"; }
		else					{ status = "3"; }
		$.ajax({
			url		: proses_page,
			type	: "POST",
			data 	: {"direction":"set_status","id_addon":data_id,"status":status},
			success	: function(response){}
		})
	});
})