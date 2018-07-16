<?php
session_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once("../../../../../includes/config.php");
include_once("../../../../../includes/classes.php");
include_once("../../../../../includes/functions.php");
include_once("../../../../../includes/declarations.php");
$id_coin	= isset($_REQUEST['id_coin']) ? $_REQUEST['id_coin'] 	: "";
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {?>
    <?php
	$q_merchant  		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$id_coin."'");
	$dt_merchant 		= $db->fetchNextObject($q_merchant);
	@$nm_merchant		= $dt_merchant->CLIENT_NAME;
	@$url_merchant 		= $dt_merchant->CLIENT_URL;
	@$alamat_merchant 	= $dt_merchant->CLIENT_ADDRESS;
	@$logo_merchant		= $dt_merchant->CLIENT_LOGO;
	?>
    <style type="text/css">
			#contact-pop{
				width:700px; 
			}
		@media only screen and (max-width: 368px) {
			#contact-pop{
				width:auto; 
			}
		}
	</style>
	<script language="javascript">
        function send(id_client){
            var proses_page	= $("#proses_page").val();
            var config 		= JSON.parse("{"+$("#config").val()+"}");
            var dirhost		= config.dirhost;		
            var nama		= $("#c-name").val();
            var email		= $("#c-email").val();
            var pesan		= $("#c-question").val();
            if(nama != "" && email != "" && pesan != ""){
                $("#load_send").html("<img src='"+dirhost+"/files/images/loader_v.gif'>");
                $.ajax({
                    url 	: proses_page,
                    type	: "POST",
                    data	: {"direction":"send","id_client":id_client,"nama":nama,"email":email,"pesan":pesan},
                    success : function(response){
                        $("#main-contact-form input[type='text'],#main-contact-form textarea").val("");
                        $("#load_send").html("<div class='status alert alert-success' style='text-align:center'>Pertanyaan Berhasil Dikirim</div>");
                    }
                })
            }else{
                $("#load_send").html("<div class='status alert alert-danger' style='text-align:center'>Pengisian Form, Belum Lengkap</div>");
            }
        }
    </script>    
<div class="features_items" id="contact_pop" style="padding:10px;" ><!--features_items-->
    <input type="hidden" id="proses_page" value="<?php echo $dirhost; ?>/zendfront/pages/discoin/contact/ajax/proses.php">
    <div class="contact-form">
        <h2 class="title text-center" style="margin-bottom:3px;">Form Kontak</h2>
        <span id="load_send"></span>
        <form id="main-contact-form" class="contact-form row" name="contact-form" method="post">
            <span id="load_send"></span>
            <br>
            <div class="col-sm-3">
            <?php if(is_file($basepath."/files/images/logos/".$logo_merchant)){?>
                <img src="<?php echo $dirhost; ?>/files/images/logos/<?php echo $logo_merchant; ?>" width="100%"/>
            <?php }else{ ?>
                <img src="<?php echo $dirhost; ?>/files/images/no_image.jpg" />
            <?php } ?>
            <br /><br />
            </div>
            <div class="col-sm-9">
                <div class="form-group col-md-6">
                    <input type="text" id="c-name" class="form-control" required placeholder="Name">
                </div>
                <div class="form-group col-md-6">
                    <input type="email" id="c-email" class="form-control" required placeholder="Email">
                </div>
                <div class="form-group col-md-12">
                    <input type="text" name="subject" class="form-control" required placeholder="Subject">
                </div>
                <div class="form-group col-md-12">
                    <textarea id="c-question" style="width:100%; height:60px" required class="form-control" placeholder="Your Message Here"></textarea>
                </div> 
                <div class="form-group col-md-12">
                    <button type="button" id="msg_button" class="btn btn-primary pull-right" onclick="send('<?php echo $id_coin; ?>')">Kirim Pertanyaan</button>
                </div>
            </div>
            
        </form>
    </div>
</div>
<?php } ?>