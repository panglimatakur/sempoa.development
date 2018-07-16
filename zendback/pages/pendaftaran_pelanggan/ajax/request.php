<?php
session_start();
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && !empty($_SESSION['cidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$coin 			= isset($_REQUEST['coin']) ? $_REQUEST['coin']:"";
	$id_customer 	= isset($_REQUEST['id_customer']) ? $_REQUEST['id_customer']:"";
	$cidkey 		= isset($_REQUEST['cidkey']) ? $_REQUEST['cidkey']:"";
	@$active_id		= $db->fob("CUSTOMER_STATUS",$tpref."customers"," WHERE ID_CUSTOMER='".$id_customer."'");
	if(empty($active_id)){ $active_id = 0; }
?>
    <div class="col-md-6" style="margin-left:6px;">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h4>Permintaan Aktivasi No COIN Pelanggan</h4>
            </div>
            <div class="form-group">
            	<label>Permintaan Status Aktifasi</label>
             	<?php if($active_id <= 1){?>
                    <select id="active_id">
                        <?php if($active_id < 1){
							$pesan = "Permintaan Untuk Mengaktifkan No COIN ".$coin;	
						?>
                            <option value="1" >&raquo; KIRIM PERMINTAAN AKTIFASI &laquo;</option>
                        <?php } ?>
                        <?php if($active_id == 1){
							$pesan = "Permintaan Untuk Membatalkan No COIN ".$coin;	
						?>
                            <option value=""> &raquo; BATALKAN PERMINTAAN AKTIFASI &laquo;</option>
                        <?php } ?>
                    </select>
				<?php }else{ 
                	echo msg("Nomor COIN ".$coin." untuk pelanggan ini sudah di aktifkan, silahkan kirim pesan, untuk keperluan pembatalan status aktifasi","warning");
					$pesan = "Permintaan Untuk Membatalkan No COIN ".$coin." karena........";	
				?>
                <input type="hidden" id='active_id' value='<?php echo @$active_id; ?>' />
                <?php	
                 } 
				?>
            </div>
            <div class="form-group">
                <label >Pesan</label>
                <textarea style='width:99%' id="pesan_request"><?php echo @$pesan; ?></textarea>
                <input type="hidden" id='cidkey' name='cidkey' value='<?php echo $cidkey; ?>' />
            </div>
            <div class="form-group">
                <button type="button" id="send_activation" value='<?php echo $id_customer; ?>' class='btn btn-sempoa-1'>Kirim Pesan Aktifasi</button><span id="activate_loader"></span>
            </div>
       </div>
   </div>
<?php
}else{
	defined('mainload') or die('Restricted Access');
}
?>