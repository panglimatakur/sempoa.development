<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$show 		= isset($_POST['show']) 	? $_POST['show'] 	: "";
	$id_post 	= isset($_POST['id_post']) 	? $_POST['id_post'] : "";
	$lastId 	= isset($_POST['lastId']) 	? $_POST['lastId'] : "";
	if(!empty($show) && $show == "data_reply"){
		if(empty($lastId)){
			$str_query		= " SELECT * 
								FROM 
									".$tpref."posts a, system_users_client b 
								WHERE 
									a.ID_POST='".$id_post."' AND
									a.ID_USER = b.ID_USER"; 
			$q_post 		= $db->query($str_query);
			$dt_post		= $db->fetchNextObject($q_post);
			
			$q_client		= $db->query("SELECT TGLUPDATE,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_post->ID_CLIENT."'");
			$dt_client		= $db->fetchNextObject($q_client);
			$nm_merchant	= $dt_client->CLIENT_NAME;
			$join_merchant	= $dt_client->TGLUPDATE;
			$title			= $dt_post->POST_TITLE;
		?>
		<button type="button" value="back" class='btn btn-large btn-beoro-2' style='margin-bottom:6px' onclick="back()">
        	<i class="icsw32-bended-arrow-left icsw32-white"></i>
            Kembali Ke Forum
        </button>
        <a href="#form">
        <button type="button" value="reply" class='btn btn-large btn-beoro-3' style='margin-bottom:6px' onclick="open_reply('','<?php echo $id_post; ?>','<?php echo $title; ?>')">
            <i class="icsw32-speech-bubble icsw32-white"></i>
            Tulis Balasan Baru
        </button>
		</a>
        <div id="tr_<?php echo $dt_post->ID_POST; ?>">
            <div class="ibox-title">
                <h4><?php echo $dtime->now2indodate2($dt_post->TGLUPDATE)." ".$dt_post->WKTUPDATE; ?></h4>
            </div>
            <div class="ibox-content">
                <table width="100%" class="table">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="11%">
                                <div style='text-align:center; border-bottom:1px solid #666'>
                                   <b class='code'><?php echo $nm_merchant; ?></b><br />
                                    <?php echo getuserfoto($dt_post->ID_USER," width='90%' "); ?>
                                </div>
                                <b>Nama</b> 	<br />
                                    <?php echo $dt_post->USER_NAME; ?><br />
                               <b>Bergabung</b> <br />
                                    <?php echo $dtime->now2indodate2($join_merchant); ?>
                                <?php if($dt_post->ID_USER == $_SESSION['uidkey']){?>
                                    <div style="text-align:center;margin-top:3px;">
                                        <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_post->ID_POST; ?>" class="btn btn-mini" title="Edit">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <a href='javascript:void()' onclick="removal('<?php echo $dt_post->ID_POST; ?>')" class="btn btn-mini" title="Delete">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                               <?php } ?>
                            </td>
                            <td width="89%">
                                <h4 style="color:#800080"><?php echo $dt_post->POST_TITLE; ?></h4>
                                
                                <?php if(!empty($dt_post->POST_COVER) && is_file($basepath."/files/images/".$dt_post->POST_COVER)){?>
                                    <img src="<?php echo $dirhost; ?>/files/images/<?php echo $dt_post->POST_COVER; ?>" style="width:200px; float:left; margin:4px; " class='thumbnail'>
                                <?php } ?>
                                <?php echo html_entity_decode($dt_post->POST_CONTENT); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
		<?php
		}
		$condition	= "";
		if(!empty($lastId)){
			$condition = " AND a.ID_POST < '".$lastId."'"; 	
		}
		$str_query		= " SELECT * 
						    FROM 
								".$tpref."posts a, system_users_client b 
							WHERE 
								a.ID_POST_PARENT='".$id_post."' AND
								".$condition."
								a.ID_USER = b.ID_USER";
		#echo $str_query; 
		$q_post 		= $db->query($str_query." LIMIT 0,10");
		$num_post		= $db->numRows($q_post);
		?>
        <div class="post_reply">
		<?php while($dt_post	= $db->fetchNextObject($q_post)){ $lastId = $dt_post->ID_POST; ?>
        <div id="tr_<?php echo $dt_post->ID_POST; ?>">
            <div class="w-box-header ">
                <h4><?php echo $dtime->now2indodate2($dt_post->TGLUPDATE)." ".$dt_post->WKTUPDATE; ?></h4>
            </div>
            <div class="ibox-content">
                <table width="100%" class="table">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="11%">
                                <div style='text-align:center; border-bottom:1px solid #666'>
                                   <b class='code'><?php echo $nm_merchant; ?></b><br />
                                    <?php echo getuserfoto($dt_post->ID_USER," width='90%' "); ?>
                                </div>
                                <b>Nama</b> 	<br />
                                    <?php echo $dt_post->USER_NAME; ?><br />
                               <b>Bergabung</b> <br />
                                    <?php echo $dtime->now2indodate2($join_merchant); ?>
                                    
                                <?php if($dt_post->ID_USER == $_SESSION['uidkey']){?>
                                    <div style="text-align:center;margin-top:3px;">
                                        <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_post->ID_POST; ?>" class="btn btn-mini" title="Edit">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <a href='javascript:void()' onclick="removal('<?php echo $dt_post->ID_POST; ?>')" class="btn btn-mini" title="Delete">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                               <?php } ?>
                            </td>
                            <td width="89%">
                                <h4 style="color:#800080"><?php echo $dt_post->POST_TITLE; ?></h4>
                                <?php if(!empty($dt_post->ID_POST_REPLY)){ 
                                        $q_reply 		= $db->query("SELECT * FROM ".$tpref."posts WHERE ID_POST = '".$dt_post->ID_POST_REPLY."'");	
                                        $dt_reply		= $db->fetchNextObject($q_reply);
                                        @$nm_merchant2 	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$dt_post->ID_CLIENT."'");
                                ?>
                                        <div class="repler">
                                            <b>
                                                <?php echo "<b class='code'>".@$nm_merchant2."</b> - ".$dt_post->USER_NAME; ?> - 
                                                <?php echo $dtime->now2indodate2($dt_post->TGLUPDATE)." ".$dt_post->WKTUPDATE; ?>
                                            </b>
                                            <br />
                                            <?php echo html_entity_decode($dt_reply->POST_CONTENT); ?>
                                        </div>
                                <?php } ?>
                                <?php if(!empty($dt_post->POST_COVER) && is_file($basepath."/files/images/".$dt_post->POST_COVER)){?>
                                    <img src="<?php echo $dirhost; ?>/files/images/<?php echo $dt_post->POST_COVER; ?>" style="width:200px; float:left; margin:4px; " class='thumbnail'>
                                <?php } ?>
                                <?php echo html_entity_decode($dt_post->POST_CONTENT); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ibox-title" style="text-align:right;">
                <a href="#form">
				<button type="button" value="reply" class='btn' style='margin-right:6px' onclick="open_reply('<?php echo @$dt_post->ID_POST_PARENT; ?>','<?php echo $dt_post->ID_POST; ?>','<?php echo $dt_post->POST_TITLE; ?>')">
                    <i class="icsw16-speech-bubble"></i>
                    Balas
                </button>
				</a>
            </div>
        </div>
		<?php } ?>
        	<div id="rloader"></div>
        </div>
        
        <?php if($num_post > 10){?>
            <div class='wrdLatest' data-info='<?php echo $lastId; ?>'></div>
            <div id="lastReplyLoader"></div>
            <div class="ibox-title" style="text-align:center; margin-top:7px" id="replyFooter">
                <a href="javascript:void()" onclick="lastReply('<?php echo $id_post; ?>')">Selanjutnya...</a>
            </div>
        <?php } ?>
        <button type="button" value="back" class='btn btn-large btn-beoro-2' style='margin-top:6px' onclick="back()">
        	<i class="icsw32-bended-arrow-left icsw32-white"></i>
            Kembali Ke Forum
        </button>
        <a href="#form">
		<button type="button" value="reply" class='btn btn-large btn-beoro-3' style='margin-top:6px' onclick="open_reply('','<?php echo $id_post; ?>','<?php echo $title; ?>')">
            <i class="icsw32-speech-bubble icsw32-white"></i>
            Tulis Balasan Baru
        </button>
		</a>
	<?php } 
    
    
    if(!empty($show) && $show == "new_reply"){
			$str_query		= " SELECT * 
								FROM 
									".$tpref."posts a, system_users_client b 
								WHERE 
									a.ID_POST='".$id_post."' AND
									a.ID_USER = b.ID_USER AND 
									a.ID_USER = '".$_SESSION['uidkey']."'"; 
			$q_post 		= $db->query($str_query);
			$dt_post		= $db->fetchNextObject($q_post);
			
			$q_client		= $db->query("SELECT TGLUPDATE,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_post->ID_CLIENT."'");
			$dt_client		= $db->fetchNextObject($q_client);
			$nm_merchant	= $dt_client->CLIENT_NAME;
			$join_merchant	= $dt_client->TGLUPDATE;
		?>
        <div id="tr_<?php echo $dt_post->ID_POST; ?>">
            <div class="ibox-title">
                <h4><?php echo $dtime->now2indodate2($dt_post->TGLUPDATE)." ".$dt_post->WKTUPDATE; ?></h4>
            </div>
            <div class="ibox-content">
                <table width="100%" class="table">
                    <thead>
                    </thead>
                    <tbody>
                        <tr>
                            <td width="11%">
                                <div style='text-align:center; border-bottom:1px solid #666'>
                                   <b class='code'><?php echo $nm_merchant; ?></b><br />
                                    <?php echo getuserfoto($dt_post->ID_USER," width='90%' "); ?>
                                </div>
                                <b>Nama</b> 	<br />
                                    <?php echo $dt_post->USER_NAME; ?><br />
                               <b>Bergabung</b> <br />
                                    <?php echo $dtime->now2indodate2($join_merchant); ?>
                                <?php if($dt_post->ID_USER == $_SESSION['uidkey']){?>
                                    <div style="text-align:center;margin-top:3px;">
                                        <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_post->ID_POST; ?>" class="btn btn-mini" title="Edit">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <a href='javascript:void()' onclick="removal('<?php echo $dt_post->ID_POST; ?>')" class="btn btn-mini" title="Delete">
                                            <i class="icon-trash"></i>
                                        </a>
                                    </div>
                               <?php } ?>
                            </td>
                            <td width="89%">
                                <h4 style="color:#800080"><?php echo $dt_post->POST_TITLE; ?></h4>
                                <?php if(!empty($dt_post->ID_POST_REPLY)){ 
                                        $q_reply 		= $db->query("SELECT * FROM ".$tpref."posts WHERE ID_POST = '".$dt_post->ID_POST_REPLY."'");	
                                        $dt_reply		= $db->fetchNextObject($q_reply);
                                        @$nm_merchant2 	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$dt_post->ID_CLIENT."'");
                                ?>
                                        <div class="repler">
                                            <b>
                                                <?php echo "<b class='code'>".@$nm_merchant2."</b> - ".$dt_post->USER_NAME; ?> - 
                                                <?php echo $dtime->now2indodate2($dt_post->TGLUPDATE)." ".$dt_post->WKTUPDATE; ?>
                                            </b>
                                            <br />
                                            <?php echo html_entity_decode($dt_reply->POST_CONTENT); ?>
                                        </div>
                                <?php } ?>
                                <?php if(!empty($dt_post->POST_COVER) && is_file($basepath."/files/images/".$dt_post->POST_COVER)){?>
                                    <img src="<?php echo $dirhost; ?>/files/images/<?php echo $dt_post->POST_COVER; ?>" style="width:200px; float:left; margin:4px; " class='thumbnail'>
                                <?php } ?>
                                <?php echo html_entity_decode($dt_post->POST_CONTENT); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ibox-title" style="text-align:right;">
                <a href="#form">
				<button type="button" value="reply" class='btn' style='margin-right:6px' onclick="open_reply('<?php echo @$dt_post->ID_POST_PARENT; ?>','<?php echo $dt_post->ID_POST; ?>','<?php echo $dt_post->POST_TITLE; ?>')">
                    <i class="icsw16-speech-bubble"></i>
                    Balas
                </button>
				</a>
            </div>
        </div>
<?php        
	}
	
	if(!empty($show) && $show == "data_post"){
		include $call->clas("class.html2text");
		
		$condition	= "";
		if(!empty($lastPost))	{ $condition = "AND ID_POST < '".$lastPost."'"; }
		if(!empty($id_post))	{ $condition = "AND ID_POST = '".$id_post."'"; 	}

		$str_query		= "SELECT * FROM ".$tpref."posts WHERE ID_POST IS NOT NULL AND (ID_POST_PARENT IS NULL OR ID_POST_PARENT = 0) ".$condition." ORDER BY ID_POST DESC"; 
		#echo $str_query;
		$q_polling 		= $db->query($str_query." LIMIT 0,10");
		$t = 0; 
		while($dt_polling = $db->fetchNextObject($q_polling)){ 
			$t++; 
			$lastPost		= $dt_polling->ID_POST;
			$by 			= $db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$dt_polling->ID_USER."'");
			$q_last_post	= $db->query("SELECT TGLUPDATE,WKTUPDATE,ID_USER FROM ".$tpref."posts WHERE ID_POST = '".$dt_polling->ID_POST."' ORDER BY ID_POST DESC");
			$dt_last_post	= $db->fetchNextObject($q_last_post);
			@$tgl_last_post	= $dt_last_post->TGLUPDATE;
			@$wkt_last_post	= $dt_last_post->WKTUPDATE;
			@$by_last_post	= $db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$dt_last_post->ID_USER."'");
			@$sum_reply		= $db->recount("SELECT ID_POST FROM ".$tpref."posts WHERE ID_POST_PARENT = '".$dt_polling->ID_POST."'");
		?>
		<tr id="tr_<?php echo $dt_polling->ID_POST; ?>">
			<td>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOne_<?php echo $dt_polling->ID_POST; ?>">
							<b style="color:#800080"><?php echo $dt_polling->POST_TITLE; ?></b>
							<br />
							<span class="splashy-contact_blue"></span> By : <?php echo $by; ?>
						</a>
					</div>
					<div id="collapseOne_<?php echo $dt_polling->ID_POST; ?>" class="accordion-body collapse">
						<div class="accordion-inner">
							<?php if(!empty($dt_polling->POST_COVER) && is_file($basepath."/files/images/".$dt_polling->POST_COVER)){?>
									<img src="<?php echo $dirhost; ?>/files/images/<?php echo $dt_polling->POST_COVER; ?>" style="width:60px; float:left; margin:4px; " class='thumbnail'>
							<?php } ?>
							<?php echo printtext(html_entity_decode($dt_polling->POST_CONTENT),500); ?>
							<br />
							<a href="javascript:void()" class="code" onclick="view_post('<?php echo $dt_polling->ID_POST; ?>')">...Selanjutnya</a>
							<br clear="all" />
						</div>
					</div>
				</div>
			</td>
			<td>
				<?php echo $dtime->now2indodate2($tgl_last_post)." ".$wkt_last_post; ?>
				<br />
				<i class="splashy-contact_blue"></i> By : <?php echo $by_last_post; ?>
			</td>
			<td><?php if(!empty($sum_reply)){ echo $sum_reply; ?> Komentar <?php }else{ ?> 0 Komentar <?php } ?></td>
			<td style="text-align:center; vertical-align:top">
			<?php if($dt_polling->ID_USER == $_SESSION['uidkey']){?>
				<div class="btn-group">
					<a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_polling->ID_POST; ?>" class="btn btn-mini" title="Edit">
						<i class="icon-pencil"></i>
					</a>
					<a href='javascript:void()' onclick="removal('<?php echo $dt_polling->ID_POST; ?>')" class="btn btn-mini" title="Delete">
						<i class="icon-trash"></i>
					</a>
				</div>
		   <?php } ?>
			</td>
		</tr>
		<?php }
	?>
    	<div class='wrdPostLatest' data-info='<?php echo $lastPost; ?>'></div>
    <?php
	}
	
	if(!empty($show) && $show == "destiny"){
		$destiny 	= isset($_POST['destiny']) 	? $_POST['destiny'] : "";
		$search 	= isset($_POST['search']) 	? $_POST['search'] : "";
		if($destiny == "komunitas"){
			echo "<span class='code'>Pilih salah satu atau beberapa nama Komunitas dibawah ini</span><br>";
			$str_list_comm	= "SELECT ID_COMMUNITY,NAME FROM ".$tpref."communities WHERE ID_COMMUNITY IS NOT NULL AND STATUS_ACTIVE = '2' AND NAME LIKE '%".$search."%' ORDER BY ID_COMMUNITY ASC";
			$q_list_comm	= $db->query($str_list_comm);
			while($dt_comm = $db->fetchNextObject($q_list_comm)){ ?>
				<div class='col-md-4 dest_list' id="id_comm_<?php echo $dt_comm->ID_COMMUNITY; ?>" style="margin:4px 4px 0 0;	" onclick="pick_this('komunitas','<?php echo $dt_comm->ID_COMMUNITY; ?>')">
					<b><?php echo $dt_comm->NAME; ?></b>
                    <input type="hidden" id="val_comm_<?php echo $dt_comm->ID_COMMUNITY; ?>" value='"nama":"<?php echo trim($dt_comm->NAME); ?>"'/>
                </div>
			<?php }
		}
		if($destiny == "personal"){
			echo "<span class='code'>Pilih salah satu atau beberapa nama Pengguna dibawah ini</span><br>";
			$str_user	= "SELECT ID_CLIENT,ID_USER,USER_NAME FROM system_users_client WHERE ID_USER IS NOT NULL AND USER_NAME LIKE '%".$search."%' ORDER BY USER_NAME ASC";
			$q_user	= $db->query($str_user);
			while($dt_user = $db->fetchNextObject($q_user)){ 
				$nm_merchant = $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$dt_user->ID_CLIENT."'");
			?>
				<div class='col-md-4 dest_list' id="id_user_<?php echo $dt_user->ID_USER; ?>" style="margin:4px 4px 0 0;" onclick="pick_this('personal','<?php echo $dt_user->ID_USER; ?>')">
					<b><?php echo $dt_user->USER_NAME; ?></b><br />
                    <b class='code'><?php echo $nm_merchant; ?></b>
                    <input type="hidden" id="val_personal_<?php echo $dt_user->ID_USER; ?>" value='"nama":"<?php echo trim($dt_user->USER_NAME); ?>","merchant":"<?php echo $nm_merchant; ?>"'/>
                </div>
			<?php }
		}
	}
}
?>