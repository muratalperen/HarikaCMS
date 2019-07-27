



CREATE TABLE `abone` (
  `ad` varchar(30) COLLATE utf8_turkish_ci NOT NULL,
  `mail` varchar(35) COLLATE utf8_turkish_ci NOT NULL,
  `tarih` date NOT NULL COMMENT 'Abone olduğu tarih'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='E-posta adresleriyle haber bültenine kayıt olanlar';






CREATE TABLE `admin` (
  `id` tinyint(4) unsigned NOT NULL,
  `ad` varchar(30) COLLATE utf8_turkish_ci NOT NULL,
  `duzey` tinyint(4) unsigned NOT NULL,
  `mail` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `sifre` varchar(32) COLLATE utf8_turkish_ci NOT NULL,
  `sonCevrimici` date NOT NULL COMMENT 'Yöneticinin son panele giriş zamanı',
  `hakkinda` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Yöneticiler';


INSERT INTO admin (id, ad, duzey, mail, sifre, sonCevrimici, hakkinda) VALUES (1,"Root",100,"root@host","7b24afc8bc80e548d66c4e7ff72171c5","2019-01-01","Sitenin baş yöneticisi");

CREATE TABLE `admin_bildirimler` (
  `id` smallint(5) unsigned NOT NULL,
  `onem` tinyint(3) unsigned NOT NULL,
  `ip` varchar(15) COLLATE utf8_turkish_ci NOT NULL,
  `tarih` date NOT NULL,
  `baslik` varchar(30) COLLATE utf8_turkish_ci NOT NULL,
  `uyari` text COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Yöneticilerin göreceği bildirimler';






CREATE TABLE `admin_mesajlar` (
  `id` smallint(5) unsigned NOT NULL COMMENT 'Mesajın ID''si',
  `gonderenID` tinyint(3) unsigned NOT NULL COMMENT 'Gönderen yöneticinin ID''si',
  `alanID` tinyint(3) unsigned NOT NULL COMMENT 'Alan yöneticinin ID''si',
  `tarih` datetime NOT NULL COMMENT 'Gönderilen tarih',
  `silmeDurum` tinyint(2) NOT NULL COMMENT '0:normal, 1:gönderen sildi, 2:alan sildi',
  `okunmaDurum` tinyint(1) NOT NULL COMMENT '0:okunmadı, 1:okundu',
  `icerik` varchar(255) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Mesaj. En fazla 255 karakter',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Yöneticilerin birbirleriyle mesajlaşma alanı';






CREATE TABLE `admin_yapilacaklar` (
  `id` smallint(3) unsigned NOT NULL,
  `icerik` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `admininID` tinyint(3) unsigned NOT NULL,
  `tarih` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Yöneticilerin yapılacaklar listeleri';






CREATE TABLE `istatistik` (
  `baktigi` int(11) NOT NULL,
  `ref` varchar(150) COLLATE utf8mb4_turkish_ci NOT NULL,
  `tarih` date NOT NULL,
  `tarayici` varchar(30) COLLATE utf8mb4_turkish_ci NOT NULL,
  `os` varchar(15) COLLATE utf8mb4_turkish_ci NOT NULL,
  `mobilmi` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci COMMENT='Site istatistikleri';






CREATE TABLE `urun` (
  `id` int(10) unsigned NOT NULL,
  `ad` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `kategori` tinyint(3) unsigned NOT NULL,
  `altkategori` tinyint(3) unsigned NOT NULL,
  `sef` varchar(80) COLLATE utf8_turkish_ci NOT NULL,
  `goruntulenme` mediumint(8) unsigned NOT NULL,
  `yukleyen` tinyint(3) unsigned NOT NULL,
  `tarih` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Ürünlerin bulunduğu tablo';






CREATE TABLE `urun_join` (
  `id` int(10) unsigned NOT NULL COMMENT 'urun''deki id karşılığı. Foreign key',
  `aciklama` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `taglar` varchar(200) COLLATE utf8_turkish_ci NOT NULL,
  `begen` smallint(5) unsigned NOT NULL,
  `begenme` smallint(5) unsigned NOT NULL,
  `kaynak` text COLLATE utf8_turkish_ci NOT NULL,
  `icerik` text COLLATE utf8_turkish_ci NOT NULL COMMENT 'Markdown blog içeriği'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Ürünün her içerikte olmayacak, ayrıcalıklı bilgileri burada tutulur';






CREATE TABLE `yorum` (
  `id` int(10) unsigned NOT NULL COMMENT 'Yorumun özel ID''si',
  `yanitID` int(11) unsigned NOT NULL COMMENT 'Başka bir yoruma cevapsa, o yorumun id''sini alır',
  `urunID` int(10) unsigned NOT NULL COMMENT 'Yorumun hangi ürüne yapıldığı',
  `ad` varchar(30) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Yorum sahibinin adı',
  `mail` varchar(35) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Yorum sahibinin mail adresi',
  `site` varchar(25) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Yorum sahibinin internet sitesi',
  `tarih` date NOT NULL COMMENT 'Yorumun yapıldığı tarih',
  `incelendi` tinyint(1) NOT NULL COMMENT 'Bir admin inceledi mi',
  `icerik` varchar(255) COLLATE utf8_turkish_ci NOT NULL COMMENT 'Yorum',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci COMMENT='Ürünlerin yorumları';




