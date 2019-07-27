<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class U extends CI_Controller{

  public function index ($kategori = null, $altkategori = null, $ad = null, $parametre = null)
  {
		if (ucfirst($this->urI->segments[1]) == "U") {// Linkte /u/ olarak geldiyse u'suza gönder
			redirect(base_url() . substr($this->urI->uri_string, 2));
		}
    if($kategori == null){// site/u/ diyerek gelmiştir ki, böyle bir sayfa yok
      boyleBisiOlamaz();
    }

		$urun = new urun();

		for ($i=0; $i < $urun->kategoriSayisi; $i++) {  // kategorinin id'sini bul
			if ($urun->Tkateg[$i] == $kategori) {
				$kategoriID = $i;
				break;
			}
		}
		if (!isset($kategoriID)) {  // öyle bir kategori yoksa
			boyleBisiOlamaz();
		}
		if ($altkategori != null){  // site/kategori/altkategori ise altkategorinin id'sini bul
			for ($i = 0; $i < count ($urun->Taltkateg[$kategoriID]); $i++) {
				if ($urun->Taltkateg[$kategoriID][$i] == $altkategori) {
					$altkategoriID = $i;
					break;
				}
			}
			if(!isset($altkategoriID)){ // öyle bir altkategori yoksa kategoriye gönder
				redirect(base_url($kategori));
			}
		}


		$this->load->model('Urun_model');

		if ($ad == null) { // Kategori veya altkategori gösterilecek

			// Kategori gösterilecek
			if (is_null($altkategori))
			{
				$urun = new Urun();

				// Ana sayfada her kategoriden en ünlü 4 ürün gösterilir
				$this->viewData['kategoriVeri'] = array();
				for ($i=0; $i < count($urun->altkateg[$kategoriID]); $i++) {
					$this->viewData['kategoriVeri'][$urun->altkateg[$kategoriID][$i]] = $this->Urun_model->enAl('unlu', 4, $kategoriID, $i);
				}

				render_page('sunumEkran', $urun->kateg[$kategoriID], array (
					'aciklama' 	=> $this->config->item('site')->ad . ' sitesi ' . $urun->kateg[$kategoriID] . ' kategorisindekiler.',
					'yansiVeri'	=> $this->Urun_model->enAl('unlu', 4, $kategoriID),	// Yansı (slayt) verisi: en ünlü 5 ürün
					'yeniVeri'	=> $this->Urun_model->enAl('yeni', 3, $kategoriID)	// Sağdaki yeni verisi: en yeni 4 ürün
				));

			}
			else // Alt kategoridekiler gösterilecek
			{
				$this->viewData['liste'] = $this->Urun_model->kategSirala($kategoriID, $altkategoriID);

				render_page('vitrin', $urun->kateg[$kategoriID], array (
					'aciklama' 	=> $this->config->item('site')->ad . ' sitesi ' . $urun->altkateg[$kategoriID][$altkategoriID] . ' alt kategorisindekiler.',
					'yansiVeri'	=> $this->Urun_model->enAl('unlu', 4, $kategoriID, $altkategoriID),	// Yansı (slayt) verisi: en ünlü 5 ürün
					'yeniVeri'	=> $this->Urun_model->enAl('yeni', 3, $kategoriID, $altkategoriID)	// Sağdaki yeni verisi: en yeni 4 ürün
				));
			}


      // NOTE: Şimdilik ürün dışındaki sayfalarda istatistik devredışı bırakıldı
      // Yönetici değilse istatistik tut
			// if ( ! isset($this->session->get_userdata ()['admin'])){//Admin değilse
			// 	$this->load->library('user_agent');
			// 	$this->load->model('Istatistik_model');
			// 	$this->Istatistik_model->ekle(
			// 		($kategoriID . ( ($altkategoriID === null) ? '' : $altkategoriID )),// DEBUG: Buraya çözüm bul. İlk kategori id 0 olunca numara yaparken yok ediyor
			// 		( $this->agent->is_browser()? $this->agent->browser() : 'noBrowser' ),
			// 		$this->agent->platform(),
			// 		$this->agent->is_mobile()
			// 	);
			// }

		}
		else // Urun ile ilgili işlem olacak
		{

			if (empty($sonuc = $this->Urun_model->addanBul($ad))) // Böyle bir ürün yoksa
			{
				bildir(4, 'Bir ürün bulunamadı', '&quot;' . htmlspecialchars($urun->urunLink($kategoriID, $altkategoriID, $ad)) . '&quot; adresindeki ürün bulunamadı. Yönlendiren: ' . $_SERVER['HTTP_REFERER']);
				show_404();
			}
			else // Ürün ile ilgili işlemler
			{

				if ($parametre == 'resim') // Resmi Göster
				{
					// $this->output->set_content_type('image/jpg'); Çalışmıyor

					if (file_exists(site_YOL . 'dosya/img/' . $sonuc->id . '.jpg')) // İstenen ürünün resmi varsa
					{
						header('Content-Type: image/jpg');
						echo file_get_contents(site_YOL . 'dosya/img/' . $sonuc->id . '.jpg');
					}
					else // İstenen ürünün resmi yoksa
					{
						bildir(5, 'Ürün resmi bulunamadı', '&quot;' . htmlspecialchars($urun->urunLink($kategoriID, $altkategoriID, $ad)) . '&quot; adresindeki resim bulunamadı. Yönlendiren: ' . $_SERVER['HTTP_REFERER']);
						show_404($urun->urunLink($kategoriID, $altkategoriID, $ad), false);
					}

		    }
				elseif ($parametre == 'thumb') // Thumbnail göster
				{

					if (file_exists(site_YOL . 'dosya/img/' . $sonuc->id . '.jpg')) // Resim varsa
					{
						// NOTE: yaklaşık 35ms sürüyor
						header('Content-Type: image/jpg');

						$this->load->library('image_lib', array(
							'image_library' => 'gd2',
							'source_image'=> site_YOL . 'dosya/img/' . $sonuc->id . '.jpg',
							'dynamic_output' => true,
							'maintain_ratio' => true,
							'width' => 128
						));
						echo $this->image_lib->resize();

					}
					else // Resim yoksa
					{
						bildir(5, 'Ürün resmi bulunamadı', '&quot;' . htmlspecialchars($urun->urunLink($kategoriID, $altkategoriID, $ad)) . '&quot; adresindeki resim bulunamadı. Yönlendiren: ' . $_SERVER['HTTP_REFERER']);
						show_404($urun->urunLink($kategoriID, $altkategoriID, $ad), false);
					}

		    }
				else // Ürün sayfasını göster
				{

					// Ön Tanımlamalar
					$tanimlar = array(
						'rD'		=> base_url('dosya/icerik/resim/'),
						'dD'		=> base_url('dosya/icerik/dosya/'),
						'sD'		=> base_url(),
						'code'	=> '<pre class="prettyprint linenumber"><code>',
						'/code'	=> '</code></pre>'
					);
					// Ön tanımlamalar uygulanıyor
					foreach ($tanimlar as $key => $value)
					{
						$sonuc->icerik = str_replace('{' . $key . '}', $value, $sonuc->icerik);
					}

					// İçerik Markdown çevrilir
					$this->load->library('markdown');
					$sonuc->icerik = $this->markdown->parse($sonuc->icerik);

					// DEBUG: Resimlerin etrafına p koyuyor. Onu kaldır.
					// $sonuc->icerik =  preg_replace("#(<p><[^<]*</p>)#", '<$1', $sonuc->icerik);

					$this->viewData = array(
						'onerilenler' => $this->Urun_model->enAl('unlu', 5, $sonuc->kategori, $sonuc->altkategori),
		        'buUrun' => $sonuc
					);

					render_page('urun', $sonuc->ad, array(
						'aciklama'		=> $sonuc->aciklama,
						// 'ekleCSS'			=> array('yazi'),
						'footerExtra'	=>
							'<script type="text/javascript">'.
							(isset($_GET['yorum']) ? (($_GET['yorum'] == "false") ? 'bildirim("Yorum Yapma İşlemi Başarısız", "Yorum yapma işlemi başarısız. Lütfen tekrar deneyin. Sorun devam ederse site yöneticisine bildirin.", "d");' : 'yorumlariYukle(' . $_GET['yorum'] . ');' ) : '') .
							(isset($_GET['abone']) ? (($_GET['abone'] == "1") ? 'bildirim("Abone Olma Başarılı!", "E-posta bilgilendirme sistemine kayıt oldunuz!", "s");' : 'bildirim("Abone Olma Başarısız", "Abone olma işlemi başarısız. Lütfen tekrar deneyin. Veya <a href=\'' . base_url() . 'sayfa/statik/iletisim\'></a> bölümünden bize yazın.", "d");') : '') .
							'</script>'
					));

          // Giren yönetici değilse görüntülenme arttırılıyor
					if ( ! isset($this->session->get_userdata ()['admin']))
						$this->Urun_model->goruntulenmeArttir($sonuc->id);

					// Giren yönetici değilse ve istatistik tutma config'den açıksa istatistik tutulur
					if ( ! isset($this->session->get_userdata ()['admin']) && $this->config->item('istatistik_tut'))
					{
						$this->load->library('user_agent');
						$this->load->model('Istatistik_model');
						$this->Istatistik_model->ekle(
							$sonuc->id,
							( $this->agent->is_browser() ? $this->agent->browser() : 'noBrowser' ),
							$this->agent->platform(),
							$this->agent->is_mobile()
						);
					}

				}

			}

		}
	}
}
