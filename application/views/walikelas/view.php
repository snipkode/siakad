<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-star"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Data Walikelas</h3>
        <p class="text-xs text-slate-500">
          Total
          <span id="total-walikelas" class="inline-flex items-center justify-center rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-[11px] font-bold">0</span>
          terdaftar
        </p>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 dt-actions">
      
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full text-sm">
      <thead>
        <tr>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">KELAS</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">JURUSAN</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">TINGKATAN</th>          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA WALIKELAS</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    var t = $('#mytable').DataTable({
      "ajax": '<?php echo site_url('walikelas/data'); ?>',
      "order": [[1, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $('#total-walikelas').text(json.recordsTotal);
        var $sf = $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input");
        $sf.attr("placeholder", "Cari walikelas...");
        $sf.css("min-width", "200px");
      },
      "columns": [
        { "data": null, "width": "45px", "class": "px-3 py-2.5 text-center", "orderable": false },
        { "data": "nama_kelas", "width": "150px", "class": "px-3 py-2.5" },
        { "data": "nama_jurusan", "class": "px-3 py-2.5 text-center" },
        { "data": "nama_tingkatan", "class": "px-3 py-2.5 text-center" },
        { "data": "nama_guru", "class": "px-3 py-2.5" }
      ],
      "createdRow": function (row) {
        $(row).addClass('border-b border-slate-100 last:border-0');
      }
    });

    t.on('draw.dt', function () {
      $('#total-walikelas').text(t.page.info().recordsTotal);
      t.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
      });
    }).draw();

    window.updateWalikelas = function (id) {
      var $select = $('#guru' + id);
      var id_guru = $select.val();
      if (!id_guru) { return; }

      $.ajax({
        type: 'GET',
        url: '<?php echo site_url('walikelas/update_walikelas'); ?>',
        data: { id_walikelas: id, id_guru: id_guru },
        dataType: 'json',
        success: function (res) {
          showToast(res.message, res.status === 'error' ? 'error' : 'success');
        },
        error: function () {
          showToast('Gagal memperbarui data wali kelas', 'error');
        }
      });
    };

    window.showToast = function (msg, type) {
      var $toast = $('<div class="fixed bottom-5 left-1/2 z-[999] -translate-x-1/2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-lg ' + (type === 'error' ? 'bg-red-500' : 'bg-emerald-600') + '">' + msg + '</div>').appendTo('body');
      setTimeout(function () { $toast.fadeOut(300, function () { $(this).remove(); }); }, 2500);
    };
  });
</script>
