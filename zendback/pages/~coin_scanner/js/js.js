$(document).ready(function(){	
	coin_merchant = $("#coin_merchant").val();
	$('#qrcode').qrcode({width: 580,height: 580,text: coin_merchant});
	$("#coin_scanner").on("click",function(){
		$("#reader").slideToggle(200,function(){
			$("#html5_qrcode_video").css({"padding":"0","width":"80%","height":"280px","margin":"9px 0 29px 0"}); 
		})
	});
	$('#reader').html5_qrcode(function(data){
			$('#coin').val(data);
			$('#btn_coin').trigger("click");
		},
		function(error){
			$('#read_error').html("Scan Lagi"); //error
		},
		function(videoError){
			//$('#vid_error').html(videoError);
		}
	);

	$('#btn_coin').on('click',function(){
		data 		= $("#data_page").val();
		coin 		= $("#coin").val();
		if(coin != ""){
			$("#div_coin").html("<div style='text-align:center'><img src='files/images/loader.gif'></div>");
			$.ajax({
				url		: data,
				type	: "POST",
				data	: {"direction":"check","coin":coin},
				success	: function(response){
					$("#div_coin").html(response);
				}
			})
		}else{
			bootbox.alert("Masukan Nomor COIN");
		}
	})
	
	
	$("#send_visit").on("click",function(){
		container		= "{"+$(this).val()+"}";
		var vis_data	= JSON.parse(container);
		var chat_data	= $("#data_page").val();
		var conf 		= JSON.parse("{"+$("#config").val()+"}");
		var cust_photo 	= $("#cust_photo").attr("src");
		$("#send_visit").after("<img src='files/images/loading-bars.gif' id='loader_img' style='margin-left:5px'>");
		$.ajax({
			url 	: chat_data,
			type	: "POST",
			data 	: {"direction":"send_visit","id_customer":vis_data.id_customer,"id_merchant":vis_data.id_merchant}, 
			success : function(response){
				if(conf.realtime == 1){
					result = JSON.parse(response);
					$.each(result,function(index,value){
						pushit("/note_visit_"+value,"1",container);
					})
					content 	= 
					"<tr style='border-bottom:dashed 1px #333'>"+
						"<td style='text-align:center'><img class='img-avatar' src='"+vis_data.cust_photo+"'></td>"+
						"<td>"+
							"<span class='label'>"+vis_data.wkt_visit+"</span><br>"+
							"<strong><a href='#'>"+vis_data.nm_customer+"</a></strong><br />"+
							"Baru saja berkunjung ke "+vis_data.nm_merchant+" "+
						"</td>"+
						"<td style='text-align:center'><img class='img-avatar' src='"+vis_data.merchant_logo+"'></td>"+
					"</tr>";
					$('#new_visit tr:first').after(content);
					$("#div_coin").empty();
					$("#loader_img").remove();
				}
			}
		})	
	})
});


