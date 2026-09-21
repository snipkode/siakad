<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

  <!-- Filter -->
  <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-4 py-3">
      <h3 class="text-sm font-bold text-slate-800"><i class="fa fa-filter mr-1.5 text-sky-500"></i> Filter Data</h3>
    </div>
    <div class="space-y-3 p-4">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jurusan</label>
        <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', null, "id='filter_jurusan' onChange='loadData()'"); ?>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tingkatan Kelas</label>
        <?php echo cmb_dinamis('tingkatan_kelas', 'tbl_tingkatan_kelas', 'nama_tingkatan', 'kd_tingkatan', null, "id='filter_tingkatan' onChange='loadData()'"); ?>
      </div>
      <div class="grid grid-cols-2 gap-2 pt-1">
        <?php
          echo anchor('kurikulum/add_detail/'.$this->uri->segment(3), '<i class="fa fa-plus"></i> Tambah', array('class'=>'inline-flex items-center justify-center gap-1.5 rounded-xl bg-sky-600 px-3 py-2 text-xs font-semibold text-white hover:bg-sky-700'));
          echo anchor('kurikulum', 'Kembali', array('class'=>'inline-flex items-center justify-center rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200'));
        ?>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
    <div class="border-b border-slate-100 px-4 py-3">
      <h3 class="text-sm font-bold text-slate-800">Data Daftar Pelajaran</h3>
    </div>
    <div id="table_daftarpelajaran" class="p-4 text-center">
      <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-left">
        <p class="text-sm font-semibold text-amber-700"><i class="fa fa-warning"></i> Tingkatan Kelas Tidak Terdeteksi</p>
        <p class="mt-1 text-xs text-amber-600">Pilih Tingkatan Kelas yang ingin ditampilkan Data Daftar Pelajarannya di Filter Data terlebih dahulu.</p>
      </div>
    </div>
  </div>
</div>

<script>
  function loadData() {
    var tingkatan_kelas = $("#filter_tingkatan").val();
    var jurusan = $("#filter_jurusan").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>kurikulum/dataKurikulumDetail',
      data: 'kd_jurusan=' + jurusan + '&kd_tingkatan=' + tingkatan_kelas + '&kurikulumnya=<?php echo $this->uri->segment(3) ?>',
      success: function (html) {
        $("#table_daftarpelajaran").html(html);
      }
    });
  }
</script>