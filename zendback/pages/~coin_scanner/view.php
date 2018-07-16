<?php defined('mainload') or die('Restricted Access'); ?>
<?php
/*$tr = $db->query("DESC cat_chat_subject");
while($we = $db->fetchNextObject($tr)){
	echo $we->Field."<br>";
}*/
?>
<div class="row-fluid">
    <input type="hidden" id="coin_merchant" value="<?php echo $_SESSION['ccoin']; ?>" />
    <div class='w-box'>
        <div class='ibox-title'>
        	<h4>Form Validasi</h4>
        </div>
        <div class='ibox-content' style="padding:20px;">
            <div id="qrcode"></div>
            <div style="clear:both"></div>
        </div>
    </div>
</div>
