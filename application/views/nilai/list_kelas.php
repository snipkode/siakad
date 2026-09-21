<div class="mb-4 grid grid-cols-2 gap-3 lg:max-w-xl">
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tahun Akademik</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo get_tahun_akademik('tahun_akademik'); ?></p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Semester</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo get_tahun_akademik('semester'); ?></p>
  </div>
</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <h3 class="text-sm font-bold text-slate-800">Daftar Kelas yang Diajar</h3>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table class="w-full min-w-[640px] text-sm">
      <thead>
        <tr class="border-b border-slate-200">
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">KELAS</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">Jurusan &amp; Tingkatan</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">MATA PELAJARAN</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">HARI</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">JAM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">RUANG</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = 1;
          foreach ($jadwal->result() as $row) {
            echo "<tr class='border-b border-slate-100 last:border-0'>
                    <td class='px-3 py-2.5'>$no</td>
                    <td class='px-3 py-2.5 font-semibold text-slate-700'>$row->nama_kelas</td>
                    <td class='px-3 py-2.5'>Jurusan $row->nama_jurusan $row->nama_tingkatan</td>
                    <td class='px-3 py-2.5'>$row->nama_mapel</td>
                    <td class='px-3 py-2.5'>$row->hari</td>
                    <td class='px-3 py-2.5'>$row->jam</td>
                    <td class='px-3 py-2.5'>$row->nama_ruangan</td>
                    <td class='px-3 py-2.5 text-center'>".anchor('nilai/kelas/'.$row->id_jadwal, '<i class="fa fa-eye" aria-hidden="true"></i>', array('class'=>'inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100'))."</td>
                  </tr>";
            $no++;
          }
        ?>
      </tbody>
    </table>
  </div>
</div>