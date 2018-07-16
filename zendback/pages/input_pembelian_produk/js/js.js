document.write('<script type="text/javascript" src="modules/laporan_pembelian_produk/js/js.js"></script>');
$(document).ready(function(){		
	$('#div_input').on('click',function(){
		$("#div_form_pembelian").slideUp(400);
		$(".add_button").show(400);
	});
	$('#div_report').on('click',function(){
		$("#div_form_pembelian").slideDown(400);
		$(".add_button").hide(400);
	});
	
	$(".new_cat").on("click",function(){
		$(".category").toggle();
	})
	$(".cancel_cat").on("click",function(){
		id_product = $(this).attr("data-info");
		$(".category").toggle();
		
	})
	$(".save_cat").on("click",function(){
		id_product 	= $(this).attr("data-info");
		value 		= $("#category_"+id_product).val();
		if(value != ""){
			proses_page	= $("#proses_page").val();
			$("#div_category_"+id_product).html("<img src='files/images/loading-bars.gif'>");
			$.ajax({
				url  : proses_page,
				type : "POST",
				data : {"direction":"add_supplier","id_product":id_product,"nama":value},
				success : function(response){
					$("#id_cat_"+id_product).remove();
					$("#div_category_"+id_product).html(response);
				}
			})
		}
	})
	$("#termin_multi").live("keyup blur",function(){
		show_termin("multi");
	})
	$(".ttempo").each(function(){
		if($(this).length > 0){
			$(this).datepicker();
		}
	})
	$("#btn_termin").on("click",function(){
		today			= $("#tgljs").val();
		id_cash_flow	= $("#id_cash_flow").val();
		status 			= $("#status_lunas_edit").val(); 
		termin 			= $("#termin_edit").val(); 
		termin 			=  +termin + 1;
		container = 
		"<tr id='tr_edit_"+termin+"' class='tr_edit' data-list='new'>"+
			"<td class='option'>Tanggal Jatuh Tempo "+termin+"</td>"+
			"<td>"+
				"<span class='input-append date' id='dp_edit_"+termin+"' data-date='"+today+"' data-date-format='dd-mm-yyyy'>"+
					"<input class='mousetrap date_input' size='16' value='"+today+"' readonly='' type='text' id='tgl_tempo_edit_"+termin+"'>"+
					"<span class='add-on'><i class='icsw16-day-calendar'></i></i></span>"+
				"</span> "+
				"<button class='btn beoro-btn' id='cancel_more' style='margin-left:3px' onclick=\"remove_new_tempo('"+termin+"','"+id_cash_flow+"')\">"+
					"<i class='icsw16-trashcan'></i>"+
				"</button>"+
				"<input type='hidden' class='st_termin' id='st_termin_"+termin+"' value='0' />"+
				"<span class='tempo_loader' id='delete_tempo_loader_"+termin+"'></span>"+
			"</td>"+
		"</tr>";
		$("#div_termin_edit").before(container);
		$('#dp_edit_'+termin).datepicker();
		$("#termin_edit").val(termin);
	});
	setInterval("save_draft()",9000);
}); 

function remove_tempo(ordinal,id_cash_flow){
	proses_page = $("#proses_page").val();
	termin 		= $("#termin_edit").val();
	$("#delete_tempo_loader_"+ordinal).html("<img src='files/images/loading-bars.gif' style='margin-left:4px;'>");
	$.ajax({
		url		: proses_page,
		type	: "POST",
		data 	: {"direction":"delete_tempo","id_cash_flow":id_cash_flow,"termin":ordinal},
		success	: function(response){
			remove_new_tempo(ordinal,id_cash_flow)
		}
	})
}

function remove_new_tempo(termin,id_cash_flow){
	r = 0;
	$("#tr_edit_"+termin).remove();
	 $(".tr_edit").each(function(){
		thisis 		= $(this).attr("id");
		list_data 	= $(this).attr("data-list");
		r++;
		$("#"+thisis+" .option").html("Tanggal Jatuh Tempo "+r);
		$("#"+thisis+" .date").attr("id","dp_edit_"+r);
		$("#"+thisis+" .date_input").attr("id","tgl_tempo_edit_"+r);
		$("#"+thisis+" .st_termin").attr("id","st_termin_"+r);
		$("#"+thisis+" .tempo_loader").attr("id","delete_tempo_loader_"+r);
		if(list_data == "new"){
			$("#"+thisis+" button").attr({"id":"cancel_more","onclick":"remove_new_tempo('"+r+"','"+id_cash_flow+"')"}).val(r);
		}else{
			$("#"+thisis+" button").attr({"id":"cancel_more","onclick":"remove_tempo('"+r+"','"+id_cash_flow+"')"}).val(r);
		}
		$("#"+thisis).attr("id","tr_edit_"+r);
	 })
	num_termin		= $("#termin_edit").val();
	num_termin 		= +num_termin - 1;
	$("#termin_edit").val(num_termin); 
}

