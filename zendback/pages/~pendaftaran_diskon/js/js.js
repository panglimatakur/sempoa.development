$(document).ready(function(){	
	$("#add_more").on("click",function(){
  		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		count 		 	= $(".option").length;
		new_count	 	= +count + 1;
		check_before 	= $("#besar"+count).val();
		
		if(check_before != ""){
			data_page  	= $("#data_page").val();
			$(".elm:last").after('<img src="'+conf.dirhost+'/files/images/loader.gif" style="margin:5px 0 5px 0" id="form_loader">');
			$.ajax({
				url 	: data_page,
				type	: "POST",
				data	: {"show":"get_form","new_count":new_count},
				success : function(input_answer){
					$(".elm:last").after(input_answer);
					$("#form_loader").remove();
				}
			})
		}else{
			bootbox.alert("Opps.., Diskon Reguler "+count+" belum di isi.");	
		}
		
	 })
	 
	$(".besar").on("keyup blur",function(){
		besar 		= $(this).attr("id");
		new_besar 	= numeric(document.getElementById(besar));
		$(besar).val(new_besar);
	})	
	
})

function jumlah_beli(el){
	jumlah_beli 	= $(el).attr("id");
	new_jumlah_beli = numeric(document.getElementById(jumlah_beli));
	$(jumlah_beli).val(new_jumlah_beli);
}

function open_min_order(id_box,is_check){
	//is_check = $("#pre_order_"+id_box).is(":checked");
	if(is_check == true){
		$("#label_order_"+id_box).html("<br><img src='files/images/loading.gif' >");
		data_page 	= $("#data_page").val();
		$.ajax({
			url 	: data_page,
			data 	: {"show":"order_quota","id_box":id_box},
			success	: function(response){
				$("#label_order_"+id_box).html(response);
			}
		})
	}else{
		$("#label_order_"+id_box).empty();
	}
}
function change_pattern(id,el){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	var data_page 	= $("#data_page").val();
	var pattern 	= $(el).val();
	if(pattern !=""){
		$("#div_pattern"+id).html("<br><img src='files/images/loading.gif' >");
		$.ajax({
			url 	: data_page,
			type	: "POST",
			data 	: {"show":"pattern","id_box":id,"pattern":pattern}, 
			success : function(response){
				$("#div_pattern"+id).html(response);
			}
		})
	}else{
		$("#div_pattern"+id).empty();	
	}
	$("#div_product"+id).hide();	
	$("#div_product"+id+" .prod_box").empty();

}
function open_product(id,el){
 	$("#div_product"+id).slideDown(200);
	var value 	  = $("#jumlah_beli"+id).val();
 	if(value == "few"){
		prod_location = $("#prod_location"+id).attr("data-direction");
		$.fancybox.open([{
			type	: 'ajax',
			href 	: prod_location,                
			title 	: 'Pilih Produk'
		}], 
		{padding : 0});
	}else{
	}
}
function pick(id_product,code,photo){
  var conf 	= JSON.parse("{"+$("#config").val()+"}");
  id_disc 	= $("#id_disc").val();
 
  ch_pilih = $("#div_product"+id_disc+" #pframe_"+id_product).length;
  if($("#div_product"+id_disc+" #pframe_"+id_product).length == 0){
	jml_frame	= $("#pilihan_"+id_disc+" .pframe").length;
	s			= +jml_frame+1;
	if(photo != ""){
		photo_container = "<img src='"+conf.dirhost+"/files/images/products/"+conf.id_client+"/thumbnails/"+photo+"' class='photo' style='height:65px'/>"; 
	}else{
		photo_container = "<img src='"+conf.dirhost+"/files/images/no_image.jpg' class='photo' style='height:65px'/>"; 
	}
	container = "<div class='pframe' id='pframe_"+id_product+"' style='margin-left:4px'>"+
					"<a href='javascript:void()' style='float:right' onclick='del_pic(\""+id_disc+"\",\""+id_product+"\",\"pilihan_"+id_disc+"\",\"pframe_"+id_product+"\")'>"+
						"<i class='icon-remove'></i>"+
					"</a>"+
					"<label><b><small class='code'>"+code+"</small></b></label>"
					+photo_container+
					"<input type='hidden' class='pic_h' id='id_product_"+id_disc+"' value='"+id_product+"'>"+
					"<br clear='all'>"+
				"</div>";
	
	$("#pic_"+id_disc).after(container);
	//$.fancybox.close();
	$("#tr_"+id_product).fadeOut(300);
  }else{
	$("#msg_"+id_product).html("<div class='alert alert-error'>Produk Ini sudah dipilih, silahkan pilih yang lain</div>");  
  }
}
function del_pic(id,id_product_disc,container,pic){
	bootbox.confirm("Anda Yakin Menghapus Foto Produk Ini?",function(confirmed){
		var proses_page = $("#proses_page").val();
		id_diskon	= $("#id_diskon"+id).val();
		if(confirmed == true){
			$.ajax({
				url 	: proses_page,
				type	: "POST",
				data 	: {"direction":"del_pic","id_diskon":id_diskon,"id_product_disc":id_product_disc}, 
				success : function(response){
					$("#"+container+" #"+pic).remove();
				}
			})	
		}
	})
}


