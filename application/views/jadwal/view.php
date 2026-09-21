<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

  <!-- filter -->
  <div class="h-fit overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="bg-gradient-to-br from-sky-500 to-teal-600 px-4 py-4 text-white">
      <div class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
          <i class="fa fa-filter" aria-hidden="true"></i>
        </span>
        <div>
          <h3 class="text-sm font-bold leading-tight">Filter Jadwal</h3>
          <p class="text-[11px] leading-tight text-sky-100">Pilih jurusan, tingkat, dan kelas</p>
        </div>
      </div>
    </div>

    <div class="p-4">
      <?php echo form_open('jadwal/cetak_jadwal', 'id="formCetak" class="space-y-4"'); ?>

        <div>
          <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-slate-700">
            <i class="fa fa-graduation-cap text-xs text-sky-500" aria-hidden="true"></i> Jurusan
          </label>
          <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', null, "id='filter_jurusan' onChange='loadKelas()'"); ?>
        </div>

        <div>
          <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-slate-700">
            <i class="fa fa-bars text-xs text-sky-500" aria-hidden="true"></i> Tingkatan Kelas
          </label>
          <?php echo cmb_dinamis('tingkatan_kelas', 'tbl_tingkatan_kelas', 'nama_tingkatan', 'kd_tingkatan', null, "id='filter_tingkatan' onchange='loadKelas()'"); ?>
        </div>

        <div>
          <label class="mb-1.5 flex items-center gap-1.5 text-sm font-medium text-slate-700">
            <i class="fa fa-university text-xs text-sky-500" aria-hidden="true"></i> Kelas
          </label>
          <div id="tampilKelas"></div>
        </div>

        <div class="border-t border-slate-100 pt-3">
          <p class="mb-2 text-[10px] leading-snug text-slate-400">
            Generate: buat baris jadwal dari kurikulum. Auto Isi: isi hari &amp; jam kosong.
            Seed Full: jadwal penuh 1 minggu. Cetak PDF: jadwal kelas terpilih.
          </p>
          <div class="grid grid-cols-2 gap-1.5">
            <button type="button" onclick="askAction('generate')"
                    class="inline-flex w-full items-center justify-center gap-1 rounded-xl bg-sky-600 px-2.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-sky-700">
              <i class="fa fa-cogs text-[11px]" aria-hidden="true"></i> Generate Jadwal
            </button>
            <button type="button" onclick="askAction('auto_isi')"
                    class="inline-flex w-full items-center justify-center gap-1 rounded-xl bg-teal-600 px-2.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-teal-700">
              <i class="fa fa-magic text-[11px]" aria-hidden="true"></i> Auto Isi
            </button>
            <button type="button" onclick="askAction('seed')"
                    class="inline-flex w-full items-center justify-center gap-1 rounded-xl bg-indigo-600 px-2.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-indigo-700">
              <i class="fa fa-table text-[11px]" aria-hidden="true"></i> Seed Full 1 Minggu
            </button>
            <button type="button" onclick="askAction('cetak')"
                    class="inline-flex w-full items-center justify-center gap-1 rounded-xl bg-red-500 px-2.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-600">
              <i class="fa fa-print text-[11px]" aria-hidden="true"></i> Cetak PDF
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <!-- tabel -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
      <div class="flex items-center gap-3">
        <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-teal-600 text-white shadow-md sm:inline-flex">
          <i class="fa fa-calendar-check-o"></i>
        </span>
        <div>
          <h3 class="text-sm font-bold text-slate-800">Data Daftar Pelajaran</h3>
          <p class="text-xs text-slate-500">
            Total
            <span id="total-jadwal" class="inline-flex items-center justify-center rounded-full bg-sky-100 px-2 py-0.5 text-[11px] font-bold text-sky-700">0</span>
            jadwal terdata
          </p>
        </div>
      </div>
    </div>
    <div id="table_daftarpelajaran" class="p-2 sm:p-4"></div>
  </div>
</div>

<div class="mt-4 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800">
  <i class="fa fa-info-circle mt-0.5 shrink-0 text-amber-500" aria-hidden="true"></i>
  <p class="text-xs leading-relaxed sm:text-sm">
    <span class="font-bold">Catatan:</span> Jadwal yang belum punya hari &amp; jam akan diisi otomatis (hari/jam yang tidak bentrok) lewat tombol
    <span class="font-bold">Auto Isi</span>. Ubah guru, ruangan, hari, atau jam langsung dari tabel sesuai kebutuhan.
  </p>
