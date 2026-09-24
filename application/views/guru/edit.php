<div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Edit <?php echo meta_mode_label('label_staf'); ?></h3>

  <?php echo form_open('guru/edit', 'role="form"'); ?>
  <?php echo form_hidden('id_guru', $guru['id_guru']); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700"><?php echo meta_label('guru', 'nomor_induk', 'NUPTK'); ?></label>
        <input type="text" name="nuptk" value="<?php echo $guru['nuptk']; ?>" placeholder="Masukkan <?php echo meta_label('guru', 'nomor_induk', 'NUPTK'); ?>"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama <?php echo meta_mode_label('label_staf'); ?></label>
        <input type="text" name="nama_guru" value="<?php echo $guru['nama_guru']; ?>" placeholder="Masukkan Nama Lengkap <?php echo meta_mode_label('label_staf'); ?>"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Gender</label>
        <?php echo form_dropdown('gender', array('Pilih Gender', 'P'=>'Pria', 'W'=>'Wanita'), $guru['gender'], "class='w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30'"); ?>
      </div>

      <div></div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
        <input type="text" name="username" value="<?php echo $guru['username']; ?>" placeholder="Masukkan Username"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
        <input type="password" name="password" value="" placeholder="Kosongkan jika tidak diubah"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
    </div>

    <?php $this->load->view('common/_eav_fields'); ?>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
        Simpan
      </button>
      <?php echo anchor('guru', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>