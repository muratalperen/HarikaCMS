<!DOCTYPE html>
<html lang="tr">
<head>

	<!-- SEO -->
	<meta charset="UTF-8" />
	<title><?php echo $meta['baslik'] . ' | ' . $this->config->item('site')->ad; ?></title>
	<meta name="description" content="<?php echo $meta['aciklama']; ?>" />
	<meta name="keywords" content="<?php echo $meta['taglar']; ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="author" content="<?php echo $this->config->item('site')->ad; ?>" /><?php // IDEA: Site yazarı konabilir ?>
	<meta name="subject" content="<?php echo $meta['aciklama']; ?>">
	<!-- <meta name="Classification" content="News"> -->
	<meta name="geography" content="Turkey">
	<meta name="reply-to" content="<?php echo $this->config->item('site')->iletisimMail; ?>">
	<!-- <meta name="owner" content="Sahip"> -->
	<!-- <meta name="category" content="News"> -->
	<meta name="coverage" content="global">
	<meta name="rating" content="general">
	<meta HTTP-equiv="Content-Language" content="tr-TR">
	<meta name="robots" CONTENT="FOLLOW,INDEX">
	<meta name="theme-color" content="#ffffff">

	<meta property="og:type" content="article">
	<meta property="og:url" content="<?php echo base_url($this->urI->uri_string); ?>">
	<meta property="og:locale" content="tr_TR" />
	<meta property="og:site_name" content="<?php echo $this->config->item('site')->ad; ?>" />
	<meta property="og:title" content="<?php echo $meta ['baslik']; ?>" >
	<meta property="og:description" content="<?php echo $meta ['aciklama']; ?>" />
	<meta property="og:image" content="<?php echo (isset($buUrun)) ? base_url($this->urI->uri_string) . '/resim' : base_url('favicon.ico'); ?>" >
	<link rel="shortcut icon" href="<?php echo base_url('favicon.ico'); ?>" type="image/x-icon" sizes="16x16 24x24 32x32"/>
	<!-- //SEO -->


	<!-- Stil Dosyaları -->
	<script type="text/javascript">
	function linkError(dosyaAdi, uzanti, elem){
		console.debug('CDN\'ye ulaşmada sorun çıktığı için yereldeki içerik (' + dosyaAdi + '.' + uzanti + ') kullanıldı.');
		if(uzanti == "css"){
			if (elem.href != "<?php echo base_url('rel/css/'); ?>" + dosyaAdi + ".css") {
				elem.href = "<?php echo base_url('rel/css/'); ?>" + dosyaAdi + ".css";
			}
		} else if(uzanti == "js") {
			if (elem.src != "<?php echo base_url('rel/js/'); ?>" + dosyaAdi + ".js") {
				elem.src = "<?php echo base_url('rel/js/'); ?>" + dosyaAdi + ".js";
			}
		}
	}
	</script>
	<!-- <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" onerror="linkError('bootstrap.min', 'css', this);"> -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('rel/css/'); ?>bootstrap.min.css" onerror="linkError('bootstrap.min', 'css', this);">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" onerror="linkError('font-awesome.min', 'css', this);">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('rel/css/'); ?>style.css">

	<?php
	if (isset ($ekleCSS)){
		foreach ($ekleCSS as $stilYolu) {
			echo '<link rel="stylesheet" href="' . base_url('rel/css/') . $stilYolu . '.css" >';
		}
	}

	// Zengin Kartlar
	if (isset($buUrun)) {
		echo '<script type="application/ld+json">{
  		"@context" : "http://schema.org",
  		"@type" : "Article",
  		"name" : "' . $buUrun->ad . '",
  		"author" : {
    		"@type" : "Person",
    		"name" : "' . $buUrun->adminAd . '"
  		},
  		"articleSection" : "' . $buUrun->taglar . '",
  		"url" : "' . base_url($this->urI->uri_string) . '",
  		"publisher" : {
    		"@type" : "Organization",
    		"name" : "' . $this->config->item('site')->ad . '"
  		}
		}</script>';
	}

	?>