function check_similarity(nilai){
	var done 			= 2;
	var besar 			= $("#besar"+nilai).val();	
	var satuan 			= $("#satuan"+nilai).val();
	var formember 		= $("#formember"+nilai).val();
	var pattern			= $("#pattern"+nilai).val();
	var jumlah_beli 	= $("#jumlah_beli"+nilai).val();
	
	if(formember != "customer")	{ target 	= "community"; 	}else{ target 		= "customer"; 	}
	if(target == "community")	{ fortarget = formember; 	}else{ formember 	= 1; 			}
	
	var target_sama,besar_sama,pattern_sama,jumlah_beli_sama,jumlah_caption = "";
	
	if(pattern == 2){
		ch_jumlah 		= $(".jumlah_beli[value="+jumlah_beli+"]").length;
		jumlah_caption	= "<b>, Jumlah Beli</b>";
	}else{
		ch_jumlah 		= $(".jumlah_beli option:selected[value="+jumlah_beli+"]").length;
		jumlah_caption	= "<b>, Jumlah Item</b>";
	}
	ch_target	= $(".formember option:selected[value='"+target+"']").length;
	$(".formember option:selected[value='"+target+"']").each(function(){
		id = $(this).attr("data-id");
		if(nilai != id){
			var r	= 0;
			var other_target 		= $("#formember"+id).val();	
			var other_besar 		= $("#besar"+id).val();	
			var other_pattern		= $("#pattern"+id).val();
			var other_jumlah_beli 	= $("#jumlah_beli"+id).val();
			
			if(other_target 	== target)		{ r++; 
					target_sama   	= "<b>, Untuk Member</b>";  
					$("#formember"+nilai+", #formember"+id).css("background","#FFD5EA");
			}else{ 	$("#formember"+nilai+", #formember"+id).css("background","#FFF"); 		}
			if(other_besar 		== besar) 		{ r++; 
					besar_sama    	= "<b>, Diskon</b>";		 	
					$("#besar"+nilai+", #besar"+id).css("background","#FFD5EA");
			}else{ 	$("#besar"+nilai+", #besar"+id).css("background","#FFF"); 				}
			if(other_pattern 	== pattern) 	{ r++; 
					pattern_sama  	= "<b>, Pola Diskon</b>";	 	
					$("#pattern"+nilai+", #pattern"+id).css("background","#FFD5EA");
			}else{ 	$("#pattern"+nilai+", #pattern"+id).css("background","#FFF"); 			}
			if(other_jumlah_beli== jumlah_beli) { r++; 
					jumlah_beli_sama =  jumlah_caption; 	
					$("#jumlah_beli"+nilai+", #jumlah_beli"+id).css("background","#FFD5EA");
			}else{ 	$("#jumlah_beli"+nilai+", #jumlah_beli"+id).css("background","#FFF"); 	}

			if(r > 2){			
				bootbox.alert("Opps...Terjadi Kebingungan antara <b style='#FF0000'>Diskon Reguler "+nilai+"</b> dan <b style='#FF0000'>Diskon Reguler "+id+"</b>, perhatikan persamaan antara input "+besar_sama+" "+target_sama+" "+pattern_sama+" "+jumlah_beli_sama);
				done = 1;
			}
		}
	})
	return done;
}
function insert_diskon(nilai){
	var besar 			= $("#besar"+nilai).val();	
	var satuan 			= $("#satuan"+nilai).val();
	var formember 		= $("#formember"+nilai).val();
	if(formember != "customer")	{ target 	= "community"; 	}else{ target 		= "customer"; 	}
	if(target == "community")	{ fortarget = formember; 	}else{ formember 	= 1; 			}
	var pattern			= $("#pattern"+nilai).val();	
	var jumlah_beli 	= $("#jumlah_beli"+nilai).val();
	
	var ch_pre_order 	= $("#pre_order_"+nilai).is(":checked");
	if(ch_pre_order == true){ 
		var pre_order = 1; 
		var quota 			= $("#quota_"+nilai).val();
		var satuan 			= $("#satuan"+nilai).val();	
	}else{ 
		var pre_order = 0; 
		var quota 			= "";
		var satuan 			= $("#satuan"+nilai).val();	
	}

	
	var keterangan		= $("#keterangan"+nilai).val();
	var tgl				= $("#tgl_"+nilai).val();
	var bln				= $("#bln_"+nilai).val();
	var thn				= $("#thn_"+nilai).val();
	
	var proses_page 	= $("#proses_page").val();
	var id_products		= new Array();
	if($("#pilihan_"+nilai+" .pic_h").length > 0){
		$("#pilihan_"+nilai+" .pic_h").each(function(){
			id_product 	= $(this).val();
			id_products.push(id_product);
		});
	}
	
	// CHECK SIMILARITY //
	done = check_similarity(nilai);
	// END OF CHECK SIMILARITY //
	if(done == 2){
		$(".formember , .besar, .pattern, .jumlah_beli").css("background","#FFF");
		
		$("#pilihan_"+nilai+" input[type='text'],#pilihan_"+nilai+" select, #pilihan_"+nilai+" textarea").attr("disabled","disabled");
		
		expiration	= "";
		if(tgl != "" && bln != "" && thn != ""){ expiration = thn+"-"+bln+"-"+tgl; }
		if(besar >= 10){
			if(besar.length < 4){
	
				if(besar != "" && pattern != ""){
					$("#diskon_load_"+nilai).html("<div style='text-align:center'><img src='files/images/loader_v.gif' style='margin:4px 0 4px 0'></div>");
					$.ajax({
						url 	: proses_page,
						type	: "POST",
						data 	: {"direction":"insert_diskon","pattern":pattern,"jml_beli":jumlah_beli,"pre_order":pre_order,"quota":quota,"quota_unit":satuan,"nilai":nilai,"besar":besar,"satuan":satuan,"target":target,"formember":formember,"keterangan":keterangan,"id_products":id_products,"expiration":expiration},
						success : function(response){
							$("#msg").html(response);
							result = JSON.parse(response);
							if(result.msg == 1){
								nilai 		= result.nilai;
								id_diskon	= result.id_diskon;
								$("#pilihan_"+nilai+" .index").after("<input type='hidden' class='id_diskon' id='id_diskon"+nilai+"' value='"+id_diskon+"' />");
								$("#pilihan_"+nilai+" .insert-btn").attr({"onclick":"save_diskon('"+nilai+"')"}).val(nilai).after('<button type="button" class="btn btn-beoro-2" style="margin-left:4px" onclick="removal(\''+id_diskon+'\',\''+nilai+'\')"><i class="icsw16-trashcan icsw16-white"></i> Hapus</button>');
								$("#id_diskon"+nilai).val(id_diskon);
								$("#pilihan_"+nilai+" #cancel_more").remove();
								$("#diskon_load_"+nilai).empty();
								send_note(id_diskon,"insert");
								//bootbox.alert("Promo Diskon Reguler "+nilai+" Berhasil Ditambahkan");
								location.reload();
							}else{
								bootbox.alert("Pengisian Diskon Belum Lengkap");
							}
						}
					})	
				}else{
					$("#pilihan_"+nilai+" input, #pilihan_"+nilai+" select, #pilihan_"+nilai+" textarea").css("border","1px solid #F00");
				}
			}else{
				bootbox.alert("Besar diskon yang anda tulis terlalu besar");	
				$("#pilihan_"+nilai+" input, #pilihan_"+nilai+" select, #pilihan_"+nilai+" textarea").css("border","1px solid #F00");
			}
		}else{
			bootbox.alert("Mohon masukan besar diskon di atas 10%, Terimakasih");
		}
	}
}

