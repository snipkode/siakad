<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Edit <?php echo meta_mode_label('label_rombongan'); ?></h3>

  <?php echo form_open('kelas/edit', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Kode</label>
        <input type="text" value="<?php echo $kelas['kd_kelas']; ?>" readonly name="kd_kelas"
               class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
        <input type="text" value="<?php echo $kelas['nama_kelas']; ?>" name="nama_kelas" placeholder="Masukkan Nama Rombongan"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <?php if (meta_mode() === 'KAMPUS'): ?>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Program Studi</label>
        <select name="prodi" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-700 bg-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          <option value="">-- Pilih --</option>
          <?php foreach (meta_ref('PRODI') as $code => $nm): ?>
            <option value="<?php echo $code; ?>" <?php echo ((string) $kelas['kd_prodi'] === (string) $code) ? 'selected' : ''; ?>><?php echo $nm; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Angkatan</label>
        <input type="number" name="angkatan" value="<?php echo html_escape($kelas['angkatan']); ?>" placeholder="mis. 2025"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <?php else: ?>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tingkatan</label>
        <select name="tingkatan" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-700 bg-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          <option value="">-- Pilih --</option>
          <?php foreach (meta_ref('TINGKATAN') as $code => $nm): ?>
            <option value="<?php echo $code; ?>" <?php echo ((string) $kelas['kd_tingkatan'] === (string) $code) ? 'selected' : ''; ?>><?php echo $nm; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jurusan</label>
        <select name="jurusan" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-700 bg-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          <option value="">-- Pilih --</option>
          <?php foreach (meta_ref('JURUSAN') as $code => $nm): ?>
            <option value="<?php echo $code; ?>" <?php echo ((string) $kelas['kd_jurusan'] === (string) $code) ? 'selected' : ''; ?>><?php echo $nm; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('kelas', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>