function save_draft(){
	jumlah_multi 		= $("#jumlah_multi").val();
	if(jumlah_multi != ""){
		var conf 			= JSON.parse("{"+$("#config").val()+"}");
		page				= conf.page;
		value 		= $("#formID").serialize();
		proses_page	= $("#proses_page").val();
		$.ajax({
			url  : proses_page,
			type : "POST",
			data : {"page":page,"direction":"save_draft","keterangan":value},
			success : function(response){
				$.sticky("<form method='post' name='form_notif'>Menyimpan Draft Input Pembelian Produk...<br><button type='submit' name='direction' value='delete_draft' class='btn btn-sempoa-1' style='margin:0'><i class='icsw16-trashcan icsw16-white'></i></i></button></form>", {autoclose : 3000, position: "top-right", type: "st-basic" });
			}
		})
	}
}
	
function show_termin(src){
	termin  = $("#termin_multi");
	$("#termin_multi").val(termin.val().replace(/[^0-9]/g,''));
	termin  = $("#termin_multi").val();
	
	if(termin != "" || termin != 0){
		content ="";
		for(t=1;t<=termin;t++){
			content +=
			"<div class='form-group' style='padding-left:5px'>"+
			"<label>Tanggal Jatuh Tempo "+t+"</label>"+
				"<span class='input-append date' id='dp_"+t+"' data-date='' data-date-format='dd-mm-yyyy'>"+
					"<input size='16' value='' readonly='' type='text' id='tgl_tempo_multi_"+t+"' name='tgl_tempo_multi[]' class='mousetrap form-control validate[required] text-input'>"+
					"<span class='add-on'><i class='icsw16-day-calendar'></i></i></span>"+
				"</span>"+                     
			"</div>";
		}
		$("#div_termin_multi").html(content).slideDown(200);
		for(t2=1;t2<=termin;t2++){
			$('#dp_'+t2).datepicker();
		}
	}else{
		$("#div_termin_multi").html("").slideUp(200).css("display","none");
	}	
}

