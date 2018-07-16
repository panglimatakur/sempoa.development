<?php defined('mainload') or die('Restricted Access'); ?>
<form method="post" action="">
    <div class='form-group'>
    	<label>Judul Tulisan</label>
    <input type="text" id="subject_report" name="subject_report" class="col-md-6" value="<?php echo @$subject_report; ?>"/>
    </div>
    <div class='form-group'>
    	<label>Isi Tulisan</label>
        <textarea name="question_report" id="question_report" class='col-md-6'><?php echo @$question_report; ?></textarea>
    </div>
    <div class='form-group'>
        <button type="submit" name="direction" value="show" class="btn btn-sempoa-1">Lihat Data</button>
    </div>
</form>