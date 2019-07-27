<?php $this->load->view ('admin/include/header'); ?>

<div class="row">
<?php foreach ($adminListe as $admininBiri): ?>
  <div class="col-md-4">
		<div class="box box-widget widget-user-2">
			<div class="widget-user-header bg-green">
				<div class="widget-user-image">
					<img class="img-circle" src="<?php echo base_url('rel/'); ?>img/admin/<?php echo $admininBiri->id; ?>.jpg" alt="Yönetici resmi">
				</div>
				<a href="<?php echo base_url('admin/') . 'profil/' . $admininBiri->id; ?>" class="text-black"><h3 class="widget-user-username"><?php echo $admininBiri->ad; ?></h3></a>
				<h5 class="widget-user-desc">
					Düzey: <?php echo yonetici_duzey_adi($admininBiri->duzey); ?>
					<span class="pull-right text-muted"><i class="fa fa-clock-o"></i> <?php echo gecenZaman($admininBiri->sonCevrimici); ?></span>
				</h5>
			</div>
			<div class="box-footer">
				<ul class="nav nav-stacked">
					<li>Toplam Ürün <span class="pull-right badge bg-blue"><?php echo $admininBiri->urunSayisi; ?></span></li>
					<!-- IDEA: buraya daha çok veri ekle. -->
				</ul>
			</div>
		</div>
	</div>
<?php endforeach; ?>
</div>


<div class="row">

	<div class="col-md-8">

		<!-- Aylık Tıklanma Grafiği -->
		<div class="box box-primary">
      <div class="box-header with-border">
        <i class="fa fa-bar-chart-o"></i>

        <h3 class="box-title">Ürün Paylaşımı</h3>
      </div>
      <div class="box-body">
        <div id="line-chart" style="height: 300px;"></div>
      </div>
    </div>

	</div>

	<div class="col-md-4">

		<!-- O ayki ürünlerin kaçta kaçını kimin paylaştığı -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>
				<h3 class="box-title">Paylaşılan Ürün Oranı (Aylık)</h3>
			</div>
			<div class="box-body">
				<div id="ay-urun-donut" style="height: 300px;"></div>
			</div>
		</div>

	</div>

</div>


<div class="row">

	<div class="col-md-4">

		<!-- O ürünlerin ne kadarını kimin paylaştığı -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>
				<h3 class="box-title">Paylaşılan Ürün Oranı</h3>
			</div>
			<div class="box-body">
				<div id="urun-donut" style="height: 300px;"></div>
			</div>
		</div>

	</div>

	<?php if ($this->config->item('istatistik_tut')): ?>
		<div class="col-md-4">

			<!-- Bu ay paylaşılanlardan tıklanma oranı -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-bar-chart-o"></i>
					<h3 class="box-title">Ürünlerine Tıklanma Oranı (Aylık)</h3>
				</div>
				<div class="box-body">
					<div id="ay-tiklanma-donut" style="height: 300px;"></div>
				</div>
			</div>

		</div>
		<div class="col-md-4">

			<!-- O ürünlerin ne kadarını kimin paylaştığı -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-bar-chart-o"></i>
					<h3 class="box-title">Ürünlerine Tıklanma Oranı</h3>
				</div>
				<div class="box-body">
					<div id="tiklanma-donut" style="height: 300px;"></div>
				</div>
			</div>

		</div>
	<?php endif; ?>

</div>


<div class="panel box box-primary">
	<div class="box-header with-border">
		<h4 class="box-title">
			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="">
				Grafikler Hakkında <i class="fa fa-info-circle"></i>
			</a>
		</h4>
	</div>
	<div id="collapseOne" class="panel-collapse collapse" aria-expanded="true" style="">
		<div class="box-body">
			<ul>
				<li>Ürün Paylaşımı: Tüm yöneticilerin şu andan itibaren 12 hafta geriye kadarki, haftalık paylaşım grafiğidir.</li>
				<li>Paylaşılan Ürün Oranı: Paylaşılan (Toplam veya o ay) ürün sayılarının oranıdır.</li>
				<?php if ($this->config->item('istatistik_tut')): ?>
					<li>Ürünlerine Tıklanma Oranı: Paylaşılan (Toplam veya o ay) ürünlerin toplam tıklanma oranıdır. (Örneğin birinin tıklanma oranı fazlaysa, yaptığı ürünler başarılıdır.)</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</div>

