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
<div class="col-md-12">
    <div class="ibox float-e-margins" style="background:#FFF">
        <div class="ibox-title">
            <h5>Filter Pencarian</h5>
        </div>
         <div class="ibox-content">
            <form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                <label>Daftar Merchant</label>
                <select name="id_client_form" id="id_client_form" class="form-control mousetrap" />
                    <option value=''>--PILIH MERCHANT--</option>
                    <?php
                    while($data_branch = $db->fetchNextObject($query_branch)){
                    ?>
                        <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_client_form) && $id_client_form == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?>
                        </option>
                <?php } ?>
                </select>
                </div>
                <div class="form-group col-md-6">
                	<label>&nbsp;</label><br />
                    <button name="direction" type="submit" value='show' id="button_cmd" class="btn btn-sempoa-1">
                    	<i class="fa fa-eye"></i> Lihat Data</button>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
    
    
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="padding:10px 0 0 0">
    
            <div class="col-md-6">
              <div class="ibox-title">
                    <h4>Daftar Logo Merchant</h4>
                </div>
                <div class="ibox-content">
                    <table width="100%" class="table table-striped table-bordered" id="client_list">
                        <thead>
                            <tr>
                              <th width="53" style="text-align:center">Photo</th>
                                <th width="884">Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                              <?php while($dt_user	= $db->fetchNextObject($q_user)){ 
                                    $lastID = $dt_user->ID_CLIENT;
                              ?>
                              <tr id="tr_<?php echo $dt_user->ID_CLIENT; ?>" style="cursor:pointer" onclick="pick('<?php echo $dt_user->ID_CLIENT; ?>')">
                                <td style="text-align:center">
                                    <?php echo getclientlogo($dt_user->ID_CLIENT," class='thumbnail' style='width:50px'"); ?>
                                    <input type="hidden" name="client[]" value="<?php echo $dt_user->ID_CLIENT; ?>" />
                                </td>
                                <td>
                                    <b style="color:#C00"><?php echo $dt_user->CLIENT_NAME; ?></b><br>
                                    <?php
                                        $statement 		= "";
                                        $q_discount_2 	= $db->query("SELECT VALUE,PIECE,STATEMENT,ID_PRODUCTS FROM ".$tpref."client_discounts WHERE ID_CLIENT = '".$dt_user->ID_CLIENT."'");
                                        $num_discount	= $db->numRows($q_discount_2);
                                        if($num_discount > 0 || !empty($dt_user->CLIENT_STATEMENT)){
                                    ?>
                                    <div style="font-size:11px; max-height:200px;" class='code'>
                                        <?php 
                                        if($num_discount > 0){
                                            $persen	= "";
                                            $rupiah	= "";
                                            while($dt_discount_2 = $db->fetchNextObject($q_discount_2)){
                                                if($dt_discount_2->PIECE == "persen"){ $persen = "%"; 	}
                                                $id_product_discs 	= $dt_discount_2->ID_PRODUCTS;
                                                echo  "<div style='border-bottom:1px dashed #666666'>
                                                            Diskon ".$rupiah."".$dt_discount_2->VALUE."".$persen." 
                                                            ".$dt_discount_2->STATEMENT."
                                                            <div style='clear:both; heigth:4px'></div>
                                                       </div>";
                                            }
                                        }else{
                                            echo $dt_user->CLIENT_STATEMENT;
                                        }
                                        ?>
                                    </div>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>  
                            <div class='wrdLatest' data-info='<?php echo $lastID; ?>'></div>
                        </tbody>
                    </table>
                </div>
                <div class="ibox-title" style="text-align:center">
                    <div id="lastPostsLoader"></div>
                    <?php if($num_user > 3){?>
                        <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
                    <?php } ?>
                    <br clear="all" />
                </div>
            </div>
            <input type="hidden" id="data_page" value="<?php echo @$dirhost; ?>/<?php echo $ajax_dir; ?>/data.php" />
            <div class="col-md-6">
                <form id="form" action="<?php echo $inc_dir; ?>/print.php" method="POST" target="_blank">
                <div class="ibox-title">
                    <h4>Daftar Pilihan Logo Merchant</h4>
                </div>
                <div class="ibox-content">
                    <table width="100%" class="table table-striped table-bordered" id="new_pick">
                        <thead>
                            <tr>
                              <th width="53" style="text-align:center">Photo</th>
                                <th width="884">Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                              <tr style="display:none">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="sub_butt">
                        <button type="submit" class="btn btn-sempoa-1" value="print">
                            <i class="icsw16-fax icsw16-white"></i> Print Logo
                        </button>
                        <input type="checkbox" name="size" value="1" /> Home Logo
                    </div>
                </div>
                </form>
            </div>
        <div class="clearfix"></div>
        </div>
    </div>
</div>
