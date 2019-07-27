<?php $this->load->view('include/header'); ?>
<?php // QUESTION: Bu sayfa ne işe yarıyor? ?>
<div class="">
	<h3>Üzgünüm</h3>
	<h1><?php echo $hataNumarasi; ?></h1>
	<p><?php echo $hataBaslik; ?></p>
	<span><?php echo ($hataBildir ? 'Bu hata, yöneticiye bildirildi. Bu hatanın çözülmesi için uğraşacağız.' : ''); ?></span>
	<a href="<?php echo base_url(); ?>" class="btn btn-info">Ana Sayfaya Git</a>
</div>


<?php $this->load->view('include/footer'); ?>
