$(document).ready(function(){		
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
	
	if($('#akses_off_1').length) {
		$("#akses_off_1").iButton();
	}
	if($('#akses_off_2').length) {
		$("#akses_off_2").iButton();
	}
	if($('#akses_off_3').length) {
		$("#akses_off_3").iButton();
	}
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
	$('.download').on('click',function(){
		id_doc 	= $(this).attr("data-info");
		url 	= $("#download_page").val();
		$("#download").attr("src",url+"?id_doc="+id_doc);
	})
	
	 $("#add_more").on("click",function(){
		count 		 = $(".option").length;
		new_count	 = +count + 1;
		$(this).html('<i class="icon-minus"></i>Batal').attr({"id":"cancel_more","onclick":"cancel_option("+count+")"}).val(count);
	 	input_answer = '<div class="form-group elm" id="pilihan_'+new_count+'">'+
							'<label class="option">Dokumen '+new_count+'</label>'+
							'<input type="file" name="document[]">'+
							'<button type="button" class="btn" style="margin:-9px 0 0 3px" id="add_more">'+
								'<i class="icon-plus"></i>Tambah Pilihan'+
							'</button>'+
						'</div>';
		$(".elm:last").after(input_answer);
	 })
});
function getparent(id,target){
	add 	= $("#parent_page").val();
	$("#"+target).html("<img src='files/images/loading-bars.gif'>");
	$.get(add+"?parent_id="+id,function(response){
		 $("#"+target).html(response);
	});
	
}

function resetchild(){
	$("#divparent_id").html("");
	$("#newlink").html("");
}

function delete_link(id){
	bootbox.confirm("Anda yakin menghapus Link Ini? Karena juga akan menghapus data link anak di bawahnya",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(){
					$("#li_"+id).fadeOut(500);	
				}
			})
		}
	})
}

function cancel_option(id){
	e = 0;
	$("#pilihan_"+id).remove();
	 $("#ans_content .elm").each(function(){
		thisis = $(this).attr("id");
		r = e+1;
		$("#"+thisis+" .option").html("Pilihan "+r);
		$("#"+thisis+" button").attr({"id":"cancel_more","onclick":"cancel_option("+r+")"}).val(r);
		$("#"+thisis).attr("id","pilihan_"+r);
		e++;
	 })
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