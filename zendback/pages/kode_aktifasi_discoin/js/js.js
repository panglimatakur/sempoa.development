var data_page 		= $("#data_page").val();
var proses_page 	= $("#proses_page").val();

$(document).ready(function(){
	if($('.i-switch').length) {
		$(".i-switch").bootstrapSwitch({
			on: 'Generator',
			off: 'Pencarian',
			width:'100%',
			onClass: 'danger',
			offClass: 'default'
		}).on("change",function(){
			checked_status = ($(this).prop("checked"));
			if(checked_status == true){
				$(".generate").show();
				$(".filter").hide();
			}else{
				$(".generate").hide();
				$(".filter").show();
			}
		})
	}
	
	$(".merchant_group").chosen({
		create_option: true,
		width: "100%",
		persistent_create_option: true,
		create_option_text: 'add',
	}).change(function() {
		choosen_val 	= $(this).chosen().val();
		choosen_target 	= $(this).chosen().attr("data-target");
		$("#"+choosen_target).val(choosen_val)
	});
	
	$("#select_rows").on("click",function(){
		ch = $(this).prop("checked");
		if(ch == true){
			$(".row_sel").prop("checked",true).addClass("checked");
		}else{
			$(".row_sel").prop("checked",false).removeClass("checked");
		}
	})
	$("#select_rows_2").on("click",function(){
		ch = $("#select_rows").prop("checked");
		if(ch == true){
			$("#select_rows").prop("checked",false);	
			$(".row_sel").prop("checked",false).removeClass("checked");
		}else{
			$("#select_rows").prop("checked",true);	
			$(".row_sel").prop("checked",true).addClass("checked");
		}
	})
	$(".row_sel").on("change",function(){
		check_status = $(this).prop("checked");
		if(check_status == true)	{ $(this).addClass("checked"); 		}
		if(check_status == false)	{ $(this).removeClass("checked");  }
	})
	$("#show_merchant").on("click",function(){
		num_ch = $(".row_sel.checked").length;
		if(num_ch > 0){
			$("#merchant_list").modal("show");
			$.ajax({
				url 	: data_page,
				type	: "POST",
				data	: {"direction":"get_merchant"},
				success : function(data){
					$("#merchant_list .modal-body").html(data);
					$(".q_merchant").chosen({
						create_option: true,
						width: "100%",
						persistent_create_option: true,
						create_option_text: 'add',
					}).change(function() {
						choosen_val 	= $(this).chosen().val();
						choosen_target 	= $(this).chosen().attr("data-target");
						$("#"+choosen_target).val(choosen_val)
					});
				}
			});
		}else{
			bootbox.alert("Tidak ada satupun kode aktifasi yang akan di aktifkan, silahkan pilih satu atau lebih daftar kode aktifasi dibawah");	
		}
	})
	
	$("#activate").on("click",function(){
		id_merchant		= $("#id_merchant").val();
		jml_code 		= $(".row_sel.checked").length;
		code_numbers 	= [];
		$(".row_sel.checked").each(function(){
			code_number = $(this).val();
			code_numbers.push(code_number);
			
		})
		$("#load_activate").html("<i class='fa fa-refresh fa-spin fa-fw'></i>");
		$.ajax({
			url 	: proses_page,
			type	: "POST",
			data	: {"direction":"activate","id_merchant":id_merchant,"code_numbers":code_numbers},
			success : function(data){
				$("#load_activate").empty();
				$("#merchant_list").modal("hide");
				bootbox.alert("<b class='text-danger'>"+jml_code+"</b> Kode aktifasi berhasil di aktifkan untuk merchant <b class='text-info'>"+data+"</b>",function(){
					location.reload();
				});
			}
		});
	})
	
})
$("#print_r").on("click",function(){
	var divContents = $(".print-table").html();
	document.body.innerHTML = 
	"<html><head><title></title></head><body>" + 
	divContents + "</body>";
	window.print();
})

$("#print_excel").on("click",function(e){
	var d = new Date();
	$("#tbl_data").table2excel({
		exclude: ".noExl",
		name: "Excel Document Name",
		filename: "coin_list_"+d,
		fileext: ".xls",
		exclude_img: true,
		exclude_links: true,
		exclude_inputs: true
	});
})

function lastPostFunc(){ 
	id_client_form 	= $("#id_client_form").val();
	status	 		= $("#status").val();
	$('div#lastPostsLoader').html('<div style="text-align:center;margin:10px"><img src="files/images/loading-bars.gif"><br>Mengambil Data...</div>');
	lastId			= $(".wrdLatest:last").attr("data-info");
	label_no		= $(".label_no:last").html();
	$.ajax({
		url 	: data_page,
		type	: "POST",
		data	: {"label_no":label_no,"lastID":lastId,"id_client_form":id_client_form,"status":status,"direction":"list_report"},
		success : function(data){
			if (data != "") {
				$("#tbl_data tbody tr:last").after(data);
			}
			if($.trim(data) == ""){
				$('div#lastPostsLoader').remove();
				$(".next_button").remove();
			}
			$('div#lastPostsLoader').empty();
			
		}
	});
};  
