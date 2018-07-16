id_customer = getCookie("sidkey");

adapter.subscribe("/to_chat_customer_"+id_customer, function(data){
    num_chat = $("#subject_"+data.id_chat).length;
    chat_content 		=
    '<div class="chat-item">'+
        '<div class="col-xs-10 col-sm-10 col-md-10" id="subject_'+data.id_chat+'" >'+
            '<div class="bubble bubble-right">'+
                '<div class="ch-content">'+
                    '<small style="color:#C03F3F"><b>'+data.user_name+'</b></small><br>'+
                    data.msg+
                '</div>'+
                '<div class="ch-time" style="margin-top:-6px">'+data.wkt_chat+'</div>'+
            '</div>'+
        '</div>'+
        '<div class="col-xs-2 col-sm-2 col-md-2 text-center" style="padding:0 0 0 20px">'+
            '<div class="img-circle img-box">'+
                '<img src="'+data.user_photo+'"  width="100%">'+
            '</div>'+
        '</div>'+
        '<div class="clearfix"></div>'+
    '</div>';
    $("#onwrite").val("");	
    $("#write_status").empty();
    $(".chat-item:last").after(chat_content);
    $("#page_content").animate({scrollTop: $("#page_content")[0].scrollHeight }, 1600);
    $(".alert-chat").remove();
    
});

adapter.subscribe("/write_chat_customer_"+id_customer, function(data) {
    if(data.flag == "2"){
        $("#write_status").html("<div style='padding:4px; margin:5px;'>"+data.name+" sedang menulis pesan....</div>");
    }else{
        $("#write_status").empty();
    }
});
