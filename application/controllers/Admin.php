<?php
// TODO: Form validation class ı kullan!
// TODO: İnput class kullan!
// TODO: Language class kullan!
// TODO: Pace kullan ajax için
// TODO: Define leri constant a koy

defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends CI_Controller {

	function __construct() {
		parent::__construct();

		// Oturum açmamış ve giriş sayfasında değilse
		if ( ! isset($this->session->get_userdata ()['admin']))
		{
			if ($this->router->fetch_method() != 'giris')
			{
				// Giriş sayfasına gönder
				redirect (base_url('admin/giris?redirect=') . $this->router->fetch_method());

			}

		}
		else
		{
			// Yönetici ise

			$this->load->helper ('urun');
			$this->load->helper ('admin');
			$this->load->model ('admin_model');

			$this->adminInfo = $this->admin_model->adminiAl($this->session->get_userdata ()['admin']);	//Admin bilgilerini al

			// Gösterilen ID'de bir yönetici yoksa
			if (is_null($this->adminInfo))
			{
				// Gösterilen ID'yi sil ve giriş sayfasına gönder
				$this->session->unset_userdata ('admin');
				redirect (base_url('admin/giris'));

			}

			$this->headerInfo = array (
				'admin'			=> $this->adminInfo,
				'headVar'		=> $this->admin_model->headBilgileri($this->adminInfo->id)
			);

		}
	}


	public function index()
	{

		$this->load->model(array('Istatistik_model', 'AdminYapilacaklar_model'));

		$this->viewData = array(
			'yapilacaklar'	=> $this->AdminYapilacaklar_model->al(5)
		);

		//En çok tıklananlar tablosu oluşturuluyor
		$this->load->library('table');
		$this->table->set_heading(array('Ad', 'Kategori', 'Alt Kategori', 'Görüntülenme'));
		$this->table->set_template(array('table_open' => '<table class="table no-margin">'));
		$u = new Urun();
		foreach ($this->Urun_model->enAl('unlu', 5) as $sonuc) {
			$this->table->add_row(
				'<a href="' . $u->urunLink($sonuc) . '" target="_blank">' . $sonuc->ad . '</a>',
				$u->kateg[$sonuc->kategori],
				$u->altkateg[$sonuc->kategori][$sonuc->altkategori],
				$sonuc->goruntulenme
			);
		}
		$this->viewData['enCokTiklananlar'] = $this->table->generate();


		render_page('anaSayfa', 'Yönetici Paneli');
	}



	public function giris ()
	{
		try {

			// Zaten yönetici ise ana sayfaya gönder
			if (isset($this->session->get_userdata ()['admin']))
				throw new \Exception('Zaten Yöneticisiniz');

			// Ek önlem: admin/giris?code= şeklinde verilen algoritmada çıkacak sonuç
			// yazılmazsa, sistemi hiç bilmeyen biri tarafından yönetici paneline
			// girilmiştir. $fakeGiris TRUE olur, kullanıcı hiç dikkate alınmaz.
			$fakeGiris = ($this->config->item('giriste_ek_url_koruması')) ? (isset($_GET['code']) ? ( ($_GET['code'] == $this->config->item('giriste_ek_url_koruma_algoritması') ) ? FALSE : TRUE ) : TRUE) : FALSE ;

			// Giriş yapmak için bilgiler gönderildiyse
			if (isset($_POST['mail']) && ! $fakeGiris)
			{
				// Captcha yanlış yazıldıysa
				if ($_SESSION['captchaWord'] != $_POST['captcha'])
					throw new \Exception('Güvenlik Kodu Yanlış!');


				$this->load->model ('admin_model');

				// Giriş bilgilerini kontrol et
				$kullanici = $this->admin_model->girisKontrol($_POST['mail']);
				if(empty($kullanici))
					throw new \Exception('Bu mail\'e sahip kimse yok! Mail: ' . htmlspecialchars($_POST['mail']));

				// Şifreyi kontrol et
				if ($kullanici->sifre != md5($_POST['sifre']))
					throw new \Exception('&quot;' . htmlspecialchars($_POST['mail']) . '&quot; mail\'i için yanlış şifre girildi.');

				// Hiç sorun yoksa :

				// Giriş için session'u oluştur
				$this->session->set_userdata('admin', $kullanici->id);
				$this->admin_model->sonGorulmeGuncelle($kullanici->id);
				// Ana panele veya verilen sayfaya yönlendir
				redirect(base_url('admin/') . (empty($_POST['redirect']) ? '' : $_POST['redirect']));

			}

		} catch (\Exception $e) {
			if ($e->getMessage() == 'Zaten Yöneticisiniz')
			{
				boyle_birsey_olamaz(isset($_GET['redirect']) ? $_GET['redirect'] : '');
			}
			else
			{
				$hata = $e->getMessage();
				yonetime_bildir($this->config->item('bildirim_seviyesi')['hata_yonetici_giris'], 'Yönetici Girişi Sırasında Hata', $e->getMessage());
			}

		}

		// Captcha oluşturuluyor
		$this->load->helper('captcha');
		$capParam = array(
			'img_path'		=> './rel/img/captcha/',
			'img_url'			=> base_url('rel/img/captcha/'),
			'word_length'	=> $this->config->item('captcha_uzunluğu'),
			'expiration'	=> 20
		);
		$cap = create_captcha($capParam);
		$_SESSION['captchaWord'] = $cap['word'];

		if (isset($_POST['mail']) && $fakeGiris)
			$hata = 'Hatalı giriş';

		$this->load->view ('admin/sayfa/giris', array('cap' => $cap, 'hata' => (isset($hata) ? $hata : null)));

	}


	/**
	 * Yönetici
	 *
	 * Yönetici yönetme, düzenleme, ekleme işlemleri yapılır
	 *
	 * @param string	Yönetme, düzenleme, eklemeden hangisini yapacağı
	 * @param string
	 */
	public function yonetici($metod = '', $param1 = '')
	{
		// Tüm yöneticilerin gösterildiği tablo
		if ($metod == 'yonet')
		{
			girmek_icin_gerekli_seviye(YONETICI_UST);

			$this->load->model('admin_model');

			// Yöneticilerin bulunduğu tablo oluşturuluyor
			$this->load->library('table');
			$this->table->set_heading(array('Adı', 'Düzey', 'Mail', 'ID'));
			$this->table->set_template(array('table_open' => '<table id="adminTablosu" class="table table-bordered table-striped">'));
			foreach ($this->admin_model->tumAdminler() as $sAdmin) {
				$this->table->add_row(
					'<img class="profile-user-img img-circle" style="height:50px; width:50px;" src="'. base_url('rel/img/admin/') . $sAdmin->id .'.jpg">&nbsp;<a href="'.base_url('admin/yonetici/duzenle/') . $sAdmin->id . '">' . $sAdmin->ad . '</a>',
					yonetici_duzey_adi($sAdmin->duzey),
					$sAdmin->mail,
					$sAdmin->id
				);
			}
			$this->headerInfo['tablo'] = $this->table->generate();


			render_page('adminYonet', 'Adminleri Yönetin', array(
				'ekleCSS'			=> array ('bower_components/datatables.net-bs/css/dataTables.bootstrap.min'),
				'ekleJS'			=> array ('bower_components/datatables.net/js/jquery.dataTables.tr.min', 'bower_components/datatables.net-bs/js/dataTables.bootstrap.min'),
				'footerExtra'	=> '<script> $(function () { $("#adminTablosu").DataTable() }) </script>'
			));

		}
		// Verilen ID'deki yöneticiyi düzenler
		elseif ($metod == 'duzenle')
		{
			// ID verilmediyse "ekle" sayfasına gönder
			if ($param1 == 0)
			{
				redirect(base_url('admin/yonetici/ekle'));
			}

			// Kendi bilgilerini düzenlemeyecekse sadece üst yöneticiler yöneticileri düzenleyebilir
			if ($param1 != $this->adminInfo->id)
			{
				girmek_icin_gerekli_seviye(YONETICI_UST);
			}

			// Yöneticinin bilgileri alınır
			$this->viewData['adminDuzenle'] = $this->admin_model->adminiAl($param1);

			// "Silinecek yöneticinin ürünleri nereye gidecek" için tüm adminler gerekli
			$this->viewData['tumAdminler'] = $this->admin_model->tumAdminler('id', 'ad');

			render_page('adminDuzenle', 'Yöneticiyi Düzenleyin');

		}
		// Yeni yönetici ekleme sayfası
		elseif ($metod == 'ekle')
		{
			girmek_icin_gerekli_seviye(YONETICI_UST);

			render_page('adminDuzenle', 'Yeni Yönetici Ekleyin');
		}
		else
		{
			boyle_birsey_olamaz();
		}
	}

	public function php()
	{
		if ($this->config->item('php_calistirilabilir'))
		{
			girmek_icin_gerekli_seviye(YONETICI_BAS);

			if (isset($_POST['calistir'])) {
				$dbBaglansinMi = ($_POST['database'] == 'on') ? 'try {$dbBaglanti = new PDO(\'mysql:host=' . $this->db->hostname . ';dbname=' . $this->db->database . '\', \'' . $this->db->username . '\', \'' . $this->db->password . '\', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));}catch(PDOException $mesajpdo){echo \'Veritabanı bağlantısında bir hata oluştu: \' . $mesajpdo->getMessage(); exit();}' : '';
				$phpyeYaz = '<?php //Php çalıştırmak için oluşturulmuştur. Silebilirsiniz.'. "\n" . $dbBaglansinMi . ' unlink(__FILE__); ' . $_POST['calistir'] . ' ?>';
				if (!file_put_contents(site_YOL . '.deleteThis.php', $phpyeYaz)) {
					bildirimOlustur(SONUC_HATA, 'Php çalıştırmak için ana dizinde, php ile dosya oluşturulabiliyor olmalıdır. Bir şekilde php dosyası oluşturulamadı. Bu yüzden bu özelliği kullanamıyorsunuz.');
				}
			}

			render_page('phpCalistir', 'Php Komutu Çalıştırın');
		}
		else
		{
			bildirim_olustur(SONUC_UYARI, 'Bu sayfaya giriş site ayarları bölümünden engellenmiştir', '');
		}

	}

	public function ayarlar()
	{
		girmek_icin_gerekli_seviye(YONETICI_UST);

		render_page('siteAyarlar', 'Site Ayarları');
	}

	public function urun($metod='', $param1='')
	{
		if ($metod == 'ekle') {
			girmek_icin_gerekli_seviye(YONETICI_NOR);
			render_page('urunEkle', 'Ürün Ekle');

		} elseif ($metod == 'yonet') {

			girmek_icin_gerekli_seviye(YONETICI_NOR);
			if (isset($_GET['kategori'])){
				$this->load->model ('urun_model');
				$this->load->model('Admin_model');

				foreach ($this->Admin_model->tumAdminler() as $admini) {
					$adminKarsilastir[$admini->id] = $admini->ad;
				}

				$this->load->library('table');

				$this->table->set_heading(array('Ad', 'Görüntülenme', 'Yükleyen', 'Yorumlar'));
				$this->table->set_template(array('table_open' => '<table id="urunTablosu" class="table table-bordered table-striped">'));
				foreach ($this->urun_model->listele($_GET['kategori'], $_GET['altkategori'], $_GET['ara']) as $sonuc) {
					$this->table->add_row(
						'<a href="'.base_url('admin/urun/duzenle/') . $sonuc->id . '">'.$sonuc->ad.'</a>',
						$sonuc->goruntulenme,
						$adminKarsilastir[$sonuc->yukleyen],
						'<a href="' . base_url('admin/') . 'yorumlar?urunID=' . $sonuc->id . '" class="btn btn-primary"><i class="fa fa-comment"></i> Yorumları Yönet</a>'
					);
				}
				$this->viewData['tablo'] = $this->table->generate();

				$this->viewData['adminIDListe'] = $adminKarsilastir;

			}

			render_page('urunYonet', 'Ürünleri Yönetin', array(
				'ekleCSS'			=>	array ('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'),
				'ekleJS'			=> array ('bower_components/datatables.net/js/jquery.dataTables.tr.min.js', 'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js'),
				'footerExtra'	=> '<script> $(function () { $("#urunTablosu").DataTable() }) </script>'
			));

		} elseif ($metod == 'duzenle') {
			if ($param1 == '')
			{
				// Ürün düzenle denip, ürün ID verilmediyse yönlendir
				boyle_birsey_olamaz('urun/yonet');
			}

			girmek_icin_gerekli_seviye(YONETICI_MOD);

			$this->load->model ('urun_model');
			$this->viewData['urun'] = $this->urun_model->al($param1);

			if (empty($this->viewData['urun']))
			{
				// Belirtilen ID'ye sahip ürün yoksa yönlendir
				boyle_birsey_olamaz('urun/yonet');
			}

			render_page('urunEkle', 'Ürün Düzenle');

		} elseif ($metod == 'onizle') {

			if (isset($_POST['ad'])) {

				$this->load->model('Urun_model');
				$this->load->library('markdown');

				//Tanımlamalar
				$tanimlar = array(
					'rD'		=> base_url('dosya/icerik/resim/'),
					'dD'		=> base_url('dosya/icerik/dosya/'),
					'sD'		=> base_url(),
					'code'	=> '<pre class="prettyprint linenumber"><code>',
					'/code'	=> '</code></pre>'
				);
				foreach ($tanimlar as $key => $value) {
					$_POST['icerik'] = str_replace('{' . $key . '}', $value, $_POST['icerik']);
				}

				$headerInfo = array(
					'u' => new urun(),
					'meta' => array (
						'baslik' => $_POST['baslik'],
						'aciklama' => $_POST['aciklama'],
						'taglar' => $_POST['taglar']
					),
					'onerilenler' =>  $this->Urun_model->enAl('unlu', 5, $_POST['kategori'], $_POST['altkategori']),
					'buUrun' => (object)array(
						'id' => 1,
						'ad' => $_POST['ad'],
						'kategori' => $_POST['kategori'],
						'altkategori' => $_POST['altkategori'],
						'sef' => seflink($_POST['ad']),
						'goruntulenme' => 0,
						'yukleyen' => $this->adminInfo->id,
						'tarih' => date('Y-m-d'),

						'aciklama' => $_POST['aciklama'],
						'taglar' => $_POST['taglar'],
						'begen' => 0,
						'begenme' => 0,
						'kaynak' => $_POST['kaynak'],
						'icerik' => $this->markdown->parse($_POST['icerik'])
					),
					'onIzleme' => array(//Ön izlemeyle ilgili bilgileri içerir
						'resim64' => (file_exists($_FILES['resim']['tmp_name'])?base64_encode(file_get_contents($_FILES['resim']['tmp_name'])):'')
					)
				);

				$this->load->view('include/header', $headerInfo);
				$this->load->view('sayfa/urun', $headerInfo);
				$this->load->view('include/footer', $headerInfo);

			} else {
				boyle_birsey_olamaz('admin');
			}


		} else {
				boyle_birsey_olamaz('admin');
		}

	}

	public function yorumlar($yeniMi = false)
	{
		girmek_icin_gerekli_seviye(YONETICI_MOD);
		$this->load->model('Yorum_model');

		$tabloVerisi = $this->Yorum_model->gelismisAl(
			($yeniMi == "yeni"),
			(isset($_GET['urunID'])?$_GET['urunID']:null),
			(isset($_GET['mail'])?$_GET['mail']:null),
			(isset($_GET['site'])?$_GET['site']:null),
			(isset($_GET['icerik'])?$_GET['icerik']:null)
		);
		$this->viewData['yeniMi'] = $yeniMi == "yeni";

		$this->load->library('table');
		$this->table->set_heading('Yorum Yapılan Yer', 'Ad', 'Mail', 'Tarih', 'Site', 'Yorum', 'İşlemler');
		$this->table->set_template(array('table_open' => '<table id="yorumTablosu" class="table table-bordered table-striped">'));
		$urun = new Urun();
		foreach ($tabloVerisi as $sonuc) {
			$this->table->add_row(
				'<a href="' . $urun->urunLink($sonuc->yapilanYer->kategori, $sonuc->yapilanYer->altkategori, $sonuc->yapilanYer->sef) . '" target="_blank">' . $sonuc->yapilanYer->ad . '</a>',
				$sonuc->ad,
				'<a href="mailto:' . $sonuc->mail . '">' . $sonuc->mail . '</a>',
				$sonuc->tarih,
				$sonuc->site,
				$sonuc->icerik,
				'<a class="btn btn-primary" href="' . $urun->urunLink($sonuc->yapilanYer->kategori, $sonuc->yapilanYer->altkategori, $sonuc->yapilanYer->sef) . '?yorum=' . $sonuc->id . '" target="_blank"><i class="fa fa-external-link"></i> Git</a>
				<a class="btn btn-danger" href="' . base_url('adminB/') . 'yorumlar?sil=' . $sonuc->id . '&redirect=' . base_url() . $this->urI->uri_string . '?' . $_SERVER['QUERY_STRING'] . '"><i class="fa fa-trash"></i> Sil</a>'
			);
		}
		$this->viewData['tablo'] = $this->table->generate();

		render_page('yorumlar', (($yeniMi)?'Yeni Yorumları İnceleyin':'Yorumları Yönetin'),array(
			'ekleCSS'			=> array ('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css'),
			'ekleJS'			=> array ('bower_components/datatables.net/js/jquery.dataTables.tr.min', 'bower_components/datatables.net-bs/js/dataTables.bootstrap.min'),
			'footerExtra'	=> '<script> $(function () { $("#yorumTablosu").DataTable() }) </script>'
		));
	}

	public function seo($metod='')
	{
		girmek_icin_gerekli_seviye(YONETICI_UST);

		if ($metod == 'robots') {
			$this->viewData['robotsIcerik'] = htmlspecialchars(file_get_contents(site_YOL . 'robots.txt'));

			render_page('robots', 'Robots.txt Dosyasını Düzenleyin.');

		} elseif ($metod == 'sitemap') {
			$this->load->model('Urun_model');
			$this->viewData['seoAyar'] = json_decode(file_get_contents(site_YOL . 'gizli/ayarlar.json'))->seo;
			$this->viewData['icerikmapSayisi'] = $this->Urun_model->urunSayisi();

			render_page('sitemap', 'Site Haritalarını Düzenleyin.');

		} else {
			boyle_birsey_olamaz();
		}

	}


#################################### DİĞER ##########################################
	public function yukle($yuklenen='', $duzenle='')
	{
		if ($duzenle == 'duzenle') {
			girmek_icin_gerekli_seviye(YONETICI_MOD);

			$this->viewData['yuklenenNe'] = (($yuklenen == 'resim')?'Resim':'Dosya');
			$this->viewData['yuklenen'] = $yuklenen;
			$this->viewData['sirala'] = array();
			if ($dizin = opendir(site_YOL . 'dosya/icerik/' . $yuklenen)) {
				$i = 0;
				while (false !== ($dosya = readdir($dizin))) {
					if ($dosya != '.' && $dosya != '..') {
						if (preg_match('/' . $_GET['ara'] . '/i', $dosya)) {
							$this->viewData['sirala'][$i] = $dosya;
							$i++;
							if ($i == 50) {
								bildirimOlustur(SONUC_UYARI, '50\'den fazla dosya olduğundan hepsi gösterilemiyor.');
								break;
							}
						}
					}
				}
			} else {
				bildirimOlustur('dDizin açılamadı. Dosyalar gösterilemiyor');
			}
			closedir($dizin);
			$this->viewData['toplamSayisi'] = $i;

			render_page('yuklenenDuzenle', $this->viewData['yuklenenNe'] . ' Düzenle');

		} else {
			girmek_icin_gerekli_seviye(YONETICI_NOR);
			$this->viewData['yuklenenNe'] = (($yuklenen == 'resim')?'Resim':'Dosya');
			$this->viewData['yuklenen'] = $yuklenen;

			render_page('yukle', $this->viewData['yuklenenNe'] . ' Yükleyin');
		}
	}

	public function abone()//Mail abonelerini yönet
	{
		// IDEA: Atılan maillere bakıyor mu gibisinden şeyler eklenebilir
		girmek_icin_gerekli_seviye(YONETICI_MOD);

		$this->load->model('Abone_model');
		$this->load->library('table');
		$this->table->set_heading(array('Ad', 'Mail', 'Tarih', 'Seçenekler'));
		$this->table->set_template(array('table_open' => '<table id="aboneTablosu" class="table table-bordered table-striped">'));
		foreach ($this->Abone_model->al() as $sonuc) {
			$this->table->add_row(
				$sonuc->ad,
				$sonuc->mail,
				$sonuc->tarih,
				'<a class="btn btn-danger" href="' . base_url('adminB/') . 'abone?sil=' . $sonuc->mail . '"><i class="fa fa-trash"></i> Sil</a>'
			);
		}

		$this->viewData = array(
			'tablo'	=> $this->table->generate()
		);

		render_page('aboneler', 'Mail Abonelerini Yönetin', array(
			'ekleCSS' => array ('bower_components/datatables.net-bs/css/dataTables.bootstrap.min'),
			'ekleJS' => array ('bower_components/datatables.net/js/jquery.dataTables.tr.min', 'bower_components/datatables.net-bs/js/dataTables.bootstrap.min'),
			'footerExtra' => '<script> $(function () { $("#aboneTablosu").DataTable() }) </script>'
		));
	}

	public function reklam()
	{
		// IDEA: Reklama ne zaman ne kadar tıklanmış, grafik yapılabilir
		render_page('reklam', 'Reklamları Yönetin', array(
			'reklamData' => json_decode(file_get_contents(site_YOL . 'rel/img/reklam/data.json'))
		));
	}

	public function bildirimler(){
		$this->load->model('AdminBildirim_model');
		#GET: onem = 0, 1, 2, 3, 4(sadece üst adminler); goster = int
		if($_GET['onem'] == 4){
			girmek_icin_gerekli_seviye(YONETICI_MOD);
		}
		$this->viewData = array(
			'bildirimler'	=> $this->AdminBildirim_model->bildirimAl($_GET['onem']),
			'gGoster'			=> ( is_numeric($_GET['goster']) ? $_GET['goster'] : 0),
			'gOnem'				=> ( is_numeric($_GET['onem']) ? $_GET['onem'] : 0)
		);

		render_page('bildirimler', (($_GET['onem'] == 3)?'Üst Düzey':(($_GET['onem'] == 3)?'Hayati':(($_GET['onem'] == 2)?'Önemli':(($_GET['onem'] == 1)?'Orta':'Tüm')))) . ' Bildirimler', array(
			'ekleCSS'	=> array ('plugins/iCheck/flat/blue'),
			'ekleJS'	=> array ('plugins/iCheck/icheck.min', 'dist/js/bildirim')
		));
	}

	/**
	 * Profil
	 *
	 * Bir yöneticinin profilini veya tüm yöneticilerin istatistiklerini gösterir
	 *
	 * @param string	Bakılmak istenen yöneticinin ID'si
	 */
	public function profil($adminID = null)
	{
		if (is_null($adminID))
		{
			// Admin istatistiklerini görecek

			$this->load->model('Istatistik_model');

			// Tüm yöneticilerin verilerini tutacak değişlenler
			$i = 0;
			$this->headerInfo['jsonHafta'] = array();
			$this->headerInfo['jsonHaftaAdlar'] = array();
			$donutlar = array('ayUrun' => array(), 'urun' => array());
			$this->headerInfo['adminListe'] = $this->admin_model->tumAdminler();
			$birAyOnce = date('Y-m-d H:i:d', mktime(0,0,0, date('m'), date('d') - 30, date('Y')));
			$bugun = date('Y-m-d H:i:d', mktime(23,59,59, date('m'), date('d'), date('Y')));

			// Tüm yöneticilerin verileri her yönetici için oluşturuluyor
			foreach ($this->headerInfo['adminListe'] as $k)
			{
				$this->headerInfo['adminListe'][$i]->urunSayisi = $this->admin_model->yukledigiUrunSayisi($this->headerInfo['adminListe'][$i]->id);
				$i++;
				$donutlar['urun'][$k->ad] =$this->admin_model->yukledigiUrunSayisi($k->id);
				array_push($this->headerInfo['jsonHafta'], $this->Istatistik_model->son4AyYukleme($k->id));
				array_push($this->headerInfo['jsonHaftaAdlar'], $k->ad);
				$donutlar['ayUrun'][$k->ad] = $this->admin_model->yukledigiUrunSayisi($k->id, $birAyOnce, $bugun);

				if ($this->config->item('istatistik_tut')) {
					$donutlar['ayTiklanma'][$k->ad] = $this->admin_model->yukledigiUrunTiklanmaSayisi($k->id, $birAyOnce, $bugun);
					$donutlar['tiklanma'][$k->ad] =$this->admin_model->yukledigiUrunTiklanmaSayisi($k->id);
				}
			}
			unset($i);

			// Donut verileri, donutların anlayacağı javascript dizilerine çevriliyor
			foreach ($donutlar as $key => $val)
			{
				$i=0;
				$ret = '';
				foreach ($val as $k => $v)
				{
					$i++;
					$this->headerInfo['donut'][$key] .= '{ label: \'' . $k . '\', data: ' . $v . ', color: \'#' . rand(100000, 999999) . '\' }' . ( ($i == count($val) ) ? '' : ',');
				}
			}

			$this->headerInfo['meta']['baslik'] = 'Yönetici İstatistikleri';

			$this->load->view ('admin/sayfa/adminIstatistik', $this->headerInfo);

		}
		else
		{
			// Belirtilen ID'ye sahip yöneticinin profili gösterilecek

			if (empty($alAdmin = $this->admin_model->adminiAl($adminID)))
			{
				// Belirtilen ID'ye sahip yönetici yoksa yönlendirilir
				bildirim_olustur(SONUC_UYARI, 'Az önce girmeye çalıştığınız admin sistemde bulunmuyor!', 'profil');

			}

			$alAdmin->paylastigiUrunSayisi = $this->admin_model->yukledigiUrunSayisi($adminID);
			$this->viewData['alAdmin'] = $alAdmin;
			$this->viewData['timeline'] = $this->admin_model->yukledigiTimeline($adminID);

			render_page('profil', $alAdmin->ad . ' Profili');
		}
	}

	public function istatistik()
	{
		if ( ! $this->config->item('istatistik_tut')) {
			boyle_birsey_olamaz('');
		}

		$this->headerInfo['meta']['baslik'] = 'Site İstatistikleri';


		$this->load->model('Istatistik_model');
		$this->Istatistik_model->tabloyuTemizle();


		$this->headerInfo['yorumSayisi'] = $this->Istatistik_model->tarihindeSay('yorum');
		$this->headerInfo['jsonGunler'] = $this->Istatistik_model->son30GunTiklama();
		$this->headerInfo['saatler'] = $this->Istatistik_model->girisSaatleri();

		// Sayfanın en üstündeki ana göstergelerin verileri
		$this->headerInfo['anaBilgiler'] = array(
			'tik' 	=> $this->headerInfo['jsonGunler'][30],
			'yorum' => $this->Istatistik_model->say('yorum'),
			'urun'	=> $this->Istatistik_model->say('urun'),
			'yonSay'=> $this->Istatistik_model->say('admin')
		);

		//Donut verileri alınıyor
		$donutlar = array(
			'tara' => $this->Istatistik_model->satirler('Tarayıcı'),
			'os' => $this->Istatistik_model->satirler('Os'),
			'ref' => $this->Istatistik_model->satirler('Referans'),
			'mobil' => $this->Istatistik_model->satirler('Mobil')
		);

		//Donut verileri, donutların anlayacağı javascript dizilerine çevriliyor
		foreach ($donutlar as $key => $val) {
			$i=0;
			$ret = '';
			foreach ($val as $k => $v) {
				$i++;
				$this->headerInfo['donut'][$key] .= '{ label: \'' . $k . '\', data: ' . $v . ', color: \'#' . rand(100000, 999999) . '\' }' . (($i == count($val))?'':',');
			}
		}

		//En çok tıklananlar tablosu oluşturuluyor
		$this->load->library('table');
		$this->table->set_heading(array('Ad', 'Kategori', 'Alt Kategori', 'Görüntülenme'));
		$this->table->set_template(array('table_open' => '<table class="table no-margin">'));
		$u = new Urun();
		foreach ($this->Urun_model->enAl('unlu', 8) as $sonuc) {
			$this->table->add_row(
				'<a href="' . base_url('admin/urun/duzenle/') . $sonuc->id . '">' . $sonuc->ad . '</a> <a href="' . $u->urunLink($sonuc) . '" target="_blank" title="Ürünün sayfasına git"><i class="fa fa-link"></i></a>',
				$u->kateg[$sonuc->kategori],
				$u->altkateg[$sonuc->kategori][$sonuc->altkategori],
				$sonuc->goruntulenme
			);
		}
		$this->headerInfo['tiklananlarTablo'] = $this->table->generate();

		$this->load->view('admin/sayfa/istatistik', $this->headerInfo);
	}

	public function yapilacaklar()
	{
		$this->load->model('adminYapilacaklar_model');
		$this->viewData['degerler'] = $this->adminYapilacaklar_model->al($this->adminInfo->id);

		// Eğer üst yöneticiyse başkalarının yapılacaklar listesine ekleme yapabilir
		if (seviyesi_yuksek_mi(YONETICI_UST))
		{
			$this->viewData['adminler'] = $this->admin_model->tumAdminler('id', 'ad');
		}

		render_page('yapilacaklar', 'Yapılacaklar Listesi');
	}

	public function yardim()
	{
		render_page('yardim', 'Kullanım Kılavuzu ve Yardım');
	}

	public function mesaj()
	{
		$this->viewData['tumAdminler'] = $this->admin_model->tumAdminler();

		render_page('mesajlasma', 'Mesajlaşma', array(
			'ekleJS'			=> array ('dist/js/mesaj'),
			'footerExtra'	=> '<script type="text/javascript">
				var mesaj = new mesajClass(
					"' . $this->adminInfo->id . '",
					"' . $this->adminInfo->ad . '",
					"' . base_url() . '",
					document.getElementById("mesajAlani"), document.getElementById("mesajAlanAl").innerHTML,
					'. json_encode($this->viewData['tumAdminler']) .'
				);
				' . (isset($_GET['kim'])?'window.onload = mesaj.goster('. htmlspecialchars($_GET['kim']) .');':'') . '
				</script>'
		));
	}

}
