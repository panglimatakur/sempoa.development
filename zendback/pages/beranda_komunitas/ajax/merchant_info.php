<?php 
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	$direction 		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 	: "";
	$id_merchant 	= isset($_REQUEST['id_merchant']) ? $_REQUEST['id_merchant'] 	: "";
	
	$str_merchant	= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$id_merchant."'";
	$q_merchant		= $db->query($str_merchant);
	$dt_merchant	= $db->fetchNextObject($q_merchant);	
	?>
<div class="row-fluid" style="width:560px">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>User profile</h4>
            </div>
            <div class="w-box-content cnt_a user_profile">
                <div class="row-fluid">
                    <div class="col-md-2">
                        <div class="img-holder">
                            <?php echo getclientlogo($id_merchant," class='thumbnail' width='100'"); ?>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <p class="form-group"><?php echo $dt_merchant->CLIENT_NAME; ?></p>
                        <?php if(!empty($dt_merchant->CLIENT_ADDRESS)){?>
                            <p class="form-group">
                            	<small class="muted">Alamat:</small>
								<?php echo $dt_merchant->CLIENT_ADDRESS; ?>
                            </p>
                        <?php } ?>
                        <?php if(!empty($dt_merchant->CLIENT_PHONE)){?>
                            <p class="form-group">
                            	<small class="muted">Telephone:</small>
								<?php echo $dt_merchant->CLIENT_PHONE; ?>
                            </p>
                        <?php } ?>
                        <?php if(!empty($dt_merchant->CLIENT_EMAIL)){?>
                            <p class="form-group">
                            	<small class="muted">Email:</small>
								<?php echo $dt_merchant->CLIENT_EMAIL; ?>
                            </p>
                        <?php } ?>
                        <?php if(!empty($dt_merchant->CLIENT_URL)){?>
                        <p class="form-group">
                        	<small class="muted">Website:</small> 
							<a href='<?php echo $dt_merchant->CLIENT_URL; ?>'><?php echo $dt_merchant->CLIENT_URL; ?></a>
                        </p>
                        <?php } ?>
                        <?php if(!empty($dt_merchant->CLIENT_URL)){?>
                            <p class="form-group"><small class="muted">Tentang <?php echo ucwords($dt_merchant->CLIENT_NAME); ?>:</small> <?php echo $dt_merchant->CLIENT_DESCRIPTIONS; ?>
                            </p>
                        <?php } ?>
                        <p class="form-group">
                            <div style="max-height:400px; overflow:scroll">
                            	<?php 
									$str_mchat	= "SELECT * FROM ".$tpref."mchat WHERE (BETWEEN_ID_CLIENTS = ';".$id_merchant.":;".$_SESSION['cidkey'].":') OR (BETWEEN_ID_CLIENTS = ';".$_SESSION['cidkey'].":;".$id_merchant.":')";
                                	$q_mchat 	= $db->query($str_mchat);
									while($dt_mchat = $db->fetchNextObject($q_mchat)){
									$username 	= $db->fob("USER_NAME","system_users_client"," WHERE ID_USER='".$dt_mchat->BY_ID_USER."'");
									$id_chat	= $dt_mchat->ID_MCHAT;
									$tgl_chat	= $dtime->date2indodate($dt_mchat->TGLUPDATE);
									$wkt_chat	= $dt_mchat->WKTUPDATE;
									echo 
										"<div class='clearfix' style='border-bottom:1px dashed #F2F2F2' id='chat_".$id_chat."'>
											<div class='img-box'>".getuserfoto($dt_mchat->BY_ID_USER," style='width:50px'")."</div>
											<div class='ch-content'>
												<p class='ch-name'>
													<strong>".$username."</strong>
													<small class='code'>".$tgl_chat." : ".$wkt_chat."</small>
												</p>
												".$dt_mchat->MCHAT_MESSAGE."
												<br />
												<button class='btn btn-mini removal ptip_sw' onclick='remove_mchat(\"".$id_chat."\")' title='Hapus Pesan'>
													<i class='icon-trash'></i>
												</button>
											</div>
										</div>";
									}
								?>
                                <span id='mchat'></span>
                                <br clear="all" />
                            </div>
                            <span id="mchat_load"></span>
                        	<label>Kirim Pesan</label>
                            <textarea id="msg_<?php echo $dt_merchant->ID_CLIENT; ?>" style="width:90%"></textarea>
                            <button id="mchat_button" value="<?php echo $dt_merchant->ID_CLIENT; ?>" class="btn btn-sempoa-1">Kirim Pesan</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php }else{
	defined('mainload') or die('Restricted Access');
}
 ?>