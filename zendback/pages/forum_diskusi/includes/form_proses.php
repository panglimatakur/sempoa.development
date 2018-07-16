<?php defined('mainload') or die('Restricted Access'); ?>
<?php if(allow('insert') == 1){?>
<iframe src="" name="proses" frameborder="0" height="0" ></iframe><!-- height="200" width="600"-->
<form method="post" id="form_forum" action="<?php echo $ajax_dir; ?>/proses.php" enctype="multipart/form-data" target="proses">
<?php if(!empty($cover)){?>
	<div class="form-group" id="pic_fr">
        <img src="<?php echo $dirhost; ?>/files/images/<?php echo $cover; ?>" class='thumbnail'>
        <button type="button" class='btn' id="del_pic" value='<?php echo $no; ?>'>
        	<i class="icsw16-trashcan"></i>Hapus Cover
         </button>
        
    </div>
<?php } ?>
	<a name="form"></a>
	<div class="form-group">
        <label>Cover Tulisan</label>
        <input type="file" id="cover" name="cover"/>
    </div>
	<div class="form-group">
        <label>Judul Tulisan</label>
        <input type="text" id="subject" name="subject" class="col-md-6" placeholder="Judul" value="<?php echo @$subject; ?>" /> 
    </div>
	<?php if($_SESSION['uidkey'] == 1){?> 
	<div class="form-group">
        <label>Meta Title</label>
        <input type="text" id="meta_title" name="meta_title" class="col-md-6" placeholder="Meta Title" value="<?php echo @$meta_title; ?>" /> 
    </div>
    <div class="form-group">
        <label>Meta Keywords</label>
        <input type="text" id="meta_keywords" name="meta_keywords" class="col-md-6" placeholder="Meta Kata Kunci" value="<?php echo @$meta_keywords; ?>" /> 
    </div>
	<div class="form-group">
        <label>Meta Description</label>
        <input type="text" id="meta_description" name="meta_description" class="col-md-6" placeholder="Meta Deskripsi" value="<?php echo @$meta_description; ?>" /> 
        <br />
        <input type="checkbox" name="as_article" value='' style="margin:-3px 0 0 0"/> Terbitkan Sebagai Artikel
    </div>
    <?php } ?>
	<div class="form-group">
        <label>Isi Tulisan</label>
        <textarea name="question" id="question" cols="30" rows="90"><?php echo @$question; ?></textarea>
        <div class="ibox-content" id="ans_content"></div>
        <span id="div_dest">
            <br />
            <div id="participants"></div>
            <br clear="all" />
            <label>Siapa saja yang dapat melihat tulisan ini?</label>
            <select id="destiny" name="destiny">
                <option value="umum">Umum</option>
                <option value="komunitas">Komunitas</option>
                <option value="personal">Personal</option>
            </select>
            <span id="id_search" style="display:none">
                <input type="text" class="col-md-6" id="search"/>
                <button type="button" id="btn_search" class="btn" style="margin:-9px 0 0 0"><i class="icsw16-magnifying-glass"></i></button>
                <span id="div_destiny"></span>
            </span>
       </span>
       
        <div class='form-group' style="padding:4px 0 0 0" >
            <button type="sumbit" name="direction" value="<?php echo $command_button; ?>" style="margin:0" class="btn btn-sempoa-1" >Simpan Tulisan</button>
        </div>
        <input type="hidden" id="id_parent" name="id_parent" value="" />
        <input type="hidden" id="id_post" name="id_post" value="<?php echo @$id_post; ?>" />
        <input type="hidden" id="reply" name="reply" value="" />
    </div>
</form>
<?php }else{
	echo msg("Maaf, Anda Tidak Di Izinkan melakukan Perancangan Polling Survey, karena hak proses anda di batasi","error");	
}?>