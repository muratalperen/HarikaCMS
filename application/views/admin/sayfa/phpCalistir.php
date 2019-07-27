<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Php Kodu Çalıştırma</h3>
	</div>

	<div class="box-body">
		<form role="form" action="<?php echo base_url('admin/'); ?>php" method="post">
			<label for="icerik">Çalıştırmak istediğiniz php kodunu yazın:</label><br>
			<input type="checkbox" name="database" onchange="dbKodlar(this.checked)" id="databasem" <?php echo (($_POST['database'] == 'on')?'checked':''); ?>> <label for="databasem">Veritabanına bağlan</label>
			<textarea name="calistir" class="form-control" id="icerik" style="font-family:monospace;" rows="8" placeholder="Ör/ echo 'deneme';" required><?php echo (isset($_POST['calistir'])?$_POST['calistir']:''); ?></textarea><br>
			<button type="submit" class="btn btn-primary"><i class="fa fa-terminal"></i> Çalıştır</button>
		</form><br>
		<?php if (file_exists('.deleteThis.php')): ?>
			<iframe width="100%" height="200px" style="border:1px solid gray;" src="<?php echo base_url(); ?>.deleteThis.php"></iframe>
		<?php else: ?>
			<div width="100%" style="height:200px; padding:5px; border:1px solid gray;">Yukarı kodunuzu yazıp Çalıştır tuşuna basın</div>
		<?php endif; ?>
	</div>

</div>

<script type="text/javascript">
	var kodAlan = document.getElementById('icerik');
	function dbKodlar(seciliMi) {
		if (seciliMi) {
			kodAlan.innerHTML += "\n$qNesne = $dbBaglanti->prepare('SELECT * FROM tabloAd WHERE sutunAd = ?');\n$qNesne->execute(array('deger'));\n$sonuc = $qNesne->fetchAll(/*PDO::FETCH_ASSOC*/);\necho '<pre>';\nprint_r($sonuc);\necho '</pre>';\n";
		}
	}
</script>
