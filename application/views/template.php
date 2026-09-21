<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?php echo isset($page_title) ? $page_title : 'Dashboard'; ?> | SIAKAD</title>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/custom/css/app.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">

  <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">

<?php
  $nama_lengkap = (string) $this->session->userdata('nama_lengkap');
  $id_level_user = (int) $this->session->userdata('id_level_user');
  $active_segment = strtolower((string)$this->uri->segment(1));
  if ($active_segment === '' || $active_segment === 'dashboard' || $active_segment === 'index' || $active_segment === 'auth') {
      $active_segment = 'tampilan_utama';
  }

  function _short_label($n) {
      $n = preg_replace('/^(Data|Form) /', '', (string)$n);
      $map = array('Mata Pelajaran' => 'Mapel', 'Peserta Didik' => 'Peserta', 'Pengguna Sistem' => 'Pengguna',
                   'Tahun Akademik' => 'Tahun', 'Laporan Nilai' => 'Laporan', 'Tingkatan Kelas' => 'Tingkatan',
                   'Ruangan Kelas' => 'Ruangan', 'Jadwal Pelajaran' => 'Jadwal');
      return isset($map[$n]) ? $map[$n] : $n;
  }
  function _seg($link) { return strtolower(trim(explode('/', (string)$link)[0])); }

  $menus = array();
  if ($id_level_user > 0) {
      $sql_menu = "SELECT * FROM `tabel_menu` WHERE id IN(SELECT id_menu FROM tbl_user_rule WHERE id_level_user = $id_level_user) AND is_main_menu = 0";
      $main_menu = $this->db->query($sql_menu)->result();
      foreach ($main_menu as $main) {
          $m = array('id' => $main->id, 'nama' => $main->nama_menu, 'link' => $main->link, 'icon' => $main->icon, 'subs' => array());
          if ($main->link === '#') {
              $subs = $this->db->get_where('tabel_menu', array('is_main_menu' => $main->id));
              foreach ($subs->result() as $s) { $m['subs'][] = array('nama' => $s->nama_menu, 'link' => $s->link, 'icon' => $s->icon); }
          } elseif ($main->link !== '') {
              $m['subs'] = $m['subs']; /* tidak punya submenu */
          }
          $menus[] = $m;
      }
  }

  $page_title = 'Dashboard';
  foreach ($menus as $m) {
      if (_seg($m['link']) === $active_segment) { $page_title = $m['nama']; break; }
      foreach ($m['subs'] as $s) {
          if (_seg($s['link']) === $active_segment) { $page_title = $s['nama']; break 2; }
      }
  }

  $tab_home = array('id' => 0, 'nama' => 'Beranda', 'link' => 'tampilan_utama', 'icon' => 'fa fa-home', 'subs' => array());
  $tab_main = array_slice($menus, 0, 3);
  $tab_more = array_slice($menus, 3);
  $tabs = array_merge(array($tab_home), $tab_main);
  $need_more_tab = count($tab_more) > 0;
  if ($need_more_tab || count($tabs) >= 5) {
      $tab_cols = 'grid-cols-5';
  } elseif (count($tabs) === 4) {
      $tab_cols = 'grid-cols-4';
  } elseif (count($tabs) === 3) {
      $tab_cols = 'grid-cols-3';
  } else {
      $tab_cols = 'grid-cols-2';
  }
?>

