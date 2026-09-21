<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Tambah Kelas</h3>

  <?php echo form_open('kelas/add', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Kode Kelas</label>
        <input type="text" name="kd_kelas" placeholder="Masukkan Kode Kelas"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Kelas</label>
        <input type="text" name="nama_kelas" placeholder="Masukkan Nama Kelas"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tingkatan</label>
        <?php echo cmb_dinamis('tingkatan', 'tbl_tingkatan_kelas', 'nama_tingkatan', 'kd_tingkatan'); ?>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jurusan</label>
        <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan'); ?>
      </div>
    </div>

    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-700">
      <p class="font-semibold"><i class="fa fa-warning"></i> Catatan</p>
      <p class="mt-1">Di akhir Kode Kelas harus ditambahkan angka 1/2.<br>Contoh : 7-A1 untuk Kelas 7-A IPA &amp; 7-A2 untuk Kelas 7-A IPS.</p>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('kelas', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>