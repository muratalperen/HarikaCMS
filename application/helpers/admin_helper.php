<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('YONETICI_BAS', 100);
define('YONETICI_UST', 80);
define('YONETICI_MOD', 60);
define('YONETICI_NOR', 40);


/**
 * Yönetici Düzey Adı
 *
 * Verilen yönetici seviyesinden, yöneticinin düzey adını string olarak verir
 *
 * @param string	Yöneticinin seviyesi
 *
 * @return	string
 */
function yonetici_duzey_adi($yoneticiSeviye){
	switch ($yoneticiSeviye) {
		case YONETICI_NOR:
			return 'Yönetici';
			break;
		case YONETICI_MOD:
			return 'Moderatör';
			break;
		case YONETICI_UST:
			return 'Üst Yönetici';
			break;
		case YONETICI_BAS:
			return 'Baş Yönetici';
			break;

		default:
			return 'Böyle bir düzey yok! Bu durumu yöneticinize bildirin.';
			break;
	}
}

/**
 * Girmek İçin Gerekli Seviye
 *
 * Eğer yöneticinin seviyesi gerekli seviyeden düşükse ana sayfaya
 * yönlendirip, sayfaya erişim için yeterli izninin olmadığını söyler
 *
 * @param int	Yöneticinin seviyesi
 */
function girmek_icin_gerekli_seviye($seviye){
	if (get_instance()->adminInfo->duzey < $seviye) {
		bildirim_olustur(SONUC_UYARI, 'Az önce girmeye çalıştığınız sayfaya girmek için yetkiniz yok!', '');
  }
}

/**
 * Seviyesi Yüksek Mi
 *
 * Yöneticinin, verilen seviyeden yüksek olup olmadığını döndürür
 *
 * @param int	Yöneticinin seviyesi
 *
 * @return	boolean
 */
function seviyesi_yuksek_mi($seviye){
	return get_instance()->adminInfo->duzey >= $seviye;
}

/**
 * Uyar
 *
 * Admin paneli için bir uyarı paneli döndürür
 *
 * @param string	Bildirim içeriği
 * @param string	Uyarının durumunu belirtir. (s -> success, w -> warning, d -> danger)
 * @return	string
 */
function uyar($bd, $bildirim){
  return
	'<div class="alert alert-'.(($bd === SONUC_BASARI)?'success':(($bd === SONUC_UYARI)?'warning':'danger')).' alert-dismissible">
  	<button type="button" class="close" data-dismiss="alert" aria-hidden="true" >x</button>
  	<h4><i class="fa fa-'.(($bd === SONUC_BASARI)?'check':(($bd === SONUC_UYARI)?'warning':'ban')).'"></i>
		'.(($bd === SONUC_BASARI)?'Başarılı':(($bd === SONUC_UYARI)?'Uyarı':'Hata')).'</h4>' . $bildirim.'
	</div>';
}

/**
 * Bildirim Oluştur
 *
 * Admin paneli için uyarı oluşturur
 *
 * @param string	Sonuç (SONUC_HATA...)
 * @param string	Oluşturulacak bildirim yazısı
 * @param string	Bildirim sonrası isteğe bağlı yönlendirme
 */
function bildirim_olustur($sonucDurum, $bildirimIcerigi = null, $yonlendir = null)
{
	if (is_numeric($sonucDurum)) // Yeni bildirim sisteminde olması gereken bu
	{
		$bl = array($sonucDurum, $bildirimIcerigi);

	}
	else
	{
		// Eski yöntemi kullanıyor
		$olaySonucu = $sonucDurum[0];
		$sonucDurum[0] = ' ';
		$bl = array($olaySonucu, $sonucDurum);
		$yonlendir = $bildirimIcerigi;

	}

	get_instance()->session->set_flashdata ('bildirim', $bl);

	if ($yonlendir !== null)
	{
		// Yönlendirme parametresi tanımlanmışsa o sayfaya yönlendirir
		redirect(base_url('admin/' . $yonlendir));
	}

}
// Bu fonksiyonun eski hali
function bildirimOlustur($sonucDurum, $bildirimIcerigi = null, $yonlendir = null){
	return bildirim_olustur($sonucDurum, $bildirimIcerigi, $yonlendir);
}


/**
 * Geçen Zaman
 *
 * Alınan zaman üzerinden geçen zamanı söyler ("3 gün önce" gibi)
 *
 * @param string	Aradan geçmiş olacak zamanı alır.
 * @return	string
 */
