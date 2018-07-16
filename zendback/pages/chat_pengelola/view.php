<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.onwrite{
	padding:2px;
	margin:3px;
	font-size:11px;
}	
.pframe{
	-webkit-box-shadow: 0 1px 2px #999;
	-moz-box-shadow: 0 1px 2px #999;
	-ms-box-shadow: 0 1px 2px #999;
	-o-box-shadow: 0 1px 2px #999;
	box-shadow: 0 1px 2px #999;
	padding:4px; 
	margin-right:2px; 
	float:left; 
	text-align:center;
	background:#FFF;
}
.pframe label .code{
	font-size:2vmin;
}

.elm{
	-webkit-box-shadow: 0 1px 2px #999;
	-moz-box-shadow: 0 1px 2px #999;
	-ms-box-shadow: 0 1px 2px #999;
	-o-box-shadow: 0 1px 2px #999;
	box-shadow: 0 1px 2px #999;
	padding:0; 
}
</style>
<p class='alert alert-info' style="margin-top:10px;font-size:12px;">
    <span style="color:#900; font-weight:bold; ">Catatan :</span><br />
    Beranda ini adalah tempat menerima pesan dari pelanggan dan mengirim pesan ke forum aplikasi discoin pelanggan, dan setiap komunikasi yang terjadi akan di notifikasikan.
</p>

