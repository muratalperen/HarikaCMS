<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Düzenle</h3>
		<small>Toplam <?php echo $toplamSayisi; ?> öğe</small>
		<form class="pull-right form-inline" method="get">
			<input type="search" class="form-control" name="ara" <?php echo (isset($_GET['ara'])?'value="' . $_GET['ara'] . '"':''); ?> placeholder="Ara.." required autocomplete="off">
			<button type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>
	<div class="box-body">

		<ul class="mailbox-attachments clearfix">
		<?php	for ($i=0; $i < count($sirala); $i++) { ?>

				<li>
					<span class="mailbox-attachment-icon <?php echo (($yuklenen == 'resim') ? 'has-img' : ''); ?>"><?php echo ($yuklenenNe == 'Resim') ? '<img src="' . base_url('dosya/icerik/' . $yuklenen . '/' . $sirala[$i]) . '" alt="' . $sirala[$i] . '">' : '<i class="fa fa-file-o"></i>' ; ?></span>

					<div class="mailbox-attachment-info">
						<a href="<?php echo base_url('dosya/icerik/' . $yuklenen . '/' . $sirala[$i]); ?>" class="mailbox-attachment-name" target="_blank"><i class="fa fa-paperclip"></i> <?php echo $sirala[$i]; ?></a>
						<span class="mailbox-attachment-size">
							<?php echo dosyaBoyut(site_YOL . 'dosya/icerik/' . $yuklenen . '/' . $sirala[$i]); ?>
							<button type="button" onclick="silEminMisin('<?php echo $sirala[$i]; ?>');" class="btn btn-default btn-xs pull-right"><i class="fa fa-trash"></i></a>
						</span>
					</div>
				</li>

			<?php }	?>
		</ul>
	</div>

	<div class="box-footer">
		<a href="<?php echo base_url('admin/yukle/' . $yuklenen); ?>" class="btn bg-maroon"><i class="fa fa-arrow-left"></i> Geri (Yükleme Ekranına Git)</a>
	</div>
</div>

<script type="text/javascript">
	function silEminMisin(id) {
		bildirim("Emin Misiniz?", 'Bu dosyayı geri getirilemeyecek şekilde silmek istediğinizden emin misiniz?<br>\
		<a href="<?php echo base_url('adminB/yukle/' . $yuklenen . '/duzenle'); ?>?sil=' + id + '" class="btn btn-danger"><i class="fa fa-trash"></i> Evet Sil!</a>', "w");
		return false;
	}
</script>
