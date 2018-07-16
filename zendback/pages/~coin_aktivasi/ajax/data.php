<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	if(!empty($_SESSION['cidkey'])){
		if(!defined('mainload')) { define('mainload','SEMPOA',true); }
		include_once("../../../../includes/config.php");
		include_once("../../../../includes/classes.php");
		include_once("../../../../includes/functions.php");
		include_once("../../../../includes/declarations.php");
		$direction 			= isset($_REQUEST['direction']) 		? $_REQUEST['direction'] 		: "";
		$id_client_parent	= isset($_REQUEST['id_client_parent']) 	? $_REQUEST['id_client_parent'] : "";
		$coin_numbers		= isset($_REQUEST['coin_numbers']) 		? $_REQUEST['coin_numbers'] 	: "";
		
		if(!empty($_POST['form_name'])){ $form_name 		= isset($_POST['form_name']) ? $_POST['form_name'] : ""; }
	}else{
		defined('mainload') or die('Restricted Access');
	}
}else{
	defined('mainload') or die('Restricted Access');
}

	if((!empty($direction) && $direction == "get_city") || !empty($kota)){
		if(!empty($_POST['propinsi'])){ $propinsi 		= isset($_POST['propinsi']) 		? $_POST['propinsi'] : ""; }
	?>
            <div class="form-group">
                <label class="req">Kota</label>
                <select name="kota" id="kota" class="form-control validate[required] text-input">
                    <option value=''>--PILIH KOTA--</option>
                    <?php
                    $query_kota = $db->query("SELECT * FROM system_master_location WHERE PARENT_ID = '".$propinsi."' ORDER BY NAME ASC");
                    while($data_kota = $db->fetchNextObject($query_kota)){
                    ?>
                        <option value='<?php echo $data_kota->ID_LOCATION; ?>' <?php if(!empty($kota) && $kota == $data_kota->ID_LOCATION){?> selected<?php } ?>>
							<?php echo $data_kota->NAME; ?>
                        </option>
                <?php } ?>
                </select>
            </div>
    <?php		
	}

	if((!empty($direction) && $direction == "get_customer")){
	$display 			= isset($_REQUEST['display']) 		? $_REQUEST['display'] 		: "";
	$lastId 			= isset($_REQUEST['lastId']) 		? $_REQUEST['lastId'] 		: "";
	$last_row 			= isset($_REQUEST['last_row']) 		? $_REQUEST['last_row'] 	: "";
	$condition				= "";
	if(empty($coin_numbers)){
		$condition 			.= " AND ID_CLIENT='".$id_client_parent."'";
	}else{
		$coin_numbers 	= explode(";",$coin_numbers); 
		$t 				= 0;
		foreach($coin_numbers as &$coin_number){
			$t++;
			if($t == 1){ $operator = "AND"; }else{ $operator = "OR"; } 
			$condition 		.= $operator." COIN_NUMBER='".$coin_number."'";
		}
	}
	if(!empty($display) && $display == "list_report"){ $condition .= " AND ID_CUSTOMER > '".$lastId."'"; $y = $last_row; }
	$str_query			= "SELECT * FROM ".$tpref."customers WHERE ID_CLIENT IS NOT NULL ".$condition." ORDER BY ID_CUSTOMER ASC"; 
	#echo $str_query;
	$q_customer 		= $db->query($str_query." LIMIT 0,20");
	$num_customer		= $db->recount($str_query);
	
	?>
    	<div class='ibox-title'>
        <input type="hidden" id="id_client_parent" value="<?php echo $id_client_parent; ?>" />
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
        <a name="report"></a>
		<?php if($num_customer > 0){ ?>
        <div class="ibox-content">
        <table width="100%" class="table table-bordered table-striped tbl_cust">
            <thead>
                <tr>
                    <th colspan="2">INFORMASI COIN</th>
                </tr>
            </thead>
            <tbody>
                  <?php 
				  if(empty($y)){ $y = 0; } 
				  while($dt_customer	= $db->fetchNextObject($q_customer)){ $y++; 
						  $tgl				= "";
						  $bln				= "";
						  $thn				= "";
						  $lastId 			= $dt_customer->ID_CUSTOMER;
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
				if(!empty($dt_customer->ID_CLIENT_TITANIUM)){ 
					$id_client_titanium[$id_cust] = $dt_customer->ID_CLIENT_TITANIUM; 
				}else{
					$id_client_titanium[$id_cust] = '1';	
				}
				$nm_client			= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT='".$dt_customer->ID_CLIENT."'");
				?>
                  <tr id="tr_<?php echo $dt_customer->ID_CUSTOMER; ?>">
                    <td width="66"><?php echo getmemberfoto($dt_customer->ID_CUSTOMER,"class='thumbnail' style='width:50px'"); ?></td>
                    <td width="545" >
                    	<div class='form-group'>
                        	<?php echo @$y; ?>
                        </div>
                        <div class='form-group col-md-6'>
                        	<label class='code'>MERCHANT</label><br />
							<?php echo @$nm_client; ?>
                        </div>
                        <div class='form-group col-md-6'>
                        	<label class='code'>COIN</label><br />
							<?php echo strtoupper($dt_customer->COIN_NUMBER); ?>
                        </div>
                        <div class='form-group col-md-6'>
                       	  <label class='code'>Nama</label> 
						  <input type='text' id="cust_name_<?php echo $id_cust; ?>" value='<?php if(!empty($dt_customer->CUSTOMER_NAME)){ echo @$dt_customer->CUSTOMER_NAME; }else{ ?> Unknown <?php } ?>' class="form-control"/>
                       </div>
                       <div class='form-group col-md-6'>
                          <label class='code'>Telephone</label>
						  <input type='text' id="cust_phone_<?php echo $id_cust; ?>" value='<?php if(!empty($dt_customer->CUSTOMER_PHONE)){ echo @$dt_customer->CUSTOMER_PHONE; }else{?> Unknown <?php } ?>' class="form-control"/>
                      </div>
                      <div class='form-group col-md-6'>
                          <label class='code'>Email</label>
						  <input type='text' id="cust_email_<?php echo $id_cust; ?>" value='<?php if(!empty($dt_customer->CUSTOMER_EMAIL)){ echo @$dt_customer->CUSTOMER_EMAIL; }else{?> Unknown <?php } ?>' class="form-control"/>
                      </div>
					  <?php if(!empty($dt_customer->CUSTOMER_SEX)){?>
                        <div class='form-group col-md-6'>
                            <label class='code'>Jenis Kelamin</label>
                            <?php if($dt_customer->CUSTOMER_SEX == "L"){ echo "Laki-laki"; }?>
                            <?php if($dt_customer->CUSTOMER_SEX == "P"){ echo "Perempuan"; }?> 
                        </div>
                      <?php } ?>
                      
                      <?php 
					  if($dt_customer->ID_CLIENT == "1"){
						$query_branch 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL ".$condition_client." ORDER BY CLIENT_NAME");
					  ?>
                        <div class='form-group col-md-6'>
                            <label class='code'>ID CLIENT TITANIUM</label>
                            <select name="id_client_titanium_<?php echo $id_cust; ?>" id="id_client_titanium_<?php echo $id_cust; ?>" class="form-control mousetrap" >
                                <option value=''>--PILIH CLIENT--</option>
                                <?php
                                while($data_branch = $db->fetchNextObject($query_branch)){
                                ?>
                                    <option value='<?php echo $data_branch->ID_CLIENT; ?>' <?php if(!empty($id_client_titanium[$id_cust]) && $id_client_titanium[$id_cust] == $data_branch->ID_CLIENT){?> selected<?php } ?>><?php echo $data_branch->CLIENT_NAME; ?>
                                    </option>
                            <?php } ?>
                            </select>
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
        <div class='wrdLatest' data-info='<?php echo $lastId; ?>'></div>
        </div>
        <input type='hidden' id="last_row" value='<?php echo $y; ?>'></div>
        <div class="ibox float-e-margins" id="paging">  
            <div id="lastPostsLoader"></div>
            <div class="ibox-title" style="text-align:center">
                <?php if($num_customer > 20){?>
                    <a href='javascript:void()' onclick="lastPostFunc()" class='next_button'><i class='icon-chevron-down'></i>SELANJUTNYA</a>
                <?php } ?>
                <br clear="all" />
            </div>            
        </div> 
		<?php }else{
			echo "<br>";
            echo msg("Tidak Ada Pelanggan Yang Terdaftar","error");
        } ?>
	<?php 
	} 
	?>	
    