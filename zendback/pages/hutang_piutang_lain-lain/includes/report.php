<?php defined('mainload') or die('Restricted Access'); ?>
<div class="ibox-title">
    <h4>Laporan Keuangan 1 Minggu Terakhir</h4>
    <div class="pull-right">
        <div class="toggle-group">
            
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo $inc_dir; ?>/print_report.php<?php echo @$lcondition; ?>" target="_blank">
                    <i class="icsw16-printer" style="margin:-2px 4px 0 0"></i>Cetak Data
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="ibox-content">
    <ul id="browser" class="filetree">
        <?php
        $t = 0;
        while($dt 	= $db->fetchNextObject($qlink)){
            $t++;
            $close = ""; 
            if($dt->IS_FOLDER == 2){ $close = "display:none"; }
            $total_t			= $db->sum("CASH_VALUE",$tpref."cash_flow"," WHERE ID_CASH_TYPE='".$dt->ID_CASH_TYPE."' AND ID_CLIENT='".$_SESSION['cidkey']."' ".@$condition."");
        ?>
            <li id="li_<?php echo $dt->ID_CASH_TYPE; ?>">
                <div id='link_list'>
                    <p class='link1' style="float:left">
                    <a href="<?php echo $lparam; ?>&status_lunas=<?php echo $status_lunas; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>" class="folder">
                        <?php echo $dt->NAME; ?>
                    </a>
                    </p>
                    <p class='buttons1' style="float:right; margin-right:5px; <?php echo @$close; ?>">
                        <a href="<?php echo $lparam; ?>&status_lunas=<?php echo $status_lunas; ?>&parent_id=<?php echo $dt->ID_CASH_TYPE; ?>">
                            <?php echo money("Rp",$total_t); ?>
                        </a>
                    </p>
                 </div>
                 <br clear="all" />
                <?php echo transaction_type_list_2($dt->ID_CASH_TYPE); ?>
            </li>
        <?php } ?>	
        <br clear="all" />
    </ul>
</div>