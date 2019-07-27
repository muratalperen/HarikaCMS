<!DOCTYPE html>
<html lang="tr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Kurulum</title>
    <style media="screen">
		*{
			font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
		}
		body{
			background-color: #d2d6de;
			color: #333;
			height: 100%;
			margin: 0px;
		}
		form{
			width: 450px;
			margin: 7% auto;
			padding: 30px 10px;
			margin-top: 100px;
			background-color: #fff;
		}
		form > h1{text-align: center;}
		form input{
			display: block;
			width: 90%;
			height: 34px;
			padding: 1px 12px;
			font-size: 14px;
			line-height: 1.42857143;
			color: #555;
			background-color: #fff;
			background-image: none;
			border: 1px solid #ccc;
			margin: auto;
		}
		#watermark{
			display: block;
			text-align: center;
			margin:20px;
			color: #555;
		}
		#watermark > a{
			color: #555;
		}
		@media only screen and (max-width:768px){
			form{
				width: 90%;
			}
		}

    </style>
  </head>
  <body>

    <form method="post">
			<h1>Sitenizi Kurun</h1>

      <table>
        <tr><td><h2>Site bilgileri</h2></td><hr></tr>
        <tr><td><label for="base_url">Sitenizin adresini tam yazın</label></td><td><input type="text" name="base_url" id="base_url" placeholder="https://ornek.com/" required><br></td></tr>
        <tr><td><label for="site_ad">Sitenizin adını yazın</label></td><td><input type="text" name="site_ad" id="site_ad" placeholder="" required><br></td></tr>
        <tr><td><label for="hakkinda">Siteniz için kısa bir hakkında yazısı yazın:</label></td><td><input type="text" name="hakkinda" id="hakkinda" placeholder="Yaklaşık 20 kelimelik"><br></td></tr>
        <tr><td><label for="mail">Siteniz için iletişim mail adresi:</label></td><td><input type="email" name="mail" id="mail" placeholder="iletisim@mail.com"><br></td></tr>
        <tr><td><label for="twitter">(Varsa) sitenizin twitter hesabının adresi:</label></td><td><input type="url" name="twitter" id="twitter"><br></td></tr>
        <tr><td><label for="facebook">(Varsa) sitenizin facebook hesabının adresi:</label></td><td><input type="url" name="facebook" id="facebook"><br></td></tr>
        <tr><td><label for="instagram">(Varsa) sitenizin instagram hesabının adresi:</label></td><td><input type="url" name="instagram" id="instagram"><br></td></tr>

        <tr><td><h2>Veritabanı Bilgileri</h2></td></tr>
        <tr><td><label for="hostname">Sunucu adı:</label></td><td><input type="text" name="hostname" id="hostname" required><br></td></tr>
        <tr><td><label for="username">Kullanıcı adı:</label></td><td><input type="text" name="username" id="username"><br></td></tr>
        <tr><td><label for="password">Şifre:</label></td><td><input type="password" name="password" id="password"><br></td></tr>
        <tr><td><label for="database">Veritabanı adı:</label></td><td><input type="text" name="database" id="database" required><br></td></tr>

				<tr><td><h2>Yönetici Bilgileri</h2></td></tr>
				<tr>
					<td>Yeni yönetici oluşturun</td>
				</tr>
        <tr><td><label for="hostname">Kullanıcı adı:</label></td><td><input type="text" name="kullanici_adi" id="kullanici_adi" required><br></td></tr>
        <tr><td><label for="username">Mail adresi:</label></td><td><input type="text" name="kullanici_mail" id="kullanici_mail" required><br></td></tr>
        <tr><td><label for="password">Şifre:</label></td><td><input type="password" name="kullanici_sifre" id="kullanici_sifre" required onchange="sifreKontrol(this.value);"><br></td></tr>
      </table>

      <h2>Kategoriler</h2>
      <div id="kategTextbox"></div>
      <input type="button" onclick="ekleK();" value="Yeni kategori ekle"><br><br>

      <input type="submit" value="Yükle">

			<h4 id="watermark"><a href="https:/github.com/muratalperen/HarikaCMS" target="_blank">HarikaCMS</a> Kullanıldı</h4>
    </form>

    <script type="text/javascript">
      var ekleNum = 0;
      function ekleK() {
        ekleNum++;
        document.getElementById('kategTextbox').innerHTML += '<label>Kategori Adı:</label> <input type="text" name="k' + ekleNum + '" required>\
        <label>Alt Kategorilerini aralarına virgül koyarak yazın</label>\
        <input type="text" name="ak' + ekleNum + '" placeholder="Alt Kategori 1, Alt Kategori 2, Alt Kategori 3" required><br>';
      }
      ekleK();

			function sifreKontrol(sifre) {
				if (sifre == '1234' || sifre == '1234567890' || sifre == '123456789' || sifre == 'asdf' || sifre == 'toor') {
					alert('Böyle şifre mi konur?');
				} else if (sifre.length < 8) {
					alert('Şifreniz fazla kısa olmadı mı?');
				}
			}
    </script>

  </body>
</html>
