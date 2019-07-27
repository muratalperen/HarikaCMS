<div class="box box-primary">
  <style media="screen">
    #reklamTable td{
      padding: 10px;
    }
  </style>
  <div class="box-header">
    <h3 class="box-title">Reklamlarınızı Yönetin</h3>
  </div>
  <table id="reklamTable" class="box-body">
    <tr>
      <td><b>Yan Reklam (JPG türünde)</b></td>
      <td><img src="<?php echo base_url(); ?>rel/img/reklam/1.png" alt="Yan reklam" class="img-fluid" style="max-width:100px;"></td>
      <td>
        <form class="form-inline" action="<?php echo base_url(); ?>/adminB/reklam" method="post" enctype="multipart/form-data">
          <input type="hidden" name="reklamHangi" value="yan">
          <input type="file" name="reklamResmi" class="form-control" required>
          <input type="url" name="baglanti" value="<?php echo $reklamData->yan; ?>" class="form-control" placeholder="https://reklam/adresi" required>
          <button type="submit" class="btn btn-primary">Güncelle</button>
        </form>
      </td>
    </tr>
  </table>
</div>
