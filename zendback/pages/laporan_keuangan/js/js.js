$(document).ready(function(){
	$("#direction_save").on("click",function(){
		id_root			= $("#id_root").val();
		parent_id		= $("#parent_id").val();
		cash_value		= $("#cash_value").val();
		cash_ori_value	= $("#cash_original_value").val();
		sumber			= $("#sumber").val();
		status_lunas	= $("#status_lunas").val();
		downpay			= $("#downpay").val();
		dp_ori_value	= $("#first_downpay").val();
		credit			= $("#credit").val();
		first_credit	= $("#first_credit").val();
		termin 			= $("#termin_edit").val();
		
		if(dp_ori_value != ""){
			first_value = dp_ori_value;
		}else{
			first_value = cash_ori_value
		}
		jumlah_all 	= $("#jumlah_all").val();
		total_all	= $("#total_all").val();
		remain_all 	= $("#remain_all").val();
		
		if(downpay == ""){ downpay = 0; }
		done = 2;
		el_total		= "#cash_value";
		if(status_lunas != 2){
			el_total 		= 	"#downpay";		
			if(+downpay > +cash_value){
				$("#load").html("<div class='alert alert-error' style='float:left; padding:4px; margin-left:4px; width:60%'>Jumlah Uang Muka Lebih Besar Dari Pada Nilai Uang</div>");
				done = 1;
			}
		}

		if(done == 2){
			if(cash_value != ""){
			proses_page		= $("#proses_page").val();
			id_cash			= $(this).attr("data-info");
			keterangan		= $("#keterangan").val();
			st_termin		= "";
			tgl_tempo_edit	= "";
			if(termin != "" || termin != 0){
				for(t2=1;t2<=termin;t2++){
					tgl_tempo_edit += ";"+$("#tgl_tempo_edit_"+t2).val();
					st_termin	   += ";"+$("#st_termin_"+t2).val();
				}
			}
			
			$("#direction_save_button").html("<img src='files/images/loading-bars.gif' style='margin-left:4px'>");
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data	: {
							"direction":"save",
							"id_root":id_root,
							"parent_id":parent_id,
							"no":id_cash,
							"cash_value":cash_value,
							"first_value":first_value,
							"sumber":sumber,
							"status_lunas":status_lunas,
							"downpay":downpay,
							"credit":credit,
							"first_credit":first_credit,
							"termin":termin,
							"tgl_tempo":tgl_tempo_edit,
							"st_termin":st_termin,
							"keterangan":keterangan,
							"jumlah_all":jumlah_all,
							"total_all":total_all,
							"remain_all":remain_all
						  },
				success	: function(response){
					dt	= JSON.parse(response);
					if(dt.io == 1){
						$.fancybox.close();
						bootbox.alert(dt.note);
					}
					
					if(dt.io == 2){
						$("#div_cash_"+id_cash+"_1").html(dt.cash_value_label);
						$("#div_cash_"+id_cash+"_2").html(dt.status_lunas_label);
						$("#div_cash_"+id_cash+"_3").html(dt.paid_value_label);
						$("#div_cash_"+id_cash+"_4").html(dt.credit_value_label);
						$("#balance").html(dt.balance_label);
						$("#cash").val(dt.balance);
						$("#direction_save_button").html(dt.note);
						
						$("#total_all").val(dt.new_total_all);
						$("#div_total_all").html(dt.new_total_all_label);
						$("#remain_all").val(dt.new_remain_all);
						$("#div_remain_all").html(dt.new_remain_all_label);
						$.fancybox.close();
					}
				}
			})
		}
		}
	})
	
	$("#reset_date").on("click",function(){
		$("#tgl_1").val("");
		$("#tgl_2").val("");
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
		bootbox.confirm("Anda Yakin Menghapus Data Ini?",function(confirmed){
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
	$("#btn_termin").on("click",function(){
		today			= $("#tgljs").val();
		id_cash_flow	= $("#id_cash_flow").val();
		status 			= $("#status_lunas").val(); 
		termin 			= $("#termin_edit").val(); 
		termin 			=  +termin + 1;
		container = 
		"<div id='tr_edit_"+termin+"' class='tr_edit form-group' data-list='new'>"+
		"<label class='option'>Tanggal Jatuh Tempo "+termin+"</label>"+
			"<span class='input-append date' id='dp_edit_"+termin+"' data-date='"+today+"' data-date-format='dd-mm-yyyy'>"+
				"<input class='mousetrap date_input' size='16' value='"+today+"' readonly='' type='text' id='tgl_tempo_edit_"+termin+"'>"+
				"<span class='add-on'><i class='icsw16-day-calendar'></i></i></span>"+
			"</span> "+
			"<button class='btn beoro-btn' id='cancel_more' style='margin-left:3px' onclick=\"remove_new_tempo('"+termin+"','"+id_cash_flow+"')\">"+
				"<i class='icsw16-trashcan'></i>"+
			"</button>"+
			"<input type='hidden' class='st_termin' id='st_termin_"+termin+"' value='0' />"+
			"<span class='tempo_loader' id='delete_tempo_loader_"+termin+"'></span>"+
		"</div>";
		$("#div_termin_edit").before(container);
		$('#dp_edit_'+termin).datepicker();
		$("#termin_edit").val(termin);
	});
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

function print_r(){
	tgl_1 		= $("#tgl_1").val();
	tgl_2 		= $("#tgl_2").val();
	parent_id 	= $("#parent_id").val();
	
	show_data		= $("#show_data").val();

	print_container = "<input type='hidden' name='parent_id' value='"+parent_id+"' />"+
	"<input type='hidden' name='tgl_1' value='"+tgl_1+"' />"+
	"<input type='hidden' name='tgl_2' value='"+tgl_2+"' />"+
	"<input type='hidden' name='show_data' value='"+show_data+"' />";

	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
	
}


function removal(id){
	bootbox.confirm("Anda Yakin Menghapus Data Ini?",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data	: {"direction":"delete","no":id},
				success	: function(response){
					$("#tr_detail_"+id).fadeOut(500);
					new_cash	= $.trim(response);
					res 		= accounting.formatMoney(new_cash,"Balance : Rp.",2,".",","); // €4.999,99	
					$("#balance").html(res);
					$("#cash").val(new_cash);
				}
			})
		}
	})
}

function count_payment(el,first_val){
	new_val 	= $(el).val();
	number 		= new_val.replace(/[^0-9]/g,''); // a string of only digits, or the empty string
	$(el).val(number);
	
	id_root		= $("#id_root").val();
	total 		= $("#cash_value").val();
	downpay 	= $("#downpay").val();
	kredit 		= $("#credit").val();
	kredit_label= $("#kredit_label").val();
	cash 		= $("#cash").val();
	
	if(downpay != ""){
		el		= "#downpay";
		fist_val= $("#first_downpay").val();
		if(+downpay > +total){
			$("#load").html("<div class='alert alert-error' style='float:left; padding:4px; margin-left:4px; width:60%'>Jumlah Down Payment Lebih Besar Dari Pada Total Bayar</div>");
			$("#downpay").val("");	
			new_kredit 	= total;
		}
		else{
			$("#load").html("");
			new_kredit 	= +total- +downpay;
		}
	}else{
		el				= "#cash_value";
		new_kredit 		= total;
		fist_val= $("#first_downpay").val();
	}
	$("#credit").val(new_kredit);
	new_kredit_label = accounting.formatMoney(new_kredit,"",2,".",","); //"Rp.",2,".",",");
	$("#kredit_label").val(new_kredit_label);
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
	first_val = $("#first_downpay").val();
	count_payment("#cash_value",first_val);
}
