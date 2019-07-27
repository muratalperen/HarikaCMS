<div class="row">
	<div class="col-md-3">

		<div class="box box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Bildirimler</h3>

				<div class="box-tools">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="box-body no-padding">
				<ul class="nav nav-pills nav-stacked">
					<li<?php echo (($_GET['onem'] == 0)?' class="active"':''); ?>><a href="<?php echo base_url('admin/'); ?>bildirimler?onem=0"><i class="fa fa-inbox"></i> Tüm Bildirimler     <span class="label label-success pull-right"><?php echo $headVar['bildirim'][0]; ?></span></a></li>
					<li<?php echo (($_GET['onem'] == 1)?' class="active"':''); ?>><a href="<?php echo base_url('admin/'); ?>bildirimler?onem=1"><i class="fa fa-inbox"></i> Orta Bildirimler    <span class="label label-primary pull-right"><?php echo $headVar['bildirim'][1]; ?></span></a></li>
					<li<?php echo (($_GET['onem'] == 2)?' class="active"':''); ?>><a href="<?php echo base_url('admin/'); ?>bildirimler?onem=2"><i class="fa fa-inbox"></i> Önemli Bildirimler  <span class="label label-warning pull-right"><?php echo $headVar['bildirim'][2]; ?></span></a></li>
					<li<?php echo (($_GET['onem'] == 3)?' class="active"':''); ?>><a href="<?php echo base_url('admin/'); ?>bildirimler?onem=3"><i class="fa fa-inbox"></i> Hayati Bildirimler  <span class="label label-danger pull-right" ><?php echo $headVar['bildirim'][3]; ?></span></a></li>
					<?php
					if ($admin->duzey < 5) {
						echo '<li '. (($_GET['onem'] == 4)?' class="active"':'') .'><a href="'. base_url('admin/') .'bildirimler?onem=4"><i class="fa fa-info-circle"></i> Üst Düzey Bildirimler  <span class="label label-danger pull-right" >'. $headVar['bildirim'][4]. '</span></a></li>';
					} ?>
				</ul>
			</div>
		</div>
	</div>
	<!-- /.col -->
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Gelen Bildirimler</h3>

				<div class="box-tools pull-right">
					<div class="has-feedback">
						<input type="text" class="form-control input-sm" placeholder="Bildirimler arasında ara">
						<span class="fa fa-search form-control-feedback"></span>
					</div>
				</div>
				<!-- /.box-tools -->
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding">
				<div class="mailbox-controls">
					<!-- Check all button -->
					<button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
					</button>
					<div class="btn-group">
						<button type="button" onclick="sil();" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
						<!--<button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
						<button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>-->
					</div>
					<!-- /.btn-group -->
					<button type="button" onclick="window.location.reload();" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
					<div class="pull-right">
						<?php
						if ($gGoster == 0) {
							echo '0-' . ( ($gGoster + 50 > $headVar['bildirim'][$gOnem]) ? $headVar['bildirim'][$gOnem] : '50');
						} else {
							echo $gGoster . '-' . ( ($gGoster + 50 > $headVar['bildirim'][$gOnem]) ? $headVar['bildirim'][$gOnem] : $gGoster + 50);
						}
						echo '/' . $headVar['bildirim'][$gOnem];
						?>
						<div class="btn-group">
							<?php // IDEA: Codeingiter Pagination kullan ?>
							<a <?php echo (($gGoster-50 < 0)?'disabled':''); ?> href="<?php echo base_url('admin/bildirimler?') . 'goster='. ($gGoster-50) . (($gOnem == 0)?'':'&onem='.$gOnem); ?>" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
							<a <?php echo (($gGoster+50 < $headVar['bildirim'][$gOnem])?'':'disabled'); ?> href="<?php echo base_url('admin/bildirimler?') . 'goster='. ($gGoster+50) . (($gOnem == 0)?'':'&onem='.$gOnem); ?>" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
						</div>
					</div>
					<!-- /.pull-right -->
				</div>
				<div class="table-responsive mailbox-messages">
					<table class="table table-hover table-striped">
						<tbody>
							<?php
							foreach ($bildirimler as $bild) {
								echo '<tr>
								<td><input type="checkbox" name="'. $bild->id .'"></td>
								<td class="mailbox-star">'. $bild->onem .'</td>
								<td class="mailbox-name"><a href="#">'. $bild->baslik .'</a></td>
								<td class="mailbox-subject"><b>'. $bild->ip .'</b> - '. $bild->uyari/*word limiter*/ .'</td>
								<td class="mailbox-date">'. $bild->tarih .'</td>
								</tr>';
							}
							?>
						</tbody>
					</table>
					<!-- /.table -->
				</div>
				<!-- /.mail-box-messages -->
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /. box -->
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<script type="text/javascript">
function sil (){
	var checkler = document.getElementsByClassName('table table-hover table-striped')[0].getElementsByTagName("input");
	var silinecekURL = "";
	for(var i=0; i<checkler.length; i++){
		if(checkler[i].checked){
			silinecekURL += "," + checkler[i].name;
		}
	}
	if (silinecekURL == "") {
		bildirim("Boş Tıklamayın", "Silme tuşuna basmadan önce silmek istediğiniz bildirimleri seçin.", "w");
	}else{
		silinecekURL = silinecekURL.substr(1);
		$.post('<?php echo base_url('adminB/'); ?>bildirimler', {sil: silinecekURL}, function(gelen_cevap){
			if(gelen_cevap == null || gelen_cevap == ""){
				bildirim("Bağlantı Hatası", "İsteğe cevap gelmedi veya bir sunucu hatası. İnternet bağlantınızı kontrol edin.", "d");
			}else{
				if (gelen_cevap == "0") {
					bildirim("Bir Hata", "Bilinmeyen bir hata oluştu.", "d");
				}else if(gelen_cevap == "1"){
					bildirim("Silindi", "Bildirimler başarıyla silindi", "s");
					var gizlenecekDivler = silinecekURL.split(",");
					for (var i = 0; i < gizlenecekDivler.length; i++) {
						document.getElementsByName(gizlenecekDivler[i])[0].parentNode.parentNode.parentNode.style.display = "none";
					}
				}else{
					bildirim("Silinemedi", "Bir hata oluştu. Sorun devam ederse yöneticinizle veya yazılımcınızla görüşünüz. Hata: " + gelen_cevap, "d");
				}
			}
		});
	}
}
</script>
