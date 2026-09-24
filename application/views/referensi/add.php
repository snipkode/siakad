<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="mx-auto max-w-xl">
  <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
    <h2 class="text-sm font-bold text-slate-800 sm:text-base">Tambah <?php echo $label_kategori; ?></h2>
    <p class="text-xs text-slate-500">Kategori: <span class="font-mono text-slate-600"><?php echo $kategori; ?></span></p>

    <?php if ($msg = $this->session->flashdata('msg_ref')): ?>
      <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm font-medium text-amber-800"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php echo form_open('referensi/add/'.$kategori, 'role="form"'); ?>
      <div class="mt-4 space-y-3 sm:mt-5 sm:space-y-4">
        <div>
          <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kode</label>
          <input type="text" name="kode" placeholder="mis. IPA / SI / MTK" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
          <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</label>
          <input type="text" name="nama" placeholder="Nama lengkap" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
        </div>

        <?php if ($is_mapel): ?>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">SKS (kampus)</label>
            <input type="number" name="attr_sks" min="0" max="12" value="3" class="w-32 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
          </div>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jenjang</label>
            <input type="text" name="attr_jenjang" placeholder="mis. S1 / D3" class="w-40 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
          </div>
        <?php endif; ?>

        <?php if ($kategori === 'PRODI'): ?>
          <div>
            <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Akreditasi</label>
            <input type="text" name="attr_akreditasi" placeholder="A / B / C" class="w-32 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
          </div>
        <?php endif; ?>

        <div>
          <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-slate-500">Urutan</label>
          <input type="number" name="urutan" value="0" class="w-28 rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
        </div>
        <div class="flex items-center gap-2">
          <label class="text-sm text-slate-600">Aktif</label>
          <select name="is_aktif" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-sky-400 focus:outline-none">
            <option value="Y">Ya</option>
            <option value="N">Tidak</option>
          </select>
        </div>

        <div class="flex items-center gap-2 pt-2">
          <button type="submit" name="submit" class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
          <?php echo anchor('referensi/index/'.$kategori, 'Kembali', array('class' => 'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>