function cancel_pick(id_product){
	var prod_count	= $("#jumlah_multi").val();
	var harga_count	= $("#total_bayar_multi").val();
	var downpay		= $("#downpay_multi").val();
	var status_lunas= $("#status_lunas_multi").val();
	var total_beli	= $("#new_total_"+id_product).val();
	var jml_beli	= $("#new_jumlah_"+id_product).val();
	var harga_beli	= $("#new_beli_"+id_product).val();
	
	new_prod_count	= +prod_count - +jml_beli;
	$("#jumlah_multi").val(new_prod_count);
	
	new_price_count	= +harga_count - (harga_beli*jml_beli);
	result 			= accounting.formatMoney(new_price_count,"",2,".",",");
	$("#total_bayar_label_multi").val(result);
	$("#total_bayar_multi").val(new_price_count);
	
	new_price_kredit = +new_price_count - +downpay;
	if(new_price_kredit > 0){
		if(status_lunas == 2){
			$('#status_lunas_multi_label').val(1);
			$('#status_lunas_multi').val(1);
			$(".div_kredit_multi").slideDown(500);
		}
	}else{
		if(status_lunas != 2){
			$('#status_lunas_multi_label').val(2);
			$('#status_lunas_multi').val(2);
			$(".div_kredit_multi").slideUp(500);
		}
	}
	money2input("#kredit_multi","#kredit_label_multi",new_price_kredit,"");

	cash			= $("#new_cash").val();
	new_cash_count	= +cash + +total_beli;
	$("#new_cash").val(new_cash_count);
	
	res = accounting.formatMoney(new_cash_count,"Balance : Rp.",2,".",",");
	$("#balance").html(res);

	$(".data_"+id_product).fadeOut(300);
	$(".data_"+id_product).remove();
	
}
function pic_item(id_product){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	var jml_beli	= $("#jumlah_"+id_product).val();
	var harga_beli	= $("#harga_beli_"+id_product).val();
	var harga_jual	= $("#harga_jual_"+id_product).val();
	stock			= $("#stock_label_"+id_product).html();
	
	status_lunas 	= $("#status_lunas_multi").val();
	var prod_count	= $("#jumlah_multi").val();
	var harga_count	= $("#total_bayar_multi").val();
	var downpay		= $("#downpay_multi").val();
	var value 		= $("#value_"+id_product).val();	
	var data		= JSON.parse("{"+value+"}");
	
	
	if(prod_count == "") { prod_count = 0; }
	if(harga_count == ""){ harga_count = 0; }
	photo_big		= "javascript:void()";
	if(data.photo != ""){
		photo 		= "files/images/products/"+conf.id_client+"/thumbnails/"+data.photo;	
		photo_big 	= "files/images/products/"+conf.id_client+"/"+data.photo;	
	}else{
		photo = "files/images/noimage-m.jpg";	
	}
	
	if($("#product_"+id_product).length == 0){
		if(jml_beli != "" && harga_beli != "" && harga_jual != ""){
			harga_beli_label	= accounting.formatMoney(harga_beli,"Rp.",2,".",",");
			harga_jual_label	= accounting.formatMoney(harga_jual,"Rp.",2,".",",");
			total_beli			= harga_beli*jml_beli;
			total_beli_label 	= accounting.formatMoney(total_beli,"Rp.",2,".",",");
			var container	= " <tr class='data_"+id_product+"'>"+
									"<td colspan='6'><b style='color:#CC0000'>"+data.code+"</b> - "+data.name+"</td>"+
								"</tr>"+
								"<tr class='data_"+id_product+"'>"+
									"<td style='text-align:center;'>"+
										"<a href='"+photo_big+"' class='fancybox'><img src='"+photo+"' class='photo' style='width:90%; margin-right:5px'></a>"+
									"</td>"+
									"<td style='text-align:right'>"+harga_beli_label+"</td>"+
									"<td style='text-align:center'>"+jml_beli+"</td>"+
									"<td style='text-align:right'>"+total_beli_label+"</td>"+
									"<td style='text-align:right'>"+harga_jual_label+"</td>"+
									"<td style='text-align:center'>"+
										"<a href='javascript:void()' onclick='cancel_pick(\""+id_product+"\")' class='btn btn-mini'>"+
										"<i class='icon-trash'></i>"+
										"</a>"+
										"<input type='hidden' id='product_"+id_product+"' 	 name='id_product[]' value='"+$.trim(id_product)+"'>"+
										"<input type='hidden' id='new_beli_"+id_product+"' 	 name='harga_beli[]' value='"+$.trim(harga_beli)+"'>"+
										"<input type='hidden' id='new_jual_"+id_product+"'	 name='harga_jual[]' value='"+$.trim(harga_jual)+"'>"+
										"<input type='hidden' id='new_jumlah_"+id_product+"' name='jumlah[]' 	 value='"+$.trim(jml_beli)+"'>"+
										"<input type='hidden' id='stock_"+id_product+"' 	 name='stock[]'  	 value='"+$.trim(stock)+"'>"+
										"<input type='hidden' id='new_total_"+id_product+"'  name='total[]'  	 value='"+$.trim(total_beli)+"'>"+
									"</td>"+
								"</tr>";
			
			cash 	= $("#new_cash").val();
			if(cash == ""){
				cash 	= $("#cash").val();
			}
			new_cash	= +cash-(total_beli-downpay);
			res 		= accounting.formatMoney(new_cash,"Balance : Rp.",2,".",",");
			$("#balance").html(res);
			
			if(new_cash > 0){
				new_prod_count	= +prod_count + +jml_beli;
				$("#jumlah_multi").val(new_prod_count);
				new_price_count	= +harga_count + (+harga_beli*+jml_beli);
				
				result 			= accounting.formatMoney(new_price_count,"",2,".",",");
				$("#total_bayar_label_multi").val(result);
				$("#total_bayar_multi").val(new_price_count);
				
				new_price_kredit	= +new_price_count- +downpay;
				money2input("#kredit_multi","#kredit_label_multi",new_price_kredit,"");
				
				if(new_price_kredit > 0){
					if(status_lunas == 2){
						$('#status_lunas_multi_label').val(1).change();
						$('#status_lunas_multi').val(1);
						$(".div_kredit_multi").slideDown(500);
					}
				}
				
				$("#new_cash").val(new_cash);
				$("#jumlah_form_"+id_product).css({"border":"1px solid #CCCCCC","background":"#FFFFFF"}).attr("placeholder","");
				$("#item_"+id_product+" input").attr("readonly","readonly");
				$("#msg_"+id_product).html("").removeClass("alert alert-error");
				$("#table_list tbody tr:first").before(container);
			}else{
				$.fancybox.close();
				$("#balance").css("color","#CC0000"); 
				if($(".modal-body").length == 0){
					bootbox.alert("Uang kas anda tidak mencukupi untuk transaksi ini",function(){
						new_cash = $("#cash").val();
						res = accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
						$("#balance").html(res);
						$("#balance").css("color","#000000");
					});
				}
			}
		}else{
			$("#msg_"+id_product).html("Pengisian Form Belum Lengkap").addClass("alert alert-error").css("margin","0");
		}
	}else{
		$("#item_"+id_product+" td").addClass("alert alert-error");
		$("#msg_"+id_product).html("<b>"+data.name+" sudah di pilih</b>");
		$("#item_"+id_product+" input").attr("readonly","readonly");
	}
}

