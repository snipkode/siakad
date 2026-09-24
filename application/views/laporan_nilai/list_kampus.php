<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
    <div class="flex items-center gap-3">
      <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-white shadow-md sm:inline-flex">
        <i class="fa fa-file-pdf-o"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Laporan Hasil Studi — Kampus</h3>
        <p class="text-xs text-slate-500">KHS per semester & transkrip kumulatif (IPK)</p>
      </div>
    </div>
    <form method="get" action="<?php echo site_url('laporan_nilai'); ?>" class="flex flex-wrap gap-2">
      <input type="text" name="cari" value="<?php echo html_escape($cari); ?>" placeholder="Cari NIM / nama..."
             class="rounded-xl border border-slate-300 px-3.5 py-2 text-sm focus:border-sky-400 focus:outline-none">
      <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Cari</button>
    </form>
  </div>

  <div class="overflow-x-auto p-2 sm:p-4">
    <table class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-100">
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NIM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">PRODI</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">ANGKATAN</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">SKS</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">IP</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">IPK</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; foreach ($mahasiswa as $m): ?>
        <tr class="border-b border-slate-100 transition-colors hover:bg-slate-50">
          <td class="px-3 py-2.5 text-center text-xs font-semibold text-slate-400"><?php echo $no++; ?></td>
          <td class="px-3 py-2.5 font-mono text-xs text-slate-600"><?php echo $m['nim']; ?></td>
          <td class="px-3 py-2.5 font-medium text-slate-800"><?php echo $m['nama']; ?></td>
          <td class="px-3 py-2.5 text-slate-600"><?php echo $m['nama_prodi']; ?></td>
          <td class="px-3 py-2.5 text-center text-slate-600"><?php echo $m['angkatan']; ?></td>
          <td class="px-3 py-2.5 text-center font-semibold text-cyan-600"><?php echo $m['sks_diambil']; ?></td>
          <td class="px-3 py-2.5 text-center"><?php echo number_format((float) $m['ip'], 2); ?></td>
          <td class="px-3 py-2.5 text-center font-semibold"><?php echo number_format((float) $m['ipk'], 2); ?></td>
          <td class="px-3 py-2.5 text-center">
            <div class="inline-flex gap-1">
              <?php echo anchor('laporan_nilai/nilai_semester/'.$m['nim'], '<i class="fa fa-file-text-o"></i> KHS', 'class="inline-flex items-center gap-1 rounded-lg bg-sky-50 px-2.5 py-1.5 text-xs font-semibold text-sky-600 hover:bg-sky-100" title="Cetak KHS"'); ?>
              <?php echo anchor('laporan_nilai/transkrip/'.$m['nim'], '<i class="fa fa-file-pdf-o"></i> Transkrip', 'class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-100" title="Cetak Transkrip"'); ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($mahasiswa)): ?>
        <tr>
          <td colspan="9" class="px-3 py-12 text-center">
            <i class="fa fa-file-pdf-o text-3xl text-slate-200"></i>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Belum ada mahasiswa kampus terdaftar.</p>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>