<?php
$this->load->view ('admin/include/header');

// echo uyar(SONUC_UYARI, 'Bu sayfayı gerekmedikçe meşgul etmeyin. Bu istatistiklerin oluşması için birçok veritabanı işlemi oluyor (' . sprintf("%.4f", $this->db->benchmark) . 'ms sürdü).', 'w');
?>

<!-- Bilgi Kutuları -->
<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-aqua"><i class="fa fa-mouse-pointer"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Bu Gün Tıklanma</span>
				<span class="info-box-number"><?php echo $anaBilgiler['tik']; ?></span>
			</div>
		</div>
	</div>
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-red"><i class="fa fa-book"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Ürün Sayısı</span>
				<span class="info-box-number"><?php echo $anaBilgiler['urun']; ?></span>
			</div>
		</div>
	</div>

	<!-- fix for small devices only -->
	<div class="clearfix visible-sm-block"></div>

	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-green"><i class="fa fa-comment-o"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Bu Gün Yorum Sayısı</span>
				<span class="info-box-number"><?php echo $anaBilgiler['yorum']; ?></span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Yöneticiler</span>
				<span class="info-box-number"><?php echo $anaBilgiler['yonSay']; ?></span>
			</div>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<!-- /.col -->
</div>
<!-- /Bilgi Kutuları -->



<!-- Referanslar ve Bu ay tıklama -->
<div class="row">

	<div class="col-md-8">

		<!-- Aylık Tıklanma Grafiği -->
		<div class="box box-primary">
      <div class="box-header with-border">
        <i class="fa fa-bar-chart-o"></i>

        <h3 class="box-title">Aylık Tıklanma</h3>
      </div>
      <div class="box-body">
        <div id="line-chart" style="height: 300px;"></div>
      </div>
    </div>

	</div>

	<div class="col-md-4">

		<!-- hangi arama motorundan gelindiği -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-bar-chart-o"></i>
				<h3 class="box-title">Referanslar</h3>
			</div>
			<div class="box-body">
				<div id="referans-donut" style="height: 300px;"></div>
			</div>
		</div>

	</div>

</div>

<!-- DONUT'lar -->
<div class="row">
	<div class="col-md-4">

		<!-- İşletim Sistemi Donut -->
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">İşletim Sistemi Kullanımı</h3>
			</div>
			<div class="box-body no-padding">
				<div id="os-donut" style="height: 300px;"></div>
			</div>

		</div>

	</div>
	<!-- /.col -->

	<div class="col-md-4">
		<!-- Tarayıcı Donut -->
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Tarayıcı Kullanımı</h3>
			</div>
			<div class="box-body no-padding">
				<div id="tarayici-donut" style="height: 300px;"></div>
			</div>

		</div>
	</div>
	<!-- /.col -->

	<div class="col-md-4">
		<!-- Mobil Donut -->
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Platform</h3>
			</div>
			<div class="box-body no-padding">
				<div id="mobil-donut" style="height: 300px;"></div>
			</div>

		</div>
	</div>
	<!-- /.col -->
</div>

<!-- Main row -->
<div class="row">


	<div class="col-md-8">

		<!-- Tablo: En Çok Tıklananlar -->
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">En Çok Tıklananlar</h3>
			</div>

			<div class="box-body">
				<div class="table-responsive">

					<?php echo $tiklananlarTablo; ?>

				</div>
			</div>
		</div>

	</div>


	<div class="col-md-4">

		<div class="box box-primary">
			<div class="box-header with-border">
				<i class="fa fa-clock-o"></i>
				<h3 class="box-title">Giriş Saatleri</h3>
			</div>
			<div class="box-body">
				<div id="bar-chart" style="height: 300px;"></div>
			</div>
		</div>

		<?php if (seviyesi_yuksek_mi(YONETICI_MOD)): ?>
			<div class="box box-primary">
				<div class="box-header with-border">
					<i class="fa fa-download"></i>
					<h3 class="box-title">İndir</h3>
				</div>
				<div class="box-body">
					<a href="<?php echo base_url('adminB/'); ?>istatistik?csv=true"><i class="fa fa-file-text-o"></i> İstatistikleri CSV olarak indir</a>
				</div>
			</div>
		<?php endif; ?>
	</div>

</div>




<?php
$barData  = '';
for ($i=0; $i < 8; $i++) {
	$barData .= '["' . ($i*3) . '-' . (($i*3)+3) . '", ' . $saatler[$i] . ']' . ( ($i == 7)?'':',' );
}
$footereGonder['ekleJS'] = array (
	'bower_components/Flot/jquery.flot',
	'bower_components/Flot/jquery.flot.pie',
	'bower_components/Flot/jquery.flot.resize',
	'bower_components/Flot/jquery.flot.categories'
);

$footereGonder['footerExtra'] = "
<script type=\"text/javascript\">
$(function () {
  // 'use strict';

	/*
 	* LINE CHART
 	* ----------
 	*/

	var degerler = " . json_encode($jsonGunler) . ";
	var gorData = [];
	for (var i=0; i < 31; i++) {
		gorData[i] = [i, degerler[i]];
	}


	$.plot('#line-chart', [{data: gorData, color: '#3c8dbc'}], {
		grid  : {
			hoverable  : true,
			borderColor: '#f3f3f3',
			borderWidth: 1,
			tickColor  : '#f3f3f3'
		},
		series: {
			shadowSize: 3,
			// label: '',
			lines     : {
				show: true
			},
			points    : {
				show: true
			}
		},
		lines : {
			fill : false, //Grafik çizgilerinin altını doldurur.
			color: ['#3c8dbc']
		},
		yaxis : {
			show: true
		},
		xaxis : {
			show: true
		}
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

		$('#line-chart-tooltip').html(((x == 30)?'Bugün ':(30 - x) + ' gün önce ') + y + ' tıklanma')
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

	// Referans Donut
  var donutData = [" . $donut['ref'] . "]
  $.plot('#referans-donut', donutData, {
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
    legend: {
      show: false
    }
  })

	// Tarayıcı Donut
	 var donutData = [" . $donut['tara'] . "]
	 $.plot('#tarayici-donut', donutData, {
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
		 legend: {
			 show: false
		 }
	 })

	// İşletim sistemi Donut
	 var donutData = [" . $donut['os'] . "]
	 $.plot('#os-donut', donutData, {
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
		 legend: {
			 show: false
		 }
	 })

	// Mobil Donut
	 var donutData = [" . $donut['mobil'] . "]
	 $.plot('#mobil-donut', donutData, {
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
		 legend: {
			 show: false
		 }
	 })


	 /*
     * BAR CHART
     * ---------
     */
    $.plot('#bar-chart', [{color: '#3c8dbc', data: [$barData] }], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      }
    })
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
