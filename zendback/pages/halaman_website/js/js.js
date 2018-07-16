$(document).ready(function(){
	$("#judul").on("keyup",function(){
		var length = $(this).val().length;
		$("#counter").html(length);
	})
	
	$('.inner-content-div').slimScroll({height: '400px'});
	$("#sb_off").bootstrapSwitch({
		on: 'Aktif',
		off: 'Non Aktif',
		size: 'sm',
		onClass: 'primary',
		offClass: 'default'
	});
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

