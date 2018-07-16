<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox-title">
    <h4>Pesan</h4>
</div>
<div class="ibox-content" style='height:auto'>
    <div class="ch-messages">  
        <?php 
		$for_id			= "";
        while($dt_chat = $db->fetchNextObject($q_chat)){
            $q_user_chat	= $db->query("SELECT * FROM system_users_client WHERE ID_USER='".$dt_chat->ID_USER."'"); 
            $dt_user_chat	= $db->fetchNextObject($q_user_chat);
            $user_foto_chat	= $dt_user_chat->USER_PHOTO;
            $user_name_chat	= $dt_user_chat->USER_NAME;
            $tgl_chat		= $dt_chat->TGLUPDATE;
            $wkt_chat		= substr($dt_chat->WKTUPDATE,0,5);
        ?> 
        <div class="ch-message-item clearfix">
            <?php if(!empty($user_foto_chat) && is_file($user_foto_dir."/".$user_foto_chat)){?>
                <img src="<?php echo $user_foto_dir; ?>/<?php echo $user_foto_chat; ?>" alt="" class="ch-image img-avatar"/>
            <?php }else{ ?>
                <img src="files/images/noimage-m.jpg" alt="" class="ch-image img-avatar"/>
            <?php } ?>
            <div class="ch-content">
                <p class="ch-name">
                    <strong><?php echo $user_name_chat; ?></strong>
                    <span class="ch-time"><?php echo $wkt_chat; ?></span>
                </p>
                <?php echo $dt_chat->CHAT_MESSAGE; ?>
            </div>
        </div>
        <?php } ?>
        <span id="chat_load"></span>
    </div>
    
    <div class="ch-message-item clearfix" id="ch-message-temp" style="display:none">
		<?php echo getuserfoto($_SESSION['uidkey']," class='ch-image img-avatar'"); ?>
        <div class="ch-content">
            <p class="ch-name">
                <strong><?php echo ucwords(@$user_name); ?></strong>
                <span class="ch-time"></span>
            </p>
            <span class="ch-text"></span>
        </div>
    </div>
</div>
