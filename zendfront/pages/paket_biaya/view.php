<?php defined('mainload') or die('Restricted Access'); ?>
<section class="testimonial">
    <h3>Paket Biaya Pendaftaran</h3>
</section>

<style type="text/css">
.tbl_package{
	width:100%;
}
#responsive-example-table th, #tbl_kelebihan th{
	padding:5px;
	font-size:11px;
	font-family:Century Gothic;
	background:#B1697E;
	color:#FFF;
}
#responsive-example-table td{
	vertical-align:top;
	padding:4px;
	font-size:11px;
	font-family:Century Gothic;
}
#tbl_kelebihan th {
	border-bottom:1px #FFFFFF solid;	
}
#tbl_kelebihan tr td{
	text-align:center;
	vertical-align:middle;
}

</style>

<div class="w-box">
    <div class="blocking" style="background:#FFF; text-align:justify" >
	
    	<p>Pemilihan jenis paket brand dibawah ini, menentukan, di wilayah komunitas mana brand usaha anda akan berada pada sistem sempoa. </p>
        <br />
    	<p>Contoh, brand anda memilih jenis paket brand Advance Brand, artinya posisi awal brand anda akan berada pada level komunitas yang di dalamnya terdapat brand-brand usaha yang memiliki tingkat jenis paket brand yang sama, dan begitu seterusnya.</p>
        <br />
    	<p>Namun, bukan berati sebuah brand dengan komunitas awal &quot;Advance Brand&quot; tidak dapat mencapai, tingkat komunitas Expert Brand, karena yang di butuhkan adalah, memiliki dan merekrut member loyal sendiri untuk brand anda sendiri.</p>
        <br />
        <p>
        	Karena semakin banyak member loyal yang anda miliki, mewakili simbol bahwa semakin besarlah brand usaha anda, karena memiliki pasar, yang bermanfaat bagi mitra bisnis nonkompetitor yang bekerjasama dengan anda.
        </p>
        <br />
        Dengan semakin banyak member loyal yang anda miliki, secara otomatis, sistem akan mengangkat nilai brand dan menggabungkan brand pada sistem komunitas diatasnya.
        
        <br />
    </div>
    <br />
  <div class="blocking" style="background:#FFF; text-align:justify" >
    <input type="hidden" id="proses_page" value="<?php echo $dirhost; ?>/zendfront/pages/paket_biaya/ajax/proses.php">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-striped large-only tbl_package" id="responsive-example-table" >
      <tr>
        <th width="23%"><p><strong>Jenis Paket Brand</strong></p></th>
        <th width="39%"><p><strong>Starter Pack </strong></p></th>
        <th width="19%"><p><strong>Setup Fee</strong></p></th>
        <th width="19%" align="center">&nbsp;</th>
      </tr>
      <tr>
        <td><p><strong>Starter Brand</strong></p>
        </td>
        <td><ol>
          <li>Ditampilkan Di  Sempoa.biz</li>
          <li>Discoin Berlogo Brand Bisnis Pribadi - For Android</li>
          <li>20 Free Akun Perdana Discoin</li>
        </ol></td>
        <td><p>Free</p></td>
        <td align="right" valign="top">
        	<form method="post" action="<?php echo $dirhost; ?>/website/mendaftar" >
            <button type="submit"  name="package" value="full_1" class="btn btn-beoro-1">Daftar</button>
            </form>
        </td>
      </tr>
      <tr>
        <td>
        	<p><strong>Advance Brand</strong></p>
        </td>
        <td><ol>
          <li>Ditampilkan Di  Sempoa.biz</li>
          <li>Discoin Berlogo Brand Bisnis Pribadi - For Android</li>
          <li>20 Free Akun Perdana Discoin</li>
          <li>50 Akun Perdana Discoin</li>
        </ol></td>
        <td><p>Rp. 1.500.000,-</p></td>
        <td align="right" valign="top">
        	
            <form method="post" action="<?php echo $dirhost; ?>/website/mendaftar" >
                <button type="submit"  name="package" value="full_2" class="btn btn-beoro-1">Daftar</button>
            </form>
        </td>
      </tr>
      <tr>
        <td><p><strong>Profesional Brand</strong></p></td>
        <td><ol>
          <li>Ditampilkan Di  Sempoa.biz</li>
          <li>Discoin Berlogo Brand Bisnis Pribadi - For Android</li>
          <li>20 Free Akun Perdana Discoin</li>
          <li>100 Akun Perdana Discoin</li>
        </ol></td>
        <td><p>Rp. 3.000.000,-</p></td>
        <td align="right" valign="top">
        
            <form method="post" action="<?php echo $dirhost; ?>/website/mendaftar" >
                <button type="submit"  name="package" value="full_3" class="btn btn-beoro-1">Daftar</button>
            </form>
        </td>
      </tr>
      <!--<tr>
        <td><p><strong>Expert Brand</strong></p></td>
        <td><ol>
          <li>Ditampilkan Di  Sempoa.biz</li>
          <li>Personal Discoin For Android</li>
          <li>200 Akun Perdana Discoin</li>
          <li>10 Mini Banner / 2    X-Banner</li>
          <li>2 Minggu Training + CD    Tutorial</li>
        </ol></td>
        <td><p>Rp. 6.000.000,-</p></td>
        <td align="right" valign="top">
        
            <form method="post" action="<?php echo $dirhost; ?>/website/mendaftar" >
                <button type="submit"  name="package" value="full_4" class="btn btn-beoro-1">Daftar</button>
            </form>
        </td>
      </tr>
      <tr>
        <td><p><strong>Corporate Brand</strong></p></td>
        <td><ol>
          <li>Ditampilkan Di  Sempoa.biz</li>
          <li>Personal Discoin For Android</li>
          <li>Personal Discoin For iOS</li>
          <li>300 Akun Perdana Discoin</li>
          <li>10 Mini Banner / 2    X-Banner</li>
          <li>3 Minggu Training + CD    Tutorial</li>
        </ol></td>
        <td><p>Rp.9.000.000,-</p></td>
        <td align="right" valign="top">
        
            <form method="post" action="<?php echo $dirhost; ?>/website/mendaftar" >
                <button type="submit"  name="package" value="full_5" class="btn btn-beoro-1">Daftar</button>
            </form>
        </td>
      </tr>
      <tr>
        <td><p><strong>Enterprise Brand</strong></p></td>
        <td><ol>
          <li>Ditampilkan Di  Sempoa.biz</li>
          <li>Personal Discoin For Android</li>
          <li>Personal Discoin For iOS</li>
          <li>500 Akun Perdana Discoin</li>
          <li>10 Mini Banner / 2    X-Banner</li>
          <li>1 Bulan Training + CD    Tutorial</li>
        </ol></td>
        <td><p>Rp.15.000.000,-</p></td>
        <td align="right" valign="top">
        
            <form method="post" action="<?php echo $dirhost; ?>/website/mendaftar" >
                <button type="submit"  name="package" value="full_6" class="btn btn-beoro-1">Daftar</button>
            </form>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align="right" valign="top">
                Atau <button type="button"  id="demo" value="demo" class="btn btn-beoro-2">Coba Versi Demo</button>
        </td>
      </tr>-->
    </table>
    <br />
            
	</div>
    <br /><br />
  <div class="blocking" style="background:#FFF; text-align:justify" >
    <h2>Pertanyaan Seputar Pendaftaran Merchant: </h2>
    <ol>
        <li><a href="#faq1">Apa keuntungan bagi saya, dengan memiliki aplikasi Discoin brand bisnis saya sendiri ?</a> </li>
        <li><a href="#faq2">Apa yang saya dapatkan jika saya memilih paket Starter Brand?</a></li> 
        <li><a href="#faq3">Bagaimana metode pembayaran perusahaan / brand saya kepada sempoa?</a></li>
        <li><a herf="#faq4">Bagaimana Jika Akun COIN member saya sudah habis?</a></li>
        <li><a href="#faq5">Apakah saya bisa membatalkan dan mengambil kembali deposit pencetakan akun discoin brand bisnis saya ?</a></li>
        <li><a href="#faq6">Bagaimana Jika member pertama saya, melakukan perpanjangan keanggotaan Discoin brand saya?</a></li> 
        <li><a href="#faq7">Apa tanggung jawab sempoa kepada brand saya, jika member saya, kecewa dengan informasi diskon yang salah dari brand bisnis</a></li> 
        <li><a href="#faq8">Bagaimana jika terdapat kompetitor saya di komunitas bisnis saya ?</a></li> 
        <li><a href="#faq9">Apa yang sempoa berikan setelah saya mendaftar ?</a></li>
    </ol>
    <br /><br />
    <hr />
    <br /><br />
        <ol>
            <li><a name="faq1"></a>
            	<b>Apa keuntungan bagi saya, dengan memiliki aplikasi Discoin brand bisnis saya sendiri ? </b>
                <br />
                <ol  style="list-style:lower-alpha">
                	<li>
                    	<b>Secara Materi</b><br />
                    	Anda bisa menjual aplikasi Discoin anda kepada member anda, dengan harga yang anda tentukan sendiri<br /><br />
                        Contoh : harga 1 akun COIN adalah <?php echo money("Rp.",$discoin_fee); ?> / Tahun, tentu anda bisa, menjual dengan harga <?php $self_price = $discoin_fee * 2;  echo money("Rp.",$self_price); ?> / Tahun atau lebih, artinya anda bisa memiliki keuntungan sebesar <?php $profit = $self_price - $discoin_fee; echo money("Rp.",$profit); ?> <br />
                        
                        Tentunya, harga harus disesuaikan dengan layanan anda kepada member loyal anda, yang tidak dimiliki oleh sempoa dalam pelayanan standarnya untuk member loyal anda.
                           
                    </li>
                    <li>
                    	<b>Secara Manfaat</b><br />
                        <ul>
                        	<li>Dengan kerjasama komunitas Sempoa, layanan aplikasi Discoin brand bisnis anda, menjadi kuat, dimata member loyal anda, termasuk brand bisnis anda sendiri yang berimbas pada meningkatnya kekuatan pesan promosi anda, untuk pembelian produk sebagai syarat kepemiliki aplikasi Discoin brand bisnis anda.</li>
                            <li>Brand anda diangkat sebagai aplikasi yang dapat menjadi marketplace online diskon, bagi member loyal anda, jika anda membayangkan sebuah website mall online, atau website daily deals.<br />
                            Bagi member loyal anda,Aplikasi Discoin anda dapat menjadi gabungan keduanya, tergantung bagai mana anda berkomunitas dan bersilaturahmi dengan brand-brand bisnis lainnya yang terdapat di website sempoa.biz</li>
                            <li>Brand anda selalu terlihat setiap hari, di telephone genggam, dan dapat di tularkan oleh member anda, sebagai alat demontrasi pemasaran mulut ke mulut oleh member anda, untuk merekomendasikan brand bisnis anda, kepada lingkungannya.</li>
                        </ul>
                    </li>
                </ol>
            	<br />
            </li>
        	<li><a name="faq2"></a>
            	<b>Apa yang saya dapatkan jika saya memilih paket Starter Brand? </b>
            	<ol style="list-style:lower-alpha">
                	<li>
                    	Brand dan produk anda ditampilkan di salah satu komunitas bisnis, di sempoa.biz, dengan jangka waktu 1 bulan.
                    </li>
                    <li>
                    	Brand dan produk anda ditampilkan di website www.sempoa.biz, selama anda tidak menghapus keanggotaan anda.
                    </li>
            	</ol>
                Namun dengan syarat, anda harus memberikan Diskon belanja minimal 30% untuk sebuah produk, beberapa produk atau semua produk anda, kepada COIN member yang dapat anda validasi menggunakan akun sempoa anda.
                <br /><br />
                Dan Dimana kerjasama diskon ini tentunya akan di ikat oleh sempoa.biz untuk menjamin kebenaran informasi kepada seluruh member pemiliki COIN tervalidasi.
                <br /><br />
                Namun, brand anda tidak memiliki aplikasi Discoin, untuk melayani member loyal anda.
                <br /><br />
            </li>
            <li><a name="faq3"></a>
            	<b>Bagaimana metode pembayaran perusahaan / brand saya kepada sempoa?</b>
                <br />
                Anda hanya perlu mendepositkan biaya pencetakan akun Discoin member anda,yang akan di potong sebesar <?php echo money("Rp.",$discoin_fee); ?>, di setiap anda mengaktifkan aplikasi Discon brand usaha anda untuk member anda. 
                <br /><br />
            </li>
            <li><a name="faq4"></a>
            <b>Bagaimana Jika Akun COIN member saya sudah habis?</b><br />
                Anda dapat menghubungi sempoa melalui akun sempoa.biz anda, untuk mengajukan pencetakan akun Discoin member anda, sejumlah yang anda tentukan sendiri, dimana setiap akun aplikasi Discoin yang di cetak, bernilai <?php echo money("Rp.",$discoin_fee); ?> / COIN<br /><br />
                Contoh : Jika anda ingin mencetak 5 COIN, maka anda akan di bebankan biaya deposit sebesar <?php 
				$total = $discoin_fee * 5; ?> <?php echo money("Rp.",$total); ?>, Dan begitu seterusnya.
        		<br /><br />        
        	</li>
            <li><a name="faq5"></a>
            <b>Apakah saya bisa membatalkan dan mengambil kembali deposit pencetakan akun discoin brand bisnis saya ?</b><br />
            Sangat bisa, namun deposit yang bisa diambil kembali, jika deposit itu terjadi setelah pembayaran deposit pertama.<br />
            Dan dikembalikan sesuai, dengan jumlah akun discoin yang belum diaktifkan.<br /><br />
            Contoh : setelah deposit pertama, seluruh akun Discoin member anda sudah habis, lalu anda melakukan deposit kedua sebesar Rp.300.000,- untuk deposit cetak akun discoin sebanyak 10 Akun<br /><br />
            
            Lalu anda, ingin membatalkan deposit tersebut, dan mengambil kembali Rp.300.000,- tersebut, untuk dikembalikan ke rekening bisnis anda.<br />
            
            Anda bisa melakukan permintaan <i>return deposit</i> di akun sempoa.biz anda, yang caranya akan di edukasi kepada anda saat masa training atau setelahnya.
          <br />
          Jika belum ada satupun COIN yang aktif, maka Rp.300.000,- akan dikembalikan<br />
          Namun jika terdapat 5 Akun Discoin yang sudah diaktifkan oleh member anda melalui anda, maka yang dikembalikan adalah sebesar Rp.150.000,-  
            
            <br /><br />
            Pasal pengembalian ini akan diatur di surat kerjasama sempoa dan brand bisnis anda.   
            <br /><br />
        	</li>
            <li><a name="faq6"></a>
            	<b>Bagaimana Jika member pertama saya, melakukan perpanjangan keanggotaan Discoin brand saya?</b>
                <br />
                Jika anda masih memiliki beberapa akun Discoin yang tersisa, anda dapat menggunakan akun COIN yang tersisa tersebut, untuk diberikan kepada member anda yang memperpanjang keanggotaannya, sebagai kode aktivasi aplikasi Discoinnya, tanpa merubah Kode Identitas / COIN nya yang lama.
                <br /><br />
           	</li>
            <li><a name="faq7"></a>
            <b>Apa tanggung jawab sempoa kepada brand saya, jika member saya, kecewa dengan informasi diskon yang salah dari brand bisnis lainnya?</b><br />
                Sempoa sebagai penyedia layanan, akan melakukan investigasi, dari data dan informasi yang di cocokan dengan kebenaran dilapangan, menggunakan tenaga <i>mistery shopper</i> sempoa, yang diutus untuk membuktikan, kebenaran informasi dan aksi, kepada brand bisnis yang bersangkutan.<br /><br />
                Jika terbukti brand bisnis yang bersangkutan, melakukan penyampaian informasi yang salah, brand tersebut akan di nonaktifkan dari komunitas dan dari sempoa.biz, dengan tanpa peringatan sebelumnya, namun member mereka masih akan tetap dapat mengakses brand tersebut, namun member mereka tidak dapat terhubung dengan komunitas probadi maupun komunitas umum yang lebih luas, hingga batas waktu yang ditentukan, yang pemberitahuannya akan diberitakan melalui email<br /><br />
          
          Catatan : 
          <ul>
          	<li>Komunitas Pribadi adalah, komunitas yang tersusun dari kerjasama anda sendiri bersama brand bisnis lain yang anda tentukan sendiri, di website sempoa.biz<li>
            <li>Komunitas Umum, adalah, komunitas yang di bangun sempoa, untuk membantu brand-brand yang belum memiliki mitra bisnis.</li>
         </ul>
                
        		<br />      
        	</li>
            <li><a name="faq8"></a>
            	<b>Bagaimana jika terdapat kompetitor saya di komunitas bisnis saya ?</b>
                <br />
                Anda dapat menutup akses layanan discoin member anda, kepada brand tersebut melalui, akun sempoa.biz anda, agar member anda tidak dapat melihat brand kompetitor anda dan agar Kode Identitas Member anda, tidak dapat di validasi oleh brand kompetitor anda.<br />  <br />  
            </li>
            <li><a name="faq9"></a>
            	<b>Apa yang sempoa berikan setelah saya mendaftar ?</b>
                <br />
                Setelah anda mengisi formulir pendaftaran, kurang dari 48jam, sempoa akan mengirimkan starterpack yang berisi.
                <ol>
                	<li>Mengirimkan informasi akun sempoa admin brand anda </li>
                	<li>Daftar akun perdana Discoin member, melalui email (Free Vesion) berupa kartu (Full Version)</li>
                    <li>Mengirimkan alamat download aplikasi Discoin brand bisnis anda</li>
                    <li>Melakukan training admin, dalam pengelolaan aplikasi Discoin (Full Version dan Saat ini khusus daerah bandung), atau mengirimkan CD tutorial,</li>
                    <li>Dapat bertanya ke pada Customer Service atau Technical Support Kami di email support@sempoa.biz selama 24 Jam.</li>
                </ol>
            </li>
        </ol>
        
        <br /><br />
        Untuk pertanyaan lebih detail anda bisa menghubungi, email : <a href="mailto:support@sempoa.biz">support@sempoa.biz</a> , 
        Twitter : <a herf="www.twitter.com/SempoaTech" target="_blank">@SempoaTech</a> , Facebook : <a href="https://www.facebook.com/profile.php?id=100008211553917" target="_blank">Sempoa Teknologi Proyek </a>
   </div>
   
   <br />

</div>