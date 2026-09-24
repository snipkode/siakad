<?php $siswa = $data['siswa']; // $data diextract template library, tetap disediakan utk kompatibilitas ?>
<div class="space-y-5">
  <?php if ($msg = $this->session->flashdata('msg_krs')): ?>
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800"><?php echo $msg; ?></div>
  <?php endif; ?>

  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
      <div class="flex items-center gap-3">
        <span class="hidden h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 text-white shadow-md sm:inline-flex">
          <i class="fa fa-user-graduate"></i>
        </span>
        <div>
          <h3 class="text-sm font-bold text-slate-800">KRS Mahasiswa</h3>
          <p class="text-xs text-slate-500">Tahun Akademik <?php echo $ta['tahun_akademik'] . ' - Semester ' . ucfirst($ta['semester']); ?></p>
        </div>
      </div>
      <?php echo anchor('krs', 'Kembali', array('class'=>'inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

    <div class="grid grid-cols-1 gap-4 px-4 py-4 sm:grid-cols-3 sm:px-5">
      <div class="rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3">
        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Mahasiswa</p>
        <p class="mt-1 text-sm font-bold text-slate-800"><?php echo $siswa['nama']; ?></p>
        <p class="text-xs font-mono text-slate-500"><?php echo $siswa['nim']; ?></p>
      </div>
      <div class="rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3">
        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Program Studi / Angkatan</p>
        <p class="mt-1 text-sm font-bold text-slate-800"><?php echo $siswa['nama_prodi']; ?> (<?php echo $siswa['angkatan']; ?>)</p>
        <p class="text-xs text-slate-500">Rombongan: <?php echo $siswa['nama_kelas']; ?></p>
      </div>
      <div class="grid grid-cols-3 gap-2">
        <div class="rounded-xl border border-cyan-100 bg-cyan-50/60 px-3 py-3 text-center">
          <p class="text-[11px] font-semibold uppercase tracking-wide text-cyan-600">SKS</p>
          <p class="mt-1 text-lg font-bold text-cyan-700"><?php echo $total_sks; ?></p>
        </div>
        <div class="rounded-xl border border-sky-100 bg-sky-50/60 px-3 py-3 text-center">
          <p class="text-[11px] font-semibold uppercase tracking-wide text-sky-600">IP</p>
          <p class="mt-1 text-lg font-bold text-sky-700"><?php echo number_format($ip, 2); ?></p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 px-3 py-3 text-center">
          <p class="text-[11px] font-semibold uppercase tracking-wide text-emerald-600">IPK</p>
          <p class="mt-1 text-lg font-bold text-emerald-700"><?php echo number_format($ipk, 2); ?></p>
        </div>
      </div>
    </div>
  </div>

  <?php echo form_open('krs/simpan/'.$siswa['nim'], 'role="form"'); ?>
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto p-2 sm:p-4">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-slate-100">
            <th class="w-10 px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
            <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">MATA KULIAH</th>
            <th class="w-16 px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">SKS</th>
            <th class="w-24 px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NILAI</th>
            <th class="w-24 px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">MUTU</th>
            <th class="w-16 px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">AMBIL</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($matakuliah as $mk): 
            $diambil = ($mk['id_nilai'] !== null);
            $sks_ef = $mk['sks'] > 0 ? $mk['sks'] : $mk['sks_mapel'];
          ?>
          <tr class="border-b border-slate-100 transition-colors hover:bg-slate-50">
            <td class="px-3 py-2.5 text-center text-xs font-semibold text-slate-400"><?php echo $no++; ?></td>
            <td class="px-3 py-2.5 font-medium text-slate-800"><?php echo $mk['nama_mapel']; ?>
              <span class="ml-1 text-[10px] font-mono uppercase text-slate-400"><?php echo $mk['kd_mapel']; ?></span>
            </td>
            <td class="px-3 py-2.5 text-center text-slate-700"><?php echo $sks_ef; ?></td>
            <td class="px-3 py-2.5 text-center">
              <input type="number" name="nilai[<?php echo $mk['id_jadwal']; ?>]" min="0" max="100" value="<?php echo $mk['nilai'] > 0 ? $mk['nilai'] : ''; ?>"
                     <?php echo $diambil ? '' : 'disabled'; ?>
                     class="w-20 rounded-lg border border-slate-300 px-2 py-1.5 text-center text-sm focus:border-sky-400 focus:outline-none">
            </td>
            <td class="px-3 py-2.5 text-center">
              <?php if ($mk['nilai'] > 0): ?>
                <span class="inline-flex rounded-full bg-<?php echo $mk['nilai'] >= 90 ? 'emerald' : ($mk['nilai'] >= 80 ? 'sky' : ($mk['nilai'] >= 60 ? 'amber' : 'red')); ?>-100 px-2 py-0.5 text-[11px] font-bold text-<?php echo $mk['nilai'] >= 90 ? 'emerald' : ($mk['nilai'] >= 80 ? 'sky' : ($mk['nilai'] >= 60 ? 'amber' : 'red')); ?>-700">
                  <?php echo $mk['nilai'] >= 90 ? 'A' : ($mk['nilai'] >= 80 ? 'B' : ($mk['nilai'] >= 70 ? 'C' : ($mk['nilai'] >= 60 ? 'D' : 'E'))); ?>
                </span>
              <?php else: ?>
                <span class="text-xs text-slate-300">-</span>
              <?php endif; ?>
            </td>
            <td class="px-3 py-2.5 text-center">
              <input type="checkbox" name="ambil[<?php echo $mk['id_jadwal']; ?>]" value="1"
                     <?php echo $diambil ? 'checked' : ''; ?>
                     class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($matakuliah)): ?>
          <tr>
            <td colspan="6" class="px-3 py-12 text-center">
              <i class="fa fa-folder-open-o text-3xl text-slate-200"></i>
              <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-400">Belum ada penawaran mata kuliah untuk rombongan ini.</p>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-4 py-4 sm:px-5">
      <p class="text-xs text-slate-500"><i class="fa fa-info-circle text-slate-300"></i> Centang mata kuliah yang diambil, isi nilai bila sudah dinilai, lalu simpan.</p>
      <div class="flex gap-2">
        <button type="submit" name="submit" class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan KRS</button>
      </div>
    </div>
  </div>
  <?php echo form_close(); ?>
</div>

<script>
  // aktif/nonaktifkan kolom nilai mengikuti tanda ambil
  document.querySelectorAll('input[name^="ambil["]').forEach(function (cb) {
    cb.addEventListener('change', function () {
      var id = this.name.replace('ambil[', '').replace(']', '');
      var inp = document.querySelector('input[name="nilai[' + id + ']"]');
      if (inp) { inp.disabled = !this.checked; }
    });
  });
</script>