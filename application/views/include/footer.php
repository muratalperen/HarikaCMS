
</article>


<!-- Yan panel -->
<aside class="col-md-4 col-sm-12">

	<!-- Bu ürünü yazan/oluşturan -->
	<?php
	$yz = isset($buUrun);
	$yapan = $this->db->select('ad,hakkinda')->where('id', $buUrun->yukleyen)->get('admin')->result()[0];
	?>
		<div class="p-3 mb-3 mt-2">
			<figure class="w-100 text-center">
				<img src="<?php echo (($yz) ? base_url('rel/') . 'img/admin/' . $buUrun->yukleyen . '.jpg' : base_url('favicon.ico')); ?>" alt="Yazarın Resmi" class="img-fluid align-center" style="border-radius:100%; max-width:120px;">
				<h4><?php echo ($yz) ? $yapan->ad : $this->config->item('site')->ad; ?></h4>
				<figcaption class="text-muted"><?php echo ($yz) ? $yapan->hakkinda : $this->config->item('site')->hakkinda; ?></figcaption>
			</figure>
		</div>
	<!-- / Bu ürünü yazan/oluşturan -->

	<!-- Reklam Alanı -->
	<div class="mt-3">
		<figure>
			<a href="#">
				<!-- Şu reklamı ortala -->
				<img src="<?php echo base_url('rel/img/reklam/'); ?>0.jpg" alt="Reklam resmi" class="img-fluid" style="margin-left:50px;">
			<!-- <figcaption class="text-center text-dark">Reklam Adı</figcaption> -->
			</a>
		</figure>
	</div>
	<!-- / Reklam Alanı -->

	<!-- Sosyal Medya -->
	<div class="w-100 mt-3">
		<h3 class="text-center">Bizi Takip Edin</h3>
		<hr>
		<ul class="list-inline text-center">
			<?php if ( ! empty($this->config->item('site')->medya['facebook'])): ?>
				<li class="m-1 list-inline-item"><a class="btn btn-md text-white pFacebook" target="_blank" href="<?php echo $this->config->item('site')->medya['facebook']; ?>" title="Facebook"><i class="fa fa-facebook"></i></a></li>
			<?php endif; ?>
			<?php if ( ! empty($this->config->item('site')->medya['twitter'])): ?>
				<li class="m-1 list-inline-item"><a class="btn btn-md text-white pTwitter" target="_blank" href="<?php echo $this->config->item('site')->medya['twitter']; ?>" title="Twitter"><i class="fa fa-twitter"></i></a></li>
			<?php endif; ?>
			<?php if ( ! empty($this->config->item('site')->medya['instagram'])): ?>
				<li class="m-1 list-inline-item"><a class="btn btn-md text-white pInstagram" target="_blank" href="<?php echo $this->config->item('site')->medya['instagram']; ?>" title="İnstagram"><i class="fa fa-instagram"></i></a></li>
			<?php endif; ?>
		</ul>
	</div>
	<!-- / Sosyal Medya -->

</aside>
<!-- / Yan Panel -->

</div>
<!-- / Row -->

</main>

	<!-- Footer -->
	<footer class="bg-dark text-light p-3 mt-3 rounded">

		<div class="row">

			<!-- Abone Olma Bölümü -->
			<div class="col-md-4 col-sm-12">
				<h2>Mail Abonesi Olun!</h2>
				<hr>
				<p>Size sadece önemli bulduğumuz yazılardan haberdar etmek için mail göndeririz. Asla SPAM yapmayız. İstediğiniz zaman abonelikten çıkabilirsiniz.</p>
				<div class="contact_form">
					<input class="form-control mt-2" type="text" maxlength="30" placeholder="İsim">
					<input class="form-control mt-2" type="email" maxlength="35" placeholder="E-Mail Adresi">
					<input type="button" class="form-control btn-primary mt-2" value="TAKİBE BAŞLA" onclick="aboneOl(this.parentNode, '<?php echo base_url(); ?>');" >
				</div>
			</div>
			<!-- / Abone Olma Bölümü -->


			<!-- Sayfa Bağlantıları -->
			<div class="col-md-4 col-sm-12">
				<h2>Bağlantılar</h2>
				<hr>
				<ul>
					<li><a href="<?php echo base_url('sayfa/statik/hakkinda'); ?>" class="text-white">Hakkında</a></li>
					<li><a href="<?php echo base_url('sayfa/statik/gizlilik'); ?>" class="text-white">Gizlilik Politikası</a></li>
				</ul>
			</div>
			<!-- / Sayfa Bağlantıları -->


			<!-- İletişim Bölümü -->
			<div class="col-md-4 col-sm-12">
				<h2>İletişim</h2>
				<hr>
				<address>
					<!-- Bir adres. Burayı el ile düzenleyin. /application/views/include/footer.php -->
				</address>
				<p> Bize <a href="<?php echo base_url('sayfa/statik/iletisim'); ?>">iletişim</a> sayfasından da ulaşabilirsiniz.</p>
			</div>
			<!-- / İletişim Bölümü -->


		</div>
		<!-- / Row -->

		<!-- Site Hakları -->
		<div class="mt-5">
			<p class="mb-0">
				Copyright &copy; 2019 <a href="<?php echo base_url(); ?>sayfa/statik/hakkinda"><?php echo $this->config->item('site')->ad; ?></a>

				<span class="pull-right"><a href="https://github.com/muratalperen/HarikaCMS">Harika CMS</a> Kullanıldı</span>
			</p>
		</div>
		<!-- Site Hakları -->

	</footer>

</div>
<!-- / Container -->


<!-- Ana Bildirim Ekranı (Modal) -->
<div id="anaModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Bildirim Başlık</h3>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<p>Bu bildirim metnidir ve bildirim gelmeden bu metnin değişmiş olması gerekir. Eğer bu yazıyı okuyorsanız lütfen bildirin.</p>
			</div>
		</div>
	</div>
</div>
<!-- Ana Bildirim Ekranı (Modal) -->


<!-- Yukarı Çık Tuşu -->
<a href="#" id="scrollYukari" class="btn btn-circle btn-info btn-lg" title="Sayfanın yukarısına çık"><i class="fa fa-angle-up"></i></a>
<!-- / Yukarı Çık Tuşu -->

<script src="<?php echo base_url ('rel/js/'); ?>jquery.min.js" onerror="linkError('jquery.min', 'js', this);"></script>
<script src="<?php echo base_url ('rel/js/'); ?>bootstrap.min.js" onerror="linkError('bootstrap.min', 'js', this);"></script>
<script src="<?php echo base_url ('rel/js/'); ?>sayfa.js"></script>

<?php
// Ek Javascript Dosyaları:
if (isset ($ekleJS))
  foreach ($ekleJS as $jsYolu)  echo '<script src="' . base_url('rel/js/') . $jsYolu . '.js"></script>';

echo ( isset($footerExtra) ? $footerExtra : '' );
?>
</body>
</html>
