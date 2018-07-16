$(document).ready(function(){
	$('.inner-content-div').slimScroll({height: '500px'});
	if($('#sb_off').length) {
		$("#sb_off").bootstrapSwitch({
			on: 'Aktif',
			off: 'Non Aktif',
			size: 'sm',
			onClass: 'primary',
			offClass: 'default'
		});
	}
})
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
	bootbox.confirm("Dengan menghapus data kategori ini, data produk yang sudah di daftarkan sebelumnya akan kehilangan relasi data kategori produk/bahan, Anda yakin menghapus data kategori Ini? yang juga akan menghapus sub kategori di bawahnya",function(confirmed){
		if(confirmed == true){
			proses_page = $("#proses_page").val();
			$.ajax({
				url		: proses_page,
				type	: "POST",
				data 	: {"direction":"delete","no":id},
				success	: function(response){
					$("#li_"+id).fadeOut(300);	
				}
			})
		}
	})
}

function getcontenttype(add,target){
	ctype = $("#is_folder").val();
	if(ctype == 2){
		$.get(add+"&refresh=true",function(response){
			 $("#"+target).html(response);
		});
	}
	else{
	document.getElementById("divctype").innerHTML="";
	}
}

