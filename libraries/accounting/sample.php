
	<script src="accounting.js"></script>
	<script src="demo-resources/js/libs/jquery.min.js"></script>
	<script src="demo-resources/js/prettify.js"></script>

	<script type="text/javascript">
		// demo thangs:
		jQuery(document).ready(function($) {
			var $columnValues = $('#demo-column').find('input'),
			    $columnOutputs2 = $('#demo-column').find('.output2');

			$columnValues.bind('keydown keyup keypress focus blur paste', function() {
			 	var list = $.map( $columnValues, function(each) { return $(each).val(); } ),
			 		formatted = accounting.formatColumn(list, {
			 			format : "%s %v"
			 		}),
			 		formatted2 = accounting.formatColumn(list, {
			 			symbol : "Rp.",
						thousand:'.',
						decimal:','
			 		});
			 	$.each($columnOutputs2, function(i, each) {
			 		$(each).text(formatted2[i]);
			 	});
			});

		});
	function test(){
		rp = $("#rp").val();
		res = accounting.formatMoney(rp,"Rp",2,".",","); // €4.999,99	
		$("#output").html(res);
	}
	</script>
    <input type="text" id="rp" value="1000000" maxlength="20" onkeyup="test()"/>
    <div id="output">Rp.2.000.000,00</div>
    <table id="demo-column">
        <tbody>
            <tr>
                <td><input type="text" value="1000000" maxlength="20" /></td>
                <td class="output2">Rp.1.000.000,00</td>
            </tr>
        </tbody>
    </table>
