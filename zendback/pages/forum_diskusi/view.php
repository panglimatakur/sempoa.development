<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-12" >
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Data Berhasil Disimpan","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
        }
    ?>
    <input type="hidden" id="id_post_request" value="<?php echo @$_REQUEST['id_post']; ?>" />
    <?php if(empty($direction)){ ?>
    <div class="ibox float-e-margins" id="list_forum">
        <div id="post_container" >
            <a href="#form">
			<button class="btn btn-large btn-beoro-3" style="margin-bottom:5px" id="open_post">
            	<i class="icsw32-pencil icsw32-white"></i>
                Menulis Baru
            </button>
			</a>
            <div class="alert alert-info" style="text-align:center; margin-bottom:3px">
                Silahkan tuliskan pertanyaan, tips, trik dan tulisan apa saja yang bermanfaat di Forum ini, dan hindari menulis hal-hal yang berbau sara dan hal-hal negatif lainnya.
            </div>
            <div class="ibox-title">
                <h4>Forum</h4>
            </div>
                <div class="ibox-content">
                  <a name="report"></a>
                  <div id="ploader" style="margin-top:10px"></div>
                  <div class="accordion" id="accordion1">
                   
                    <table width="100%" class="table" id="listForum">
                        <thead>
                            <tr>
                                <th width="54%">&nbsp;</th>
                                <th width="20%">Terkini</th>
                                <th width="17%">Statistik</th>
                                <th width="6%" style="text-align:center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr style="display:none">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
						<?php if($num_polling > 0){ ?>
                            <?php $t = 0; while($dt_polling = $db->fetchNextObject($q_polling)){ $t++; 
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
                                                <a href="javascript:void()" class="code" onclick="view_post('<?php echo $dt_polling->ID_POST; ?>')">...Selanjutnya</a><!--<?php echo $dirhost; ?>/?page=forum&id_post=<?php echo $dt_polling->ID_POST; ?>-->
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
							}else{ ?>
                            <tr id="noPost">
                            	<td colspan='4'><?php echo msg("Tidak ada yang ditulis","error"); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                  </div>
                </div>
                <?php if($num_polling > 10){?>
                    <div class='wrdPostLatest' data-info='<?php echo $lastPost; ?>'></div>
                    <div id="lastPostLoader"></div>
                    <div class="ibox-title" style="text-align:center; margin-top:7px" id="postFooter">
                        <a href="javascript:void()" onclick="lastPost()">Selanjutnya...</a>
                    </div>
                <?php } ?>
			<a href="#form">
            <button class="btn btn-large btn-beoro-3" style="margin-top:5px" id="open_post">
                <i class="icsw32-pencil icsw32-white"></i>
                Menulis Baru
            </button>
			</a>
            <br clear="all" >
        </div>
        <div id="post_detail_container"></div>
    </div>
    <?php } ?>
    <div class="ibox float-e-margins" id="n_wysiwg" <?php if(empty($direction)){ ?> style="display:none" <?php } ?>> <!---->
        <div class="ibox-title">
            <h5>Form</h4>
        </div>
        <div class="ibox-content">
			<?php include $call->inc($page_dir."/includes","form_proses.php"); ?>
        </div>
    </div>
    <input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
    <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
</div>