<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{

	/**
	 * Random ID
	 *
	 * Adı verilen tablodakilerle çakışmayacak ID oluşturup döndürür
	 *
	 * @param string	Tablo adı
	 * @param array		ID'nin değer aralığı
	 * @param string	Tabloda ID'ye verilen isim
	 *
	 * @return	integer
	 */
	function randID($tabloAdi, $aralik = array(10000, 65000), $idninAdi = 'id'){
		// IDEA: watch dog timer konulabilir
		do{
			// Verilen aralıklarda ID oluşturur
			$id = rand($aralik[0], $aralik[1]);
			// ID verilen tabloda var mı diye kontrol eder
			$idKontrol = $this->db->where($idninAdi, $id)->get($tabloAdi)->result();

		} while($idKontrol != null);

		return $id;
	}

	/**
	 * Header Bilgileri
	 *
	 * Yönetim panelinin üst tarafındaki başlıca bilgileri (Bildirim sayısı,
	 * yeni mesajlar, yeni yorum sayısı...) verir.
	 *
	 * @param integer	Üst bilgileri alacak yöneticinin ID
	 *
	 * @return	array
	 */
	public function headBilgileri($adminID)
	{
		return array(
			// İncelenmemiş yorum sayısı
			'yorum' => $this->db->where('incelendi', 0)->get('yorum')->num_rows(),
			// Son mesajlar ve okunmamış mesaj sayısı
			'mesaj'		=> array(
				'sayisi' => $this->db->where(array('alanID' => $adminID, 'okunmaDurum' => 0))->get('admin_mesajlar')->num_rows(),
				'sonMesajlar' => $this->db->where(array('alanID' => $adminID, 'okunmaDurum' => 0))->order_by('tarih', 'desc')->limit(4)->get('admin_mesajlar')->result()
				),
			// Bildirm sayıları
			'bildirim'=> array(
				$this->db->get('admin_bildirimler')->num_rows(),// FIXME: normal yönetici, üst düzey bildirimlerin sayısını görür
				$this->db->where('onem', 3)->or_where('onem', 4)->or_where('onem', 5)->get('admin_bildirimler')->num_rows(),
				$this->db->where('onem', 6)->or_where('onem', 7)->or_where('onem', 8)->get('admin_bildirimler')->num_rows(),
				$this->db->where('onem', 9)->or_where('onem', 10)->get('admin_bildirimler')->num_rows(),
				$this->db->where('onem', 11)->or_where('onem', 12)->get('admin_bildirimler')->num_rows()
			)
		);
	}

	/**
	 * Tüm Adminler
	 *
	 * Tüm yöneticilerin istenen verilerini verir
	 *
	 * @param string İstenen sütunların adları
	 *
	 * @return	object
	 */
	public function tumAdminler()
	{
		// Parametre verildiyse, yöneticilerin verilen parametredeki verilerini al
		if (func_num_args() > 0)
		{
			$this->db->select(implode(',', func_get_args()));
		}

		return $this
		->db
		->get('admin')
		->result();
	}

	/**
	 * Admini Al
	 *
	 * Verilen ID'ye sahip yöneticiyi döndürür
	 *
	 * @param integer	İstenen yöneticinin ID
	 *
	 * @return	object
	 */
	public function adminiAl($adminId)
	{
		return $this
		->db
		->where('id', $adminId)
		->get('admin')
		->result()[0];
	}

	/**
	 * Giriş Kontrol
	 *
	 * Verilen maildeki yöneticinin id ve şifresini döndürür
	 *
	 * @param integer	İstenen yöneticinin mail'i
	 *
	 * @return	object
	 */
	public function girisKontrol ($mail){
		return $this
		->db
		->select('sifre, id')
		->where('mail', $mail)
		->get('admin')
		->result()[0];
	}

	/**
	 * Sil
	 *
	 * Verilen ID'ye sahip yöneticiyi siler
	 *
	 * @param integer	Silinmesi istenen yöneticinin ID
	 * @param integer	Silinen yöneticinin ürünlerinin gideceği yer (0: sil)
	 *
	 * @return	object
	 */
	public function sil ($id, $tasi){
		// Yöneticiyi siler
		$adminSil = $this
		->db
		->where ('id', $id)
		->delete ('admin');

		// Yöneticinin mesajlarını siler
		$mesajSil = $this
		->db
		->where ('gonderenID', $id)
		->or_where ('alanID', $id)
		->delete ('admin_mesajlar');

		// Yöneticinin yapılacaklar verilerini siler
		$yapilacaklarSil = $this
		->db
		->where ('admininID', $id)
		->delete ('admin_yapilacaklar');

		// Taşı = 0'sa ürünleri silinir
		if ($tasi == 0)
		{
			// Silinecek ürünler'in ID'leri alınır
			$silinecekUrunler = $this->db->select('id')->where('yukleyen', $id)->get('urun')->result();
			for ($i=0; $i < count($silinecekUrunler); $i++)
			{
				$silinecekler[$i] = $silinecekUrunler[$i]->id;
			}

			// Ürünleri silinir
			$urunleriTasi = $this->db->delete('urun', array('yukleyen' => $id));
			// Ürünleri varsa
			if (!empty($silinecekler))
			{
				// Ürün join'ler de silinir
				$urunleriTasi = $urunleriTasi && $this->db->where_in('id', $silinecekler)->delete('urun_join');
			}

		}
		else
		{
			// Ürünlerin yükleyen bilgileri değiştirilir
			$urunleriTasi = $this
			->db
			->where('yukleyen', $id)
			->update('urun', array('yukleyen' => $tasi));

		}

		// Tüm işlemler başarılıysa TRUE döndürür
		return $adminSil && $mesajSil && $yapilacaklarSil && $urunleriTasi;

	}

	/**
	 * Güncelle
	 *
	 * Yöneticiyi verilen bilgilerle günceller. Sonuç başarılıysa yöneticinin
	 * ID'sini, başarısızsa 0 döndürür
	 *
	 * @param integer Yöneticinin ID
	 * @param string	Yöneticinin adı
	 * @param string	Yöneticinin mail adresi
	 * @param string	Yöneticinin düzeyi
	 * @param string	Yöneticinin şifresi
	 * @param string	Yöneticinin hakkında yazısı
	 *
	 * @return	integer
	 */
	public function guncelle($id, $ad, $mail, $duzey, $sifre, $hakkinda){
		// Güncellenecek bilgiler
		$guncelleData = array(
			'ad'    => $ad,
			'duzey' => $duzey,
			'mail'  => $mail,
			'sifre' => md5($sifre),
			'hakkinda'=> $hakkinda
		);
		// Güncelleme başarılıysa
		if ($this->db->where('id', $id)->update('admin', $guncelleData))
		{
			return $id;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Ekle
	 *
	 * Verilen bilgilerle yönetici ekler. Sonuç başarılıysa yöneticinin
	 * ID'sini, başarısızsa 0 döndürür
	 *
	 * @param string	Yöneticinin adı
	 * @param string	Yöneticinin mail adresi
	 * @param string	Yöneticinin düzeyi
	 * @param string	Yöneticinin şifresi
	 * @param string	Yöneticinin hakkında yazısı
	 *
	 * @return	integer
	 */
	public function ekle ($ad, $mail, $duzey, $sifre, $hakkinda){
		$id = $this->randID('admin', array(1,255));
		$insertData = array(
			'id'    => $id,
			'ad'    => $ad,
			'duzey' => $duzey,
			'mail'  => $mail,
			'sifre' => md5($sifre),
			'hakkinda'=>$hakkinda
		);

		if ($this->db->insert ('admin', $insertData)){
			return $id;
		}else{
			return 0;
		}
	}


	/**
	 * Yüklediği Ürün Sayısı
	 *
	 * ID'si verilen yöneticinin belirtilen tarihler arasında yüklediği
	 * ürün sayısını döndürür
	 *
	 * @param integer	Yöneticinin ID
	 * @param string	Başlangıç tarihi
	 * @param string	Bitiş tarihi
	 *
	 * @return	integer
	 */
	public function yukledigiUrunSayisi($adminID, $bas='', $son='')
	{
		if (!empty($bas)) {
			$whereData['tarih >'] = $bas;
		}
		if (!empty($son)) {
			$whereData['tarih <'] = $son;
		}
		$whereData['yukleyen'] = $adminID;

		return $this->db->where($whereData)->get('urun')->num_rows();
	}


	/**
		* Yüklediği Ürün Tıklanma Sayısı
		*
		* ID'si verilen yöneticinin belirtilen tarih aralığında yüklediği
		* ürünlerin toplam tıklanma sayısını döndürür
		*
		* @param integer	Yöneticinin ID
		* @param string	Başlangıç tarihi
		* @param string	Bitiş tarihi
		*
		* @return	integer
		*/
	public function yukledigiUrunTiklanmaSayisi($adminID, $bas='', $son='')
	{
		// Başlangıç ve bitiş tarihi verildiyse WHERE sorgusuna ekle
		if (!empty($bas))
		{
			$whereData['tarih >'] = $bas;
		}
		if (!empty($son))
		{
			$whereData['tarih <'] = $son;
		}
		// Yükleyen, adminin ID'si olsun
		$whereData['yukleyen'] = $adminID;

		// IDEA: direkt toplamını almamızı sağlayan sql kodu vardı
		$goruntulenmeler = $this->db->select('goruntulenme')->where($whereData)->get('urun')->result();
		$toplam = 0;
		foreach ($goruntulenmeler as $k) {
			$toplam += $k->goruntulenme;
		}
		return $toplam;
	}


	/**
		* Yüklediği Timeline
		*
		* ID'si verilen yöneticinin en son yüklediği ürünleri
		* admin panelinde Timeline'a uyacak şekilde verir
		*
		* @param integer	Yöneticinin ID
		*
		* @return	object
		*/
	public function yukledigiTimeline($admininID)
	{
		return $this
		->db
		->where(array('yukleyen' => $admininID))
		->order_by('tarih', 'desc')
		->limit(10)
		->get('urun')
		->result();
	}


	/**
		* Son Görülme Güncelle
		*
		*	ID'si verilen yöneticinin son görülme tarihini şimdi yapar
		*
		* @param integer	Yöneticinin ID
		*
		* @return	boolean
		*/
	public function sonGorulmeGuncelle($adminID)
	{
		return $this->db->where('id', $adminID)->update('admin', array('sonCevrimici' => date('Y-m-d')));
	}
}
