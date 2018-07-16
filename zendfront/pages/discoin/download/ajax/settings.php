<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../../includes/config.php");
include_once("../../../../../includes/classes.php");
include_once("../../../../../includes/functions.php");
include_once("../../../../../includes/declarations.php");
$id_coin	= isset($_REQUEST['id_coin']) ? $_REQUEST['id_coin'] 	: "";
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {?>




<?php if(empty($direction)){?>

<div class="features_items" style="padding:10px;" ><!--features_items-->
    <h2 class="title text-center" style="margin-bottom:3px;">Konfigurasi Android</h2>
    <form method="post" action="" id="form_warning">
        <p style="padding:4px;">
            Sebelum mendownload aplikasi Discoin ini, pastikan jika perangkat seluler Androidmu mengijinkan install aplikasi dari <strong>"Sumber yang Tidak Dikenal"</strong> atau <strong>non Play Store.</strong>
        </p>
        <div style="text-align:center">
            <button type="button" class="step_btn btn  download_btn" onclick="open_sc('step')" style="margin-bottom:4px; padding:10px;"><i class='fa fa-folder-open'></i> Buka Cara Pengaturan</button>
            <button type="submit" name="direction" value="download" class="btn  download_btn" style="margin-bottom:4px;padding:10px; "><i class='fa fa-download'></i> Lanjut Proses Download</button>
        </div>
        <br />
        <ol id="step" style="display:none" data-title="1">
            <li>Buka menu <strong>"Setting"</strong> atau <strong>"Pengaturan"</strong> pada Home Screen.
                <br /><br />
                <div style="text-align:center" >
                    <br />
                    <a href="<?php echo $dirhost; ?>/files/images/setting.png" class="fancybox">
                        <img src="<?php echo $dirhost; ?>/files/images/setting.png" width="40%" />
                    </a>
                    <a href="<?php echo $dirhost; ?>/files/images/setting_id.png" class="fancybox">
                        <img src="<?php echo $dirhost; ?>/files/images/setting_id.png" width="40%" />
                    </a>
                </div>
                <br />
                <br />
            </li>
            <li>Tap pada menu <strong>"Security"</strong> atau <strong>"Kemanan"</strong>
                <br /><br />
                <div style="text-align:center">
                    <br />
                    <a href="<?php echo $dirhost; ?>/files/images/security.png" class="fancybox">
                        <img src="<?php echo $dirhost; ?>/files/images/security.png" width="40%" />
                    </a>
                    <a href="<?php echo $dirhost; ?>/files/images/security_id.png" class="fancybox">
                        <img src="<?php echo $dirhost; ?>/files/images/security_id.png" width="40%" />
                    </a>
                </div>
                <br />
                <br />
            </li>
            <li>Scroll kebawah dan centang pada menu <strong>"Unknown Source"</strong> atau <strong>"Sumber Tidak Dikenal"</strong>, yang akan memungkinkan anda untuk menginstall aplikasi dari sumber non Play Store.
                <br /><br />
                <div style="text-align:center">
                    <br />
                    <a href="<?php echo $dirhost; ?>/files/images/unknown_src_id.png" class="fancybox">
                        <img src="<?php echo $dirhost; ?>/files/images/unknown_src_id.png" width="40%" />
                    </a>
                    <a href="<?php echo $dirhost; ?>/files/images/unknown_src.png" class="fancybox">
                        <img src="<?php echo $dirhost; ?>/files/images/unknown_src.png" width="40%" />
                    </a>
                </div>
                <br />
                <br />
            </li>
        </ol>
        <br />
        <div style="text-align:center; display:none" id="step_btn_2">
            <button type="button" class="step_btn btn download_btn" onclick="open_sc('step')"  style="margin-bottom:4px; padding:10px;"><i class='fa fa-folder-open'></i> Buka Cara Pengaturan</button>
            <button type="submit" name="direction" value="download" class="btn download_btn" style="margin-bottom:4px;padding:10px; "><i class='fa fa-download'></i> Lanjut Proses Download</button>
        </div>
        <script language="javascript">
            function open_sc(id){
                $("#"+id).slideToggle();
                display = $("#"+id).attr("data-title");
                if(display == "1"){ 
					$(".step_btn").html("Tutup Cara Pengaturan"); 
					$("#step_btn_2").show();
					$("#"+id).attr("data-title","2"); 
					$.ajax({
						url 	: "<?php echo $dirhost; ?>/zendfront/pages/discoin/download/settings.php",
						data	: {"direction":"save_log","id_coin":"<?php echo $id_coin; ?>"},
						type	: "POST"
					});
				}
                else				  { 
					$(".step_btn").html("Buka Cara Pengaturan"); 
					$("#step_btn_2").hide(); 
					$("#"+id).attr("data-title","1"); 
				}
            }
        </script>
    </form>
</div>

<?php } ?>

<?php if(!empty($direction) && $direction == "save_log"){
		$ip_address = $_SERVER['REMOTE_ADDR'];
		cuser_log("customer","0","Buka Pengaturan But Confused...Wakakaka",@$id_coin);
} ?>

<?php } ?>