function gecen_zaman($eskiZaman)
{
	if ($eskiZaman == null || gettype($eskiZaman) != 'string') { // Zaman biçimi bozuksa
		return 'Bilinmiyor';
	} elseif ($eskiZaman) {
		return 'xxx';
	}

}
function gecenZaman($eskiZaman)
{
	return gecen_zaman($eskiZaman);
}


########################################## YÖNETİM ##########################################

/**
 * Yönetici Ekle
 *
 * Yeni yönetici ekler. Sonuç yazısını döndürür
 *
 * @return	string
 */
function adminEkle ()
{
  $dosyaYolu = 'rel/img/admin/';
  $bu = get_instance ();
  $bu->load->model('admin_model');

	// ID de gönderildiyse bu güncelleme işlemidir
  $guncellemeIslemi = ! empty($_POST['id']);

  // Veritabanında güncelleme veya ekleme
  if ($guncellemeIslemi) {
    $id = $bu->admin_model->guncelle($_POST['id'], $_POST['ad'], $_POST['mail'], $_POST['duzey'], $_POST['sifre'], $_POST['hakkinda']);
  } else {
    $id = $bu->admin_model->ekle($_POST['ad'], $_POST['mail'], $_POST['duzey'], $_POST['sifre'], $_POST['hakkinda']);
  }

	try {

		if ($id == 0)
			throw new \Exception('Veritabanına veri girişi başarısız. İşlem iptal edildi.', SONUC_HATA);

		// Resim yüklenmemişse
		if (empty($_FILES['resim']['name']))
			return array(SONUC_BASARI, 'İşlem başarılı');

		$bu->load->library ('upload', array (
			'upload_path'		=> $dosyaYolu,
			'allowed_types'	=> 'jpg',
			'overwrite'     => TRUE,
			'file_name'			=> $id . '.jpg'
		));

		// Resmi yükle
		if ($bu->upload->do_upload('resim'))
		{
			return array(SONUC_BASARI, 'Herşey başarılı, ' . ( $guncellemeIslemi ? 'yönetici bilgileri güncellendi' : 'yeni yönetici eklendi' ) );

		}
		else // Resim yüklenemezse
		{
			if ($guncellemeIslemi)
			{
				throw new \Exception('Yönetici bilgileri güncellendi, ama resim güncellenemedi: ' . $bu->upload->display_errors(), SONUC_UYARI);

			}
			else // Resim yüklenemedi. Yeni yönetici eklendiyse
			{
				// Eklenen yöneticiyi veritabanından sil
				if ($bu->admin_model->sil($id))
				{
					throw new \Exception('Resim yüklenemedi. İşlem iptal edildi: ' . $bu->upload->display_errors(), SONUC_HATA);

				}
				else // Eklenen yönetici veritabanından silinemezse
				{
					throw new \Exception('Yönetici eklendi, ama resmi yüklenemedi: ' . $bu->upload->display_errors(), SONUC_HATA);

				}
			}
		}

	} catch (\Exception $e) {
		return array($e->getCode(), $e->getMessage());

	}
}


/**
 * Yönetici Sil
 *
 * Yönetici siler, sonucu yazı olarak döndürür.
 *
 * @param string	Aradan geçmiş olacak zamanı alır.
 * @param int			Silinen yöneticinin ürünlerinin gideceği yöneticinin ID (0: sil)
 * @return	string
 */
function adminSil ($id, $urunlerNereye){
  $dosyaYolu = 'rel/img/admin/';
  $bu = get_instance ();
  $bu->load->model('urun_model');

	// Veritabanından silinir
  if ($bu->admin_model->sil($id, $urunlerNereye))
	{
		// Yöneticinin resmi varsa
		if (file_exists($dosyaYolu . $id . '.jpg'))
		{
			// Yöneticinin resmini sil
			if(unlink($dosyaYolu . $id . '.jpg'))
			{
	      return array(SONUC_BASARI, 'Yönetici silindi');

	    }
			else
			{
	      return array(SONUC_UYARI, 'Yönetici silindi ancak resmi silinemedi. Lütfen mesaj vb. yöntemlerle bu durumu bildirin');
	    }
		}
		else // Yöneticinin zaten resmi yoksa
		{
			return array(SONUC_BASARI, 'Yönetici başarıyla silindi');
		}
  }else{
    return 'dVeritbanından silinemedi. İşlem iptal edildi';
  }
}
?>
