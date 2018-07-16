<?php defined('mainload') or die('Restricted Access'); ?>
    <div id="loader"></div>
    <div class="form-group">
    <select name="id_com" id="id_com" class="form-control validate[required] text-input">
        <option value=''>--PILIH KOMUNITAS--</option>
        <?php
        while($data_com = $db->fetchNextObject($query_com)){
        ?>
            <option value='<?php echo $data_com->ID_COMMUNITY; ?>' <?php if(!empty($id_com) && $id_com == $data_com->ID_COMMUNITY){?> selected<?php } ?>><?php echo $data_com->NAME; ?>
            </option>
    <?php } ?>
    </select>
    </div>
    <div class="form-group" style="border:0">
        <div id="merchant_list_2">
            <div style="display:none"></div>
        </div>
    </div>
    <div class="form-group">
        <button id="save_com" type="submit"  class="btn btn-large btn-sempoa-1" value="insert">
            <i class="icsw16-white icsw16-facebook-like-2"></i> Simpan Data
        </button>
    </div>
