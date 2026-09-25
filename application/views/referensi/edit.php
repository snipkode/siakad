<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="mx-auto max-w-2xl">
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-2.5 border-b border-slate-100 px-4 py-4 sm:px-5">
      <div class="flex items-center gap-3">
        <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 text-white shadow-md sm:inline-flex">
          <i class="fa fa-pencil"></i>
        </span>
        <div class="min-w-0">
          <h2 class="truncate text-sm font-bold text-slate-800 sm:text-base">Edit <?php echo $label_kategori; ?></h2>
          <p class="text-[11px] text-slate-500 sm:text-xs">Kategori: <span class="font-mono text-slate-600"><?php echo $kategori; ?></span></p>
        </div>
      </div>
      <?php echo anchor('referensi/index/'.$kategori, '<i class="fa fa-arrow-left mr-1"></i> Kembali', array('class' => 'inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200 sm:text-sm')); ?>
    </div>

    <?php if ($msg = $this->session->flashdata('msg_ref')): ?>
      <div class="mx-4 mt-3 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2.5 text-sm font-medium text-amber-800 sm:mx-5"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php echo form_open('referensi/edit/'.$kategori.'/'.$ref['id'], 'role="form"'); ?>
      <div class="grid grid-cols-1 gap-4 px-4 py-5 sm:grid-cols-2 sm:gap-5 sm:px-5 sm:py-6">
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kode</label>
          <input type="text" name="kode" value="<?php echo html_escape($ref['kode']); ?>" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
        </div>
        <div>
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Urutan</label>
          <input type="number" name="urutan" value="<?php echo (int) $ref['urutan']; ?>" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
        </div>
        <div class="sm:col-span-2">
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</label>
          <input type="text" name="nama" value="<?php echo html_escape($ref['nama']); ?>" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
        </div>

        <?php if ($is_mapel): ?>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">SKS (kampus)</label>
            <input type="number" name="attr_sks" min="0" max="12" value="<?php echo isset($attrs['sks']) ? (int)$attrs['sks'] : 3; ?>" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
          </div>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Jenjang</label>
            <input type="text" name="attr_jenjang" value="<?php echo isset($attrs['jenjang']) ? html_escape($attrs['jenjang']) : ''; ?>" placeholder="mis. S1 / D3" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
          </div>
        <?php endif; ?>

        <?php if ($kategori === 'PRODI'): ?>
          <div>
            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Akreditasi</label>
            <input type="text" name="attr_akreditasi" value="<?php echo isset($attrs['akreditasi']) ? html_escape($attrs['akreditasi']) : ''; ?>" placeholder="A / B / C" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
          </div>
        <?php endif; ?>

        <div class="sm:col-span-2">
          <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Aktif</label>
          <div class="flex w-fit gap-1 rounded-xl border border-slate-300 bg-slate-50 p-1 shadow-sm">
            <label class="cursor-pointer">
              <input type="radio" name="is_aktif" value="Y" <?php echo $ref['is_aktif'] !== 'N' ? 'checked' : ''; ?> class="peer sr-only">
              <span class="block rounded-lg px-4 py-1.5 text-sm font-semibold text-slate-500 peer-checked:bg-sky-600 peer-checked:text-white">Ya</span>
            </label>
            <label class="cursor-pointer">
              <input type="radio" name="is_aktif" value="N" <?php echo $ref['is_aktif'] === 'N' ? 'checked' : ''; ?> class="peer sr-only">
              <span class="block rounded-lg px-4 py-1.5 text-sm font-semibold text-slate-500 peer-checked:bg-slate-500 peer-checked:text-white">Tidak</span>
            </label>
          </div>
        </div>

        <div class="flex flex-col-reverse gap-2 pt-2 sm:col-span-2 sm:flex-row sm:justify-between sm:border-t sm:border-slate-100">
          <?php echo anchor('referensi/index/'.$kategori, '<i class="fa fa-arrow-left mr-1"></i> Kembali', array('class' => 'inline-flex flex-1 items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200 sm:flex-none')); ?>
          <button type="submit" name="submit" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 sm:flex-none"><i class="fa fa-save"></i> Simpan</button>
        </div>
      </div>
    <?php echo form_close(); ?>
  </div>
</div>