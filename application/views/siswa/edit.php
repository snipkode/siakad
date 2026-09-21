<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Edit Siswa</h3>

  <?php echo form_open_multipart('siswa/edit', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">NIM</label>
        <input type="text" name="nim" value="<?php echo $siswa['nim']; ?>" readonly
               class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Lengkap</label>
        <input type="text" name="nama" value="<?php echo $siswa['nama']; ?>" placeholder="Masukkan Nama Lengkap"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" value="<?php echo $siswa['tempat_lahir']; ?>" placeholder="Tempat Lahir"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" value="<?php echo $siswa['tanggal_lahir']; ?>"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Gender</label>
        <?php echo form_dropdown('gender', array('Pilih Gender', 'L'=>'Laki-Laki', 'P'=>'Perempuan'), $siswa['gender'], "class='w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30'"); ?>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Agama</label>
        <?php echo cmb_dinamis('agama', 'tbl_agama', 'nama_agama', 'kd_agama', $siswa['kd_agama']); ?>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Kelas</label>
        <?php echo cmb_dinamis('kelas', 'tbl_kelas', 'nama_kelas', 'kd_kelas', $siswa['kd_kelas']); ?>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Foto</label>
        <input type="file" name="userfile"
               class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2 text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-sky-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-sky-700">
        <?php if (!empty($siswa['foto'])): ?>
          <img src="<?php echo base_url().'/uploads/'.$siswa['foto']; ?>" width="96" class="mt-3 rounded-xl border border-slate-200">
        <?php endif; ?>
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
        Simpan
      </button>
      <?php echo anchor('siswa', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>