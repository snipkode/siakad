<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Masuk | SIAKAD</title>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/custom/css/app.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-950 p-4 font-sans antialiased">

  <!-- ===== Latar dekoratif: blob gradient + grid halus ===== -->
  <div class="pointer-events-none absolute inset-0">
    <div class="absolute -left-40 -top-40 h-[28rem] w-[28rem] rounded-full bg-sky-500/40 blur-3xl"></div>
    <div class="absolute -bottom-48 -right-40 h-[30rem] w-[30rem] rounded-full bg-indigo-600/40 blur-3xl"></div>
    <div class="absolute left-1/2 top-1/2 h-[22rem] w-[22rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-cyan-500/20 blur-3xl"></div>
    <div class="absolute inset-0 [background-image:radial-gradient(circle_at_1px_1px,rgba(148,163,184,.12)_1px,transparent_0)] [background-size:28px_28px]"></div>
  </div>

  <div class="relative z-10 w-full max-w-5xl">
    <div class="overflow-hidden rounded-3xl bg-white shadow-2xl shadow-black/40 lg:grid lg:grid-cols-2">

      <!-- ===== Panel branding (desktop) ===== -->
      <div class="relative hidden overflow-hidden bg-gradient-to-br from-sky-700 via-sky-600 to-indigo-700 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div class="pointer-events-none absolute inset-0">
          <div class="absolute -right-16 top-10 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
          <div class="absolute -bottom-20 -left-10 h-64 w-64 rounded-full bg-indigo-400/20 blur-2xl"></div>
        </div>

        <div class="relative">
          <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-2xl font-bold shadow-lg backdrop-blur">S</div>
            <div>
              <p class="text-lg font-bold leading-tight">SIAKAD</p>
              <p class="text-xs text-sky-200">Sistem Informasi Akademik</p>
            </div>
          </div>

          <h1 class="mt-12 text-3xl font-bold leading-snug">
            Kelola akademik
            <br>
            lebih <span class="text-cyan-300">mudah</span> & <span class="text-cyan-300">modern</span>
          </h1>
          <p class="mt-3 max-w-sm text-sm leading-relaxed text-sky-100">
            Satu platform untuk jadwal pelajaran, absensi, dan nilai siswa — tersimpan rapi dan dapat diakses kapan saja.
          </p>

          <ul class="mt-10 space-y-5">
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/15 text-sm backdrop-blur"><i class="fa fa-calendar-check-o"></i></span>
              <div>
                <p class="text-sm font-semibold">Jadwal & absensi</p>
                <p class="text-xs text-sky-200">Pantau kehadiran dan jadwal secara real-time.</p>
              </div>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/15 text-sm backdrop-blur"><i class="fa fa-bar-chart"></i></span>
              <div>
                <p class="text-sm font-semibold">Nilai terpusat</p>
                <p class="text-xs text-sky-200">Seluruh nilai tersimpan rapi dalam satu tempat.</p>
              </div>
            </li>
            <li class="flex items-start gap-3">
              <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-white/15 text-sm backdrop-blur"><i class="fa fa-shield"></i></span>
              <div>
                <p class="text-sm font-semibold">Aman & terpercaya</p>
                <p class="text-xs text-sky-200">Hak akses sesuai peran: admin, guru, dan siswa.</p>
              </div>
            </li>
          </ul>
        </div>

        <p class="relative mt-10 text-xs text-sky-200">&copy; <?php echo date('Y'); ?> SIAKAD · Sistem Informasi Akademik</p>
      </div>

      <!-- ===== Panel form ===== -->
      <div class="p-8 sm:p-12">
        <div class="mb-8 lg:hidden">
          <div class="mb-4 flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-600 to-indigo-600 text-2xl font-bold text-white shadow-lg shadow-sky-600/30">S</div>
            <div>
              <p class="text-lg font-bold leading-tight text-slate-900">SIAKAD</p>
              <p class="text-xs text-slate-500">Sistem Informasi Akademik</p>
            </div>
          </div>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900">Selamat datang kembali</h2>
          <p class="mt-1 text-sm text-slate-500">Silakan masuk untuk memulai sesi Anda</p>
        </div>

        <?php
          $pesanGagal = $this->session->flashdata('gagal');
          if ($pesanGagal) {
            echo '<div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">';
            echo '<i class="fa fa-exclamation-circle mt-0.5 text-red-500"></i>';
            echo '<span>'.htmlspecialchars($pesanGagal, ENT_QUOTES, 'UTF-8').'</span>';
            echo '</div>';
          }
        ?>

        <?php echo form_open('auth/check_login', 'class="space-y-5"'); ?>

          <div>
            <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
            <div class="group relative">
              <i class="fa fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-sky-600"></i>
              <input type="text" name="username" id="username" placeholder="Masukkan username" autocomplete="username" required autofocus
                     class="w-full rounded-xl border border-slate-300 py-3 pl-10 pr-4 text-sm text-slate-800 placeholder:text-slate-400 transition-all focus:border-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-500/20">
            </div>
          </div>

          <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
            <div class="group relative">
              <i class="fa fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-sky-600"></i>
              <input type="password" name="password" id="password" placeholder="Masukkan password" autocomplete="current-password" required
                     class="w-full rounded-xl border border-slate-300 py-3 pl-10 pr-12 text-sm text-slate-800 placeholder:text-slate-400 transition-all focus:border-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-500/20">
              <button type="button" id="togglePassword" aria-label="Tampilkan / sembunyikan password"
                      class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 transition-colors hover:text-slate-600">
                <i class="fa fa-eye text-base"></i>
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between pt-1">
            <label class="flex cursor-pointer select-none items-center gap-2 text-sm text-slate-600">
              <input type="checkbox" name="remember" class="h-4 w-4 cursor-pointer rounded border-slate-300 text-sky-600 accent-sky-600">
              Ingat saya
            </label>
            <a href="#" class="text-sm font-medium text-sky-600 transition-colors hover:text-sky-700 hover:underline">Lupa password?</a>
          </div>

          <button type="submit" name="submit" id="btnSubmit"
                  class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/30 transition-all hover:from-sky-700 hover:to-indigo-700 hover:shadow-sky-600/40 focus:outline-none focus:ring-4 focus:ring-sky-500/30 active:scale-[.98]">
            <span class="btn-label">
              Masuk <i class="fa fa-arrow-right ml-1"></i>
            </span>
            <span class="btn-loading hidden items-center gap-2">
              <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              Memproses...
            </span>
          </button>

          <div class="flex items-center gap-3 pt-2">
            <div class="h-px flex-1 bg-slate-200"></div>
            <span class="text-xs uppercase tracking-widest text-slate-400">SIAKAD</span>
            <div class="h-px flex-1 bg-slate-200"></div>
          </div>

          <p class="text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="#" class="font-medium text-sky-600 transition-colors hover:text-sky-700 hover:underline">Hubungi admin</a>
          </p>

        <?php echo form_close(); ?>
      </div>
    </div>
  </div>

  <script>
    (function () {
      var toggle = document.getElementById('togglePassword');
      var pwd = document.getElementById('password');
      var icon = toggle.querySelector('i');

      toggle.addEventListener('click', function () {
        var showing = pwd.type === 'text';
        pwd.type = showing ? 'password' : 'text';
        icon.className = showing ? 'fa fa-eye text-base' : 'fa fa-eye-slash text-base';
        toggle.classList.toggle('text-sky-600', !showing);
      });

      var btn = document.getElementById('btnSubmit');
      var submitting = false;
      document.querySelector('form').addEventListener('submit', function () {
        if (submitting) return;
        submitting = true;
        btn.querySelector('.btn-label').classList.add('hidden');
        btn.querySelector('.btn-loading').classList.remove('hidden');
        btn.querySelector('.btn-loading').classList.add('inline-flex');
      });
    })();
  </script>
</body>
</html>