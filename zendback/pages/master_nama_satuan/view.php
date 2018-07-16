<?php defined('mainload') or die('Restricted Access'); ?>
<?php 
	if(!empty($msg)){
		switch ($msg){
			case "1":
				echo msg("Data Berhasil Disimpan","success");
			break;
			case "2":
				echo msg("Pengisian Form Belum Lengkap","error");
			break;
		}
	}
?>
<div class="ibox float-e-margins">
	<div class='ibox-content'>
		<form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
			<div class="form-group col-md-6">
			  <label class="req">Nama Satuan</label>
			  <input name="nama" type="text" id="nama" value="<?php echo @$nama; ?>" class="form-control validate[required] text-input" />
			</div>
			<div class="form-group col-md-6">
				<label>&nbsp;</label><br />
				<?php
				if(empty($direction) || 
				(!empty($direction) && ($direction != "edit" || $direction != "show"))){
					$prosesvalue = "insert";	
				}
				if(!empty($direction) && ($direction != "get_form" && ($direction != "insert" || $direction != "delete" || $direction != "show"))){
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
				<button name="Submit" type="submit" id="button_cmd" class="btn btn-sempoa-1">
					<i class="icsw16-white icsw16-create-write"></i> Simpan Data
				</button>
				<?php echo @$addbutton; ?>
				<input type='hidden' name='direction' id='direction' value='<?php echo $prosesvalue; ?>' />
			</div>
			<div class="clearfix"></div>
		</form>
	</div>
</div>

<div class="ibox float-e-margins">
	<div class="ibox-title">
		<h5>Daftar Satuan</h5>
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
	<?php if($num_satuan > 0){ ?>
	<table width="100%" class="table table-striped table-bordered">
		<thead>
			<tr>
				<th width="20" class="table_checkbox" style="width:13px">
					<input type="checkbox" id="select_rows" class="select_rows" data-tableid="dt_gal" />
				</th>
				<th width="852">Nama</th>
				<th width="116" style="text-align:center">Actions</th>
			</tr>
		</thead>
		<tbody>
			  <?php while($dt_satuan	= $db->fetchNextObject($q_satuan)){ 
			  ?>
			  <tr id="tr_<?php echo $dt_satuan->ID_PRODUCT_UNIT; ?>">
				<td><input type="checkbox" name="row_sel" class="row_sel" /></td>
				<td><?php echo $dt_satuan->NAME; ?></td>
				<td style="text-align:center">
					<div class="btn-group">
						<a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_satuan->ID_PRODUCT_UNIT; ?>" class="btn btn-sm btn-danger" title="Edit">
								<i class="fa fa-pencil-square-o"></i>
						</a>
						<a href='javascript:void()' onclick="removal('<?php echo $dt_satuan->ID_PRODUCT_UNIT; ?>')" class="btn btn-sm btn-warning" title="Delete">
								<i class="fa fa-trash"></i>
						</a>
					</div>
				</td>
			</tr>
			<?php } ?>    
		</tbody>
	</table>
	<?php //echo pfoot($query_str,$link_str); ?>       
	<?php }else{
		echo "<br>";
		echo msg("Tidak Ada Supplier Yang Terdaftar","error");
	} ?>
	</div>
</div>
