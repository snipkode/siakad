<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="theme-color" content="#0a0e27">
  <title>Masuk | SIAKAD</title>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/custom/css/app.css">

  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      -webkit-tap-highlight-color: transparent;
      overflow-x: clip;
    }
    /* Cegah zoom otomatis di iOS saat fokus input (min 16px) */
    input { font-size: 16px; }
    /* Autofill Safari: tetap putih & teks terbaca */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
      -webkit-box-shadow: 0 0 0 1000px #fff inset;
      -webkit-text-fill-color: #1e293b;
      caret-color: #1e293b;
      transition: background-color 9999s ease-in-out 0s;
    }
  </style>
</head>
<body class="relative flex min-h-[100vh] flex-col bg-[#0a0e27] font-sans antialiased"
      style="background:
        radial-gradient(60rem circle at 15% -10%, rgba(26,115,232,.55) 0%, rgba(26,115,232,0) 45%),
        radial-gradient(50rem circle at 110% 115%, rgba(108,60,224,.5) 0%, rgba(108,60,224,0) 45%),
        radial-gradient(40rem circle at 85% -15%, rgba(108,60,224,.35) 0%, transparent 55%),
        linear-gradient(180deg, #141a3d 0%, #0a0e27 55%, #070a1c 100%);">

  <!-- ===== Latar: blob gradient biru-ungu ===== -->
  <div class="pointer-events-none absolute inset-0 overflow-hidden">
    <div class="absolute -left-16 -top-20 h-72 w-72 rounded-full bg-[#1a73e8]/40 blur-3xl"></div>
    <div class="absolute -bottom-28 -right-16 h-80 w-80 rounded-full bg-[#6c3ce0]/40 blur-3xl"></div>
    <div class="absolute left-1/2 top-1/2 h-72 w-72 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#1a73e8]/10 blur-3xl"></div>
  </div>

  <!-- ===== Konten: penuh tinggi, branding di atas, footer menempel dasar, tanpa space kosong ===== -->
  <div class="relative z-10 mx-auto flex w-full max-w-md flex-1 flex-col px-4"
       style="padding-top: calc(env(safe-area-inset-top) + 1.25rem); padding-bottom: env(safe-area-inset-bottom);">

    <!-- ===== Branding kompak (max ~120px) ===== -->
    <div class="flex items-center gap-2.5 py-4">
      <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#1a73e8] to-[#6c3ce0] text-lg font-bold text-white shadow-lg shadow-[#6c3ce0]/30">S</div>
      <div>
        <p class="text-[15px] font-bold leading-tight text-white">SIAKAD</p>
        <p class="text-[11px] text-slate-400">Sistem Informasi Akademik</p>
      </div>
    </div>

    <!-- ===== Blok form: isi tinggi area tengah (proporsional) ===== -->
    <div class="flex flex-1 flex-col justify-center py-6">

    <!-- ===== Heading ===== -->
    <div class="mb-6 mt-1">
      <h1 class="text-[22px] font-bold tracking-tight text-white">Masuk</h1>
      <p class="mt-1 text-[13px] text-slate-400">Silakan masuk untuk memulai sesi Anda</p>
    </div>

    <?php
      $pesanGagal = $this->session->flashdata('gagal');
      if ($pesanGagal) {
        echo '<div class="mb-4 flex items-start gap-2 rounded-[12px] border border-red-500/30 bg-red-500/10 px-3.5 py-2.5 text-[13px] text-red-300">';
        echo '<svg class="mt-0.5 h-4 w-4 shrink-0 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
        echo '<span>'.htmlspecialchars($pesanGagal, ENT_QUOTES, 'UTF-8').'</span>';
        echo '</div>';
      }
    ?>

    <?php echo form_open('auth/check_login', 'class="space-y-3"'); ?>

      <!-- ===== Username ===== -->
      <div>
        <label for="username" class="mb-1 block text-[13px] font-medium text-slate-300">Username</label>
        <div class="relative">
          <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
          </svg>
          <input type="text" name="username" id="username" placeholder="Masukkan username" autocomplete="username" autocapitalize="none" autocorrect="off" spellcheck="false" enterkeyhint="next" required autofocus
                 class="h-11 w-full rounded-[12px] border border-transparent bg-white pl-10 pr-4 text-base text-slate-800 placeholder:text-slate-400 transition-all focus:border-[#1a73e8] focus:outline-none focus:ring-4 focus:ring-[#1a73e8]/15">
        </div>
      </div>

      <!-- ===== Password ===== -->
      <div>
        <label for="password" class="mb-1 block text-[13px] font-medium text-slate-300">Password</label>
        <div class="relative">
          <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="11" rx="2"></rect>
            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
          </svg>
          <input type="password" name="password" id="password" placeholder="Masukkan password" autocomplete="current-password" enterkeyhint="go" required
                 class="h-11 w-full rounded-[12px] border border-transparent bg-white pl-10 pr-12 text-base text-slate-800 placeholder:text-slate-400 transition-all focus:border-[#1a73e8] focus:outline-none focus:ring-4 focus:ring-[#1a73e8]/15">
          <button type="button" id="togglePassword" aria-label="Tampilkan / sembunyikan password"
                  class="absolute right-0 top-1/2 flex h-11 w-12 -translate-y-1/2 items-center justify-center rounded-r-[12px] text-slate-400 transition-colors hover:text-slate-600">
            <svg id="icn-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4">
              <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
            <svg id="icn-eye-off" class="hidden h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 3l18 18"></path>
              <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path>
              <path d="M9.9 4.2A10.9 10.9 0 0 1 12 4c6.5 0 10 8 10 8a17.4 17.4 0 0 1-3.3 4.3"></path>
              <path d="M6.6 6.6A16.6 16.6 0 0 0 2 12s3.5 8 10 8a9.7 9.7 0 0 0 4-1.1"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- ===== Ingat saya / Lupa password ===== -->
      <div class="flex items-center justify-between pt-1">
        <label class="flex h-11 cursor-pointer select-none items-center gap-2 text-[13px] text-slate-300">
          <input type="checkbox" name="remember" class="h-4 w-4 cursor-pointer rounded accent-[#1a73e8]">
          Ingat saya
        </label>
        <a href="#" class="flex h-11 items-center text-[13px] font-medium text-[#8ab4ff]">Lupa password?</a>
      </div>

      <!-- ===== Tombol Masuk ===== -->
      <button type="submit" name="submit" id="btnSubmit"
              class="flex h-11 w-full items-center justify-center gap-2 rounded-[12px] bg-gradient-to-r from-[#1a73e8] to-[#6c3ce0] text-[15px] font-semibold text-white shadow-lg shadow-[#1a73e8]/25 transition-all hover:brightness-110 focus:outline-none focus:ring-4 focus:ring-[#6c3ce0]/30 active:scale-[.98]">
        <span class="btn-label inline-flex items-center gap-2">
          Masuk
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
        </span>
        <span class="btn-loading hidden items-center gap-2">
          <svg class="h-4 w-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          Memproses...
        </span>
      </button>

      <!-- ===== Separator ===== -->
      <div class="flex items-center gap-3 pt-1">
        <div class="h-px flex-1 bg-white/10"></div>
        <span class="text-[11px] font-medium uppercase tracking-widest text-slate-500">atau</span>
        <div class="h-px flex-1 bg-white/10"></div>
      </div>

      <!-- ===== Link sekunder (kompak) ===== -->
      <p class="pb-1 text-center text-[13px] text-slate-400">
        Belum punya akun?
        <a href="#" class="font-medium text-[#8ab4ff]">Hubungi admin</a>
      </p>

    <?php echo form_close(); ?>
    </div>

    <!-- ===== Footer ===== -->
    <p class="text-center text-[10px] tracking-wide text-slate-600">&copy; <?php echo date('Y'); ?> SIAKAD &middot; Sistem Informasi Akademik</p>
  </div>

  <script>
    (function () {
      var toggle = document.getElementById('togglePassword');
      var pwd = document.getElementById('password');
      var icnEye = document.getElementById('icn-eye');
      var icnEyeOff = document.getElementById('icn-eye-off');

      toggle.addEventListener('click', function () {
        var showing = pwd.type === 'text';
        pwd.type = showing ? 'password' : 'text';
        icnEye.classList.toggle('hidden', showing);
        icnEyeOff.classList.toggle('hidden', !showing);
        toggle.classList.toggle('text-sky-600', showing);
      });

      var btn = document.getElementById('btnSubmit');
      var submitting = false;
      document.querySelector('form').addEventListener('submit', function () {
        if (submitting) return;
        submitting = true;
        btn.disabled = true;
        btn.querySelector('.btn-label').classList.add('hidden');
        btn.querySelector('.btn-loading').classList.remove('hidden');
        btn.querySelector('.btn-loading').classList.add('inline-flex');
      });
    })();
  </script>
</body>
</html>