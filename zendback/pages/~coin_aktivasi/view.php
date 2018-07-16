<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
	.state .btn-group{
		margin-top:4px;
	}
</style>
<form id="formID" class="formular" method="post" action="" enctype="multipart/form-data">
<?php 
	if(!empty($msg)){
		echo "<div class='col-md-12'>";
		switch ($msg){
			case "1":
				echo msg("Data Link Berhasil Disimpan","success");
			break;
			case "2":
				echo msg("Pengisian Form Belum Lengkap","error");
			break;
		}
		echo "</div>";
	}
?>
<div class="ibox float-e-margins">
    <div class="ibox-content no-padding-lr">
    
        <div class="col-md-6">
                <div class="ibox-title no-padding-l">
                    <h5>Daftar Merchant</h5>
                </div>
                <div class='alert alert-warning' style="margin:0">Klik salah satu daftar Klien dibawah ini untuk menampilkan daftar konsumennya pengguna aplikasi Discoinnya</div>
                <div class="ibox-content merchant-list">
                    <ul id="browser" class="filetree">
                        <?php
                        while($dt = $db->fetchNextObject($qlink)){
                        ?>
                            <li id="li_<?php echo $dt->ID_CLIENT; ?>">
                                <div class='link-name pull-left'>
                                    <p class="folder">
                                        <a href="javascript:void(0);" onclick="getparent('<?php echo $dt->ID_CLIENT; ?>');">
                                            &nbsp; <?php echo $dt->CLIENT_NAME; ?>
                                        </a>
                                    </p>
                                 </div>
                                 <div class="clearfix"></div>
                                <?php echo lchild($dt->ID_CLIENT); ?>
                            </li>
                        <?php } ?>	
                    </ul>
                </div>
        </div>
        <div class="col-md-6">
            <div class="ibox-title no-padding-l">
                <h5>Form Aktivasi COIN</h5>
            </div>
            <div class='form-group'>
                <label>Nomor COIN</label>
                <textarea id="list_coin" class='form-control' style="text-transform:uppercase"></textarea>
                <br />
                ex : 89TY6293;782TY43628;
            </div>
            <div class='form-group'>
                <button type="button" class='btn btn-sempoa-1' id="show_coin">
                    <i class="fa fa-eye"></i> Tampilkan
                </button>
            </div>
            <br clear="all" />
            <div  id="divparent_id">
            <?php if($num_coin > 0){?>
            
                <div class='ibox-title no-padding-l'>
                <label>SET SEMUA TANGGAL :</label><br />
                <select id='tgl_all' style='width:70px' class="form-control col-md-4">
                    <option value="">TGL</option>
                    <?php 
                    for($w = 1;$w<31;$w++)	{ if(strlen($w) == 1){ $g = "0".$w; }
                    else					{ $g = $w; 							}
                    ?>
                    <option value='<?php echo $g; ?>' <?php if(!empty($tgl) && $tgl == $g){?>selected<?php } ?>>
                        <?php echo $g; ?>
                    </option>
                    <?php } ?>
                </select>
                <select id='bln_all' style='width:70px' class="form-control col-md-4">
                    <option value="">BLN</option>
                    <?php 
                    for($w2 = 1;$w2<12;$w2++)	{ if(strlen($w2) == 1){ $g2 = "0".$w2; 	}
                    else						{ $g2 = $w2; 							}
                    ?>
                    <option value='<?php echo $g2; ?>' <?php if(!empty($bln) && $bln == $g2){?>selected<?php } ?>>
                        <?php echo $g2; ?>
                    </option>
                    <?php } ?>
                </select>
                <select id='thn_all' style='width:70px' class="form-control col-md-4">
                    <option value="">THN</option>
                    <?php 
                    for($w3 = date('Y');$w3<(date('Y') + 10);$w3++)	{ if(strlen($w3) == 1){ $g3 = "0".$w3; 	}
                    else											{ $g3 = $w3; 							}
                    ?>
                    <option value='<?php echo $g3; ?>' <?php if(!empty($thn) && $thn == $g3){?>selected<?php } ?>>
                        <?php echo $g3; ?>
                    </option>
                    <?php } ?>
                </select>
                </div>
                <br /><br />
                <div class="ibox-content no-padding-lr">
                    <a name="report"></a>
                    <table width="100%" class="table table-bordered table-striped tbl_cust">
                        <thead>
                            <tr>
                                <th colspan="2">INFORMASI COIN 
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                              <?php while($dt_customer	= $db->fetchNextObject($query_coin)){ 
                                      $tgl				= "";
                                      $bln				= "";
                                      $thn				= "";
                                      if(!empty($dt_customer->EXPIRATION_DATE)){
                                        @$tgl_expired	= explode("-",$dt_customer->EXPIRATION_DATE);
                                        $tgl			= $tgl_expired[2];
                                        $bln			= $tgl_expired[1];
                                        $thn			= $tgl_expired[0];
                                      }
                            @$propinsi 				= $db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_customer->CUSTOMER_PROVINCE."'");
                            @$kota	   				= $db->fob("NAME","system_master_location"," WHERE ID_LOCATION='".$dt_customer->CUSTOMER_CITY."'");           
                            $id_cust				= $dt_customer->ID_CUSTOMER;   
                            @$id_status[$id_cust] 	= $dt_customer->CUSTOMER_STATUS; 
                            ?>
                              <tr id="tr_<?php echo $dt_customer->ID_CUSTOMER; ?>">
                                <td width="66"><?php echo getmemberfoto($dt_customer->ID_CUSTOMER,"class='thumbnail' style='width:50px'"); ?></td>
                                <td width="545" >
                                    <div class='form-group col-md-6'>
                                        <label class='code'>COIN</label><br />
                                        <?php echo strtoupper($dt_customer->COIN_NUMBER); ?>
                                    </div>
                                    <div class='form-group col-md-6'>
                                      <label class='code'>Nama</label> 
                                      <input type='text' id="cust_name_<?php echo $id_cust; ?>" value='<?php echo @$dt_customer->CUSTOMER_NAME; ?>' class="form-control"/>
                                   </div>
                                   <div class='form-group col-md-6'>
                                      <label class='code'>Telephone</label>
                                      <input type='text' id="cust_phone_<?php echo $id_cust; ?>" value='<?php echo @$dt_customer->CUSTOMER_PHONE; ?>' class="form-control"/>
                                  </div>
                                  <div class='form-group col-md-6'>
                                      <label class='code'>Email</label>
                                      <input type='text' id="cust_email_<?php echo $id_cust; ?>" value='<?php echo @$dt_customer->CUSTOMER_EMAIL; ?>' class="form-control"/>
                                  </div>
                                  <?php if(!empty($dt_customer->CUSTOMER_SEX)){?>
                                    <div class='form-group col-md-6'>
                                        <label class='code'>Jenis Kelamin</label><br />
                                        <?php if($dt_customer->CUSTOMER_SEX == "L"){ echo "Laki-laki"; }?>
                                        <?php if($dt_customer->CUSTOMER_SEX == "P"){ echo "Perempuan"; }?> 
                                    </div>
                                  <?php } ?>
                                   <?php if($_SESSION['uclevelkey'] == 1){?>
                                       <table width="100%" class="table table-striped">
                                            <tr>
                                                <td width="5%" class="text-center">Status</td>
                                                <td width="87%" class="text-center">Expired</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center state">
                                                       <input type='checkbox' 
                                                              id="id_status_<?php echo $id_cust; ?>" 
                                                              <?php if(!empty($id_status[$id_cust]) && $id_status[$id_cust] == "3"){?> checked <?php } ?> 
                                                              class="iCheck"/> 
                                                </td>
                                                <td style='text-align:center'>
                                                  <select id='tgl_<?php echo $id_cust; ?>' class="tgl_aktif form-control pull-left" style="width:30%;">
                                                    <option value="">TGL</option>
                                                    <?php 
                                                    for($w = 1;$w<31;$w++){ 
                                                        if(strlen($w) == 1){ $g = "0".$w; }
                                                        else			   { $g = $w; }
                                                    ?>
                                                    <option value='<?php echo $g; ?>' 
                                                        <?php if(!empty($tgl) && $tgl == $g){?>selected<?php } ?>>
                                                        <?php echo $g; ?>
                                                    </option><?php } ?>
                                                  </select>
                                                  
                                                  <select id='bln_<?php echo $id_cust; ?>' class="bln_aktif form-control col-md-4" style="width:30%;">
                                                    <option value="">BLN</option>
                                                    <?php 
                                                    for($w2 = 1;$w2<12;$w2++){ if(strlen($w2) == 1){ $g2 = "0".$w2; }else{ $g2 = $w2; }
                                                    ?><option value='<?php echo $g2; ?>' <?php if(!empty($bln) && $bln == $g2){?>selected<?php } ?>><?php echo $g2; ?></option><?php } ?>
                                                  </select>
                                                  <select id='thn_<?php echo $id_cust; ?>' class="thn_aktif form-control col-md-4" style="width:40%;">
                                                    <option value="">THN</option>
                                                    <?php 
                                                    for($w3 = date('Y');$w3<(date('Y') + 10);$w3++){ if(strlen($w3) == 1){ $g3 = "0".$w3; }else{ $g3 = $w3; }
                                                    ?><option value='<?php echo $g3; ?>' <?php if(!empty($thn) && $thn == $g3){?>selected<?php } ?>><?php echo $g3; ?></option><?php } ?>
                                                  </select>
                                                </td>
                                           </tr>
                                           <tr>     
                                                <td colspan="2">
                                                    <button type="button" class="btn btn-sempoa-1 btn-block"  
                                                            onclick="set_status('<?php echo $dt_customer->ID_CUSTOMER; ?>')">
                                                    <i class="fa fa-check-square-o"></i> Set
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                   <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>    
                        </tbody>
                    </table>
                </div>
                
            <?php } ?>         
            </div>
        </div>
        
    	<div class="clearfix"></div>
    </div>
    
</div>
    <input id="data_page" type="hidden"  value="<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/data.php" />
    <input type='hidden' id='proses_page' value='<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php' />
</form>