<?php
for ($i=0; $i < count($jsonHafta); $i++) {
	// IDEA: Chart'ın altında tarih yazmalı date('Y-m-d', mktime( date('d') - 7*$i ))
	$lineDatas .= '
	var degerler'.$i.' = ' . json_encode($jsonHafta[$i]) . ';
	var gorData'.$i.' = [];
	for (var i=0; i <= 12; i++) {
		gorData'.$i.'[i] = [i, degerler'.$i.'[i]];
	}
	';
	$lineList .= '{data : gorData'.$i.', color: "#' . rand(100, 999) . '", name: "' . $jsonHaftaAdlar[$i] . '"}' . (($i+1 == count($jsonHafta))?'':',');
}

$footereGonder['ekleJS'] = array (
	'bower_components/Flot/jquery.flot',
	'bower_components/Flot/jquery.flot.pie',
	'bower_components/Flot/jquery.flot.resize'
);

$footereGonder['footerExtra'] = "
<script type=\"text/javascript\">
$(function () {

	$lineDatas
  $.plot('#line-chart', [$lineList], {
		grid  : {
			hoverable  : true,
			borderColor: '#f3f3f3',
			borderWidth: 1,
			tickColor  : '#f3f3f3'
		},
		series: {
			shadowSize: 3,
			lines     : {show: true},
			points    : {show: true}
		},
		lines : {
			fill : false,
			color: ['#3c8dbc']
		},
		yaxis : {show: true},
		xaxis : {show: true}
	})
	//Üzerine gelince çıkacak şey
	$('<div class=\"tooltip-inner\" id=\"line-chart-tooltip\"></div>').css({
		position: 'absolute',
		display : 'none',
		opacity : 0.8
	}).appendTo('body')
	$('#line-chart').bind('plothover', function (event, pos, item) {
	if (item) {
		var x = item.datapoint[0].toFixed(0),
				y = item.datapoint[1].toFixed(0)

		$('#line-chart-tooltip').html(item.series.name + ', ' + ((x == 12)?'bu hafta ':(12 - x) + ' hafta önce ') + y + ' paylaşım yaptı.')
			.css({ top: item.pageY + 5, left: item.pageX + 5 })
			.fadeIn(200)
	} else {
		$('#line-chart-tooltip').hide()
	}

})
/* END LINE CHART */


	// -----------------------
	// - Donut Grafikler -
	// -----------------------
	var donutAyar = {
    series: {
      pie: {
        show       : true,
        radius     : 1,
        innerRadius: 0.5,
        label      : {
          show     : true,
          radius   : 2 / 3,
          formatter: labelFormatter,
          threshold: 0.1
          }
      }
    },
    legend: {show: false}
  }

	// Ayki ürün paylaşım Donut
  $.plot('#ay-urun-donut', [" . $donut['ayUrun'] . "], donutAyar)
	// Toplam ürün paylaşım donut
  $.plot('#urun-donut', [" . $donut['urun'] . "], donutAyar)

	" . (
	$this->config->item('istatistik_tut') ? "
		// Ayki ürün tıklanma oranı
  	$.plot('#ay-tiklanma-donut', [" . $donut['ayTiklanma'] . "], donutAyar)
		// Toplam ürün tıklanma oranı
  	$.plot('#tiklanma-donut', [" . $donut['tiklanma'] . "], donutAyar)
	" : ''
	) . "

});

function labelFormatter(label, series) {
	return '<div style=\"font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;\">'
    + label
    + '<br>'
    + Math.round(series.percent) + '%</div>'
}

</script>
";
$this->load->view ('admin/include/footer', $footereGonder);
?>