</div>

<!-- Modal Konfirmasi Aksi -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-900/50 sm:items-center sm:p-4">
  <div class="w-full max-w-sm rounded-t-3xl bg-white shadow-xl sm:rounded-2xl">
    <div class="px-5 pb-5 pt-6 text-center">
      <span id="confirmIcon" class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-amber-600">
        <i id="confirmIconI" class="fa fa-info-circle text-xl" aria-hidden="true"></i>
      </span>
      <h5 id="confirmTitle" class="text-sm font-bold text-slate-800"></h5>
      <p id="confirmBody" class="mt-2 text-xs leading-relaxed text-slate-500"></p>
    </div>
    <div class="grid grid-cols-2 gap-2 border-t border-slate-100 p-4">
      <button type="button" onclick="closeConfirm()" class="rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-200">Batal</button>
      <button type="button" id="confirmOk" class="rounded-xl bg-sky-600 px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-sky-700">Ya, Lanjutkan</button>
    </div>
  </div>
</div>

<!-- Modal Generate -->
<div id="myModal" class="fixed inset-0 z-50 hidden items-end justify-center bg-slate-900/50 sm:items-center sm:p-4">
  <div class="w-full max-w-md rounded-t-3xl bg-white shadow-xl sm:rounded-2xl">
    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
      <h4 class="text-sm font-bold text-slate-800">Generate Jadwal</h4>
      <button type="button" onclick="closeModal()" class="rounded-full bg-slate-100 px-2.5 py-1 text-sm text-slate-500 hover:bg-slate-200">&times;</button>
    </div>
    <div class="px-5 py-4">
      <?php
        echo form_open('jadwal/generate_jadwal', 'role="form" class="form-horizontal"');
      ?>
        <div class="space-y-4">
          <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Kurikulum</label>
            <?php echo cmb_dinamis('kurikulum', 'tbl_kurikulum', 'nama_kurikulum', 'id_kurikulum'); ?>
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-slate-700">Semester</label>
            <?php echo form_dropdown('semester', array('ganjil' => 'Ganjil', 'genap' => 'Genap'), null, "class='form-control'"); ?>
          </div>
        </div>
        <div class="mt-5 flex items-center justify-end gap-2">
          <button type="button" onclick="closeModal()" class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">Tutup</button>
          <button type="submit" name="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Generate Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  function openModal() {
    document.getElementById('myModal').style.display = 'flex';
  }
  function closeModal() {
    document.getElementById('myModal').style.display = 'none';
  }
  document.getElementById('myModal').addEventListener('click', function (e) {
    if (e.target === this) closeModal();
  });

  function showConfirmModal() {
    document.getElementById('confirmModal').style.display = 'flex';
  }
  function closeConfirm() {
    document.getElementById('confirmModal').style.display = 'none';
  }
  document.getElementById('confirmModal').addEventListener('click', function (e) {
    if (e.target === this) closeConfirm();
  });

  function askAction(act) {
    var cfg = {
      generate: {
        title: 'Generate Jadwal',
        icon: 'cogs',
        color: 'bg-sky-100 text-sky-600',
        body: 'Membuat <b>baris jadwal baru</b> untuk setiap kelas berdasarkan <b>kurikulum</b> dan <b>semester</b> yang Anda pilih. Baris yang sudah ada <b>tidak dihapus</b> — gunakan <b>Auto Isi</b> untuk mengisi bagian yang masih kosong.',
        label: 'Lanjutkan',
        btn: 'bg-sky-600 hover:bg-sky-700'
      },
      auto_isi: {
        title: 'Auto Isi Jadwal',
        icon: 'magic',
        color: 'bg-teal-100 text-teal-600',
        body: 'Mengisi otomatis <b>hari &amp; jam</b> yang masih kosong untuk <b>semua kelas</b>, serta mencocokkan ruangan dengan rombel. Jadwal yang sudah terisi <b>tidak akan diubah</b>.',
        label: 'Ya, Isi Otomatis',
        btn: 'bg-teal-600 hover:bg-teal-700',
        url: baseJadwal + 'auto_isi'
      },
      seed: {
        title: 'Seed Full 1 Minggu',
        icon: 'table',
        color: 'bg-indigo-100 text-indigo-600',
        body: 'Menghapus <b>SEMUA jadwal</b> yang ada, lalu membuat <b>jadwal penuh 1 minggu</b> (Senin&ndash;Sabtu &times; seluruh jam, 4 mata pelajaran per tingkat) untuk semua kelas. <span class="font-bold text-red-500">Tindakan ini tidak bisa dibatalkan.</span>',
        label: 'Ya, Buat Penuh',
        btn: 'bg-indigo-600 hover:bg-indigo-700',
        url: baseJadwal + 'seed_full'
      },
      cetak: {
        title: 'Cetak PDF',
        icon: 'print',
        color: 'bg-red-100 text-red-600',
        body: 'Menyiapkan <b>PDF jadwal</b> untuk kelas yang sedang dipilih: tabel matriks 1 minggu + rincian lengkap (mapel, guru, ruangan, hari, jam). Siap untuk dicetak atau diunduh.',
        label: 'Cetak',
        btn: 'bg-red-500 hover:bg-red-600'
      }
    }[act];
    if (!cfg) return;

    document.getElementById('confirmIcon').className = 'mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full ' + cfg.color;
    document.getElementById('confirmIconI').className = 'fa fa-' + cfg.icon + ' text-xl';
    document.getElementById('confirmTitle').innerHTML = cfg.title;
    document.getElementById('confirmBody').innerHTML = cfg.body;
    var ok = document.getElementById('confirmOk');
    ok.innerHTML = cfg.label;
    ok.className = 'rounded-xl px-4 py-2.5 text-xs font-semibold text-white shadow-sm ' + cfg.btn;
    ok.onclick = function () {
      closeConfirm();
      if (act === 'cetak') {
        document.getElementById('formCetak').submit();
      } else if (act === 'generate') {
        openModal();
      } else if (cfg.url) {
        window.location.href = cfg.url;
      }
    };
    showConfirmModal();
  }
