<?php defined('mainload') or die('Restricted Access'); ?>
<div class="row-fluid" style=" <?php echo @$display; ?> background:#FFF" >
    <div class="ibox-title">
        <h4>Form Seleksi Pencarian Data</h4>
    </div>

<form method="post" action="" >
    <div class="form-group">
      <label>Cabang</label>
      <select name="id_branch" id="id_branch" class="form-control mousetrap">
            <option value=''>--PILIH CABANG--</option>
            <?php
            $query_branch = $db->query("SELECT * FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$_SESSION['cidkey'].",%' ORDER BY CLIENT_NAME");
            while($data_branch = $db->fetchNextObject($query_branch)){
            ?>
                <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_branch) && $id_branch == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?>
                </option>
        <?php } ?>
        </select>
    </div>
    <div class="form-group">
      <label>Keterangan</label>
      <textarea name="keterangan" id="keterangan" class="form-control mousetrap" ><?php echo @$keterangan; ?></textarea>
    </div>
    <div class="form-group">
      <label>Status Distribusi</label>
      <select name="shipp_direction" id="shipp_direction" class="form-control mousetrap">
            <option value=''>--PILIH STATUS DISTRIBUSI--</option>
            <optgroup label="Pengiriman">
            <?php
            $origin	= 2;
            $query_type = $db->query("SELECT * FROM ".$tpref."distribution_status ORDER BY ID_DISTRIBUTION_STATUS ASC");
            while($data_type = $db->fetchNextObject($query_type)){
                if($data_type->ORIGIN != $origin && empty($request)){
                    $request = 1;
                ?>
              </optgroup>
              <optgroup label="Permintaan & Pengembalian">
                <?php		
                }
            ?>
                <option value='<?php echo $data_type->ID_DISTRIBUTION_STATUS; ?>' <?php if(!empty($shipp_direction) && $shipp_direction == $data_type->ID_DISTRIBUTION_STATUS){?> selected <?php } ?>>
                    <?php echo $data_type->NAME; ?> (<?php echo $data_type->NOTE; ?>)
                </option>
        <?php 
            $origin = $data_type->ORIGIN; 
            } 
        ?>
            </optgroup>
        </select>
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-sempoa-1" name="direction" value="show" style='margin-left:0'>
        <i class="icsw16-info-about icsw16-white"></i>Lihat Data
      </button>
    </div>
    <br clear="all" />
  <input id="data_page" type="hidden"  value="modules/laporan_distribusi/ajax/data.php" />
  <input id="proses_page" type="hidden"  value="modules/input_distribusi/ajax/proses.php" />
</form>
<br clear="all" />
<br />
</div> 


<div class="row-fluid" style=" background:#FFF" >

<div class="ibox-title">
    <h4>Data Distribusi</h4>
</div>