</head>
<body>

	<?php if (isset($adminInfo)): ?>
		<!-- Yöneticiler İçin Yan Panel -->
		<div id="yanAdminPanel">
			<img class="img-fluid p-2" src="<?php echo base_url('rel/') . 'img/admin/' . $adminInfo->id; ?>.jpg">
			<h1><?php echo $adminInfo->ad; ?></h1>
			<hr>
			<?php if (isset($buUrun)): ?>
				<a href="<?php echo $u->urunLink($buUrun); ?>" class="btn btn-info" title="Bu Ürünü Düzenle"><i class="fa fa-pencil-square-o"></i></a>
			<?php endif; ?>
			<a href="<?php echo base_url('admin/'); ?>" class="btn btn-success mt-2" title="Yönetici Paneline Git"><i class="fa fa-home"></i></a>
		</div>
		<!-- / Yöneticiler İçin Yan Panel -->
	<?php endif; ?>


	<!-- Üst Navigasyon -->
	<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
		<div class="container">
			<a id="siteBaslik" class="navbar-brand text-primary " href="<?php echo base_url(); ?>"><?php echo $this->config->item('site')->ad; ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarsExampleDefault">
				<ul class="navbar-nav mr-auto">

					<?php
					// Kategori ve alt kategorileri navigation'a yazar
					for ($i=0; $i < $u->kategoriSayisi; $i++)
					{
						echo '
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="' . base_url($u->Tkateg[$i]) . '" id="dropdown' . $i . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $u->kateg[$i] . '</a>
							<div class="dropdown-menu" aria-labelledby="dropdown0' . $i . '">
						';

						// Alt Kategorileri yazar
						for ($w=0; $w < count($u->altkateg[$i]); $w++)
						{
							echo '<a class="dropdown-item" href="' . base_url($u->Tkateg[$i]) . '/' . $u->Taltkateg[$i][$w] . '">' . $u->altkateg[$i][$w] . '</a>';
						}

						echo '
							</div>
						</li>
						';
					}
					?>

				</ul>

				<form method="get" action="<?php echo base_url('sayfa/ara'); ?>" id="arama" class="form-inline my-2 my-lg-0">
					<input class="form-control mr-sm-2" type="search" name="ad" placeholder="Ara.." aria-label="Ara..">
					<button class="btn btn-primary my-2 my-sm-0" type="submit"><i class="fa fa-search"></i></button>
				</form>

			</div>
		</div>
	</nav>
	<!-- Üst Navigasyon -->

<br><br>

<div class="container mt-5 pt-4">

	<header class="mb-5">

	<?php if ( ! empty($yansiVeri)): ?>
		<!-- Yansı (Slayt) için veri gönderildiyse bunu göster -->
		<div class="row">

			<div class="col-md-8">
				<div id="anaSlide" class="carousel slide" data-interval="5000" data-ride="carousel">

					<!-- Carousel indicators -->
					<ol class="carousel-indicators">
						<li data-target="#anaSlide" data-slide-to="0"	class="active"></li>
						<?php for ($i=1; $i < count($yansiVeri); $i++) {
							echo '<li data-target="#anaSlide" data-slide-to="' . $i . '"></li>';
						} ?>
					</ol>

					<!-- Carousel items -->
					<div class="carousel-inner">

						<?php
						$i = TRUE;
						foreach ($yansiVeri as $k): ?>
							<div class="bg-dark text-center carousel-item <?php echo ($i) ? 'active' : '' ; ?>">
								<img src="<?php echo $u->urunLink($k); ?>/resim" alt="<?php echo $k->ad; ?>">
								<div class="carousel-caption">
									<a href="<?php echo $u->urunLink($k); ?>"><?php echo $k->ad; ?></a>
								</div>
							</div>
						<?php
						$i = FALSE;
						endforeach;
						?>

					</div>

					<!-- Carousel nav -->
					<a class="carousel-control-prev" href="#anaSlide" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#anaSlide" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
				<!-- / Ana Yansı (Slayt) -->

			</div>
			<div class="col-md-4">

				<div class="p-2">
					<h3 class="text-center">En Yeni</h3>
					<hr>
					<ul class="medyaListesi">

						<?php foreach ($yeniVeri as $k): ?>
							<li>
								<img src="<?php echo $u->urunLink($k); ?>/thumb" alt="<?php echo $k->ad; ?>">
								<a href="<?php echo $u->urunLink($k); ?>"><?php echo $k->ad; ?></a>
								<small><i class="fa fa-eye"></i> <?php echo $k->goruntulenme; ?></small>
							</li>
						<?php endforeach; ?>

					</ul>
				</div>

			</div>
		</div>
	<?php endif; ?>


	<?php if (isset($buUrun)): ?>
		<!-- Rich Cards BreadcrumbList-->
		<ol class="breadcrumb mt-3" vocab="http://schema.org/" typeof="BreadcrumbList">
			<li class="breadcrumb-item" property="itemListElement" typeof="ListItem">
				<a class="text-muted" property="item" typeof="WebPage" href="<?php echo base_url($u->Tkateg[$buUrun->kategori]); ?>">
					<span property="name"><?php echo $u->kateg[$buUrun->kategori]; ?></span>
				</a>
				<meta property="position" content="1">
			</li>
			<li class="breadcrumb-item" property="itemListElement" typeof="ListItem">
				<a class="text-muted" property="item" typeof="WebPage" href="<?php echo base_url($u->Tkateg[$buUrun->kategori]) . '/' . $u->Taltkateg[$buUrun->kategori][$buUrun->altkategori]; ?>">
					<span property="name"><?php echo $u->altkateg[$buUrun->kategori][$buUrun->altkategori]; ?></span>
				</a>
				<meta property="position" content="2">
			</li>
			<li class="breadcrumb-item active" property="itemListElement" typeof="ListItem">
				<a property="item" typeof="WebPage" href="<?php echo $u->urunLink($buUrun); ?>">
					<span property="name"><?php echo $buUrun->ad; ?></span>
				</a>
				<meta property="position" content="3">
			</li>
		</ol>
	<?php endif; ?>

	</header>

	<main role="main">

		<div class="row">

			<!-- Sayfa İçeriğinin Bölümü -->
			<article class="col-md-8 col-sm-12">
