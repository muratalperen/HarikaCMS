<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ürün Ekle
 *
 * Ürün Ekler
 *
 * @param string Yükleyen yöneticinin ID
 * @param string Ürünün adı
 * @param string Ürünün kategori ID
 * @param string Ürünün altkategori ID
 * @param string Ürün join tablosu için ek veriler
 *
 * @return string
 */
function urunEkle ($yukleyen, $ad, $kategori, $altkategori, $ek){
  $dosyaYolu = 'dosya/';
  $CI = get_instance ();
  $CI->load->model('urun_model');
	// Ürün, veritabanına yükleniyor
  $id = $CI->urun_model->ekle($ad, $kategori, $altkategori, $yukleyen, $ek);

	if ($id === false){
		return array(SONUC_HATA, 'Veritabanına veri girişi başarısız. İşlem iptal edildi.');
	}

	$resimYukleSonuc = urunResmiYukle($id, (dosyaGonderildiMi('resim')?'resim':$_POST['resimLink']));
	if ($resimYukleSonuc[0] === SONUC_BASARI) {
		return array(SONUC_BASARI, 'Ürün başarıyla yüklendi.');
	} else {
		// Resim yüklenemediyse yüklediğimiz ürünü tekrar veritabanından siliyoruz
		if ($CI->urun_model->sil($id)){
			return array(SONUC_HATA, 'Ürün resmi yüklenemedi. ' . $resimYukleSonuc[1] . ' İşlem iptal edildi: ' . $CI->upload->display_errors());
		}else{
			return array(SONUC_UYARI, 'Veritabanına yüklendi, resim yüklenemedi: ' . $CI->upload->display_errors() . '. En kısa sürede resim yükleyin.');
		}
	}
}

/**
 * Ürün Düzenle
 *
 * Ürün düzenler, günceller, değişiklik yapar
 *
 * @param string Yükleyen yöneticinin ID
 * @param string Ürünün adı
 * @param string Ürünün kategori ID
 * @param string Ürünün altkategori ID
 * @param string Ürün join tablosu için ek veriler
 *
 * @return string
 */
function urunDuzenle ($id, $ad, $kategori, $altkategori, $ek){
  $CI = get_instance ();
  $CI->load->model('urun_model');
	if ( ! $CI->urun_model->guncelle($id, $ad, $kategori, $altkategori, $ek)){
		return array(SONUC_HATA, 'Veritabanına veri girişi başarısız. İşlem iptal edildi.');
	}

	if(dosyaGonderildiMi('resim')){
		$resimSonuc = urunResmiYukle($id, 'resim');
		if ($resimSonuc[0] === SONUC_BASARI) {
			return array(SONUC_BASARI, 'Ürün başarıyla güncellendi.');
		}else{
			return array(SONUC_UYARI, 'Veritabanı bilgileri güncellendi ancak resim güncellenemedi: ' . $resimSonuc[1]);
		}

	} else {
		return array(SONUC_BASARI, 'Ürün başarıyla güncellendi.');
	}
}

/**
 * Ürün Sil
 *
 * Verilen ID'deki ürünü siler
 *
 * @param int Silinecek ürünün ID
 *
 * @return string
 */
function urunSil ($id)
{
  $CI = get_instance ();
  $CI->load->model('urun_model');

  if (!$CI->urun_model->sil($id))
		return array(SONUC_HATA, 'Veritbanı işlemlerinde bir hata. Ürün ve/veya ürünün yorumları silinememiş olabilir. İşlem iptal edildi.');

  // Eğer resim varsa
	if( ! file_exists('dosya/img/' . $id . '.jpg'))
		return array(SONUC_BASARI, 'Ürün başarıyla silindi.');

  // Resmi sil
	if (unlink ('dosya/img/' . $id . '.jpg'))
  {
  	return array(SONUC_BASARI, 'Ürün başarıyla silindi.');

  }
  else
  {
    // Resim silinemediyse
    onemliRapor (7, 'Dosya silinemedi.', 'Ürün veritabanından silindi, ama ana resmi silinemedi. İd: ' . $id);
    return array(SONUC_UYARI, 'Ürün verileri başarıyla silindi ancak ana resmi silinemedi. Bunu üst yöneticiler düzeltir.');
	}
}


/**
 * Ürün Resmi Yükle
 *
 * Ürün için ana resmi yükler
 *
 * @param int 		Ürünün ID
 * @param string	Ürünün post edilme adı veya resim linki
 *
 * @return array
 */
function urunResmiYukle($id, $resimAd)
{
	$uploadConfig = array (
		'upload_path'   => 'dosya/img/',
		'allowed_types' => 'jpg',
    'overwrite'     => TRUE,
		'file_name'     => $id . '.jpg'
	);

	try {

    // Resim Post Edildiyse
		if (dosyaGonderildiMi($resimAd))
    {
		  $CI = get_instance();

			// Upload kütüphanesini yüklüyoruz
			if ( ! $CI->load->is_loaded('upload'))
				$CI->load->library ('upload');

		  $CI->upload->initialize ($uploadConfig);
		  if ($CI->upload->do_upload($resimAd))
		    return array(SONUC_BASARI, 'Resim başarıyla yüklendi.');
		  else
				throw new \Exception('Resim yüklenirken hata oluştu.', SONUC_HATA);

		}
    else // Değilse resim linki gönderilmiştir
    {
      if (empty($resim = curl($resimAd)))
				throw new \Exception('İnternetten resim alınamadı.', SONUC_HATA);

			// NOTE: Meta data düzenle
			if (file_put_contents($uploadConfig['upload_path'] . $uploadConfig['file_name'], $resim))
				return array(SONUC_BASARI, 'Resim başarıyla yüklendi.');
			else
				throw new \Exception('Alınan resim "' . $uploadConfig['upload_path'] . $uploadConfig['file_name'] . '" adıyla yüklenemedi.', SONUC_HATA);
		}

	} catch (\Exception $e) {
		return array($e->getCode(), $e->getMessage());
	}

}

/**
 * Dosya Gönderildi mi
 *
 * Verilen adda dosya gönderildi mi
 *
 * @param string	dosyanın formda gönderilme adı
 *
 * @return boolean
 */
function dosyaGonderildiMi($postAdi)
{
	return $_FILES[$postAdi]['error'] === 0;
}
?>
