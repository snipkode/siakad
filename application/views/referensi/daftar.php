<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="mx-auto max-w-3xl space-y-4">
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h2 class="text-base font-bold text-slate-800">Referensi Data</h2>
    <p class="text-xs text-slate-500">Taksonomi terpadu yang dipakai seluruh modul. Buat/muat data di sini, lalu pilih kategori di bawah.</p>
    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
      <?php foreach ($kategori_boleh as $k => $label): ?>
        <a href="<?php echo site_url('referensi/index/'.$k); ?>"
           class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:border-sky-300 hover:bg-sky-50">
          <span><i class="fa fa-database mr-2 text-slate-400"></i><?php echo $label; ?></span>
          <i class="fa fa-chevron-right text-xs text-slate-300"></i>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>