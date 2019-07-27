
<!-- Temel Bilgiler
<div class="row">
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3> echo $anaBilgi->tiklanma;</h3>
				<p>Bu gün tıklanma</p>
			</div>
			<div class="icon">
				<i class="fa fa-mouse-pointer"></i>
			</div>
			<a href="<?php echo base_url('admin/istatistik'); ?>" class="small-box-footer">Site İstatistikleri <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-green">
			<div class="inner">
				<h3></h3>
				<p></p>
			</div>
			<div class="icon">
				<i class="ion ion-stats-bars"></i>
			</div>
			<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3>44</h3>

				<p>User Registrations</p>
			</div>
			<div class="icon">
				<i class="ion ion-person-add"></i>
			</div>
			<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
	<div class="col-lg-3 col-xs-6">
		<div class="small-box bg-red">
			<div class="inner">
				<h3>65</h3>

				<p>Unique Visitors</p>
			</div>
			<div class="icon">
				<i class="ion ion-pie-graph"></i>
			</div>
			<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
		</div>
	</div>
</div>
Temel Bilgiler -->


<div class="row">
  <div class="col-md-3">

		<!-- Profil -->
		<div class="box box-primary">
			<div class="box-body box-profile">
      	<img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('rel/img/admin/' . $admin->id); ?>.jpg	" alt="<?php echo $admin->ad; ?> resmi">
        <h3 class="profile-username text-center"><?php echo $admin->ad; ?></h3>
        <p class="text-muted text-center"><?php echo yonetici_duzey_adi($admin->duzey); ?></p>

        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <b>Mail:</b> <a class="pull-right"><?php echo $admin->mail; ?></a>
          </li>
          <li class="list-group-item">
          	<b>Hakkında:</b> <a class="pull-right"><?php echo $admin->hakkinda; ?></a>
          </li>
        </ul>

        <a href="<?php echo base_url('admin/yonetici/duzenle/' . $admin->id); ?>" class="btn btn-primary btn-block"><b>Bilgileri Düzenle</b></a>
      </div>
		</div>
		<!-- / Profil -->

  </div>

	<div class="col-md-6">

		<!-- Yapılacaklar -->
		<div class="box box-warning">
			<div class="box-header">
				<h3 class="box-title"><a class="text-black" href="<?php echo base_url('admin/yapilacaklar'); ?>"><i class="fa fa-flag"></i> Yapılacaklar</a></h3>
			</div>
			<div class="box-body">

				<ol>
					<?php if (empty($yapilacaklar)): ?>
						<li>Henüz yapılacaklar listeniz boş</li>
					<?php else: ?>
						<?php foreach ($yapilacaklar as $key): ?>
							<li style="font-site:1.5em;"><?php echo $key->icerik ?></li>
						<?php endforeach; ?>
					<?php endif; ?>
				</ol>

			</div>

			<div class="box-footer">
				<a href="<?php echo base_url('admin/'); ?>yapilacaklar" class="btn btn-warning">Yapılacaklar listesine git</a>
			</div>
		</div>
		<!-- / Yapılacaklar -->

	</div>

	<div class="col-md-3">

		<!-- Kısayollar -->
		<div class="box box-info">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-external-link-square"></i> Kısayollar</h3>
			</div>
			<div class="box-body">
				<a href="<?php echo base_url('admin/') ?>urun/ekle" class="btn btn-app"><i class="fa fa-plus"></i> Ürün Ekle</a>
				<?php if (seviyesi_yuksek_mi(YONETICI_MOD)): ?>
					<a href="<?php echo base_url('admin/') ?>yorumlar/yeni" class="btn btn-app"><span class="badge bg-yellow"><?php echo $headVar['yorum']; ?></span><i class="fa fa-commenting"></i> Yeni Yorumlara Bak</a>
					<a href="<?php echo base_url('admin/') ?>abone" class="btn btn-app"><i class="fa fa-envelope"></i> Mail Abonelerine Bak</a>
				<?php else: ?>
					<a href="<?php echo base_url('admin/') ?>profil" class="btn btn-app"><i class="fa fa-plus"></i> Yönetici İstatistiklerine Bak</a>
				<?php endif; ?>
				<a href="<?php echo base_url('admin/') ?>yardim" class="btn btn-app"><i class="fa fa-question-circle"></i> Yardım</a>
			</div>
		</div>
		<!-- / Kısayollar -->

	</div>

</div>


<div class="row">

	<div class="col-md-4">

		<div class="box box-success">
			<div class="box-header">
				<h3 class="box-title"><i class="fa fa-external-link"></i> Bağlantılar</h3>
			</div>
			<div class="box-body">
				<ul>
				  <li><a target="_blank" href="https://search.google.com/search-console?resource_id=<?php echo base_url(); ?>&hl=tr">Google Search Console</a></li>
				  <li><a target="_blank" href="https://webmaster.yandex.com/">Yandex Search Console</a></li>
				  <li><a target="_blank" href="https://www.alexa.com/siteinfo/">Alexa</a></li>
				  <li><a target="_blank" href="https://tingpng.com/">Png Sıkıştırıcı</a></li>
					<!-- <li><a target="_blank" href="https://analytics.google.com/analytics/web/">Google Analystics</a></li> -->
				</ul>
			</div>
		</div>

	</div>

	<div class="col-md-8">

		<?php if ($this->config->item('istatistik_tut')): ?>
			<!-- En çok tıklananlar tablosu -->
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><a class="text-black" href="<?php echo base_url('admin/istatistik'); ?>"><i class="fa fa-line-chart"></i> En Çok Tıklananlar</a></h3>
				</div>

				<div class="box-body">
					<?php echo $enCokTiklananlar; ?>
				</div>

				<div class="box-footer">
					<a href="<?php echo base_url('admin/'); ?>istatistik" class="btn btn-primary">İstatistiklere git</a>
				</div>
			</div>
			<!-- / En çok tıklananlar tablosu -->
		<?php endif; ?>

	</div>
</div>
