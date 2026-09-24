<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Tambah <?php echo meta_mode_label('label_peserta'); ?></h3>

  <?php if (isset($upload_error)): ?>
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
      <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo $upload_error; ?>
    </div>
  <?php endif; ?>

  <?php echo form_open_multipart('siswa/add', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700"><?php echo meta_label('peserta', 'nomor_induk'); ?></label>
        <input type="text" name="nim" placeholder="Masukkan <?php echo meta_label('peserta', 'nomor_induk'); ?>"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">NISN</label>
        <input type="text" name="nisn" placeholder="NISN (opsional)"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700"><?php echo meta_label('peserta', 'nama'); ?></label>
        <input type="text" name="nama" placeholder="Masukkan Nama Lengkap"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" placeholder="Tempat Lahir"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Gender</label>
        <?php echo form_dropdown('gender', array('Pilih Gender', 'L'=>'Laki-Laki', 'P'=>'Perempuan'), null, "class='w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30'"); ?>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Agama</label>
        <select name="agama" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-700 bg-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          <option value="">-- Pilih --</option>
          <?php foreach (meta_ref('AGAMA') as $code => $nm): ?>
            <option value="<?php echo $code; ?>"><?php echo $nm; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700"><?php echo meta_mode_label('label_rombongan'); ?></label>
        <?php echo cmb_dinamis('kelas', 'tbl_kelas', 'nama_kelas', 'kd_kelas', null, null, "w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm text-slate-700 bg-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30", array('kd_mode' => meta_mode())); ?>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Foto</label>
        <label for="userfile" class="group flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/60 px-4 py-5 text-center transition hover:border-sky-400 hover:bg-sky-50/50">
          <input type="file" name="userfile" id="userfile" accept="image/*" class="sr-only">
          <img id="foto-preview" src="<?php echo base_url('uploads/default-avatar.svg'); ?>"
               class="h-16 w-16 rounded-full border border-slate-200 object-cover shadow-sm">
          <div>
            <p class="text-[13px] font-semibold text-slate-700">Klik untuk pilih foto</p>
            <p class="text-[11px] text-slate-400">JPG/PNG/GIF, maks 2 MB</p>
          </div>
        </label>
        <p id="foto-name" class="mt-1.5 hidden text-xs font-semibold text-sky-600"></p>
      </div>
    </div>

    <?php $this->load->view('common/_eav_fields'); ?>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
        Simpan
      </button>
      <?php echo anchor('siswa', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>

<script>
  $(function () {
    $('#userfile').on('change', function () {
      var f = this.files[0];
      if (!f) { return; }
      if (!/\.(jpe?g|png|gif)$/i.test(f.name)) { alert('Format file harus JPG, PNG, atau GIF'); this.value = ''; return; }
      if (f.size > 2 * 1024 * 1024) { alert('Ukuran file maksimal 2 MB'); this.value = ''; return; }
      var reader = new FileReader();
      reader.onload = function (e) { $('#foto-preview').attr('src', e.target.result); };
      reader.readAsDataURL(f);
      $('#foto-name').removeClass('hidden').text('<i class="fa fa-check"></i> ' + f.name).html('<i class="fa fa-check mr-1" aria-hidden="true"></i>' + f.name);
    });
  });
</script>