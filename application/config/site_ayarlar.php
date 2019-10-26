<?php
/**
 * Site Ayarlar
 *
 * Site'nin bilgilerini tutar
 */

defined('BASEPATH') OR exit('No direct script access allowed');


/*
| -------------------------------------------------------------------------
| Site Bilgileri
| -------------------------------------------------------------------------
| Site bilgileri bulunur (site adı, açıklaması, iletişim mail, facebook
| sayfası vs.)
*/
$config['site'] = (object) array(
	'ad'						=> 'Bir Site',
	'hakkinda'			=> 'Deneme website. Blog platformu',
	'iletisimMail'	=> 'iletisim@protonmail.com',
	'medya'					=> array(
		'twitter'		=> '',
		'facebook'	=> '',
		'instagram'	=> ''
	)
);


/*
| -------------------------------------------------------------------------
| Bildirim Oluşturulacaklar.
| -------------------------------------------------------------------------
| Belirtilen durumda bildirim oluşturulması isteniyorsa 1-10 arası bildirim
| önem derecesi alır. Bildirilmesin isteniyorsa 0 yazılır.
| Ayrıca 11 sadece YONETICI_UST, 12 sadece YONETICI_BAS görebileceği seviyelerdir
*/
$config['bildirim_seviyesi'] = array(
	'hata_yonetici_giris'	=> 2,
	'hata_404'						=> 3
);


/*
| -------------------------------------------------------------------------
| Güvenlik Kodu Uzunluğu
| -------------------------------------------------------------------------
| Yönetim paneli giriş ekranında, insan kontrolüne yarayan (captcha) resmin
| karakter uzunluğunu belirler. Uzun oldukça robotlardan daha koruyucu
| olmakla beraber, yöneticilerin giriş yapmasını zorlaştırır.
*/
$config['captcha_uzunluğu'] = 6;


/*
| -------------------------------------------------------------------------
| Girişte Ek Url Koruması.
| -------------------------------------------------------------------------
| Yönetici paneli çok kolay bulunabilir bir yerde olduğundan, bruteforce
| saldırıları oldukça sık olacaktır. Bunun için ek url koruması konabilir.
| Bu değer TRUE olduğunda doğrudan giriş ekranına girenler, ne yazarlarsa
| yazsınlar, hatalı giriş yazısını görürler. Sadece yöneticilere söylenecek
| özel algoritma belirlenir. admin/giris?code={algoritmanın sonucu} şeklinde
| giriş ekranına gelenler dikkate alınır.
*/
$config['giriste_ek_url_koruması'] = FALSE;
$config['giriste_ek_url_koruma_algoritması'] = date('d') + date('m'); // Gün ile ayın rakamsal değerlerinin toplamı


/*
| -------------------------------------------------------------------------
| Php Çalıştırılabilir
| -------------------------------------------------------------------------
| Baş yönetici için php çalıştırma sayfası ekler. Açık olması, geliştiriciler için
| iyi olabilir. Ancak baş yöneticinin şifresinin ele geçirilmesi durumunda
| güvenlik sorunu oluşturabilir.
*/
$config['php_calistirilabilir'] = FALSE;


/*
| -------------------------------------------------------------------------
| İstatistik Tut
| -------------------------------------------------------------------------
| Ürünlere tıklanma sayısı, hangi saatlerde ziyaret edildiği, ziyaretçilerin
| nereden geldiği, kullandıkları işletim sistemleri ve tarayıcılar gibi
| verileri anonim olarak saklar. Bunun için gizlilik politikasına gerek yoktur.
| ancak site ziyaretçisi çok fazla olduğunda yavaşlamalara sebep olabilir. Veya
| başka bir istatistik sistemi kullanılıyorsa buna ihtiyaç olmayabilir.
*/
$config['istatistik_tut'] = TRUE;
