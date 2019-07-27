<div style="display:none;" id="mesajAlanAl">

	<div class="box box-warning direct-chat direct-chat-warning">

		<div class="box-header with-border">
			<h3 class="box-title">__ad__ İle Mesajlaş</h3>
			<div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
				<button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Tüm mesajları temizle" onclick="mesaj.temizle(__id__);">
					<i class="fa fa-trash"></i></button>
				<button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Mail adresini gör" onclick="bildirim('Mail Adresi', 'Yöneticinin mail adresi: <a href=\'mailto:__mail__\'>__mail__</a>');">
					<i class="fa fa-envelope"></i></button>
				<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div>

		<div class="box-body">
			<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
			<div class="direct-chat-messages" id="__id__msj">
				<div class="direct-chat-msg hepsiniYukle">
					<button onclick="mesaj.hepsiniYukle(__id__);">Tüm mesajları yükle</button>
				</div>
			</div>
		</div>

		<div class="box-footer">
			<form action="#" method="post">
				<div class="input-group">
					<input class="form-control msjim" placeholder="Mesajınızı yazın..." type="text" onkeydown="if(event.keyCode == 13 /*Enter'e basıldıysa*/){ mesaj.gonder(__id__, this); return false;}">
					<span class="input-group-btn">
						<button type="button" onclick="mesaj.gonder(__id__, this.parentNode.parentNode.getElementsByClassName('msjim')[0]); return false;" class="btn btn-success"><i class="fa fa-send"></i></button>
						<!-- <button type="button" class="btn btn-warning btn-flat">Send</button> -->
					</span>
				</div>
			</form>
		</div>

	</div>

</div>

<div class="row">
	<div class="col-lg-7 connectedSortable ui-sortable" id="mesajAlani"><!-- Taşınamıyor?--></div>

	<!-- Yöneticilerin Gösterildiği Bölüm -->
	<div class="col-lg-5 connectedSortable ui-sortable">
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Yöneticiler</h3>

				<div class="box-tools pull-right">
					<span class="label label-danger">Siz hariç <?php echo count($tumAdminler)-1; ?> yönetici</span>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body no-padding">
				<ul class="users-list clearfix">
					<?php
					foreach ($tumAdminler as $birAdmin) {
						if ($birAdmin->id != $admin->id) {
							echo '<li>
							<img src="'. base_url('rel/') . 'img/admin/' . $birAdmin->id .'.jpg" alt="Yönetici resmi">
							<a class="users-list-name" onclick="mesaj.goster('. $birAdmin->id .');">'. $birAdmin->ad .'</a>
							<span class="users-list-date">'. gecenZaman($birAdmin->sonCevrimici) .'</span>
							</li>';
						}
					}
					?>
				</ul>
				<!-- /.users-list -->
			</div>
			<!-- /.box-body -->
			<div class="box-footer text-center">
				<?php echo ($admin->duzey < 6)?'<a href="'. base_url('admin/') .'yonetici/yonet">Yöneticileri Yönet</a>':''; ?>
			</div>
			<!-- /.box-footer -->
		</div>
	</div>
</div>
