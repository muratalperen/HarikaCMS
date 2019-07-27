<div class="row">
	<div class="col-md-4">

		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><i class="fa fa-cubes"></i> Site İkonunu Güncelleyin</h3>
		  </div>
		  <div class="box-body">
				<form class="form-inline" action="<?php echo base_url('adminB/'); ?>ayarlar" method="post" enctype="multipart/form-data">
					<label for="dosya">Bir ikon seçin (ico formatında):</label> <input type="file" name="icon" id="dosya" class="btn bg-maroon m-1" required><br>
					<input type="submit" value="Güncelle" class="form-control">
					<br><small>İkonu güncellediğinizde tarayıcınız önbelleğe aldığı için aynı görünebilir. Güncelleme sonucunu öğrenmek için siteyi gizli sekmeden açın.</small>
					<br><span>Şu anki ikon: </span><img src="<?php echo base_url('favicon.ico'); ?>" alt="Site ikonu">
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="box box-primary">
		  <div class="box-header with-border">
		    <h3 class="box-title"><i class="fa fa-clipboard"></i> Önbelleği Temizleyin</h3>
		  </div>
		  <div class="box-body">
		  	<a href="<?php echo base_url('adminB/'); ?>ayarlar?onbellek=sil" class="btn btn-primary"><i class="fa fa-minus-circle"></i> Önbelleği Temizle</a>
				<br>
				<small>Sitenin hızlı olması için bazı sayfalar önbelleğe alınır. Bu tuş, önbelleği temizleyerek tekrar oluşturulmasını sağlar.</small>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-files-o"></i> Site Yedeğini Alın</h3>
			</div>
			<div class="box-body">
				<a href="<?php echo base_url('adminB/'); ?>ayarlar?yedek=yap" class="btn btn-primary"><i class="fa fa-clone"></i> Yedek Oluştur</a><br><br>
				<?php if (file_exists(site_YOL . 'gizli/yedek.zip')): ?>
					<a href="<?php echo base_url('adminB/'); ?>ayarlar?yedek=indir"><i class="fa fa-download"></i> Son Yedeği İndir - <?php echo date('d-m-Y', filemtime(site_YOL . 'gizli/yedek.zip')); ?> (<?php echo dosyaBoyut(site_YOL . 'gizli/yedek.zip'); ?>)</a>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php if (seviyesi_yuksek_mi(YONETICI_BAS)): ?>
		<div class="col-md-4">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title"><i class="fa fa-minus-circle"></i> Siteyi Sıfırla</h3>
				</div>
				<div class="box-body">
					<button onclick="silsor();" class="btn btn-primary btn-lg"><i class="fa fa-power-off"></i> Tüm Siteyi Sıfırla</button>
				</div>
			</div>
			<script type="text/javascript">
				function silsor() {
					bildirim(
						'Silmek İstediğinizden Emin Misiniz?',
						'Bu işlem, sitenin tüm içeriğini (yazı, metin, görsel, yöneticiler, mesajlaşmalar, bildirimler, yorumlar...) silecektir. Ama sitenin kendisi kalacak ve sıfırdan kullanabileceksiniz\
						Sitenizin içeriğini tamamen silmeyi düşünüyorsanız bile sitenizin yedeğini almanızı öneririz.\
						<a href="<?php echo base_url('adminB/'); ?>ayarlar?yedek=yap&ydSil=TRUE" class="btn btn-warning">Yedek al ve sil</a>\
						<a href="<?php echo base_url('adminB/'); ?>ayarlar?sifirla=site" class="btn btn-danger">Sadece sil</a>',
						"d"
					);
				}
			</script>
		</div>
	<?php endif; ?>

</div>
