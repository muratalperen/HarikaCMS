<?php $buSayfa = base_url() . $this->urI->uri_string . '?' . $_SERVER['QUERY_STRING']; ?>

<div class="box box-primary">

  <div class="box-header">
    <h3 class="box-title">Yorumlar</h3>
		<br><br>
		
		<form class="form-inline" action="" method="get">
			<label for="forLabelMail">Mail:</label> <input type="email" name="mail" value="<?php echo (isset($_GET['mail'])?$_GET['mail']:''); ?>" class="form-control" id="forLabelMail">
			<label for="forLabelSite">Site:</label> <input type="url" name="site" value="<?php echo (isset($_GET['site'])?$_GET['site']:''); ?>" class="form-control" id="forLabelSite">
			<label for="forLabelYorum">Yorum:</label> <input type="search" name="icerik" value="<?php echo (isset($_GET['icerik'])?$_GET['icerik']:''); ?>" class="form-control" id="forLabelYorum">
			<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Ara</button>
		</form>

		<small>Sadece bir ürüne yapılan yorumları görmek isterseniz <a href="<?php echo base_url('admin/'); ?>urun/yonet">Ürün Yönetme</a> sayfasından ürünü bulup, &quot;Yorumları Gör&quot; tuşuna tıklayın.</small>
  </div>

  <div class="box-body">
		<?php echo $tablo; ?>
		<a class="btn btn-primary" href="<?php echo base_url('adminB/'); ?>yorumlar?tamir=true&redirect=<?php echo $buSayfa; ?>"><i class="fa fa-wrench"></i> Yorum Tablosunu Tamir Et</a>
  </div>

</div>
