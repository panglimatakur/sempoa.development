<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
		$direction 	= isset($_POST['direction']) 	? $_POST['direction'] : "";
		if(!empty($_POST['form_name'])){ $form_name 		= isset($_POST['form_name']) ? $_POST['form_name'] : ""; }
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');
}

	if((!empty($direction) && $direction == "get_city") || !empty($kota)){
		if(!empty($_POST['propinsi'])){ $propinsi 		= isset($_POST['propinsi']) 		? $_POST['propinsi'] : ""; }
	?>
            <div class="form-group">
                <label class="req">Kota</label>
                <select name="kota" id="kota" class="form-control validate[required] text-input">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' <?php if(!empty($kota) && $kota == $data_kota->ID_LOCATION){?> selected<?php } ?>>
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}
	
	
	if((!empty($direction) && $direction == "list_report")){
		@$lastID 	= $_REQUEST['lastID'];
		$condition_2 = "";
		if($_SESSION['admin_only'] == "false"){
			$condition_2 =  "AND (ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($id_client_form).") ";
		}else{
			if(!empty($id_client_form)){
				$condition_2 =  "AND (ID_CLIENT='".$id_client_form."' ".parent_condition($id_client_form).") ";
			}
		}
		$query_str		= "SELECT * FROM system_users_client WHERE ID_CLIENT IS NOT NULL  ".$condition_2." AND ID_USER < ".$lastID." ORDER BY ID_USER DESC";
		$link_str		= "";
		$q_user 		= $db->query($query_str." LIMIT 0,50");
		$num_user 		= $db->recount($query_str);	
		
		 while($dt_user	= $db->fetchNextObject($q_user)){ 
				$jabatan =  $db->fob("NAME","system_master_client_users_level"," 
									  WHERE ID_CLIENT_USER_LEVEL='".$dt_user->ID_CLIENT_USER_LEVEL."'");
		  ?>
		  <tr class="wrdLatest" data-info='<?php echo $dt_user->ID_USER; ?>' id="tr_<?php echo $dt_user->ID_USER; ?>">
			<td style="text-align:center;" class="align-top">
				<a href="javascript:void()" onclick="modal_ajax(this)" modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/detail.php?id_list=<?php echo $dt_user->ID_USER; ?>","size":"modal-lg"' title="Detail">
				<div class="thumbnail">
					<div class="thumbnail-inner" style="height:60px;">
						<?php if(!empty($dt_user->USER_PHOTO) && is_file($basepath."/".$user_foto_dir."/".$dt_user->USER_PHOTO)){?>
							<img src='<?php echo $dirhost; ?>/<?php echo $user_foto_dir; ?>/<?php echo $dt_user->USER_PHOTO; ?>' style="width:100%"/>
						<?php }else{ ?>
							<img src='<?php echo $dirhost; ?>/files/images/noimage-m.jpg' style="width:100%"/>
						<?php } ?>
					</div>
				</div>
				</a>
			</td>
			<td class="align-top">
				<b style="color:#C00"><?php echo $dt_user->USER_NAME; ?></b><br>
				<?php echo $jabatan; ?>
				<?php if(!empty($dt_user->ADDITIONAL_INFO)){?>
					<br />
					<b>Keterangan :</b><br />
					<span class='code'><?php echo $dt_user->ADDITIONAL_INFO; ?></span>
				<?php } ?>
			</td>
			<td style="text-align:center" class="align-top">
				<div class="btn-group">
					<?php if($_SESSION['admin_only'] == "true"){?>
					<button onclick="send_starterpack('<?php echo $dt_user->ID_CLIENT; ?>')" type="button" class="btn btn-sm btn-sempoa-2" id="start_<?php echo $dt_user->ID_CLIENT; ?>" title="Kirim StarterPack">
						<i class="fa fa-reorder"></i> 
					</button>
					<?php } ?>
					<a href="javascript:void()" class="btn btn-sm btn-info" onclick="modal_ajax(this)"  modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/detail.php?id_list=<?php echo $dt_user->ID_USER; ?>","size":"modal-lg"' title="Detail">
						<i class="fa fa-eye"></i>
					</a>
					<?php if(allow('edit') == 1){?> 
					<a href="?page=<?php echo $page; ?>&direction=edit&no=<?php echo $dt_user->ID_USER; ?>" class="btn btn-sm btn-danger " title="Edit">
						<i class="fa fa-pencil-square-o"></i>
					</a>
					<?php } ?>
					<?php if(allow('delete') == 1){?> 
					<a href='javascript:void()' onclick="removal('<?php echo $dt_user->ID_USER; ?>','table')" class="btn btn-sm btn-warning" title="Delete">
						<i class="fa fa-trash"></i>
					</a>
					<?php } ?>
				</div>
			</td>
		</tr>
		<?php }  
		
		
	}
?>