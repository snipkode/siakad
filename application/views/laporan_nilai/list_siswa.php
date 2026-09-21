<div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-3 lg:max-w-3xl">
  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-2.5 bg-gradient-to-br from-sky-500 to-indigo-600 px-4 py-3 text-white">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-calendar" aria-hidden="true"></i>
      </span>
      <div>
        <p class="text-sm font-bold leading-tight">Tahun Akademik</p>
        <p class="text-[11px] leading-tight text-sky-100">Tahun berjalan</p>
      </div>
    </div>
    <div class="px-4 py-3">
      <p class="text-lg font-extrabold text-slate-800"><?php echo get_tahun_akademik('tahun_akademik'); ?></p>
      <p class="text-[11px] text-slate-400">Periode <?php echo get_tahun_akademik('tahun_akademik'); ?></p>
    </div>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center gap-2.5 bg-gradient-to-br from-teal-500 to-emerald-600 px-4 py-3 text-white">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-list-alt" aria-hidden="true"></i>
      </span>
      <div>
        <p class="text-sm font-bold leading-tight">Semester</p>
        <p class="text-[11px] leading-tight text-teal-100">Periode aktif</p>
      </div>
    </div>
    <div class="px-4 py-3">
      <p class="text-lg font-extrabold capitalize text-slate-800"><?php echo get_tahun_akademik('semester'); ?></p>
      <p class="text-[11px] text-slate-400"><?php echo get_tahun_akademik('semester'); ?> ganjil / genap</p>
    </div>
  </div>

  <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm sm:col-span-2 lg:col-span-1">
    <div class="flex items-center gap-2.5 bg-gradient-to-br from-amber-500 to-orange-600 px-4 py-3 text-white">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-university" aria-hidden="true"></i>
      </span>
      <div>
        <p class="text-sm font-bold leading-tight">Jurusan &amp; Tingkatan</p>
        <p class="text-[11px] leading-tight text-amber-100">Cakupan raport</p>
      </div>
    </div>
    <div class="px-4 py-3">
      <?php if (!empty($kelas['nama_kelas'])): ?>
        <p class="text-sm font-extrabold text-slate-800">Jurusan <?php echo $kelas['nama_jurusan'] ?? ''; ?> <?php echo $kelas['nama_tingkatan'] ?? ''; ?></p>
        <p class="text-[11px] text-slate-400">Kelas <?php echo $kelas['nama_kelas']; ?> &middot; <?php echo $total_siswa; ?> siswa</p>
      <?php elseif (empty($is_guru)): ?>
        <p class="text-sm font-extrabold text-slate-800">Semua Kelas</p>
        <p class="text-[11px] text-slate-400"><?php echo $total_siswa; ?> siswa &middot; seluruh rombel</p>
      <?php else: ?>
        <p class="text-sm font-extrabold text-slate-400">Belum ditentukan</p>
        <p class="text-[11px] text-slate-400">Anda belum ditugaskan sebagai wali kelas</p>
      <?php endif; ?>
    </div>
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
    <?php if (empty($is_guru)): ?>
<div class="mb-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="bg-gradient-to-br from-sky-500 to-indigo-600 px-4 py-3 text-white">
    <div class="flex items-center gap-2.5">
      <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
        <i class="fa fa-filter" aria-hidden="true"></i>
      </span>
      <div>
        <h3 class="text-sm font-bold leading-tight">Filter Siswa</h3>
        <p class="text-[11px] leading-tight text-sky-100">Saring berdasarkan jurusan, tingkatan, kelas, atau pencarian</p>
      </div>
    </div>
  </div>
  <form method="get" action="<?php echo base_url(); ?>laporan_nilai" class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 lg:grid-cols-5">
    <div>
      <label class="mb-1 block text-xs font-medium text-slate-600">Jurusan</label>
      <?php echo cmb_dinamis('jurusan', 'tbl_jurusan', 'nama_jurusan', 'kd_jurusan', $jurusan_filter, "id='flt_jurusan' onChange='loadKelasRaport()'"); ?>
    </div>
    <div>
      <label class="mb-1 block text-xs font-medium text-slate-600">Tingkatan Kelas</label>
      <?php echo cmb_dinamis('tingkatan', 'tbl_tingkatan_kelas', 'nama_tingkatan', 'kd_tingkatan', $tingkatan_filter, "id='flt_tingkatan' onChange='loadKelasRaport()'"); ?>
    </div>
    <div>
      <label class="mb-1 block text-xs font-medium text-slate-600">Kelas</label>
      <div id="divKelas">
        <select id="slcKelas" name="kelas" class="form-control" >
          <option value="">-- Pilih Kelas --</option>
          <?php if (!empty($kelas_pilih)): ?>
            <option value="<?php echo $kelas['kd_kelas'] ?? ''; ?>" selected><?php echo $kelas['nama_kelas'] ?? ''; ?></option>
          <?php endif; ?>
        </select>
      </div>
    </div>
    <div>
      <label class="mb-1 block text-xs font-medium text-slate-600">Cari NIM / Nama</label>
      <input type="text" name="cari" value="<?php echo htmlspecialchars($cari); ?>" placeholder="NIM atau nama..."
             class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
    </div>
    <div class="flex items-end gap-2">
      <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-sky-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-sky-700 sm:w-auto">
        <i class="fa fa-search text-[11px]" aria-hidden="true"></i> Tampilkan
      </button>
      <a href="<?php echo base_url(); ?>laporan_nilai" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-200">
        <i class="fa fa-refresh text-[11px]" aria-hidden="true"></i> Reset
      </a>
    </div>
  </form>
</div>
<?php endif; ?>

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

<script type="text/javascript">
  function loadKelasRaport() {
    var jurusan = $("#flt_jurusan").val();
    var tingkatan = $("#flt_tingkatan").val();
    $("#divKelas").html('<select id="slcKelas" name="kelas" class="form-control"><option value="">Memuat...</option></select>');
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url(); ?>laporan_nilai/tampil_kelas',
      data: 'jurusan=' + jurusan + '&tingkatan=' + tingkatan,
      success: function (html) {
        $("#divKelas").html(html);
        $("#slcKelas").change(function () { this.form.submit(); });
      }
    });
  }
  $(document).ready(function () {
    var kp = <?php echo json_encode($kelas_pilih); ?>;
    $("#slcKelas").change(function () { this.form.submit(); });
    if (!kp && $("#flt_jurusan").val() && $("#flt_tingkatan").val()) loadKelasRaport();
  });
</script>