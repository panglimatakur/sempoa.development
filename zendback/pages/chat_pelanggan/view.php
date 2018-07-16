<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.onwrite{
	padding:2px;
	margin:3px;
	font-size:11px;
}
.chat-user{ cursor:pointer; }
.chat-avatar{
	border:1px solid #FFF;
	-webkit-border-radius:40px;
	-moz-border-radius:40px;
	border-radius:40px;
}
.chat-user-name a{ font-size:11px; }
.chat-avatar img,.message-avatar img{ width:100%; }
.ch-item-new{ background:#F5F5F5;}
.removal{
	position:absolute;
	top:0;
	right:0;
	margin-right:5px;
}
.ibox-content-clean {
    background-color: #ffffff;
    color: inherit;
    padding: 7px 9px 8px 13px;
    border-color: #e7eaec;
    border-image: none;
    border-width: 1px 0px;
}
.chat-discussion{min-height:440px;}
.no-msg-bg{
	background:url("<?php echo $dirhost; ?>/files/images/chat-bg.png") no-repeat;
	background-size:100%;
	opacity:0.4;
	filter:alpha(opacity=40);}
</style>
<div class="col-md-12">
    <?php 
        @$user_foto = $db->fob("USER_PHOTO","system_users_client","WHERE ID_USER = '".$_SESSION['uidkey']."'");
        if(empty($user_foto)){
            $user_foto = $dirhost."/files/images/no_image.jpg";
        }else{
            $user_foto = $dirhost."/files/images/users/".$user_foto;	
        }
    ?>
    <input type='hidden' id='proses_page' value='<?php echo $ajax_dir."/proses.php"; ?>' />
    <div class="ibox chat-view">
        <div class="ibox-title">
            <small class="pull-right text-muted">
            	<?php if(!empty($last_tgl)){ ?> Chat Terakhir :  <?php echo @$last_tgl." - ".$last_time;  }?>
            </small>
            <a href="javascript:void()" onclick="test()">Chat Pelanggan</a>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-9 ch-messages">
                	<span id="chat_target"><?php echo $last_chat_target; ?></span>
                    <div class="chat-discussion <?php echo @$no_msg_bg; ?>">
                        <span id="ch-messages-loader"></span>
                        <div id="chat-list">
    
							<?php include $call->inc($ajax_dir,"chat_list.php"); ?>  
                                                  
                            
                        </div>
					</div>
                </div>
                <div class="col-md-3">
                    <div class="chat-users">
                        <div class="users-list">
							<?php 
                                while($dt_cust_chat = $db->fetchNextObject($q_cust_chat)){
                                    @$id_subject  		= $dt_cust_chat->ID_CHAT_ATTRIBUTE;
                                    @$id_customer_chat  = $dt_cust_chat->ID_CUSTOMER;
                                    @$id_merchant   	= $dt_cust_chat->ID_CLIENT;
                                    
                                    //LAST CHAT
                                    @$msg 				= "";
                                    @$tgl_chat			= "";
                                    @$wkt_chat			= "";	
                                    $msg 				= $dt_cust_chat->CHAT_SUBJECT;
                                    $info_tanggal 		= explode(" ",$dt_cust_chat->ORDER_DTIME);
                                    @$wkt_chat			= substr($info_tanggal[1],0,5);
                                    @$tgl_chat			= $dtime->date2indodate($info_tanggal[0]);
                                    //END OF LAST CHAT
                                    
                                    @$user_foto_subject	= $dt_cust_chat->CUSTOMER_PHOTO;
                                    if(is_file($basepath."/files/images/members/".$user_foto_subject)) {
                                            $user_foto_subject = "members/".$user_foto_subject;  			}
                                    else{	$user_foto_subject = "noimage-m.jpg";  							}
                                    $user_subject 		= "<img src='".$dirhost."/files/images/".$user_foto_subject."'>"; 
                                    @$user_name_subject	= $dt_cust_chat->CUSTOMER_NAME;
                                    
                                    $ch_view_id  = $db->recount(
                                            "SELECT PARTICIPANTS_ID_VIEW 
                                             FROM 
                                                ".$tpref."chat_attribute
                                             WHERE 
                                                ID_CLIENT='".$_SESSION['cidkey']."' AND 
                                                ID_CHAT_ATTRIBUTE='".$dt_cust_chat->ID_CHAT_ATTRIBUTE."' AND
                                                PARTICIPANTS_ID_VIEW LIKE '%{ID_USER:".$_SESSION['uidkey']."}%'");
                                                
                                    if($ch_view_id == 0){ $class = "ch-item-new";	}
                                    else				{ $class = "ch-item";		}
                                ?> 
                                <div class="chat-user <?php echo @$class; ?>" id="subject_<?php echo $id_customer_chat; ?>" onclick="show_chat('<?php echo $id_customer_chat; ?>')" title="Pilih Subjek Pesan" >
                                	<span id="indicator_<?php echo $id_customer_chat; ?>">
                                    	<span class="pull-right label label-danger">Offline</span>
                                    </span>
                                    <div class='chat-avatar' style='width:40px;height:40px; overflow:hidden'>
										<?php echo $user_subject; ?>
                                    </div>
                                    <div class="chat-user-name">
                                        <a href="#"><?php echo $user_name_subject; ?></a>
                                    </div>
                                </div>                                
                                <?php 
                                } 
                                
                                while($dt_cust_list = $db->fetchNextObject($q_cust_list)){
                                    //USER SUBJECT
                                    @$id_customer_chat  = $dt_cust_list->ID_CUSTOMER;
                                    @$user_foto_subject	= $dt_cust_list->CUSTOMER_PHOTO;
                                    if(is_file($basepath."/files/images/members/".$user_foto_subject)) {
                                            $user_foto_subject = "members/".$user_foto_subject;  			}
                                    else{	$user_foto_subject = "noimage-m.jpg";  							}
                                    $user_subject 		= "<img src='".$dirhost."/files/images/".$user_foto_subject."'>"; 
                                    @$user_name_subject	= $dt_cust_list->CUSTOMER_NAME;
                                ?> 
                                <div class="chat-user" id="subject_<?php echo $id_customer_chat; ?>" onclick="show_chat('<?php echo $id_customer_chat; ?>')" title="Pilih Subjek Pesan" >
                                    <span id="indicator_<?php echo $id_customer_chat; ?>">
                                    	<span class="pull-right label label-danger">Offline</span>
                                    </span>
                                    <div class='chat-avatar' style='width:40px;height:40px; overflow:hidden'>
										<?php echo $user_subject; ?>
                                    </div>
                                    <div class="chat-user-name">
                                        <a href="javascript:void()"><?php echo $user_name_subject; ?></a>
                                    </div>
                                </div>                                
                                <?php } 
							?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="chat-message-form">
                        <small id="write_status"></small>
                        <span id="chat_load"></span>
                        <div class="form-group">
                            <textarea class="form-control message-input" name="message" id="ch-message-input" placeholder="Tulis Pesan..."  data-id="<?php echo $_SESSION['uidkey']; ?>"></textarea>
                            <button type="button" class="btn btn-primary btn-block" id="ch-message-send" value='<?php echo @$_SESSION['cidkey']; ?>'><i class="fa fa-paper-plane"></i> Kirim Chat</button>
                            
                            <input type="hidden" 
                                   id="chat_list" 
                                   value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/chat_list.php" />
                            <input type='hidden' id="id_subject" value='<?php echo @$last_subject; ?>' />
                            <input type='hidden' id="id_customer" value='<?php echo @$last_customer; ?>' />
                            <input type='hidden' id="onwrite" value='' />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>