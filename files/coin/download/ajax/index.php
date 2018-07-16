<?php
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include ("../../../includes/config.php");
include ("../../../includes/classes.php");
include ("../../../includes/functions.php");
include ("../../../includes/declarations.php");

$file_string 	= $_REQUEST['filename'];
$direction 	  	= isset($_REQUEST['direction']) ? $_REQUEST['direction']:"";
$file_str 	  	= explode(".",$file_string);
$file_name     	= $file_str[0];
$user_agent     = $_SERVER['HTTP_USER_AGENT'];
if(!empty($file_name)){
	$q_merchant  		= $db->query("SELECT * FROM ".$tpref."clients WHERE CLIENT_APP='".$file_name."'");
	$dt_merchant 		= $db->fetchNextObject($q_merchant);
	@$id_coin 			= $dt_merchant->ID_CLIENT;
	@$nm_merchant		= $dt_merchant->CLIENT_NAME;
	@$email_merchant 	= $dt_merchant->CLIENT_EMAIL;
	@$url_merchant 		= $dt_merchant->CLIENT_URL;
	@$alamat_merchant 	= $dt_merchant->CLIENT_ADDRESS;
	@$deskripsi_merchant= $dt_merchant->CLIENT_DESCRIPTIONS;
	
	@$meta_title 		= $dt_merchant->META_TITLE;
	@$meta_keywords 	= $dt_merchant->META_KEYWORDS;
	@$meta_description 	= $dt_merchant->META_DESCRIPTION;
	
	@$logo_merchant		= $dt_merchant->CLIENT_LOGO;
	@$warna_merchant	= $dt_merchant->COLOUR;
	@$app_merchant		= $dt_merchant->CLIENT_APP;
	@$color				= explode(";",$dt_merchant->COLOUR);
	@$bg_1 				= $color[0]; //#993366
	@$bg_2 				= $color[1]; //#732b4f
	if(empty($bg_1))	{ $bg_1 = "#993366"; }
	if(empty($bg_2))	{ $bg_2 = "#732b4f"; }
}
$user_os        = getOS($user_agent);
$file_folder	= $file_name;
if($file_name == "sempoa"){ $file_name = ""; }
$done = 1;

