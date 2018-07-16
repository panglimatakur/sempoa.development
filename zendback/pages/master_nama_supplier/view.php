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

<div class="col-md-12" >
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="tabbable tabbable-bordered" style="margin:0 0 9px 0">
                <ul class="nav nav-tabs">
                    <li class="<?php echo @$class_proses; ?>"><a data-toggle="tab" href="#tb1_a">Input Supplier</a></li>
                    <li class="<?php echo @$class_report; ?>"><a data-toggle="tab" href="#tb1_b">Filter Pencarian</a></li>
                </ul>
                <div class="tab-content" style="background:#FFF">
                    <div id="tb1_a" class="tab-pane <?php echo @$class_proses; ?>">
                        <p><?php include $call->inc($page_dir."/includes","form_proses.php"); ?></p>
                    </div>
                    <div id="tb1_b" class="tab-pane <?php echo @$class_report; ?>">
                        <p><?php include $call->inc($page_dir."/includes","form_report.php"); ?></p>
                    </div>
                </div>
            </div>
            <input id="proses_page" type="hidden"  value="<?php echo $ajax_dir; ?>/proses.php" />
            <input id="data_page" type="hidden"  value="<?php echo $ajax_dir; ?>/data.php" />
        </div>
    </div>
    
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Supplier</h5>
            <div class="ibox-tools">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-cogs"></i> Action
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <!--<li>
                        <a href="modules/supplier/includes/print.php?prompt=true" class="fancybox fancybox.ajax">
                        <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                        </a>
                    </li>-->
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
        <a name="report"></a>
        <div class="ibox-content">
		<?php if($num_partner > 0){ ?>
        <table width="100%" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="20" class="table_checkbox" style="width:13px">
                    	<input type="checkbox" id="select_rows" class="select_rows"/>
                    </th>
                    <th width="852">Nama</th>
                    <th width="852">Alamat</th>
                    <th width="852">Phone</th>
                    <th width="852">Email/Website</th>
                    <th width="116">Actions</th>
                </tr>
            </thead>
            <tbody>
                  <?php while($dt_partner	= $db->fetchNextObject($q_partner)){ 
                  ?>
                  <tr id="tr_<?php echo $dt_partner->ID_PARTNER; ?>">
                    <td class="align-top"><input type="checkbox" name="row_sel" class="row_sel" value='<?php echo $dt_partner->ID_PARTNER; ?>'/></td>
                    <td class="align-top"><?php echo @$dt_partner->PARTNER_NAME; ?></td>
                    <td class="align-top">
						<?php echo @$dt_partner->PARTNER_ADDRESS; ?><br />
                        <?php echo @$db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_partner->PARTNER_PROVINCE."'"); ?><br />
                        <?php echo @$db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_partner->PARTNER_CITY."'"); ?>
                    </td>
                    <td class="align-top">
						<?php echo @$dt_partner->PARTNER_PHONE; ?><br />
						<?php echo @$dt_partner->PARTNER_PERSON_CONTACT; ?>
                    </td>
                    <td class="align-top">
						<?php echo @$dt_partner->PARTNER_EMAIL; ?><br />
                    	<?php echo @$dt_partner->PARTNER_URL; ?>
                    </td>
                    <td class="align-top">
                        <div class="btn-group">
                        	<?php if(allow('edit') == 1){?> 
                                <a href="<?php echo $lparam; ?>&direction=edit&no=<?php echo $dt_partner->ID_PARTNER; ?>" class="btn btn-mini" title="Edit">
                                    <i class="icon-pencil"></i>
                                </a>
                            <?php } ?>
                            <?php if(allow('delete') == 1){?> 
                                <a href='javascript:void()' onclick="removal('<?php echo $dt_partner->ID_PARTNER; ?>')" class="btn btn-mini" title="Delete">
                                    <i class="icon-trash"></i>
                                </a>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
                <?php } ?>    
            </tbody>
        </table>
        <?php }else{
			echo "<br>";
            echo msg("Tidak Ada Supplier Yang Terdaftar","error");
        } ?>
        </div>
        <div class="ibox-title">
            <form id="form_paging" class="formular" action="#report" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nama_report" value="<?php echo @$nama_report; ?>" />
            <input type="hidden" name="propinsi_report" value="<?php echo @$propinsi_report; ?>" />
            <input type="hidden" name="kota_report" value="<?php echo @$kota_report; ?>" />
            <input type="hidden" name="alamat_report" value="<?php echo @$alamat_report; ?>" />
            <input type="hidden" name="tlp_report" value="<?php echo @$tlp_report; ?>" />
            <input type="hidden" name="kontak_report" value="<?php echo @$kontak_report; ?>" />
            <input type="hidden" name="email_report" value="<?php echo @$email_report; ?>" />
            <input type="hidden" name="website_report" value="<?php echo @$website_report; ?>" />
            <input type="hidden" name="direction" value="show" />
 			<?php echo pfoot($str_query,$link_str); ?>       
        	</form>
        </div>
    </div>
</div>