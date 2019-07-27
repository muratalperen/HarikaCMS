<h1><?php echo $buUrun->ad; ?></h1>
<div class="w-100 text-muted mb-2">
	<span><i class="fa fa-eye"></i> <?php echo $buUrun->goruntulenme; ?> Görüntülenme</span>
	<span><i class="fa fa-calendar"></i> <?php echo $buUrun->tarih; ?></span>
</div>

<img class="m-3 img-fluid" src="<?php echo (isset($onIzleme) ? '' : $u->urunLink($buUrun)); ?>/resim" alt="<?php echo $buUrun->ad; ?>" id="urunAnaResmi">
<h2>&emsp;<?php echo $buUrun->aciklama; ?></h2>

<!-- İçerik -->
<?php echo $buUrun->icerik; ?>
<!-- //İçerik -->

<!-- Blog Alt -->
<?php
// Varsa kaynakları göster
if ( ! empty($buUrun->kaynak)): ?>
	<hr>
	<div class="alert alert-info">
		<h4>Kaynak</h4>
		<ul>
			<?php foreach (explode(',', $buUrun->kaynak) as $kaynak): ?>
				<li><a href="<?php echo $kaynak; ?>" rel="nofollow" target="_blank"><?php echo $kaynak; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<br>
<?php endif; ?>

<br>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="text-center">
			<button class="btn btn-success" onclick="dondur('begen');" ><i class="fa fa-thumbs-o-up"></i> <span id="begenSpan"><?php echo $buUrun->begen; ?></span></button>
			<button class="btn btn-danger" onclick="dondur('begenme');"><i class="fa fa-thumbs-o-down"></i> <span id="begenmeSpan"><?php echo $buUrun->begenme; ?></span></button>
		</div>
	</div>
</div>

<?php
$this->load->view('include/paylas', array('link' => $u->urunLink($buUrun)));
?>

<!-- Önerilenler -->
<div class="mt-2">
	<h2>Önerilen Gönderiler <i class="fa fa-thumbs-o-up"></i></h2>

		<?php if($onerilenler == null): ?>
			<h5>&quot;<?php echo $u->kateg[$buUrun->kategori]; ?>&quot; kategorisi, <?php echo $u->altkateg[$buUrun->kategori][$buUrun->altkategori]; ?> alt kategorisinde başka yazı bulunamadı.</h5>
		<?php
		else:
			foreach ($onerilenler as $onerilen):
				if ($onerilen->sef != $buUrun->sef):
					?>

					<div class="card flex-md-row mb-4 box-shadow h-md-250 mt-2">
            <div class="card-body d-flex flex-column align-items-start">
              <small class="d-inline-block mb-2"><i class="fa fa-eye"></i> <?php echo $onerilen->goruntulenme; ?></small>
              <h3 class="mb-0">
                <a class="text-dark" href="<?php echo $u->urunLink($onerilen); ?>"><?php echo $onerilen->ad; ?></a>
              </h3>
              <div class="mb-1 text-muted"><?php echo $onerilen->tarih; ?></div>
              <p class="card-text mb-auto"><?php echo $onerilen->aciklama; ?></p>
              <a href="<?php echo $u->urunLink($onerilen); ?>">Git <i class="fa fa-arrow-right"></i></a>
            </div>
            <img class="card-img-right flex-auto d-none d-md-block" alt="Thumbnail" src="<?php echo $u->urunLink($onerilen); ?>/thumb">
          </div>

					<?php
				endif;
			endforeach;
		endif;
		?>

</div>
<!-- //Önerilenler -->

<!-- Yorum Bölümü -->
<div id="yorumBolumu">
	<h4><i class="fa fa-comment-o"></i> Yorum</h4>

	<form class="form" action="<?php echo base_url('api/yorum/yap'); ?>" method="post">
		<input type="hidden" name="urunID" value="<?php echo $buUrun->id; ?>">
		<div class="form-group">
			<label for="adYorum">İsminiz:</label>
			<input class="form-control" id="adYorum" type="text" name="ad" maxlength="30" required>
		</div>
		<div class="form-group">
			<label for="emailYorum">E-posta adresiniz:</label>
			<input class="form-control" id="emailYorum" type="email" name="mail" maxlength="35" required>
		</div>
		<div class="form-group">
			<label for="siteYorum">Siteniz: <small>(Zorunlu değildir)</small></label>
			<input class="form-control" id="siteYorum" type="url" name="site" maxlength="25">
		</div>
		<div class="form-group">
			<input type="hidden" name="yanitID">
			<input type="button" class="form-control" value="Cevaplamaktan Vazgeç" id="yorumsifirlabuton" onclick="yorumsifirla();" style="display:none;">
			<label for="yorumYorum" id="yorumYazisi">Yorum:</label>
			<textarea class="form-control" id="yorumYorum" name="icerik" maxlength="255" required></textarea>
		</div>
		<div class="checkbox">
			<label> <input type="checkbox" name="kaydol" checked> Haber Bültenine Abone Ol </label>
		</div>
		<input type="hidden" name="redirect" value="<?php echo $u->urunLink($buUrun); ?>">
		<button class="btn btn-primary" type="submit"><i class="fa fa-paper-plane"></i> Gönder</button>
	</form>
	<br>
	<button class="btn btn-default" type="button" id="tumYorumlarGosterButton" onclick="yorumlariYukle();"><i class="fa fa-comment"></i> Tüm Yorumları Göster</button>
	<div id="tumYorumlar" class="">

	</div>
