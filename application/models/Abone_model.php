<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Abone_model extends CI_Model{

	/**
	 * Kayıt
	 *
	 * Verilen ad ve mail ile abone tablosuna ekler
	 *
	 * @param string	Abone olacak kişinin adı
	 * @param string	Abone olacak kişinin mail'i
	 *
	 * @return	bool
	 */
	public function kayit($ad, $mail)
	{
		// Zaten abone ise ismini güncelle
		if ($this->db->where('mail', $mail)->get('abone')->num_rows() != 0)
		{
			return $this
			->db
			->where('mail', $mail)
			->update('abone', array('ad' => $ad));

		}
		else
		{
			return $this
			->db
			->insert('abone', array(
				'ad'		=> $ad,
				'mail'	=> $mail,
				'tarih'	=> date('Y-m-d')
			));

		}
	}

	/**
	 * Sil
	 *
	 * Verilen mail'deki kayıtı siler
	 *
	 * @param string	Silinecek abonenin mail adresi
	 *
	 * @return	bool
	 */
	public function sil($mail)
	{
		return $this
		->db
		->delete('abone', array('mail' => $mail));
	}

	// IDEA: gizli key oluşturulmalı abone silme ve abonelikten çıkma için

	/**
	 * Al
	 *
	 * Tüm aboneleri alır
	 *
	 * @return	object
	 */
	public function al()
	{
		return $this
		->db
		->get('abone')
		->result();
	}
}
