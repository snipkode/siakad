<div class="grid grid-cols-2 gap-3 xl:grid-cols-4">

  <div class="relative overflow-hidden rounded-2xl bg-sky-500 p-3.5 text-white shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-2xl font-bold leading-7"><?php echo $user['hasil']; ?></p>
        <p class="text-[11px] font-medium text-sky-100">Pengguna Sistem</p>
      </div>
      <i class="fa fa-id-badge text-xl text-sky-300/70"></i>
    </div>
    <a href="<?php echo site_url('user') ?>" class="mt-1 inline-flex items-center gap-0.5 text-[10px] font-semibold text-sky-100">
      Detail <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>

  <div class="relative overflow-hidden rounded-2xl bg-red-500 p-3.5 text-white shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-2xl font-bold leading-7"><?php echo $siswa['hasil']; ?></p>
        <p class="text-[11px] font-medium text-red-100">Siswa</p>
      </div>
      <i class="fa fa-users text-xl text-red-300/70"></i>
    </div>
    <a href="<?php echo site_url('siswa') ?>" class="mt-1 inline-flex items-center gap-0.5 text-[10px] font-semibold text-red-100">
      Detail <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>

  <div class="relative overflow-hidden rounded-2xl bg-emerald-500 p-3.5 text-white shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-2xl font-bold leading-7"><?php echo $guru['hasil']; ?></p>
        <p class="text-[11px] font-medium text-emerald-100">Guru</p>
      </div>
      <i class="fa fa-user-circle text-xl text-emerald-300/70"></i>
    </div>
    <a href="<?php echo site_url('guru') ?>" class="mt-1 inline-flex items-center gap-0.5 text-[10px] font-semibold text-emerald-100">
      Detail <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>

  <div class="relative overflow-hidden rounded-2xl bg-amber-500 p-3.5 text-white shadow-sm">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-2xl font-bold leading-7"><?php echo $ruangan['hasil']; ?></p>
        <p class="text-[11px] font-medium text-amber-100">Ruangan Kelas</p>
      </div>
      <i class="fa fa-building text-xl text-amber-300/70"></i>
    </div>
    <a href="<?php echo site_url('ruangan') ?>" class="mt-1 inline-flex items-center gap-0.5 text-[10px] font-semibold text-amber-100">
      Detail <i class="fa fa-arrow-circle-right"></i>
    </a>
  </div>

</div>