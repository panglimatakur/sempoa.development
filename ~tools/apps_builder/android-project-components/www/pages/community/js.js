var apiDir		= $("#apiDir").val();
var id_merchant	 	= $("#id_merchant").val();
var id_customer	 	= $("#id_user").val();

function open_page(){
	$(".w-box-header").css("background-color",getCookie("color_2"));
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");	
 	$.ajax({
		url 	: apiDir+"/community/model.php",
		type 	: "POST",
		data 	: {"direction":"load","id_merchant":id_merchant,"id_customer":id_customer,"sempoakey":"99765"},
        dataType: "jsonp",
        jsonp	: "mycallback",
		success:function(result){
			if(result.io_log != "" && result.msg_log != ""){
				bootbox.alert(result.msg_log,function(){
					location.href = "index.html";
				});
			}else{
				$("#page_content").html(result.content);
			//$(".w-box-header").css({"background-color":getCookie("color_1"),"padding-top":"2px"});
			}
		}, 
		function (error) {
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	})	
}

function enter_merchant(id_community,id_merchant){
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");
	$("#id_merchant").val(id_merchant);
	//var ajax 		= "pages/merchant/default.html?page=merchant";
	//$("#main_page").load(ajax,function(){  })
	location.href = "default.html?page=merchant&id_merchant="+id_merchant;
}

function lastPostFunc(){ 
	$('div#lastPostsLoader').html('<div style="text-align:center;"><img src="template/img/loader_v.gif"><br>Mengambil Data...</div>');
	lastId		= $(".wrdLatest:last").attr("data-info");
	id_com		= $("#id_com").val();	
	$.ajax({
		url 	: apiDir+"/community/model.php",
		type	: "POST",
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"next_community","lastID":lastId,"id_com":id_com,"id_merchant":id_merchant,"id_customer":id_customer,},
		success : function(result){
			//alert(result.content);
			data = $.trim(result.content);
			if (data != "") {
				$("#lastPostsLoader").before(data);
			}
			if(lastId == ""){
				$('div#lastPostsLoader').remove();
			}
			$('div#lastPostsLoader').empty();
			
		}, 
		function (error) {
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	});
}

$(document).ready(function(){
	open_page();
})