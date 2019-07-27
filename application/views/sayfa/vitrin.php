<ul class="vitrinListesi">

<?php foreach ($liste as $urun): ?>
		<li>
			<img src="<?php echo $u->urunLink($urun); ?>/thumb" alt="<?php echo $urun->ad; ?>">
			<div>
				<a href="<?php echo $u->urunLink($urun); ?>"><?php echo $urun->ad; ?></a>
				<p><?php echo $urun->aciklama; ?></p>
				<small><i class="fa fa-eye"></i> <?php echo $urun->goruntulenme; ?></small>
			</div>
		</li>

<?php endforeach; ?>

</ul>
