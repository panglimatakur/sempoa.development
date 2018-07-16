document.write('<script type="text/javascript" src="modules/laporan_distribusi/js/js.js"></script>');
$(document).ready(function(){		
	$("#insert_multi_button").on("click",function(){		
		var conf 			= JSON.parse("{"+$("#config").val()+"}");
		if(conf.realtime == 1){
			var jumlah_multi	= $("#jumlah_multi").val();
			var tgl_kirim  		= $("#tgl_kirim_multi").val();
			var id_target 		= $("#id_dest_multi").val();
			var status			= $("#status_send_multi").val();
			if(jumlah_multi != "" && tgl_kirim != "" && id_target != "" && status != ""){
				container   		= '{"cidkey":"'+conf.cidkey+'","for_id":"'+id_target+'"}';
				pushit("/laporan_distribusi",1,container);
			}
		}
		$("#formID").submit();
	})
}); 

function cancel_pick(id_product){
	var conf 			= JSON.parse("{"+$("#config").val()+"}");
	var jumlah_single	= $("#new_jumlah_"+id_product).val();
	stock				= $("#stock_label_"+id_product).html();

	var jumlah_multi	= $("#jumlah_multi").val();
	
	if(jumlah_multi == "") 	{ jumlah_multi 	= 0; 	}
	
	if($("#product_"+id_product).length > 0){
					
		jumlah_multi	= +jumlah_multi - +jumlah_single;
		$("#jumlah_multi").val(jumlah_multi);
				
		$(".data_"+id_product).fadeOut(300);
		$(".data_"+id_product).remove();
	}
	
}

function pic_item(id_product){
	var conf 			= JSON.parse("{"+$("#config").val()+"}");
	var jumlah_single	= $("#jumlah_"+id_product).val();
	stock				= $("#stock_label_"+id_product).html();
	
	var jumlah_multi	= $("#jumlah_multi").val();
	var value 			= $("#value_"+id_product).val();	
	var data			= JSON.parse("{"+value+"}");
	
	if(jumlah_multi == "") 	{ jumlah_multi 	= 0; 	}

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
			var container		= " <tr class='data_"+id_product+"'>"+
									"<td colspan='6'><b style='color:#CC0000'>"+data.code+"</b> - "+data.name+"</td>"+
								"</tr>"+
								"<tr class='data_"+id_product+"'>"+
									"<td style='text-align:center;'>"+
										"<a href='"+photo_big+"' class='fancybox'><img src='"+photo+"' class='photo' style='width:90%; margin-right:5px'></a>"+
									"</td>"+
									"<td style='text-align:center'>"+jumlah_single+" "+data.units+"</td>"+
									"<td style='text-align:center'>"+
										"<a href='javascript:void()' onclick='cancel_pick(\""+id_product+"\")' class='btn btn-mini'>"+
										"<i class='icon-trash'></i>"+
										"</a>"+
										"<input type='hidden' id='product_"+id_product+"' 	 name='id_product[]' value='"+id_product+"'>"+
										"<input type='hidden' id='new_jumlah_"+id_product+"' name='jumlah[]' 	 value='"+jumlah_single+"'>"+
									"</td>"+
								"</tr>";
			
			$("#table_list tbody tr:first").before(container);
			
			jumlah_multi	= +jumlah_multi + +jumlah_single;
			$("#jumlah_multi").val(jumlah_multi);			
			
		}else{
				$("#jumlah_form_"+id_product).css({"border":"1px solid #FF9F9F","background":"#FFD9D9"}).attr("placeholder","Isi Jumlah Pengiriman Produk");
		}
	}else{
		$("#item_"+id_product+" td").addClass("alert alert-error");
		$("#jumlah_"+id_product).attr("readonly","readonly");
	}
}



