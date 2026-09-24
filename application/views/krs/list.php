<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-list-alt"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Kartu Rencana Studi (KRS)</h3>
        <p class="text-xs text-slate-500">
          Total
          <span id="total-mhs" class="inline-flex items-center justify-center rounded-full bg-cyan-100 text-cyan-700 px-2 py-0.5 text-[11px] font-bold">0</span>
          mahasiswa terdaftar
        </p>
      </div>
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full text-sm">
      <thead>
        <tr>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NIM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">PRODI</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">ANGKATAN</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">ROMBONGAN</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">SKS</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">IP</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">IPK</th>
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
      "ajax": '<?php echo site_url('krs/data'); ?>',
      "order": [[0, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $('#total-mhs').text(json.recordsTotal);
        var $sf = $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input");
        $sf.attr("placeholder", "Cari NIM / nama / prodi...");
        $sf.css("min-width", "200px");
      },
      "columns": [
        { "data": "nim", "class": "font-mono text-center" },
        { "data": "nama" },
        { "data": "nama_prodi" },
        { "data": "angkatan", "class": "text-center" },
        { "data": "nama_kelas", "class": "text-center" },
        { "data": "sks_diambil", "class": "text-center font-semibold text-sky-600" },
        { "data": "ip", "class": "text-center" },
        { "data": "ipk", "class": "text-center" },
        { "data": "aksi", "class": "text-center", "orderable": false, "searchable": false }
      ]
    });

    t.on('draw.dt', function () {
      $('#total-mhs').text(t.page.info().recordsTotal);
    });
  });
</script>