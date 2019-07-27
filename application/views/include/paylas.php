<?php // $link değişkeni ile paylaş tuşlarını gösterir. ?>
<!-- Paylaş Tuşları -->
<div class="w-100 m-2">
	<ul class="list-inline text-center">
		<li class="m-1 list-inline-item"><a class="btn btn-md text-white pFacebook" target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo $link; ?>" title="'Facebook'da Paylaş"><i class="fa fa-facebook"></i></a></li>
		<li class="m-1 list-inline-item"><a class="btn btn-md text-white pTwitter" target="_blank" href="https://twitter.com/share?url=<?php echo $link; ?>" title="Twitter'da Paylaş"><i class="fa fa-twitter"></i></a></li>
		<li class="m-1 list-inline-item"><a class="btn btn-md text-white pLinkedin" target="_blank" href="http://www.linkedin.com/shareArticle?url=<?php echo $link; ?>" title="Linkedin'de Paylaş"><i class="fa fa-linkedin"></i></a></li>
		<li class="m-1 list-inline-item"><a class="btn btn-md text-white pWhatsapp" target="_blank" href="whatsapp://send?text=<?php echo $link; ?>" title="Whatsapp'da Paylaş"><i class="fa fa-whatsapp"></i></a></li><!-- fa-whatsapp çalışmıyor -->
	</ul>
</div>
<!-- / Paylaş Tuşları -->
