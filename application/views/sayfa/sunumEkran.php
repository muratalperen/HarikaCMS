<?php foreach ($kategoriVeri as $key => $value): ?>

<h3 class="mb-2"><?php echo $key; ?></h3><hr>
<div class="row">

	<div class="col-md-6">

		<?php if (isset($value[0])): ?>
			<div class="card mb-4 box-shadow">
	      <img class="card-img-top" src="<?php echo $u->urunLink($value[0]); ?>/resim" alt="<?php echo $value[0]->ad; ?>">
	      <div class="card-body">
	        <h4><a href="<?php echo $u->urunLink($value[0]); ?>" class="text-dark"><?php echo $value[0]->ad; ?></a></h4>
	        <p class="card-text"><?php echo $value[0]->aciklama; ?></p>
	        <div class="d-flex justify-content-between align-items-center">
	          <small class="text-muted"><i class="fa fa-eye"></i> <?php echo $value[0]->goruntulenme; ?></small>
	        </div>
	      </div>
	    </div>
		<?php endif; ?>

  </div>
	<?php
	// İlk ürün zaten gösterildiği için gösterimden kaldırılıyor
	for ($i=0; $i < count($value); $i++) {
		$value[$i] = $value[$i+1];
	}
	// Son değer kaldırılıyor
	unset($value[count($value) - 1]);
	?>
  <div class="col-md-6">
		<ul class="medyaListesi">

		<?php foreach ($value as $urun): ?>

				<li>
					<img src="<?php echo $u->urunLink($urun); ?>/thumb" alt="<?php echo $urun->ad; ?>">
					<a href="<?php echo $u->urunLink($urun); ?>">
						<?php echo $urun->ad; ?><br>
						<span class="badge badge-success"><?php echo $u->altkateg[$urun->kategori][$urun->altkategori]; ?></span>
					</a>
					<small><i class="fa fa-eye"></i> <?php echo $urun->goruntulenme; ?></small>
				</li>

		<?php endforeach; ?>

	  </ul>
  </div>
</div>

<?php endforeach; ?>
