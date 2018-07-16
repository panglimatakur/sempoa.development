<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['uidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		$id_client_level		= isset($_REQUEST['id_client_level']) 		? $sanitize->number($_REQUEST['id_client_level']) 		: "";
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}
$condition	= "";
if(!empty($single)){ $condition = " AND ID_CLIENT = '".$client_id."'";	}

$q_client_user_level 	= $db->query("SELECT ID_CLIENT_USER_LEVEL,NAME FROM system_master_client_users_level ORDER BY ID_CLIENT_USER_LEVEL ASC");
?>


<div class="ibox float-e-margins">
    <div class="ibox-content">
        <div class="col-md-6">
            <?php
            $str_client			= "SELECT * FROM ".$tpref."clients WHERE ID_CLIENT_LEVEL = '".$id_client_level."' ".$condition." ORDER BY CLIENT_NAME ASC";
            $q_clients			= $db->query($str_client);
            while($dt_clients	= $db->fetchNextObject($q_clients)){
            ?>
                <div class='ibox-title'>
                    <h5>
                        <input type="checkbox" name='c_id[]' class='id_client' value='<?php echo $dt_clients->ID_CLIENT; ?>' onclick="write_client(this)" checked >                    
						<?php echo $dt_clients->ID_CLIENT; ?> - <?php echo $dt_clients->CLIENT_NAME; ?>
                    </h5>
                    <div class="ibox-tools">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cogs"></i> Action
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <?php if(allow('delete') == 1){?> 
                            <li>
                                <a href="<?php echo $dirhost; ?>/?page=hak_akses&direction=edit&client_id=<?php echo $dt_clients->ID_CLIENT; ?>&single=true" class="fancybox fancybox.ajax">
                                    <i class="fa fa-pencil-square-o" ></i>
                                    Edit Status
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>                
                    

                <div class='ibox-content' style="padding:5px">
                    <?php echo $dt_clients->CLIENT_ADDRESS; ?>
                </div>
            <?php }?>
        </div>
        <div class="col-md-6">
            <div class='ibox-title'><h5>Tingkat Jabatan</h5></div>
            <div class='ibox-content'>
                <div class="form-group" id="input_user_level">
                    <select name="id_client_user_level" id="id_client_user_level" class="form-control validate[required] text-input" onchange="get_module();" >
                        <option value="">--LEVEL USER CLIENT--</option>
                        <?php while($dt_client_user_level = $db->fetchNextObject($q_client_user_level)){?>
                            <option value='<?php echo $dt_client_user_level->ID_CLIENT_USER_LEVEL; ?>' <?php if(!empty($id_client_user_level) && $id_client_user_level == $dt_client_user_level->ID_CLIENT_USER_LEVEL){?> selected<?php } ?>>
                                <?php echo $dt_client_user_level->NAME; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <span id="div_modules">
                <?php if(!empty($direction) && $direction == "insert"){include $call->inc($ajax_dir,"modules.php");} 
                ?>
            </span>
            
        </div>
        
        <div class="clearfix"></div>
    </div>
</div>
        
