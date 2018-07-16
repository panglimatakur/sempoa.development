<?php defined('mainload') or die('Restricted Access'); ?>
<div class="col-md-12" >
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
    <div class="ibox float-e-margins" style="background:#FFF">
        <div class="ibox-title">
            <h5>
            Filter</h4>
        </div>
        
        <form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
            <label>Daftar Client</label>
            <select name="id_client_form" id="id_client_form" class="form-control mousetrap" />
                <option value=''>--PILIH CLIENT--</option>
                <?php
                while($data_branch = $db->fetchNextObject($query_branch)){
                ?>
                    <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_client_form) && $id_client_form == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?>
                    </option>
            <?php } ?>
            </select>
            </div>
            <div class="form-group">
              <label >&nbsp;</label>
                <button name="direction" type="submit" value='show' id="button_cmd" class="btn btn-sempoa-1">Lihat Data</button>
            </div>
        </form>
        
    </div>
    <?php
	if(!empty($id_client_form)){ ?>
    <div class="ibox float-e-margins">
    	<div class="col-md-12">
          <div class="ibox-title">
                <h4>Daftar Merchant</h4>
                <div class="pull-right">
                    <div class="toggle-group">
                        
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo $page_dir; ?>/includes/print.php?id_client_form=<?php echo $dt_client->ID_CLIENT ?>" target="_blank">
                                <i class="icsw16-fax" style="margin:-2px 4px 0 0"></i>Print Starter Pack
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <table width="100%" class="table table-striped" id="client_list">
                    <tbody>
                          <tr>
                            <td>
								<b>Untuk : </b> 
                                <b style="color:#C00"><?php echo $dt_client->CLIENT_NAME; ?></b>
                            </td>
                            <td style="text-align:right">
                               <b>Tanggal : <?php echo date("d-m-Y"); ?></b>
                            </td>
                        </tr>
                          <tr>
                            <td colspan="2">
                            <b>Dibawah ini adalah daftar akun perdana dan informasi URL penting</b>
                            <br />
                            <br />
                            <table width="100%">
                              <tr style="font-weight:bold">
                                <td width="3%" align="center">I</td>
                                <td width="97%">&nbsp;Link URL Aplikasi DISCOIN</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                                	<span style="color:#930">https://sempoa.community/coin/<?php echo $dt_client->CLIENT_APP; ?></span><br />
                                	( NB: Di buka melalui browser telepon genggam Android pelanggan)
                                </td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">II</td>
                                <td>&nbsp;Daftar 5 akun COIN aktif Merchant</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                               	  <table width="100%">
                                <?php while($dt_customer = $db->fetchNextObject($q_customer)){?>
                                	  <tr>
                                	    <td width="9%"><strong>USERNAME</strong></td>
                                	    <td width="11%"><?php echo $dt_customer->COIN_NUMBER; ?></td>
                                	    <td width="9%"><strong>PASSWORD</strong></td>
                                	    <td width="71%"><?php echo $dt_customer->COIN_NUMBER; ?></td>
                              	    </tr>
                           	    <?php } ?>
                                </table>
                                
                                </td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">III</td>
                                <td>&nbsp;Informasi Akun Website <?php echo $website_name; ?> (Control Panel Merchant).</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                                    <b><?php echo $dirhost; ?></b>
                                    <br />
                                    <span style="color:#F60">
                                    	( NB: Direkomendasikan di buka melalui browser Google Chrome, di Komputer atau Notebook)
                                    </span>
                                    <br />
                                    Dengan informasi akun Super Administrator awal Control Panel, yang dapat di ubah sewaktu-waktu oleh pengguna Control Panel Merchant.<br />
                                    <table width="100%">
                                      <tr>
                                        <td width="10%"><strong>USERNAME</strong></td>
                                        <td width="90%"><?php echo $dt_admin->USER_USERNAME; ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>PASSWORD</strong></td>
                                        <td><?php echo $dt_admin->USER_PASS; ?></td>
                                      </tr>
                                </table></td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">IV</td>
                                <td>Daftar Free Akun COIN Titanium ( <?php echo $dirhost; ?>/coin/titanium )</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                               	  <table width="100%">
                                <?php while($dt_titanium = $db->fetchNextObject($q_titanium)){?>
                                	  <tr>
                                	    <td width="9%"><strong>USERNAME</strong></td>
                                	    <td width="11%"><?php echo $dt_titanium->COIN_NUMBER; ?></td>
                                	    <td width="9%"><strong>PASSWORD</strong></td>
                                	    <td width="71%"><?php echo $dt_titanium->COIN_NUMBER; ?></td>
                              	    </tr>
                           	    <?php } ?>
                                </table>
                                
                                </td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">V</td>
                                <td>&nbsp;Link URL Video Tutorial dan Cara Pemakaian Control Panel Merchant.</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td><span style="color:#F60; font-weight:bold">https://sempoa.community/files/discoin.rar</span></td>
                              </tr>
                            </table></td>
                          </tr>
                    </tbody>
                </table>
      		</div>
        </div>
    </div>
    <?php } ?>
</div>
