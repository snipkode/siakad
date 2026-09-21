<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Tambah Detail Kurikulum</h3>

  <?php echo form_open('kurikulum/add_detail', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Kurikulum</label>
        <?php echo cmb_dinamis('kurikulum', 'tbl_kurikulum', 'nama_kurikulum', 'id_kurikulum', $this->uri->segment(3), "readonly='true'"); ?>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Mata Pelajaran</label>
        <?php echo cmb_dinamis('mapel', 'tbl_mapel', 'nama_mapel', 'kd_mapel'); ?>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jurusan</label>
        <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan'); ?>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tingkatan Kelas</label>
        <?php echo cmb_dinamis('tingkatan', 'tbl_tingkatan_kelas', 'nama_tingkatan', 'kd_tingkatan'); ?>
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('kurikulum/detail/'.$this->uri->segment(3), 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>