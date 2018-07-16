<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	
	$id_list 		= isset($_REQUEST['id_list']) ? $_REQUEST['id_list'] : "";
	$qedit 			= $db->query("SELECT * FROM system_users_client WHERE ID_USER = '".$id_list."' ");
	$dtedit 		= $db->fetchNextObject($qedit);
	
	$jabatan 		=  $db->fob("NAME","system_master_client_users_level"," WHERE ID_CLIENT_USER_LEVEL='".$dtedit->ID_CLIENT_USER_LEVEL."'");
	@$perusahaan	= $db->fob("CLIENT_NAME",$tpref."clients","WHERE ID_CLIENT='".$dtedit->ID_CLIENT."'");
	@$username 		= $dtedit->USER_USERNAME;
	@$photo 		= $dtedit->USER_PHOTO;
	@$name 			= $dtedit->USER_NAME;
	@$email 		= $dtedit->USER_EMAIL;
	@$phone 		= $dtedit->USER_PHONE;
	@$alamat 		= $dtedit->USER_ADDRESS;
	
	@$propinsi 		= $db->fob("NAME","system_master_location","WHERE ID_LOCATION='".$dtedit->USER_PROVINCE."'");
	@$kota 			= $db->fob("NAME","system_master_location","WHERE ID_LOCATION='".$dtedit->USER_CITY."' AND PARENT_ID='".$dtedit->USER_PROVINCE."'");
	@$kecamatan 	= $db->fob("NAME","system_master_location","WHERE ID_LOCATION='".$dtedit->USER_DISTRICT."' AND PARENT_ID='".$dtedit->USER_CITY."'");
	@$kelurahan 	= $db->fob("NAME","system_master_location","WHERE ID_LOCATION='".$dtedit->USER_SUBDISTRICT."' AND PARENT_ID='".$dtedit->USER_DISTRICT."'");
}
?>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h4>Profil Pengelola</h4>
    </div>
    <div class="ibox-content cnt_a user_profile no-padding-lr no-padding-b" style="border:0">
        <div class="col-md-4">
            <div class="thumbnail">
            	<div class="thumbnail-inner" style="max-height:280px; overflow:hidden">
        <?php if(!empty($photo) && is_file($basepath."/files/images/users/".$photo)){?>
                <img src="<?php echo $dirhost; ?>/files/images/users/big/<?php echo $photo; ?>" style='width:100%'>
        <?php }else{ ?>
                <img src="<?php echo $dirhost; ?>/files/images/noimage-m.jpg" style='width:100%'>
        <?php } ?>
        		</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group"><span class="label label-success"><?php echo @$perusahaan; ?></span></div>
            <div class="form-group"><label>Nama:</label><br> <?php echo @$name; ?></div>
            <div class="form-group"><label>Jabatan:</label><br> <?php echo @$jabatan; ?></div>
            <div class="form-group"><label>Email:</label><br> <?php echo @$email; ?></div>
            <div class="form-group"><label>Telephone:</label><br> <?php echo @$phone; ?></div>
            <div class="form-group"><label>Dokumen:</label><br> 
            <?php
            $q_doc			=	$db->query("SELECT * FROM ".$tpref."documents WHERE ID_USER='".$id_list."' AND ID_CLIENT='".$dtedit->ID_CLIENT."'");
            while($dt_doc = $db->fetchNextObject($q_doc)){
            ?>
            <div class='file_list' id="list_<?php echo $dt_doc->ID_DOCUMENT; ?>">
                <a href='javascript:void()' class='download' onclick="download_document('<?php echo $dt_doc->ID_DOCUMENT; ?>')">
                    <?php echo $dt_doc->FILE_DOCUMENT; ?>
                </a>
             </div>
            <?php	
                }
            ?>
            </div>
        </div>
        <div class="col-md-4">
            <?php if(empty($alamat)){?>
            	<div class="form-group"><label>Alamat:</label><br> <?php echo @$alamat; ?></div>
            <?php } ?>
            <?php if(!empty($propinsi)){?>
            	<div class="form-group"><label>Propinsi:</label><br> <?php echo @$propinsi; ?></div>
            <?php } ?>
			<?php if(!empty($kota)){?>
            	<div class="form-group"><label>Kota:</label><br> <?php echo @$kota; ?></div>
            <?php } ?>
            <?php if(!empty($kecamatan)){?>
            	<div class="form-group"><label>Kecamatan:</label><br> <?php echo @$kecamatan; ?></div>
            <?php } ?>
			<?php if(!empty($kelurahan)){?>
            	<div class="form-group"><label>Kelurahan:</label><br> <?php echo @$kelurahan; ?></div>
            <?php } ?>
        </div>
        
        <div class="clearfix"></div>
    </div>
	<div class="clearfix"></div>
</div>
