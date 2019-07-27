<?php

//  anaSayfa, hakkında, iletisim vs. sayfaları gösterir

defined('BASEPATH') OR exit('No direct script access allowed');

class Sayfa extends CI_Controller {


	/**
	 * Ana Sayfa
	 *
	 * Sitenin ana sayfası
	 *
	 */
	public function index()
	{
		if ($this->urI->segments[1] == 'sayfa' || $this->urI->segments[1] == 'Sayfa') { // ana sayfaya sayfa/ şeklinde geldiyse
			boyleBisiOlamaz();// Ana sayfaya gönder
		}

		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

		if ( ! $sayfaHTML = $this->cache->get('anaSayfa'))
		{
			//Ana Sayfa cache alınmamışsa cache al.

			$urun = new Urun();

			// Ana sayfada her kategoriden en ünlü 4 ürün gösterilir
			$this->viewData['kategoriVeri'] = array();
			for ($i=0; $i < $urun->kategoriSayisi; $i++) {
				$this->viewData['kategoriVeri'][$urun->kateg[$i]] = $this->Urun_model->enAl('unlu', 4, $i);
			}

			ob_start();
			render_page('sunumEkran', 'Ana Sayfa', array (
				'aciklama' => $this->config->item('site')->ad . ' ana sayfası',
				'yansiVeri'=> $this->Urun_model->enAl('unlu', 5),	// Yansı (slayt) verisi: en ünlü 5 ürün
				'yeniVeri'	=> $this->Urun_model->enAl('yeni', 3)	// Sağdaki yeni verisi: en yeni 4 ürün
			));
			$sayfaHTML = ob_get_contents();
			ob_end_clean();

			// Ana sayfayı yarım saatlik cachele
			$this->load->library ('session');
			if ( ! isset ($_SESSION['admin'])) { // Yönetici değilse cachele
				$this->cache->save('anaSayfa', $sayfaHTML, 1800);
			}
		}

		echo $sayfaHTML;
	}

	/**
	* Statik Sayfalar
	*
	* İletişim, hakkında gibi statik sayfaları gösterir
	*
	* @param string	Statik sayfanın adı
	*/
	public function statik($sayfa)
	{
		if (is_null($sayfa)) {
			boyleBisiOlamaz();
		}

		switch ($sayfa) {
			case 'iletisim':
			render_page('iletisim', 'İletişim', array(
				'aciklama'	=> $this->config->item('site')->ad . ' sitesine buradan ulaşabilirsiniz.'
			));
			break;

			case 'hakkinda':
			render_page('hakkinda', 'Hakkında', array(
				'aciklama'	=> $this->config->item('site')->ad . '. Hakkımda.'
			));
			break;

			case 'gizlilik':
			render_page('gizlilik', 'Gizlilik Politikası', array(
				'aciklama'	=> $this->config->item('site')->ad . ' Gizlilik politikası.'
			));
			break;

			default:
			boyleBisiOlamaz();
			break;
		}
	}

	/**
	* Sitemap
	*
	* Site sayfalarının bulunduğu site haritasını ve ürünlerin bulunduğu
	* ürün haritasını oluşturur. Routing ile açılır
	*
	* @param int	Site haritası (0) veya ürün haritası (1)
	*/
	public function sitemap($hangisi = 0)
	{
		if ($hangisi == 0) { // Sitemap
			// Seo ayarlarını json dosyasından alır
			$seoAyar = json_decode(file_get_contents(site_YOL . 'gizli/ayarlar.json'))->seo;

			// Haritaya ana sayfayı ekler
			$this->headerInfo['sayfalar'] = array(array('adres' => base_url(), 'tarih' => $seoAyar->sonDegistirme, 'onem' => 1, 'degisme' => 'daily'));

			// Uygulamalar'ı sayfaya ekler
			foreach ($this->db->get('uygulama')->result() as $uygulama) {
				array_push($this->headerInfo['sayfalar'], array('adres' => base_url('sayfa/uygulamalarim/') . $uygulama->sef, 'tarih' => '2018-10-01T09:00:04+00:00', 'onem' => '0.9', 'degisme' => 'never'));
			}

			$urun = new Urun();

			for ($i = 0; $i < count($urun->Tkateg); $i++) {
				// Kategorileri site haritasına ekler
				if ($seoAyar->kategori != 0) {
					array_push($this->headerInfo['sayfalar'], array(
						'adres' => base_url($urun->Tkateg[$i]),
						'tarih' => $seoAyar->sonDegistirme,
						'onem' => $seoAyar->kategori,
						'degisme' => 'weekly'
					));
				}

				// Alt kategorileri site haritasına ekler
				if ($seoAyar->altkategori != 0) {
					foreach ($urun->Taltkateg[$i] as $altKategoriAd) {
						array_push($this->headerInfo['sayfalar'], array(
							'adres' => base_url($urun->Tkateg[$i]) . '/' . $altKategoriAd,
							'tarih' => $seoAyar->sonDegistirme,
							'onem' => $seoAyar->altkategori,
							'degisme' => 'monthly'
						));
					}
				}
			}

			// Statik sayfalar eklenir
			if ($seoAyar->sayfalar != 0) {
				foreach (array('iletisim', 'hakkinda', 'gizlilik', 'uygulamalarim') as $sayfAdi) {
					array_push($this->headerInfo['sayfalar'], array(
						'adres' => base_url('sayfa/') . $sayfAdi,
						'tarih' => $seoAyar->sonDegistirme,
						'onem' => $seoAyar->sayfalar,
						'degisme' => 'yearly'
					));
				}
			}

			$this->headerInfo['ekSayfa'] = $seoAyar->ekMap;
			$this->load->view('sayfa/siteHaritasi', $this->headerInfo);

		} else {
			// Ürün haritası oluşturulacak. Veritabanından tüm ürünler alınıyor
			$this->headerInfo['urunler'] = $this->Urun_model->urunMapAl();

			// Seo ayarlarından ürünlerin önem'i alınıyor
			$this->headerInfo['urunOnem'] = json_decode(file_get_contents(site_YOL . 'gizli/ayarlar.json'))->seo->urunOnem;
			$this->headerInfo['degisme'] = 'yearly';

			$this->load->view('sayfa/siteHaritasi', $this->headerInfo);
		}

	}


