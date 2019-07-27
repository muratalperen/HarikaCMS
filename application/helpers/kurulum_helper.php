<?php

/**
 * Yönetici Düzey Adı
 *
 * Verilen yönetici seviyesinden, yöneticinin düzey adını string olarak verir
 *
 * @param string	Yöneticinin seviyesi
 *
 * @return	string
 */
function siteyiKur()
{
	// Girilmesi zorunlu post verilerini kontrol et
	foreach (['base_url', 'site_ad', 'hostname', 'database', 'kullanici_adi', 'kullanici_mail', 'kullanici_sifre', 'k1', 'ak1'] as $k) {
		if ( ! isset($_POST[$k])) return 'Zorunlu bilgilerinden biri veya birkaçı girilmemiş! Lütfen giriş bilgilerini girip tekrar deneyin.<br>';
	}

	// Site bağlantısının sonuna / koymayı unuttuysa ekle
  $_POST['base_url'] .= (substr($_POST['base_url'], -1) == '/') ? '' : '/' ;

	// Gönderdiği verilerle veritabanına bağlan
	try {
		$baglanti = new PDO('mysql:host=' . $_POST['hostname'] . ';dbname=' . $_POST['database'], $_POST['username'], $_POST['password']);
	} catch(PDOException $mesajpdo) {
		return 'Veritabanı bağlantısında bir sorun oluştu: ' . $mesajpdo->getMessage() . '<br>Kurulum iptal edildi. <a href="' . $_POST['base_url'] . 'kur.php">Kuruluma geri dön</a><br>';
  	exit();
	}

	// İşlem sonucu işlemin başında başarılıdır.
  $ret = TRUE;

	// Yüklenecek veritabanı dosyasını al
	if (is_null($sql = file_get_contents('gizli/DB.sql'))) {
		$ret .= 'Veritabanı dosyası (gizli/DB.sql) okunamadı. Sitenizin çalışabilmesi için veritabanı dosyasını manuel olarak yükleyin.<br>';
		$ret .= 'Veritabanını oluşturduktan sonra &quot;root@host&quot; mail adresi ve &quot;toor&quot; şifresi ile giriş yapıp bilgilerinizi güncelleyebilirsiniz.<br>';
	}
	else
	{
		// Veritabanına yükle
		$sqlYukle = $baglanti->prepare($sql);
		if ($sqlYukle->execute())
		{
			// DEBUG: Çalışmıyor: instert'i $sql'ye ekleyebilirim
			// Yöneticiyi tabloya ekle
			$adminYukle = $baglanti->prepare("UPDATE admin SET ad=?, mail=?, sifre=? WHERE id=1");
			if ( ! $adminYukle->execute(array($_POST['kullanici_adi'], $_POST['kullanici_mail'], md5($_POST['kullanici_sifre'])))) {
				$ret .= 'Yönetici bilgilerini güncelleme başarısız. &quot;root@host&quot; mail adresi ve &quot;toor&quot; şifresi ile giriş yapıp bilgilerinizi güncelleyebilirsiniz.<br>';
			}
		}
		else
		{
		 	$ret .= 'Veritabanı yüklemesi başarısız oldu. Sitenizin çalışabilmesi için veritabanı dosyasını (docs/DB.sql) manuel olarak yükleyin.<br>';
			$ret .= 'Veritabanını oluşturduktan sonra &quot;root@host&quot; mail adresi ve &quot;toor&quot; şifresi ile giriş yapıp bilgilerinizi güncelleyebilirsiniz.<br>';
		}

	}

	// Veritabanı bağlantısını kes
	$baglanti=null;

	// Yönetici resmi oluşturuluyor
	file_put_contents('rel/img/admin/1.jpg', file_get_contents('rel/img/site/kisi-tam.jpg'));

	/**
	* Değiştir
	*
	* Verilen dosyada, söylenen içeriği değiştirir.
	*
	* @param string	Değiştirilecek içerik
	* @param string	Değişimle gelecek içerik
	* @param string	İçeriğinin değişeceği dosya dizini
	*/
  function degistir($neyi, $neyle, $dosya)
  {
    $dosya = 'application/' . $dosya;
		// Tırnakların arasında ne olduğu önemli değil
		$neyi = str_replace("''", "'[^']*'", $neyi);

		if ($syfIcerik = file_get_contents($dosya)) {
			if ( ! file_put_contents($dosya, preg_replace("#($neyi)#", $neyle, $syfIcerik)))
	      $ret .= $dosya . ' dosyasının yazımında sorun çıktı. Bu işlevsel bir hataya neden olabilir. "' . $neyi . '" verisini el ile düzenleyin.<br>';
		} else {
			$ret .= $dosya . ' dosyasının okumasında sorun çıktı. Bu işlevsel bir hataya neden olabilir. "' . $neyi . '" verisini el ile düzenleyin.<br>';
		}
  }

	if ( ! function_exists('seflink')) require 'main_helper.php';

	/**
	* Remove Directory Recursive
	*
	* Dizini siler
	*
	* @param string	Silinecek dizin adı
	* @param boolean	Dizinin kendisi de silinecek mi
	*/
	function rmdir_recursive($dir, $deleteFolder = FALSE) {
		foreach(scandir($dir) as $file) {
			if ('.' !== $file && '..' !== $file)
			{
				if (is_dir("$dir/$file")) {
					rmdir_recursive("$dir/$file", $deleteFolder);
				}	else {
					if ( ! unlink("$dir/$file")) {
						global $ret;
						$ret .= "$dir/$file" . ' dosyası silinemedi. Lütfen el ile silin.<br>';
					}
				}
			}
		}
		if ($deleteFolder && is_dir("$dir/$file")) rmdir("$dir/$file");
	}

	// Array şeklinde kategori ve alt kategori yazıları
	$kateg = "";
	$Tkateg = "";
	$altkateg = "";
	$Taltkateg = "";
	$i = 1;

	// Kategori ve Alt kategoriler ayarlanır
	while (isset($_POST['k' . $i])) // Hala kategori olduğu sürece
	{
		// Kategoriyi ve sef kategoriyi 'kategori', şeklinde ekle
		$kateg .= '\'' . ucfirst($_POST['k' . $i]) . '\'' . ( isset($_POST['k' . ($i+1)]) ? ',' : '' );
		$Tkateg .= '\'' . seflink($_POST['k' . $i]) . '\'' . ( isset($_POST['k' . ($i+1)]) ? ',' : '' );

		// Alt kategoriyi ekle
		$altkateg .= "['" . str_replace(',', "','", $_POST['ak' . $i]) . "']" . ( isset($_POST['k' . ($i+1)]) ? "," : '' );

		// Alt kategoriyi seflink yapınca virgüller gitmesin diye array'a böl, seflink yap, string'e geri çevir

		// $altKategSef, alt kategori virgüller ile diziye çevrilmiş hali
		$altKategSef = explode(',', $_POST['ak' . $i]);

		for ($w=0; $w < count($altKategSef); $w++) // Her alt kategori için
		{
			// alt kategoriyi seflink yap ve tırnaklar arasına al
			$altKategSef[$w] = "'" . seflink($altKategSef[$w]) . "'";
		}
		// alt kategorileri virgüller ile birleştirip köşeli parantezi ekle
		$Taltkateg .= '[' . implode($altKategSef, ',') . ']' . ( isset($_POST['k' . ($i+1)]) ? "," : '' );

		$i++;
	}

	// Config verilerini yazar
  degistir("config\['base_url'\] = ''","config['base_url'] = '" . $_POST['base_url'] . "'",'config/config.php');
  degistir("'ad'						=> ''",		"'ad'						=> '" . $_POST['site_ad'] . "'",		'config/site_ayarlar.php');
  degistir("'hakkinda'			=> ''",		"'hakkinda'			=> '" . $_POST['hakkinda'] . "'",		'config/site_ayarlar.php');
  degistir("'iletisimMail'	=> ''",		"'iletisimMail'	=> '" . $_POST['mail'] . "'",				'config/site_ayarlar.php');
  degistir("'twitter'		=> ''",				"'twitter'		=> '" . $_POST['twitter'] . "'",			'config/site_ayarlar.php');
  degistir("'facebook'	=> ''",				"'facebook'	=> '" . $_POST['facebook'] . "'",				'config/site_ayarlar.php');
  degistir("'instagram'	=> ''",				"'instagram'	=> '" . $_POST['instagram'] . "'",		'config/site_ayarlar.php');
  degistir("'hostname' => ''",				"'hostname' => '" . $_POST['hostname'] . "'",				'config/database.php');
  degistir("'username' => ''",				"'username' => '" . $_POST['username'] . "'",				'config/database.php');
  degistir("'password' => ''",				"'password' => '" . $_POST['password'] . "'",				'config/database.php');
  degistir("'database' => ''",				"'database' => '" . $_POST['database'] . "'",				'config/database.php');

	// Kategori ve alt kategorileri kaydet
  degistir("\\\$kategoriSayisi = [^;]*;", '$kategoriSayisi = ' . ($i - 1) . ';', 'helpers/main_helper.php');
  degistir('foreach \(\[[^\]]*\]', "foreach ([$Tkateg]", 'config/routes.php');
  degistir("\\\$kateg = \[[^;]*;", "\$kateg = [$kateg];", 'helpers/main_helper.php');
  degistir("\\\$Tkateg = \[[^;]*;", "\$Tkateg = [$Tkateg];", 'helpers/main_helper.php');
  degistir("\\\$altkateg = \[[^;]*;", "\$altkateg = [$altkateg];", 'helpers/main_helper.php');
  degistir("\\\$Taltkateg = \[[^;]*;", "\$Taltkateg = [$Taltkateg];", 'helpers/main_helper.php');


	rmdir_recursive('docs', TRUE);

	// Varsa gereksiz dosyaları sil
	if (file_exists('.git'))
		rmdir_recursive('.git', TRUE);

	if (file_exists('README.md'))
		if ( ! unlink('README.md')) $ret .= '"README.md" dosyası silinemedi. Lütfen el ile silin.<br>';

	if (file_exists('LICENSE'))
		if ( ! unlink('LICENSE')) $ret .= '"LICENSE" dosyası silinemedi. Lütfen el ile silin.<br>';

	// IDEA: chmod ayarları yapılabilir

  return $ret;
}


function siteyiSifirla()
{

	// Dizinin içindeki dizinler hariç herşeyi silme fonksiyonu
	function rmdir_recursive($dir) {
		foreach(scandir($dir) as $file) {
			if ('.' === $file || '..' === $file) continue;
			if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
			else unlink("$dir/$file");
		}
	}

	// Dosya dizininin içinde site içeriği bulunur, siliniyor
	rmdir_recursive('dosya/');

	// Yönetici resimleri siliniyor
	rmdir_recursive('rel/img/admin/');

	$CI =& get_instance();

	// Tablolar boşaltılıyor
	foreach (['abone', 'admin', 'admin_bildirimler', 'admin_mesajlar', 'admin_yapilacaklar', 'urun', 'urun_join', 'yorum', 'istatistik'] as $key) {
		$CI->db->query('DROP TABLE ' . $key);
	}

	// Önbellek temizleniyor
	$CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	$CI->cache->clean();

	// Oturum kapatılıyor
	$CI->session->unset_userdata ('admin');
	touch('gizli/.yeni') OR die('Dosya oluşturulamadı. Sitenizi tekrar kurabilmek için "gizli" klasörüne ".yeni" adında dosya oluşturun.');

	redirect(base_url('kur.php'));
}
