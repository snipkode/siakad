<script type="text/javascript">
  $(document).ready(function () {
    $("#kosong").hide();
  });
</script>

<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-1 text-lg font-bold text-slate-800">Form Import</h3>
  <a href="<?php echo base_url("csv/import_data.csv"); ?>" class="mb-4 inline-flex items-center gap-1 text-sm font-semibold text-sky-600 hover:text-sky-700">
    <i class="fa fa-download" aria-hidden="true"></i> Download Format
  </a>

  <form method="post" action="<?php echo base_url("Siswa/form"); ?>" enctype="multipart/form-data" class="mt-3 flex flex-wrap items-center gap-3">
    <input type="file" name="file" required
           class="rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-sky-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-sky-700">
    <button type="submit" name="preview" class="btn btn-info">Preview</button>
  </form>

  <?php
  if (isset($_POST['preview'])) {
    if (isset($upload_error)) {
      echo "<div class='mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600'>".$upload_error."</div>";
      die;
    }

    echo "<form method='post' action='".base_url('Siswa/import')."'>";

    echo "<div class='mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600' id='kosong'>
    Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum terisi semua.
    </div>";

    echo "<table class='mt-4 w-full overflow-hidden rounded-xl border border-slate-200 text-sm'>
    <tr class='bg-slate-100 text-left'>
      <th colspan='5' class='px-3 py-2.5'>Preview Data</th>
    </tr>
    <tr class='bg-slate-50'>
      <th class='px-3 py-2.5'>NIM</th>
      <th class='px-3 py-2.5'>Nama</th>
      <th class='px-3 py-2.5'>Tanggal Lahir</th>
      <th class='px-3 py-2.5'>Tempat Lahir</th>
    </tr>";

    $numrow = 1;
    $kosong = 0;

    foreach ($sheet as $row) {
      $cellIterator = $row->getCellIterator();
      $cellIterator->setIterateOnlyExistingCells(false);

      $get = array();
      foreach ($cellIterator as $cell) {
        array_push($get, $cell->getValue());
      }

      $nim = $get[0];
      $nama = $get[1];
      $tanggal_lahir = $get[2];
      $tempat_lahir = $get[3];

      if (empty($nim) && empty($nama) && empty($tanggal_lahir) && empty($tempat_lahir))
        continue;

      if ($numrow > 1) {
        $nim_td = (!empty($nim)) ? "" : " style='background: #E07171;'";
        $nama_td = (!empty($nama)) ? "" : " style='background: #E07171;'";
        $tanggal_lahir_td = (!empty($tanggal_lahir)) ? "" : " style='background: #E07171;'";
        $tempat_lahir_td = (!empty($tempat_lahir)) ? "" : " style='background: #E07171;'";

        if (empty($nim) or empty($nama) or empty($tanggal_lahir) or empty($tempat_lahir)) {
          $kosong++;
        }

        echo "<tr class='border-t border-slate-100'>";
        echo "<td class='px-3 py-2'$nim_td>".$nim."</td>";
        echo "<td class='px-3 py-2'$nama_td>".$nama."</td>";
        echo "<td class='px-3 py-2'$tanggal_lahir_td>".$tanggal_lahir."</td>";
        echo "<td class='px-3 py-2'$tempat_lahir_td>".$tempat_lahir."</td>";
        echo "</tr>";
      }

      $numrow++;
    }

    echo "</table>";

    if ($kosong > 1) {
  ?>
      <script>
        $(document).ready(function () {
          $("#jumlah_kosong").html('<?php echo $kosong; ?>');
          $("#kosong").show();
        });
      </script>
  <?php
    } else {
      echo "<hr class='my-5 border-slate-200'>";
      echo "<button type='submit' name='import' class='btn btn-success'>Import</button> ";
      echo "<a href='".base_url("Siswa")."' class='btn btn-danger'>Cancel</a>";
    }

    echo "</form>";
  }
  ?>
</div>