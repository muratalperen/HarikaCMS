<h1 class="text-danger" style="font-size:10rem;">404</h1>
<h1>Aradığınız Sayfa Bulunamadı</h1>

<p>
İstediğiniz sayfa bu sitede bulunmuyor (404 Hatası). Bağlantıyı yanlış yazmış olabilir
veya aradığınız sayfa gösterimden kalkmış olabilir. Yine de bu durum yöneticilere bildirildi.
En kısa sürede kullanıcının neden bu hatayı aldığı araştırılacak.
</p>
<hr>
<br>

<h2>Buradan arama yapabilirsiniz</h2>
<form method="get" class="mb-3">
	<div class="row">
		<div class="col-lg-10 col-md-10 col-sm-10">
			<input class="form-control" name="ad" placeholder="Aramanız.." type="search" value="<?php echo $aranan; ?>">
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2">
			<button value="Ara" type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i></button>
		</div>
	</div>
</form>
<br>

<?php if (count($aramaSonuc)): ?>

<h2>Aradığınız sayfa bunlardan biri olabilir mi?</h2>
<br>
<h3>&quot;<?php echo ucfirst($aranan); ?>&quot; İle İlgili Sonuçlar</h3>

<?php $this->load->view('sayfa/vitrin', array('liste' => $aramaSonuc)); ?>

<?php endif; ?>
