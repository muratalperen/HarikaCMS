<div class="box box-primary">
	<div class="box-header">
		<i class="ion ion-clipboard"></i>

		<h3 class="box-title">Yapılacaklar (To Do) Listesi</h3>
		<br><br>
		<form role="form" class="form-inline" action="<?php echo base_url('adminB/'); ?>yapilacaklar" method="post">
			<label class="margin-r-5">Ekle: </label>
			<input type="text" name="adi" placeholder="Yapılacak görev" maxlength="100" autocomplete="off" class="form-control">
			<?php
			// Üst seviye yöneticiyse başkalarının yapılacaklar listesine ekleme yapabilir
			if(seviyesi_yuksek_mi(YONETICI_UST)): ?>
				<label>Şunun yapılacaklar listesine:</label> <select name="kime" class="form-control">
					<?php foreach ($adminler as $admini): ?>
						<option <?php echo (($admini->id == $admin->id)?'selected=""':''); ?> value="<?php echo $admini->id; ?>"><?php echo $admini->ad; ?></option>
					<?php endforeach; ?>
				</select>
			<?php endif; ?>
			<input type="submit" value="Ekle" class="btn btn-primary">
		</form>
	</div>
	<!-- /.box-header -->
	<div class="box-body">
		<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
		<ul class="todo-list ui-sortable">
			<?php	foreach ($degerler as $yap): ?>
				<li>
					<span class="text"><?php echo $yap->icerik; ?></span>
					<small class="label label-info"><i class="fa fa-clock-o"></i> <?php echo gecen_zaman($yap->tarih); ?></small>
					<div class="tools">
						<i><a href="<?php echo base_url('adminB/'); ?>yapilacaklar?sil=<?php echo $yap->id; ?>"><i class="fa fa-trash-o"></i></a></i>
					</div>
				</li>
			<?php endforeach; ?>

		</ul>
	</div>
	<!-- /.box-body -->
	<div class="box-footer clearfix no-border">
		<p>Yapılacaklar listenizi sadece siz ve <?php echo yonetici_duzey_adi(YONETICI_BAS); ?> görebilir. Ancak <?php echo yonetici_duzey_adi(YONETICI_UST); ?> seviyesindekiler, yapılacaklar listenize görmeden ekleme yapabilirler.</p>
	</div>
</div>
