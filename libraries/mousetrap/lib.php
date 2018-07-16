<?php defined('mainload') or die('Restricted Access'); ?>
<?php $shortcut = $dirhost."/libraries/mousetrap/"; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="<?php echo @$shortcut; ?>js/mousetrap.js" type="text/javascript"></script>
<style type="text/css">
.cur_pointer{
	border:1px solid #CCC;	
}
</style>
<script type="text/javascript">
	$(function() {
		$('.mousetrap:first').focus();
		var input = $('.mousetrap');
		Mousetrap.bind('ctrl+down', function(e) {
			var elm 		= e.target;
			$("#"+elm.id).removeClass("cur_pointer");
			
			var nxt_index	= $(".mousetrap").index($("#"+elm.id)) + 1;
			$(".mousetrap:eq("+nxt_index+")").addClass("cur_pointer").focus();
			
			th = $(".mousetrap:eq("+nxt_index+")").prop("tagName");
			if(th == "SELECT"){
				$(".mousetrap:eq("+nxt_index+")").attr("size","10");
			}else{
				$("select").attr("size","0");
			}
		});
		Mousetrap.bind('ctrl+up', function(e) {
			var elm 		= e.target;
			$("#"+elm.id).removeClass("cur_pointer");

			var prev_index	= $(".mousetrap").index($("#"+elm.id)) - 1;
			$(".mousetrap:eq("+prev_index+")").addClass("cur_pointer").focus();
			th = $(".mousetrap:eq("+prev_index+")").prop("tagName");
			if(th == "SELECT"){
				$(".mousetrap:eq("+prev_index+")").attr("size","10");
			}else{
				$("select").attr("size","0");
			}
		});
	});
</script>
<!--    <form id="form1" runat="server">
    <div class="smallDiv">
        <h2>Enter text and hit Enter to move to next text box</h2><br />
        <input type="text" id="email" class="mousetrap"><br>
        <input type='text' ID="tb1" class="mousetrap" /><br />
        <textarea name="deskripsi" id="deskripsi" class="span10 mousetrap"></textarea><br />
        <select name="satuan" id="satuan" class="span10 validate[required] text-input mousetrap" />
            <option value=''>--PILIH SATUAN--</option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
        </select><br />
        <input type='text' ID="tb4" runat="server" class="mousetrap" />
    </div>
    </form>-->
