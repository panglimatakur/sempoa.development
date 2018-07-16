<?php defined('mainload') or die('Restricted Access'); ?>
<div id="subject_list">
<div class="ibox-title">
    <h4>Inbox</h4>
    <i class="pull-right icon-mail"></i>
</div>
<div class="ibox-content">
    <div class="mbox clearfix">
        <div class="mbox_content">
            <table id="dt_inbox" class="dataTables_full table table-striped" data-provides="rowlink">
                <thead>
                    <tr>
                        <th class="table_checkbox"><input type="checkbox" name="select_msgs" class="select_msgs" data-tableid="dt_inbox" /></th>
                        <th><i class="splashy-star_empty"></i></th>
                        <th><i class="splashy-mail_light"></i></th>
                        <th>Subjek</th>
                        <th>Pengirim</th>
                        <th>Tanggal</th>
                        <th>Ukuran</th>
                        <th><i class="icsw16-paperclip"></i></th>
                    </tr>
                </thead>
                <tbody>
                <?php
				while($dt_chat = $db->fetchNextObject($q_chat)){
					$q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$dt_chat->BY_ID_USER."'"); 
					$dt_user_chat	= $db->fetchNextObject($q_user_chat);
					$user_foto_chat	= $dt_user_chat->USER_PHOTO;
					$user_name_chat	= $dt_user_chat->USER_NAME;
					$tgl_chat		= $dt_chat->TGLUPDATE;
					$attachment		= $dt_chat->CHAT_ATTACHMENTS;
				?>
                        <tr class="unread">
                            <td class="nolink"><input type="checkbox" name="msg_sel" class="select_msg" /></td>
                            <td class="nolink starSelect"><i class="splashy-star_empty mbox_star"></i></td>
                            <td><i class="splashy-mail_light"></i></td>
                            <td>
                            	<a href="<?php echo $lparam; ?>&show=<?php echo $dt_chat->ID_CHAT_SUBJECT; ?>">
									<?php echo cutext($dt_chat->CHAT_SUBJECT,100)."..."; ?>
                                </a>
                            </td>
                            <td><?php echo $user_name_chat; ?></td>
                            <td><?php echo $dtime->date2indodate($tgl_chat); ?></td>
                            <td>25 KB</td>
                            <td>
                            <?php if(!empty($attachment)){?>
                            	<i class="icsw16-paperclip"></i>
                            <?php } ?>
                            </td>
                        </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>