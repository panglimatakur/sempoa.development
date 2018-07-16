<?php defined('mainload') or die('Restricted Access'); ?>
<div style='border:1px solid #CCC; width:100%; height:60%; max-height:100px; overflow:scroll;' class='lsingle'>
    <table width="100%" id="rt2" class="rt cf">
        <thead class="cf">
          <tr>
            <th width="13%">Harga Beli</th>
            <th width="13%">Harga Jual</th>
            <th width="11%">Jumlah</th>
            <th width="13%">Total</th>
            <th width="13%">Status</th>
            <th width="13%">No PO</th>
            <th width="13%">Bayar</th>
            <th width="13%">Sisa</th>
          </tr>
        </thead>
        <tbody>
              <tr>
                <td><?php echo money("Rp.",$dt_buy->BUY_PRICE); ?>&nbsp;</td>
                <td><?php if(!empty($dt_buy->SALE_PRICE)){ echo money("Rp.",$dt_buy->SALE_PRICE); } ?>&nbsp;</td>
                <td><?php echo $dt_buy->QUANTITY; ?> <?php echo $unit; ?>&nbsp;</td>
                <td><?php echo money("Rp.",$total_buy); ?>&nbsp;</td>
                <td><?php echo @$paid_status; ?>&nbsp;</td>
                <td><?php echo $dt_buy->PO_NUMBER; ?>&nbsp;</td>
                <td><?php if(!empty($dt_buy->PAID)){ echo money("Rp.",$dt_buy->PAID);}else{ echo "0"; }  ?></td>
                <td><?php if(!empty($dt_buy->DEBT)){ echo money("Rp.",$dt_buy->DEBT); }else{ echo "0"; } ?></td>
              </tr>
        </tbody>
    </table>
</div>