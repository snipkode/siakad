<div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

  <!-- filter -->
  <div class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-100 px-4 py-3">
      <h3 class="text-sm font-bold text-slate-800"><i class="fa fa-filter mr-1.5 text-sky-500"></i> Filter Data</h3>
    </div>
    <div class="space-y-3 p-4">
      <?php echo form_open('siswa/export_excel'); ?>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-slate-700">Jurusan</label>
          <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', null, "id='filter_jurusan' onChange='loadKelas()'", "block w-full rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-sm text-slate-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100"); ?>
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-slate-700">Kelas</label>
          <div id="kelas"></div>
        </div>
        <div class="pt-2">
          <button type="submit" name="export_jadwal" class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
            <i class="fa fa-print" aria-hidden="true"></i> Export Data
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- tabel -->
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
    <div class="flex items-center justify-between gap-2 border-b border-slate-100 px-4 py-3">
      <h3 class="text-sm font-bold text-slate-800">Data Table Siswa</h3>
      <span id="total-siswa" class="inline-flex items-center justify-center rounded-full bg-sky-100 px-2 py-0.5 text-[11px] font-bold text-sky-700"></span>
    </div>
    <div id="dataSiswa" class="overflow-x-auto p-2 sm:p-4"></div>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  $(document).ready(function () {
    loadKelas();
  });
</script>

<script type="text/javascript">
  function loadKelas() {
    var jurusan = $("#filter_jurusan").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>kelas/combobox_kelas',
      data: 'kd_jurusan=' + jurusan,
      success: function (html) {
        $("#kelas").html(html);
        var kelas = $("#cbkelas").val();
        loadSiswa(kelas);
      }
    });
  }

  function loadSiswa(kelas) {
    var kelas = $("#cbkelas").val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url() ?>siswa/loadDataSiswa',
      data: 'kd_kelas=' + kelas,
      success: function (html) {
        $("#dataSiswa").html(html);
        var total = $("#dataSiswa table tbody tr").length;
        $('#total-siswa').text(total + ' siswa');
      }
    });
  }
</script>