<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="space-y-4">
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <h2 class="text-base font-bold text-slate-800"><?php echo $label_kategori; ?></h2>
        <p class="text-xs text-slate-500">Kategori: <span class="font-mono text-slate-600"><?php echo $kategori; ?></span></p>
      </div>
      <div class="flex gap-2">
        <?php echo anchor('referensi', 'Kembali', array('class' => 'rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
        <?php echo anchor('referensi/add/'.$kategori, '<i class="fa fa-plus mr-1"></i> Tambah', array('class' => 'inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700')); ?>
      </div>
    </div>

    <?php if ($msg = $this->session->flashdata('msg_ref')): ?>
      <div class="mt-4 rounded-xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-800"><?php echo $msg; ?></div>
    <?php endif; ?>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <table id="tbl-ref" class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
          <th class="px-3 py-2.5">Kode</th>
          <th class="px-3 py-2.5">Nama</th>
          <th class="px-3 py-2.5">Atribut</th>
          <th class="px-3 py-2.5 text-center">Aktif</th>
          <th class="px-3 py-2.5 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script>
$(function () {
  var tbl = $('#tbl-ref').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": { "url": '<?php echo site_url('referensi/data/'.$kategori); ?>', "type": "GET" },
    "columns": [
      { "data": "kode", "width": "120px", "class": "font-mono px-3 py-2.5" },
      { "data": "nama", "class": "px-3 py-2.5" },
      {
        "data": "atribut_json",
        "class": "px-3 py-2.5 text-xs text-slate-500",
        "render": function (d) {
          if (!d) return '-';
          try { var o = JSON.parse(d); return Object.keys(o).map(function (k) { return k + ': ' + o[k]; }).join(', '); }
          catch (e) { return d; }
        }
      },
      { "data": "is_aktif", "class": "px-3 py-2.5 text-center", "render": function (d) { return d === 'Y' ? '<span class="text-emerald-600"><i class="fa fa-check"></i></span>' : '<span class="text-slate-300"><i class="fa fa-ban"></i></span>'; } },
      { "data": "aksi", "class": "px-3 py-2.5 text-center", "orderable": false }
    ],
    "order": [[0, "asc"]],
    "dom": "<'mb-2'f>rt<'dataTables_pager flex items-center justify-between py-2 flex-wrap gap-2'lip>"
  });

  $('#tbl-ref_wrapper').closest('.rounded-2xl').find('.dataTables_filter input').attr("placeholder", 'Cari kode / nama...');
});
</script>