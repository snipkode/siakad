<div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-3 lg:max-w-3xl">
  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-2.5 bg-gradient-to-br from-sky-500 to-indigo-600 px-4 py-3 text-white">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-calendar" aria-hidden="true"></i>
      </span>
      <div>
        <p class="text-sm font-bold leading-tight">Tahun Akademik</p>
        <p class="text-[11px] leading-tight text-sky-100">Tahun berjalan</p>
      </div>
    </div>
    <div class="px-4 py-3">
      <p class="text-lg font-extrabold text-slate-800"><?php echo get_tahun_akademik('tahun_akademik'); ?></p>
      <p class="text-[11px] text-slate-400">Periode <?php echo get_tahun_akademik('tahun_akademik'); ?></p>
    </div>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-2.5 bg-gradient-to-br from-teal-500 to-emerald-600 px-4 py-3 text-white">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-list-alt" aria-hidden="true"></i>
      </span>
      <div>
        <p class="text-sm font-bold leading-tight">Semester</p>
        <p class="text-[11px] leading-tight text-teal-100">Periode aktif</p>
      </div>
    </div>
    <div class="px-4 py-3">
      <p class="text-lg font-extrabold capitalize text-slate-800"><?php echo get_tahun_akademik('semester'); ?></p>
      <p class="text-[11px] text-slate-400"><?php echo get_tahun_akademik('semester'); ?> ganjil / genap</p>
    </div>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-2.5 bg-gradient-to-br from-amber-500 to-orange-600 px-4 py-3 text-white">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-book-open" aria-hidden="true"></i>
      </span>
      <div>
        <p class="text-sm font-bold leading-tight">Jadwal</p>
        <p class="text-[11px] leading-tight text-amber-100">Kelas yang diajar</p>
      </div>
    </div>
    <div class="px-4 py-3">
      <p class="mt-0.5 flex items-baseline gap-1.5 text-lg font-extrabold text-slate-800 pl-0">
        <span id="total-jadwal" class="inline-flex items-center justify-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-700">0</span>
        <span class="text-sm font-semibold text-slate-500">Jadwal</span>
      </p>
      <p class="text-[11px] text-slate-400">Semua mapel terbagi</p>
    </div>
  </div>
</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-book-open"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Daftar Kelas yang Diajar</h3>
        <p class="text-xs text-slate-500">Kelola jadwal, lalu input nilai per mapel</p>
      </div>
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full min-w-[720px] text-sm">
      <thead>
        <tr class="border-b border-slate-200">
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">KELAS</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">Jurusan &amp; Tingkatan</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">MATA PELAJARAN</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">HARI</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">JAM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">RUANG</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    var t = $('#mytable').DataTable({
      "serverSide": true,
      "processing": true,
      "ajax": '<?php echo site_url('nilai/data'); ?>',
      "order": [[1, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $('#total-jadwal').text(json.recordsTotal);
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").attr("placeholder", "Cari kelas / mapel / jurusan / tingkatan...");
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").css("min-width", "220px");
      },
      "columns": [
        { "data": null, "width": "45px", "class": "text-center text-slate-400", "orderable": false },
        { "data": 1, "class": "font-semibold text-slate-700" },
        { "data": 2 },
        { "data": 3 },
        { "data": 4, "class": "capitalize" },
        { "data": 5 },
        { "data": 6 },
        { "data": 7, "width": "70px", "class": "text-center", "orderable": false, "searchable": false }
      ]
    });

    t.on('draw.dt', function () {
      $('#total-jadwal').text(t.page.info().recordsTotal);
      t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>