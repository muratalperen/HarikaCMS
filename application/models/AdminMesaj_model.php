<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminMesaj_model extends CI_Model{

	public function ekle($gonderenID, $alanID, $mesaj){
		if ($gonderenID == $alanID) {
			return false;
		}
		do{
			$id = rand(10000, 65000);
			$idKontrol = $this->db->where('id', $id)->get('admin_mesajlar')->result();
		}while ($idKontrol != null);

		$insertData = array(
			'id'    => $id,
			'gonderenID'	=> $gonderenID,
			'alanID' => $alanID,
			'tarih' => date('Y-m-d H:i:s'),
			'silmeDurum' => 0,
			'okunmaDurum' => 0,
			'icerik' => $mesaj
		);
		if ($this->db->insert ('admin_mesajlar', $insertData)){
			return $insertData;
		}else{
			return array();
		}
	}


	public function sonMesajlar($kimID, $kimleID, $sadeceYeniler = false, $tamami = false)
	{
		if ($sadeceYeniler) {//Sadece yeni, okunmamış mesajlar
			$this
			->db
			->where(array('gonderenID' => $kimleID, 'alanID' => $kimID, 'okunmaDurum' => 0));
		} else {//Mesaj geçmişini gösterecek kadar mesaj
			$this
			->db
			->where(array(
					'gonderenID' => $kimID,
					'alanID' => $kimleID,
					'silmeDurum !=' => 1
			))//Benim gönderdiklerim
			->or_where('gonderenID', $kimleID)->where(array(
				'alanID' => $kimID,
				'silmeDurum !=' => 2
			));//Onun gönderdikleri
			if (!$tamami) {
				$this->db->limit(20);
			}
		}

		$return = $this
		->db
		->order_by('tarih', 'desc')
		->get('admin_mesajlar')
		->result();
		//echo $this->db->last_query();
		//SELECT * FROM `admin_mesajlar` WHERE `gonderenID` = '1' AND `alanID` = '61' AND `silmeDurum` != 1 OR `gonderenID` = '61' OR `alanID` = '1' OR `silmeDurum` != 2  ORDER BY `tarih` ASC LIMIT 20

		//Mesajlar alındığına göre, okundu yap.
		if (!empty($return)) {
			$okunduYap = $this
			->db
			->where('gonderenID', $kimleID)
			->update('admin_mesajlar', array('okunmaDurum' => 1));

			if (!$okunduYap) {
				onemliRapor(2, 'Mesaj okundu olarak işaretlenemedi.', $kimID . ' id\'li kullanıcı, '. $kimleID .' id\'li kullanıcı ile mesajlaşırken mesaj okundu olarak işaretlenemedi.');
			}
		}

		return $return;
	}


	public function sil($msjinID, $herkesden = null)
	{
		function tamSil($msjinID)
		{	//Mesajı veritabanından siler
			return get_instance ()->db->delete('admin_mesajlar', array('id' => $msjinID));
		}

		if ($this->db->where('id', $msjinID)->get('admin_mesajlar')->result()[0]->gonderenID == $this->adminInfo->id) {//Mesajın sahibi silmek istiyorsa
			if ($herkesden == 1) {//Herkesden sil
				return tamSil($msjinID);
			} else {//Mesajı atandan sil
				if ($this->db->where('id', $msjinID)->get('admin_mesajlar')->result()[0]->silmeDurum == 2) {//Karşıdaki kullanıcı da sildiyse
					return tamSil($msjinID);
				} else {//Sadece mesajı atandan sil
					return $this
					->db
					->where('id', $msjinID)
					->update('admin_mesajlar', array('silmeDurum' => 1));
				}
			}
		} else { //Mesajı alan silmek istiyorsa
			if ($this->db->where('id', $msjinID)->get('admin_mesajlar')->result()[0]->silmeDurum == 1) {//Mesajı atan da sildiyse
				return tamSil($msjinID);
			} else {//Sadece mesajı alandan sil
				return $this
				->db
				->where('id', $msjinID)
				->update('admin_mesajlar', array('silmeDurum' => 2));
			}
		}
	}


	public function temizle($kimID, $kimleID)
	{
		$kendiminkilerT = $this->db->delete('admin_mesajlar', array('gonderenID' => $kimID, 'alanID' => $kimleID, 'silmeDurum' => 2));
		$kendiminkiler = $this->db->where(array('gonderenID' => $kimID, 'alanID' => $kimleID))->update('admin_mesajlar', array('silmeDurum' => 1));

		$onunkilerT = $this->db->delete('admin_mesajlar', array('gonderenID' => $kimleID, 'alanID' => $kimID, 'silmeDurum' => 1));
		$onunkiler = $this->db->where(array('gonderenID' => $kimleID, 'alanID' => $kimID))->update('admin_mesajlar', array('silmeDurum' => 2));

		if ($kendiminkiler && $kendiminkilerT && $onunkiler && $onunkilerT) {
			return true;
		} elseif ($kendiminkiler || $kendiminkilerT || $onunkiler || $onunkilerT) {
			return 2;
		} else {
			return false;
		}
	}

}
