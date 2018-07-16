<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-6">
        <label>Tipe Produk</label>
        <select name="id_type_report" id="id_type_report" class="form-control mousetrap"/>
            <option value=''>--PILIH TIPE PRODUK--</option>
            <?php
            $query_type_report = $db->query("SELECT * FROM ".$tpref."products_types ORDER BY ID_PRODUCT_TYPE ASC");
            while($data_type_report = $db->fetchNextObject($query_type_report)){
            ?>
                <option value='<?php echo $data_type_report->ID_PRODUCT_TYPE ?>' <?php if(!empty($id_type_report) && $id_type_report == $data_type_report->ID_PRODUCT_TYPE){?> selected<?php } ?>><?php echo $data_type_report->NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
	<span id="div_kategori_report"><?php if(!empty($id_type_report)){ include $call->inc($ajax_dir,"data.php"); } ?></span>
    <div class="form-group col-md-6">
      <label>Code</label>
      <input type="text" name="code_report" id="code_report" value="<?php echo @$code_report; ?>"  class="code form-control"/>
    </div>
    <div class="form-group col-md-6">
       <label>Nama</label>
      <input name="nama_report" type="text" id="nama_report" value="<?php echo @$nama_report; ?>" class="form-control" />
    </div>
    <div class="form-group col-md-6">
      <label>Deskripsi</label>
      <textarea name="deskripsi_report" id="deskripsi_report" class="form-control"><?php echo @$deskripsi_report; ?></textarea>
    </div>
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-sempoa-1" name='direction' id='direction' value='show'>
        	<i class="fa fa-eye"></i> Lihat Data
        </button>
    </div>
    <div class="clearfix"></div>
</form>