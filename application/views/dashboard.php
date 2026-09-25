<!-- Kartu statistik desktop -->
<div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">

  <div class="group flex flex-col overflow-hidden rounded-2xl shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-sky-200">
    <?php echo anchor('user', '<div class="flex flex-1 items-center justify-between gap-2 bg-gradient-to-br from-sky-500 to-indigo-600 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-sky-100">Pengguna Sistem</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-white">'.$user['hasil'].'</p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white"><i class="fa fa-id-badge text-lg"></i></span>
    </div>
    <div class="flex items-center justify-center gap-1.5 bg-white px-4 py-2.5 text-xs font-semibold text-sky-700 transition group-hover:bg-sky-50">
      Kelola Pengguna <i class="fa fa-arrow-right text-[10px]"></i>
    </div>', array('class' => 'flex h-full flex-col')); ?>
  </div>

  <div class="group flex flex-col overflow-hidden rounded-2xl shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-red-200">
    <?php echo anchor('siswa', '<div class="flex flex-1 items-center justify-between gap-2 bg-gradient-to-br from-red-500 to-rose-600 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-red-100">'.$label_peserta.'</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-white">'.$siswa['hasil'].'</p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white"><i class="fa fa-users text-lg"></i></span>
    </div>
    <div class="flex items-center justify-center gap-1.5 bg-white px-4 py-2.5 text-xs font-semibold text-red-600 transition group-hover:bg-red-50">
      Kelola '.$label_peserta.' <i class="fa fa-arrow-right text-[10px]"></i>
    </div>', array('class' => 'flex h-full flex-col')); ?>
  </div>

  <div class="group flex flex-col overflow-hidden rounded-2xl shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-emerald-200">
    <?php echo anchor('guru', '<div class="flex flex-1 items-center justify-between gap-2 bg-gradient-to-br from-emerald-500 to-teal-600 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-emerald-100">'.$label_staf.'</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-white">'.$guru['hasil'].'</p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white"><i class="fa fa-user-circle text-lg"></i></span>
    </div>
    <div class="flex items-center justify-center gap-1.5 bg-white px-4 py-2.5 text-xs font-semibold text-emerald-600 transition group-hover:bg-emerald-50">
      Kelola '.$label_staf.' <i class="fa fa-arrow-right text-[10px]"></i>
    </div>', array('class' => 'flex h-full flex-col')); ?>
  </div>

  <div class="group flex flex-col overflow-hidden rounded-2xl shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-lg hover:ring-amber-200">
    <?php echo anchor('ruangan', '<div class="flex flex-1 items-center justify-between gap-2 bg-gradient-to-br from-amber-400 to-orange-500 px-4 pb-3 pt-4 sm:px-5">
      <div>
        <p class="text-xs font-semibold text-amber-100">Ruangan Kelas</p>
        <p class="mt-1 text-3xl font-extrabold leading-8 text-white">'.$ruangan['hasil'].'</p>
      </div>
      <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white"><i class="fa fa-building text-lg"></i></span>
    </div>
    <div class="flex items-center justify-center gap-1.5 bg-white px-4 py-2.5 text-xs font-semibold text-amber-600 transition group-hover:bg-amber-50">
      Kelola Ruangan <i class="fa fa-arrow-right text-[10px]"></i>
    </div>', array('class' => 'flex h-full flex-col')); ?>
  </div>

</div>

<!-- Statistik -->
<div class="mt-4 grid grid-cols-1 gap-2.5 sm:gap-4 lg:grid-cols-2">

  <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-5">
    <div class="mb-2 flex items-center gap-2 sm:mb-3 sm:gap-2.5">
      <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-sky-50 text-sm text-sky-600 sm:h-9 sm:w-9 sm:rounded-xl sm:text-base"><i class="fa fa-bar-chart"></i></span>
      <div class="min-w-0">
        <h3 class="truncate text-[13px] font-bold text-slate-800 sm:text-sm"><?php echo $chart1['title']; ?></h3>
        <p class="mt-0.5 truncate text-[10px] text-slate-500 sm:text-[11px]"><?php echo $chart1['sub']; ?></p>
      </div>
    </div>
    <div class="relative h-52 sm:h-64" id="wrap-chart-1">
      <canvas id="chart1" class="h-52 w-full sm:h-64"></canvas>
    </div>
  </div>

  <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-5">
    <div class="mb-2 flex items-center gap-2 sm:mb-3 sm:gap-2.5">
      <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm text-indigo-600 sm:h-9 sm:w-9 sm:rounded-xl sm:text-base"><i class="fa fa-pie-chart"></i></span>
      <div class="min-w-0">
        <h3 class="truncate text-[13px] font-bold text-slate-800 sm:text-sm"><?php echo $chart2['title']; ?></h3>
        <p class="mt-0.5 truncate text-[10px] text-slate-500 sm:text-[11px]"><?php echo $chart2['sub']; ?></p>
      </div>
    </div>
    <div class="relative h-52 sm:h-64" id="wrap-chart-2">
      <canvas id="chart2" class="h-52 w-full sm:h-64"></canvas>
    </div>
  </div>

</div>

<script src="<?php echo base_url(); ?>assets/bower_components/chart.js/Chart.min.js"></script>
<script>
(function () {
  var data1 = <?php echo json_encode($chart1['data']); ?>;
  var data2 = <?php echo json_encode($chart2['data']); ?>;

  if (data1.length === 0) {
    document.getElementById('wrap-chart-1').innerHTML =
      '<p class="flex h-full items-center justify-center text-sm text-slate-400">Belum ada data <?php echo strtolower($label_peserta); ?>.</p>';
  } else {
    var ctx1 = document.getElementById('chart1').getContext('2d');
    new Chart(ctx1).Bar({
      labels: data1.map(function (d) { return d.label; }),
      datasets: [{
        label: '<?php echo $chart1['dataset_label']; ?>',
        fillColor: 'rgba(14,165,233,0.85)',
        highlightFill: 'rgba(14,165,233,1)',
        data: data1.map(function (d) { return +d.jumlah; })
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

  if (data2.length === 0) {
    document.getElementById('wrap-chart-2').innerHTML =
      '<p class="flex h-full items-center justify-center text-sm text-slate-400">Belum ada data <?php echo strtolower($label_peserta); ?>.</p>';
  } else {
    var palet = [
      { c: '#0ea5e9', h: '#38bdf8' },
      { c: '#6366f1', h: '#818cf8' },
      { c: '#f59e0b', h: '#fbbf24' },
      { c: '#10b981', h: '#34d399' },
      { c: '#f43f5e', h: '#fb7185' }
    ];
    var ctx2 = document.getElementById('chart2').getContext('2d');
    new Chart(ctx2).Doughnut(data2.map(function (d, i) {
      return { value: +d.jumlah, color: palet[i % palet.length].c, highlight: palet[i % palet.length].h, label: d.label };
    }), {
      segmentShowStroke: false,
      percentageInnerCutout: 68
    });
    var leg = data2.map(function (d) { return d.label + ': ' + d.jumlah; }).join('  |  ');
    document.getElementById('wrap-chart-2').insertAdjacentHTML('beforeend',
      '<p class="mt-3 text-center text-xs font-medium text-slate-600">' + leg + '</p>');
  }
})();
</script>