<?php defined('mainload') or die('Restricted Access'); ?>
<div class="row-fluid">
    <div class="span1">
        <div class="mbox_toolbar clearfix">
            <?php if(!empty($show))	{ $link = 'href="'.$lparam.'&compose=1"'; } ?>
            <?php if(empty($show))	{ $link = 'href="javascript:void()" id="compose"'; } ?>
            <a <?php echo $link; ?>><i class="icsw32-create-write"></i><span>Compose</span></a>
            <a href='<?php echo $lparam; ?>'><i class="icsw32-box-incoming"></i><span>Pesan Masuk</span></a>
            <a href='<?php echo $lparam; ?>&outcome=1'><i class="icsw32-box-outgoing"></i><span>Pesan Keluar</span></a>
            <a href="#"><i class="icsw32-trashcan"></i><span>Delete</span></a>
        </div>
    </div>
    <div class="col-md-11">
        <div class="ibox float-e-margins">
			<?php 
            if(!empty($msg)){
                echo "<div class='alert alert-success'>Pesan anda berhasil di kirim</div>";
            }
            ?>
        	<?php 
			if(!empty($show)){
				include $call->inc($inc_dir,"chats.php");	
			}else{
				if(empty($compose)){
					include $call->inc($inc_dir,"subjects.php");
				}
			}
			?>
            <div <?php if(empty($show) && empty($compose)){?> style="display:none" <?php } ?> id="form_letter">
            <div class="ibox-title">
                <h4>Tulis Surat</h4>
                <i class="pull-right icon-mail"></i>
            </div>
            <div class="ibox-title" >
            <form id="form_chat" action="" enctype="multipart/form-data" method="post" target="proses">
                <div class="ch-message-item clearfix" style='padding-top:0'>
                    <?php echo getuserfoto($_SESSION['uidkey']," style='' class='ch-image img-avatar'"); ?>
                    <div class="ch-content" style="position:relative">
						<?php if(empty($show) && empty($compose)){?> 
                            <a href='javascript:void()' id="close_letter" style="right:0; position:absolute">
                                <i class='icon-remove'></i>
                            </a>
                        <?php } ?>
                        <div class='form-group' id="n_ench_select">
                        	<label>Kepada</label>
                            <select id="kepada" multiple class="col-md-6">
                            	<?php 
								$q_client = $db->query("SELECT 
															ID_CLIENT,CLIENT_NAME 
														FROM 
															".$tpref."clients 
														WHERE 
															ID_CLIENT = '".$id_client."' ".networks_condition($id_client)." 
														ORDER BY 
															ID_CLIENT ASC"); 
								while($dt_client = $db->fetchNextObject($q_client)){?>
                            	<optgroup label="<?php echo $dt_client->CLIENT_NAME; ?>">
									<?php 
                                    $q_user = $db->query("SELECT 
                                                                ID_USER,
                                                                USER_NAME 
                                                            FROM 
                                                                system_users_client 
                                                            WHERE ID_CLIENT='".$dt_client->ID_CLIENT."' ORDER BY ID_USER ASC"); 
                                    while($dt_user = $db->fetchNextObject($q_user)){
                                    ?>
                                        <option value="<?php echo @$dt_user->ID_USER; ?>" <?php if(!empty($show) && in_array(@$dt_user->ID_USER, $part_user)){?> selected <?php } ?>>
											<?php echo @$dt_user->USER_NAME; ?>
                                        </option>
                                    <?php 
									} ?>
                                </optgroup>
                                <?php } ?>
                            </select>
                        </div>
                        <div class='form-group'>
                        	<label>Subjek</label>
                        	<input type="text" id="subjek" class='col-md-6' value="<?php echo @$subjek; ?>"/>
                        </div>
                        <div class='form-group'>
                        	<label>Pesan</label>
                        	<textarea class="form-control ch-message-input validate[required] text-input" id="ch-message-input" placeholder="Text Pesan" ></textarea>
                        </div>
                        <div class='form-group'>
                            <span id="fileList"></span>
                            <input type="file" id='attachment' style="display:none">
                            <!--onchange="preview(this,'1')" -->
                            <button type="button" class="btn btn-beoro-3 ch-message-send" id="ch-message-send">
                                Kirim Pesan
                            </button>
                            <input type='hidden'   id='direction' 	value='<?php echo@ $direction; ?>' />
                            <input type='hidden' id='user_name' 	value='<?php echo $user_name; ?>' />
                            <a href='javascript:void()' class='btn' id="open_file">
                                <i class="icsw16-paperclip"></i> Lampiran
                            </a>
                            <input type='hidden' id='id_topic' 		value='<?php echo@ $show; ?>' />
                       </div>
                    </div>
                </div>
            </form>
            </div>
            <input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
        	</div>
        </div>
    </div>
</div>
</div>
<div class="footer_space"></div>