<div class="lg:flex lg:h-screen lg:overflow-hidden">

  <!-- ================================================================
       DESKTOP SIDEBAR
  ================================================================= -->
  <aside class="hidden w-72 shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">

    <!-- Logo -->
    <a href="<?php echo site_url('tampilan_utama'); ?>" class="flex h-16 shrink-0 items-center gap-2.5 border-b border-slate-100 px-5">
      <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 font-bold text-white shadow-md shadow-sky-500/30">S</div>
      <div>
        <p class="text-sm font-bold leading-4 text-slate-800">SIAKAD</p>
        <p class="text-[11px] text-slate-400">Sistem Informasi Akademik</p>
      </div>
    </a>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto px-3 py-4">
      <?php
        $sidebar_group = array(
          'Data Induk'  => array('siswa', 'guru', 'siswa/siswa_aktif', 'walikelas'),
          'Akademik'    => array('jadwal', 'nilai', 'laporan_nilai'),
          'Data Master' => array('mapel', 'ruangan', 'tingkatan', 'jurusan', 'tahunakademik', 'kelas', 'kurikulum'),
          'Pengaturan'  => array('user', 'menu', 'pembayaran'),
        );

        // kelompokkan menu utama berdasarkan link
        $nav_groups = array();
        $master = null;
        foreach ($menus as $m) {
          if ($m['link'] === '#') { $master = $m; continue; }
          $grp = 'Lainnya';
          foreach ($sidebar_group as $label => $links) {
            if (in_array($m['link'], $links, true)) { $grp = $label; break; }
          }
          $nav_groups[$grp][] = $m;
        }
        // submenu induk (mis. Data Master) dirender flat pada kelompoknya
        if ($master !== null && count($master['subs']) > 0) {
          $nav_groups['Data Master'] = $master['subs'];
        }
        $nav_order = array_merge(array('Utama'), array_keys($sidebar_group), array('Lainnya'));
      ?>

      <p class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Utama</p>
      <ul class="space-y-1">
        <li>
          <a href="<?php echo site_url('tampilan_utama'); ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 <?php echo $active_segment === 'tampilan_utama' ? 'bg-sky-50 text-sky-700 font-semibold' : ''; ?>">
            <i class="fa fa-home w-5 text-center text-slate-400"></i>
            <span>Beranda</span>
          </a>
        </li>
      </ul>

      <?php foreach ($nav_order as $grp):
            if (empty($nav_groups[$grp])) continue; ?>
        <p class="mt-4 px-3 pb-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400"><?php echo $grp; ?></p>
        <ul class="space-y-1">
          <?php foreach ($nav_groups[$grp] as $item): ?>
            <li>
              <a href="<?php echo site_url($item['link']); ?>" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900 <?php echo _seg($item['link']) === $active_segment ? 'bg-sky-50 text-sky-700 font-semibold' : ''; ?>">
                <i class="<?php echo $item['icon']; ?> w-5 text-center text-slate-400"></i>
                <span><?php echo $item['nama']; ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endforeach; ?>
    </nav>

    <div class="border-t border-slate-100 p-3">
      <?php echo anchor('auth/logout', '<i class="fa fa-sign-out"></i>  Keluar', array('class'=>'flex w-full items-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-red-50 hover:text-red-600')); ?>
    </div>
  </aside>

  <!-- ================================================================
       CONTENT COLUMN
  ================================================================= -->
  <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">

    <!-- Topbar -->
    <header class="flex h-12 shrink-0 items-center justify-between border-b border-slate-200 bg-white/90 px-3.5 backdrop-blur lg:h-16 lg:px-6">
      <div class="flex items-center gap-2 lg:gap-3">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-indigo-600 text-sm font-bold text-white lg:hidden">S</div>
        <div>
          <h1 class="text-sm font-bold leading-5 text-slate-800 lg:text-lg"><?php echo $page_title; ?></h1>
          <p class="hidden text-[11px] text-slate-500 sm:block">Sistem Informasi Akademik</p>
        </div>
      </div>

      <div class="relative">
        <button id="user-menu-btn" class="flex items-center gap-2 rounded-full py-1 pl-1 pr-2 hover:bg-slate-100">
          <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-sm font-bold text-white">
            <?php echo strtoupper(substr($nama_lengkap, 0, 1)); ?>
          </div>
          <span class="hidden text-sm font-medium text-slate-700 md:block"><?php echo $nama_lengkap; ?></span>
          <i class="fa fa-angle-down text-xs text-slate-400"></i>
        </button>
        <div id="user-menu" class="absolute right-0 top-full z-40 mt-1 hidden w-52 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/10">
          <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-4 py-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-sky-500 to-indigo-600 text-sm font-bold text-white">
              <?php echo strtoupper(substr($nama_lengkap, 0, 1)); ?>
            </div>
            <div class="min-w-0">
              <p class="truncate text-sm font-semibold text-slate-800"><?php echo $nama_lengkap; ?></p>
              <p class="text-[11px] text-slate-500">Level: <?php echo $id_level_user; ?></p>
            </div>
          </div>
          <div class="p-2">
            <?php echo anchor('auth/logout', '<i class="fa fa-sign-out"></i>  Keluar', array('class'=>'flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-red-50 hover:text-red-600')); ?>
          </div>
        </div>
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1 overflow-y-auto p-2.5 pb-[5.5rem] lg:p-6 lg:pb-6">
      <?php echo $contents; ?>
    </main>

    <footer class="hidden shrink-0 border-t border-slate-200 bg-white px-4 py-3 text-center text-[11px] text-slate-400 lg:block">
      SIAKAD - Sistem Informasi Akademik &copy; <?php echo date('Y'); ?>
    </footer>
  </div>
