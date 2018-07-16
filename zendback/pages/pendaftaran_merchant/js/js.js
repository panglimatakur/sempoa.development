$(document).ready(function(){	
	$('.inner-content-div').slimScroll({height: '922px'});
	if($('.i-switch').length) {
		$(".i-switch").bootstrapSwitch({
			on: 'Ya',
			off: 'Tidak',
			size: 'sm',
			onClass: 'primary',
			offClass: 'default'
		});
	}
	
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
	$("#nama").on("blur",function(){
		client_name = $(this).val();
		direction	= $("#direction").val();
		if(direction == "save"){
			id_client = $("#no").val()
		}else{
			id_client	= "";
		}
		$("#app_load").html("<img src='files/images/loading-bars.gif'>");
		proses_page = $("#proses_page").val();
		$.ajax({
			url		: proses_page,
			type	: "POST",
			data 	: {"direction":"check_app","no":id_client,"client_name":client_name,"form_direction":direction},
			success	: function(response){
				result = JSON.parse(response);
				$("#app").val(result.nama_app);
				$("#app_load").empty();	
			}
		})
	})
});
function getparent(id,target){
	add 	= $("#parent_page").val();
	$("#"+target).html("<img src='files/images/loading-bars.gif'>");
	$.get(add+"?parent_id="+id,function(response){
		 $("#"+target).html(response);
	});
	
}

function resetchild(){
	$("#divparent_id").html("");
	$("#newlink").html("");
}

function delete_link(id){
	bootbox.confirm("Anda yakin menghapus Link Ini? Karena juga akan menghapus data link anak di bawahnya",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(){
					$("#li_"+id).fadeOut(500);	
				}
			})
		}
	})
}
