</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal modal-danger fade" id="bildirim-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Modal Başlığı</h4>
        </div>
        <div class="modal-body">
          <p>Modal İçeriği&hellip;</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-dismiss="modal">Kapat</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <script type="text/javascript">
  function bildirim(baslik, metin, durum = null){
    var bildirimModal = document.getElementById("bildirim-modal");
    bildirimModal.getElementsByClassName("modal-title")[0].innerHTML = baslik;
    bildirimModal.getElementsByClassName("modal-body")[0].innerHTML = metin;
    bildirimModal.className = "modal " + ((durum == null)?"":(durum == "s")?"modal-success":(durum == "w")?"modal-warning":(durum == "d")?"modal-danger":"modal-default") + " fade";
    $('#bildirim-modal').modal();
  }
  </script>

<!-- Main Footer -->
<footer class="main-footer">
<!-- To the right -->
<div class="pull-right hidden-xs">
  <a href="https://github.com/muratalperen/HarikaCMS">Harika CMS</a> Kullanıldı
</div>
<!-- Default to the left -->
<strong>Copyright &copy; 2019 <a href="<?php echo base_url(); ?>"><?php echo $this->config->item('site')->ad; ?></a>.</strong> Tüm hakları saklıdır.
</footer>


<!-- GEREKLİ JS KODLARI -->
<!-- Jquery headere eklendi -->
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('rel/'); ?>admin/bower_components/bootstrap/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('rel/admin/'); ?>dist/js/adminlte.min.js"></script>

<?php
// Dışardan Gelirler:
if (isset ($ekleJS)){
  foreach ($ekleJS as $jsYolu) {
    echo '<script src="' . base_url('rel/admin/') . $jsYolu.'.js"></script>';
  }
}
echo ((isset($footerExtra))?$footerExtra:'');
?>
</body>
</html>
