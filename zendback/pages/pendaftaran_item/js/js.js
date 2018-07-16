$(document).ready(function(){
	if($('.status_view').length) {
		$(".status_view").bootstrapSwitch({
			on: 'Aktif',
			off: 'Tidak Aktif',
			size: 'sm',
			onClass: 'primary',
			offClass: 'default'
		});
	}
	$("#nama_kat").on("keyup",function(){
		nkat = $(this).val().length;
		if(nkat > 0){
			$("#div_form_link").slideDown(200);
		}else{
			$("#div_form_link").slideUp(200);
		}
	})
	$(".new_cat").on("click",function(){
		var id_type 	= $("#id_type").val();
		kategori_page 	= $("#kategori_page").val();
		$(".clist").remove();
		$.fancybox.open([{
			type	: 'ajax',
			href 	: kategori_page+"?display=master_kategori_item&id_type="+id_type,                
			title 	: 'Tambah Kategori Produk'
		}], 
		{padding : 10});
	})
	$("#kategori_type").on("change",function(){
		jenis = $(this).val();
		if(jenis == "anak"){
			$("#div_kategori_induk").html('<div style="text-align:center;"><img src="files/images/loading-bars.gif"></div>');
			var id_type 	= $("#id_type").val();
			kategori_page 	= $("#kategori_page").val();
			$.ajax({
				url 	: kategori_page,
				type 	: "POST",
				data 	: {"display":"kategori_proses","form_add":"true","id_type":id_type,"kategori_type":jenis},
				success : function(response){
					$("#div_kategori_induk").html(response);
				}
			});
		}else{
			$("#div_kategori_induk").empty();
		}
	})
	
	$("#new_category").on("click",function(){
		var id_type 		= $("#new_id_type").val();
		var parent_id		= $("#parent_id").val();
		var nama_kategori 	= $("#nama_kat").val();
		proses_page 		= $("#proses_page").val();
		$("#div_new_kat").html('<div class="form-group"><img src="files/images/loading-bars.gif"></div>');
		$.ajax({
			url 	: proses_page,
			type 	: "POST",
			data 	: {"direction":"add_new_category","parent_id":parent_id,"id_type":id_type,"nama_kategori":nama_kategori},
			success : function(response){
				result = JSON.parse(response);
				if(result['msg'] == "berhasil"){
					show_catlist(result['value']);
					$("#div_new_kat").empty();
					$.fancybox.close();
				}
			}
		});
	})
	$("#id_type_report").on("change",function(){
		data_page 	= $("#kategori_page").val();
		type		= $(this).val();
		if(type != ""){
			$("#div_kategori_report").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
			$.ajax({
				url		: data_page,
				type	: "POST",
				data 	: {"display":"kategori_report","id_type_report":type},
				success : function(response){
					$("#div_kategori_report").html(response);	
				}
			})
		}else{
			$("#div_kategori_report").html("");	
		}
	})
	$("#select_rows").on("click",function(){
		ch = $(this).is(":checked");
		if(ch == true){
			$(".row_sel").attr("checked","checked");
		}else{
			$(".row_sel").removeAttr("checked");	
		}
	})
	$("#select_rows_2").on("click",function(){
		ch = $("#select_rows").is(":checked");
		if(ch == true){
			$("#select_rows").removeAttr("checked");	
			$(".row_sel").removeAttr("checked");	
		}else{
			$("#select_rows").attr("checked","checked");
			$(".row_sel").attr("checked","checked");
		}
	})
	$("#delete_picked").on("click",function(){
		bootbox.confirm("Dengan menghapus data produk ini, data penjualan, pembelian dan distribusi yang sudah di daftarkan sebelumnya akan kehilangan relasi data produk/bahan ini, Anda yakin menghapus data produk Ini?",function(confirmed){
			if(confirmed == true){
				proses_page = $("#proses_page").val();
				$(".row_sel").each(function() {
				   ch 		= $(this).is(":checked");
				   ch_val 	= $(this).val();
				   if(ch == true){
					$.ajax({
						url		: proses_page,
						type	: "POST",
						data 	: {"direction":"delete","no":ch_val},
					})
					$("#tr_"+ch_val).fadeOut(500);	   
				   }
				});
			}
		})
	})	
	
	$(".open_file").on("click",function () {
		info = $(this).attr("data-info");
		$("#image_single_"+info).trigger("click");
	});	
	
	$(".harga_multi").on("keyup",function(){ 
		newval = $(this).val();
		newval = newval.replace(/[^0-9]/g,'');
		$(this).val(newval);
	})
})


