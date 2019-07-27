<div class="box box-primary">

  <div class="box-header">
    <h3 class="box-title">Adminleri Yönetin</h3>
  </div>

  <div class="box-body">
    <?php	echo $tablo; ?>

    <?php if (seviyesi_yuksek_mi(YONETICI_UST)): ?>
      <a href="<?php echo base_url(); ?>admin/yonetici/ekle" class="btn btn-primary"><i class="fa fa-plus"></i> Yeni yönetici ekle</a>
    <?php endif; ?>
  </div>
</div>
