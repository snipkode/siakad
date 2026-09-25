<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <div class="mb-6 flex items-center gap-3">
    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 text-base font-bold text-white">
      <i class="fa fa-lock"></i>
    </div>
    <div>
      <h3 class="text-lg font-bold text-slate-800">Ganti Password</h3>
      <p class="text-xs text-slate-500">Ganti password akun yang sedang login</p>
    </div>
  </div>

  <?php if ($msg = $this->session->flashdata('gagal_password')): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-3 py-2.5 text-sm font-medium text-red-700"><?php echo $msg; ?></div>
  <?php elseif ($msg = $this->session->flashdata('sukses_profil')): ?>
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2.5 text-sm font-medium text-emerald-700"><?php echo $msg; ?></div>
  <?php endif; ?>

  <?php echo form_open('profil/save_password', 'role="form"'); ?>

    <div class="grid grid-cols-1 gap-5">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Password Lama</label>
        <input type="password" name="current_password" placeholder="Masukkan Password Lama" autocomplete="current-password"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
        <input type="password" name="new_password" placeholder="Masukkan Password Baru" autocomplete="new-password"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" placeholder="Ulangi Password Baru" autocomplete="new-password"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('tampilan_utama', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>