<?php
header('Content-Type: text/xml; charset=UTF-8');

echo
'<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="'.  base_url() . 'rel/diger/sitemap.xsl"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
';

if (isset($sayfalar)) { // Site Haritası

	foreach ($sayfalar as $sayfa) {
		echo '
		<url>
  		<loc>' . $sayfa['adres'] . '</loc>
  		<lastmod>' . $sayfa['tarih'] . 'T09:00:04+00:00</lastmod>
  		<priority>' . $sayfa['onem'] . '</priority>
  		<changefreq>' . $sayfa['degisme'] . '</changefreq>
		</url>
		';
	}

	echo $ekSayfa;
	
} elseif (count($urunler) != 0) { // Ürün Haritası

	foreach ($urunler as $sayfa) {
		echo '
		<url>
  		<loc>' . base_url() . $u->Tkateg[$sayfa->kategori] . '/' . $u->Taltkateg[$sayfa->kategori][$sayfa->altkategori] . '/' . $sayfa->sef . '</loc>
  		<lastmod>' . $sayfa->tarih . 'T09:00:04+00:00</lastmod>
  		<priority>' . $urunOnem . '</priority>
  		<changefreq>' . $degisme . '</changefreq>
		</url>
		';
	}

}


echo '</urlset>';
?>
