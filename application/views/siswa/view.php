<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-graduation-cap"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Data Siswa</h3>
        <p class="text-xs text-slate-500">
          Total
          <span id="total-siswa" class="inline-flex items-center justify-center rounded-full bg-sky-100 px-2 py-0.5 text-[11px] font-bold text-sky-700">0</span>
          terdaftar
        </p>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 dt-actions">
      <?php
        echo anchor('siswa/add', '<i class="fa fa-plus"></i> Tambah Data', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-sky-700'));
        echo anchor('siswa/form', '<i class="fa fa-upload"></i> Import Data', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-amber-600'));
        echo anchor('siswa/naik_kelas', '<i class="fa fa-level-up"></i> Naik Kelas', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700'));
      ?>
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full text-sm">
      <thead>
        <tr>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">FOTO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NIM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA</th>
          <th class="dt-hide-xs px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">TEMPAT LAHIR</th>
          <th class="dt-hide-xs px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">TANGGAL LAHIR</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    var t = $('#mytable').DataTable({
      "ajax": '<?php echo site_url('siswa/data'); ?>',
      "order": [[2, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $('#total-siswa').text(json.recordsTotal);
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").attr("placeholder", "Cari siswa / NIM...");
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").css("min-width", "200px");
      },
      "columns": [
        { "data": null, "width": "45px", "class": "text-center", "orderable": false },
        { "data": "foto", "width": "60px", "class": "text-center", "orderable": false, "searchable": false },
        { "data": "nim", "width": "110px", "class": "text-center font-mono" },
        { "data": "nama" },
        { "data": "tempat_lahir", "width": "150px", "class": "dt-hide-xs" },
        { "data": "tanggal_lahir", "width": "140px", "class": "dt-hide-xs text-center" },
        { "data": "aksi", "width": "110px", "class": "text-center", "orderable": false, "searchable": false }
      ]
    });

    t.on('draw.dt', function () {
      $('#total-siswa').text(t.page.info().recordsTotal);
      t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>