</div>



<script type="text/javascript" id="siteIciKodlar">
//	Yorum Kodları

var yrmElem = document.getElementById('yorumBolumu');

function kullaniciResimHata(elem) {
	console.debug("Gravatar sitesine ulaşılamadığından resim değiştirildi.");
	elem.src = '<?php echo base_url('rel/img/site/kisi.png'); ?>';
}

// Verilen ID'ye sahip yoruma cevap olarak verir
function cevapla(yorumID, yorumSahibi)
{
	document.getElementsByName('yanitID')[0].value = yorumID;
	document.getElementById('yorumYazisi').innerHTML = "Cevapla: " + yorumSahibi;
	window.location.href = "#yorumBolumu";
}

// Cevap vermeyi kapatır
function yorumsifirla()
{
	document.getElementsByName('yanitID')[0].value = "";
	document.getElementById('yorumYazisi').innerHTML = "Yorum: ";
}

// Yorum elementi oluşturur
function yorumElementOlustur(id, mail, ad, site, tarih, icerik, cevapSira = 0)
{
	// IDEA: Burada vue kullansan tadından yenmez
	//https://tr.gravatar.com/site/implement/profiles/php/ eklenecek
	return '\
	<div class="yorum cevap-' + cevapSira + '" id="msj' + id + '">\
		<div class="yorumKullanici">\
			<img src="http://www.gravatar.com/avatar/' + mail + '?d=<?php echo base_url('rel/img/site/kisi.png'); ?>&s=50" alt="Kullanıcı Resmi" onerror="kullaniciResimHata(this);">\
			<span class="yorumKullaniciAdi">' + ((site == "")?'<a href="http://www.gravatar.com/' + mail + '" title="' + ad + ' gravatar hesabı" target="_blank" rel="nofollow">' + ad + '</a>':'<a rel="nofollow" target="_blank" href="' + site + '" >' + ad + '</a>') + '</span>\
			<span class="yorumKullaniciEk">' + tarih + '</span>\
		</div>\
		<p>' + icerik + '</p>\
		' + ((cevapSira != 2) ? '<a onclick="cevapla(' + id + ', \'' + ad + '\');"><i class="fa fa-reply"></i> Cevapla</a>' : '') + '\
	</div>';
}

