  <link href="stacktable.css" rel="stylesheet" />


  <table id="responsive-example-table" class="large-only" cellspacing="0">
    <tbody>
      <tr align="left">
        <th width="30%">Stuff</th>
        <th width="12%">Rate</th>
        <th width="12%">Amount</th>
        <th width="12%">Points</th>
        <th width="12%">Number</th>
        <th width="18%">Type</th>
        <th width="12%">Name</th>
      </tr>
      <tr>
        <td>Something</td>
        <td>3.375%</td>
        <td>$123.12</td>
        <td>1.125</td>
        <td>4,000</td>
        <td>Potato</td>
        <td>Paul</td>
      </tr>
      <tr>
        <td>Something Else</td>
        <td>2.750%</td>
        <td>$345.23</td>
        <td>5</td>
        <td>180</td>
        <td>Spaceship</td>
        <td>Skippy</td>
      </tr>
    </tbody>
  </table>




 <table id="card-table" class="table">
  <thead>
  <tr>
     <th width="30%">Name</th>
     <th width="30%">Phone</th>
     <th width="30%">Info</th>
     <th width="10%">Actions</th>
  </tr>
 </thead> 
 <tbody>
  <tr>
    <td>Test</td>
    <td>555-555-5555</td>
    <td> I am a test</td>
    <td><a href="#">Edit</a></td>
  </tr>
  <tr>
    <td>Greg</td>
    <td>555-555-5555</td>
    <td>This is an example</td>
    <td><a href="#">Edit</a></td>
  </tr>
  <tr>
    <td>John</td>
    <td>444-444-4444</td>
    <td>Tables are cool</td>
    <td><a href="#">Edit</a></td>
  </tr>
 </tbody>
 <tfoot>
  <tr>
  	<td colspan="4"><a href="#">View all</a></td>
  </tr>
 </tfoot>
</table> 



<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.8.1.min.js"><\/script>')</script> </script>

<script src="stacktable.js" type="text/javascript"></script>

<script>
  $('#responsive-example-table').stacktable({myClass:'stacktable small-only'});
  $('#card-table').cardtable({myClass:'stacktable small-only' });
</script>
