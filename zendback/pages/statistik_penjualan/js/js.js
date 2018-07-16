$(document).ready(function(){		
	data 		= $("#data_page").val();
	$('#periode').on('change',function(){
		periode = $(this).val();
		$("#div_periode").html("<img src='files/images/loading-bars.gif' style='margin-left:4px'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"periode","periode":periode},
			success	: function(response){
				$("#div_periode").html(response);
			}
		})
	})
	
	$("#id_type_report").on("change",function(){
		data_page 	= $("#data_page").val();
		type		= $(this).val();
		if(type != ""){
			$("#div_kategori_report").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
			$.ajax({
				url		: data_page,
				type	: "POST",
				data 	: {"display":"kategori_report","id_type_report":type},
				success : function(response){
					$("#div_kategori_report").html(response);	
				}
			})
		}else{
			$("#div_kategori").html("");	
		}
	})
});

function open_location(name){
	loc		 		= $("#locations").val();
	var location 	= JSON.parse("{"+loc+"}");
	if(name == "province"){
		$("#location_open").hide();
		$("#location_close").show();
		$("#propinsi").show();
	}
	if(name == "city"){
		$("#div_kota").html("<img src='files/images/loading-bars.gif'>");
		if($("#div_kecamatan").length){ $("#div_kecamatan").html(""); }
		if($("#div_kelurahan").length){ $("#div_kelurahan").html(""); }

		propinsi = $("#propinsi").val();
		$.ajax({
			url 	: location.city,
			data	: {"propinsi":propinsi},
			success : function(response){
				$("#div_kota").html(response);
			}
		});
	}
	if(name == "district"){
		$("#div_kecamatan").html("<img src='files/images/loading-bars.gif'>");
		if($("#div_kelurahan").length){ $("#div_kelurahan").html(""); }
		
		kota = $("#kota").val();
		$.ajax({
			url 	: location.district,
			data	: {"kota":kota},
			success : function(response){
				$("#div_kecamatan").html(response);
			}
		});
	}
	if(name == "subdistrict"){
		$("#div_kelurahan").html("<img src='files/images/loading-bars.gif'>");
		kecamatan = $("#kecamatan").val();
		$.ajax({
			url 	: location.subdistrict,
			data	: {"kecamatan":kecamatan},
			success : function(response){
				$("#div_kelurahan").html(response);
			}
		});
	}
}

function close_location(){
	$("#location_open").show();
	$("#propinsi").val("");
	$("#propinsi").hide();
	if($("#div_kota").length)		{ $("#div_kota").html(""); 		}
	if($("#div_kecamatan").length)	{ $("#div_kecamatan").html(""); }
	if($("#div_kelurahan").length){	 $("#div_kelurahan").html(""); }
	$("#location_close").hide();
}