// Bu ürünün yorumlarını gösterir
function yorumlariYukle(yorumaGit = null)
{
	tumYorumlar.innerHTML = '<h1 class="fa fa-refresh fa-spin"></h1>';//Yükleniyor ekranı

	$.get("<?php echo base_url('api/yorum/'); ?>al", {urunID: <?php echo $buUrun->id; ?>}, function (gelen_cevap, gelen_stat){
		if (gelen_stat == 404)
		{
			alert("404, Sayfa bulunamadı hatası. İnternet bağlantınızı kontrol edin.");
		}
		else if(gelen_cevap == null || gelen_cevap == "" || gelen_stat != "success")
		{
			alert("Bağlantı Hatası. İsteğe gelen_cevap gelmedi veya bir sunucu hatası. Tekrar deneyin.");
		}
		else if (gelen_cevap == "0" || gelen_cevap == "false")
		{
			alert("Sistemsel bir hata oluştu.");
		}
		else
		{
			try{
				var jsonuc = JSON.parse(gelen_cevap);
			}catch(hata){
				alert("Sunucu Hatası. Sunucudan gelen veriler işlenemeyecek biçimde. Sorun devam ederse sayfayı yenileyebilir veya yöneticiyle iletişime geçebilirsiniz. Hata: " + hata);
			}
			if (jsonuc != null) {
				document.getElementById('tumYorumlarGosterButton').remove();

				var tumYorumlar = document.getElementById('tumYorumlar');

				if (jsonuc == 0) {
					tumYorumlar.innerHTML = "<h5>Hiç yorum bulunamadı.</h5>";
				} else {
					tumYorumlar.innerHTML = "<h5>Toplam " + jsonuc.length + " yorum.</h5><br>";

					for (var i = 0; i < jsonuc.length; i++) {
						if (jsonuc[i]['yanitID'] == 0) {//Bu bir cevap değilse
							tumYorumlar.innerHTML += yorumElementOlustur(jsonuc[i]['id'], jsonuc[i]['mail'], jsonuc[i]['ad'], jsonuc[i]['site'], jsonuc[i]['tarih'], jsonuc[i]['icerik'], 0);

							for (var j = 0; j < jsonuc.length; j++) {
								if (jsonuc[j]['yanitID'] == jsonuc[i]['id']) {//Şimdiki yorumun cevabıysa
									tumYorumlar.innerHTML += yorumElementOlustur(jsonuc[j]['id'], jsonuc[i]['mail'], jsonuc[j]['ad'], jsonuc[j]['site'], jsonuc[j]['tarih'], jsonuc[j]['icerik'], 1);

									for (var w = 0; w < jsonuc.length; w++) {
										if (jsonuc[w]['yanitID'] == jsonuc[j]['id']) {//Şimdiki cevabın cevabıysa
											tumYorumlar.innerHTML += yorumElementOlustur(jsonuc[w]['id'], jsonuc[i]['mail'], jsonuc[w]['ad'], jsonuc[w]['site'], jsonuc[w]['tarih'], jsonuc[w]['icerik'], 2);
										}
									}

								}
							}

						}
					}

					tumYorumlar.innerHTML += "Tüm yorumlar yüklendi.";
					if (yorumaGit != null) {
						window.location.href = "#msj" + yorumaGit;
					}

				}
			}
		}
	});
}



function dondur(donen){//Beğeni işlemleri
	$.post('<?php echo base_url('api/begeni'); ?>', {donus: donen, urunID: <?php echo $buUrun->id; ?>}, function (gelen) {
		if(gelen == "yt"){	//Tıklananı bir arttır
			var degersimdi = document.getElementById(donen + 'Span').innerHTML;
			degersimdi = parseInt(degersimdi) + 1;
			$('#' + donen + 'Span').html(degersimdi);
		}else if(gelen == "vt"){	//Tekrar tıklanmış, bir azalt
			var degersimdi = document.getElementById(donen + 'Span').innerHTML;
			degersimdi = parseInt(degersimdi) - 1;
			$('#' + donen + 'Span').html(degersimdi);
		}else if(gelen.substr(0,2) == 'dt'){	//Başka birine tıklanmış; birini azalt, diğerini arttır
			var degersimdi = document.getElementById(donen + 'Span').innerHTML;
			degersimdi = parseInt(degersimdi) + 1;
			$('#' + donen + 'Span').html(degersimdi);
			var azallan = document.getElementById(gelen.substr(2) + 'Span').innerHTML;
			degersimdi = parseInt(azallan) - 1;
			$('#' + gelen.substr(2) + 'Span').html(degersimdi + '');
		}else{
			if(gelen){
				alert('Hata: ' + gelen);
			}else{
				alert('Hata: İsteğe cevap gelmedi. İnternet bağlantınızı kontrol edin.');
			}
		}
	});
}
</script>
<!-- //Yorum Bölümü -->

<!-- //Blog Alt -->


<?php if (isset($onIzleme)): // Ön izleme yapılıyor. ?>
<script type="text/javascript">

// Tüm tuşların çalışmasını engelliyoruz
// Begenme tuşlarından birine basıldığında tabloda olmayan ürün beğenilmiş oluyor. Ama tabloda olmaması işlemin hatalı olduğunu göstermez. Bu yüzden beğenme işlemi oluyor gibi görünüyor
var butonlar = document.getElementsByTagName("main")[0].getElementsByTagName("button");
for (i=0;i<butonlar.length;i++) {
	butonlar[i].onclick = "";
}

// Site içindeki javascript'i kapatıyoruz
document.getElementById("siteIciKodlar").innerHTML = "";

// Ana resmi koyuyoruz
document.getElementById("urunAnaResmi").src = "<?php echo (empty($onIzleme['resim64'])?base_url('rel/img/site/resmiUnuttun.png'):'data:image/png;base64,' . $onIzleme['resim64']); ?>";

</script>
<?php endif; ?>
