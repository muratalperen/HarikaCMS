<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error404 extends CI_Controller{

	/**
	*
	*	404 Sayfası
	*
	*/
	public function index()
	{
		$this->output->set_status_header('404');

		$this->load->helper(array('url', 'main'));
		$this->load->model('urun_model');


		// 404 hatası yönetim panelinde olduysa
		if ($this->router->fetch_class() == 'admin')
		{
			// Ama yönetici değilse
			if ( ! isset($this->session->get_userdata ()['admin']))
			{
				redirect (base_url('admin/giris'));
			}

			$this->load->model ('admin_model');
			$this->adminInfo = $this->admin_model->adminiAl($this->session->get_userdata ()['admin']);	//Admin bilgilerini al

			// Gösterilen ID'de bir yönetici yoksa
			if (is_null($this->adminInfo))
			{
				// Gösterilen ID'yi sil ve giriş sayfasına gönder
				$this->session->unset_userdata ('admin');
				redirect (base_url('admin/giris'));
			}

			$this->headerInfo = array (
				'admin'			=> $this->adminInfo,
				'headVar'		=> $this->admin_model->headBilgileri($this->adminInfo->id)
			);
			$this->load->helper('admin');

		}

		// 404 hatası bildiriliyor
		yonetime_bildir(
			$this->config->item('bildirim_seviyesi')['hata_404'],
			'404 Hatası',
			'&quot;' . $this->urI->uri_string . '&quot; adresinde 404 hatası alındı.' . (empty($_SERVER['HTTP_REFERER']) ? '' : ' Referans: ' . $_SERVER['HTTP_REFERER'])
		);

		$araStr = $this->urI->segments[count($this->urI->segments)]; // Sonuncu segment

		$this->viewData = array(
			'aramaSonuc'	=> $this->Urun_model->ara($araStr, 10),
			'aranan'			=> $araStr
		);

		render_page('hata404', 'Sayfa Bulunamadı', array(
			'aciklama'	=> 'Bu sayfa bulunamadı'
		));
	}
}
?>
