<?php
$toplamSonucSayisi = count($aramaSonuc);
if($hackdenemesi) // hack denemesi ise
{
	echo '<h1>凸( ͡° ͜ʖ ͡°)凸</h1><h2>You can\'t hack this Website ;)</h2>';
	yonetimeBildir(6, 'Sql açığı bulma girişimi', 'Sql açığı denemesinde bulunuldu. Araması: '.str_replace('\'','\\\'', $aranan));
}
?>
<h1>&quot; <?php echo ucfirst($aranan); ?> &quot; Araması</h1>
<form method="get" class="mb-3">
	<div class="row">
		<div class="col-lg-10 col-md-10 col-sm-10">
			<input class="form-control" name="ad" placeholder="Aramanız.." type="search" <?php echo (($aranan == 'Ara')?'':'value="'.$aranan.'"'); ?>>
		</div>
		<div class="col-lg-2 col-md-2 col-sm-2">
			<button value="Ara" type="submit" class="form-control btn btn-primary"><i class="fa fa-search"></i></button>
		</div>
	</div>
</form>

<h5><?php echo (($toplamSonucSayisi == 0)?'Aranan kelimeyle ilgili hiç veri bulunamadı :(':'Toplam ' . $toplamSonucSayisi . ' sonuç bulundu.'); ?></h5>


<?php $this->load->view('sayfa/vitrin', array('liste' => $aramaSonuc)); ?>


<p>Daha kısa bir arama yapmak bazen daha çok sonuç çıkmasını sağlayabilir.</p>
<b>Veya: </b><a href="https://www.google.com/search?q=<?php echo $aranan; ?>&amp;as_sitesearch=<?php echo base_url(); ?>" rel="nofollow" >Google üzerinden sitemde arama yapabilirsiniz.</a>

<?php
// Aramanın sonucu çıktıysa, paylaş tuşları görünsün
if ($toplamSonucSayisi != 0)
{
	$this->load->view('include/paylas', array('link' => base_url('sayfa/ara?ad=' . $aranan)));
}
?>
