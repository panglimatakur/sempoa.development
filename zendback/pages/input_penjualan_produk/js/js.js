document.write('<script type="text/javascript" src="modules/laporan_penjualan_produk/js/js.js"></script>');
$(document).ready(function(){	
	$("#btn_pick").on("click",function(){
		searching 	= $("#searching").val();
		$("#"+searching).trigger("click");
	})	
	$("#searching").on("keyup",function(){
		var conf 	= JSON.parse("{"+$("#config").val()+"}");
		page		= conf.page;
		searching 	= $("#searching").val();
		filter		= $("#filter").val();	
		item_type	= $("#item_type").val();	
		product_list= $("#product_list_kolektif").val();
		if(item_type != "" && searching.length >= 6){
			$("#dt").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
			$.ajax({
				url 	: product_list,
				type	: "POST",
				data 	: {"page":page,"direction":"search_produk","searching":searching,"filter":filter,"item_type":item_type}, 
				success : function(response){
					$("#dt").html(response);
				}
			})	
		}
	}) 	
	$('#div_input').on('click',function(){
		$("#div_form_penjualan").slideUp(400);
		$(".add_button").show(400);
	});
	$('#div_report').on('click',function(){
		$("#div_form_penjualan").slideDown(400);
		$(".add_button").hide(400);
	});
	$("#diskon_multi").on("keyup",function(){ 
		numeric(this);
		$(".dismulti").val($(this).val()); 
	});
	
	$("#insert_multi_button").on("click",function(){
		var conf 			= JSON.parse("{"+$("#config").val()+"}");
		$(this).before("<input type='hidden' name='direction' value='insert_multi'>");
		var jumlah_multi	= $("#jumlah_multi").val();
		var total_multi		= $("#new_harga_multi").val();
		var tgl_jual 		= $("#tgl_jual_multi").val();
		var id_sales 		= $("#id_sales_multi").val();
		var id_faktur 		= $("#faktur_multi").val();
		var lunas 			= $("#status_lunas_multi").val();
		if(jumlah_multi != "" && total_multi != "" && tgl_jual != "" && id_sales != "" && id_faktur != "" && lunas != ""){
			proses_page = $("#proses_page").val();
			if(conf.realtime == 1){
					container   		= '{"id_client":"'+conf.id_client+'","cidkey":"'+conf.cidkey+'"}';
					pushit("/laporan_penjualan",1,container);
			}
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"check_facture","faktur":id_faktur},
				success : function(){
					$("#formID").submit();
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
				$.sticky("<form method='post' name='form_notif'>Menyimpan Draft Input Penjualan Produk...<br><button type='submit' class='btn btn-sempoa-1' name='direction' value='delete_draft' style='margin:0'><i class='icsw16-trashcan icsw16-white'></i></i></button></form>", {autoclose : 3000, position: "top-right", type: "st-basic" });
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

function open_location(name,id_product){
	loc		 		= $("#locations").val();
	var location 	= JSON.parse("{"+loc+"}");
	if(name == "province"){
		$("#location_open_"+id_product).hide();
		$("#location_close_"+id_product).show();
		$("#propinsi_"+id_product).show();
	}
	if(name == "city"){
		$("#div_kota_"+id_product).html("<img src='files/images/loading-bars.gif'>");
		if($("#div_kecamatan_"+id_product).length){ $("#div_kecamatan_"+id_product).html(""); }
		if($("#div_kelurahan_"+id_product).length){ $("#div_kelurahan_"+id_product).html(""); }

		propinsi = $("#propinsi_"+id_product).val();
		$.ajax({
			url 	: location.city,
			data	: {"id_product":id_product,"propinsi":propinsi},
			success : function(response){
				$("#div_kota_"+id_product).html(response);
			}
		});
	}
	if(name == "district"){
		$("#div_kecamatan_"+id_product).html("<img src='files/images/loading-bars.gif'>");
		if($("#div_kelurahan_"+id_product).length){ $("#div_kelurahan_"+id_product).html(""); }
		
		kota = $("#kota_"+id_product).val();
		$.ajax({
			url 	: location.district,
			data	: {"id_product":id_product,"kota":kota},
			success : function(response){
				$("#div_kecamatan_"+id_product).html(response);
			}
		});
	}
	if(name == "subdistrict"){
		$("#div_kelurahan_"+id_product).html("<img src='files/images/loading-bars.gif'>");
		kecamatan = $("#kecamatan_"+id_product).val();
		$.ajax({
			url 	: location.subdistrict,
			data	: {"id_product":id_product,"kecamatan":kecamatan},
			success : function(response){
				$("#div_kelurahan_"+id_product).html(response);
			}
		});
	}
}

function close_location(id_product){
	$("#location_open_"+id_product).show();
	$("#propinsi_"+id_product).val("");
	$("#propinsi_"+id_product).hide();
	if($("#div_kota_"+id_product).length)		{ $("#div_kota_"+id_product).html(""); 		}
	if($("#div_kecamatan_"+id_product).length)	{ $("#div_kecamatan_"+id_product).html(""); }
	if($("#div_kelurahan_"+id_product).length){	 $("#div_kelurahan_"+id_product).html(""); }
	$("#location_close_"+id_product).hide();
}


function cancel_pick(id_product){
	var conf 			= JSON.parse("{"+$("#config").val()+"}");
	var harga_single	= $("#new_price_"+id_product).val();
	var jumlah_single	= $("#new_jumlah_"+id_product).val();
	var total_single	= $("#new_total_"+id_product).val();
	stock				= $("#stock_label_"+id_product).html();

	var jumlah_multi	= $("#jumlah_multi").val();
	var total_multi		= $("#new_harga_multi").val();
	var diskon			= $("#diskon_multi").val();
	var static_payment 	= $("#harga_multi").val();
	var downpay			= $("#downpay_multi").val();
	var lunas 			= $("#status_lunas_multi").val();
	
	if(jumlah_multi == "") 	{ jumlah_multi 	= 0; 	}
	if(total_multi == "")	{ total_multi 	= 0; 	}
	
	if($("#product_"+id_product).length > 0){
		static_total_single	= harga_single*jumlah_single;
		total_single_label 	= accounting.formatMoney(total_single,"",2,".",",");
		new_diskon			= "";
		if($.trim(diskon) != ""){
			new_diskon			= (total_single/100)*diskon;
		}
		jumlah_multi	= +jumlah_multi - +jumlah_single;
		total_multi		= +total_multi - (+total_single  - +new_diskon);
		static_payment	= +static_payment - +static_total_single;
		payment 		= +total_multi;

		$("#jumlah_multi").val(jumlah_multi);
		$("#harga_multi").val(static_payment);
		money2input("#new_harga_multi","#harga_label_multi",payment,"");
		
		if(lunas != 2){
			if(downpay != ""){
				if(+downpay > +total_multi){
					if($(".modal-body").length == 0){
						bootbox.alert("Jumlah Down Payment Lebih Besar Dari Pada Total Bayar");
					}
				}
				else{
					$("#load_multi").html("");
				}
				payment	= downpay;
			}else{
				payment	= "";	
			}
		}
		sisa 			= +total_multi - +payment;
		$("#kredit_multi").val(sisa);
		sisa_label 		= accounting.formatMoney(sisa,"",2,".",","); //"Rp.",2,".",",");
		$("#kredit_label_multi").val(sisa_label);			
		
		if(sisa > 0){
			if(lunas == 2){
				$('#status_lunas_multi_label').val(3);
				$('#status_lunas_multi').val(3);
				$(".div_kredit_multi").slideDown(500);
			}
		}else{
			if(lunas != 2){
				$('#status_lunas_multi_label').val(2);
				$('#status_lunas_multi').val(2);
				$(".div_kredit_multi").slideUp(500);
			}
		}
		cash 		= $("#new_cash").val();
		if(cash == ""){ cash 	= $("#cash").val(); }
		new_cash	= (+cash - +static_payment) +payment;
		money2html("#new_cash","#balance",new_cash,"Balance : Rp.");	
		
		$(".data_"+id_product).fadeOut(300);
		$(".data_"+id_product).remove();
	}
	
}

function pic_item(id_product){
	var conf 			= JSON.parse("{"+$("#config").val()+"}");
	var total_single	= $("#harga_"+id_product).val();
	var jumlah_single	= $("#jumlah_"+id_product).val();
	stock				= $("#stock_label_"+id_product).html();
	
	var jumlah_multi	= $("#jumlah_multi").val();
	var static_payment 	= $("#harga_multi").val();
	var total_multi		= $("#new_harga_multi").val();
	var diskon	 		= $("#diskon_multi").val();
	var lunas 			= $("#status_lunas_multi").val();
	var downpay			= $("#downpay_multi").val();
	var value 			= $("#value_"+id_product).val();	
	var data			= JSON.parse("{"+value+"}");
	
	if(jumlah_multi == "") 	{ jumlah_multi 	= 0; 	}
	if(total_multi == "")	{ total_multi 	= 0; 	}

	photo_big		= "javascript:void()";
	if(data.photo != ""){
		photo 		= "files/images/products/"+conf.id_client+"/thumbnails/"+data.photo;	
		photo_big 	= "files/images/products/"+conf.id_client+"/"+data.photo;	
	}else{
		photo 		= "files/images/no_image.jpg";	
		photo_big 	= "files/images/no_image.jpg";	
	}
	if($("#product_"+id_product).length == 0){
		
		$("#jumlah_form_"+id_product).css({"border":"1px solid #CCCCCC","background":"#FFFFFF"}).attr("placeholder","");
		$("#jumlah_"+id_product).attr("readonly","readonly");
		
		if(jumlah_single != ""){
			total_single		= total_single*jumlah_single;
			static_payment		= +static_payment + +total_single;
			total_single_label 	= accounting.formatMoney(total_single,"",2,".",",");

			var container		= " <tr class='data_"+id_product+"'>"+
									"<td colspan='6'><b style='color:#CC0000'>"+data.code+"</b> - "+data.name+"</td>"+
								"</tr>"+
								"<tr class='data_"+id_product+"'>"+
									"<td style='text-align:center;'>"+
										"<a href='"+photo_big+"' class='fancybox'><img src='"+photo+"' class='photo' style='width:90%; margin-right:5px'></a>"+
									"</td>"+
									"<td style='text-align:right'>"+data.price+"</td>"+
									"<td style='text-align:center'>"+jumlah_single+"</td>"+
									"<td style='text-align:right'>"+total_single_label+"</td>"+
									"<td style='text-align:center'>"+
										"<a href='javascript:void()' onclick='cancel_pick(\""+id_product+"\")' class='btn btn-mini'>"+
										"<i class='icon-trash'></i>"+
										"</a>"+
										"<input type='hidden' id='product_"+id_product+"' 	 name='id_product[]' value='"+id_product+"'>"+
										"<input type='hidden' id='new_jumlah_"+id_product+"' name='jumlah[]' 	 value='"+jumlah_single+"'>"+
										"<input type='hidden' id='new_price_"+id_product+"'  name='harga[]'  	 value='"+data.price_ori+"'>"+
										"<input type='hidden' id='stock_"+id_product+"' 	 name='stock[]'  	 value='"+stock+"'>"+
										"<input type='hidden' id='new_total_"+id_product+"'  name='total[]'  	 value='"+total_single+"'>"+
									"</td>"+
								"</tr>";
			
			$("#table_list tbody tr:first").before(container);
			new_diskon 			= "";
			if(diskon != ""){
				new_diskon		= (+total_single/100) * +diskon;
			}
			jumlah_multi	= +jumlah_multi + +jumlah_single;
			total_multi		= +total_multi + (+total_single - +new_diskon);
			payment 		= +total_multi;
			
			$("#jumlah_multi").val(jumlah_multi);
			$("#harga_multi").val(static_payment);
			money2input("#new_harga_multi","#harga_label_multi",payment,"");
			
			cash 		= $("#new_cash").val();
			if(cash == ""){ cash 	= $("#cash").val(); }
			new_cash	= +cash + +total_single;
			money2html("#new_cash","#balance",new_cash,"Balance : Rp.");

			if(lunas != 2){
				if(downpay != ""){
					if(+downpay > +total_multi){
						if($(".modal-body").length == 0){
							bootbox.alert("Jumlah Down Payment Lebih Besar Dari Pada Total Bayar");
						}
					}
					else{
						$("#load_multi").html("");
					}
					payment	= downpay;
				}else{
					payment	= "";	
				}
			}
			
			
			sisa 			= +total_multi - +downpay;
			$("#kredit_multi").val(sisa);
			sisa_label 		= accounting.formatMoney(sisa,"",2,".",","); //"Rp.",2,".",",");
			$("#kredit_label_multi").val(sisa_label);			
			
			if(sisa > 0){
				if(lunas == 2){
					$('#status_lunas_multi_label').val(3).change();
					$('#status_lunas_multi').val(3);
					$(".div_kredit_multi").slideDown(500);
				}
			}
			
		}else{
				$("#jumlah_form_"+id_product).css({"border":"1px solid #FF9F9F","background":"#FFD9D9"}).attr("placeholder","Isi Jumlah Penjualan Produk");
		}
	}else{
		$("#item_"+id_product+" td").addClass("alert alert-error");
		$("#jumlah_"+id_product).attr("readonly","readonly");
	}
}

