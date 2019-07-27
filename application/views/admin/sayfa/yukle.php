<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Yükleme Ekranı</h3>
	</div>
	<!-- /.box-header -->
	<!-- form start -->
	<form role="form" action="<?php echo base_url('adminB/yukle/') . $yuklenen; ?>" method="post" enctype="multipart/form-data">
		<div class="box-body">
			<div class="form-group">
				<label for="formAdYeri"><?php echo $yuklenenNe; ?> Adı</label> <small>Uzantısı ile yazmayı unutmayın.</small>
				<input type="text" class="form-control" id="formAdYeri" name="ad" placeholder="Tam dosya adı" autocomplete="off">
			</div>
			<div class="form-group">
				<label for="formDosyaYeri"><?php echo $yuklenenNe; ?></label>
				<input type="file" id="formDosyaYeri" name="dosya">

				<p class="help-block">Yüklemek istediğiniz şeyi seçiniz.</p>
			</div>
		</div>
		<!-- /.box-body -->

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">Yükle</button>
		</div>
	</form>
</div>

<div class="box box-info">
	<div class="box-body">
		<form class="form-inline" method="get" action="<?php echo base_url('admin/yukle/') . $yuklenen; ?>/duzenle">
			<label for="urunIcerigiArama">Tüm <?php echo $yuklenenNe; ?> arasında ara: </label> <input type="search" class="form-control" name="ara" <?php echo (isset($_GET['ara'])?'value="' . $_GET['ara'] . '"':''); ?> placeholder="Ara.." id="urunIcerigiArama">
			<button type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>
</div>
