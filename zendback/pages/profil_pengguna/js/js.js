var data 		= $("#data_page").val();
var proses_page = $("#proses_page").val();

$(document).ready(function(){
	$("#propinsi").on("change",function(){
		propinsi 	= $(this).val();
		if(propinsi != ""){
			$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
			$.ajax({
				url		: data,
				type	: "POST",
				data	: {"direction":"get_kota","propinsi":propinsi},
				success	: function(response){
					$("#div_kota").html(response);
					$("#div_name").html("Nama Kota/Kabupaten");
				}
			})
		}else{
			$("#div_kota").html("");
			$("#div_kecamatan").html("");
		}
	})	
	
	
	$('.delete_file').on('click',function(){
		id_doc 			= $(this).attr("data-info");
		bootbox.confirm("Anda yakin menghapus file ini?",function(confirmed){
			if(confirmed == true){
				$.ajax({
					url		: proses_page,
					type	: "POST",
					data 	: {"direction":"delete_file","no":id_doc},
					success	: function(response){
						$("#list_"+id_doc).fadeOut(500);	
					}
				})
			}
		})
	})
})


function get_kecamatan(el){
	kota 		= $(el).val();
	if(kota != ""){
		$("#div_kecamatan").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_kecamatan","kota":kota},
			success	: function(response){
				$("#div_kecamatan").html(response);
				$("#div_name").html("Nama Kecamatan");
			}
		})
	}else{
		$("#div_kecamatan").html("");
	}
}

function get_kelurahan(el){
	kecamatan 	= $(el).val();
	if(kecamatan != ""){
		$("#div_kelurahan").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_kelurahan","kecamatan":kecamatan},
			success	: function(response){
				$("#div_kelurahan").html(response);
				$("#div_name").html("Nama Kelurahan");
			}
		})
	}else{
		$("#div_kelurahan").html("");
	}
}

$("#kelurahan").on("change",function(){
	kelurahan 	= $(this).val();
	if(kelurahan != ""){
		$("#div_label").hide();
	}else{
		$("#div_label").show();
	}
})	

function cancel_option(id){
	e 			 = 0;
	count 		 = $(".option").length;
	$("#pilihan_"+id).remove();
	$("#ans_content .elm").each(function(){
		thisis 		= $(this).attr("id");
		data_value 	= $(this).attr("data-value");
		r 			= e+1;
		btn_id 		= $("#"+thisis+" .btn-primary").attr("id");
		if(btn_id == "cancel_doc"){
			$("#"+thisis+" .btn-primary").attr({"onclick":"cancel_option("+r+")"}).val(r);
		}else{
			$("#"+thisis+" .btn-primary").attr({"onclick":"add_document("+r+")"}).val(r);
		}
		$("#"+thisis+" input[type=file]").attr("id","doc_file_"+r);
		$("#"+thisis+" .btn-get-file").val(r);
		$("#"+thisis+" .option").html("Dokumen "+r);
		$("#"+thisis).attr("id","pilihan_"+r);
		e++;
	})
}
function download_document(id_doc){
	url 	= $("#download_page").val();
	$("#download").attr("src",url+"?id_doc="+id_doc);
}
function add_document(id){
	count 		 = $(".option").length;
	new_count	 = +count + 1;
	$("#pilihan_"+id+" .btn-primary").html('<i class="fa fa-minus"></i> Batal').attr({"id":"cancel_doc","onclick":"cancel_option("+id+")"}).val(id);
	input_answer = '<div class="form-group elm" id="pilihan_'+new_count+'">'+
						'<label class="option">Dokumen '+new_count+'</label>'+
						'<div class="input-group">'+
							'<button class="btn btn-sm btn-white btn-get-file" type="button" value="'+new_count+'" onclick="get_file(\''+new_count+'\')">'+
								'<i class="icsw16-books"></i> Unggah Dokumen'+
							'</button>'+
							'<input type="file" name="document[]" style="display:none" id="doc_file_'+new_count+'">'+
							'<span class="input-group-btn">'+
								'<button type="button" class="btn btn-sm btn-primary" onclick="add_document(\''+new_count+'\')" id="add_doc">'+
									'<i class="fa fa-plus"></i> Tambah'+
								'</button>'+
							'</span>'+
						'</div>'+
					'</div>';
	$(".elm:last").after(input_answer);
}
function get_file(id_browser){
	$("#doc_file_"+id_browser).click();	
}
