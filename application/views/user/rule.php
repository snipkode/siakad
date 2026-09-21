<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

  <!-- Filter -->
  <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-4 py-3">
      <h3 class="text-sm font-bold text-slate-800"><i class="fa fa-filter mr-1.5 text-sky-500"></i> Filter Data</h3>
    </div>
    <div class="space-y-3 p-4">
      <?php echo form_open(); ?>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-slate-700">Level User</label>
          <?php echo cmb_dinamis('level_user', 'tbl_level_user', 'nama_level', 'id_level_user', null, "id='filter_level' onChange='loadData()'"); ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabel -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
    <div class="border-b border-slate-100 px-4 py-3">
      <h3 class="text-sm font-bold text-slate-800">Data Hak Akses Modul</h3>
    </div>
    <div id="table-module" class="p-4"></div>
  </div>
</div>

<script>
  $(document).ready(function () {
    loadData();
  });

  function loadData() {
    var level = $("#filter_level").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>user/module',
      data: 'level_user=' + level,
      success: function (html) {
        $("#table-module").html(html);
      }
    });
  }

  function addRule(id_modul) {
    var level = $("#filter_level").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>user/add_rule',
      data: 'level_user=' + level + '&id_modul=' + id_modul,
      success: function (html) {
        alert("Sukses Merubah Hak Akses");
      }
    });
  }
</script>