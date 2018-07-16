<?php defined('mainload') or die('Restricted Access'); ?>
<?php 
	if(!empty($msg)){
		switch ($msg){
			case "1":
				echo msg("Data Link Berhasil Disimpan","success");
			break;
			case "2":
				echo msg("Data Link Berhasil Disimpan dan Di Perbaiki","success");
			break;
			case "3":
				echo msg("Pengisian Form Belum Lengkap","error");
			break;
		}
	}
?>
<div class="col-md-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Filter Pencarian Wilayah</h5>
        </div>
        <div class="ibox-content">
            <div class="form-group col-md-3">
                <label class="req">Propinsi</label>
                <select name="propinsi" id="propinsi" class="form-control" onclick="get_kota(this)">
                    <option value=''>--PILIH PROPINSI--</option>
                    <?php
                    $query_propinsi = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '0' ORDER BY NAME ASC");
                    while($data_propinsi = $db->fetchNextObject($query_propinsi)){
                    ?>
                    <option value='<?php echo $data_propinsi->ID_LOCATION; ?>' <?php if(!empty($propinsi) && $propinsi == $data_propinsi->ID_LOCATION){?> selected<?php } ?>><?php echo $data_propinsi->NAME; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <span id="div_kota"><?php if(!empty($kota)){ include $call->inc($ajax_dir,"data.php"); }?></span>
            <span id="div_kecamatan"><?php if(!empty($kecamatan)){ include $call->inc($ajax_dir,"data.php"); }?></span>
            <span id="div_kelurahan"></span>
            <div class="clearfix"></div>
            
            
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<form name="form1" method="post" action="" enctype="multipart/form-data">
<div class="col-md-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
        
            <div class="form-group col-md-6" id='div_label'>
                <label class="req" id="div_name">Tuliskan Nama Propinsi</label>
                <input name="nama" id="nama" type="text" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input"/>
            </div>
            <div class="form-group col-md-6">
            	<label>&nbsp;</label><br />
                <?php
                if(empty($direction) || (!empty($direction) && ($direction == "insert" || $direction == 'delete'))){
                    $directionvalue	= "insert";	
                }
                if(!empty($direction) && ($direction == "edit" || $direction == "save")){
                    $directionvalue = "save";
                    $addbutton = "
                        <a href='".$lparam."'>
                            <button name='button' type='button' class='btn btn-danger'>Tambah Data</button>
                        </a>";
                ?>
                <input type='hidden' name='no' value='<?php echo $no; ?>'>
                <?php } ?>
                <button name="direction" type="submit"  class="btn btn-sempoa-1" value="<?php echo $directionvalue; ?>"><i class='fa fa-pencil-square-o'></i> Simpan Data</button>
                <?php echo @$addbutton; ?>
                
                <input type='hidden' id='proses_page' value='<?php echo $ajax_dir; ?>/proses.php'>
                <input type='hidden' id='data_page' value='<?php echo $ajax_dir; ?>/data.php'>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
</form>

<div class="col-md-12">
    <div class="ibox float-e-margins">
    	<span id="div_result"></span>
        <div class="ibox-title">
            <h5>Daftar Wilayah </h5>
            <div class="ibox-tools">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-cogs"></i> Action
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <?php if(allow('delete') == 1){?> 
                    <li>
                        <a href="javascript:void()" id="select_rows_2">
                            <i class="fa fa-check-square-o" ></i>
                            Pilih Semua
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void()" id="delete_picked">
                            <i class="fa fa-trash" ></i>
                            Hapus Yang Di Pilih
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>                
        </div>
        <div class="ibox-content">
        
            <table width="100%" class="table table-striped table-bordered table-location">
                <thead>
                    <tr>
                        <th width="20" class="table_checkbox" style="width:13px">
                            <input type="checkbox" name="select_rows" class="select_rows" data-tableid="dt_gal" />
                        </th>
                        <th width="852">Nama</th>
                        <th width="116" style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            
        </div>
    </div>
</div>