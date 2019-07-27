<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminBildirim_model extends CI_Model{

	/**
		* Bildirim Al
		*
		*	İstenen önemlilikteki bildirimleri verir
		*
		* @param integer	Önem seviyesi (0: Hepsi, 1: Orta önemli, 2: Önemli, 3: Hayati önem taşıyan, 4: Sadece YONETICI_BAS)
		* @param integer	Bildirim limiti
		*
		* @return	integer
		*/
	public function bildirimAl ($durum = 0, $limit = 20){
		if ($durum == 1) {
			$this->db->where('onem', 3)->or_where('onem', 4)->or_where('onem', 5);
		}elseif($durum == 2){
			$this->db->where('onem', 6)->or_where('onem', 7)->or_where('onem', 8);
		}elseif($durum == 3){
			$this->db->where('onem', 9)->or_where('onem', 10);
		}elseif($durum == 4){
			$this->db->where('onem', 11)->or_where('onem', 12);
		}else{
			$this
			->db
			->where('onem <', 10);
		}

		return $this
		->db
		->limit($limit)
		->get('admin_bildirimler')
		->result();
	}


	/**
		* Bildirim Sil
		*
		*	Array şeklinde ID'si verilen bildirimleri siler
		*
		* @param array Silinecek bildirimlerin ID'leri
		*
		* @return	boolean
		*/
	public function bildirimSil($silineceklerinID)
	{
		return $this
		->db
		->where_in('id', $silineceklerinID)
		->delete('admin_bildirimler');
	}


	/**
		* Ekle
		*
		*	Yeni bildirim ekler
		*
		* @param integer	Önem seviyesi (1 - 12)
		* @param string		Bildirimin başlığı
		* @param string		Bildirimin içeriği
		*
		* @return	boolean
		*/
	public function ekle($onem, $baslik, $uyari)
	{
		// Başka bildirimin ID'siyle çakışmayacak ID oluşturur
		do{
			$id = rand(10000, 65000);
			$idKontrol = $this->db->where('id', $id)->get('admin_bildirimler')->result();
		}while ($idKontrol != null);

		return $this->db->insert ('admin_bildirimler', array(
			'id'		=> $id,
			'onem'  => $onem,
			'ip'    => $this->Input->ip_address(),
			'tarih' => date('Y-m-d'),
			'baslik'=> $baslik,
			'uyari' => $uyari
		));
	}

}
