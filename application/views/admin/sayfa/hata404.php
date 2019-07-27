<section class="content">
	<div class="error-page">
		<h2 class="headline text-yellow"> 404</h2>

		<div class="error-content">
			<h3><i class="fa fa-warning text-yellow"></i> Sayfa bulunamadı!</h3>

			<p>
				Aradığınız sayfa bulunamadı. Url yazarak geldiyseniz, url'nizi kontrol edin.
				Veya aradığınız sayfayı yan panelden bulabilirsiniz. Sayfa daha önce var,
				sonrada yok olduysa sayfa kaldırılmış olabilir. Bu hatanın olmaması gerektiğini
				düşünüyorsanız <a href="<?php echo base_url(); ?>admin/mesaj?kim=1">baş yöneticinizle</a>
				iletişime geçebilirsiniz.
			</p>
			<a class="btn btn-primary" href="<?php echo base_url('admin/'); ?>"><i class="fa fa-home"></i> Ana Sayfa</a>

		</div>
	</div>
</section>
