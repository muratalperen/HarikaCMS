<div class="box box-success">
	<div class="box-header">
		<h3 class="box-title" id="yyardim">Kullanım Kılavuzu</h3>
	</div>
	<div class="box-body">
		<style media="screen">
			dt{
				font-size: 2em;
			}
		</style>
		<img src="https://github.com/muratalperen/./docs/ss1.png" class="img-fluid" alt="Harika CMS">
    <dl>
    	<dt id="yindex">Temel Bilgiler</dt>
			<dd>
				<p>
					Harika CMS, blog sitesi ve aynı zamanda CMS (Ürün yönetim sistemi)'dir. Blog olarak hazır kullanabilecek olmanızla
					beraber, az uğraşlarla haber sitenize veya ürün satış sitenize çevirebilirsiniz. Blog olarak kullanım için hiçbir
					ek yazılım bilgisi gerektirmez.
				</p>
			</dd>

    	<dt id="yyonetici">Yöneticiler</dt>
			<dd>
				<p>Harika CMS, çoklu yönetici ve yönetici düzeylerini destekler. Sizden daha düşük yöneticiler ekleyerek site güvenliğini
				sağlayabilirsiniz. Yöneticileri görmek ve yönetimek için &quot;Üst yöneticilik &gt; <a href="<?php echo base_url(); ?>admin/yonetici/yonet">
				Yöneticileri Düzenle</a>&quot; bölümüne gidip; yönetici ekleyebilir, silebilir veya düzenleyebilirsiniz.</p>
				<p>Dört düzeyde yönetici vardır:</p>
				<h4><?php echo yonetici_duzey_adi(YONETICI_BAS); ?></h4>
				<p>
					Gerekmedikçe bu düzeyi kullanmayın. Sitenizi sıfırlamaya kadar herşeyi yapabilir. Ürün ekleme gibi basit işlemlerinizi
					en düşük düzey olan &quot;<?php echo yonetici_duzey_adi(YONETICI_NOR); ?>&quot; düzeyinde yönetici oluşturarak yapmanız önerilir.
				</p>
				<h4><?php echo yonetici_duzey_adi(YONETICI_UST); ?></h4>
				<p>
					Sitenize kendiniz dışında yüksek düzeyde yönetici almak isterseniz bu düzeyi kullanabilirsiniz. Bu yöneticinin tek
					eksiği, &quot;<?php echo yonetici_duzey_adi(YONETICI_BAS); ?>&quot; düzeyindeki yöneticiyi etkileyemez ve siteyi sıfırlayamaz.
				</p>

				<h4><?php echo yonetici_duzey_adi(YONETICI_MOD); ?></h4>
				<p>
					Bu yöneticiler site işlerini yapar, yönetime pek karışmazlar. Yönetici düzenleyemez, site ayarlarına dokunamaz,
					SEO ayarlarına dokunamaz ve başkalarına yapılacak görev veremezler.
				</p>

				<h4><?php echo yonetici_duzey_adi(YONETICI_NOR); ?></h4>
				<p>
					En düşük yönetici düzeyidir. Sadece resim, dosya gönderebilir; ürün yükleyebilirler. Ürünleri değiştirmeye bile yetkileri
					yoktur.
				</p>
			</dd>

    	<dt id="yurun">Ürün Yönetimi</dt>
			<dd>
				<p>
					Harika CMS blog sitesidir ve yazılım bilgisi gerektirmeden kurup kullanmaya başlayabilirsiniz. Ayrıca çok değişime
					çok müsait yapısı vardır. Php ve az codeigniter bilgisi ile diğer ürün yönetim sistemlerine çevirebilirsiniz.
				</p>
				<p>
					Ürünler kategori ve alt kategoriler olarak düzenlenir. Ürünün ana resmi olur ve ek olarak &quot;Yükle&quot; bölümünden resim veya
					dosya yükleyebilirsiniz. Blog olarak kullanacaksanız içeriğinizi &quot;Markdown&quot; dili ile yazabilirsiniz.
				</p>
			</dd>

    	<dt id="yyorumlar">Yorumlar</dt>
			<dd>
				<p>
					Ürünlerinize yorum yapılabilir. Yapılmış ama yöneticiler tarafından görülmemiş yorumları &quot;Yorumlar &gt;
					<a href="<?php echo base_url(); ?>admin/yorumlar/yeni">Yeni Yorumlar</a>&quot; bölümünden görebilirsiniz.
					Belirli bir ürüne yapılan yorumları görmek için &quot;<a href="<?php echo base_url(); ?>admin/urun/yonet">Ürün Düzenle</a>
					&quot; bölümünden ürününüzü bulup, bulunduğu satırdaki yorumları yöneti tuşuna basabilirsiniz.
				</p>
			</dd>

    	<dt id="yseo">SEO</dt>
			<dd>
				<p>Seo (Search engine optimization), iki bölümden oluşur.</p>
				<h4>Robots.txt</h4>
				<p>
					Sitenin hangi bölümlerinin arama motorları tarafından taranacağının belirlendiği robots.txt dosyasını bu bölümden
					değiştirebilirsiniz. Ayrıca kullanmayı bilmiyorsanız bile sağ alttaki tuşlarla hazır robots.txt dosyanızı
					oluşturabilirsiniz.
				</p>
				<blockquote>Siteyi ilk kurduğunuzda robots.txt taranmaya kapalıdır. Sitenizin arama motorlarında görünebilmesi
				için &quot;Önerilen&quot; tuşuna basıp, &quot;Güncelle&quot;ye basın.</blockquote>
				<h4>Site Haritası</h4>
				<p>
					Harika CMS'nin iki adet site haritası bulunur. Bu haritalara
					<a href="<?php echo base_url(); ?>sitemap.xml" target="_blank"><?php echo base_url(); ?>sitemap.xml</a> ve
					<a href="<?php echo base_url(); ?>sitemap.xml" target="_blank"><?php echo base_url(); ?>urunmap.xml</a> adreslerinden
					ulaşabilirsiniz. Sitemap; statik sayfaları, kategori listelenme sayfalarını ve ana sayfayı içerir. &quot;Site
					Haritasını Düzenleyin&quot; bölümünden sayfaların önemlerini değiştirebilirsiniz. Nasıl kullanıldığını bilmiyorsanız
					öyle bırakın. Ürünmap ise bütün ürünleri içerir. Ürünlerin site sayfaları arasında ne kadar önemli olduğunu oradan
					ayarlayabilirsiniz. Tavsiye edilen değer &quot;1&quot;dir.
				</p>
			</dd>

    	<dt id="yyukle">Yükle</dt>
			<dd>
				<p>
					Ürününüz/Blog yazınız için ek dosya ve resimleri buradan yükleyebilirsiniz. Dosya adı için &quot;dosya-adi&quot;
					gibi önerilen türde dosya ismi vermeniz önerilir. Resimler &quot;dosya/icerik/resim/&quot;, dosyalar da
					&quot;dosya/icerik/dosya/&quot; dizininde bulunur. Blog yazınızda resim dizininizi yazmak için &quot;{rD}&quot;
					(resim dizini), dosya dizininizi yazmak için ise &quot;{dD}&quot; (dosya dizini) yazabilirsiniz. Daha ayrıntılı
					bilgi için <a href="#yurun">ürün yönetimi yardım sayfasına</a> bakabilirsiniz.
				</p>
				<p>Ayrıca &quot;tüm dosyaları ara&quot; bölümünden daha önceden yüklediklerinizi görebilir ve silebilirsiniz.</p>
			</dd>

    	<dt id="yabone">Mail Aboneleri</dt>
			<dd>
				<p>
					Mail aboneleri bölümünden, mail adresleri ile abone olmuş kişileri görebilir, istediğiniz aboneleri silebilirsiniz.
					Sistemde toplu mail gönderme ayarı bulunmamaktadır. Bu işler için mailchimp gibi servisleri kullanabilirsiniz.
				</p>
			</dd>

    	<dt id="yreklam">Reklamlar (Beta)</dt>
			<dd>
				<p>
					Henüz yeni açılmış olan reklamlar kısmından, sitenizde görünecek reklamların resimleri ve tıklandığında gönderilecek
					bağlantıları ayarlayabilirsiniz.
				</p>
			</dd>

    	<dt id="yistatistik">İstatistikler</dt>
			<dd>
				<p>
					Sitenizin istatistikleri burada bulunur. Tüm yöneticiler ulaşabilir. Sitenizi yeni açtıysanız bu grafikler
					görünmeyebilir.
				</p>
				<h4>Site İstatistikleri</h4>
				<p>
					Bu bölümde son 30 gün içindek tıklanma sayılarına, kullanıcıların nereden geldiğine, kullanıcıların işletim sistemi
					ve tarayıcı kullanımlarına, en çok tıklanan ürünlere ve kullanıcıların girdiği saatlere buradan ulaşabilirsiniz. Ayrıca
					bu verileri csv türünde indirebilirsiniz.
				</p>

				<h4>Yönetici İstatistikleri</h4>
				<p>
					Burada yöneticilerle ilgili bilgiler bulunur. Yöneticilerin haftalık paylaşım sayıları, toplam ürünlerin ne kadarlık
					bölümünü kimin ürettiği ve yöneticilerin paylaştığı ürünlere tıklanma oranları burada bulunur.
				</p>
			</dd>

    	<dt id="yhatalar">Sistem Hataları</dt>
			<dd>
				<p>Sistem hatalarını github üzerinden issue ile gönderebilirsiniz. Harika CMS sürekli gelişmekte olan bir sistemdir.</p>
			</dd>

    	<dt id="ykatki">Katkıda Bulunma</dt>
			<dd>
				<p>Yazılımsal destek &quot;Github pull request&quot; ile yapabilirsiniz.</p>
			</dd>
    </dl>
  </div>
</div>
<?php if (isset($_GET['konu'])): ?>
	<script type="text/javascript">
		window.onload = setTimeout(gid, 400);

		function gid() {
			window.location.href = "#y<?php echo htmlspecialchars($_GET['konu']); ?>";
		}
	</script>
<?php endif; ?>
