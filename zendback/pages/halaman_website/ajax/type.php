<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	if(!defined('mainload')) { define('mainload','Sicknest',true); }
}else{
	defined('mainload') or die('Restricted Access');
}
?>
<div class="form-group col-md-12">
	<label>Tipe Halaman</label>
    <select name="contenttype" id="contenttype" class="form-control validate[required] text-input">
        <option value=''>--TIPE HALAMAN--</option>
        <option value='statis' <?php if(@$contenttype == "statis"){ ?> selected <?php } ?>>Halaman Statis</option>
        <option value='dinamis' <?php if(@$contenttype == "dinamis" ){ ?> selected <?php } ?>>Halaman Dinamis</option>
     </select>
</div>