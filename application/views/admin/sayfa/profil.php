<div class="row">
	<div class="col-md-3">

		<!-- Profile Image -->
		<div class="box box-primary">
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('rel/img/admin/') . $alAdmin->id; ?>.jpg" alt="Yöneticinin resmi">

				<h3 class="profile-username text-center"><?php echo $alAdmin->ad; ?></h3>

				<p class="text-muted text-center">Düzey: <?php echo $alAdmin->duzey; ?></p>

				<ul class="list-group list-group-unbordered">
					<li class="list-group-item">
						<b>Paylaşım Sayısı</b> <a class="pull-right"><?php echo $alAdmin->paylastigiUrunSayisi; ?></a>
					</li>
					<!-- <li class="list-group-item">
						<b>Following</b> <a class="pull-right">543</a>
					</li>
					<li class="list-group-item">
						<b>Friends</b> <a class="pull-right">13,287</a>
					</li> -->
				</ul>

				<a href="<?php echo base_url('admin/') . 'mesaj?kim=' . $alAdmin->id; ?>" class="btn btn-primary btn-block <?php echo (($alAdmin->id == $admin->id)?'disabled':''); ?>"><i class="fa fa-comments"></i><b> Mesaj At</b></a>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->

		<!-- About Me Box -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Hakkında</h3>
			</div>
			<div class="box-body">
				<strong><i class="fa fa-envelope"></i> Mail</strong>

				<p class="text-muted">
					<?php echo $alAdmin->mail; ?>
				</p>

				<hr>

				<strong><i class="fa fa-clock-o"></i> Son Görülme</strong>

				<p class="text-muted"><?php echo gecen_zaman($alAdmin->sonCevrimici); ?></p>

				<hr>

				<strong><i class="fa fa-pencil"></i> Hakkında</strong>

				<p class="text-muted"><?php echo $alAdmin->hakkinda; ?></p>

				<hr>

		</div>
		<!-- /.box-body -->
	</div>
	<!-- /.box -->
</div>
<!-- /.col -->
<div class="col-md-9">
	<div class="box box-primary">
		<div class="box-header with-border">
			<h3 class="box-title">Paylaşımları</h3>
		</div>
		<div class="box-body">

			<ul class="timeline timeline-inverse">

				<?php
				$sonTarih;// = date('');
				foreach ($timeline as $timeVerisi) {

					if ($sonTarih != $timeVerisi->tarih) {// Yeni tarih gelirse, yeni tarih labeli ekle
						echo '<li class="time-label"><span class="bg-green">'. $timeVerisi->tarih .'</span></li>';
						$sonTarih = $timeVerisi->tarih;
					}

					echo '<li>
						<i class="fa fa-camera bg-purple"></i>

						<div class="timeline-item">
							<span class="time"><i class="fa fa-clock-o"></i> '. gecen_zaman($timeVerisi->tarih) .'</span>

							<h3 class="timeline-header">
								'. $alAdmin->ad .' &quot;<a target="blank"  href="'. $u->urunLink($timeVerisi) . '">'. $timeVerisi->ad .'</a>&quot; Ürününü Ekledi
							</h3>
							<div class="timeline-body">
								<img class="img-responsive" src="' . $u->urunLink($timeVerisi) . '/resim" alt="Fotoğraf" style="max-height:250px;">
							</div>
						</div>
					</li>';
				}

				?>
				<li>
					<i class="fa fa-clock-o bg-gray"></i>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- /.col -->
</div>
