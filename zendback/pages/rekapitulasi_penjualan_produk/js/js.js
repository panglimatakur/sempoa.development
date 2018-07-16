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
});

function print_r(){
	periode 	= $("#periode").val();
	bln 		= '';
	thn 		= '';
	thn2 		= '';

	if($("#bln").length > 0)	{ bln 		= $("#bln").val();	}
	if($("#thn").length > 0)	{ thn 		= $("#thn").val();	}
	if($("#thn2").length > 0)	{ thn2 		= $("#thn2").val();	}
	
	show_data		= $("#show_data").val();
	all_data		= $("#all").is(":checked");

	print_container = "<input type='hidden' name='periode' value='"+periode+"' />"+
	"<input type='hidden' name='bln' value='"+bln+"' />"+
	"<input type='hidden' name='thn' value='"+thn+"' />"+
	"<input type='hidden' name='thn2' value='"+thn2+"' />";

	$("#print_container").html(print_container);
	$("#form_print").submit();
	$.fancybox.close();
}