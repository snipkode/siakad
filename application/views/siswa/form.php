<div class="mb-3 flex items-center justify-between gap-3 rounded-xl border border-sky-100 bg-sky-50/60 px-3 py-2.5">
  <div class="flex items-center gap-2.5">
    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-600 text-white shadow-sm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></span>
    <div>
      <p class="text-[13px] font-bold text-slate-800">Format Import Siswa (.csv)</p>
      <p class="mt-0.5 text-[11px] leading-snug text-slate-500">
        Kolom: <span class="font-semibold text-sky-700">NIM</span>, <span class="font-semibold text-sky-700">Nama</span>, <span class="font-semibold text-sky-700">Tanggal Lahir</span>, <span class="font-semibold text-sky-700">Tempat Lahir</span>
      </p>
    </div>
  </div>
  <a href="<?php echo base_url("csv/import_data.csv"); ?>"
     class="inline-flex shrink-0 items-center gap-1.5 rounded-lg border border-sky-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-sky-700 shadow-sm hover:bg-sky-50">
    <i class="fa fa-download" aria-hidden="true"></i> Download
  </a>
</div>

<form method="post" action="<?php echo base_url("Siswa/form"); ?>" enctype="multipart/form-data"
      data-preview="1">

  <label for="file-csv" id="dropzone"
         class="group flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/60 px-4 py-5 text-center transition hover:border-sky-400 hover:bg-sky-50/50">
    <input type="file" name="file" id="file-csv" required accept=".csv,text/csv"
           class="sr-only">
    <span id="dropzone-empty" class="flex flex-col items-center gap-1.5 text-slate-500">
      <span class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-200/70 text-slate-400 transition group-hover:scale-105 group-hover:bg-sky-100 group-hover:text-sky-600">
        <i class="fa fa-cloud-upload text-lg" aria-hidden="true"></i>
      </span>
      <span class="text-[13px] font-semibold text-slate-700">
        Tarik &amp; letakkan file, atau <span class="text-sky-600 underline underline-offset-2">pilih file</span>
      </span>
      <span class="text-[11px] text-slate-400">Hanya file berformat <b>.csv</b> yang diperbolehkan</span>
    </span>
    <span id="dropzone-file" class="hidden items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 shadow-sm">
      <i class="fa fa-file-text-o text-sky-600" aria-hidden="true"></i>
      <span id="file-name" class="text-sm font-semibold text-slate-700"></span>
    </span>
  </label>

  <p id="file-error" class="mt-2 hidden text-xs font-semibold text-red-600">
    <i class="fa fa-exclamation-circle mr-1" aria-hidden="true"></i>
    File harus berformat .csv
  </p>

  <div class="mt-3 flex gap-2">
    <button type="submit" name="preview"
            class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
      <i class="fa fa-eye" aria-hidden="true"></i> Preview Data
    </button>
    <button type="button" onclick="closeImportModal()"
            class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
      Batal
    </button>
  </div>
</form>

<?php
  if (isset($_POST['preview'])) {
    if (isset($upload_error)) {
      echo "<div class='mt-4 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600'><i class='fa fa-exclamation-triangle' aria-hidden='true'></i>".$upload_error."</div>";
    } else {

      echo "<form method='post' action='".base_url('Siswa/import')."' data-import='1'>";

      echo "<div class='mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600' id='kosong' style='display:none'>
      Semua data belum diisi, Ada <span id='jumlah_kosong'></span> data yang belum terisi semua.
      </div>";

      echo "<div class='mt-4 overflow-hidden rounded-xl border border-slate-200'>
      <div class='flex items-center gap-2 bg-slate-100 px-3 py-2.5 text-sm font-bold text-slate-700'><i class='fa fa-table text-sky-600' aria-hidden='true'></i> Preview Data</div>
      <table class='w-full text-sm'>
      <tr class='bg-slate-50'>
        <th class='px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>NIM</th>
        <th class='px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Nama</th>
        <th class='px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Tanggal Lahir</th>
        <th class='px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Tempat Lahir</th>
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
          $nim_td = (!empty($nim)) ? "" : " style='background: #FEE2E2;'";
          $nama_td = (!empty($nama)) ? "" : " style='background: #FEE2E2;'";
          $tanggal_lahir_td = (!empty($tanggal_lahir)) ? "" : " style='background: #FEE2E2;'";
          $tempat_lahir_td = (!empty($tempat_lahir)) ? "" : " style='background: #FEE2E2;'";

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

      echo "</table></div>";

      if ($kosong > 1) {
    ?>
      <script>
        $("#jumlah_kosong").html('<?php echo $kosong; ?>');
        $("#kosong").show();
      </script>
  <?php
      } else {
      echo "<div class='mt-5 flex flex-wrap items-center gap-3'>";
      echo "<button type='submit' name='import' class='inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700'><i class='fa fa-upload' aria-hidden='true'></i> Import Sekarang</button> ";
      echo "<button type='button' onclick='closeImportModal()' class='rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200'>Batal</button>";
      echo "</div>";
      }

      echo "</form>";
    }
  }
?>

<script>
  $(function () {
    var $dropzone = $('#dropzone');
    var $file = $('#file-csv');

    $dropzone.on('dragover', function (e) {
      e.preventDefault();
      $(this).addClass('border-sky-400 bg-sky-50/50');
    });
    $dropzone.on('dragleave drop', function (e) {
      e.preventDefault();
      $(this).removeClass('border-sky-400 bg-sky-50/50');
    });
    $dropzone.on('drop', function (e) {
      var files = e.originalEvent.dataTransfer.files;
      if (files.length) {
        $file[0].files = files;
        $file.trigger('change');
      }
    });

    $file.on('change', function () {
      var f = this.files[0];
      if (!f) { return; }
      var isCsv = /\.csv$/i.test(f.name) || f.type === 'text/csv' || f.type === 'application/vnd.ms-excel';
      if (!isCsv) {
        $('#file-error').removeClass('hidden');
        $('#dropzone-file').addClass('hidden');
        $('#dropzone-empty').removeClass('hidden');
        this.value = '';
        return;
      }
      $('#file-error').addClass('hidden');
      $('#file-name').text(f.name);
      $('#dropzone-empty').addClass('hidden');
      $('#dropzone-file').removeClass('hidden').addClass('flex');
    });
  });
</script>