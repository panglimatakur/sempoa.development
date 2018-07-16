<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$direction 		= isset($_REQUEST['direction']) 	? $_REQUEST['direction'] 	: "";
	$propinsi 		= isset($_REQUEST['propinsi']) 		? $_REQUEST['propinsi'] 	: "";
	$kota 			= isset($_REQUEST['kota']) 			? $_REQUEST['kota'] 		: "";
	$kecamatan 		= isset($_REQUEST['kecamatan']) 	? $_REQUEST['kecamatan'] 	: "";
	$id_parent 		= isset($_REQUEST['id_parent']) 	? $_REQUEST['id_parent'] 	: '0';
}else{
	defined('mainload') or die('Restricted Access');
}

	if(!empty($direction) && ($direction == "get_kota" || !empty($propinsi))){
	?>
        <div class="form-group col-md-3">
            <label>Kota / Kabupaten</label>
            <select name="kota" id="kota" class="form-control location" onchange="get_kecamatan(this)">
                <option value=''>--PILIH KOTA--</option>
                <?php
                $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                while($data_kota = $db->fetchNextObject($query_kota)){
                ?>
                    <option value='<?php echo $data_kota->ID_LOCATION; ?>' <?php if(!empty($kota) && $kota == $data_kota->ID_LOCATION){?> selected<?php } ?> title="<?php echo $data_kota->ID_LOCATION; ?>">
                        <?php echo $data_kota->NAME; ?>
                    </option>
            <?php } ?>
            </select>
        </div>
    <?php }

	if(!empty($direction) && ($direction == "get_kecamatan" || !empty($kota))){
	?>
        <div class="form-group col-md-3">
            <label>Kecamatan</label>
            <select name="kecamatan" id="kecamatan" class="form-control location" onchange="get_kelurahan(this)">
                <option value=''>--PILIH KECAMATAN--</option>
                <?php
                $query_kecamatan = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kota."' ORDER BY NAME ASC");
                while($data_kecamatan = $db->fetchNextObject($query_kecamatan)){
                ?>
                    <option value='<?php echo $data_kecamatan->ID_LOCATION; ?>' <?php if(!empty($kecamatan) && $kecamatan == $data_kecamatan->ID_LOCATION){?> selected<?php } ?>>
                        <?php echo $data_kecamatan->ID_LOCATION; ?> - <?php echo $data_kecamatan->NAME; ?>
                    </option>
            <?php } ?>
            </select>
        </div>
    <?php }

	if((!empty($direction) && $direction == "get_kelurahan")){
	?>
        <div class="form-group col-md-3">
            <label>Kelurahan</label>
            <select name="kelurahan" id="kelurahan" class="form-control location">
                <option value=''>--PILIH KELURAHAN--</option>
                <?php
                $query_kelurahan = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$kecamatan."' ORDER BY NAME ASC");
                while($data_kelurahan = $db->fetchNextObject($query_kelurahan)){
                ?>
                    <option value='<?php echo $data_kelurahan->ID_LOCATION; ?>' <?php if(!empty($kelurahan) && $kelurahan == $data_kelurahan->ID_LOCATION){?> selected<?php } ?>>
                        <?php echo $data_kelurahan->ID_LOCATION; ?> - <?php echo $data_kelurahan->NAME; ?>
                    </option>
            <?php } ?>
            </select>
        </div>
    <?php		
	}
	
	
	if((!empty($direction) && $direction == "get_data")){
	  if(empty($id_parent)){ $id_parent = '0'; }
	  $query_loc = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$id_parent."' ORDER BY NAME ASC");
	  $num_loc 	 = $db->numRows($query_loc); 
	
	  if($num_loc > 0){
		  while($data_loc = $db->fetchNextObject($query_loc)){ ?>
			<tr id="tr_<?php echo $data_loc->ID_LOCATION; ?>">
				<td><input type="checkbox" name="row_sel" class="row_sel" /></td>
				<td><?php echo $data_loc->NAME; ?></td>
				<td style="text-align:center">
					<div class="btn-group">
						<a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $data_loc->ID_LOCATION; ?>" class="btn btn-sm btn-danger" title="Edit">
								<i class="fa fa-pencil-square-o"></i>
						</a>
						<a href='javascript:void()' onclick="removal('<?php echo $data_loc->ID_LOCATION; ?>')" class="btn btn-sm btn-warning" title="Delete">
								<i class="fa fa-trash"></i>
						</a>
					</div>
				</td>
			</tr>
<?php 		} 
	  }else{ ?>
			<tr>
				<td colspan='2'><div class="alert alert-danger">Maaf, Informasi wilayah tidak ditemukan</div></td>
			</tr>
 <?php 
	  }
	} ?>
