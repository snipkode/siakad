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
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo "Jurusan".' '.($kelas['nama_jurusan'] ?? '').' '.($kelas['nama_tingkatan'] ?? ''); ?> (<?php echo $kelas['nama_kelas'] ?? ''; ?>)</p>
  </div>
</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <h3 class="text-sm font-bold text-slate-800">Cetak Raport Siswa</h3>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table class="w-full min-w-[360px] text-sm">
      <thead>
        <tr class="border-b border-slate-200">
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NIM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($siswa->result() as $row) {
            echo "<tr class='border-b border-slate-100 last:border-0'>
                    <td class='px-3 py-2.5 text-center'>$row->nim</td>
                    <td class='px-3 py-2.5'>$row->nama</td>
                    <td class='px-3 py-2.5 text-center'>".anchor('laporan_nilai/nilai_semester/'.$row->nim, 'Lihat Laporan Nilai', array('class'=>'btn btn-danger btn-sm'))."</td>
                  </tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</div>