var apiDir			= $("#apiDir").val();
var id_merchant	 	= $("#id_merchant").val();
var id_customer	 	= $("#id_user").val();

function open_page(id_merchant){
	$("#page_content").html("<div class='page_loader'><img src='template/img/bars.svg'></div>");
	$.ajax({
		url 	: apiDir+"/merchant/model.php",
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
				$(".navbar").css("background-color",result.colour_1);
			}
		},
		error: function (xhr, status, errorThrown) {
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	})	
}

function open_spec(location,id_product){
	id_merchant	 	= $("#id_merchant").val();
	$("#id_product").val(id_product);
	$('#shopping_cart .modal-body').load(location,function(result){
		$('#shopping_cart').modal({show:true});
		$(this).html("<div class='page_loader' style='margin-top:-6px'><img src='template/img/bars.svg'></div>");
		$.ajax({
			url 	: apiDir+"/cart/model.php",
			type 	: "POST",
			data 	: {"direction":"load","id_merchant":id_merchant,"id_customer":id_customer,"id_product":id_product,"sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success:function(result){
				$('#shopping_cart .modal-body').html(result.content);
			},
			error: function (xhr, status, errorThrown) {
				//The message added to Response object in Controller can be retrieved as following.
				bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
					location.href = "index.html";
				});
			}
		})	

		
	});	
}
function open_detail_product(location,id_merchant,id_product){
	$('#detail .modal-body').load(location,function(result){
		$('#detail').modal({show:true});
		$(this).html("<div class='page_loader' style='margin-top:-6px'><img src='template/img/bars.svg'></div>");
		$.ajax({
			url 	: apiDir+"/merchant/ajax/product_detail.php",
			type 	: "POST",
			data 	: {"direction":"load","id_merchant":id_merchant,"id_customer":id_customer,"id_product":id_product,"sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success:function(result){
				$('#detail .modal-title').html("<i class='icsw16-balloons'></i> Detail Produk");
				$('#detail .modal-body').html(result.content);
			},
			error: function (xhr, status, errorThrown) {
				//The message added to Response object in Controller can be retrieved as following.
				bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
					location.href = "index.html";
				});
			}
		})	
	});	
}

function open_member(location,id_member){
	$('#detail .modal-body').load(location,function(result){
		$('#detail').modal({show:true});
		$(this).html("<div class='page_loader' style='margin-top:-6px'><img src='template/img/bars.svg'></div>");
		$.ajax({
			url 	: apiDir+"/merchant/ajax/member_detail.php",
			type 	: "POST",
			data 	: {"direction":"load","id_member":id_member,"id_merchant":id_merchant,"id_customer":id_customer,"sempoakey":"99765"},
			dataType: "jsonp",
			jsonp	: "mycallback",
			success:function(result){
				$('#detail .modal-title').html("<i class='icsw16-v-card-2'></i> Info Member");
				$('#detail .modal-body').html(result.content);
			},
			error: function (xhr, status, errorThrown) {
				//The message added to Response object in Controller can be retrieved as following.
				bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
					location.href = "index.html";
				});
			}
		})	
	});	
}
function next_product(id_client){ 
	$('div#lastPostsLoader').html('<div style="text-align:center; margin:10px"><img src="template/img/loader_v.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	$.ajax({
		url 	: apiDir+"/merchant/model.php",
		type	: "POST",
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"next_product","id_merchant":id_client,"id_customer":id_customer,"lastID":lastId,"sempoakey":"99765"},
		success : function(result){
			//alert(result.content);
			data = $.trim(result.content);
			if (data != "") {
				$("#gallery_product .gallery li.wrdLatest:last").after(data);				
			}
			if(result.jumlah < 10){ $('div#lastPostsLoader, #footer_product').remove(); }
			$('div#lastPostsLoader').empty();
			
		},
		error: function (xhr, status, errorThrown) {
			//The message added to Response object in Controller can be retrieved as following.
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
			
		}
	});
}

function next_member(id_client){ 
	$('div#lastMemberLoader').html('<div style="text-align:center; margin:10px"><img src="template/img/loader_v.gif"><br>Mengambil Data...</div>');
	lastId			= $(".memberLatest:last").attr("data-info");
	$.ajax({
		url 	: apiDir+"/merchant/model.php",
		type	: "POST",
        dataType: "jsonp",
        jsonp	: "mycallback",
		data	: {"direction":"next_member","id_merchant":id_client,"id_customer":id_customer,"lastID":lastId,"sempoakey":"99765"},
		success : function(result){
			//alert(result.content);
			data = $.trim(result.content);
			if (data != "") {
				$("#gallery_member .gallery div.memberLatest:last").after(data);				
			}else{ $("#footer_member").empty(); }

			if($.trim(result.jumlah) < 10){ alert("yesyi"); $('div#lastMemberLoader, #footer_member').remove(); }
			$('div#lastMemberLoader').empty();
			
		},
		error: function (xhr, status, errorThrown) {
			//The message added to Response object in Controller can be retrieved as following.
			bootbox.alert("Whopss..:D, Maaf, Session ini telah berakhir, silahkan login kembali",function(){
				location.href = "index.html";
			});
		}
	});
};


$(document).ready(function(){
	id_merchant = getUrlParameter("id_merchant");
	if(id_merchant == undefined){ 
		id_merchant	 	= $("#id_merchant").val();		
	}
	open_page(id_merchant);
})