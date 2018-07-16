<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
#img_list .btn-upload{ margin-top:14px; }
#img_list table{ width:70%; }
#img_list table tr td img{ margin:auto; }
#img_list table tr td{ width:6%; vertical-align:bottom; text-align:center;}
.buildiframe{
	border:1px solid #CCC;
	border-radius:3px;
	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	font-family:"Century Gothic";
	font-size:10px;
}
.count_status{ padding:0 0 10px 0;  }
</style>
<div class="ibox float-e-margins">
    <div class="ibox-title">
    	<h5>Discoin <?php echo $nm_merchant; ?> Builder</h5>
    </div>
    <div class='ibox-content'>
        <span id="count_status"></span>
        <div class="progress" style="display:none"> 
            <div id="process" class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"><div id="df"></div></div> 
        </div>
        
        <div id="builder_container">
            <div class="clearfix"></div>
            <form id="ouput" method="post" action="<?php echo $dirhost; ?>/discoin_builder/data/builder.php" target="progress_output" enctype="multipart/form-data">
            <div id="detail" class="col-md-12" style="display:none">
                <iframe class="buildiframe" frameborder="0" name="progress_output" style="width:100%; height:400px; overflow-y:scroll;" >
                    
                </iframe>
            </div>
            <div class="clearfix"></div>
            <div class="alert alert-info" style="font-size:11px;">
                Petunjuk :<br />
                <ol>
                    <li>Format *.png</li>
                    <li>ukuran minimal 200px x 200px</li>
                </ol>
            </div>
            <div class="form-group col-md-6">
                <label>Nama Aplikasi</label>
                <input type="text" class="form-control ucwords" name="app_name" placeholder="Nama Aplikasi" value="<?php echo $app_name; ?>">
            </div>
            <div class="form-group col-md-6">
                <label>Icon Aplikasi</label>
                <input type="file" name="icon" id="logo_aplikasi">
            </div>
            <div class="form-group col-md-12 no-padding-lr">
                <span class="pev_logo"> 
                <?php
                    $r = 0;
                    $discon_user_folder = $discoin_folder."/".$_SESSION['cidkey']."-".$_SESSION['app'];
                    $discoin_user_dir	= $dirhost."/files/images/icons/discoin/".$_SESSION['cidkey']."-".$_SESSION['app'];
                    if(is_dir($discon_user_folder)){
                ?>
                    <div id="img_list">
                        <table>
                            <tr>
                    <?php
                            while($r < 6){ $r++;
                                if(is_file($discon_user_folder."/res/".$folder[$r]."/icon.png")){
                    ?>
                                <td>
                                    <img src="<?php echo $discoin_user_dir; ?>/res/<?php echo $folder[$r]; ?>/icon.png" 
                                    class="thumbnail">
                                    <br>
                                    <b><?php echo $size[$r]."x".$size[$r]."px"; ?></b>
                                </td>			
                    <?php 		} 
                            }
                    ?>
                            </tr>
                        </table >
                    <div>
                <?php
                    }
                ?>
                </span>
            </div>
            </form>
            <div class="clearfix"></div>
        </div>
	</div>
</div>
<div class="ibox float-e-margins">
    <div class="ibox-title">
    	<h5>Konfigurasi Fitur-Fitur Discoin <?php echo $nm_merchant; ?></h5>
    </div>
    <div class='ibox-content'>
        <table width="100%" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th width="21%">Nama Fitur</th>
                    <th width="55%">Deskripsi Fitur</th>
                    <th width="13%" class="text-center">Biaya Aktivasi</th>
                    <th width="11%" class="text-center">Status Aktif</th>
                </tr>
            </thead>
            <tbody>
            	<?php 
					while($dt_addons	= $db->fetchNextObject($q_addons)){ 
					$num_addon	 = "";
					$id_addon	 = $dt_addons->ID_DISCOIN_ADDON; 
					$num_addon 	 = $db->recount("SELECT ID_CLIENT 
												 FROM ".$tpref."discoin_configs 
												 WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND 
												 	   ID_DISCOIN_ADDON = '".$id_addon."'");
					if($dt_addons->ADDON_PRICE == 0){ $biaya = "Rp.0,00"; 	}
					else							{ $biaya = money("Rp.",$dt_addons->ADDON_PRICE); 			}
				?>
                    <tr>
                        <td class="align-top"><?php echo $dt_addons->ADDON_NAME; ?></td>
                        <td class="align-top"><?php echo $dt_addons->ADDON_DESCRIPTION; ?></td>
                        <td class="text-center align-top"><label class='text-danger'><?php echo $biaya; ?></label></td>
                        <td class="text-center align-top">
                            <input type="checkbox" 
                            	   class="st_addons" 
                            	   data-id="<?php echo $id_addon; ?>"
								   <?php if($num_addon > 0){?> checked <?php } ?>/>
                        </td>
                    </tr>
				<?php } ?>
            </tbody>
        </table>
        <input type="hidden" id="proses_page" value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php" />
  </div>
</div>
