<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-graduation-cap"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Data <?php echo meta_mode_label('label_peserta'); ?></h3>
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
        echo '<button type="button" onclick="openImportModal()" class="inline-flex items-center gap-1.5 rounded-xl bg-amber-500 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-amber-600"><i class="fa fa-upload"></i> Import Data</button>';
        if (meta_punya('punya_naik_kelas')) {
          echo anchor('siswa/naik_kelas', '<i class="fa fa-level-up"></i> Naik Kelas', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700'));
        }
      ?>
    </div>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="dataTable w-full text-sm">
      <thead>
        <tr>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">FOTO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500"><?php echo meta_label('peserta', 'nomor_induk'); ?></th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA</th>
          <th class="dt-hide-xs px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500"><?php echo mb_strtoupper(meta_mode_label('label_rombongan')); ?></th>
          <th class="dt-hide-xs px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">TEMPAT LAHIR</th>
          <th class="dt-hide-xs px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">TANGGAL LAHIR</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Modal Import -->
<div id="modal-import" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div id="modal-import-backdrop" onclick="closeImportModal()" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
  <div class="relative w-full max-w-md max-h-[85vh] overflow-hidden rounded-2xl bg-white shadow-2xl">
    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
      <div class="flex items-center gap-2.5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600"><i class="fa fa-upload"></i></span>
        <h3 class="text-sm font-bold text-slate-800">Form Import</h3>
      </div>
      <button type="button" onclick="closeImportModal()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"><i class="fa fa-times"></i></button>
    </div>
    <div id="siswa-import-body" class="max-h-[65vh] overflow-y-auto p-4"></div>
  </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div id="modal-edit-backdrop" onclick="closeEditModal()" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
  <div class="relative w-full max-w-2xl max-h-[90vh] overflow-hidden rounded-2xl bg-white shadow-2xl">
    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
      <div class="flex items-center gap-2.5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600"><i class="fa fa-pencil"></i></span>
        <h3 class="text-sm font-bold text-slate-800">Edit <?php echo meta_mode_label('label_peserta'); ?></h3>
      </div>
      <button type="button" onclick="closeEditModal()" class="flex h-7 w-7 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600"><i class="fa fa-times"></i></button>
    </div>
    <div id="siswa-edit-body" class="max-h-[72vh] overflow-y-auto p-4"></div>
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
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").attr("placeholder", "Cari <?php echo mb_strtolower(meta_mode_label('label_peserta')); ?> / <?php echo meta_label('peserta', 'nomor_induk', 'NIM'); ?>...");
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").css("min-width", "200px");
      },
      "columns": [
        { "data": null, "width": "45px", "class": "text-center", "orderable": false },
        { "data": "foto", "width": "60px", "class": "text-center", "orderable": false, "searchable": false },
        { "data": "nim", "width": "110px", "class": "text-center font-mono" },
        { "data": "nama" },
        { "data": "rombel", "width": "130px", "class": "dt-hide-xs" },
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

  window.openImportModal = function () {
    var $modal = $('#modal-import');
    $modal.removeClass('hidden').addClass('flex');
    document.body.classList.add('overflow-hidden');
    $('#siswa-import-body').html('<p class="py-8 text-center text-sm text-slate-400"><i class="fa fa-spinner fa-spin mr-2"></i>Menyiapkan form...</p>');
    $.get('<?php echo site_url('siswa/form'); ?>', function (html) {
      $('#siswa-import-body').html(html);
    });
  };

  window.closeImportModal = function () {
    $('#modal-import').addClass('hidden').removeClass('flex');
    document.body.classList.remove('overflow-hidden');
  };

  $('#modal-import').on('click', function (e) {
    if (e.target === this) { closeImportModal(); }
  });

  var siswaBase = '<?php echo site_url(); ?>';

  window.openEditModal = function (nim) {
    var $modal = $('#modal-edit');
    $modal.removeClass('hidden').addClass('flex');
    document.body.classList.add('overflow-hidden');
    $('#siswa-edit-body').html('<p class="py-8 text-center text-sm text-slate-400"><i class="fa fa-spinner fa-spin mr-2"></i>Menyiapkan form...</p>');
    $.get(siswaBase + 'siswa/form_edit/' + nim, function (html) {
      $('#siswa-edit-body').html(html);
    });
  };

  window.closeEditModal = function () {
    $('#modal-edit').addClass('hidden').removeClass('flex');
    document.body.classList.remove('overflow-hidden');
    $('#siswa-edit-body').empty();
  };

  $('#modal-edit').on('click', function (e) {
    if (e.target === this) { closeEditModal(); }
  });

  // Simpan edit via AJAX ke dalam modal, lalu tutup + refresh tabel
  $(document).on('submit', '#form-edit-siswa', function (e) {
    e.preventDefault();
    var $form = $(this);
    var $btn = $form.find('button[name="submit"]');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
    $.ajax({
      url: siswaBase + 'siswa/edit',
      type: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function (res) {
        if (res.ok) {
          closeEditModal();
          if ($.fn.DataTable.isDataTable('#mytable')) {
            $('#mytable').DataTable().ajax.reload(null, false);
          }
        } else {
          $('#edit-msg').removeClass('hidden').addClass('flex')
            .html('<i class="fa fa-exclamation-triangle mr-1" aria-hidden="true"></i>' + res.message);
        }
      },
      complete: function () {
        $btn.prop('disabled', false).html('Simpan');
      }
    });
    return false;
  });

  // Preview file: AJAX ke siswa/form, hasil (tabel preview + tombol import) di-inject ke modal
  $(document).on('submit', '#siswa-import-body form[data-preview="1"]', function (e) {
    e.preventDefault();
    var $btn = $(this).find('button[name="preview"]');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
    $.ajax({
      url: this.action,
      type: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function (html) {
        $('#siswa-import-body').html(html);
      },
      complete: function () {
        $btn.prop('disabled', false).html('<i class="fa fa-eye"></i> Preview');
      }
    });
    return false;
  });

  // Eksekusi import: AJAX ke siswa/import, lalu tutup modal + refresh tabel
  $(document).on('submit', '#siswa-import-body form[data-import="1"]', function (e) {
    e.preventDefault();
    var $btn = $(this).find('button[name="import"]');
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mengimport...');
    $.ajax({
      url: this.action,
      type: 'POST',
      data: new FormData(this),
      processData: false,
      contentType: false,
      success: function () {
        closeImportModal();
        if ($.fn.DataTable.isDataTable('#mytable')) {
          $('#mytable').DataTable().ajax.reload(null, false);
        }
      },
      complete: function () {
        $btn.prop('disabled', false).html('<i class="fa fa-upload"></i> Import');
      }
    });
    return false;
  });
</script>