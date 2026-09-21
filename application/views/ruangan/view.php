<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-building-o"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Data Ruangan</h3>
        <p class="text-xs text-slate-500">
          Total
          <span id="total-ruangan" class="inline-flex items-center justify-center rounded-full bg-orange-100 text-orange-700 px-2 py-0.5 text-[11px] font-bold">0</span>
          terdaftar
        </p>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 dt-actions">
      <?php
    echo anchor('ruangan/add', '<i class="fa fa-plus"></i> Tambah Data', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-sky-700'));
  ?>
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full text-sm">
      <thead>
        <tr>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">KODE RUANGAN</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA RUANGAN</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    var t = $('#mytable').DataTable({
      "ajax": '<?php echo site_url('ruangan/data'); ?>',
      "order": [[1, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $('#total-ruangan').text(json.recordsTotal);
        var $sf = $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input");
        $sf.attr("placeholder", "Cari ruangan / kode...");
        $sf.css("min-width", "200px");
      },
      "columns": [
{ "data": null, "width": "45px", "class": "text-center", "orderable": false },
        { "data": "kd_ruangan", "width": "150px", "class": "text-center font-mono" },
        { "data": "nama_ruangan" },
        { "data": "aksi", "width": "110px", "class": "text-center", "orderable": false, "searchable": false }
      ]
    });

    t.on('draw.dt', function () {
      $('#total-ruangan').text(t.page.info().recordsTotal);
      t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();
  });
</script>
