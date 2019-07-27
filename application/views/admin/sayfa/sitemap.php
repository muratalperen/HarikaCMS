<?php // DEBUG: input type text yaptım ama float number olması lazım ?>
<div class="row">
	<div class="col-md-6 col-sm-12 col-xs-24">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Site Haritasını Düzenleyin</h3>
			</div>
			<!-- /.box-header -->
			<!-- form start -->
			<form role="form" action="<?php echo base_url('adminB/'); ?>seo/sitemap" method="post">
				<input type="hidden" name="sitemap" value="true">
				<div class="box-body">

					<div class="row">
						<div class="col-lg-5">
							<h4>Sitemap içeriği: </h4>
						</div>
						<div class="col-lg-7">
							<h4>Önem (0.00 - 1.00 arasında): </h4>
						</div>
					</div>
					<!-- İçerikler -->
					<div class="row">
						<div class="col-lg-5">
							<label>Kategoriler: </label>
						</div>
						<div class="col-lg-7">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" <?php echo (($seoAyar->kategori == 0)?'':'checked'); ?> onchange="checKapa(this);">
								</span>
								<input name="kategori" <?php echo (($seoAyar->kategori == 0)?'disabled':''); ?> class="form-control" type="text" max="1" min="0" value="<?php echo $seoAyar->kategori; ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-5">
							<label>Altkategoriler: </label>
						</div>
						<div class="col-lg-7">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" <?php echo (($seoAyar->altkategori == 0)?'':'checked'); ?> onchange="checKapa(this);">
								</span>
								<input name="altkategori" <?php echo (($seoAyar->altkategori == 0)?'disabled':''); ?> class="form-control" type="text" max="1" min="0" value="<?php echo $seoAyar->altkategori; ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-5">
							<label>Hakkında, iletişim vb.: </label>
						</div>
						<div class="col-lg-7">
							<div class="input-group">
								<span class="input-group-addon">
									<input type="checkbox" <?php echo (($seoAyar->sayfalar == 0)?'':'checked'); ?> onchange="checKapa(this);">
								</span>
								<input name="sayfalar" <?php echo (($seoAyar->sayfalar == 0)?'disabled':''); ?> class="form-control" type="text" max="1" min="0" value="<?php echo $seoAyar->sayfalar; ?>">
							</div>
						</div>
					</div>
					<!-- İçerikler -->

					<label for="icerik">Ek Site İçeriği:</label>
					<textarea name="icerik" rows="8" class="form-control" id="icerik" placeholder="Eklemek istediğiniz sitemap içeriğini yazabilirsiniz."><?php echo $seoAyar->ekMap; ?></textarea>
				</div>
				<!-- /.box-body -->

				<script type="text/javascript">
				function checKapa(elem) {//Checkbox kapanırsa, yazmayı engelleyecek
					elem.parentNode.parentNode.getElementsByTagName('input')[1].disabled = !(elem.checked);
					elem.parentNode.parentNode.getElementsByTagName('input')[1].value = ((elem.checked)?0.75:0);
				}
				var yaziArena = document.getElementById('icerik');
				//var icerik = yaziArena.value;
				//yaziArena.value = icerik;
				</script>

				<div class="box-footer">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Güncelle</button>
					<div class="text-right">
						<button type="button" class="btn btn-app" onclick="yaziArena.value = yaziArena.value + '\n<url>\n<loc><?php echo base_url(); ?></loc>\n<lastmod><?php echo date('Y-m-d'); ?>T09:00:04+00:00</lastmod>\n<priority>0.80</priority>\n<changefreq>monthly</changefreq>\n</url>';">
							<i class="fa fa-plus"></i> Sayfa Ekle
						</button>
						<button type="reset" class="btn btn-app" >
							<i class="fa fa-times-circle-o"></i> Sıfırla
						</button>
						<button type="button" class="btn btn-app" onclick="bildirim('Sitemap (sitemap.xml) Hakkında', ' Seçtiğiniz içerikler otomatik olarak oluşturulacaktır. Olur da ayrı bir sayfa oluşturursanız, ek bölüme, her zamanki sitemap yazma kuralları ile ekleyebilirsiniz.');" >
							<i class="fa fa-info"></i> Bilgi
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="col-md-6 col-sm-12 col-xs-24">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">İçerik Haritasını Düzenleyin</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<p>Şu an içerik haritasında <b><?php echo $icerikmapSayisi; ?></b> sayfa var.</p>
				<div class="row">
					<form role="form" method="post" action="<?php echo base_url('adminB/'); ?>seo/sitemap">
						<div class="col-lg-4">
							<label>Önem: </label>
						</div>
						<div class="col-lg-6">
							<input name="onem" class="form-control" type="text" placeholder="0.00 ile 1.00 arasında bir değer girin." max="1" min="0" value="<?php echo $seoAyar->urunOnem; ?>">
						</div>
						<div class="col-lg-2">
							<input class="form-control btn btn-primary" type="submit" value="Güncelle">
						</div>
					</form>
				</div>
				<button type="button" class="btn btn-app" onclick="bildirim('İçerik Haritası (urunmap.xml) Hakkında', 'Sitenin sayfaları dışında oluşmuş, sitenin içeriğini oluşturan ürünler bu haritada saklanır. Bunu site kendisi oluşturur.');" >
					<i class="fa fa-info"></i> Bilgi
				</button>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<!-- <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Güncelle</button> -->
			</div>
		</div>
	</div>
</div>
