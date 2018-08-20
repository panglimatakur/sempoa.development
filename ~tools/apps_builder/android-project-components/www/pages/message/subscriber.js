id_user = $("#id_user").val();

adapter.subscribe("/to_chat_customer_"+id_user, function(datas) {
    alert(datas);
    var container 	= JSON.parse(datas);
    msg  = container.msg;
    time = container.time;
    

});