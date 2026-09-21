<!-- Kartu statistik desktop -->
<div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">

  <div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-sky-200">
    <div class="flex flex-1 items-center justify-between gap-2 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-slate-500">Pengguna Sistem</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-slate-900"><?php echo $user['hasil']; ?></p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-sky-50 text-sky-600 transition group-hover:bg-sky-100">
        <i class="fa fa-id-badge text-lg"></i>
      </span>
    </div>
    <a href="<?php echo site_url('user') ?>" class="flex items-center justify-center gap-1.5 bg-sky-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-sky-700">
      Kelola Pengguna <i class="fa fa-arrow-right text-[10px]"></i>
    </a>
  </div>

  <div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-red-200">
    <div class="flex flex-1 items-center justify-between gap-2 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-slate-500">Siswa</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-slate-900"><?php echo $siswa['hasil']; ?></p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-red-600 transition group-hover:bg-red-100">
        <i class="fa fa-users text-lg"></i>
      </span>
    </div>
    <a href="<?php echo site_url('siswa') ?>" class="flex items-center justify-center gap-1.5 bg-red-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-red-700">
      Kelola Siswa <i class="fa fa-arrow-right text-[10px]"></i>
    </a>
  </div>

  <div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-emerald-200">
    <div class="flex flex-1 items-center justify-between gap-2 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-slate-500">Guru</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-slate-900"><?php echo $guru['hasil']; ?></p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
        <i class="fa fa-user-circle text-lg"></i>
      </span>
    </div>
    <a href="<?php echo site_url('guru') ?>" class="flex items-center justify-center gap-1.5 bg-emerald-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
      Kelola Guru <i class="fa fa-arrow-right text-[10px]"></i>
    </a>
  </div>

  <div class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-amber-200">
    <div class="flex flex-1 items-center justify-between gap-2 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-slate-500">Ruangan Kelas</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-slate-900"><?php echo $ruangan['hasil']; ?></p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
        <i class="fa fa-building text-lg"></i>
      </span>
    </div>
    <a href="<?php echo site_url('ruangan') ?>" class="flex items-center justify-center gap-1.5 bg-amber-600 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-amber-700">
      Kelola Ruangan <i class="fa fa-arrow-right text-[10px]"></i>
    </a>
  </div>

</div>

<!-- Statistik -->
<div class="mt-4 grid grid-cols-1 gap-3 sm:gap-4 lg:grid-cols-2">

  <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-3 flex items-center gap-2.5">
      <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600"><i class="fa fa-bar-chart"></i></span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Statistik Siswa per Tingkatan</h3>
        <p class="text-[11px] text-slate-500">Jumlah siswa pada tiap tingkatan kelas</p>
      </div>
    </div>
    <div class="relative h-64" id="wrap-chart-tingkatan">
      <canvas id="chartTingkatan" style="height: 16rem;"></canvas>
    </div>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-3 flex items-center gap-2.5">
      <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600"><i class="fa fa-pie-chart"></i></span>
      <div>
        <h3 class="text-sm font-bold text-slate-800">Statistik Siswa per Jurusan</h3>
        <p class="text-[11px] text-slate-500">Sebaran siswa berdasar jurusan kelas</p>
      </div>
    </div>
    <div class="relative h-64" id="wrap-chart-jurusan">
      <canvas id="chartJurusan" style="height: 16rem;"></canvas>
    </div>
  </div>

</div>

<script src="<?php echo base_url(); ?>assets/bower_components/chart.js/Chart.min.js"></script>
<script>
(function () {
  var dataTingkatan = <?php echo json_encode($chart_tingkatan); ?>;
  var dataJurusan   = <?php echo json_encode($chart_jurusan); ?>;

  if (dataTingkatan.length === 0) {
    document.getElementById('wrap-chart-tingkatan').innerHTML =
      '<p class="flex h-full items-center justify-center text-sm text-slate-400">Belum ada data siswa.</p>';
  } else {
    var ctxT = document.getElementById('chartTingkatan').getContext('2d');
    new Chart(ctxT).Bar({
      labels: dataTingkatan.map(function (d) { return d.label; }),
      datasets: [{
        label: 'Jumlah Siswa',
        fillColor: 'rgba(14,165,233,0.85)',
        highlightFill: 'rgba(14,165,233,1)',
        data: dataTingkatan.map(function (d) { return +d.jumlah; })
      }]
    }, {
      scaleBeginAtZero: true,
      scaleFontFamily: "'Inter','Helvetica','Arial',sans-serif",
      scaleFontSize: 11,
      scaleFontColor: '#64748b',
      scaleGridLineColor: 'rgba(148,163,184,0.2)',
      barShowStroke: false
    });
  }

  if (dataJurusan.length === 0) {
    document.getElementById('wrap-chart-jurusan').innerHTML =
      '<p class="flex h-full items-center justify-center text-sm text-slate-400">Belum ada data siswa.</p>';
  } else {
    var palet = [
      { c: '#0ea5e9', h: '#38bdf8' },
      { c: '#6366f1', h: '#818cf8' },
      { c: '#f59e0b', h: '#fbbf24' },
      { c: '#10b981', h: '#34d399' },
      { c: '#f43f5e', h: '#fb7185' }
    ];
    var ctxJ = document.getElementById('chartJurusan').getContext('2d');
    new Chart(ctxJ).Doughnut(dataJurusan.map(function (d, i) {
      return { value: +d.jumlah, color: palet[i % palet.length].c, highlight: palet[i % palet.length].h, label: d.label };
    }), {
      segmentShowStroke: false,
      percentageInnerCutout: 68
    });
    var leg = dataJurusan.map(function (d) { return d.label + ': ' + d.jumlah; }).join('  |  ');
    document.getElementById('wrap-chart-jurusan').insertAdjacentHTML('beforeend',
      '<p class="mt-3 text-center text-xs font-medium text-slate-600">' + leg + '</p>');
  }
})();
</script>