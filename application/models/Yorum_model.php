<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yorum_model extends CI_Model{

	public function tumAl($id)//ID'ye sahip urunun tüm yorumlarını verir
	{
		return $this
		->db
		->where('urunID', $id)
		->get('yorum')
		->result();
	}

	public function yorumYap($yanitID, $urunID, $ad, $mail, $site, $icerik)// Yorum yapar
	{
		do{
      $id = rand(100000000, 999999999);
      $idKontrol = $this->db->where('id', $id)->get('yorum')->result();
    }while ($idKontrol != null);

    $veri = array(
      'id'        => $id,
      'yanitID'   => empty($yanitID)?0:$yanitID,
      'urunID' 		=> $urunID,
      'ad'				=> htmlspecialchars($ad),
      'mail'      => htmlspecialchars($mail),
      'site'			=> htmlspecialchars($site),
      'tarih'			=> date('Y-m-d'),
      'incelendi'	=> 0,
      'icerik'		=> htmlspecialchars($icerik)
    );
		return $this->db->insert ('yorum', $veri)?$id:false;
	}

	public function gelismisAl($sadeceYeniler = false, $hangiUrune = null, $mail = null, $site = null, $icerik = null)
	{
		$whereArray = array();
		if ($sadeceYeniler) {
			$whereArray['incelendi'] =  0;
		}
		if ($hangiUrune != null) {
			$whereArray['urunID'] = $hangiUrune;
		}
		if ($mail != null) {
			$whereArray['mail'] = $mail;
		}
		// IDEA: Tarih eklenebilir
		if ($site != null) {
			$whereArray['site'] = $site;
		}
		if ($icerik != null) {
			$this->db->like('icerik', $icerik);
		}

		if (!empty($whereArray)) {
			$this->db->where($whereArray);
		}
		$tumYorumlar = $this->db->limit(100)->get('yorum')->result();
		for ($i=0; $i < count($tumYorumlar); $i++) {
			$tumYorumlar[$i]->yapilanYer = $this->db->select('ad, kategori, altkategori, sef')->where('id', $tumYorumlar[$i]->urunID)->get('urun')->result()[0];
			$yorumlarinIDleri[$i] = $tumYorumlar[$i]->id;
		}
		// DEBUG: Yenilere değilde normal bakanlara da incelendi yapar.
		if (!empty($yorumlarinIDleri)) {
			if (!$this->db->where_in('id', $yorumlarinIDleri)->update('yorum', array('incelendi' => 1))) {//Alınan yorumlar incelenmiş sayılır
				// DEBUG: Alınan yorumlar incelendi olamadı. Bildir
			}
		}
		return $tumYorumlar;
	}

	public function sil($id)
	{
		$return[0] = $this->db->delete('yorum', array('id' => $id));//Yorumun kendisini siler

		$idler = $this->db->select('id')->where('yanitID', $id)->get('yorum')->result();//Yorumun yanıtlarının yorum id'lerini al
		if (count($idler) != 0) {
			for ($i=0; $i < count($idler); $i++) {
				$ids[$i] = $idler[$i]->id;
			}
			$return[2] = $this->db->where_in('yanitID', $ids)->delete('yorum');//Yorumun yanıtlarının yanıtlarını sil
		}
		$return[1] = $this->db->delete('yorum', array('yanitID' => $id));//Yorumun yanıtlarını sil
		$return[0] = (isset($return[0])?$return[0]:true);
		$return[1] = (isset($return[1])?$return[1]:true);
		$return[2] = (isset($return[2])?$return[2]:true);
		return $return;
	}

	public function tabloTamir()//Karşılıksız yorumları siler
	{
		$tumYorumlar = $this->db->get('yorum')->result();
		$silme = true;
		$silinen = 0;
		foreach ($tumYorumlar as $yorum) {//Tüm yorumlar
			if ($yorum->yanitID != 0) {//Eğer bir yoruma yanıtsa
				if ($this->db->where('id', $yorum->yanitID)->get('yorum')->num_rows() == 0) {//Eğer yanıtı olduğu yorum yoksa
					$silme = ($this->db->delete('yorum', array('id' => $yorum->id)) && $silme);//Bu yanıtı silebiliriz
					$silinen++;
				}
			}
		}
		foreach ($tumYorumlar as $yorum) {
			if ($this->db->where('id', $yorum->urunID)->get('urun')->num_rows() == 0) {//Eğer bu yorumun yapıldığı bir şey
				$silme = ($this->db->delete('yorum', array('id' => $yorum->id)) && $silme);//Bu yorumu silebiliriz
				$silinen++;
			}
		}
		return array($silme, $silinen);
	}

}
