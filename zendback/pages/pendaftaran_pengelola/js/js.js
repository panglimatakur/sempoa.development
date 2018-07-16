conf 		= JSON.parse("{"+$("#config").val()+"}");

function removal(id,src){
	bootbox.confirm("Dengan menghapus data pengguna ini, data-data yang sudah di daftarkan sebelumnya atas nama pengguna ini, akan kehilangan relasi informasi pengguna yang melakukan proses input data, Anda yakin menghapus data pengguna Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(response){
					if(src == "table"){
						$("#tr_"+id).fadeOut(500);
					}else{
						location.href = conf.dirhost+"/?page="+conf.page; 
					}
				}
			})
		}
	})
}

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


function send_starterpack(id_merchant){
	bootbox.confirm("Anda akan mengirimkan informasi starterpack merchant ini!!",function(confirmed){
		if(confirmed == true){
			$("#"+id_merchant).html("<img src='files/images/loading-bars.gif'>");
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"starterpack","no":id_merchant},
				success	: function(response){
					bootbox.alert(response);
				}
			})
		}
	})
}


$(document).ready(function(){	
	$("#productGroup").chosen({
		create_option: true,
		persistent_create_option: true,
		create_option_text: 'add',
	}).change(function() {
		choosen_val = $("#productGroup").chosen().val();
		$("#parent_id").val(choosen_val)
        
	});
	$("#id_client_form_op").chosen({
		create_option: true,
		persistent_create_option: true,
		create_option_text: 'add',
	}).change(function() {
		choosen_val = $("#id_client_form_op").chosen().val();
		$("#id_client_form").val(choosen_val)
	});
	
	$("#akses_off_1, #akses_off_2, #akses_off_3").bootstrapSwitch({
		on: 'Buka',
		off: 'Tutup',
		size: 'sm',
		onClass: 'primary',
		offClass: 'default'
	});
	
	$('#propinsi').on('change',function(){
		data 		= $("#data_page").val();
		propinsi 	= $(this).val();
		$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_city","propinsi":propinsi},
			success	: function(response){
				$("#div_kota").html(response);
			}
		})
	})
	
	$('.delete_file').on('click',function(){
		id_doc 			= $(this).attr("data-info");
		bootbox.confirm("Anda yakin menghapus file ini?",function(confirmed){
			if(confirmed == true){
				proses_page 	= $("#proses_page").val();
				$.ajax({
					url		: proses_page,
					type	: "POST",
					data 	: {"direction":"delete_file","no":id_doc},
					success	: function(){
						$("#list_"+id_doc).fadeOut(500);	
					}
				})
			}
		})
	})
	
});

function lastPostFunc(){ 
	data_page 	= $("#data_page").val();
	id_client_form	= $("#id_client_form").val();
	conf 		= JSON.parse("{"+$("#config").val()+"}");
	page		= conf.page;
	lastId		= $(".wrdLatest:last").attr("data-info");
	parameters 	= $("#form_report").serialize();
	parameters	= parameters+"&page="+page+"&lastID="+lastId+"&id_client_form="+id_client_form+"&direction=list_report";
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: parameters,
		success : function(data){
			if (data != "") {
				$(".wrdLatest:last").after(data)				
			}
			$('div#lastPostsLoader').empty();
		}
	});
};  
