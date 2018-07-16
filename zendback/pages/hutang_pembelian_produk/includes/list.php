<?php defined('mainload') or die('Restricted Access'); ?> 
<?php $total_beli = $dt_buy->SUMMARY; ?>
<div> 
<table width="100%" class="rt cf">
    <thead>
         <tr>
           <th width="17%">Total </th>
           <th width="14%">Status</th>
           <?php if(!empty($dt_buy->PO_NUMBER)){?><th width="20%">No PO</th><?php } ?>
           <th width="20%">Bayar</th>
           <th width="29%">Sisa</th>
         </tr>
    </thead>
    <tbody>
         <tr>
           <td><?php echo money("Rp.",$total_beli); ?></td>
           <td><?php echo @$paid_status; ?></td>
           <?php if(!empty($dt_buy->PO_NUMBER)){?><td><?php if(!empty($dt_buy->PO_NUMBER)){ echo $dt_buy->PO_NUMBER; }  ?></td><?php } ?>
           <td><?php if(!empty($new_bayar)){ echo money("Rp.",$new_bayar);}else{ echo "0"; }  ?></td>
           <td><?php if(!empty($sisa)){ echo money("Rp.",$sisa); }else{ echo "0"; }?></td>
         </tr>
    </tbody>
</table>
</div>
<div style='border:1px solid #e8dbe3; height:auto; max-height:150px; overflow:scroll;'>
	<?php
      $q_hutang = $db->query("SELECT * FROM ".$tpref."cash_debt_credit WHERE ID_CASH_FLOW='".$dt_buy->ID_CASH_FLOW."' AND PLUS_MINUS = '-' ORDER BY ORDINAL ASC");
      $pembayaran_2 = 0;
    ?>
    <table width="100%" class="rt cf">
    	<thead>
        	<tr>
                <th>&nbsp;</th>
                <th><b>JML Bayar</b></th>
                <th><b>TGL Bayar</b></th>
                <th>Keterangan</th>
        	</tr>
        </thead>
        <tbody>
    <?php
      while($dt_hutang = $db->fetchNextObject($q_hutang)){
    ?>
          <tr>
            <td width="27%"><b>Pembayaran <?php echo $dt_hutang->ORDINAL; ?></b></td>
            <td width="23%"><?php echo money("Rp.",$dt_hutang->AMOUNT); ?></td>
            <td width="25%"><?php echo $dtime->date2indodate($dt_hutang->PAY_DATE); ?></td>
            <td width="25%"><?php echo $dt_hutang->NOTE; ?></td>
          </tr>
    <?php } ?>
    	</tbody>
    </table>
</div>
<div style="clear:both"></div>
