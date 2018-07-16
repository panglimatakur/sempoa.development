<?php
session_start(); 
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_SESSION['uidkey'])) {
	if(!defined('mainload')) { define('mainload','SEMPOA',true); }
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/functions.php");
	include_once("../../../../includes/declarations.php");
	
	$id_client_form		= isset($_REQUEST['id_client_form']) ? $_REQUEST['id_client_form'] 	:"";
	$query_client 		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$id_client_form."' ORDER BY CLIENT_NAME");
	$dt_client			= $db->fetchNextObject($query_client);
	
	$q_customer 		= $db->query("SELECT COIN_NUMBER FROM ".$tpref."customers WHERE ID_CLIENT='".$id_client_form."' AND CUSTOMER_STATUS = '3' ORDER BY ID_CUSTOMER ASC LIMIT 0,5");
	
	$q_admin			= $db->query("SELECT USER_USERNAME,USER_PASS FROM system_users_client WHERE ID_CLIENT='".$id_client_form."' AND ID_CLIENT_USER_LEVEL = '1' ORDER BY ID_USER ASC");
	$dt_admin			= $db->fetchNextObject($q_admin);
	
	$q_titanium		= $db->query("SELECT COIN_NUMBER FROM ".$tpref."customers WHERE ID_CLIENT='1' AND (CUSTOMER_STATUS = '0' OR CUSTOMER_STATUS = '') ORDER BY ID_CUSTOMER ASC LIMIT 0,3");
	
?>
<style type="text/css">
.boxit{
	font-family:Verdana, Geneva, sans-serif; 
	text-align:center; 
}
.boxit table{
	font-size:12px;	
}
body{
	border:1px solid #666;
	border-radius:4px;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	width:100%;
}
.boxit table td{
	padding:5px;	
}
.header_print{
	border-bottom:2px solid #4A204B;	
}
</style>
<body>
    <div id="print_wrapper">
        <div class="boxit" >
                <table width="90%"  align="center" class='main-table'>
                    <tbody>
                          <tr>
                            <td colspan="2">
                                  <table width="100%"  align="center" class="header_print">  
                                    <tr>
                                        <td>
                                            <img src="<?php echo $dirhost; ?>/files/images/clip_image002.gif">
                                        </td>
                                        <td align="right">
                                            <b>Sempoa <span style="color:#8D2966">Discoin</span> Community</b>
                                            <br>
                                            PT. Sempoa Tech Indonesia<br>
                                            Komplek Telkom Cibeureum<br>
                                            Jl. Palapa No. 27 Kebon Kopi – Cimahi 40535 - INDONESIA<br>
                                            support@<?php echo $website_name; ?> | +62 22 6019739 <br>
            
                                        </td>
                                    </tr>
                                  </table>
                            </td>
                          </tr>
                          <tr>
                            <td>
								<b>Untuk : </b> 
                                <b style="color:#C00"><?php echo $dt_client->CLIENT_NAME; ?></b>
                            </td>
                            <td style="text-align:right">
                               <b>Tanggal : <?php echo date("d-m-Y"); ?></b>
                            </td>
                        </tr>
                          <tr>
                            <td colspan="2">
                            <b>Dibawah ini adalah daftar akun perdana dan informasi URL penting</b>
                            <br />
                            <br />
                            <table width="100%">
                              <tr style="font-weight:bold">
                                <td width="3%" align="center">I</td>
                                <td width="97%">Link URL Aplikasi DISCOIN</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                                	<span style="color:#F60; font-weight:bold">https://sempoa.community/coin/<?php echo $dt_client->CLIENT_APP; ?></span><br />
                                	( NB: Di buka melalui browser telepon genggam Android pelanggan)
                                </td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">II</td>
                                <td>Daftar 5 Akun COIN Aktif Merchant</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                               	  <table width="100%">
                                <?php while($dt_customer = $db->fetchNextObject($q_customer)){?>
                                	  <tr>
                                	    <td width="9%"><strong>USERNAME</strong></td>
                                	    <td width="11%"><?php echo $dt_customer->COIN_NUMBER; ?></td>
                                	    <td width="9%"><strong>PASSWORD</strong></td>
                                	    <td width="71%"><?php echo $dt_customer->COIN_NUMBER; ?></td>
                              	    </tr>
                           	    <?php } ?>
                                </table>
                                
                                </td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">III</td>
                                <td>Informasi Akun Website <?php echo $website_name; ?> (Control Panel Merchant).</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                                    <span style="color:#F60; font-weight:bold"><?php echo $dirhost; ?></span>
                                    <br />
                                    <span style="color:#F60">
                                    	( NB: Direkomendasikan di buka melalui browser Google Chrome, di Komputer atau Notebook)
                                    </span>
                                    <br />
                                    Dengan informasi akun Super Administrator awal Control Panel, yang dapat di ubah sewaktu-waktu oleh pengguna Control Panel Merchant.<br />
                                    <table width="100%">
                                      <tr>
                                        <td width="10%"><strong>USERNAME</strong></td>
                                        <td width="90%"><?php echo $dt_admin->USER_USERNAME; ?></td>
                                      </tr>
                                      <tr>
                                        <td><strong>PASSWORD</strong></td>
                                        <td><?php echo $dt_admin->USER_PASS; ?></td>
                                      </tr>
                                </table></td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">IV</td>
                                <td>Daftar Free Akun COIN Titanium ( <?php echo $dirhost; ?>/coin/titanium )</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td>
                               	  <table width="100%">
                                <?php while($dt_titanium = $db->fetchNextObject($q_titanium)){?>
                                	  <tr>
                                	    <td width="9%"><strong>USERNAME</strong></td>
                                	    <td width="11%"><?php echo $dt_titanium->COIN_NUMBER; ?></td>
                                	    <td width="9%"><strong>PASSWORD</strong></td>
                                	    <td width="71%"><?php echo $dt_titanium->COIN_NUMBER; ?></td>
                              	    </tr>
                           	    <?php } ?>
                                </table>
                                
                                </td>
                              </tr>
                              <tr style="font-weight:bold">
                                <td align="center">V</td>
                                <td>Link URL Video Tutorial dan Cara Pemakaian Control Panel Merchant.</td>
                              </tr>
                              <tr>
                                <td align="center">&nbsp;</td>
                                <td><span style="color:#F60; font-weight:bold">https://sempoa.community/files/discoin.rar</span></td>
                              </tr>
                            </table></td>
                          </tr>
                    </tbody>
                </table>
        </div>
    </div>
</body>


<script language="javascript">
	window.print();
</script>
<?php
}
?>
