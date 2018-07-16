<!DOCTYPE html>
<html>


<!-- Site: HackForums.Ru | E-mail: abuse@hackforums.ru | Skype: h2osancho -->
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sempoa  | Login 2</title>

    <link href="<?php echo $web_btpl_dir; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $web_btpl_dir; ?>font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet">

    <link href="<?php echo $web_btpl_dir; ?>css/animate.css" rel="stylesheet">
    <link href="<?php echo $web_btpl_dir; ?>css/style.css" rel="stylesheet">
    <style type="text/css">
		.logo{
			margin-bottom:10px;
			width:50%;	
		}
		.logo img{ 
			width:100%; 
			margin-left:40%;
			margin-right:auto;
		}
	</style>
    <script src="<?php echo $web_btpl_dir; ?>js/jquery-2.1.1.js"></script>
	<script language="javascript">
		$(document).ready(function() {
            $(".btn-toggle").on("click",function(){
				$(".form-additional").slideToggle( "fast", "linear" );
			})
        });
	</script>
</head>

<body style="box-shadow:none">

    <div class="loginColumns animated fadeInDown">
        <div class="row">
					<?php 
                        if(!empty($msg)){
                            switch ($msg){
                                case "1":
                                    echo msg("Info Username dan Password Tidak Cocok","error");
                                break;
                                case "2":
                                    echo msg("Pengisian Form Login Belum Lengkap","error");
                                break;
                            }
                        }
                    ?>
            <div class="col-md-6 text-white">
                <h2 class="font-bold">Selamat Datang</h2>
                <p>Ruang Kendali Sempoa</p>
                <br>
                Ruang Kendali Sempoa adalah ruang untuk mengelola, memonitor, interaksi, dan mengendalikan bisnis anda secara digital, menggunakan fitur-ftur yang saling berinteraksi untuk mendukung bisnis dan usaha anda. 
                <br>
                <br>
                Ruang Kendali ini juga terdapat forum untu pertanyaan-pertanyaan kepada sempoa selaku penyedia layanan, perihal tips-tips dan trik memaksimalkan pemasaran anda menggunaan Discoin atau layanan Sempoa lainnya

            </div>
            <div class="col-md-6">
                <div class="ibox-content form-additional" style="display:none">
                    <div  class="logo"><img src="<?php echo $dirhost; ?>/files/images/sempoa_label.png"></div>
                    <p>
                        Masukan email anda, untuk mengembalikan password anda seperti semula, yang akan di kirimkan ke email tersebut.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="">
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" required>
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b">
                                	<i class="fa fa-asterisk" aria-hidden="true"></i> Kembalikan Password
                                </button>
                                <a class="btn btn-sm btn-white btn-block btn-toggle" href="javascript:void()">
                                    Kembali Ke Form Masuk
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            
                <div class="ibox-content form-additional">
                    <div  class="logo"><img src="<?php echo $dirhost; ?>/files/images/sempoa_label.png"></div>
            
                    <form role="form" action="" method="post">
                        <div class="form-group">
                            <input type="email" id="username" name="username" placeholder="Email"  class="form-control" required value="<?php echo @$username; ?>">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" required id="password" name="password" placeholder="Password" >
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b" name="direction" value="login">
                        	<i class="fa fa-sign-in"></i> Masuk Ruang Kendali
                        </button>

                        <a class="btn btn-sm btn-white btn-block btn-toggle" href="javascript:void()">
                        	Lupa Password
                        </a>
                        <a class="btn btn-sm btn-white btn-block" href="<?php echo $dirhost; ?>/website/mendaftar">
                        	Belum Punya Akun Sempoa ?
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6 text-white">
                Copyright Sempoa Tech Indonesia
            </div>
            <div class="col-md-6 text-right text-white">
               <small>© 2017-2018</small>
            </div>
        </div>
    </div>

</body>


<!-- Site: HackForums.Ru | E-mail: abuse@hackforums.ru | Skype: h2osancho -->
</html>