</div>

<!-- ================================================================
     MOBILE BOTTOM TAB BAR  (iOS style)
================================================================= -->
<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-slate-200 bg-white/90 backdrop-blur-xl pb-safe lg:hidden">
  <div class="grid <?php echo $tab_cols; ?>">
    <?php foreach ($tabs as $t):
        $t_active = _seg($t['link']) === $active_segment;
        $has_subs = count($t['subs']) > 0;
        $label = ($t['id'] === 0) ? 'Beranda' : _short_label($t['nama']);
        if ($has_subs):
    ?>
      <button type="button" onclick="openSheet('sheet-tab-<?php echo $t['id']; ?>')"
              class="flex flex-col items-center gap-0 py-1.5 text-slate-500">
        <i class="<?php echo $t['icon']; ?> text-[17px]"></i>
        <span class="text-[9px] font-medium"><?php echo $label; ?></span>
      </button>
    <?php else: ?>
      <a href="<?php echo site_url($t['link']); ?>"
         class="flex flex-col items-center gap-0 py-1.5 <?php echo $t_active ? 'text-sky-600' : 'text-slate-500'; ?>">
        <i class="<?php echo $t['icon']; ?> text-[17px]"></i>
        <span class="text-[9px] font-medium"><?php echo $label; ?></span>
        <?php echo $t_active ? '<span class="h-1 w-1 rounded-full bg-sky-600"></span>' : '<span class="h-1"></span>'; ?>
      </a>
    <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($need_more_tab): ?>
      <button type="button" onclick="openSheet('sheet-more')"
              class="flex flex-col items-center gap-0 py-1.5 text-slate-500">
        <i class="fa fa-ellipsis-h text-[17px]"></i>
        <span class="text-[9px] font-medium">Lainnya</span>
      </button>
    <?php endif; ?>
  </div>
</nav>

<!-- ================================================================
     MOBILE SHEETS (bottom drawer)
================================================================= -->
<div id="sheet-backdrop" onclick="closeSheet()" class="fixed inset-0 z-50 hidden bg-black/40">
</div>

<?php foreach ($tabs as $t): if (count($t['subs']) > 0): ?>
  <div id="sheet-tab-<?php echo $t['id']; ?>" class="mobile-sheet fixed inset-x-0 bottom-0 z-50 translate-y-full transition-transform duration-300 lg:hidden">
    <div class="mx-auto mb-0.5 mt-1.5 h-1 w-10 rounded-full bg-slate-300"></div>
    <div class="max-h-[78vh] overflow-y-auto rounded-t-3xl bg-white px-2 pb-3 shadow-2xl">
      <p class="px-4 pb-1.5 pt-2 text-sm font-bold text-slate-700"><?php echo $t['nama']; ?></p>
      <?php if (isset($t['link']) && $t['link'] !== '#'): ?>
        <a href="<?php echo site_url($t['link']); ?>" onclick="closeSheet()" class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-slate-700 active:bg-slate-100">
          <i class="<?php echo $t['icon']; ?> w-6 text-center text-sky-500"></i>
          <span class="text-sm font-medium"><?php echo $t['nama']; ?></span>
          <i class="fa fa-chevron-right ml-auto text-xs text-slate-300"></i>
        </a>
        <div class="mx-4 my-1 h-px bg-slate-100"></div>
      <?php endif; ?>
      <?php foreach ($t['subs'] as $s): ?>
        <a href="<?php echo site_url($s['link']); ?>" onclick="closeSheet()" class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-slate-700 active:bg-slate-100">
          <i class="<?php echo $s['icon']; ?> w-6 text-center text-sky-500"></i>
          <span class="text-sm font-medium"><?php echo $s['nama']; ?></span>
          <i class="fa fa-chevron-right ml-auto text-xs text-slate-300"></i>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; endforeach; ?>

