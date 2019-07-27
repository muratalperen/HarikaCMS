<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	function __construct() {
		parent::__construct();
		//Tüm get ve post method'larını xss güvenliğinden geçiriyoruz
		foreach ($_GET as $key => $value) {
			$_GET[$key] = $this->input->get($key, TRUE);
		}
		foreach ($_POST as $key => $value) {
			$_POST[$key] = $this->input->post($key, TRUE);
		}
	}


	public function index()
	{
		boyleBisiOlamaz();
	}

	public function yorum($islem)
	{
		$this->load->model('Yorum_model');
		if ($islem == 'al') {// Tüm yorumları istiyor
			if (!isset($_GET['urunID'])) {
				boyleBisiOlamaz();
			}
			$yorumlar = $this->Yorum_model->tumAl($_GET['urunID']);
			for ($i=0; $i < count($yorumlar); $i++) {//Gravatar hesabı ve mailin ifşa olmaması için
				$yorumlar[$i]->mail = md5(strtolower($yorumlar[$i]->mail));
			}
			echo json_encode($yorumlar);

		} elseif ($islem == 'yap') {

			if (!isset($_POST['ad'])) {
				boyleBisiOlamaz();
			}
			if ($_POST['kaydol'] == true) {
				$this->load->model('Abone_model');
				$abone = $this->Abone_model->kayit($_POST['ad'], $_POST['mail']);
			}

			if (empty($this->Urun_model->al($_POST['urunID']))) {
				redirect($_POST['redirect']);
			} else {
				$this->load->model('Yorum_model');
				if ($yorumID = $this->Yorum_model->yorumYap($_POST['yanitID'], $_POST['urunID'], $_POST['ad'], $_POST['mail'], $_POST['site'], $_POST['icerik'])) {//Yorum başarılıysa
					redirect($_POST['redirect'] . '?yorum=' . $yorumID . (isset($abone)?'&abone=' . $abone:''));
				} else {
					redirect($_POST['redirect'] . '?yorum=false' . (isset($abone)?'&abone=' . $abone:''));
				}
			}

		} else {
			boyleBisiOlamaz();
		}

	}

	public function begeni()// $_POST['donus'] -> 'begen', 'begenme'. Ajax ile çalışır
	{
		if (isset($_POST['donus'])) {
			//ya cookie kabul etmiyorsa?
			ob_start();
			if($_COOKIE[$_POST['urunID']] == $_POST['donus']){ // Önceki tıkladığı, şimdi tıkladığına eşitse (bir azalt)
				if($this->Urun_model->begeni($_POST['urunID'], $_POST['donus'], -1)){
					$begeniCerez = null;
					echo 'vt';
				} else {
					echo 'Seçiminiz azaltılamadı.';
				}
			} elseif ($_COOKIE[$_POST['urunID']] != null){	//Daha önce birşeye tıklamış, şimdi başka birşeye tıklamış (önceki tıkladığını azalt, şimdi tıkladığını arttır.)
				if($this->Urun_model->begeni($_POST['urunID'], $_COOKIE[$_POST['urunID']], -1) && $this->Urun_model->begeni($_POST['urunID'], $_POST['donus'], 1)){
					echo 'dt' . $_COOKIE[$_POST['urunID']];
					$begeniCerez = $_POST['donus'];
				}else{
					echo 'Veritabanı hatası';
				}
			}else{	//İlk kez birine tıklıyor
				if($this->Urun_model->begeni($_POST['urunID'], $_POST['donus'], 1)){
					echo 'yt';
					$begeniCerez = $_POST['donus'];
				}else{
					echo 'yf';
				}
			}
			setcookie($_POST['urunID'], $begeniCerez);
		} else {
			boyleBisiOlamaz();
		}

	}

	public function mailAbone()
	{
		if (isset($_POST['mail'])) {
			$this->load->model('Abone_model');
			echo $this->Abone_model->kayit($_POST['ad'], $_POST['mail']);
		}else {
			boyleBisiOlamaz();
		}
	}


	public function iletisim()
	{
		if (isset($_POST['ad'])) {
			$this->load->model('AdminBildirim_model');
			$sonuc = $this->AdminBildirim_model->ekle(9, $_POST['ad'] . ' Bir Mesaj Attı!', 'Mail: ' . $_POST['mail'] . '. Mesaj: ' . $_POST['yorum']);
		  redirect(base_url('sayfa/iletisim') . '?ilet=' . (($sonuc)?'true':'false'));

		} else {
			boyleBisiOlamaz('sayfa/iletisim');
		}
	}
}
