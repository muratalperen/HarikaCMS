<?php
if (isset($_GET['ilet'])) {
	if ($_GET['ilet'] === 'true') {
		echo '<h2>Mesajınız alındı. Mail\'inizi doğru yazdıysanız en kısa sürede cevap verilecektir.</h2>';// Modal yap
	} else {
		echo '<h3 class="text-danger">Mesajınız veritabanına yüklenemedi (Sistem içi hata). <a href="mailto:' . $this->config->item('site')->iletisimMail . '">' . $this->config->item('site')->iletisimMail . '</a> adresine normal mail göndermeyi deneyebilirsiniz.</h3>';
	}

}
?>

<div class="contact_area">
	<h2>İletişim</h2>
	<p>Aşağıdaki form üzerinden mesaj atabilir, veya <a href="mailto:<?php echo $this->config->item('site')->iletisimMail; ?>"><?php echo $this->config->item('site')->iletisimMail; ?></a> mail adresine mail atabilirsiniz.</p>
	<form action="<?php echo base_url(); ?>api/iletisim" method="post" class="contact_form">
		<input name="ad" class="m-2 form-control" type="text" placeholder="İsim*" maxlength="15" required="required">
		<input name="mail" class="m-2 form-control" type="email" placeholder="Email*" maxlength="30" required="required">
		<textarea name="yorum" class="m-2 form-control" cols="30" rows="10" placeholder="Mesajınız*" maxlength="800"></textarea>
		<input type="submit" value="Gönder" class="btn btn-primary m-2">
	</form>
</div>