<?php if ($need_more_tab): ?>
  <div id="sheet-more" class="mobile-sheet fixed inset-x-0 bottom-0 z-50 translate-y-full transition-transform duration-300 lg:hidden">
    <div class="mx-auto mb-0.5 mt-1.5 h-1 w-10 rounded-full bg-slate-300"></div>
    <div class="max-h-[78vh] overflow-y-auto rounded-t-3xl bg-white px-2 pb-3 shadow-2xl">
      <p class="px-4 pb-1.5 pt-2 text-sm font-bold text-slate-700">Menu Lainnya</p>
      <?php foreach ($tab_more as $t): ?>
        <?php if (count($t['subs']) > 0): ?>
          <p class="px-4 pb-0.5 pt-1.5 text-[10px] font-semibold uppercase tracking-wider text-slate-400">
            <i class="<?php echo $t['icon']; ?> mr-1.5"></i><?php echo $t['nama']; ?>
          </p>
          <?php foreach ($t['subs'] as $s): ?>
            <a href="<?php echo site_url($s['link']); ?>" onclick="closeSheet()" class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-slate-700 active:bg-slate-100">
              <i class="<?php echo $s['icon']; ?> w-6 text-center text-slate-400"></i>
              <span class="text-sm font-medium"><?php echo $s['nama']; ?></span>
              <i class="fa fa-chevron-right ml-auto text-xs text-slate-300"></i>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <a href="<?php echo site_url($t['link']); ?>" onclick="closeSheet()" class="flex items-center gap-3.5 rounded-xl px-4 py-2.5 text-slate-700 active:bg-slate-100">
            <i class="<?php echo $t['icon']; ?> w-6 text-center text-slate-400"></i>
            <span class="text-sm font-medium"><?php echo $t['nama']; ?></span>
            <i class="fa fa-chevron-right ml-auto text-xs text-slate-300"></i>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<script>
(function () {
  var menuBtn = document.getElementById('user-menu-btn');
  var menuEl  = document.getElementById('user-menu');
  menuBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    menuEl.classList.toggle('hidden');
  });
  document.addEventListener('click', function () { menuEl.classList.add('hidden'); });

  document.querySelectorAll('.nav-sub-toggle').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var ul = document.querySelector(btn.getAttribute('data-sub'));
      ul.classList.toggle('hidden');
      btn.querySelector('.fa-angle-down').classList.toggle('rotate-180');
    });
  });

  window.openSheet = function (id) {
    var s = document.getElementById(id);
    s.classList.remove('hidden');
    requestAnimationFrame(function () { s.classList.remove('translate-y-full'); });
    document.getElementById('sheet-backdrop').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
  };
  window.closeSheet = function () {
    document.querySelectorAll('.mobile-sheet').forEach(function (s) {
      s.classList.add('translate-y-full');
      setTimeout(function () { s.classList.add('hidden'); }, 300);
    });
    document.getElementById('sheet-backdrop').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };
})();

/* Default DataTables: teks Indonesia + kosongkan label "Search:" */
$.extend($.fn.dataTable.defaults, {
  "language": {
    "search": "",
    "info": "_START_ sampai _END_ dari _TOTAL_ entri",
    "infoEmpty": "0 entri",
    "infoFiltered": "(difilter dari _MAX_ entri)",
    "zeroRecords": "Tidak ada data yang cocok dengan pencarian",
    "emptyTable": "Belum ada data",
    "paginate": { "first": "&laquo;", "last": "&raquo;", "next": "&rsaquo;", "previous": "&lsaquo;" }
  }
});

/* Geser input cari keluar dari area scroll tabel ke header kartu */
$(document).on('init.dt', function (e, settings) {
  var $t = $(settings.nTable);
  var $card = $t.closest('.rounded-2xl');
  if (!$card.length) return;
  var $wrap = $t.closest('.dataTables_wrapper');
  var $flt = $wrap.find('> div.dataTables_filter').first();
  if (!$flt.length || $card.find('.dt-searchbar').length) return;
  $('<' + 'div class="dt-searchbar"></' + 'div>').append($flt).appendTo($card.children().first());
});
</script>

</body>
</html>