</script>

<script type="text/javascript">
  var baseJadwal = '<?php echo base_url(); ?>jadwal/';
  $(document).ready(function () {
    loadKelas();
  });
</script>

<script type="text/javascript">
  function loadKelas() {
    var tingkatan_kelas = $("#filter_tingkatan").val();
    var jurusan = $("#filter_jurusan").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>jadwal/tampil_kelas',
      data: 'kd_jurusan=' + jurusan + '&kd_tingkatan=' + tingkatan_kelas,
      success: function (html) {
        $("#tampilKelas").html(html);
        loadPelajaran();
      }
    });
  }

  function loadPelajaran() {
    var tingkatan_kelas = $("#filter_tingkatan").val();
    var jurusan = $("#filter_jurusan").val();
    var kelas = $("#kelas").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>jadwal/dataJadwal',
      data: 'kd_jurusan=' + jurusan + '&kd_tingkatan=' + tingkatan_kelas + '&kelas=' + kelas,
      success: function (html) {
        $("#table_daftarpelajaran").html(html);
        $("#total-jadwal").text($("#table_daftarpelajaran [data-row]").length);
        initJadwalTable();
      }
    });
  }

  function initJadwalTable() {
    var $t = $("#table_daftarpelajaran table");
    if (window.jQuery && $.fn.DataTable && $t.length) {
      $("#table_daftarpelajaran").removeClass("dataTables_processing");
      $t.DataTable({
        paging: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
        ordering: false,
        destroy: true
      });
    }
  }

  function updateGuru(id) {
    var guru = $("#guru" + id).val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>jadwal/update_guru',
      data: 'id_guru=' + guru + '&id_jadwal=' + id,
      success: function (html) {
        loadPelajaran();
      }
    });
  }

  function updateRuangan(id) {
    var ruangan = $("#ruangan" + id).val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>jadwal/update_ruangan',
      data: 'kd_ruangan=' + ruangan + '&id_jadwal=' + id,
      success: function (html) {
        loadPelajaran();
      }
    });
  }

  function updateHari(id) {
    var hari = $("#hari" + id).val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>jadwal/update_hari',
      data: 'hari=' + hari + '&id_jadwal=' + id,
      success: function (html) {
        loadPelajaran();
      }
    });
  }

  function updateJam(id) {
    var jam = $("#jam" + id).val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>jadwal/update_jam',
      data: 'jam=' + jam + '&id_jadwal=' + id,
      success: function (html) {
        loadPelajaran();
      }
    });
  }
</script>