<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Istatistik_model extends CI_Model{

	/**
	 * Ekle
	 *
	 * Kullanıcı bir sayfaya geldiğinde bu çalışır ve istatistik
	 * oluşturmak için veri girer
	 *
	 * @param int			Baktığı ürünün ID'si
	 * @param	string	Kullanıcının tarayıcısı
	 * @param	string	Kullanıcının işletim sistemi
	 * @param	boolean	Kullanıcını mobil cihazdan mı giriyor
	 * @return	boolean
	 */
  public function ekle($baktigi, $tarayici, $os, $mobilmi)
  {
    // IDEA: Utm kullan (?utm_source=GitHub&utm_medium=website&utm_campaign=GitHub)
  	return $this->db->insert('istatistik', array(
			'baktigi' => $baktigi,
			'ref'			=> isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
			'tarih'		=> date('Y-m-d H:i:s'),
			'tarayici'=> $tarayici,
			'os'			=> $os,
			'mobilmi'	=> $mobilmi
		));
  }


	/**
	 * Tarihindeki Satır Sayısı
	 *
	 * Belirtilen tarihler arasındaki satır sayısını döndürür
	 *
	 * @param	string	Arama yapılacak tablonun adı
	 * @param	string	Hangi tarihten itibaren,
	 * @param	string	Hangi tarihe kadar
	 * @return	int
	 */
	public function tarihindeSay($ne, $bas='', $son='')
	{
		if (empty($bas)) {
			$bas = date('Y-m-d') . '00:00:00';
		}
		if (empty($son)) {
			$son = date('Y-m-d H:i:d');
		}

		return $this
		->db
		->where(array(
			'tarih >' => $bas,
			'tarih <' => $son
		))
		->get($ne)
		->num_rows();
	}

	/**
	 * say
	 *
	 * Verilen tablodaki satır sayısını döndürür.
	 *
	 * @param string Tablo adı
	 *
	 * @return	int
	 */
	public function say($tablo)
	{
		return $this->db->get($tablo)->num_rows();
	}

	/**
	 * Satır Sayıları
	 *
	 * Belirtilen sütunda hangi verinin kaç tane olduğunu döndürür.
	 *
	 * @param	string	Sütun adı
	 * @return	array
	 */
	public function satirler($sutun)
	{
		if ($sutun == 'Tarayıcı') {
			return array(
				'Chrome'	=> $this->db->where('tarayici', 'Chrome')->get('istatistik')->num_rows(),
				'Internet Explorer'	=> $this->db->where('tarayici', 'Internet Explorer')->get('istatistik')->num_rows(),
				'Firefox'	=> $this->db->where('tarayici', 'Firefox')->get('istatistik')->num_rows(),
				'Safari'	=> $this->db->where('tarayici', 'Safari')->get('istatistik')->num_rows(),
				'Opera'	=> $this->db->where('tarayici', 'Opera')->get('istatistik')->num_rows(),
				'Unknown'	=> $this->db->where('tarayici', 'noBrowser')->get('istatistik')->num_rows()
			);

		} elseif ($sutun == 'Os') {
			return array(
				'Linux'	=> $this->db->where('os', 'Linux')->get('istatistik')->num_rows(),
				'Windows 7'	=> $this->db->where('os', 'Windows 7')->get('istatistik')->num_rows(),
				'Android'	=> $this->db->where('os', 'Android')->get('istatistik')->num_rows(),
				'iOS'	=> $this->db->where('os', 'iOS')->get('istatistik')->num_rows(),
				'Unknown Platform'	=> $this->db->where('os', 'Unknown Platform')->get('istatistik')->num_rows()
			);

		}elseif ($sutun == 'Referans') {
			$ret = array(
				'Google'		=> $this->db->like('ref', 'google')->get('istatistik')->num_rows(),
				'Yandex'		=> $this->db->like('ref', 'yandex')->get('istatistik')->num_rows(),
				'Duckduckgo'=> $this->db->like('ref', 'duckduckgo')->get('istatistik')->num_rows(),
				'Yahoo'			=> $this->db->like('ref', 'yahoo')->get('istatistik')->num_rows(),
				'Bing'			=> $this->db->like('ref', 'bing')->get('istatistik')->num_rows(),
				'Facebook'	=> $this->db->like('ref', 'facebook')->get('istatistik')->num_rows(),
				'Site İçi'	=> $this->db->like('ref', base_url())->get('istatistik')->num_rows(),
				'Direkt'		=> $this->db->where('ref', '')->get('istatistik')->num_rows()
			);
			$toplam = 0;
			foreach ($ret as $a) {
				$toplam += $a;
			}
			$ret['Diğer'] = ( $this->db->get('istatistik')->num_rows() - $toplam);
			return $ret;

		}elseif ($sutun == 'Mobil') {
			return array(
				'Mobil' => $this->db->where('mobilmi', 1)->get('istatistik')->num_rows(),
				'Masaüstü' => $this->db->where('mobilmi', 0)->get('istatistik')->num_rows()
			);

		} else {
			return array();

		}
	}

	/**
	 * Günlerdeki Tıklama
	 *
	 * Son 30 gündeki tıklanma sayılarını döndürür.
	 *
	 * @return	array
	 */
	public function son30GunTiklama()
	{
		$ret = array();
		for ($i=0; $i < 30; $i++) {
			$ret[30 - $i] = $this
			->db
			->where(
				array(
					'tarih >' => date('Y-m-d H:i:s', mktime(0,0,0, date('m'), date('d') - $i - 1, date('Y'))),
					'tarih <' => date('Y-m-d H:i:s', mktime(23,59,59, date('m'), date('d') - $i, date('Y')))
				)
			)
			->get('istatistik')
			->num_rows();
		}

		return $ret;
	}

	/**
	 * Son 4 Ay Ürün Yükleme
	 *
	 * Son 4 aydaki ürün yükleme sayılarını haftalar halinde döndürür.
	 *
	 * @param	int Son 4 aydaki ürün yükleme sayısı istenen adminin ID
	 * @return	array
	 */
	public function son4AyYukleme($adminID)
	{
		$ret = array();
		for ($i=0; $i <= 12; $i++) {
			$ret[12 - $i] = $this
			->db
			->where(
				array(
					'yukleyen'=> $adminID,
					'tarih <' => date('Y-m-d H:i:s', mktime(23,59,59, date('m'), date('d') - $i * 7, date('Y'))),
					'tarih >' => date('Y-m-d H:i:s', mktime(0,0,0, date('m'), date('d') - ($i+1) * 7, date('Y')))
				)
			)
			->get('urun')
			->num_rows();
		}

		return $ret;
	}

	/**
	 * Giriş Saatleri
	 *
	 * Gün içinde hangi saatlerde ne yoğunlukta girildiğini gösterir. (Üç saat aralıklarla)
	 *
	 * @return	array
	 */
	public function girisSaatleri($value='')
	{
		$ret = array();
		for ($i=0; $i < 8; $i++) {
			$ret[$i] = $this
			->db
			->query('SELECT count(tarih) as say FROM istatistik WHERE HOUR(tarih) BETWEEN ' . ($i*3) . ' AND ' . (($i*3)+3))
			->result()[0]
			->say;
		}
		return $ret;
	}

	/**
	 * Tabloyu Temizle
	 *
	 * (Şimdilik) istatistikler tablosundaki 4 aydan eski verileri siler
	 *
	 * @return	boolean
	 */
	public function tabloyuTemizle()
	{
		return $this
		->db
		->delete('istatistik', array(
			'tarih <' => date('Y-m-d H:i:d', mktime(0,0,0, date('m') - 4, date('d'), date('Y')))
		));
	}

	/**
	 * Csv Olarak Al
	 *
	 * İstatistikler tablosu verilerini csv türüne dönüştürür
	 *
	 * @return	string
	 */
  public function csvOlarakAl()
  {
		$veri = $this->db->get('istatistik')->result();
		$ret = 'Baktığı,Referans,Tarih,Tarayıcı,İşletim Sistemi,Mobil Cihaz Mı' . "\n";
		foreach ($veri as $value) {
			$ret .= "$value->baktigi,$value->ref,$value->tarih,$value->tarayici,$value->os,$value->mobilmi\n";
		}
		return $ret;
	}

}
