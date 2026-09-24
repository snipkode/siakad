<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="mx-auto max-w-3xl space-y-4">
  <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
    <h2 class="text-sm font-bold text-slate-800 sm:text-base">Referensi Data</h2>
    <p class="mt-0.5 text-[11px] text-slate-500 sm:text-xs">Taksonomi terpadu yang dipakai seluruh modul. Buat/muat data di sini, lalu pilih kategori di bawah.</p>
    <div class="mt-3 grid grid-cols-1 gap-2.5 sm:mt-4 sm:grid-cols-2 sm:gap-3">
      <?php foreach ($kategori_boleh as $k => $label): ?>
        <a href="<?php echo site_url('referensi/index/'.$k); ?>"
           class="flex items-center justify-between gap-2 rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-medium text-slate-700 transition hover:border-sky-300 hover:bg-sky-50">
          <span class="truncate"><i class="fa fa-database mr-2 text-slate-400"></i><?php echo $label; ?></span>
          <i class="fa fa-chevron-right shrink-0 text-xs text-slate-300"></i>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>