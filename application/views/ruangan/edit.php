<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Edit <?php echo meta_mode() === 'KAMPUS' ? 'Ruangan' : 'Ruang Kelas'; ?></h3>

  <?php echo form_open('ruangan/edit', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Kode <?php echo meta_mode() === 'KAMPUS' ? 'Ruangan' : 'Ruang Kelas'; ?></label>
        <input type="text" value="<?php echo $ruangan['kd_ruangan']; ?>" readonly name="kd_ruangan"
               class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama <?php echo meta_mode() === 'KAMPUS' ? 'Ruangan' : 'Ruang Kelas'; ?></label>
        <input type="text" value="<?php echo $ruangan['nama_ruangan']; ?>" name="nama_ruangan" placeholder="Masukkan Nama <?php echo meta_mode() === 'KAMPUS' ? 'Ruangan' : 'Ruang Kelas'; ?>"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('ruangan', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>