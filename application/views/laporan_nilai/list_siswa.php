<div class="mb-4 grid grid-cols-2 gap-3 lg:max-w-2xl">
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tahun Akademik</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo get_tahun_akademik('tahun_akademik'); ?></p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Semester</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo get_tahun_akademik('semester'); ?></p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm lg:col-span-2">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Jurusan &amp; Tingkatan</p>
    <?php if (!empty($kelas['nama_kelas'])): ?>
      <p class="mt-0.5 text-sm font-bold text-slate-800">Jurusan <?php echo $kelas['nama_jurusan'] ?? ''; ?> <?php echo $kelas['nama_tingkatan'] ?? ''; ?> (<?php echo $kelas['nama_kelas']; ?>)</p>
    <?php elseif (empty($is_guru)): ?>
      <p class="mt-0.5 text-sm font-bold text-slate-800">Semua Kelas</p>
    <?php else: ?>
      <p class="mt-0.5 text-sm font-bold text-slate-400">Anda belum ditugaskan sebagai wali kelas</p>
    <?php endif; ?>
  </div>
</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <h3 class="text-sm font-bold text-slate-800">Cetak Raport Siswa</h3>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <?php if ($siswa->num_rows() == 0): ?>
      <p class="py-10 text-center text-sm text-slate-400"><i class="fa fa-folder-open-o mr-2" aria-hidden="true"></i>Belum ada siswa di kelas Anda</p>
    <?php else: ?>
    <table class="w-full min-w-[720px] text-sm">
      <thead>
        <tr class="border-b border-slate-200">
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NIM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA SISWA</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">KELAS</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">RATA-RATA</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">STATUS</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = 1;
          foreach ($siswa->result() as $row) {
            $harus   = isset($jml_mapel_kelas[$row->kd_kelas]) ? (int) $jml_mapel_kelas[$row->kd_kelas] : 0;
            $siap    = ($harus > 0 && (int) $row->jml_mapel >= $harus) || ($harus == 0 && (int) $row->jml_mapel > 0);
            $jalur   = $siap ? '<span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-700"><i class="fa fa-check" aria-hidden="true"></i> Siap Cetak</span>' : '<span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700"><i class="fa fa-clock-o" aria-hidden="true"></i> Belum Lengkap</span>';
            $rata    = is_null($row->rata_nilai) ? '-' : $row->rata_nilai;
            echo "<tr class='border-b border-slate-100 last:border-0'>
                    <td class='px-3 py-2.5 text-center text-slate-400'>$no</td>
                    <td class='px-3 py-2.5 text-center'>$row->nim</td>
                    <td class='px-3 py-2.5 font-medium text-slate-800'>$row->nama</td>
                    <td class='px-3 py-2.5 text-slate-600'>$row->nama_kelas</td>
                    <td class='px-3 py-2.5 text-center font-semibold text-slate-700'>$rata</td>
                    <td class='px-3 py-2.5 text-center'>$jalur</td>
                    <td class='px-3 py-2.5 text-center'>".anchor('laporan_nilai/nilai_semester/'.$row->nim, '<i class="fa fa-print mr-1.5" aria-hidden="true"></i> Cetak Raport', array('class'=>'inline-flex items-center gap-1.5 whitespace-nowrap rounded-lg bg-red-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-red-600 hover:shadow-md', 'target'=>'_blank', 'rel'=>'noopener', 'title'=>'Buka laporan nilai siswa (tab baru)'))."</td>
                  </tr>";
            $no++;
          }
        ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>