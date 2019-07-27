<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class AdminB extends CI_Controller { //Admin Arkaplan İşleri Burada Yapılır

	function __construct() {
		parent::__construct();

		if ( ! isset($this->session->get_userdata ()['admin']))
		{
			// Yönetici değilse buraya gelemez
			boyle_birsey_olamaz('giris');
		}
		else
		{
			$this->load->model('admin_model');
			$this->load->helper('admin');

			$this->adminInfo = $this->admin_model->adminiAl($this->session->get_userdata ()['admin']);

			if (is_null($this->adminInfo))
			{
				// Belirttiği ID'de yönetici yoksa belirttiği ID silinip giriş sayfasına yönlendirilir
				$this->session->unset_userdata ('admin');
				boyle_birsey_olamaz('giris');

			}
		}
	}

	public function index()
	{
		boyle_birsey_olamaz();
	}

	public function yonetici($metod='',$param1='')
	{
		if ($metod == 'duzenle')
		{

			// Kendinden yüksek düzeyde bir yöneticiyi düzenlemeye çalışıyorsa
			if ( ! empty($this->admin_model->adminiAl($param1)) && $this->admin_model->adminiAl($param1)->duzey > $this->adminInfo->duzey)
			{
				bildirim_olustur(SONUC_UYARI, 'Sizden yüksek düzeyde birini düzenleyemezsiniz!', 'yonetici/yonet');
			}

			//Yeni yönetici bilgileri gönderildiyse
			if (isset($_POST['ad']))
			{

				// Kendi bilgilerini düzenlemeyecekse üst yönetici olması gerekir
				if ($param1 != $this->adminInfo->id)
				{
					girmek_icin_gerekli_seviye(YONETICI_UST);
				}

				// Düzenlediği kişinin düzeyi kendisininkinden yüksekse engelle
				if ($this->adminInfo->duzey < $_POST['duzey'])
				{
					bildirim_olustur(
						SONUC_UYARI,
						'Düzeyinizi arttıramaz, sizden yüksek düzeyli biri oluşturamazsınız!',
						'yonetici/duzenle/' . $param1
					);
				}
				else
				{
					// Hiçbir sorun yoksa yöneticiyi düzenle/ekle
					$eklemeSonucu = adminEkle();
					bildirim_olustur($eklemeSonucu[0], $eklemeSonucu[1], 'yonetici/yonet/');
				}

			}
			elseif (isset($_GET['sil'])) // Silme isteği gönderildiyse
			{

				girmek_icin_gerekli_seviye(YONETICI_UST);
				$silinmeSonucu = adminSil($param1, $_GET['urunler']);
				bildirim_olustur($silinmeSonucu[0], $silinmeSonucu[1], ($silinmeSonucu === SONUC_BASARI) ? '' : 'yonetici/yonet/');
			}

		}
		else
		{
			boyle_birsey_olamaz('admin');
		}

	}


	public function ayarlar()
	{
		girmek_icin_gerekli_seviye(YONETICI_MOD);
		if (isset($_GET['onbellek'])) // Önbelleği temizle
		{
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			$temizlemeSonucu = $this->cache->clean();
			bildirim_olustur(
				$temizlemeSonucu ? SONUC_BASARI : SONUC_HATA,
				$temizlemeSonucu ? 'Önbellek temizlendi' : 'Önbellek temizlenemedi.',
				'ayarlar'
			);

		}
		elseif (isset($_FILES['icon'])) // Icon'u güncelle
		{
			girmek_icin_gerekli_seviye(YONETICI_BAS);
			$this->load->library ('upload', array(
				'upload_path'		=> '.',
				'allowed_types' => 'ico',
				'overwrite'			=> TRUE,
				'file_name'			=> 'favicon.ico'
			));
			$yukleSonuc = $this->upload->do_upload('icon');
			bildirim_olustur(
				$yukleSonuc ? SONUC_BASARI : SONUC_HATA,
				$yukleSonuc ? 'Site ikonu güncellendi' : 'İkon güncellenemedi. Hata: ' . $this->upload->display_errors(),
				'ayarlar'
			);

		}
		elseif (isset($_GET['yedek'])) // Yedek oluştur
		{
			if ($_GET['yedek'] == 'yap') // Yedek oluştur
			{
				try {

					$zip = new ZipArchive();

					// Eski zip'i sil
					if (file_exists(site_YOL . 'gizli/yedek.zip')) {
						if (!unlink(site_YOL . 'gizli/yedek.zip'))
							throw new \Exception('Eski Yedek Dosyası Silinemedi.');
					}

					// Zip oluştur
					if ($zip->open(site_YOL . 'gizli/yedek.zip', ZIPARCHIVE::CREATE) !== true)
						throw new \Exception('Zip dosyası oluşturulamadı.');

					// Tabloları json olarak kaydet
					$kaydedilecekTablolar = array('abone', 'admin', 'urun', 'urun_join', 'yorum');
					foreach ($kaydedilecekTablolar as $k) {
						// $k tablosu, json dosyasına çevrilir
						if ( ! file_put_contents(site_YOL . 'gizli/' . $k . '.json', json_encode($this->db->get($k)->result()))) {
							throw new \Exception('Veritabanından bilgileri alıp dosya oluşturmada bir sorun oluştu.');
						} else {
							// Json dosyasını zip'e ekle
							$zip->addFile(site_YOL . 'gizli/' . $k . '.json', 'Database/' . $k . '.json');
						}
					}

					// Ek içerik dosyalarını zip'e ekle
					$objects = new RecursiveIteratorIterator(
						new RecursiveDirectoryIterator(realpath('dosya/')), RecursiveIteratorIterator::SELF_FIRST
					);
					foreach($objects as $name) {
						if (substr($name, -1) == '.') continue;
						if (is_file($name))
							$zip->addFile($name, 'Dosya/' . substr($name, strlen(realpath('dosya/')) + 1));
					}

					// Son olarak site ikonu ve da ekleniyor
					$zip->addFile(site_YOL . 'favicon.ico', 'favicon.ico');

					$zip->close();

					// Zip'e eklenmek için oluşturulmuş dosyalar silinir.
					foreach ($kaydedilecekTablolar as $k) {
						unlink(site_YOL . 'gizli/' . $k . '.json');
					}

					// Zip dosyası kontrol edilir
					if ( ! file_exists(site_YOL . 'gizli/yedek.zip')) {
						throw new \Exception('Herşey başarılı görünse de yedek oluşturulamadı.');
					} else {

						// Herşey başarılı
						redirect(base_url('adminB/') . 'ayarlar?yedek=indir' . (isset($_GET['ydSil']) ? '&ydSil=true' : '' ));
					}

				} catch (\Exception $e) {
					bildirim_olustur(SONUC_HATA, 'Sorun Oluştu: ' . $e->getMessage(), 'ayarlar');

				}


			}
			elseif ($_GET['yedek'] == 'indir') // Yedeği indir
			{
				if (file_exists(site_YOL . 'gizli/yedek.zip')) {
					header('Content-Type: application/x-zip');
					header('Content-Disposition: attachment;filename="' . $this->config->item('site')->ad . '-Yedek ' . date('d-m-Y', filemtime(site_YOL . 'gizli/yedek.zip')) . '.zip"');
					echo file_get_contents(site_YOL . 'gizli/yedek.zip');

					if (isset($_GET['ydSil'])) {
						// DEBUG: Çalışmıyor
						header('Refresh:0; url=' . base_url('adminB/') . 'ayarlar?sifirla=site');
					}
				} else {
					bildirim_olustur(SONUC_UYARI, 'Henüz hiç yedek yok.', 'ayarlar');

				}

			} else {
				boyle_birsey_olamaz('ayarlar');
			}

		} elseif (isset($_GET['sifirla'])) {

			girmek_icin_gerekli_seviye(YONETICI_BAS);
			// HACK: Biri buraya direkt link verir ve oturum açmış yönetici linke tıklarsa site yok olur
			$this->load->helper('kurulum');
			siteyiSifirla();

		} else {
			boyle_birsey_olamaz('ayarlar');
		}

	}

	public function urun($metod='', $param1='')
	{
		if ($metod == 'ekle') {

			if (isset ($_POST['ad'])){
				$this->load->helper('urun');
				$sonuc = urunEkle ($this->adminInfo->id, trim($_POST['ad']), $_POST['kategori'], $_POST['altkategori'], array(
					'aciklama'	=> trim($_POST['aciklama']),
					'taglar'		=> trim($_POST['taglar']),
					'begen'			=> 0,
					'begenme'		=> 0,
					'kaynak'		=> trim($_POST['kaynak']),
					'icerik'		=> trim($_POST['icerik'])
				));
				if ($sonuc[0] === SONUC_BASARI){ // Sonuç başarılıysa
					$u = new urun();
					redirect($u->urunLink($_POST['kategori'], $_POST['altkategori'], $_POST['ad']));

				} else {//Sonuç başarılı değilse
					bildirim_olustur(SONUC_HATA, 'Hata: ' . $sonuc[1], 'urun/ekle/');
				}

			}else{
				boyleBisiOlamaz('admin');
			}

		} elseif ($metod == 'duzenle') {

			$this->load->model ('urun_model');
			if ($param1 == 0 || empty($this->urun_model->al($param1))){
				boyleBisiOlamaz();
			}

			if(isset($_GET['sil'])){//Ürün silinecekse
				$this->load->helper('urun');
				$silmeBasarilimi = urunSil($param1);
				bildirim_olustur($silmeBasarilimi[0], $silmeBasarilimi[1], ($silmeBasarilimi[0] === SONUC_BASARI)?'urun/yonet':'urun/duzenle/' . $param1);
			}

			if (isset ($_POST['ad'])){//Ürün düzenlenecekse
				$this->load->helper('urun');
				$sonuc = urunDuzenle($_POST['id'], $_POST['ad'], $_POST['kategori'], $_POST['altkategori'], array(
					'aciklama'	=> $_POST['aciklama'],
					'taglar'		=> $_POST['taglar'],
					'icerik'		=> $_POST['icerik']
				));
				if ($sonuc[0] === SONUC_BASARI) {
					$u = new urun();
					redirect($u->urunLink($_POST['kategori'], $_POST['altkategori'], $_POST['ad']));

				} else {
					bildirim_olustur($sonuc[0], $sonuc[1], 'urun/duzenle/' . $param1);

				}
			}

		} else {
			boyleBisiOlamaz('admin');
		}

	}

	public function yorumlar()
	{
		girmek_icin_gerekli_seviye(YONETICI_MOD);
		$this->load->model('Yorum_model');
		if (isset($_GET['sil'])) {
			$silmeSonucu = $this->Yorum_model->sil($_GET['sil']);
			if ($silmeSonucu[0] && $silmeSonucu[1] && $silmeSonucu[2]) {
				bildirim_olustur(SONUC_BASARI, 'Yorum ve cevapları silindi.');
			} else {
				if ($silmeSonucu[0]) {
					bildirim_olustur(SONUC_UYARI, 'Yorum silindi. Ancak yorumun cevapları silinemedi. Bu görüntüde sorun çıkarmaz ama veritabanında boş yer kaplar.');
				} else {
					bildirim_olustur(SONUC_HATA, 'Yorum silinemedi. Tekrar deneyin.');
				}
			}

			redirect(empty($_GET['redirect'])? base_url('admin/') . 'yorumlar' : $_GET['redirect']);
		} elseif (isset($_GET['tamir'])) {
			$silSonuc = $this->Yorum_model->tabloTamir();
			if ($silSonuc[0]) {
				bildirim_olustur(SONUC_BASARI, 'Tablodaki karşılıksız yorumlar kontrol edildi, ' . $silSonuc[1] . ' karşılıksız yorum silindi.');
			} else {
				bildirim_olustur(SONUC_BASARI, 'Tablodaki karşılıksız yorumları silme sırasında sorun çıkmış olabilir, ' . $silSonuc[1] . ' karşılıksız yorum silindi.');
			}
			redirect(empty($_GET['redirect'])? base_url('admin/') . 'yorumlar' : $_GET['redirect']);
		}	else {
			boyleBisiOlamaz('admin/yorumlar');
		}
	}

	public function seo($metod='')
	{
		if ($metod == 'robots') {

			if (isset($_POST['icerik'])) {
				$sonuc = file_put_contents(site_YOL . 'robots.txt', $_POST['icerik']);
				bildirim_olustur($sonuc ? SONUC_BASARI : SONUC_HATA, $sonuc ? 'Robots.txt dosyası başarıyla güncellendi' : 'Robots.txt dosyası güncellenemedi', 'seo/robots');
			}

		} if ($metod == 'sitemap') {

			if (isset($_POST['sitemap'])) {//Sitemap üzerinde değişiklik yapılmış
				// IDEA: round ile noktadan sonra iki basamak bırak
				$ayarlarJson = json_decode(file_get_contents(site_YOL . 'gizli/ayarlar.json'));
				$ayarlarJson->seo->kategori = (($_POST['kategori'] <= 1 && $_POST['kategori'] >= 0)?(isset($_POST['kategori'])?(float)$_POST['kategori']:0):1);//Geçersiz bir değerse bir yap
				$ayarlarJson->seo->altkategori = (($_POST['altkategori'] <= 1 && $_POST['altkategori'] >= 0)?(isset($_POST['altkategori'])?(float)$_POST['altkategori']:0):1);//Geçersiz bir değerse bir yap
				$ayarlarJson->seo->sayfalar = (($_POST['sayfalar'] <= 1 && $_POST['sayfalar'] >= 0)?(isset($_POST['sayfalar'])?(float)$_POST['sayfalar']:0):1);//Geçersiz bir değerse bir yap
				$ayarlarJson->seo->ekMap = $_POST['icerik'];
				try {
					file_put_contents(site_YOL . 'gizli/ayarlar.json', json_encode($ayarlarJson));
				} catch (\Exception $e) {
					$hata = $e;
				}
				if (isset($hata)) {
					bildirim_olustur(SONUC_HATA, 'Bir hata oluştu.');
					// DEBUG: hata yı bildir.
				} else {
					bildirim_olustur(SONUC_BASARI, 'Site haritası ayarları başarıyla güncellendi.');
				}
				redirect (base_url('admin/seo/') . 'sitemap');

			} elseif(isset($_POST['onem'])) {//Urunmap üzerinde değişiklik yapılmış

				$ayarlarJson = json_decode(file_get_contents(site_YOL . 'gizli/ayarlar.json'));
				$ayarlarJson->seo->urunOnem = ($_POST['onem'] <= 1 && $_POST['onem'] >= 0)?(float)$_POST['onem']:1;//Geçersiz bir değerse bir yap
				try {
					file_put_contents(site_YOL . 'gizli/ayarlar.json', json_encode($ayarlarJson));
				} catch (\Exception $e) {
					$hata = $e;
				}
				if (isset($hata)) {
					bildirim_olustur(SONUC_HATA, 'İçeriğin önemi güncellenemedi. Bir hata oluştu.');
				} else {
					bildirim_olustur(SONUC_BASARI, 'İçeriğin önemi ' . $ayarlarJson->seo->urunOnem . ' olarak başarıyla güncellendi.');
				}
				redirect (base_url('admin/') . 'sitemap');

			} else {
				boyle_birsey_olamaz('sitemap');
			}

		} else {
			boyleBisiOlamaz();
		}
	}


	public function reklam()
	{
		if (isset($_FILES))
		{
			// HACK: reklamHangi farklı rakam yazarsa siteye erişim yapar
			$this->load->library('upload', array (
				'upload_path'   => site_YOL . 'rel/img/reklam/',
				'allowed_types' => 'jpg',
		    'overwrite'     => TRUE,
				'file_name'     => (int) $_POST['reklamHangi'] . '.jpg'
			));
			if ($this->upload->do_upload('reklamResmi'))
			{
				$reklamData = json_decode(file_get_contents(site_YOL . 'rel/img/reklam/data.json'));
				$reklamData->{$_POST['reklamHangi']} = $_POST['baglanti'];
				if (file_put_contents(site_YOL . 'rel/img/reklam/data.json', json_encode($reklamData)))
				{
					bildirim_olustur(SONUC_BASARI, 'Reklamınız başarıyla güncellendi', 'reklam');
				}
				else
				{
					bildirim_olustur(SONUC_HATA, 'Reklam resmi yüklendi ama bağlantı güncellenemedi', 'reklam');
				}

			} else {
				bildirim_olustur(SONUC_HATA, 'Reklam resminin yüklenmesi başarısız: ' . $this->upload->display_errors(), 'reklam');
			}
		}
		else
		{
			boyle_birsey_olamaz('reklam');
		}

	}

	#################################### DİGER ##########################################

	public function yukle($yuklenen='', $duzenle='')
	{
		if ($duzenle == 'duzenle') {
			girmek_icin_gerekli_seviye(YONETICI_MOD);
			if (isset($_GET['sil'])) {
				// HACK: $_GET sil içine .. dizinini kullanırsa tüm sunucuyu silebilir
				if (file_exists(site_YOL . 'dosya/icerik/' . $yuklenen . '/' . $_GET['sil'])) {
					$silmeSonucu = unlink(site_YOL . 'dosya/icerik/' . $yuklenen . '/' . $_GET['sil']);

					bildirim_olustur(
						$silmeSonucu ? SONUC_BASARI : SONUC_HATA,
						$silmeSonucu ? 'Silme başarılı' : 'Silme başarısız',
						'yukle/' . $yuklenen . '/duzenle'
					);

				} else {
					bildirim_olustur(SONUC_UYARI, 'Silinmesini istediğiniz dosya bulunamadı.', 'yukle/' . $yuklenen . '/duzenle');

				}

			} else {
				boyleBisiOlamaz('admin/yukle/' . $yuklenen . '/duzenle');
			}

		} else {
			girmek_icin_gerekli_seviye(YONETICI_NOR);
			if (isset($_FILES['dosya'])) {
				if (file_exists(site_YOL . 'dosya/icerik/' . $yuklenen . '/' . $_POST['ad'])) {
					bildirim_olustur(SONUC_HATA, 'Aynı adda başka dosya var. Başka birşeye zarar vermemek için yükleme iptal edildi. Yenisini yüklemek için düzenleme yerinden eski dosyayı silebilirsiniz.', 'yukle/' . $yuklenen);
				}

				$this->load->library ('upload', array (
			    'upload_path' => site_YOL . 'dosya/icerik/' . $yuklenen,
			    'allowed_types' => (($yuklenen == 'resim') ? 'png|jpg|gif' : '*'),
			  	'file_name' =>  $_POST['ad']
			  ));
				if ($this->upload->do_upload('dosya')) {
					if ($yuklenen == 'resim') {
						$this->load->library('Metadata');
						if (substr($_POST['ad'], -3) == 'jpg') {
							if ($this->metadata->jpg('dosya/icerik/' . $yuklenen . '/' . $_POST['ad'])[0] !== SONUC_BASARI) {
								yonetimeBildir(5, 'Resim Metadata Değişiminde Sorun', 'Resim metadata değiştirilirken sorun çıkmış olabilir. Resim adı: "' . $_POST['ad'] . '".');
							}
						}
					}

					bildirim_olustur(SONUC_BASARI, 'Yüklendi. Dosyaya <a href="' . base_url('dosya/icerik/' . $yuklenen . '/' . $_POST['ad']) . '" target="_blank">buradan</a> ulaşabilirsiniz.', 'yukle/' . $yuklenen);
				} else {
					bildirim_olustur(SONUC_HATA, 'Yükleme başarısız. Hata: ' . $this->upload->display_errors(), 'yukle/' . $yuklenen);
				}

			} elseif ($_POST['url']) {// Ajax ile internetteki resmi yükleme isteği gelmiş
				$resimData = curl($_POST['url']);
				if (empty($resimData)) {
					echo "0";
				} else {
					$this->load->library('Metadata');
					echo (file_put_contents(site_YOL . 'dosya/icerik/resim/' . $_POST['ad'], $this->metadata->jpg($resimData)) == true);

				}
			} else {
				boyleBisiOlamaz('admin/yukle/' . $yuklenen . '/duzenle');
			}
		}
	}


	public function istatistik()
	{
		if (isset($_GET['csv']))
		{
			girmek_icin_gerekli_seviye(YONETICI_MOD);

			$this->load->model('Istatistik_model');

			// Görüntülenmesini değil, indirilmesini istiyoruz
			header('Content-Type:application/octet-stream');
			header('Content-Disposition: attachment;filename="' . $this->config->item('site')->ad . '-istatistikler.csv"');

			echo $this->Istatistik_model->csvOlarakAl();
		}
		else
		{
			boyle_birsey_olamaz('istatistik');
		}

	}
	public function abone()
	{
		girmek_icin_gerekli_seviye(YONETICI_UST);
		$this->load->model('Abone_model');
		if (isset($_GET['sil'])) {
			if ($this->Abone_model->sil($_GET['sil'])) {
				bildirim_olustur(SONUC_BASARI, 'Abone kaldırıldı.');
			} else {
				bildirim_olustur(SONUC_HATA, 'Abone silinemedi. Tekrar deneyin.');
			}

			redirect(base_url('admin/') . 'abone');
		}	else {
			boyleBisiOlamaz('admin/abone');
		}
	}

	public function bildirimler(){
		if (isset($_POST['sil'])) {//Silinecek bildirimlerin id'lerini alır
			$this->load->model('adminBildirim_model');
			$silDizi = explode(',', $_POST['sil']);
			if($this->adminBildirim_model->bildirimSil($silDizi)){
				echo '1';
			}else{
				echo '0';
			}
		}else{
			redirect(base_url('admin/') . 'bildirimler');
		}
	}

	public function yapilacaklar()
	{
		$this->load->model('adminYapilacaklar_model');
		if (isset($_POST['adi'])) {//Yeni bilgi ekleyecek
			$ekleme = ($admin->duzey < 6)?$this->adminYapilacaklar_model->ekle($_POST['adi'], $_POST['kime']):$this->adminYapilacaklar_model->ekle($_POST['adi'], $this->adminInfo->id);

			if ($ekleme) {
				bildirim_olustur(SONUC_BASARI, 'Yeni görev eklendi.');
			} else{
				bildirim_olustur(SONUC_HATA, 'Yeni görev eklenemedi.');
			}
		} elseif (isset($_GET['sil'])){//Bir görev silecek

			if ($this->adminYapilacaklar_model->sil($_GET['sil'])) {
				bildirim_olustur(SONUC_BASARI, 'Görev kaldırıldı.');
			} else {
				bildirim_olustur(SONUC_HATA, 'Görev kaldırılamadı.');
			}
		}
		redirect(base_url('admin/') . 'yapilacaklar');
	}

	public function mesaj()//Tüm mesajlaşma işlemleri
	{
		$this->load->model('AdminMesaj_model');

		if(isset($_POST['al'])) {//Yeni mesajları al

			echo json_encode($this->AdminMesaj_model->sonMesajlar($this->adminInfo->id, $_POST['al'], (isset($_POST['sadeceYeniler'])?true:false)));

		} elseif (isset($_POST['at'])) {

			echo json_encode($this->AdminMesaj_model->ekle($this->adminInfo->id, $_POST['atKime'], $_POST['at']));

		} elseif (isset($_POST['sil'])) {

				echo $this->AdminMesaj_model->sil($_POST['sil'], isset($_POST['herkes'])?$_POST['herkes']:null);

		} elseif (isset($_POST['temizle'])) {

			echo $this->AdminMesaj_model->temizle($this->adminInfo->id, $_POST['temizle']);

		} elseif (isset($_POST['Tal'])) {

			echo json_encode($this->AdminMesaj_model->sonMesajlar($this->adminInfo->id, $_POST['Tal'], false, true));

		} else {
			boyleBisiOlamaz();
		}

	}


	public function cikis (){
		$this->session->unset_userdata ('admin');

		redirect (base_url('admin/giris'));
	}

}
