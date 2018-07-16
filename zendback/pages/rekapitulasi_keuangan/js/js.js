$(document).ready(function(){		
	url 		= $("#data_page").val();
	$('#periode').on('change',function(){
		periode = $(this).val();
		$("#div_periode").html("<img src='files/images/loading-bars.gif' style='margin-left:4px'>");
		$.ajax({
			url		: url,
			type	: "POST",
			data	: {"direction":"periode","periode":periode},
			success	: function(response){
				$("#div_periode").html(response);
			}
		})
	})
});

function get_report(src,target,periode,links){
	conf 		= JSON.parse("{"+$("#config").val()+"}");
	$("#div_detail").html("");
	inout		= $("#alur").val();
	$("#"+src).addClass("col-md-4");
	$("#"+target).addClass("span8");
	$("#"+target).html("<div style='text-align:center; background:#FFFFFF'><img src='files/images/loader.gif'></div>");
	data		= $("#"+target).attr("data-info"); 
	$.ajax({
		url		: data,
		type	: "POST",
		data	: {"page":conf.page,"periode":periode,"in_out":inout,"links":links},
		success	: function(response){
			$("#"+target).html(response);
		}
	})
}

function get_detail(src,target,parent_id){
	conf 		= JSON.parse("{"+$("#config").val()+"}");
	periode 	= $("#periode").val();
	links		= $("#links_"+parent_id).val();
	bln 		= "";
	thn 		= "";
	thn2 		= "";
	data		= JSON.parse("{"+links+"}");
	bln 		= data.bln;
	thn 		= data.thn;
	thn2 		= data.thn2;
	$("#"+src).addClass("col-md-4");
	$("#"+target).addClass("col-md-4");
	$("#"+target).html("<div style='text-align:center; background:#FFFFFF'><img src='files/images/loader.gif'></div>");
	data		= $("#"+target).attr("data-info"); 
	$.ajax({
		url		: data,
		type	: "POST",
		data	: {"page":conf.page,"periode":periode,"parent_id":parent_id,"bln":bln,"thn":thn,"thn2":thn2},
		success	: function(response){
			$("#"+target).html(response);
		}
	})
}