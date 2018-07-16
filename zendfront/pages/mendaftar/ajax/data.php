<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	$direction 	= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: ""; 
	$id_jabatan = isset($_REQUEST['id_jabatan'])	? $_REQUEST['id_jabatan'] 	: ""; 
	$nm_jabatan = isset($_REQUEST['nm_jabatan'])	? $_REQUEST['nm_jabatan'] 	: ""; 

	if((!empty($direction) && $direction == "get_city")){
		if(!empty($_REQUEST['propinsi'])){ $propinsi = isset($_REQUEST['propinsi']) 	? $_REQUEST['propinsi'] : ""; }
	?>
            <div class="formSep">
                <label class="req">Kota</label>
                <select name="kota" id="kota" class="span10 validate[required] text-input">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' >
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php	
	}
	if((!empty($direction) && $direction == "get_kecamatan")){
		if(!empty($_REQUEST['kota']))	{ $kota 	 = isset($_REQUEST['kota']) 	 ? $_REQUEST['kota'] 	 : ""; }
	?>
            <div class="formSep">
                <label class="req">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" class="span10 validate[required] text-input">
                    <option value=''>--PILIH KECAMATAN--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kota."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' >
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php	
	}
	if((!empty($direction) && $direction == "get_kelurahan")){
		if(!empty($_REQUEST['kecamatan'])){ $kecamatan 	 = isset($_REQUEST['kecamatan']) ? $_REQUEST['kecamatan'] 	 : ""; }
	?>
            <div class="formSep">
                <label class="req">Kelurahan</label>
                <select name="kelurahan" id="kelurahan" class="span10 validate[required] text-input">
                    <option value=''>--PILIH KELURAHAN--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kecamatan."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' >
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php	
	}
	if((!empty($direction) && $direction == "get_community")){
		$code = isset($_REQUEST['code']) 	? $_REQUEST['code'] : ""; 
		$id_purple = $db->fob("ID_CLIENT","system_users_client"," WHERE USER_CODE='".$code."'");

	?>
         <div class="formSep">
            <label class="req">Komunitas Merchant</label>
            <select name="id_com" id="id_com" class="span10 validate[required] text-input">
                <option value=''>--PILIH KOMUNITAS MERCHANT--</option>
                <?php
                $query_kom = $db->query("SELECT * FROM ".$tpref."communities WHERE BY_ID_PURPLE = '".$id_purple."' ORDER BY NAME ASC");
                while($data_kom = $db->fetchNextObject($query_kom)){
                ?>
                    <option value='<?php echo $data_kom->ID_COMMUNITY; ?>' >
                        <?php echo $data_kom->NAME; ?>
                    </option>
            <?php } ?>
            </select>
            <input type="hidden" id="id_pur" value="<?php echo @$id_purple; ?>" />
        </div>
    <?php
	}
	
	
	if((!empty($direction) && $direction == "get_page")){
			$page_name = "";
			switch($id_jabatan){
				case "1":
					$page_list = array("4","204","118","9","207","128","17","202","191","183","205","196","121","208","209","129","168");
				break;
				case "2":
					$page_list = array("207","128","17","129");
				break;
				case "3":	
					$page_list = array("121","208","209","129");
				break;
				case "4":	
					$page_list = array("202","191","183","205","196","121","208","209","129");
				break;
			}
			foreach($page_list as &$page){
				@$q_page 	= $db->query("SELECT ID_PARENT,NAME,DESCRIPTION FROM system_pages_client 
										  WHERE ID_PAGE_CLIENT = '".$page."' AND ID_PARENT != '0'");	
				$dt_page	= $db->fetchNextObject($q_page);
				
				if((!empty($dt_page->NAME) && $dt_page->NAME != "0") && !empty($dt_page->NAME)){
					$page_name .= "<li><b>".$dt_page->NAME."</b><br>".@$dt_page->DESCRIPTION."</li>"; 
				}
			}
			$message = "<b>".$nm_jabatan."</b> Bertugas Mengelola aplikasi-aplikasi antara lain <ol>".$page_name."</ol>";
			echo $message;
	}
	
	
	if((!empty($direction) && $direction == "get_jabatan")){
		if(!empty($id_jabatan)){ $condition = " AND ID_CLIENT_USER_LEVEL = '".$id_jabatan."' "; } 
		$q_jabatan = $db->query("SELECT * FROM system_master_client_users_level 
							 	 WHERE 
								 	ID_CLIENT_USER_LEVEL IS NOT NULL 
									AND ID_CLIENT != '1' 
									AND ACTIVE_STATUS ='3' 
									".@$condition." 
								 ORDER BY NAME ASC");
		?>
            <option value="">--PILIH JABATAN--</option>
            <?php while($dt_jabatan = $db->fetchNextObject($q_jabatan)){?>
            <option value="<?php echo $dt_jabatan->ID_CLIENT_USER_LEVEL; ?>"
            		<?php if(!empty($id_jabatan) && $id_jabatan == $dt_jabatan->ID_CLIENT_USER_LEVEL){?>
                    	selected
					<?php } ?>>
                <?php echo $dt_jabatan->NAME; ?>
            </option>
            <?php } ?>
	<?php 
	}
	
}else{  
	defined('mainload') or die('Restricted Access'); 
}
?>