function save_diskon(nilai,target){
	var id_diskon		= $("#id_diskon"+nilai).val();
	var besar 			= $("#besar"+nilai).val();
	var satuan 			= $("#satuan"+nilai).val();

	var formember 		= $("#formember"+nilai).val();
	if(formember != "customer"){ target = "community"; }else{ target = "customer"; }
	if(target == "community"){ fortarget = formember; }else{ formember = 1; }

	var pattern			= $("#pattern"+nilai).val();
	var jumlah_beli 	= $("#jumlah_beli"+nilai).val();
	
	var ch_pre_order 	= $("#pre_order_"+nilai).is(":checked");
	if(ch_pre_order == true){ 
		var pre_order = 1; 
		var quota 			= $("#quota_"+nilai).val();
		var satuan 			= $("#satuan_"+nilai).val();	
	}else{ 
		var pre_order = 0; 
		var quota 			= "";
		var satuan 			= "";	
	}

	keterangan			= $("#keterangan"+nilai).val();
	var tgl				= $("#tgl_"+nilai).val();
	var bln				= $("#bln_"+nilai).val();
	var thn				= $("#thn_"+nilai).val();
	proses_page 		= $("#proses_page").val();
	var id_products		= new Array();

	if($("#pilihan_"+nilai+" .pic_h").length > 0){
		$("#pilihan_"+nilai+" .pic_h").each(function(){
			id_product 	= $(this).val();
			id_products.push(id_product);
		});
	}
	// CHECK SIMILARITY //
	done = check_similarity(nilai);
	// END OF CHECK SIMILARITY //
	if(done == 2){
		$(".formember , .besar, .pattern, .jumlah_beli").css("background","#FFF");
		expiration	= "";
		if(tgl != "" && bln != "" && thn != ""){ expiration = thn+"-"+bln+"-"+tgl; }
		if(besar >= 10){
			if(besar.length < 4){
				if(besar != "" && satuan != "" && pattern != ""){
					$("#diskon_load_"+nilai).html("<div style='text-align:center'><img src='files/images/loader_v.gif' style='margin:4px 0 4px 0'></div>");
					$.ajax({
						url 	: proses_page,
						type	: "POST",
						data 	: {"direction":"save_diskon","target":target,"pattern":pattern,"jml_beli":jumlah_beli,"pre_order":pre_order,"quota":quota,"quota_unit":satuan,"nilai":nilai,"id_diskon":id_diskon,"besar":besar,"satuan":satuan,"keterangan":keterangan,"formember":formember,"id_products":id_products,"expiration":expiration}, 
						success : function(response){
							//alert(response);
							//$("#msg").html(response);
							result = JSON.parse(response);
							if(result.msg == 1){
								nilai 		= result.nilai;
								$("#diskon_load_"+nilai).empty();
								send_note(id_diskon,"save");
								bootbox.alert("Promo Diskon Reguler "+nilai+" Berhasil Disimpan");
							}else{
								bootbox.alert("Pengisian Diskon Belum Lengkap");
							}
						}
					})	
				}else{
					$("#pilihan_"+nilai+" input, #pilihan_"+nilai+" select, #pilihan_"+nilai+" textarea").css("border","1px solid #F00");
				}
			}else{
				bootbox.alert("Besar diskon yang anda tulis terlalu besar");	
				$("#pilihan_"+nilai+" input, #pilihan_"+nilai+" select, #pilihan_"+nilai+" textarea").css("border","1px solid #F00");
			}
		}else{
			bootbox.alert("Mohon masukan besar diskon di atas 10%, Terimakasih");
		}
	}
}

