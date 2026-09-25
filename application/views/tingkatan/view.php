<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-bars"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Data Tingkatan Kelas</h3>
        <p class="text-xs text-slate-500">
          Total
          <span id="total-tingkatan" class="inline-flex items-center justify-center rounded-full bg-rose-100 text-rose-700 px-2 py-0.5 text-[11px] font-bold">0</span>
          terdaftar
        </p>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 dt-actions">
      <?php
    echo anchor('tingkatan/add', '<i class="fa fa-plus"></i> Tambah Data', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-sky-700'));
  ?>
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full text-[13px] sm:text-sm">
      <thead>
        <tr>
          <th class="px-3 py-2.5 text-left text-[11px] sm:text-xs font-semibold uppercase tracking-wide text-slate-500">NO</th>          <th class="px-3 py-2.5 text-left text-[11px] sm:text-xs font-semibold uppercase tracking-wide text-slate-500">KODE TINGKAT</th>          <th class="px-3 py-2.5 text-left text-[11px] sm:text-xs font-semibold uppercase tracking-wide text-slate-500">NAMA TINGKATAN</th>          <th class="px-3 py-2.5 text-left text-[11px] sm:text-xs font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
    </table>
  </div>
  <div id="empty-state" class="hidden py-14 text-center">
    <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
      <i class="fa fa-inbox"></i>
    </div>
    <p class="text-sm font-semibold text-slate-600">Belum ada data tingkatan</p>
    <p class="mt-1 text-xs text-slate-400">Klik tombol "Tambah Data" untuk mengisi data pertama.</p>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    var t = $('#mytable').DataTable({
      "ajax": '<?php echo site_url('tingkatan/data'); ?>',
      "order": [[2, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $('#total-tingkatan').text(json.recordsTotal);
        var $sf = $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input");
        $sf.attr("placeholder", "Cari tingkatan...");
        $sf.css("min-width", "200px");
      },
      "columns": [
{ "data": null, "width": "45px", "class": "text-center", "orderable": false },
        { "data": "kd_tingkatan", "width": "150px", "class": "text-center font-mono" },
        { "data": "nama_tingkatan" },
        { "data": "aksi", "width": "110px", "class": "text-center", "orderable": false, "searchable": false }
      ]
    });

    t.on('draw.dt', function () {
      $('#total-tingkatan').text(t.page.info().recordsTotal);
      t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();

    function toggleEmpty() {
      var total = t.page.info().recordsTotal;
      if (total === 0) {
        $('#mytable_wrapper').hide();
        $('#empty-state').removeClass('hidden');
      } else {
        $('#mytable_wrapper').show();
        $('#empty-state').addClass('hidden');
      }
    }
    t.on('draw.dt', toggleEmpty);
    toggleEmpty();
  });
</script>
