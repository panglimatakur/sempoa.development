<?php defined('mainload') or die('Restricted Access'); ?>

<div class="col-md-12">
    <div class="ibox float-e-margins">
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
        <div class="ibox-title">
            <h5>Form Pendaftaran Pengguna</h5>
            <div class="ibox-tools">
                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </div>                
        </div>
        <div class="ibox-content">
            <?php include $call->inc($page_dir."/includes","form.php");  ?>
            <div class="clearfix"></div>
        </div>
    </div>
    
    <?php if($_SESSION['cidkey'] == 1 && $_SESSION['ulevelkey'] == 1){?>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Filter Pencarian Pengguna</h5>
        </div>
        <div class="ibox-content">
            <form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
                <div class="form-group col-md-6">
                    <label>Daftar Merchant</label>
                    <select name="id_client_form_op" id="id_client_form_op" class="form-control mousetrap" />
                        <option value=''>--PILIH MERCHANT--</option>
                        <?php
                        while($data_branch = $db->fetchNextObject($query_branch)){
                        ?>
                            <option value='<?php echo $data_branch->ID_CLIENT; ?>' 
                                    <?php if(!empty($id_client_form) && $id_client_form == $data_branch->ID_CLIENT){?> selected<?php } ?>>
                                    <?php echo $data_branch->CLIENT_NAME; ?>
                            </option>
                            <?php echo merchant_list($data_branch->ID_CLIENT,1);?>
                    <?php } ?>
                    </select>
                    <input type="hidden" name="id_client_form" id="id_client_form" value="<?php echo @$id_client_form; ?>" />
                </div>
                <div class="form-group col-md-6">
                    <label >&nbsp;</label><br />
                    <button name="direction" type="submit" value='show' id="button_cmd" class="btn btn-sempoa-1">
                        <i class="fa fa-eye"></i> Lihat Data
                    </button>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
    </div>
    <?Php } ?>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Daftar Pengguna</h5>
        </div>
        <div class="ibox-content">
            <table width="100%" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="92" style="text-align:center">Photo</th>
                        <th width="742">Nama</th>
                        <th width="181" style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                      <?php while($dt_user	= $db->fetchNextObject($q_user)){ 
                            $jabatan =  $db->fob("NAME","system_master_client_users_level"," WHERE ID_CLIENT_USER_LEVEL='".$dt_user->ID_CLIENT_USER_LEVEL."'");
                      		@$nama_merchant		=	$db->fob("CLIENT_NAME","".$tpref."clients","where ID_CLIENT='".$dt_user->ID_CLIENT."'");

					  ?>
                      <tr class="wrdLatest" data-info='<?php echo $dt_user->ID_USER; ?>' id="tr_<?php echo $dt_user->ID_USER; ?>"  >
                        <td style="text-align:center;" class="align-top">
                            <a href="javascript:void()" onclick="modal_ajax(this)" modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/detail.php?id_list=<?php echo $dt_user->ID_USER; ?>","size":"modal-lg"' title="Detail">
                            <div class="thumbnail">
                                <div class="thumbnail-inner" style="height: 60px;">
                                    <?php if(!empty($dt_user->USER_PHOTO) && is_file($basepath."/".$user_foto_dir."/".$dt_user->USER_PHOTO)){?>
                                        <img src='<?php echo $dirhost; ?>/<?php echo $user_foto_dir; ?>/<?php echo $dt_user->USER_PHOTO; ?>' style="width:100%"/>
                                    <?php }else{ ?>
                                        <img src='<?php echo $dirhost; ?>/files/images/noimage-m.jpg' style="width:100%"/>
                                    <?php } ?>
                                </div>
                            </div>
                            </a>
                        </td>
                        <td class="align-top">
                        	<h3 class="text-info"><?php echo $nama_merchant; ?></h3>
                            <b style="color:#C00"><?php echo $dt_user->USER_NAME; ?></b><br>
                            <div class="form-group" style="margin-bottom:3px;">
                            	<label>Telephone</label><br />
								<?php echo $dt_user->USER_PHONE; ?>
                            </div>
                            <div class="form-group" style="margin-bottom:3px;">
                            	<label>Email</label><br />
								<?php echo $dt_user->USER_EMAIL; ?>
                            </div>
                            <div class="form-group" style="margin-bottom:3px;">
                            	<label>Jabatan</label><br />
								<?php echo $jabatan; ?>
                            </div>
                           <?php if(!empty($dt_user->ADDITIONAL_INFO)){?>
                                <div class="form-group">
                                	<label>Keterangan</label><br />
                                	<span class='code'><?php echo $dt_user->ADDITIONAL_INFO; ?></span>
                                </div>
                            <?php } ?>
                        </td>
                        <td style="text-align:center" class="align-top">
                            <div class="btn-group">
                                <?php if($_SESSION['admin_only'] == "true"){?>
                                <button onclick="send_starterpack('<?php echo $dt_user->ID_CLIENT; ?>')" type="button" class="btn btn-sm btn-sempoa-2" id="start_<?php echo $dt_user->ID_CLIENT; ?>" title="Kirim StarterPack">
                                    <i class="fa fa-reorder"></i> 
                                </button>
                                <?php } ?>
                                <a href="javascript:void()" class="btn btn-sm btn-info" onclick="modal_ajax(this)"  modal-ajax-options='"url":"<?php echo $dirhost; ?>/<?php echo $ajax_dir; ?>/detail.php?id_list=<?php echo $dt_user->ID_USER; ?>","size":"modal-lg"' title="Detail">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <?php if(allow('edit') == 1){?> 
                                <a href="?page=<?php echo $page; ?>&direction=edit&no=<?php echo $dt_user->ID_USER; ?>" class="btn btn-sm btn-danger " title="Edit">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <?php } ?>
                                <?php if(allow('delete') == 1){?> 
                                <a href='javascript:void()' onclick="removal('<?php echo $dt_user->ID_USER; ?>','table')" class="btn btn-sm btn-warning" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>    
                </tbody>
            </table>
            <div id="lastPostsLoader"></div>
            <div class="ibox-title" style="text-align:center">
                <?php if($num_user > 50){?>
                    <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
                <?php } ?>
                <br clear="all" />
            </div>       
            
            <input type='hidden' id='download_page' value='<?php echo @$dirhost; ?>/<?php echo $ajax_dir; ?>/download.php'>
            <input type="hidden" id="proses_page" value="<?php echo @$dirhost; ?>/<?php echo $ajax_dir; ?>/proses.php" />
            <input type="hidden" id="data_page" value="<?php echo @$dirhost; ?>/<?php echo $ajax_dir; ?>/data.php" />
               
        </div>
    </div>
</div>

<div id="modal-detail" class="modal fade" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated flipInY">
            <div class="modal-body no-padding"></div>
        </div>
    </div>
</div>
