<?php defined('mainload') or die('Restricted Access'); ?>
<style type="text/css">
	.coffee-loader{
		width:100px;
		height:100px;
		overflow:hidden;
		padding-top:10px;
		margin-top:-10px;
		border-radius:90px;
		-moz-border-radius:90px;
		-webkit-border-radius:90px;
		background:#ffc466;
	}
</style>
<section id="page-breadcrumb">
    <div class="vertical-center sun">
         <div class="container">
            <div class="row">
                <div class="action">
                    <div class="col-sm-12">
                        <h1 class="title">Form Registrasi</h1>
                        <p>Pendaftaran keanggotaan sempoa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script language="javascript" src="<?php echo $dirhost; ?>/zendfront/pages/mendaftar/js/js.js"></script>
<section id="blog" >
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
				<?php 
                    if(!empty($msg)){
                        switch ($msg){
                            case "1":
                                echo msg("Data Link Berhasil Disimpan","success");
                            break;
                            case "2":
                                echo msg("Pengisian Form Belum Lengkap","error");
                            break;
                            case "3":
                                echo msg("Merchant ini sudah terdaftar","error");
                            break;
                        }
                    }
                ?>
            
                <input type="hidden" id="ct_proses_page" 
                	   value="<?php echo $dirhost; ?>/zendfront/pages/mendaftar/ajax/proses.php"/>
                <input type="hidden" id="data_page" 
                	   value="<?php echo $dirhost; ?>/zendfront/pages/mendaftar/ajax/data.php"/>
                <form id="formID" class="formular" method="post" action="return false" enctype="multipart/form-data" 
                	  onsubmit="return false">
                    <input type="hidden" name="package" value="<?php echo @$package; ?>"/>
                    <input type="hidden" name="paket_id" value="<?php echo @$paket_id; ?>" />
                    <input type="hidden" name="jenis" value="<?php echo @$jenis; ?>" />
                    <h4 class="page-header"><b>Status Pendaftar / Pengelola</b></h4>
                    <ol class="">
                    	<li><h4 class="page-header"><b>Apakah merchant/perusahaan anda sudah pernah terdaftar sebelumnya di 
							<?php echo $website_name; ?> ?</b></h4>
                            <input type="checkbox" id="registered_status"></select>
                            <br /><br />
                            <div class="well">
                                Simbol bintang (<span class="text-danger">*</span>) adalah penting untuk di isi.
                            </div>
                        </li>
                    	<li><h4 class="page-header req" id="merchant_header"><b>Informasi Merchant / Perusahaan</b></h4>
                            <div id="ever" style="display:none">
                              <div class="form-group col-md-6">
                                  <select id="merchant_name"  name="merchant_name" class="form-control">
                                        <option value="">--PILIH MERCHANT--</option>
                                        <?php while($dt_merchant = $db->fetchNextObject($q_merchant)){?>
                                        <option value="<?php echo $dt_merchant->ID_CLIENT; ?>"
                                                <?php if(!empty($merchant_id) && 
                                                         $merchant_id == $dt_merchant->ID_CLIENT){?>selected<?php } ?>
                                            ><?php echo $dt_merchant->CLIENT_NAME; ?></option>
                                        <?php } ?>
                                  </select>
                              </div>
                              <input type="hidden" id="merchant_id" name="merchant_id"/>
                              <div class='clearfix'></div>
                            </div>
                            
                            
                            <div id="never">
                                <div class="form-group col-md-6">
                                    <label class="req">Nama Merchant</label>
                                    <input name="nama" id="nama" type="text" value="<?php echo @$nama; ?>" class="form-control uppercase"/>
                                </div>
                                <div class="form-group col-md-6">
                                  <label class="req">E-Mail Merchant</label>
                                  <input type="email" name="email_brand" id="email_brand" value="<?php echo @$email_brand; ?>" class="form-control lowercase" />
                                </div>
                                <div class="form-group col-md-6">
                                  <label class="req">No Tlp Merchant</label>
                                  <input type="number" name="tlp" id="tlp" value="<?php echo @$tlp; ?>" class="form-control" />
                                </div>
                                <div class="form-group col-md-6">
                                  <label>Website Merchant (Optional)</label>
                                  <input type="url" name="website" id="website" value="<?php echo @$website; ?>" class="form-control lowercase" placeholder="http://"/> 
                                  <div class='cl'></div>
                                </div>
                                <div class="form-group col-md-6">
                                  <label class="req">Alamat Merchant</label>
                                  <textarea name="alamat" id="alamat" class="form-control capitalize"><?php echo @$alamat; ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                  <label class="req">Deskripsi Merchant</label> 
                                  <textarea name="deskripsi" id="deskripsi" class="form-control capitalize"><?php echo @$deskripsi; ?></textarea>
                                </div>
                                <div class='clearfix'></div>
                            </div>
                       </li>     
                       <li><h4 class="page-header req"><b>Informasi Pendaftar / Pengelola</b></h4>
                            <div class="form-group col-md-12">
                                <small>NB : Untuk melihat fungsi masing-masing jabatan,pilih salah satu jabatan yang di tentukan, lali klik icon <span class="text-danger">(<i class="fa fa-question"></i>)</span> 
                                pada sisi kanan kolom jabatan
                                </small>
                             </div>
                            <div class="form-group col-md-6">
                              <span id="jabatan_loader"></span>
                              <label class="req">Jabatan </label>
                              <div class="input-group">
                              <select name="id_jabatan" id="id_jabatan" class="form-control">
                                    <?php while($dt_jabatan = $db->fetchNextObject($q_jabatan)){?>
                                    <option value="<?php echo $dt_jabatan->ID_CLIENT_USER_LEVEL; ?>"
                                            <?php if(!empty($id_jabatan) && 
                                                     $id_jabatan == $dt_jabatan->ID_CLIENT_USER_LEVEL){?>
                                                      selected
											<?php } ?>
                                        >
                                        <?php echo $dt_jabatan->NAME; ?>
                                    </option>
                                    <?php } ?>
                              </select>
                              		<span class="input-group-addon">
                                    	<a href="javascript:void();" id="level-help" data-level='1'>
                                        	<i class="fa fa-question"></i>
                                        </a>
                                    </span>
                              </div>
                            </div>
                            <div class="form-group col-md-6">
                              <label class="req">Nama</label>
                              <input type="text" name="nama_pemohon" id="nama_pemohon" value="<?php echo @$nama_pemohon; ?>" class="form-control capitalize" />
                            </div>
                            <div class="form-group col-md-6">
                              <label class="req">No HP</label>
                              <input type="number" name="kontak" id="kontak" value="<?php echo @$kontak; ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                              <label class="req">Email</label>
                              <input type="email" name="email" id="email" value="<?php echo @$email; ?>" class="form-control lowercase" />
                            </div>
                            <div class="form-group col-md-6">
                              <label class="req">Password</label>
                              <input type="password" name="new_pass" id="new_pass" value="<?php echo @$new_pass; ?>" class="form-control" />
                            </div>
                            <div class="form-group col-md-6">
                              <label class="req">Ulangi Password</label>
                              <input type="password" name="konf_new_pass" id="konf_new_pass" value="<?php echo @$konf_new_pass; ?>" class="form-control" />
                            </div>
                            <div class="clearfix"></div>
                            
                            
                            <div class="form-group col-md-12">
                                <span id="register_loader"></span>
                                <button  type="submit"  name="direction" class="btn btn-warning" value="register" ><i class="fa fa-check-square-o"></i> Kirim Pendaftaran</button><!--id="save_direction"-->
                                
                            </div>
                    	</li>
                    </ol>
                    <div class="clearfix"></div>
                    
                    
                    
                </form>
            </div>
        </div>
     </div>
</section>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><h4><b>Fungsi Jabatan</b></h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>