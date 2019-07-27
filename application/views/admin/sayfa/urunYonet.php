<div class="box box-primary">
  <div class="box-header">
    <h3 class="box-title">Ürünleri Yönetin</h3>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <form method="get" class="form">
      <div class="row">
        <div class="col-md-3">
          <label for="kategori">Kategori</label>
          <select name="kategori" id="kategori" class="form-control" onchange="altKategoriAyarla (this.value);">
            <?php
            for ($i=0; $i < $u->kategoriSayisi; $i++) {
              echo '<option value="'.$i.'">'.$u->kateg[$i].'</option>';
            }
            ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="altkategori">Alt Kategori</label>
          <select name="altkategori" id="altkategori" class="form-control"></select>
        </div>
        <div class="col-md-4">
          <label for="arama">İsim</label>
          <input type="search" name="ara" id="arama" placeholder="Ara..." value="<?php echo (isset($_GET['ara'])?$_GET['ara']:''); ?>" class="form-control">
        </div>
        <div class="col-md-2">
          <label></label>
          <button type="submit" class="btn btn-primary form-control"><i class="fa fa-sort"></i> Sırala</button>
        </div>
      </div>
      <script type="text/javascript">
      var altKategoriler = [
        <?php
        for ($i=0; $i< $u->kategoriSayisi; $i++){
          echo '[';
          for ($w=0; $w < count($u->altkateg[$i]); $w++) {
            echo '"' . $u->altkateg[$i][$w] . '"' . (($w + 1 != count($u->altkateg[$i]))?',':'');
          }
          echo ']' . (($i + 1 != $u->kategoriSayisi )?',':'');
        }
         ?>
      ];
      var altKategoriNesnesi = document.getElementById("altkategori");
      function altKategoriAyarla (kategori){
        var optionlar = "";
        for (i=0; i < altKategoriler[kategori].length; i++){
          optionlar = optionlar + '<option value="' + i + '">' + altKategoriler[kategori][i] + '</option>';
        }
        altKategoriNesnesi.innerHTML = optionlar;
      }
      <?php echo (isset($_GET['kategori']))?'document.getElementById("kategori").value=' . $_GET['kategori'] . ';':''; ?>
      altKategoriAyarla (<?php echo (isset($_GET['kategori']))?$_GET['kategori']:0; ?>);
      <?php echo (isset($_GET['altkategori']))?'altKategoriNesnesi.value=' . $_GET['altkategori'] . ';':''; ?>
      </script>
    </form><hr>
		<?php echo $tablo; ?>
  </div>
  <!-- /.box-body -->
</div>
