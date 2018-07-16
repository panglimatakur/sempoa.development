<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
.onwrite{
	padding:2px;
	margin:3px;
	font-size:11px;
}	
</style>
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
        <input type="hidden" id="sender_info" value='"user_photo":"<?php echo @$user_foto; ?>","user_name":"<?php echo $_SESSION['loginname']; ?>","wkt_chat":"<?php echo substr($wktupdate,0,5); ?>","tgl_chat":"<?php echo $dtime->date2indodate($tglupdate); ?>"'>
            
                <div class="ibox-title">
                    <h4 style="margin-left:14px">Pesan Pengunjung</h4>
                </div>
                <span id="topic_load"></span>
                <div class="ibox-content"  style='height:648px; '>
                    <div class="ch-topic" style="max-height: 640px;"> 
                        <?php 
							/*while($dt_cust_chat = $db->fetchNextObject($q_cust_chat)){
								@$id_subject  		= $dt_cust_chat->ID_CHAT_ATTRIBUTE;
								@$id_customer_chat  = $dt_cust_chat->ID_CUSTOMER;
								@$id_merchant   	= $dt_cust_chat->ID_CLIENT;
								
								//LAST CHAT
								@$msg 				= "";
								@$tgl_chat			= "";
								@$wkt_chat			= "";	
								$q_last_msg			= $db->query("SELECT CHAT_MESSAGE,UPDATEDATETIME FROM ".$tpref."chat 
																  WHERE 
																	ID_SENDER = '".$id_customer_chat."' AND 
																	ID_CLIENT = '".$id_merchant."'
																   ORDER BY ID_CHAT DESC");
								$dt_last_msg 		= $db->fetchNextObject($q_last_msg);
								$msg 				= $dt_last_msg->CHAT_MESSAGE;
								$info_tanggal 		= explode(" ",$dt_last_msg->UPDATEDATETIME);
								@$wkt_chat			= substr($info_tanggal[1],0,5);
								@$tgl_chat			= $dtime->date2indodate($info_tanggal[0]);
								//END OF LAST CHAT
								
								@$user_foto_subject	= $dt_cust_chat->CUSTOMER_PHOTO;
								if(is_file($basepath."/files/images/members/".$user_foto_subject)) {
										$user_foto_subject = "members/".$user_foto_subject;  			}
								else{	$user_foto_subject = "noimage-m.jpg";  							}
								$user_subject 		= "<img src='".$dirhost."/files/images/".$user_foto_subject."' 
															style='width:50px'>"; 
								@$user_name_subject	= $dt_cust_chat->CUSTOMER_NAME;
								
								$ch_view_id  = $db->recount(
										"SELECT PARTICIPANTS_ID_VIEW 
										 FROM 
											".$tpref."chat_attribute
										 WHERE 
											ID_CLIENT='".$_SESSION['cidkey']."' AND 
											ID_CHAT_ATTRIBUTE='".$dt_cust_chat->ID_CHAT_ATTRIBUTE."' AND
											PARTICIPANTS_ID_VIEW LIKE '%{ID_USER:".$_SESSION['uidkey']."}%'");
											
								if($ch_view_id == 0){ $class = "ch-topic-item-new";	}
								else				{ $class = "ch-topic-item";		}
								
							?> 
							<div class="<?php echo $class; ?> clearfix cl_subject" style="cursor:pointer" id="subject_<?php echo $id_customer_chat; ?>">
								<div class='img-box'><?php echo $user_subject; ?></div>
								<div class="ch-content">
									<p class="ch-name">
										<strong><?php echo $user_name_subject; ?></strong>
										<span class="ch-time" id="time_<?php echo $id_customer_chat; ?>">
											<?php echo @$tgl_chat; ?> <?php echo @$wkt_chat; ?>
										</span>
									</p>
									<span id="msg_<?php echo $id_customer_chat; ?>">
										<?php echo @$msg; ?>
									</span>
									<br />
									<button class='btn ptip_sw' onclick="show_chat('<?php echo $id_customer_chat; ?>')" title="Pilih Subjek Pesan" style="float:right; margin-left:4px">
										<i class="fa fa-comments"></i>
									</button>
								</div>
							</div>
							<?php 
							} */
							
							while($dt_vis_list = $db->fetchNextObject($q_vis_list)){
								//VISITOR SUBJECT
								@$id_visitor_chat  = $dt_vis_list->ID_VISITOR;
								@$vis_name_subject = $dt_vis_list->VISITOR_NAME;
							?> 
							<div class="ch-topic-item clearfix cl_subject" style="cursor:pointer" id="subject_<?php echo $id_visitor_chat; ?>">
								<div class="ch-content">
									<p class="ch-name">
										<strong><?php echo $vis_name_subject; ?></strong>
										<span class="ch-time" id="time_<?php echo $id_visitor_chat; ?>"></span>
									</p>
									<span id="msg_<?php echo $id_visitor_chat; ?>"></span>
									<br />
									<button class='btn ptip_sw' onclick="show_chat('<?php echo $id_visitor_chat; ?>')" title="Pilih Subjek Pesan" style="float:right; margin-left:4px">
										<i class="fa fa-comments"></i>
									</button>
								</div>
							</div>
							<?php }
						?>
                    </div>
                </div>
        </div>

        <div class="col-md-6" >
            <div class="ibox-title">
                <h4 style="margin-left:14px">Detail Pesan</h4>
            </div>
            <i class="splashy-help pop-over" data-content="Forum Chat ini adalah untuk, mendiskusikan subjek pesan yang di pilih" data-title="Forum Diskusi" data-placement="right" data-trigger="hover" data-original-title="" style="margin:-27px 0 0 2px; float:left"></i>         
            <div class="ibox-content"  style='height:582px; '>
                <div class="ch-messages" id="ch-messages" style="max-height:580px">
                	<span class="ch-messages-loader"></span>
                    <span class="chat-list"></span>
                	<span class="ch-message-item" style="display:none"></span> 
                <?php 
                    if(empty($last_customer)){ $last_customer = '0'; }
                    while($dt_chat = $db->fetchNextObject($q_chat)){
                        $remove_subject		= "";
						$info_tanggal 		= explode(" ",$dt_chat->UPDATEDATETIME);
						@$wkt_chat			= substr($info_tanggal[1],0,5);
						@$tgl_chat			= "";
						if($tglupdate != $info_tanggal[0]){
							@$tgl_chat		= $dtime->date2indodate($info_tanggal[0])."<br>";
						}
						
						switch($dt_chat->SENDER_LEVEL_NAME){
							case "USER":
								$q_user_chat	= $db->query("SELECT ID_USER,USER_PHOTO,USER_NAME 
															  FROM system_users_client 
															  WHERE ID_USER = '".$dt_chat->ID_SENDER."'");
								$dt_user_chat	= $db->fetchNextObject($q_user_chat);
								$user_foto_chat	= $dt_user_chat->USER_PHOTO;
								if(is_file($basepath."/files/images/users/".$user_foto_chat)) {
										$user_foto_chat = "users/".$user_foto_chat;  			}
								else{	$user_foto_chat = "noimage-m.jpg";  							}
								$user_chat 		= "<img src='".$dirhost."/files/images/".$user_foto_chat."' 
														style='width:50px'>"; 								
								$user_name_chat	= $dt_user_chat->USER_NAME;
								
								$remove_subject = '<button class="btn btn-mini removal ptip_sw" 
														onclick="remove_chat(\'chat\',\''.$dt_chat->ID_CHAT.'\')" 
														title="Hapus Pesan">
														<i class="icon-trash"></i>
												   </button>';
							break;
							case "CUSTOMER":
								$q_user_chat	= $db->query("SELECT ID_CUSTOMER,CUSTOMER_PHOTO,CUSTOMER_NAME 
															  FROM ".$tpref."customers 
															  WHERE ID_CUSTOMER = '".$dt_chat->ID_SENDER."'"); 
															  
								$dt_user_chat	= $db->fetchNextObject($q_user_chat);								
								$user_foto_chat	= $dt_user_chat->CUSTOMER_PHOTO;
								if(is_file($basepath."/files/images/members/".$user_foto_chat)) {
										$user_foto_chat = "members/".$user_foto_chat;  			}
								else{	$user_foto_chat = "noimage-m.jpg";  							}
								$user_chat 		= "<img src='".$dirhost."/files/images/".$user_foto_chat."' 
														style='width:50px'>"; 								
								$user_name_chat	= $dt_user_chat->CUSTOMER_NAME;
							break;	
						}
                    ?> 
                    <div class="ch-message-item clearfix" id="chat_<?php echo $dt_chat->ID_CHAT; ?>">
                        <div class='img-box'><?php echo $user_chat; ?></div>
                        <div class="ch-content">
                            <p class="ch-name">
                                <strong><?php echo $user_name_chat; ?></strong>
                                <span class="ch-time"><?php echo $tgl_chat." ".$wkt_chat; ?></span>
                            </p>
                            <?php echo $dt_chat->CHAT_MESSAGE; ?>
                            <br />
                            <?php echo @$remove_subject; ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="ibox-title">
                <small id="write_status"></small>
                <span id="chat_load"></span>
                <div class="input-group ch-message-add ">
                	<input type="text" class="form-control ch-message-input" 
                    	   id="ch-message-input" 
                           placeholder="Text Pesan"  
                           data-id="<?php echo $_SESSION['uidkey']; ?>">
                    <span class="input-group-btn"> 
                        <button type="button" 
                        `		class="btn btn-sempoa-1 ch-message-send" 
                        		id="ch-message-send" 
                                value='<?php echo @$_SESSION['cidkey']; ?>'>>
                        		<i class="fa fa-paper-plane"></i> Kirim
                        </button> 
                    </span>
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
