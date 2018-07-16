$(document).ready(function(){
	$(".countext").on("keyup",function() {
		var len = $(this).val().length;
		var id 	= $(this).attr("data-id");
		var jml = $(this).attr("data-count");
		var new_count = +jml - +len;
		$('#'+id).html(new_count);
		if (new_count < 0) {
		  $(this).css({"background-color":"#FEE0FE"});;
		}else{
		  $(this).css({"background-color":"#FFF"});;
		}
	})
	
	$('#propinsi').on('change',function(){
		data 		= $("#data_page").val();
		propinsi 	= $(this).val();
		$("#div_kota").html("<img src='files/images/loading-bars.gif' style='margin-left:4%'>");
		$.ajax({
			url		: data,
			type	: "POST",
			data	: {"direction":"get_city","propinsi":propinsi},
			success	: function(response){
				$("#div_kota").html(response);
			}
		})
	})
	
	$("#tlp").on("keyup",function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
	$("#kontak").on("keyup",function(){
		this.value = this.value.replace(/[^0-9\.]/g,'');
	})
});

function save_map(){
	lat 		= $("#lat").val();
	lng 		= $("#lng").val();
	proses_page	= $("#proses_page").val();
	$.ajax({
		url  : proses_page,
		type : "POST",
		data : {"direction":"save_map","lat":lat,"lng":lng}, 
		success: function(response){
			$("#map_msg").html("<div class='alert alert-success'>Peta lokasi berhasil disimpan</div>");
			//$("#myMapModal").modal("hide");
		}
	})
}


