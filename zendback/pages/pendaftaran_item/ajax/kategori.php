<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		$display 		= isset($_REQUEST['display']) 			? $_REQUEST['display'] 			: "";
		$id_type 		= isset($_REQUEST['id_type']) 			? $_REQUEST['id_type'] 			: "";
		$id_type_report = isset($_REQUEST['id_type_report']) 	? $_REQUEST['id_type_report'] 	: "";
		$form_add		= isset($_REQUEST['form_add']) 			? $_REQUEST['form_add'] 		: ""; 
	}else{
		defined('mainload') or die('Restricted Access');	
	}
}else{
	defined('mainload') or die('Restricted Access');
}

function product_category_list($id_parent){
	global $db,$tpref,$dirhost,$id_kategori,$function;
	
	$query_kategori = $db->query("SELECT * FROM ".$tpref."products_categories
								  WHERE 
									ID_PARENT = '".$id_parent."' AND 
									ID_CLIENT='".$_SESSION['cidkey']."' 
								  ORDER BY SERI"); 
	while($data_kategori = $db->fetchNextObject($query_kategori)){ ?>
		<option value="<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>" 
				data-parent="<?php echo $id_parent; ?>">
			<?php echo $data_kategori->NAME; ?>
		</option>
<?php }
}


if((!empty($display) && $display == "kategori_proses") || (!empty($direction) && $direction == "edit")){
	
    $query_kategori = $db->query("SELECT * FROM ".$tpref."products_categories 
								  WHERE 
								  	ID_PRODUCT_TYPE='".$id_type."' AND ID_PARENT = '0' AND 
									ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY SERI");
	$num_kategori 	= $db->numRows($query_kategori);
	if($num_kategori > 0){
?>
    <div class="form-group col-md-6">
    	<label class="req">Kategori Item</label>
        <select id="id_kategori" name="id_kategori" class="form-control">
		<?php while($data_kategori = $db->fetchNextObject($query_kategori)){?>
            <option value="<?php echo $data_kategori->ID_PRODUCT_CATEGORY; ?>"
            		<?php if(!empty($id_kategori) && 
							 $data_kategori->ID_PRODUCT_CATEGORY == $id_kategori){?> selected <?php } ?>>
				<?php echo $data_kategori->NAME; ?>
            </option>
            <?php echo product_category_list($data_kategori->ID_PRODUCT_CATEGORY); ?>
        <?php } ?>
        </select>
	</div>
    <script>$("#id_kategori").treeselect();</script>

<?php 
	}
} 
?>

<?php 
if((!empty($display) && $display == "kategori_report") || !empty($id_type_report)){
	
    $query_kategori_report 	= $db->query("SELECT * FROM ".$tpref."products_categories 
										  WHERE 
										  	ID_PRODUCT_TYPE='".$id_type_report."' AND ID_PARENT = '0' AND 
											ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY SERI");
	$num_kategori_report 	= $db->numRows($query_kategori_report);
	if($num_kategori_report > 0){
?>
    <div class="form-group col-md-6">
        <label>Kategori</label>
        <select id="id_kategori_report" name="id_kategori_report" class="form-control">
		<?php while($data_kategori_report = $db->fetchNextObject($query_kategori_report)){?>
            <option value="<?php echo $data_kategori_report->ID_PRODUCT_CATEGORY; ?>"
					<?php if(!empty($data_kategori_report) && 
							 $data_kategori_report->ID_PRODUCT_CATEGORY == $data_kategori_report){?> selected <?php } ?>>
				<?php echo $data_kategori_report->NAME; ?>
            </option>
            <?php echo product_category_list($data_kategori_report->ID_PRODUCT_CATEGORY); ?>
        <?php } ?>
        </select>
    </div>
    <script>$("#id_kategori_report").treeselect();</script>
<?php 
	}
} 
if((!empty($display) && $display == "input_kategori")){ ?>
    <input type="hidden" id="new_id_type" value="<?php echo @$id_type; ?>" />
    <div class="form-group">
        <label class='req'>Nama Kategori</label>
        <input id="nama_kat" type="text" value="" class="form-control validate[required] text-input" style="text-transform:capitalize"/>
    </div>
    <div id="div_form_link" style="display:none">
        <div class="alert alert-info" style="margin:0">Apakah nama kategori diatas,  adalah jenis kategori anak (subkategori) atau jenis kategori induk (kategori) ?</div>
        <div class="form-group">
            <label class='req'>Jenis Kategori</label>
            <select id="kategori_type" class="form-control" >
                <option value="induk">Kategori Induk</option>
                <option value="anak">Kategori Anak</option>
            </select>
        </div>
        <span id="div_kategori_induk"></span>
    </div>
    <span id="div_new_kat"></span>
    <div class="form-group">
        <button id="new_category" type="submit"  class="btn btn-sempoa-1" value="new_category">
        	<i calss="fa fa-check-square-o"></i> Simpan Kategori
        </button>
    </div>
<?php } ?>