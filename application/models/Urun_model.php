<?php

class Urun_model extends CI_Model
{

  public function al($id)
  {//Urun id'sinden, ürünü gösterir
    $sonuc = $this
			->db
			->where('urun.id', $id)
			->join('urun_join', 'urun_join.id = urun.id')
			->get('urun')
			->result()[0];

		if (!empty($sonuc)) {
			$sonuc->adminAd = $this->db->where('id', $sonuc->yukleyen)->get('admin')->result()[0]->ad;
		}
		return $sonuc;
  }

  public function addanBul($urunAd)
  {// Sef link adından, ürünü gösterir
		return $this->al($this->db->where('sef', $urunAd)->get('urun')->result()[0]->id);
  }

  public function ara($ad = null, $limit = 30){
    return $this
		->db
		->like('ad', $ad)
		->limit($limit)
		->get('urun')
		->result();
  }


  public function kategSirala($kategori, $altkategori = null, $limit = 25)
  {
    $whereData['kategori'] = $kategori;

    if($altkategori !== null && $altkategori !== ''){
      $whereData['altkategori'] = $altkategori;
    }

    return $this
      ->db
      ->where($whereData)
			->order_by('goruntulenme', 'desc')
			->join('urun_join', 'urun_join.id = urun.id')
      ->limit($limit)
      ->get('urun')
      ->result();
  }


	public function enAl($ozellik='unlu', $limit=5, $kategori=null, $altkategori=null)
	{
		//Özellik
		if ($ozellik == 'unlu') {
			$this->db->order_by('goruntulenme', 'desc');
		} elseif ($ozellik == 'yeni') {
			$this->db->order_by('tarih', 'desc');
		}

		if ( ! is_null($kategori)) {
			$this->db->where('kategori', $kategori);
		}
		if ( ! is_null($altkategori)) {
			$this->db->where('altkategori', $altkategori);
		}

		return $this
		->db
		->limit($limit)
		->join('urun_join', 'urun_join.id = urun.id')
		->select('urun.id,ad,kategori,altkategori,sef,goruntulenme,aciklama')
		->get('urun')
		->result();

	}

  public function goruntulenmeArttir($id)
  {
    $goruntulenmeSayisi = $this->db->where('id', $id)->get('urun')->result()[0]->goruntulenme;
    return $this->db->where('id', $id)->update('urun', array('goruntulenme' => $goruntulenmeSayisi + 1));
    // return $this->db->where('id', $id)->update('urun', array('goruntulenme' => 'goruntulenme + 1'));
  }

  ######### ADMİN ########

  public function ekle($ad, $kategori, $altkategori, $yukleyen, $ek)
  {
    do{
      $id = rand(100000000, 999999999);
      $idKontrol = $this->db->where('id', $id)->get('urun')->result();
    }while ($idKontrol != null);
    $veri = array(
      'id'        	=> $id,
      'ad'        	=> $ad,
      'kategori'  	=> $kategori,
      'altkategori'	=>$altkategori,
      'sef'       	=> seflink ($ad),
      'goruntulenme'=> 0,
      'yukleyen'  	=> $yukleyen,
			'tarih'				=> date('Y-m-d')
    );

		$joinArray = array('id' => $id);
		foreach ($ek as $anahtar => $deger) {
			$joinArray[$anahtar] = $deger;
		}
		$joinEkle = $this->db->insert('urun_join', $joinArray);

    if ($this->db->insert ('urun', $veri) && $joinEkle){
      return $id;
    }else{
      return false;
    }
  }

  public function listele($kategori, $altkategori, $ad)
  {
    return $this
      ->db
      ->where(array('kategori' => $kategori, 'altkategori' => $altkategori))
      ->like('ad', $ad)
      ->limit(50)
      ->get('urun')
      ->result();
  }

  public function guncelle($id, $ad, $kategori, $altkategori, $ek){
    $veri = array(
      'ad'        => $ad,
      'kategori'  => $kategori,
      'altkategori'=>$altkategori,
      'sef'       => seflink ($ad)
    );
		$joinArray = array('id' => $id);
		foreach ($ek as $anahtar => $deger) {
			$joinArray[$anahtar] = $deger;
		}
    return $this->db->where('id', $id)->update('urun', $veri) && $this->db->where('id', $id)->update('urun_join', $joinArray);
  }

  public function sil($id){
    return ($this->db->where ('id', $id)->delete('urun')
		&& $this->db->delete('urun_join', array('id' => $id))
		&& $this->db->delete('yorum', array('urunID' => $id)));
  }


	public function urunMapAl()
	{
		return $this
		->db
		->select('kategori, altkategori, sef, tarih')
		->get('urun')
		->result();
	}

	public function urunSayisi()
	{
		return $this->db->get('urun')->num_rows();
	}


	public function begeni($urunID, $hangisi, $degisim)//id, (begen,begenme), (+1, -1)
	{
		$sonucSayi = $this
		->db
		->select($hangisi)
		->where('id', $urunID)
		->get('urun_join')
		->result()[0]->{$hangisi};

		if ($sonucSayi === false) {
			return false;
		} else {
			return $this
			->db
			->where('id', $urunID)
			->update('urun_join', array($hangisi => ($sonucSayi + $degisim)));
		}
	}

}
