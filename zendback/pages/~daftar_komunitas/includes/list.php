<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox float-e-margins">
<?php 
while($dt_comm	= $db->fetchNextObject($q_list_comm)){ 
	$lastID = $dt_comm->ID_COMMUNITY;
	$str_statistik = "
		SELECT 
			COUNT(a.ID_CUSTOMER) AS JML_COIN, 
			SUM(70000) AS TOTAL_PROFIT
		FROM 
			cat_customers a,cat_communities_merchants b 
		WHERE 
			b.ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND
			 a.ID_CLIENT != '1' AND
			a.ID_CLIENT = b.ID_CLIENT";
	$q_statistik 	= $db->query($str_statistik);
	$dt_statistik	= $db->fetchNextObject($q_statistik);
	
	$str_statistik_aktif = "
		SELECT 
			COUNT(a.ID_CUSTOMER) AS JML_COIN_AKTIF, 
			SUM(70000) AS TOTAL_PROFIT_AKTIF
		FROM 
			cat_customers a,cat_communities_merchants b 
		WHERE 
			a.CUSTOMER_STATUS = '3' AND
		    a.ID_CLIENT != '1' AND
			b.ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND
			a.ID_CLIENT = b.ID_CLIENT";
	$q_statistik_aktif 	= $db->query($str_statistik_aktif);
	$dt_statistik_aktif	= $db->fetchNextObject($q_statistik_aktif);
?>
<div class="w-box-content col-md-4 merchants_class">
	<div class='ibox-title'><?php echo @$dt_comm->NAME; ?>
    	<div class="pull-right">
            <div class="toggle-group">
                
                <ul class="dropdown-menu">
                    <li>
                    	<a href="javascript:void()" onclick="show_abacus('<?php echo $dt_comm->ID_COMMUNITY; ?>')">
                        <i class="icsw16-abacus" style="margin:-2px 4px 0 0"></i>Lihat Statistik
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="community_<?php echo $dt_comm->ID_COMMUNITY; ?>" style="display:none">
        <table class="table table-striped " style="width:100%">
            <tbody>
            	<tr>
                	<td>JUMLAH COIN</td>
                    <td><?php echo $dt_statistik->JML_COIN; ?></td>
                </tr>
            	<tr>
                	<td>JUMLAH COIN AKTIF</td>
                    <td><?php echo $dt_statistik_aktif->JML_COIN_AKTIF; ?></td>
                </tr>
            	<tr>
                	<td>POTENSI PROFIT</td>
                    <td><?php echo money("Rp.",$dt_statistik->TOTAL_PROFIT); ?></td>
                </tr>
            	<tr>
                	<td>PROFIT AKTIF</td>
                    <td><?php echo money("Rp.",$dt_statistik_aktif->TOTAL_PROFIT_AKTIF); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
		$q_merchant = $db->query("SELECT * FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY = '".$dt_comm->ID_COMMUNITY."' AND ID_CLIENT != '1' ORDER BY ID_COMMUNITY_MERCHANT ASC");
	?>
    <table class="table table-striped " style="width:100%">
        <tbody>
        <?php
		while($dt_merchant	= $db->fetchNextObject($q_merchant)){
			$q_client 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$dt_merchant->ID_CLIENT."'");
			$dt_client	= $db->fetchNextObject($q_client);
			
		?>
              <tr id="tr_<?php echo @$dt_merchant->ID_COMMUNITY_MERCHANT; ?>">
                <td width="46">
                <?php if(allow('delete') == 1){?>
                    <a href='javascript:void()' onclick="removal('<?php echo $dt_merchant->ID_COMMUNITY_MERCHANT; ?>','<?php echo $dt_merchant->ID_COMMUNITY; ?>')" class="btn btn-mini" title="Delete"> <i class="icon-trash"></i> </a>
                <?php } ?>
                </td>
                <td width="127">
                	<?php echo getclientlogo($dt_client->ID_CLIENT," class='thumbnail' style='width:50px'"); ?>
                </td>
                <td width="835">
					<span class='code'><?php echo @$dt_client->CLIENT_NAME; ?></span>
                    <br />
                	<span style="font-size:11px"><?php if(!empty($dt_client->CLIENT_STATEMENT)){ echo printtext($dt_client->CLIENT_STATEMENT,100)."..."; } ?></span>
                </td>
            </tr>
         <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>  
<div class='wrdLatest' data-info='<?php echo $lastID; ?>'></div>
</div>