function check_discount(el){
	val = $(el).val();
	if(val < 5){
		bootbox.alert("Mohon masukan besar diskon di atas 10%, Terimakasih");
	}
}

function cancel_option(id){
 var conf 		= JSON.parse("{"+$("#config").val()+"}");
 e = 0;
 $("#pilihan_"+id).remove();	
 $("#ans_content .elm").each(function(){
	thisis = $(this).attr("id");
	r = e+1;
	$("#"+thisis+" .option").html("Diskon Reguler "+r);
	$("#"+thisis+" .index").val(r);
	$("#"+thisis+" .id_diskon").attr("id","id_diskon"+r);
	$("#"+thisis+" .diskon_load").attr("id","diskon_load_"+r);
	$("#"+thisis+" .pic_c").attr("id","pic_"+r);
	$("#"+thisis+" .pic_h").attr("id","id_product_"+r);
	$("#"+thisis+" .bpick").attr("href",conf.dirhost+'/modules/'+conf.page+'/ajax/product_list.php?page='+conf.page+'&id_disc='+r);
	$("#"+thisis+" .besar").attr("id","besar"+r);
	$("#"+thisis+" .satuan").attr("id","satuan"+r);
	$("#"+thisis+" .formember").attr("id","formember"+r);
	$("#"+thisis+" .keterangan").attr("id","keterangan"+r);
	
	$("#"+thisis+" .pattern").attr("id","pattern"+r);
	$("#"+thisis+" .pattern").attr({"onchange":"change_pattern('"+r+"',this)"});
	$("#"+thisis+" .div_pattern").attr("id","div_pattern"+r);
	$("#"+thisis+" .div_product").attr("id","div_product"+r);

	$("#"+thisis+" .jumlah_beli").attr("id","jumlah_beli"+r);
	$("#"+thisis+" .pre_order").attr("id","pre_order_"+r);
	$("#"+thisis+" .t1").attr("id","t1_"+r);
	$("#"+thisis+" .t2").attr("id","t2_"+r);		
	$("#"+thisis+" .tgl").attr("id","tgl_"+r);
	$("#"+thisis+" .bln").attr("id","bln_"+r);
	$("#"+thisis+" .thn").attr("id","thn_"+r);
	
	$("#"+thisis+" .cancel-btn").attr({"onclick":"cancel_option('"+r+"')"}).val(r);
	$("#"+thisis+" .insert-btn").attr({"onclick":"insert_diskon('"+r+"')"}).val(r);
	$("#"+thisis).attr("id","pilihan_"+r);
	e++;
 })
}

function removal(id,nilai){
	bootbox.confirm("Anda Yakin Menghapus Data Diskon Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete_diskon","id":id},
				success	: function(){
					cancel_option(nilai);
					location.reload();
				}
			})
			
		}
	})
}

function send_note(id_diskon,src_direction){
	proses_page 		= $("#proses_page").val();
	$.ajax({
		url 	: proses_page,
		type	: "POST",
		data 	: {"direction":"send_note","id_diskon":id_diskon,"src_direction":src_direction}, 
		success : function(response){
			$("#msg").html(response);
		}
	})	
}