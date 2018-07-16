<?php defined('mainload') or die('Restricted Access'); ?>
<form id="formID" class="formular" action="" method="POST" enctype="multipart/form-data">
<div class="col-md-6">
    <div class="fileupload fileupload-new" data-provides="fileupload">
        <div class="input-append">
            <div class="uneditable-input input-small">
                <i class="icon-file fileupload-exists"></i>
                <span class="fileupload-preview"></span>
            </div>
            <span class="btn btn-file">
                <span class="fileupload-new">Masukan File Excel</span>
                <span class="fileupload-exists">Change</span>
                <input type="file">
            </span>
            <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                    <button name="Submit" type="submit" class="btn btn-sempoa-1" style="margin-left:3px">Export Data</button>
                    <input type='hidden' name='direction' id='direction' value='export' />
        </div>
    </div>
</div>
</form>