function set_status(id){ 
	var proses_page 	= $("#proses_page").val();
	var value 		= $("#st_prod_"+id).val();
	if(value != ""){
		$("#st_prod_"+id).before('<div style="text-align:center;margin:10px" id="st_load_'+id+'"><img src="files/images/loading-bars.gif"></div>');
		$.ajax({
			url 	: proses_page,
			type	: "POST",
			data	: {"direction":"set_status","no":id,"id_status":value},
			success : function(data){
				$("#st_load_"+id).remove();
			}
		});
	}
}

function show_catlist(picked){
	var conf = JSON.parse("{"+$("#config").val()+"}");
	data_page 	= $("#kategori_page").val();
	type		= $("#id_type").val();
	$("#last_code").html("");
	if(type != ""){
		$("#div_kategori").html("<img src='files/images/loading-bars.gif' style='margin-left:2%'>");
		$.ajax({
			url		: data_page,
			type	: "POST",
			data 	: {"page":conf.page,"display":"kategori_proses","id_type":type,"picked":picked},
			success : function(response){
				$("#div_kategori").html(response);	

			}
		})
	}else{
		$("#div_kategori").html("");	
	}
}
function removal(id){
	bootbox.confirm("Dengan menghapus data produk ini, data penjualan, pembelian dan distribusi yang sudah di daftarkan sebelumnya akan kehilangan relasi data produk/bahan ini, Anda yakin menghapus data produk Ini?",function(confirmed){
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
function last_code(){
	proses_page = $("#proses_page").val();
	id_kategori = $("#id_kategori").val();
	$("#last_code").html("<img src='files/images/loading-bars.gif' style='margin-left:4px'>");
	$.ajax({
		url 	: proses_page,
		type 	: "POST",
		data	: {"direction":"check_code","id_kategori":id_kategori},
		success	: function(response){
			if(response != ""){
				$("#last_code").html("(<b>Code Terakhir : </b>"+response+")");
			}else{
				$("#last_code").html("");	
			}
		}
	})
}

function check_element(){
	if($(".product_content").length > 0){
		$("#button_container").show(500);
	}else{
		$("#button_container").hide(500);
	}	
}
function cancel_content(el,id_picture,direction){
	bootbox.confirm("Anda Yakin Menghapus Gambar Ini?",function(confirmed){
		if(confirmed == true){
			if(direction == "edit"){
				proses_page = $("#proses_page").val();
				$.ajax({
					url 	: proses_page,
					type 	: "POST",
					data	: {"direction":"delete_pic","id_picture":id_picture},
					success	: function(response){
						$("#product_"+el).fadeOut(500,function(){ $(this).remove(); })
					}
				})
			}else{
				$("#"+el).fadeOut(500,function(){ 
					$("#group_"+id_picture).val("");
				})
			}
			check_element();
		}
	})
}


function preview(input,direction) {
	if(direction == ""){ direction = "insert";  }
	
	if(direction == "insert")	{ col_class = "col-md-4"; }
	if(direction == "edit")		{ col_class = "";}
	
	var fileList 	= input.files;
    var anyWindow 	= window.URL || window.webkitURL;
	var satuan		= $("#satuan_tag").html();
	var code_rand	= $("#code_random").val();
	var a 			= 0;
	for(var i = 0; i < fileList.length; i++){
	 a++;
	 var counter 	= $("#counter").val();
	 var t 			= +counter+1;
	 jml_all = $(".product_content").length;
	 
	 if($("#product_"+a).length > 0){ a = +jml_all + 1; }
	 if($.trim(t.toString().length) == 1){ t = "0"+t; }
	  var new_counter = +counter+1;
	  var objectUrl = anyWindow.createObjectURL(fileList[i]);
	  input_content = 
	  '<div class="'+col_class+'" style="padding:3px">'+
		  '<div class="product_content tool_cont" id="product_'+a+'">'+

			'<div class="tools" style="text-align:center; width:95%;" >'+
				'<a href="javascript:void()" class="btn btn-sm btn-default" onclick="cancel_content(\'product_'+a+'\',\''+a+'\',\'\')">'+
					'<i class="icsw16-trashcan"></i>'+
				'</a>'+
			'</div>'+	
		
			'<div class="frame_photo">'+
				'<div class="thumbnail">'+
					'<div class="thumbnail_inner">'+
						'<img src="'+objectUrl+'" width="100%" class=" picker" id="picker_'+a+'"/>'+
					'</div>'+
				'</div>'+
			'</div>';
			if(direction != "edit"){
	input_content += 
					'<input type="hidden" name="group['+a+']" id="group_'+a+'" value="'+a+'">'+
					'<input type="text" name="code_arr[]" value="'+code_rand+'-'+t+'" class="form-control code validate[required] text-input mousetrap" placeholder="Kode*">'+
					'<input type="text" name="nama_arr[]" value="" class="form-control nama_multi validate[required] text-input mousetrap" placeholder="Nama*">'+
					'<textarea name="deskripsi_arr[]" class="form-control deskripsi_multi mousetrap" placeholder="Deskripsi"></textarea>'+
					'<input type="text" name="harga_arr[]" value="" class="form-control harga_multi mousetrap" placeholder="Harga">'+
					'<select name="satuan_arr[]" class="form-control satuan_multi mousetrap">'+
						satuan
					'</select>';
			}
	  input_content += 
		  '</div>'+
	  '</div>';
	  if(direction != "edit"){
	  	$('#preview_zone').prepend(input_content);
	  }else{
	  	$('#preview_zone').html(input_content);
	  }
	  window.URL.revokeObjectURL(fileList[a]);
	  $("#counter").val(new_counter);
	}
 	if(direction != "edit"){
		check_element();
		$("#elements").prepend("<input type='file' name='image[]' onchange='preview(this,\""+direction+"\")' multiple>");
		input.style.display = "none";
	}
}

function send_note(proses_page,id_session){
	var conf = JSON.parse("{"+$("#config").val()+"}");
	$.ajax({
		url		: proses_page,
		type	: "POST",
		data 	: {"direction":"send_notification","id_session":id_session},
		success : function(response){
			//$("#div_msg").html("<div style='padding:10px'>"+response+"</div>");	
			self.location.href = conf.dirhost+"/?page=pendaftaran_item&msg=1";
		}
	})
}

function lastPostFunc(){ 
	data_page 	= $("#data_page").val();
	conf 		= JSON.parse("{"+$("#config").val()+"}");
	page		= conf.page;
	lastId		= $(".wrdLatest:last").attr("data-info");
	
	id_type_report = $("#id_type_report").val();
	id_kategori = $("#id_kategori_report").val();
	code 		= $("#code_report").val();
	nama 		= $("#nama_report").val();
	deskripsi 	= $("#deskripsi_report").val();
	
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"page":page,
				   "lastID":lastId,
				   "id_type_report":id_type_report,
				   "id_kategori":id_kategori,
				   "code":code,
				   "nama":nama,
				   "deskripsi":deskripsi,
				   "display":"list_report"},
		success : function(data){
			if (data != "") {
				$(".wrdLatest:last").after(data)				
			}
			$('div#lastPostsLoader').empty();
		}
	});
};  

