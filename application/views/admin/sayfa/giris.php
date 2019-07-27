<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Giriş Yapın</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<script type="text/javascript">
	function linkError(dosyaAdi, elem){
		console.debug('CDN\'ye ulaşmada sorun çıktığı için yereldeki içerik (' + dosyaAdi + '.min.css) kullanıldı.');
		if (elem.href != "<?php echo base_url('rel/'); ?>" + dosyaAdi + ".min.css") {
			elem.href =  "<?php echo base_url('rel/'); ?>" + dosyaAdi + ".min.css";
		}
	}
	</script>
  <link rel="stylesheet" href="<?php echo base_url('rel/'); ?>admin/bower_components/bootstrap/bootstrap.min.css" onerror="linkError('css/bootstrap', this);"><!-- orjinalde 3.3.7, ana sayfadaki 3.3.1 -->
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" onerror="linkError('css/bootstrap', this);"><!-- orjinalde 3.3.7, ana sayfadaki 3.3.1 -->
  <link rel="stylesheet" href="<?php echo base_url('rel/'); ?>css/font-awesome.min.css" onerror="linkError('css/font-awesome', this);">
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" onerror="linkError('css/font-awesome', this);"> -->
  <!-- <link rel="stylesheet" href="<?php echo base_url('rel/admin/'); ?>bower_components/Ionicons/css/ionicons.min.css"> -->
  <link rel="stylesheet" href="<?php echo base_url('rel/admin/'); ?>dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo base_url('admin'); ?>"><b>Admin</b> Panel</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Giriş Yapın</p>
    <span class="text-red"><?php echo ($hata == null)?'':$hata; ?></span>
    <form action="" method="post">
      <?php echo (empty($_GET['redirect']))?'':'<input type="hidden" name="redirect" value="'.$_GET['redirect'].'" >'; ?>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" name="mail" placeholder="Email" autofocus>
        <span class="fa fa-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="sifre" placeholder="Şifre">
        <span class="fa fa-lock form-control-feedback"></span>
      </div>
			<?php echo $cap['image']; ?>
			<div class="form-group has-feedback" style="margin-top:10px;">
		  	<input type="text" class="form-control" name="captcha" placeholder="Güvenlik Kodu" autocomplete="off">
		  	<span class="fa fa-barcode form-control-feedback"></span>
		  </div>
      <div class="row">
        <div class="col-xs-8">
          <!--<div class="checkbox icheck">
            <label>
              <input type="checkbox"> Beni Hatırla
            </label>
          </div>-->
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Giriş</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <a href="<?php echo base_url('sayfa/'); ?>iletisim">Şifremi Unuttum</a><br>
    <a href="<?php echo base_url('sayfa/'); ?>iletisim" class="text-center">Yöneticilik İste</a>

  </div>
</div>

<script src="<?php echo base_url('rel/admin/'); ?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url('rel/'); ?>js/bootstrap.min.js"></script>
</body>
</html>