<div class="row-fluid">
	<div class="ibox float-e-margins">
		<?php 
            @$user_foto = $db->fob("USER_PHOTO","system_users_client","WHERE ID_USER = '".$_SESSION['uidkey']."'");
            if(empty($user_foto)){
                $user_foto = $dirhost."/files/images/no_image.jpg";
            }else{
                $user_foto = $dirhost."/files/images/users/".$user_foto;	
            }
        ?>
        <div class="col-md-6" >
        <input type='hidden' id='proses_page' value='<?php echo $ajax_dir."/proses.php"; ?>' />
        <input type="hidden" id="sender_info" value='"user_photo":"<?php echo @$user_foto; ?>","user_name":"<?php echo $_SESSION['username']; ?>","wkt_chat":"<?php echo substr($wktupdate,0,5); ?>","tgl_chat":"<?php echo $dtime->date2indodate($tglupdate); ?>"'>
            
                <div class="ibox-title">
                    <h4 style="margin-left:14px">Subjek Pesan</h4>
                </div>
                <div class="ibox-title" style="text-align:center;">
                    <i class="splashy-help pop-over" data-content="Subjek Pesan ini adalah untuk, menulis topik diskusi yang akan disebarkan, dan di bahas antara anda dan pelanggan anda." data-title="Menulis Subjek Pesan" data-placement="right" data-trigger="hover" data-original-title="" style="margin:-39px 0 0 2px; float:left"></i>         
                    <div class="ch-topic-add control-group">
                        <div class="input-append" style="padding:0 4px 4px 4px;">
                            <textarea class="col-md-11 ch-topic-input" id="ch-topic-input" placeholder="Subjek Pesan"></textarea>
                            <br clear="all"/>
                            <button type="button" class="btn ch-topic-send" id="ch-topic-send">Kirim Topic</button>
                            <input type="hidden" id="topic_list" value="<?php echo $ajax_dir; ?>/topic_list.php">
                        </div>
                    </div>
                </div>
                <span id="topic_load"></span>
                <div class="ibox-content"  style='height:506px; '>
                    <div class="ch-topic" > 
                        <?php 
                        while($dt_topic = $db->fetchNextObject($q_topic)){
                            @$tgl_chat		= $dtime->date2indodate($dt_topic->TGLUPDATE);
                            @$wkt_chat		= substr($dt_topic->WKTUPDATE,0,5);
                            @$jml_comment	= $db->recount("SELECT * FROM ".$tpref."chat WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$dt_topic->ID_CHAT_SUBJECT."'");
                            $remove_subject	= "";
							@$id_customer 	= $dt_topic->BY_ID_CUSTOMER;
							
                            if(!empty($dt_topic->BY_ID_CUSTOMER)){
                                $q_user_subject		= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$dt_topic->BY_ID_CUSTOMER."'"); 
                                $dt_user_subject	= $db->fetchNextObject($q_user_subject);
                                @$user_subject		= getmemberfoto($dt_user_subject->ID_CUSTOMER," style='width:50px'");
                                @$user_foto_subject	= $dt_user_subject->CUSTOMER_PHOTO;
                                @$user_name_subject	= $dt_user_subject->CUSTOMER_NAME;
                            }
                            if(empty($dt_topic->BY_ID_CUSTOMER) && !empty($dt_topic->BY_ID_USER)){
                                $q_user_subject		= $db->query("SELECT * FROM system_users_client WHERE ID_USER = '".$dt_topic->BY_ID_USER."'");
                                $dt_user_subject	= $db->fetchNextObject($q_user_subject);
                                @$user_subject		= getuserfoto($dt_topic->BY_ID_USER," style='width:50px'");
                                @$user_foto_subject	= $dt_user_subject->USER_PHOTO;
                                @$user_name_subject	= $dt_user_subject->USER_NAME;
                            }
							
                            if($dt_topic->BY_ID_USER == $_SESSION['uidkey'] || !empty($dt_topic->BY_ID_CUSTOMER)){
                            $remove_subject = '<button class="btn btn-mini removal ptip_sw" onclick="remove_chat(\'subject\',\''.$dt_topic->ID_CHAT_SUBJECT.'\')" title="Hapus Subjek Pesan" >
                                                    <i class="icon-trash"></i>
                                               </button>';
                            }
							
                            $views_id				= $db->fob("VIEWS_ID_USER",$tpref."chat_subject"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' AND ID_CHAT_SUBJECT='".$dt_topic->ID_CHAT_SUBJECT."'");
                            $ch_view_id		= substr_count($views_id,";".$_SESSION['uidkey'].";");
                            if($ch_view_id == 0){
                                $class = "ch-topic-item-new";	
                            }else{
                                $class = "ch-topic-item";	
                            }
                        ?> 
                        <div class="<?php echo $class; ?> clearfix cl_subject" style="cursor:pointer" id="subject_<?php echo $dt_topic->ID_CHAT_SUBJECT; ?>">
                            <div class='img-box'><?php echo $user_subject; ?></div>
                            <div class="ch-content">
                                <p class="ch-name">
                                    <strong><?php echo $id_customer." ".$user_name_subject; ?></strong>
                                    <span class="ch-time">
                                        <span class="<?php echo $dt_topic->CHAT_SECRECY; ?>">
                                            <?php echo $dt_topic->CHAT_SECRECY; ?>
                                        </span> : 
                                        <?php echo $wkt_chat; ?>
                                    </span>
                                </p>
                                <?php echo $dt_topic->CHAT_SUBJECT; ?>
                                <br />
                                <small class='code'> <?php echo $tgl_chat; ?> : <?php echo $jml_comment; ?> Komentar </small>
                                <button class='btn btn-mini ptip_sw' onclick="show_chat('<?php echo $dt_topic->ID_CHAT_SUBJECT; ?>','<?php echo @$id_customer; ?>')" title="Pilih Subjek Pesan" style="float:right; margin-left:4px">
                                    <i class="icon-search"></i>
                                </button>
                                <?php echo @$remove_subject; ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
        </div>

        <div class="col-md-6" >
            <div class="ibox-title">
                <h4 style="margin-left:14px">Forum Diskusi</h4>
            </div>
            <i class="splashy-help pop-over" data-content="Forum Chat ini adalah untuk, mendiskusikan subjek pesan yang di pilih" data-title="Forum Diskusi" data-placement="right" data-trigger="hover" data-original-title="" style="margin:-27px 0 0 2px; float:left"></i>         
            <div class="ibox-content"  style='height:582px; '>
                <div class="ch-messages" id="ch-messages" style="height:100%">  
                <?php 
                    if(empty($last_topic)){ $last_topic = '0'; }
                    while($dt_chat = $db->fetchNextObject($q_chat)){
                        $remove_subject		= "";
						$id_customer 		= $dt_chat->ID_CUSTOMER;
                        if(!empty($dt_chat->ID_CUSTOMER)){
                            $q_user_chat	= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$dt_chat->ID_CUSTOMER."'"); 
                            $dt_user_chat	= $db->fetchNextObject($q_user_chat);
                            $user_chat		= getmemberfoto($dt_chat->ID_CUSTOMER," style='width:50px;' ");
                            $user_foto_chat	= $dt_user_chat->CUSTOMER_PHOTO;
                            $user_name_chat	= $dt_user_chat->CUSTOMER_NAME;
                        }
                        if(!empty($dt_chat->ID_USER)){
                            $q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER = '".$dt_chat->ID_USER."'");
                            $dt_user_chat	= $db->fetchNextObject($q_user_chat);
                            $user_chat		= getuserfoto($dt_chat->ID_USER," style='width:50px' ");
                            $user_foto_chat	= $dt_user_chat->USER_PHOTO;
                            $user_name_chat	= $dt_user_chat->USER_NAME;
                        }
                        if($dt_chat->ID_USER == $_SESSION['uidkey'] || !empty($dt_chat->ID_CUSTOMER)){
                        $remove_subject = '<button class="btn btn-mini removal ptip_sw" onclick="remove_chat(\'chat\',\''.$dt_chat->ID_CHAT.'\')" title="Hapus Pesan">
                                                <i class="icon-trash"></i>
                                           </button>';
                        }
                        @$tgl_chat		= $dtime->date2indodate($dt_chat->TGLUPDATE);
                        @$wkt_chat		= substr($dt_chat->WKTUPDATE,0,5);
                    ?> 
                    <div class="ch-message-item clearfix" id="chat_<?php echo $dt_chat->ID_CHAT; ?>">
                        <div class='img-box'><?php echo $user_chat; ?></div>
                        <div class="ch-content">
                            <p class="ch-name">
                                <strong><?php echo $user_name_chat; ?></strong>
                                <span class="ch-time"><?php echo $tgl_chat." : ".$wkt_chat; ?></span>
                            </p>
                            <?php echo $dt_chat->CHAT_MESSAGE; ?>
                            <br />
                            <?php echo @$remove_subject; ?>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($last_topic > 0){?>
                    <script language="javascript">
                        var conf 		= JSON.parse("{"+$("#config").val()+"}");
                        tulcom.subscribe("/chat_merchant_<?php echo $last_topic; ?>", function(message) {
                            var container 	= JSON.parse(message.nick);
                            user_id			= container.uidkey; 
                            if(message.msg != ""){
                                id_chat		= container.id_chat; 
                                if($("#chat_"+id_chat).length == 0){
                                    user_photo	= container.user_photo; 
                                    user_name	= container.user_name; 
                                    wkt_chat	= container.wkt_chat;
                                    tgl_chat	= container.tgl_chat;
                                    response 	= 
                                    "<div class='ch-message-item clearfix' id='chat_"+id_chat+"'>"+
                                        "<div class='img-box'><img src='"+user_photo+"' style='width:50px'></div>"
                                        +"<div class='ch-content'>"
                                            +"<p class='ch-name'>"
                                                +"<strong>"+user_name+" nah</strong>"
                                                +"<span class='ch-time'>"+tgl_chat+" : "+wkt_chat+"</span>"
                                            +"</p>"
                                            +message.msg
                                            +"<br>";
										if(conf.uidkey == user_id){
									 response 	+= 
                                            "<button class='btn btn-mini removal ptip_sw' onclick='remove_chat(\"chat\",\"<?php echo $last_topic; ?>\")' title='Hapus Pesan'>"+
                                                "<i class='icon-trash'></i>"+
                                             "</button>";
										}
									 response 	+= 
                                        "</div>"
                                    +"</div>";
                                    $('.ch-messages').append(response);
                                    $(".ch-messages").animate({scrollTop: $(".ch-messages")[0].scrollHeight }, 600);
                                    if($("#cust_"+user_id).length > 0){
                                        $("#cust_"+user_id).remove();
                                    }
                                }
                            }
                        });
                        tulcom.subscribe("/write_merchant_<?php echo $last_topic; ?>", function(message) {
                            var container 	= JSON.parse(message.nick);
                            onwrite 		= message.msg;
							if(onwrite == "2" && container.id_cust != conf.uidkey){
                                id_cust	= container.id_cust;
                                name	= container.name;
                                if($("#cust_"+id_cust).length == 0){
                                    content = "<div id='cust_"+id_cust+"' class='onwrite'>"+name+" Sedang mengetik...</div>";
                                    $("#write_status").html(content);
                                }
                            }
                            if(onwrite == "1"){
                                if($("#cust_"+id_cust).length > 0){
                                    $("#cust_"+id_cust).remove();
                                }
                            }
                        });
                    </script>
                    <?php } ?>
                </div>
            </div>
            <div>
                <span id="write_status"></span>
            </div>
            <div class="ibox-title">
                <div class="ch-message-add control-group">
                    <div class="input-append">
                        <span id="chat_load"></span>
                        <input type="text" class="col-md-9 ch-message-input" id="ch-message-input" placeholder="Text Pesan" onkeyup="onWrite('<?php echo $_SESSION['uidkey']; ?>')" data-id="<?php echo $_SESSION['uidkey']; ?>">
                        <button type="button" class="btn ch-message-send" id="ch-message-send" value='<?php echo @$_SESSION['cidkey']; ?>'>Kirim Chat</button>
                        <input type="hidden" id="chat_list" value="<?php echo $ajax_dir; ?>/chat_list.php" />
                        <input type='hidden' id="id_subject" value='<?php echo @$last_topic; ?>' />
                        <input type='hidden' id="id_customer" value='<?php echo @$id_customer; ?>' />
                        <input type='hidden' id="onwrite" value='' />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
