<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	$show			=	isset($_REQUEST['show']) 		? $_REQUEST['show']		:"";
	$parent_id		=	isset($_REQUEST['parent_id']) 	? $_REQUEST['parent_id']:"";
	$id_root		=	isset($_REQUEST['id_root']) 	? $_REQUEST['id_root']	:"";

	$qchild			=	$db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE='".$parent_id."'");
	$dtchild		=	$db->fetchNextObject($qchild);
	$no 			= 	$dtchild->ID_CASH_TYPE;
	$is_folder		=  	$dtchild->IS_FOLDER;
	$nama_parent	= 	$dtchild->NAME;
	$contenttype	= 	$id_root;
	$inout			= 	$db->fob("NAME",$tpref."cash_type"," WHERE ID_CASH_TYPE = '".$dtchild->IN_OUT."'");
}
else{  defined('mainload') or die('Restricted Access'); }
?>
<?php
if((!empty($show) && $show == "form_master") || (!empty($direction) && $direction == "edit")){?>
    <div class="w-box w-box-blue" id="divparent_id">
    <span id="loader"></span>
    <?php if(!empty($parent_id)){?>
        <input type="hidden" name="parent_id" value='<?php echo @$parent_id; ?>' id='parent_id'/>
        <div class="ibox-title">
            <h5>
            Di bawah adalah Sub Rincian dari <?php echo "<b><u>".$nama_parent."</u></b>"; ?>
            <!--<a href='javascript:void();' onclick='resetchild();' style="float:right; margin-top:-8px">
                <i class="icsw16-trashcan icsw16-white"></i>
            </a>-->
            </h4>
        </div>
	<?php } ?> 
    </div>
    <div class="form-group">
        <input type="hidden" name="id_root" id="id_root" value="<?php echo @$id_root; ?>" />
        <label class='req'>Nama Transaksi</label>
        <input name="nama" id="nama" type="text" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
    </div>
    <div class="form-group">
        <label class='req'>Bentuk Rincian Transasksi</label>
        <select name="is_folder" id="is_folder" class="form-control validate[required] text-input">
        <option value='2' <?php if(!empty($is_folder) && $is_folder == 2){ echo "selected"; } ?>>Folder</option>
        <option value='1' <?php if(!empty($is_folder) && $is_folder == 1){ echo "selected"; } ?>>File</option>
        </select>
    </div>
    
    <div class="form-group">
		<?php
        if(empty($direction) || (!empty($direction) && ($direction == "insert" || $direction == 'delete'))){
			$directionvalue	= "insert";	
        }
        if(!empty($direction) && ($direction == "edit" || $direction == "save")){
			$directionvalue = "save";
			$addbutton = "
				<a href='".$lparam."'>
					<input name='button' type='button' class='btn btn-beoro-3' value='Tambah Data'>
				</a>";
        ?>
        <?php } ?>
        <input type='hidden' id='no' value='<?php echo $no; ?>'>
        <button name="direction" id="direction" type="button"  class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>">Simpan Data</button>
        <?php echo @$addbutton; ?>
    </div>
<?php } ?>