<?php defined('mainload') or die('Restricted Access'); ?>
<?php //if((empty($direction) && allow('insert') == 1) || (!empty($direction) && $direction == "edit" && allow('edit') == 1)){?> 
<form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" id="code_random" value="<?php echo $code_random; ?>"/>
    <div class="form-group col-md-6">
        <label class="req">Tipe Produk</label>
        <select name="id_type" id="id_type" class="form-control validate[required] text-input mousetrap" onchange="show_catlist('')"/>
            <option value=''>--PILIH TIPE PRODUK--</option>
            <?php
            $query_type = $db->query("SELECT * FROM ".$tpref."products_types ORDER BY ID_PRODUCT_TYPE ASC");
            while($data_type = $db->fetchNextObject($query_type)){
            ?>
                <option value='<?php echo $data_type->ID_PRODUCT_TYPE ?>' <?php if(!empty($id_type) && $id_type == $data_type->ID_PRODUCT_TYPE){?> selected<?php } ?>><?php echo $data_type->NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
	<span id="div_kategori">
		<?php if(!empty($id_type)){ include $call->inc($ajax_dir,"kategori.php"); } ?>
    </span>
    <div class="form-group col-md-12">
		<?php if(empty($direction) || (!empty($direction) && $direction != "edit")){?>
            <label class="req">Gambar Item</label>
            <span id='elements'></span>
            <input type="file" name="image[]" id="image_1" onchange="preview(this,'<?php echo @$direction; ?>')" style="height:50px" multiple class='mousetrap'>
            <div style='height:30px;'></div>
            <div id="preview_zone"></div>
        <?php } ?>
    </div>

	<?php
    if(!empty($direction) && ($direction == "insert" || $direction == "edit" || $direction == "save")){
        if(!empty($no)){?>
            <div class=" col-md-3">
                <div id="preview_zone">
    <?php
				$q_photos 	= $db->query("SELECT * FROM ".$tpref."products_photos WHERE ID_PRODUCT='".$no."'");
				while($dt_photos = $db->fetchNextObject($q_photos)){
					if(is_file($basepath."/files/images/products/".$id_client."/".$dt_photos->PHOTOS)){
		?>
					  <div class="product_content" id="product_<?php echo $no; ?>">
						<img src="<?php echo $dirhost; ?>/files/images/icons/close.png" 
							 class="close_button" 
							 onClick="cancel_content('<?php echo $no; ?>','<?php echo $dt_photos->ID_PRODUCT_PHOTO; ?>','<?php echo @$direction; ?>')">
						<div class="frame_photo">
							<img src="<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/<?php echo $dt_photos->PHOTOS; ?>" width="100%"/>
						</div>
					  </div>
			
		<?php 
					}
				}
    ?>
                </div>
                <div style="clear:both"></div>
                <label class="req">Gambar Item</label>
                <span id='elements'></span>
                <input type="file" name="image[]" 
                       id="image_1" onchange="preview(this,'<?php echo @$direction; ?>')" 
                       style="height:50px" class='mousetrap'>
             </div>
    <?php
        }
    }
    ?>
    
	<?php
		if(!empty($direction) && $direction == "edit"){
			$display 					= "form_edit";
			$button_container_style 	= '';
			$multi_button_style 		= 'style="display:none"';
			$button_multiple_form		= 'style="display:none"';
			
			include $call->inc($inc_dir,"form_edit.php");
		}else{
			$button_container_style 	= 'style="display:none"';
		}
	?>
    
    
    <div id="satuan_tag" style='display:none'>
            <option value=''>--PILIH SATUAN--</option>
            <?php
            $query_unit = $db->query("SELECT * FROM ".$tpref."products_units ORDER BY NAME");
            while($data_unit = $db->fetchNextObject($query_unit)){
            ?>
                <option value='<?php echo $data_unit->ID_PRODUCT_UNIT; ?>'><?php echo $data_unit->NAME; ?>
                </option>
        <?php } ?>
    </div>
    
    <div class="clearfix"></div>
    <div class="form-group col-md-12" id="button_container" <?php echo $button_container_style; ?>>
      	<label >&nbsp;</label><br />
        <?php
        if(empty($direction) || 
		(!empty($direction) && ($direction != "edit" || $direction != "show" || $direction != "export"))){
            $prosesvalue = "insert";	
        }
        if(!empty($direction) && ($direction != "insert" || $direction != "delete" || $direction != "show" || $direction != "export")){
            $prosesvalue = "save";
            $addbutton = "
                <a href='".$lparam."'>
					<button name='button' 
							type='button' 
							class='btn btn-danger' 
							value='Tambah Data'>
							<i class='fa fa-plus'></i>  Tambah Data
					</button>
                </a>";
    ?>
        <input type='hidden' name='no' id='no' value='<?php echo $no; ?>' />
        <?php
        }
    	?>
        <button name="direction" id="direction" type="submit" class="btn btn-sempoa-1" value="<?php echo $prosesvalue; ?>"><i class="fa fa-check-square-o"></i> Simpan Data</button>
    	<a href="<?php echo $ajax_dir; ?>/data.php?display=multiple_form" class="fancybox fancybox.ajax" style="margin:0 5px 0 0"  >
        </a>
        <?php echo @$addbutton; ?>
        <input type="hidden" name="counter" id="counter" value="<?php @$counter; ?>"/>
    </div>
</form>
<?php //}else{
	//echo msg("Maaf, Anda Tidak Di Izinkan melakukan Pengisian Data Baru Produk, karena hak proses anda di batasi","error");	
//}?>
 <div class="clearfix"></div>