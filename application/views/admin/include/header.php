<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $meta['baslik']; ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>favicon.ico" type="image/x-icon">

	<script type="text/javascript">
	function linkError(dosyaAdi, uzanti, elem){
		console.debug('CDN\'ye ulaşmada sorun çıktığı için yereldeki içerik (' + dosyaAdi + '.min.' + uzanti + ') kullanıldı.');
		if(uzanti == "css"){
			if (elem.href != "<?php echo base_url('rel/'); ?>" + dosyaAdi + ".min.css") {
				elem.href =  "<?php echo base_url('rel/'); ?>" + dosyaAdi + ".min.css";
			}
		} else if(uzanti == "js") {
			if (elem.src != "<?php echo base_url('rel/js/'); ?>" + dosyaAdi + ".js") {
				elem.src = "<?php echo base_url('rel/js/'); ?>" + dosyaAdi + ".js";
			}
		}
	}
	</script>
  <link rel="stylesheet" href="<?php echo base_url('rel/'); ?>admin/bower_components/bootstrap/bootstrap.min.css"><!-- orjinalde 3.3.7, ana sayfadaki 3.3.1 -->
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" onerror="linkError('css/bootstrap', 'css', this);"><!-- orjinalde 3.3.7, ana sayfadaki 3.3.1-->
  <link rel="stylesheet" href="<?php echo base_url('rel/'); ?>css/font-awesome.min.css">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" onerror="linkError('css/font-awesome', 'css', this);"> -->
	<!-- jQuery 3 -->
	<script src="<?php echo base_url('rel/admin/'); ?>bower_components/jquery/dist/jquery.min.js"></script>

  <link rel="stylesheet" href="<?php echo base_url('rel/admin/'); ?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
  page. However, you can choose any other skin. Make sure you
  apply the skin class to the body tag so the changes take effect. -->
  <link rel="stylesheet" href="<?php echo base_url('rel/admin/'); ?>dist/css/skins/skin-blue.min.css">
  <?php
  if (isset ($ekleCSS)){
    foreach ($ekleCSS as $stilYolu) {
      echo '<link rel="stylesheet" href="' . base_url('rel/admin/') . $stilYolu . '.css" >';
    }
  }
  ?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="<?php echo base_url ('admin'); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b><?php echo $this->config->item('site')->ad[0]; ?></b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?php echo $this->config->item('site')->ad; ?></b></span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less-->
            <li class="dropdown messages-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-envelope-o"></i>
                <?php echo (($headVar['mesaj']['sayisi'] == 0)?'':'<span class="label label-success">' . $headVar['mesaj']['sayisi'] . '</span>'); ?>
              </a>
              <ul class="dropdown-menu">
                <li class="header"><?php echo (($headVar['mesaj']['sayisi'] == 0)?'Yeni mesajınız yok':$headVar['mesaj']['sayisi'] . ' okunmamış mesajınız var'); ?></li>
                <li>
                  <!-- inner menu: contains the messages -->
									<ul class="menu">
									<?php
									if (!empty($headVar['mesaj']['sonMesajlar'])) {
										foreach ($headVar['mesaj']['sonMesajlar'] as $sonMesaj) {
											echo '<li>
	                      <a href="'. base_url('admin/') . 'mesaj?kim=' . $sonMesaj->gonderenID .'">
	                        <div class="pull-left">
	                          <img src="'. base_url('rel/') . 'img/admin/' . $sonMesaj->gonderenID .'.jpg" class="img-circle" alt="Mesajı gönderenin resmi">
	                        </div>
	                        <h4>
	                          '. $sonMesaj->gonderenAdi .'
	                          <small><i class="fa fa-clock-o"></i> '. gecenZaman($sonMesaj->tarih) .'</small>
	                        </h4>
	                        <!-- The message -->
	                        <p>'. $sonMesaj->icerik .'</p><!-- Word limiter -->
	                      </a>
	                    </li>';
										}
									}
										?>
										</ul>
                  <!-- /.menu -->
                </li>
                <li class="footer"><a href="<?php echo base_url('admin/'); ?>mesaj">Tüm mesajları gör</a></li>
              </ul>
            </li>
            <!-- /.messages-menu -->

            <!-- Notifications Menu -->
            <li class="dropdown notifications-menu">
              <!-- Menu toggle button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <?php echo (($headVar['bildirim'][0] == 0)?'':'<span class="label label-warning">' . $headVar['bildirim'][0] . '</span>'); ?>
              </a>
              <ul class="dropdown-menu">
                <li class="header">Toplam <?php echo $headVar['bildirim'][0]; ?> bildirim var.</li>
                <li>
                  <!-- Inner Menu: contains the notifications -->
                  <ul class="menu">
                    <?php
                    if ($headVar['bildirim'][3] != 0) {
                      echo '<li>
                        <a href="'. base_url('admin/') .'bildirimler?onem=3">
                          <i class="fa fa-info text-danger"></i>'. $headVar['bildirim'][3] .' çok önemli bildirim var!
                        </a>
                      </li>';
                    }
                    ?>
                    <li>
                      <a href="<?php echo base_url('admin/'); ?>bildirimler?onem=2">
                        <i class="fa fa-warning text-warning"></i> <?php echo $headVar['bildirim'][2]; ?> önemli bildirim var
                      </a>
                    </li>
                    <li>
                      <a href="<?php echo base_url('admin/'); ?>bildirimler?onem=1">
                        <i class="fa fa-stop text-warning"></i> <?php echo $headVar['bildirim'][1]; ?> orta seviyede bildirim.
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="footer"><a href="<?php echo base_url('admin/'); ?>bildirimler">Hepsini Gör</a></li>
              </ul>
            </li>
            <!-- Tasks Menu
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-flag-o"></i>
                <span class="label label-danger">9</span>
              </a>
              <ul class="dropdown-menu">
                <li class="header">You have 9 tasks</li>
                <li>
                  <ul class="menu">
                    <li>
                      <a href="#">
                        <h3>
                          Design some buttons
                          <small class="pull-right">20%</small>
                        </h3>
                        <div class="progress xs">
                          //Change the css width attribute to simulate progress
                          <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                          aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>-->
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="<?php echo base_url('rel/'); ?>img/admin/<?php echo $admin->id; ?>.jpg" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $admin->ad; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="<?php echo base_url('rel/'); ?>img/admin/<?php echo $admin->id; ?>.jpg" class="img-circle" alt="User Image">

                <p>
                  <?php echo $admin->ad; ?>
                  <small><?php echo yonetici_duzey_adi($admin->duzey); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-6 text-center">
                    <a href="<?php echo base_url('admin/') . 'profil/' . $admin->id; ?>">Profil</a>
                  </div>
                  <div class="col-xs-6 text-center">
                    <a href="<?php echo base_url('admin/'); ?>yapilacaklar">Yapılacaklar</a>
                  </div>
                  <!-- <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div> -->
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('admin/') . 'yonetici/duzenle/' . $admin->id; ?>" class="btn btn-default btn-flat">Ayarlar</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('adminB/') . 'cikis'; ?>" class="btn btn-default btn-flat">Çıkış Yap</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url('rel/') . 'img/admin/' . $admin->id; ?>.jpg" class="img-circle" alt="<?php echo $admin->ad; ?>">
        </div>
        <div class="pull-left info">
          <p><?php echo $admin->ad; ?></p>
          <!-- Status -->
          <a><i class="fa fa-circle text-success"></i> <?php echo yonetici_duzey_adi($admin->duzey); ?></a>
        </div>
      </div>

      <!-- search form (Optional)
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Ara...">
          <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </form>
      /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">

				<!-- Üst Yönetici -->
        <?php if (seviyesi_yuksek_mi(YONETICI_UST)): ?>
          <li class="treeview">
            <a href="#"><i class="fa fa-user-secret"></i> <span>Üst Yöneticilik</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
							<?php if (seviyesi_yuksek_mi(YONETICI_BAS)): ?>
              	<li><a href="<?php echo base_url('admin/'); ?>php"><i class="fa fa-terminal"></i> Php Çalıştır</a></li>
							<?php endif; ?>
              <li><a href="<?php echo base_url('admin/') ?>yonetici/yonet"><i class="fa fa-user"></i> Yöneticileri Düzenle</a></li>
              <li><a href="<?php echo base_url('admin/') ?>ayarlar"><i class="fa fa-cog"></i> Site Ayarları</a></li>
            </ul>
          </li>
        <?php endif; ?>

				<!-- Ürün -->
        <?php if (seviyesi_yuksek_mi(YONETICI_NOR)): ?>
          <li class="treeview">
            <a href="#"><i class="fa fa-book"></i> <span>Ürün</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url('admin/') ?>urun/ekle"><i class="fa fa-plus"></i> <span>Ekle</span></a></li>
							<li><a href="<?php echo base_url('admin/') ?>urun/yonet"><i class="fa fa-edit"></i> <span>Düzenle</span></a></li>
						</ul>
					</li>
				<?php endif; ?>

				<!-- Yorumlar -->
				<?php if (seviyesi_yuksek_mi(YONETICI_MOD)): ?>
					<li class="treeview">
						<a href="#"><i class="fa fa-comment"></i> <span>Yorumlar</span>
							<span class="pull-right-container">
								<?php echo (($headVar['yorum'] == 0)?'':'<small class="label pull-left bg-green">' . $headVar['yorum'] . '</small>'); ?>
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?php echo base_url('admin/'); ?>yorumlar/yeni"><i class="fa fa-commenting"></i> Yeni Yorumlar
								<span class="pull-right-container">
									<?php echo (($headVar['yorum'] == 0)?'':'<small class="label pull-right bg-red">' . $headVar['yorum'] . '</small>'); ?>
								</span>
							</a></li>
							<li><a href="<?php echo base_url('admin/'); ?>yorumlar"><i class="fa fa-comment-o"></i> Tüm Yorumlar</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<!-- Seo -->
				<?php if (seviyesi_yuksek_mi(YONETICI_UST)): ?>
					<li class="treeview">
						<a href="#"><i class="fa fa-search"></i> <span>SEO</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?php echo base_url('admin/'); ?>seo/robots"><i class="fa fa-file-text"></i> Robots.txt</a></li>
							<li><a href="<?php echo base_url('admin/'); ?>seo/sitemap"><i class="fa fa-sitemap"></i> Site Haritası</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<!-- Ek Yükle -->
				<?php if (seviyesi_yuksek_mi(YONETICI_NOR)): ?>
					<li class="treeview">
						<a href="#"><i class="fa fa-upload"></i> <span>Yükle</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="<?php echo base_url('admin/'); ?>yukle/resim"><i class="fa fa-file-image-o"></i> Resim Yükle</a></li>
							<li><a href="<?php echo base_url('admin/'); ?>yukle/dosya"><i class="fa fa-file"></i> Dosya Yükle</a></li>
						</ul>
					</li>
				<?php endif; ?>

				<!-- Mail Aboneleri -->
				<?php	if (seviyesi_yuksek_mi(YONETICI_MOD)): ?>
					<li><a href="<?php echo base_url('admin/'); ?>abone"><i class="fa fa-envelope-square"></i> <span>Mail Aboneleri</span></a></li>
				<?php endif; ?>

				<!-- Reklam -->
				<?php	if (seviyesi_yuksek_mi(YONETICI_UST)): ?>
					<li><a href="<?php echo base_url('admin/'); ?>reklam"><i class="fa fa-usd"></i> <span>Reklam</span></a></li>
				<?php endif; ?>

					<li class="header">İstatistikler</li>
					<?php if ($this->config->item('istatistik_tut')): ?>
            <li><a href="<?php echo base_url('admin/'); ?>istatistik"><i class="fa fa-dashboard"></i> <span>Site İstatistikleri</span></a></li>
          <?php endif; ?>
					<li><a href="<?php echo base_url('admin/'); ?>profil"><i class="fa fa-pie-chart"></i> <span>Yönetici İstatistikleri</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Sayfa -->
    <div class="content-wrapper">

      <!-- Sayfa Başlığı -->
      <section class="content-header">
        <h1>
          <?php echo $meta['baslik'];
          if (isset($meta['aciklama'])) {
            echo '<small>'. $meta['aciklama'] .'</small>';
          }
          ?>
        </h1>
        <ol class="breadcrumb">
          <li><a href="<?php echo base_url('admin/yardim?konu=' . $this->router->fetch_method()); ?>" target="_blank"><i class="fa fa-info" aria-hidden></i> Yardım</a></li>
        </ol>
      </section>

      <!-- Asıl Sayfa İçeriği -->
      <section class="content container-fluid">

      <?php
			// Bildirim varsa göster
      if ( ! empty($flashBildirim = $this->session->flashdata ('bildirim')))
			{

				if (gettype($flashBildirim) == 'array') // Güncel halinde bildirim için array vermesi lazım
				{
					echo uyar($flashBildirim[0], $flashBildirim[1]);

				}
				else // Eskiden bildirimler string'di
				{
	        echo uyar($flashBildirim, $olaySonucu);

				}
      }
			unset($flashBildirim); // İleride sorun çıkarmasın
      ?>
