<div class="box box-primary">
	<form role="form" action="<?php echo base_url('adminB/') . 'yonetici/duzenle/' . $adminDuzenle->id; ?>" method="post" enctype="multipart/form-data">
		<div class="box-header with-border">
			<h3 class="box-title">Admin Yönetim Sayfası</h3>
		</div>
		<div class="box-body">
			<input type="hidden" name="id" value="<?php echo $adminDuzenle->id; ?>">
			<div class="box-body">
				<div class="form-group">
					<label for="ad">Adı</label>
					<input name="ad" value="<?php echo $adminDuzenle->ad; ?>" class="form-control" id="ad" type="text" maxlength="30" required>
				</div>
				<div class="form-group">
					<label for="mail">Mail</label>
					<input name="mail" value="<?php echo $adminDuzenle->mail; ?>" class="form-control" id="mail" type="email" required>
				</div>
				<div class="form-group">
					<label for="duzey">Düzey</label>
					<select id="duzey" name="duzey" class="form-control">
						<?php foreach (array(YONETICI_BAS, YONETICI_UST, YONETICI_MOD, YONETICI_NOR) as $k): ?>
							<?php if (seviyesi_yuksek_mi($k)): ?>
								<option value="<?php echo $k; ?>" <?php echo ($adminDuzenle->duzey == $k) ? 'selected' : '' ; ?>><?php echo yonetici_duzey_adi($k); ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="sifre">Şifre</label>
					<div class="input-group">
            <input name="sifre" class="form-control" id="sifre" type="password" autocomplete="off" required>
          	<span class="input-group-addon"><a onclick="sifreGoster();"><i class="fa fa-eye" id="sifreGosterIcon"></i></a></span>
						<script type="text/javascript">
							function sifreGoster() {
								var sifreText = document.getElementById('sifre');
								document.getElementById('sifre').type = ((sifreText.type == 'text') ? 'password' : 'text');
								document.getElementById('sifreGosterIcon').className = 'fa fa-eye' + ((sifreText.type == 'text') ? '-slash' : '');
							}
						</script>
          </div>
				</div>
				<div class="form-group">
					<label for="hakkinda">Hakkında</label>
					<textarea name="hakkinda" class="form-control" id="hakkinda"><?php echo $adminDuzenle->hakkinda; ?></textarea>
				</div>
				<div class="form-group">
					<label for="resim"><i class="fa fa-file-image-o"></i> Yöneticinin Resmi</label>
					<input name="resim" id="resim" type="file" class="btn btn-info" <?php echo isset($adminDuzenle) ? '' : 'required' ; ?>>
					<p class="help-block"></p>
				</div>
			</div>
		</div>

		<div class="box-footer">
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-<?php echo (isset($adminDuzenle) ? 'upload' : 'plus'); ?>"></i> <?php echo (isset($adminDuzenle) ? 'Güncelle' : 'Ekle'); ?>
			</button>
			<?php if (isset($adminDuzenle)): ?>
				<button type="button" onclick="silEminMisin();" class="btn btn-danger"><i class="fa fa-trash"></i> Yöneticiyi Kaldır</a>

				<script type="text/javascript">
				function silEminMisin() {
					bildirim("Emin Misiniz?",
					'Bu yöneticiyi geri getirilemeyecek şekilde silmek istediğinizden emin misiniz?<br>\
					<form class="form-inline" method="GET" action="<?php echo base_url('adminB/'); ?>yonetici/duzenle/<?php echo $adminDuzenle->id; ?>">\
					Silinen yöneticinin ürünlerini\
					<input type="hidden" name="sil" value="true">\
					<select name="urunler" class="form-control bg-yellow">\
					<option value="0">Sil</option>\
					<?php foreach ($tumAdminler as $k): ?>\
						<?php if($k->id != $admin->id): ?>\
							<option value="<?php echo $k->id; ?>"><?php echo $k->ad; ?> adlı yöneticiye devret</option>\
						<?php endif; ?>\
					<?php endforeach; ?>\
					</select>\
					<button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Sil!</a>\
					</form>',
					"w");
					return false;
				}
				</script>
			<?php endif; ?>
			</div>
		</form>
	</div>
