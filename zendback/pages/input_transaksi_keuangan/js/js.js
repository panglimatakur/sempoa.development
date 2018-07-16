$(document).ready(function(){		
	$('#direction').on('click',function(){
		proses_page 	= $("#proses_page").val();
		direction 		= $(this).val();
		tgl_bayar 		= $("#tgl_bayar_multi").val();
		parent_id 		= $("#parent_id").val();
		id_root 		= $("#id_root").val();
		cash_value 		= $("#nilai").val();
		sumber 			= $("#sumber").val();
		keterangan 		= $("#keterangan").val();
		new_cash 		= $("#new_cash").val();
		
		lunas 			= $("#status_lunas").val();
		nopo			= $("#nopo").val();	
		downpay			= $("#downpay").val();	
		kredit			= $("#credit").val();	
		termin			= $("#termin_multi").val();	
		var tgl_tempos	= new Array();
		$(".tgl_tempo_multi").each(function(){
			tgl_tempos.push($(this).val());
		})
		if(downpay == ""){ downpay = 0; }
		done = 2;
		if(lunas != 2){
			if(+downpay > +cash_value){
				bootbox.alert("Jumlah Uang Muka Lebih Besar Dari Pada Nilai Uang");
				done = 1;
			}
		}
		if(done == 2){
			$("#button_c").html("<img src='files/images/loading-bars.gif'>");
				$.ajax({
					url		: proses_page,
					type	: "POST",
					data	: {"direction":direction,"tgl_bayar":tgl_bayar,"parent_id":parent_id,"id_root":id_root,"cash_value":cash_value,"sumber":sumber,"keterangan":keterangan,"lunas":lunas,"nopo":nopo,"downpay":downpay,"kredit":kredit,"termin":termin,"tgl_tempo_multi":tgl_tempos},
					success	: function(response){
						$("#button_c").html(response);
						$("#nilai").attr("disabled","disabled");
						$("#cash").val(new_cash);	
						$("#new_cash").val("");
					}
				})
		}else{
			bootbox.alert("Pengisian Form Bertanda (<span style='color:#FF0000'>*</span>) Belum Lengkap");
		}
	})
	
	$(".new_cat").on("click",function(){
		$(".category").toggle();
		
	})
	$(".cancel_cat").on("click",function(){
		$(".category").toggle();
		
	})
	$(".save_cat").on("click",function(){
		value 		= $("#category").val();
		if(value != ""){
			proses_page	= $("#proses_page").val();
			$("#div_category").html("<img src='files/images/loading-bars.gif'>");
			$.ajax({
				url  : proses_page,
				type : "POST",
				data : {"direction":"add_source","nama":value},
				success : function(response){
					$("#id_cat").remove();
					$("#div_category").html(response);
				}
			})
		}
	})
	$("#termin_multi").live("keyup blur",function(){
		show_termin("multi");
	})
});

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
					"<input size='16' value='' readonly='' type='text' id='tgl_tempo_multi_"+t+"' name='tgl_tempo_multi[]' class='mousetrap form-control validate[required] text-input tgl_tempo_multi'>"+
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

function input_value(id,id_root){
	$("#new_cash").val("");
	cash 		= $("#cash").val();
	balance		= accounting.formatMoney(cash,"Balance : Rp.",2,".",",");
	$("#balance").html(balance);
	$("#form_value").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
	form_page = $("#form_page").val();
	$.ajax({
		url		: form_page,
		type	: "POST",
		data 	: {"show":"form_value","parent_id":id,"id_root":id_root},
		success	: function(response){
			$("#form_value").html(response);
		}
	})
}

function count_payment(el,first_val){
	new_val 	= $(el).val();
	number 		= new_val.replace(/[^0-9]/g,''); // a string of only digits, or the empty string
	$(el).val(number);
	
	id_root		= $("#id_root").val();
	total 		= $("#nilai").val();
	downpay 	= $("#downpay").val();
	kredit 		= $("#credit").val();
	kredit_label= $("#kredit_label").val();
	cash 		= $("#cash").val();
		
	if(downpay != ""){
		el		= "#downpay";
		if(+downpay > +total){
			bootbox.alert("Jumlah Down Payment Lebih Besar Dari Pada Total Bayar");
			$("#downpay").val("");	
			$("#credit").val("");
			$("#kredit_label").val("");
			done = 1;
		}
		else{
			new_kredit 	= +total- +downpay;
			result 		= accounting.formatMoney(new_kredit,"",2,".",","); //"Rp.",2,".",",");
			$("#credit").val(new_kredit);
			$("#kredit_label").val(result);
			done = 2;
		}
	}else{
		el		= "#nilai";
		$("#credit").val("");
		$("#kredit_label").val("");
	}
	new_val = $(el).val();
	if(new_val != "" && new_val != 0){
		if(id_root == 1){
			new_cash	= (+cash - +first_val)+ +new_val;
			fail		= 0;
		}else{
			new_cash	= (+cash + +first_val)- +new_val;
			if(new_val > +cash){ fail = 1; }else{ fail = 0; }
		}
	}else{
		new_cash = cash;
		fail = 0;	
	}
	if(fail == 1){
		$.fancybox.close();
		$("#balance").css("color","#CC0000"); 
		if($(".modal-body").length == 0){
			bootbox.alert("Uang kas anda tidak mencukupi untuk transaksi ini",function(){
				new_cash = $("#new_cash").val();
				res = accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
				$("#balance").html(res);
				$("#balance").css("color","#000000");
				$(el).val("");
			});
		}
	}else{
		$("#balance").css("color","#000000");
		res = accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
		$("#balance").html(res);
		$("#new_cash").val(new_cash);
	}
}


function show_kredit(){
	lunas 	= $("#status_lunas").val();
	if(lunas != "2"){
		classStyle = "mousetrap form-control validate[required] text-input";
		$(".div_kredit").fadeIn(500);
	}else{
		classStyle	= "mousetrap form-control";
		$(".div_kredit").fadeOut(500);
	}
	$("#downpay").val("").addClass(classStyle);	
	$("#kredit_label").val("");
	$("#credit").val("").addClass(classStyle);	
}
