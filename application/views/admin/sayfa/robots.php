<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Robots.txt Düzenleme</h3>
	</div>
	<!-- /.box-header -->
	<!-- form start -->
	<form role="form" action="<?php echo base_url('adminB/'); ?>seo/robots" method="post">
		<div class="box-body">
			<label for="icerik">Robots.txt İçeriği</label>
			<textarea name="icerik" rows="15" class="form-control" id="icerik" placeholder="Sitenizin robots.txt dosyasını buraya yazabilirsiniz." required><?php echo $robotsIcerik; ?></textarea>
		</div>
		<!-- /.box-body -->
		<script type="text/javascript">
		var yaziArena = document.getElementById('icerik');
		var icerik = yaziArena.value;
		function icerigi (){
			return icerik;
		}
		yaziArena.value = icerik;
		</script>
		<div class="box-footer">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Güncelle</button>
			<div class="text-right">
				<button type="button" class="btn btn-app" onclick="yaziArena.value = icerigi();">
					<i class="fa fa-repeat"></i> Geri Getir
				</button>
				<button type="button" class="btn btn-app" onclick="yaziArena.value = '#Site taranmaya kapalı\nUser-agent: *\nDisallow: /';">
					<i class="fa fa-ban"></i> Taranmayı Engelle
				</button>
				<button type="button" class="btn btn-app" onclick="yaziArena.value = '#Önerilen robots.txt dosyası\nUser-agent: *\nAllow: /\nDisallow: /gizli/\nDisallow: /admin/\nDisallow: /system/\nDisallow: /application/\nSitemap: <?php echo base_url(); ?>sitemap.xml\nSitemap: <?php echo base_url(); ?>urunmap.xml';">
					<i class="fa fa-sticky-note-o"></i> Önerilen
				</button>
				<button type="button" class="btn btn-app" onclick="bildirim('Robots.txt Hakkında', ' Site haritalarına ana dizinde sitemap.xml ve urunmap.xml olarak ulaşabilirsiniz. Robots.txt, seo\'yu etkileyen önemli bir etmendir. Nasıl kullanıldığını bilmiyorsanız kullanmayın!');" >
					<i class="fa fa-info"></i> Bilgi
				</button>
			</div>
		</div>
	</form>
</div>