<div class="ibox-content">
	
    <table width="100%" class="table table-striped" id="table_req">
        <tbody>
            <tr style="display:none">
              <td width="93%">&nbsp;</td>
              <td width="7%">&nbsp;</td>
            </tr>
          <?php
              while($dt_shipping_branch = $db->fetchNextObject($q_shipping_branch)){ 
			  	$type 			= $dt_shipping_branch->DISTRIBUTION_TYPE;
				if($type == "request")	{ 
					if($_SESSION['uclevelkey'] == 2){ 
						$fromfor = "Dari"; 
					}else{ 
						$fromfor = "Untuk";
					}
				}
										
				if($type == "shipping")	{ 
					if($_SESSION['uclevelkey'] == 2){ 
						$fromfor = "Untuk"; 
					}else{ 
						$fromfor = "Dari";
					}
				}
				

				if($_SESSION['uclevelkey'] == 2){ $target_condition = "WHERE ID_CLIENT='".$dt_shipping_branch->ID_BRANCH."'"; }
				else							{ $target_condition = "WHERE ID_CLIENT='".$dt_shipping_branch->ID_CLIENT."'"; }
                $client			= $db->fob("CLIENT_NAME",$tpref."clients",$target_condition);
				
				$q_status		= $db->query("SELECT NAME,NOTE FROM ".$tpref."distribution_status WHERE ID_DISTRIBUTION_STATUS='".$dt_shipping_branch->ID_DISTRIBUTION_STATUS."'");
				$dt_status		= $db->fetchNextObject($q_status);
				$status			= $dt_status->NAME;
				$note 			= $dt_status->NOTE;
				
				$closed			= "";
				if(($dt_shipping_branch->ID_DISTRIBUTION_STATUS == 5 && $_SESSION['uclevelkey'] == 2) || $_SESSION['uclevelkey'] != 2){ 
					$closed = 1; 
				}
				
          ?>
          <tr id="tr_req_<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>">
            <td style="vertical-align:top; position:relative;" >
            
            
                <span class='code'>
                    <strong><?php echo $fromfor; ?></strong> : <?php echo $client; ?>
                </span>
                <br />
                <small><i class="icsw16-day-calendar"></i><?php echo $dtime->now2indodate2($dt_shipping_branch->DISTRIBUTION_DATE); ?></small>
                <br />
                <div class='invoice_preview ' style='margin-top:17px;'>
                    <div class="inv_notes">
                        <span class="label label-info ptip_ne" >Status : <?php echo $status; ?></span>
                        <?php if(!empty($dt_shipping_branch->DESCRIPTION)){?>
                            <?php echo $dt_shipping_branch->DESCRIPTION; ?>
                        <?php } ?>
                    </div>
                </div>
                <br clear="all" />     
                    
                                                            
                <div style='border:1px solid #d5afc5; width:98%; max-height:200px; overflow:scroll;'>
                    <table width="100%" >
                    <thead>
                        <tr>
                          <th width="7%">&nbsp;</th>
                          <th width="47%">Code</th>
                          <th width="27%">Jumlah</th>
                          <th width="19%">&nbsp;</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php
                        $q_kolektif = $db->query("SELECT * FROM ".$tpref."products_stocks_history WHERE ID_PRODUCT_DISTRIBUTION = '".$dt_shipping_branch->ID_PRODUCT_DISTRIBUTION."' AND (ID_DIRECTION != '12' AND ID_DIRECTION != '9')");
                        while($dt_kolektif = $db->fetchNextObject($q_kolektif)){
                            $q_product		= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT='".$dt_kolektif->ID_PRODUCT."'");
                            $dt_product		= $db->fetchNextObject($q_product);
                            $code 			= $dt_product->CODE;
                            $photo			= $db->fob("PHOTOS",$tpref."products_photos"," WHERE ID_PRODUCT = '".$dt_product->ID_PRODUCT."'");
                            @$unit			= $db->fob("NAME",$tpref."products_units"," WHERE ID_PRODUCT_UNIT='".$dt_product->ID_PRODUCT_UNIT."'"); 
                            
                     ?>
                     
                        <tr id="td_<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>">
                            <td style="padding:0 10px 0 10px; ">
                               
                                <?php if(!empty($photo) && is_file($basepath."/files/images/products/".$id_client."/thumbnails/".$photo)){ ?>
                                    <a href='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/<?php echo $photo; ?>' class="fancybox">
                                    <img src='<?php echo $dirhost; ?>/files/images/products/<?php echo $id_client; ?>/thumbnails/<?php echo $photo; ?>' class="thumbnail" style="width:100%">
                                    </a>
                                <?php }else{ ?>
                                    <img src='<?php echo $dirhost; ?>/files/images/no_image.jpg' class="thumbnail" style="width:100%">
                                <?php } ?>
                            </td>
                            <td>
                                <span class='code'><b><?php echo $code; ?></b></span>
                                <br />
                                <?php echo $dt_product->NAME; ?>
                            </td>
                            <td><?php echo $dt_kolektif->STOCK; ?> <?php echo $unit; ?>&nbsp;</td>
                            <td >
                            	<?php if(allow('edit') == 1 && empty($closed)){ ?>
                                <a href='<?php echo $dirhost; ?>/modules/input_distribusi/ajax/catatan.php?page=input_distribusi&direction=edit&id_product_distribution=<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>&type=<?php echo $type; ?>&id_stock=<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Perbaiki Permintaan <?php echo $code; ?>">
                            	<i class="icsw16-pencil"></i>
                                </a>
                                <?php } ?>
                                <?php if(allow('delete') == 1 && empty($closed)){?>
                                <a href='javascript:void()' onclick="removal_single('<?php echo $type; ?>','<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>','<?php echo $dt_kolektif->ID_PRODUCT_STOCK; ?>')" class="btn btn-mini" title="Hapus Pengiriman">
                                    <i class="icon-trash"></i>
                                </a>
                                <?php } ?>
                            </td>
                        </tr>
                    
                        <?php }?>
                    </tbody>
                    </table>
                </div>
                <br clear="all" />                
                                
            </td>
            <td style="vertical-align:top; position:relative;" >
					<?php if(allow('edit') == 1 && empty($closed)){?>
                    <a href='<?php echo $dirhost; ?>/modules/input_distribusi/ajax/status.php?id_product_distribution=<?php echo $dt_shipping_branch->ID_PRODUCT_DISTRIBUTION; ?>&type=<?php echo $type; ?>' class="btn btn-mini fancybox fancybox.ajax" title="Ubah Status Permintaan">
                    <i class="icsw16-create-write"></i>
                    </a>
                    <?php } ?>
            </td>
          </tr>
          <?php } ?>
        </tbody>
    </table>
<div id="lastPostsLoader"></div>
</div>
<div class="ibox-title" style="text-align:center">
    <?php if($num_shipping_branch > 10){?>
    <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
    <?php } ?>
    <br clear="all" />
</div>
</div>