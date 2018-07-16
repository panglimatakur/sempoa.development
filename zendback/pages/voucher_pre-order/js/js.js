function send_deal(id_customer,id_product_deal){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	proses_page 	= $("#proses_page").val();
	discount		= $("#disc_"+id_product_deal).val();
	voucher			= $("#voucher_"+id_product_deal).val();
	expired			= $("#expired_"+id_product_deal).val();
	if(discount != "" && voucher != "" && expired != ""){
		
		$("#deal_loader_"+id_product_deal).html('<br><img src="'+conf.dirhost+'/files/images/loading-bars.gif" style="margin:5px 0 5px 0">');
		$.ajax({
			url 	: proses_page,
			type	: "POST",
			data	: {"direction":"save_deal","id_customer":id_customer,"id_product_deal":id_product_deal,"discount":discount,"voucher":voucher,"expired":expired},
			success : function(response){
				bootbox.alert(response);
				$("#deal_loader_"+id_product_deal).empty();
				$("#disc_"+id_product_deal).val("");
				$("#cond_"+id_product_deal).val("");
				$("#piece_"+id_product_deal).val("");
				$.fancybox.close();
			}
		});
		
	}else{
		$("#deal_loader_"+id_product_deal).html("<div clas='alert alert-error'>Pengisian Form Belum Lengkap</div>");	
	}
}

function refuse(id_product_deal,id_deal){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	proses_page 	= $("#proses_page").val();
	bootbox.confirm("Anda yakin menolak deal <b class='code'>#"+id_deal+"</b> ini?",function(confirmed){
		if(confirmed == true){
			$("#tr_loader_"+id_product_deal).html('<img src="'+conf.dirhost+'/files/images/loading-bars.gif" style="margin:9px 0 0 5px">');
			$.ajax({
				url 	: proses_page,
				type	: "POST",
				data	: {"direction":"refuse_deal","id_product_deal":id_product_deal},
				success : function(response){
					$("#tr_loader_"+id_product_deal).empty();
					$("#tr_"+id_product_deal).fadeOut(200);
				}
			});
		}
	})
}

function set_status(id_deal,id_customer){
	var conf 		= JSON.parse("{"+$("#config").val()+"}");
	proses_page 	= $("#proses_page").val();
	$("#loader_"+id_deal+"_"+id_customer).html('<img src="'+conf.dirhost+'/files/images/loading-bars.gif"><br>');
	id_status		= $("#status_"+id_deal+"_"+id_customer).val();
	$.ajax({
		url 	: proses_page,
		type	: "POST",
		data	: {"direction":"set_status","id_product_deal":id_deal,"id_customer":id_customer,"id_status":id_status},
		success : function(response){
			$("#loader_"+id_deal+"_"+id_customer).html('<div class="alert alert-success" style="margin:0">'+response+'</div>');
		}
	});
}