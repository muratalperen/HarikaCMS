<?php
// Site sıfırlanmaya izin verilmiş mi?
if ( ! file_exists('gizli/.yeni')) {
	// Kurulum yapılamaz
	header('Location: admin/giris?redirect=ayarlar');
	exit();
}

// Kurulum verileri gönderildiyse kuruluma başlanır
if (isset($_POST['base_url']))
{
	require 'application/helpers/kurulum_helper.php';

    $sonuc = siteyiKur();

	$linkler = '<a href="' . $_POST['base_url'] . '">Ana Sayfa</a> <a href="' . $_POST['base_url'] . 'admin">Yönetim Paneli</a>';

	if ($sonuc === TRUE)
	{
		unlink('gizli/.yeni') OR die('"gizli" klasöründeki ".yeni" dosyası silinemedi. Siteniz başkası tarafından ele geçirilebilir. O dosyayı silin.<br>' . $linkler);
		redirect($_POST['base_url'] . 'admin?redirect=yardim');
	}
	else
	{
		echo 'Bazı sorunlarla karşılaşıldı. Sorunları çözdükten sonra sitenizi kullanmaya başlayabilirsiniz:<br>' . substr($sonuc, 1);
		unlink('gizli/.yeni') OR die('"gizli" klasöründeki ".yeni" dosyası silinemedi. Siteniz başkası tarafından ele geçirilebilir. O dosyayı silin.<br>');
		echo $linkler;
	}

}
else // Kurulum verisi yoksa, kurulum ekranı gösterilir.
{
	include 'application/views/sayfa/kurulum.php';
}

?>
