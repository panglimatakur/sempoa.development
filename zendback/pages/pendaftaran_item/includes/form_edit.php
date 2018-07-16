<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-8">
    <div class="form-group col-md-6">
        <label class="req">Code</label>
        <input type="text" name="code" value="<?php echo @$code; ?>" class="code validate[required] text-input form-control mousetrap">
    </div>
    <div class="form-group col-md-6">
       <label class="req">Nama</label>
      <input name="nama" type="text" id="nama" value="<?php echo @$nama; ?>" class="validate[required] text-input form-control mousetrap" />
    </div>
    <div class="form-group col-md-6">
       <label>Harga</label>
      <input name="harga" type="text" id="harga" value="<?php echo @$harga; ?>" class="form-control mousetrap" />
    </div>
    <div class="form-group col-md-6">
        <label>Satuan</label>
        <select name="satuan" id="satuan" class="form-control mousetrap">
            <option value=''>--PILIH SATUAN--</option>
            <?php
            $query_unit = $db->query("SELECT * FROM ".$tpref."products_units ORDER BY NAME");
            while($data_unit = $db->fetchNextObject($query_unit)){
            ?>
                <option value='<?php echo $data_unit->ID_PRODUCT_UNIT; ?>' <?php if(!empty($satuan) && $satuan == $data_unit->ID_PRODUCT_UNIT){?> selected<?php } ?>><?php echo $data_unit->NAME; ?></option>
        <?php } ?>
        </select>
    </div><div class="form-group col-md-12">
      <label>Deskripsi</label>
      <textarea name="deskripsi" id="deskripsi" class="form-control mousetrap"><?php echo @$deskripsi; ?></textarea>
    </div>
</div>