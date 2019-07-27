<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminYapilacaklar_model extends CI_Model{

	/**
		* Al
		*
		*	ID'si verilen yöneticinin yapılacaklar listesi alınır
		*
		* @param integer	Yöneticinin ID
		* @param integer	Limit
		*
		* @return	object
		*/
	public function al($admininID, $limit=0)
	{
		if ($limit != 0) {
			$this->db->limit($limit);
		}
		return $this
		->db
		->where('admininID', $admininID)
		->order_by('tarih', 'desc')
		->get('admin_yapilacaklar')
		->result();
	}


	/**
		* Sil
		*
		*	ID'si verilen yapılacak'ı siler
		*
		* @param integer	Yapılacak ID
		*
		* @return	boolean
		*/
	public function sil($silinecekID)
	{
		return $this
		->db
		->where('id', $silinecekID)
		->delete('admin_yapilacaklar');
	}


	/**
		* Ekle
		*
		*	Yeni bildirim ekler
		*
		* @param string		Yapılacak yazısı
		* @param integer	Yöneticinin ID
		*
		* @return	boolean
		*/
	public function ekle($icerik, $admininID)
	{
		// Çakışmayacak ID bul
		do{
			$id = rand(0, 65000);
			$idKontrol = $this->db->where('id', $id)->get('admin_yapilacaklar')->result();
		}while ($idKontrol != null);

		$insertData = array(
			'id'    		=> $id,
			'icerik'		=> $icerik,
			'admininID'	=> $admininID,
			'tarih'			=> date('d-m-Y')
		);

		return $this->db->insert ('admin_yapilacaklar', $insertData);
	}

}
