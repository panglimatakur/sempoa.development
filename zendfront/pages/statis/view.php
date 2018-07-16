<?php defined('mainload') or die('Restricted Access'); ?>
<div class="container-fluid" style="margin-top:15px;">
    <div class="col-md-7">
        <h2><?php echo @$dt_statis->TITLE; ?></h2>
        <p><?php echo @$dt_statis->CONTENT; ?></p>
    </div>
    <div class="col-md-4" style="margin-left:0; padding-left:0">
        <h3>Artikel</h3>
        <ul>
        <?php while($dt_title = $db->fetchNextObject($q_title)){?>
            <li>
            	<a href="<?php echo $dirhost; ?>/website/artikel/<?php echo $dt_title->ID_POST; ?>">
					<?php echo $dt_title->POST_TITLE; ?>
                </a>
                <br />
                <i class="glyphicon glyphicon-calendar" ></i>
                <small><?php echo $dtime->now2indodate2(@$dt_title->TGLUPDATE); ?></small>
            </li>
        <?php } ?>
        </ul>
    </div>
    <div class="cl">&nbsp;</div>
</div>