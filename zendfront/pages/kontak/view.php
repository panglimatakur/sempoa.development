<?php defined('mainload') or die('Restricted Access'); ?>
<section id="blog" >
	<?php 
        if(!empty($msg)){
            switch ($msg){
                case "1":
                    echo msg("Terimakasih, Pesan anda telah dikirim, dan akan kami proses secepatnya","success");
                break;
                case "2":
                    echo msg("Pengisian Form Belum Lengkap","error");
                break;
            }
        }
    ?>
    <div class="container padding-top">
        <div class="row">
            <div class="col-md-7">
                <form name="formal" action="" method="post">
                <div class="form-group col-md-6">
                   <label class="req" >Nama</label>
                   <input type="text" name="ct_nama" value="" class="form-control validate[required]" d/>
                </div>
                <div class="form-group col-md-6">
                  <label class="req">Email (Diperlukan untuk membalas pesan)</label>
                  <input type="text" name="ct_email" value="" class="form-control validate[required]"/>
                </div>
                <div class="form-group col-md-12">
                  <label class="req" >Pesan</label>
                  <textarea name="ct_pesan" value="" class="form-control"></textarea>
                </div>
                <a name="cap"></a>
                <div class="form-group col-md-6">
                     <label><strong>Captcha Text</strong></label>
                     <br>
                    <img src="<?php echo $dirhost; ?>/libraries/captcha/captcha.php" id="captcha" />
                    <!-- CHANGE TEXT LINK -->
               </div>
               <div class="form-group col-md-6">
                     <label><strong>Tuliskan Captcha Text Disamping: </strong></label>
                     <input type="text" name="captcha" id="captcha-form" class="form-control"  autocomplete="off" />
                     <a href="#cap" onclick="
                        document.getElementById('captcha').src='<?php echo $dirhost; ?>/libraries/captcha/captcha.php?'+Math.random();
                        document.getElementById('captcha-form').focus();"
                        id="change-image">Tidak Terbaca? Ganti Text.</a>
                </div>
                <div class="form-group col-md-12">
                  <button name="direction" type="submit" id="button_send" class="btn btn-primary" value="send">Kirim Pesan</button>
                </div>
                </form>
            </div>
            <div class="col-md-5" >
                <p class="form-group">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3961.100582312984!2d107.63343700000001!3d-6.878551999999999!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e70f7ef2f079%3A0x5eed41876424636c!2sJl.+Ligar+Agung%2C+Cimenyan%2C+Bandung%2C+Jawa+Barat+40191!5e0!3m2!1sid!2sid!4v1422702598115" width="425" height="325" frameborder="0" style="border:0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </p>
            </div>
        </div>
		</div>
     </div>
<section>	
			