	/**
	* Ara
	*
	* Site içi arama sayfası
	*/
	public function ara()
	{
		if(empty($_GET['ad'])){ // Arama yapılmadıysa ana sayfaya gönder
      boyleBisiOlamaz();
    }

		// Arama özel karakterlerden ayrılır ve iki yanındaki boşluklar silinir
    $ad = htmlspecialchars(trim($_GET['ad']));

    $this->viewData = array(
      'aranan'				=> $ad,
			// Aramanın ilk harfi (') ise hack denemesi olabilir (SQL açığı)
			'hackdenemesi'	=> ($_GET['ad'][0] == '\''), // Aramanın ilk karakteri ' ise
			'aramaSonuc'		=> $this->Urun_model->ara($ad)
    );

		render_page('arama', $ad . ' Araması İçin Sonuçlar', array(
			'aciklama'	=> $ad . ' araması için sonuçlar.'
		));
	}

	/**
	* Hata Sayfaları
	*
	* Hata sayfaları
	*
	* @param string Hata kodu
	*/
	public function hata($hataAd='404')
	{
		$hataAd = htmlspecialchars($hataAd);

		$hatalar = array(// NOTE: buradaki hataları htaccesse de ekle
			'404' => 'Sayfa bulunamadı.',
			'403' => 'Bu sayfaya erişim izniniz bulumamaktadır',
			'500'	=> 'Bir sunucu hatası',
			'502'	=> 'Kötü geçit (bad gateway) hatası. Bu durum kullandığınız vpn, proxy, dns hatta tarayıcıdan bile kaynaklanıyor olabilir.'
		);
		foreach ($hatalar as $key => $value) {//Tüm hatalarda
			if ($key == $hataAd) {//Hata buysa
				$hataAciklama = $value;
				break;
			}
		}

		if ( ! isset($hataAciklama)) { // Böyle bir hata yok!
			bildir(3, 'Olmayan Hata Sayfası', $hataAd . ' hatası geldi ama böyle bir hata tanımlanmamış.');
			boyleBisiOlamaz();
		}

		$hataData = array(
			'meta' => array(
				'baslik'		=> $hataAd . ' hatası :(',
				'aciklama'	=> $hataAd . ' hatası. ' . $hataAciklama,
				'taglar'		=> $hataAd . ',hata,error'
			),
			'hataNumarasi'=> $hataAd,
			'hataBaslik'	=> $hataAciklama,
			'hataBildir'	=> bildir(3, $hataAd . ' Hatası', 'Biri &quot;' . $hataAd . '&quot; hatası aldı.' . (is_bot() ? ' Üstelik bu bir bot (' . htmlspecialchars($_SERVER['user_agent']) . ')':'') )
		);
		$this->load->view('sayfa/hata', $hataData);
	}

	public function a()
	{
		// header('Content-Type: image/jpg');

		$this->load->library('image_lib', array(
			'image_library' => 'gd2',
			'source_image'=> 'rel/img/captcha/1564076614.8035.jpg',
			'dynamic_output' => true,
			'maintain_ratio' => true,
			'width' => 128
		));
		echo $this->image_lib->resize();
	}


}