$file_path 	= $file_folder."/".strtoupper($file_name)."COIN.apk";
if($user_os == "Android"){
	$file_path 	= $file_folder."/".strtoupper($file_name)."COIN.apk";
	$done = 1;
}
if($user_os == "BlackBerry"){
	$file_path 	= $file_folder."/".strtoupper($file_name)."COIN.jad";
	$done = 1;
}
	$ip_address 		= $_SERVER['REMOTE_ADDR'];
	cuser_log("customer","0","Melihat Halaman Discoin ".strtoupper($nm_merchant)." Dari ".$user_os,@$id_coin); 
	$num_ip 	 		= $db->recount("SELECT IP_ADDRESS FROM ".$tpref."logs WHERE IP_ADDRESS = '".$ip_address."'");
	$downloaded			= $db->recount("SELECT ID_LOGS FROM ".$tpref."logs WHERE ACTIVITY LIKE '%download%' AND ID_CLIENT ='".$id_coin."'");
	if(!empty($direction) && $direction == "download"){
		include $call->inc("files/coin/download/includes","proses.php");
	}else{ ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <meta http-equiv="pragma" content="no-cache" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title><?php echo @$meta_title; ?></title>
        <meta name="keywords" content="Diskon,potongan, diskon harga, potongan harga, <?php echo $meta_keywords; ?>"/>
        <meta name="description" content="<?php echo @$meta_description; ?>"/>
        <meta property="og:title" content="<?php echo @$meta_title; ?>"/>
        <meta property="og:image" content="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant; ?>"/>
        <meta property="og:type" content="Kartu Member Digital <?php echo $nm_merchant; ?>" /> 
        <meta property="og:site_name" content="Discoin <?php echo strtoupper($file_name); ?>"/>
        <meta property="og:description" content="<?php echo @$deskripsi_merchant; ?>"/>
        <meta property="og:url" content="<?php echo $dirhost; ?>/<?php echo $app_merchant; ?>.coin" />
        <meta name="robots" content="INDEX, FOLLOW">
        <meta content='id_ID' property='og:locale:alternate'/>
        <meta content='ID' name='geo.country'/>
        <meta content='Indonesia' name='geo.placename'/>
        
        <link rel="stylesheet" href="<?php echo $dirhost; ?>/files/coin/download/css/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo $dirhost; ?>/files/coin/download/css/bootstrap/css/bootstrap-responsive.css">
		<link rel="stylesheet" media="screen" href="<?php echo $dirhost; ?>/files/coin/download/slider/css/sequencejs-theme.modern-slide-in.css" />
         <script type="text/javascript" src="<?php echo $dirhost; ?>/libraries/fancybox/js/jquery-1.8.2.min.js"></script>
         <script language="javascript">
			$(window).scroll(function() {
			   $.ajax({
					url		: "<?php echo $dirhost; ?>/files/coin/download/ajax/proses.php",
					type	: "POST",
					data	: {"direction":"save_scroll","id_client":"<?php echo $id_coin; ?>"},
					success: function(response){  }	
			   })
			});		 
		 </script>
		<?php include $call->lib("fancybox"); ?>
       
		<!--<script>
		 if (typeof jQuery == 'undefined'){
		    document.write(unescape('%3Cscript src="<?php echo $dirhost; ?>/files/coin/download/slider/js/jquery.js" %3E%3C/script%3E'));
		 }
		</script>-->
		<script src="<?php echo $dirhost; ?>/files/coin/download/slider/js/jquery.sequence.js"></script>
		<script src="<?php echo $dirhost; ?>/files/coin/download/slider/js/sequencejs-options.modern-slide-in.js"></script>
	  	<script src="<?php echo $dirhost; ?>/files/coin/download/slider/js/js.js"></script>
        <link rel="stylesheet" href="<?php echo $dirhost; ?>/files/coin/download/css/customize.css">
		<style>
        body { 
            padding-top: 20px; background: #f6f6f6; 
            font-size: 100%;
			line-height:25px;
			
        }
		.td_logo{ width:20%; }
        html, body { height: 100%; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; margin:0; padding:0; }
            .bg_d { background: url(<?php echo $web_tpl_dir; ?>/img/patterns/bg_d.png) repeat 0 0 }
            header {
                padding: 14px 0px 10px;
                background: none repeat scroll 0% 0% #E9E9E9;
                border-bottom: 1px solid #CFCFCF;
                box-shadow: 0px 1px 0px #FFF;
            }
			form table td{
				padding:3px;	
			}
			input[type="text"],textarea{
				padding:5px;
				border:1px solid #CCC;	
				border-radius:2px;
				-moz-border-radius:2px;
				-webkit-border-radius:2px;
				font-family:"Century Gothic"; 
			}
			.w-box + .w-box { margin-top: 20px }
			.w-box-header { height: 32px; line-height: 32px; padding: 0 10px 1px; background: <?php echo $bg_1; ?>; color: #fff; font-size: 13px; font-weight:bold; font-family:"Century Gothic"; 
			border-radius: 8px 8px 0 0;
			 }
			.w-box-header h4 { display: inline-block; font-size: 11px; font-weight: 700; margin: 5px 0 0 0; text-transform: uppercase; width:90%; float:left; }
			
			.green-box{
				border:1px solid #9BD0AE;
				background:#B1E4BE;
				padding:5px;
				width:25%;	
				margin:5px;
				font-size:3vmin;
				color:#4F8A69;
				text-align:center;
				font-weight:bold;
			}
			.black-box{
				border:1px solid <?php echo $bg_1; ?>;
				background:#FFF;
				padding:15px 10px 5px 10px;
				margin-bottom:5px;
				text-align:justify;
				border-radius: 0 0 8px 8px;
			}
			.same-height{
				height:500px;
			}
			.overflow{
				overflow:scroll;
			}

			.disc_st{
				margin:5px 0 5px 0;
				padding:3px 3px 10px 3px; 
				 min-height:30px; 
				 max-height:100px; 
				 overflow:scroll; 
				 border:1px solid #F2778F; 
				 background:#FADAE2	;
				border-radius:2px;
				-moz-border-radius:2px;
				-webkit-border-radius:2px;
			}
		.download_btn {
		
		  background: <?php echo $bg_2; ?>;
		  background-image: -webkit-linear-gradient(top, <?php echo $bg_1; ?>,<?php echo $bg_2; ?>);
		  background-image: -moz-linear-gradient(top, <?php echo $bg_1; ?>,<?php echo $bg_2; ?>);
		  background-image: -ms-linear-gradient(top, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
		  background-image: -o-linear-gradient(top, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
		  background-image: linear-gradient(to bottom, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
		  -webkit-border-radius: 5;
		  -moz-border-radius: 5;
		  border-radius: 5px;
		  text-shadow: 0px 1px 3px #666666;
		  font-family:"Century Gothic"; 
		  color: #ffffff;
		  padding: 4px 10px 3px 10px;
		  text-decoration: none;
		  text-align:left;
		}
		.download_title{ font-size:19px; } 
		.download_content{ font-size:14px; } 
		.download_btn:hover {
		  background: <?php echo $bg_2; ?>;
		  background-image: -webkit-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: -moz-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: -ms-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: -o-linear-gradient(top, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  background-image: linear-gradient(to bottom, <?php echo $bg_2; ?>, <?php echo $bg_1; ?>);
		  text-decoration: none;
		  color: #ffffff;
		}
		@media only screen and (min-width: 1000px) AND (max-width: 1518px) {
			.gallery_pic{ height:150px; }
			.gallery_info { font-size:12px; line-height:15px;}
			
		}
		@media only screen and (max-width: 768px) {
			 body { font-size: 15px; line-height:15px; }
			 .w-box-header{ font-size: 16px; }
			 .same-height{ height:auto; }
			.download_title{ font-size:18px; } 
			.download_content{ font-size:13px; } 
		}
		@media only screen and (max-width: 568px) {
			 body { font-size: 14px; line-height:15px;}
			 .w-box-header{ font-size: 15px; }
			  #sequence .title {
				  background: <?php echo $bg_2; ?>;
				  background-image: -webkit-linear-gradient(top, <?php echo $bg_1; ?>,<?php echo $bg_2; ?>);
				  background-image: -moz-linear-gradient(top, <?php echo $bg_1; ?>,<?php echo $bg_2; ?>);
				  background-image: -ms-linear-gradient(top, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
				  background-image: -o-linear-gradient(top, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
				  background-image: linear-gradient(to bottom, <?php echo $bg_1; ?>, <?php echo $bg_2; ?>);
			 }
			 .download_btn{ width:100%; }
			.download_title{ font-size:18px; } 
			.download_content{ font-size:13px; } 
			.gallery_pic{ height:none;}
		}
		@media only screen and (max-width: 518px) {
			 body { font-size: 13px; line-height:15px;}
			 .w-box-header{ font-size: 13px; }
			 .download_btn{ width:100%; }
			.download_title{ font-size:18px; } 
			.download_content{ font-size:13px; } 
			.gallery_pic{ height:100%; }
		}
		@media only screen and (max-width: 468px) {
			 body { font-size: 13px; line-height:15px;}
			 .w-box-header{ font-size: 14px; }
			 .download_btn{ width:100%; }
			.download_title{ font-size:17px; } 
			.download_content{ font-size:12px; } 
			.gallery_pic{ height:100%; }
		}
		@media only screen and (max-width: 418px) {
			 body { font-size: 12px; line-height:15px;}
			 .w-box-header{ font-size: 13px; }
			 .download_btn{ width:100%; }
			.download_title{ font-size:17px; } 
			.download_content{ font-size:12px; } 
			.gallery_pic{ height:100%; }
		}
		@media only screen and (max-width: 368px) {
			 body { font-size: 11px; line-height:15px;}
			 .w-box-header{ font-size: 12px; }
			 .download_btn{ width:100%; }
			 .download_title{ font-size:17px; } 
			 .download_content{ font-size:12px; } 
			.gallery_pic{ height:100%; }
		}
		@media only screen and (min-width: 321px) and (max-width: 480px) and (orientation: landscape) {
			 body { font-size: 8px; line-height:15px;}
			 .w-box-header{ font-size: 9px; }
			 .download_btn{ width:100%; }
			.download_title{ font-size:17px; } 
			.download_content{ font-size:12px; } 
			.gallery_pic{ height:100%; }
		}
      	</style>
	</head>
<body class="bg_d" onLoad="autorespon()">
	<div id="wrapper">
    <header style="background:none">
       <img src="<?php echo $web_tpl_dir; ?>img/beoro_logo.png" alt="Beoro Admin">
       <div class='green-box' style="right:0; margin-top:20px; position:absolute; top:0">Free Apps</div>
    </header>
    <input type="hidden" id="data_page" value="<?php echo $dirhost; ?>/files/coin/download/ajax/data.php">
    <input type="hidden" id="proses_page" value="<?php echo $dirhost; ?>/files/coin/download/ajax/proses.php">
    <input type='hidden' id='config' value='"id_client":"<?php echo $id_coin; ?>","dirhost":"<?php echo $dirhost; ?>","realtime":"<?php echo @$realtime; ?>"'/>
  
  
    <div class="sequence-theme">
        <div id="sequence">
            <img class="sequence-prev" src="<?php echo $dirhost; ?>/files/coin/download/slider/images/bt-prev.png"/>
            <img class="sequence-next" src="<?php echo $dirhost; ?>/files/coin/download/slider/images/bt-next.png"/>
            <ul class="sequence-canvas">
                <li>
                    <h2 class="title">Toko Online & Keranjang Belanja</h2>
                    <h3 class="subtitle">Fitur katalog online, produk-produk dari komunitas merchant-merchant.</h3>
                    <img class="model" src="<?php echo $dirhost; ?>/files/coin/download/slider/images/2.png" />
                </li>
                <li class="animate-in">
                    <h2 class="title">Kode Identitas Pelanggan (COIN)</h2>
                    <h3 class="subtitle">COIN atau Community Identification Number</h3>
                    <img class="model" src="<?php echo $dirhost; ?>/files/coin/download/slider/images/1.png" />
                </li>
                <li>
                    <h2 class="title">Daftar Diskon Brand Komunitas</h2>
                    <h3 class="subtitle">Daftar merchant-merchant yang memberikan Diskon belanja dari pemilik COIN aplikasi Discoin</h3>
                    <img class="model" src="<?php echo $dirhost; ?>/files/coin/download/slider/images/3.png" />
                </li>
                <li>
                    <h2 class="title">Pelanggan Komunitas</h2>
                    <h3 class="subtitle">Daftar member komunitas pelanggan, yang memiliki COINnya masing-masing</h3>
                    <img class="model" src="<?php echo $dirhost; ?>/files/coin/download/slider/images/4.png" />
                </li>
            </ul>

            <!--<ul class="sequence-pagination">
                <li><img src="<?php echo $dirhost; ?>/files/coin/download/slider/images/tn-model1.png" /></li>
                <li><img src="<?php echo $dirhost; ?>/files/coin/download/slider/images/tn-model2.png" /></li>
                <li><img src="<?php echo $dirhost; ?>/files/coin/download/slider/images/tn-model3.png" /></li>
            </ul>-->

        </div>
    </div>
    
  	
    <br>
    
    
  	<div class="container">
        <div class="row-fluid">
            
            <div class="span6">
                <div class="w-box">
                    <h3 class="w-box-header" >Aplikasi Discoin <?php echo $nm_merchant; ?></h3>
                    <div class="w-box-content black-box same-height" style="position:relative; overflow:scroll">
                       
                         <div class="span6">
                              <img src="<?php echo $dirhost; ?>/files/images/alldiscount.png"  class="span12"/>
                          </div>
                          
                          <div dengan="span6">
                              <div class="big_text">
                                ALL DISCOUNTS % <br>
                                SETIAP HARI<br>
                                HANYA UNTUK <br>
                                <img src="<?php echo $dirhost; ?>/files/images/02.png" width="50%"/>
                                <br>
                                KOMUNITAS DISCOIN DARI <br>
                                <?php echo $nm_merchant; ?>
                              </div>
                          </div>
                          
                          <div  class="span12" style="vertical-align:top;">
                          	<div class="w-box">
                            Hanya dengan menunjukan COIN dari apps Discoin <?php echo $nm_merchant; ?>, kamu dapat menikmati potongan harga / diskon belanja setiap hari, di <?php echo $nm_merchant; ?> dan di seluruh brand/outlet komunitas, yang ada di aplikasi Discoin in
                            <br>
                            <br>
                            <div  class="big_text">
                                LET's GO SHOPPING IN <?php echo $nm_merchant; ?>
                                <br>
                                AND 
                            </div>
                            <form method="post" action="">
                                <div style="text-align:center; margin-top:10px">
                                    <button type="submit" name="direction" class="btn download_btn" value="download">
                                        <img src="<?php echo $dirhost; ?>/files/images/android.gif" style="width:25%; float:left">
                                        <div style="margin-top:8px; margin-bottom:0">
                                            <span class="download_title">Download</span><br>
                                            <span class="download_content"> Discoin <?php echo $nm_merchant; ?> <br>(<?php echo $downloaded; ?> Kali)</span>
                                        </div>
                                        
                                    </button>
                                </div>
                            </form>
                            <br><br>
                            <div  class="big_text">
                                DAN JADILAH SALAH SATU KELUARGA KOMUNITAS
                            </div>
                            <br><br>
                            <!--<a href="javascript:void();" onclick="ajax_fancybox('<?php echo $dirhost; ?>/products/community/pages/merchant/ajax/produk.php?parent_id=<?php echo $id_coin; ?>')">
                                <button type="button" class="btn download_btn" value="show" style="padding:8px; font-size:100%; text-align:center">
                                    Lihat Katalog <b><?php echo $nm_merchant; ?></b>
                                </button>
                            </a>-->	
                            </div>
                          </div>
                    </div>
                    
                    
                </div>
			</div>
            
            <div class="span6">
                <div class="w-box">
                <?php include $call->inc("files/coin/download/includes","catalogue.php"); ?>
                </div>
            </div>
            
        </div>
    </div>
  	<div class="container">
        <div class="row-fluid">
            <div class="span6">
                <div class="w-box">
				<?php include $call->inc("files/coin/download/includes","merchants.php"); ?>
                </div>
            </div>
            
            <div class="span6"> 
				<div class="w-box">
                    <h3 class="w-box-header" >Cara Download Discoin <?php echo $nm_merchant; ?></h3>
                    <div class="w-box-content black-box same-height overflow">
                        <form method="post" action="" id="form_warning">
                        <p style="padding:4px;">
                            Sebelum mendownload aplikasi Discoin ini, pastikan jika perangkat seluler Androidmu mengijinkan install aplikasi dari <strong>"Sumber yang Tidak Dikenal"</strong> atau <strong>non Play Store.</strong>
                        </p>
                        <div style="text-align:center">
                            <button type="button" id="step_btn" class="myButton green" onclick="open_sc('step')">Buka Cara Pengaturan</button>
                        </div>
                        <br />
                        <ol id="step" style="display:none">
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
                        <script language="javascript">
                            function open_sc(id){
                                display = $("#"+id).css("display");
                                $("#"+id).slideToggle();
                                if(display == "block"){ $("#step_btn").html("Buka Cara Pengaturan"); }
                                else				 { $("#step_btn").html("Tutup Cara Pengaturan"); }
                            }
                        </script>
                        </form>
                    </div>
                </div>
             </div>
        </div>
    </div>
             
  	<div class="container">
        <div class="row-fluid">
             <div class="span12">
             
                <div class="w-box">
                    <div id="tbl_msg">
                        <h3 class="w-box-header" >Pertanyaan</h3>
                        <div class="w-box-content black-box" >
                        <form method="post" action="" id="formSend" >
                            <span id="load_send"></span>
                            <div style="text-align:center">Haii, selamat pagi, ada yang bisa dibantu ?</div>
                            <br>
                            <table style="width:100%;" border='1' cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="vertical-align:top; width:20%">Nama</td>
                                    <td style="vertical-align:top; "><input type="text" id="c-name"></td>
                                </tr>
                                <tr>
                                    <td style="vertical-align:top; ">Email</td>
                                    <td style="vertical-align:top;"><input type="text" id="c-email"></td>
                                </tr>
                                <tr>
                                  <td colspan="2">Pertanyaan</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td style="vertical-align:top;"><textarea id="c-question" style="width:90%; height:60px"></textarea></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="vertical-align:top;">
                                        <button type="button" id="msg_button" class="myButton green" onclick="send('<?php echo $id_coin; ?>')">Kirim Pertanyaan</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        </div>
                    </div>
                    <div id="tbl_msg2"></div>
            	</div>
                
            </div>
        </div>
	</div>
        
        
   </div>
</body>
</html>
<?php } ?>