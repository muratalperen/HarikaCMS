<div class="box box-primary">

	<div class="box-header with-border">
		<h3 class="box-title">Yeni Ürün Ekleme Menüsü</h3>
		<?php echo (isset($urun) ? '<small><a href="' . $u->urunLink($urun) . '" target="_blank">Bu ürüne git <i class="fa fa-external-link"></i></a></small>' : ''); ?>
	</div>


	<form id="urunForm" role="form" action="<?php echo base_url('adminB/urun/') . (isset($urun) ? 'duzenle/' . $urun->id : 'ekle'); ?>" method="post" enctype="multipart/form-data">
		<?php echo (isset($urun) ? '<input type="hidden" name="id" value="' . $urun->id . '">' : ''); ?>
		<div class="box-body">

			<!-- Ürün Adı -->
			<div class="form-group">
				<label for="ad">Ürün Adı</label>
				<input name="ad" class="form-control" id="ad" placeholder="Ürünün adını giriniz" type="text" maxlength="80" <?php echo (isset($urun)?'value="'. $urun->ad .'"':''); ?> required>
			</div>

			<!-- Kategori ve Alt Kategori Seçme -->
			<div class="form-group">
				<div class="row">
					<div class="col-xs-6">
						<label for="kategori">Kategori</label>
						<select name="kategori" id="kategori" class="form-control" onchange="altKategoriAyarla (this.value);">
							<?php
							for ($i=0; $i < $u->kategoriSayisi; $i++) {
								echo '<option value="'.$i.'">'.$u->kateg[$i].'</option>';
							}
							?>
						</select>
					</div>
					<div class="col-xs-6">
						<label for="altkategori">Alt Kategori</label>
						<select name="altkategori" id="altkategori" class="form-control">
						</select>
						<script type="text/javascript">
						var altKategoriler = [
							<?php
							for ($i=0; $i< $u->kategoriSayisi; $i++){
								echo '[';
								for ($w=0; $w < count($u->altkateg[$i]); $w++) {
									echo '"' . $u->altkateg[$i][$w] . '"' . (($w + 1 != count($u->altkateg[$i]))?',':'');
								}
								echo ']' . (($i + 1 != $u->kategoriSayisi )?',':'');
							}
							?>
						];
						var altKategoriNesnesi = document.getElementById("altkategori");
						function altKategoriAyarla (kategori){
							var optionlar = "";
							for (i=0; i < altKategoriler[kategori].length; i++){
								optionlar = optionlar + '<option value="' + i + '">' + altKategoriler[kategori][i] + '</option>';
							}
							altKategoriNesnesi.innerHTML = optionlar;
						}

						<?php

						if (isset($urun)) {
							echo '
							document.getElementById ("kategori").value = ' . $urun->kategori . ';
							altKategoriAyarla (' . $urun->kategori . ');
							altKategoriNesnesi.value = ' . $urun->altkategori . ';';
						} else {
							echo 'altKategoriAyarla (0);';
						}

						?>
						</script>
					</div>
				</div>
			</div>

			<!-- Ürün Resmi Seçme -->
			<div class="form-group">
				<label for="resmi"><i class="fa fa-file-image-o"></i> Ürün Resmi</label>
				<input id="resmi" type="file" name="resim" class="btn btn-info" <?php echo (isset($urun) ? '' : 'required'); ?>>
				<p class="help-block">Ürün için jpg formatında bir resim seçin.
					<?php echo (isset($urun) ? 'Boş bırakılırsa önceki resim kalır.' : ''); ?>
				</p>
			</div>

				<hr>
				<!-- Ek ürün bilgileri -->

			<div class="form-group">
				<label for="aciklama">Açıklama</label>
				<input name="aciklama" class="form-control" id="aciklama" placeholder="Ürün hakkında kısa açıklama" type="text" maxlength="255" required  <?php echo (isset($urun)?'value="'. $urun->aciklama .'"':''); ?>>
			</div>

			<div class="form-group">
				<label for="kaynak">Kaynak</label>
				<textarea name="kaynak" class="form-control" id="kaynak" placeholder="Aralarına virgül koyarak kaynak listesi"><?php echo (isset($urun)?$urun->kaynak:''); ?></textarea>
			</div>

			<div class="form-group">
				<div class="row">
					<div class="col-md-11">
						<label for="icerik">İçerik</label> <small>Markdown ile yazın</small>
						<textarea name="icerik" rows="20" class="form-control" id="icerik" placeholder="İçerik Girin" onchange="Seo.freqHesapla();" required><?php echo (isset($urun) ? htmlspecialchars($urun->icerik) : ''); ?></textarea>
					</div>
					<div class="col-md-1">
						<label>Anahtar Kelime</label><hr>
						<div id="yaziFrequency">

						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="box-footer">
			<!-- Tuşlar -->
			<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>  <?php echo (isset($urun) ? 'Güncelle' : 'Ekle'); ?></button>
			<button type="button" class="btn btn-info" onclick="yaziBilgilendir();"><i class="fa fa-info"></i>  Yardım</button>
			<button type="button" class="btn bg-orange" onclick="onizle();"><i class="fa fa-eye"></i>  Ön İzleme</button>
			<script type="text/javascript">
			window.onload = function () {
				Seo = new seo();
			}
			function onizle() {
				var fomr = document.getElementById('urunForm');
				var eskiAction = fomr.action;
				fomr.target = "_blank";
				fomr.action = "<?php echo base_url('admin/urun/onizle'); ?>";
				fomr.submit();
				//Eski haline çevir
				fomr.action = eskiAction;
				fomr.target = "_self";
			}
			function yaziBilgilendir() {
				bildirim('Yazı İçeriği İle İlgili', '\
				<h3>Markdown</h3>\
				<p>Yazıları markdown ile yazın.</p>\
				<h3>Ön Tanımlamalar</h3>\
				<p>Bazı ön tanımlamalar vardır. &quot;{tanimAdi}&quot; şeklinde kullanılır. Bu tanımlar, sayfa yüklenirken olması gerekenle yer değiştirir. Tanımlar:</p>\
				<ul>\
				<li>rD : Resim Dizini</li>\
				<li>dD : Dosya Dizini</li>\
				<li>sD : Site anasayfa</li>\
				</ul>\
				<button type="button" onclick="ornekle();" class="btn btn-primary">Örnek Gör</button>\
				');
			}

			function ornekle(){
				document.getElementById('icerik').value = '\
Bu sistemde yazı yazmak için Markdown kullanılmaktadır. 5dk içinde öğrenin! [Markdown Öğren](https://suleymanergen.com/genel/markdown)\n\n\
Bir paragraf. Markdownda paragraf yapmak için yeni satıra geçmeniz yeterli. Bu şekilde bir paragraf yapabilirsiniz. Veya yıldız karakteri ile *italik* veya çift yıldız ile **kalın** yazabilirsiniz.\n\n\
Ve alt satıra geçerek yeni paragraf yapabilirsiniz. Daha fazla bilgi için internetten Markdown yazımını öğrenebilirsiniz. Birçok sistem markdown kullanmaktadır.\n\n\
## H2 Başlığı ##\n\n\
### Alt Başlık ###\n\n\
Kare(Hashtag) sayısına göre daha küçük başlık yapabilirsiniz. Bağlantı koymak için [Bağlantı yazısı](http://baglanti/) şeklinde yazabilirsiniz. Resim için ise başına ünlem koymanız yeterli.\n\n\
Kendi sitenize resim yükleyip resmi sitenize koymak isterseniz, resmi yükledikten sonra köşeli parantezler arasına anahtar kelimeyi yazın. Sayfa oluşturulurken bu anahtar kelimeler, tanımlı yazılarla değiştirilecekler. Anahtar kelimeleri görmek için yardım tuşuna basın.\n\n\
Örnek olarak hakkında sayfasına [buradan]({sD}sayfa/statik/hakkinda) ulaşabilirsiniz. Veya yükledikten sonra ![Resim bilgisi]({rD}resim-adi.png) gibisinden resmi ekleyebilirsiniz.';
			}
			</script>

			<?php if (isset($urun)): ?>
				<button type="button" onclick="silEminMisin(<?php echo $urun->id; ?>);" class="btn btn-warning"><i class="fa fa-trash"></i> Sil</button>
				<script type="text/javascript">
				function silEminMisin(id) {
					bildirim("Emin Misiniz?", 'Bu ürünü geri getirilemeyecek şekilde silmek istediğinizden emin misiniz?<br>\
					<a href="<?php echo base_url('adminB/') . 'urun/duzenle/'; ?>' + id + '?sil=true" class="btn btn-danger"><i class="fa fa-trash"></i> Evet Sil!</a>', "w");
					return false;
				}
				</script>
			<?php endif; ?>
		</div>
	</form>
</div>
