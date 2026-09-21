<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | SIAKAD</title>

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/custom/css/app.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-sky-800 via-slate-900 to-slate-900 p-4 font-sans">

  <div class="w-full max-w-md">
    <!-- Brand -->
    <div class="mb-8 text-center">
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-sky-500 text-3xl font-bold text-white shadow-lg shadow-sky-500/30">S</div>
      <h1 class="text-2xl font-bold text-white">Sistem Informasi Akademik</h1>
      <p class="mt-1 text-sm text-slate-400">Silakan masuk untuk memulai sesi Anda</p>
    </div>

    <!-- Card -->
    <div class="rounded-2xl bg-white p-8 shadow-2xl">

      <?php
        if ($this->session->flashdata('gagal')) {
          echo '<div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">';
          echo '<i class="fa fa-exclamation-circle"></i> '.$this->session->flashdata('gagal');
          echo '</div>';
        }
      ?>

      <?php echo form_open('auth/check_login'); ?>

        <div class="mb-4">
          <label for="username" class="mb-1.5 block text-sm font-medium text-slate-700">Username</label>
          <div class="relative">
            <i class="fa fa-user absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="username" id="username" placeholder="Masukkan username"
                   class="w-full rounded-lg border border-slate-300 py-2.5 pl-9 pr-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          </div>
        </div>

        <div class="mb-6">
          <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
          <div class="relative">
            <i class="fa fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="password" name="password" id="password" placeholder="Masukkan password"
                   class="w-full rounded-lg border border-slate-300 py-2.5 pl-9 pr-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          </div>
        </div>

        <button type="submit" name="submit" class="w-full rounded-lg bg-sky-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
          Masuk
        </button>

      </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-500">
      SIAKAD &copy; <?php echo date('Y'); ?> - Sistem Informasi Akademik
    </p>
  </div>

</body>
</html>