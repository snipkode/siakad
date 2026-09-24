<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="mx-auto max-w-3xl space-y-5">
  <?php if ($msg = $this->session->flashdata('msg_pengaturan')): ?>
    <div class="rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm font-medium text-sky-800"><?php echo $msg; ?></div>
  <?php endif; ?>

  <!-- Ganti Mode -->
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-4">
      <h2 class="text-base font-bold text-slate-800">Mode Instansi</h2>
      <p class="text-xs text-slate-500">Pilih KAMPUS, SMA, SMP, SD, atau TK. Label, menu, struktur & laporan menyesuaikan otomatis.</p>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
      <?php foreach ($modes as $m):
        $aktif = ($m['kd_mode'] === $mode_aktif); ?>
        <button type="button" onclick="pilihMode('<?php echo $m['kd_mode']; ?>')"
          class="rounded-2xl border p-4 text-left transition <?php echo $aktif
            ? 'border-sky-500 bg-sky-50 shadow-sm ring-2 ring-sky-200'
            : 'border-slate-200 hover:border-sky-300 hover:bg-slate-50'; ?>">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wide <?php echo $aktif ? 'text-sky-700' : 'text-slate-500'; ?>"><?php echo $m['kd_mode']; ?></span>
            <?php if ($aktif): ?><i class="fa fa-check text-sm text-sky-600"></i><?php endif; ?>
          </div>
          <p class="mt-1.5 text-sm font-semibold text-slate-800"><?php echo $m['nama_mode']; ?></p>
          <p class="mt-0.5 text-[11px] leading-snug text-slate-500"><?php echo $m['label_peserta']; ?> · <?php echo $m['label_staf']; ?></p>
        </button>
      <?php endforeach; ?>
    </div>

    <form method="post" action="<?php echo site_url('pengaturan/ganti_mode'); ?>" id="form-ganti-mode">
      <input type="hidden" name="mode_aktif" id="inp-mode-aktif" value="">
      <p class="mt-4 text-[11px] text-slate-400">Data tiap mode disimpan terpisah (kolom kd_mode) sehingga berpindah mode tidak menghapus data lain.</p>
    </form>
  </div>

  <!-- Info mode aktif -->
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-sm font-bold text-slate-800">Kosa Kata Mode Aktif: <span class="text-sky-600"><?php echo $mode_aktif; ?></span></h3>
    <?php $mr = get_instance()->meta->mode_row(); ?>
    <div class="mt-3 grid grid-cols-2 gap-2 text-xs sm:grid-cols-3">
      <?php foreach (array('label_instansi','label_kepala','label_staf','label_peserta','label_nomor_induk','label_rombongan','label_mata_ajar','label_laporan','label_tahun','sistem_nilai') as $k): ?>
        <div class="rounded-xl bg-slate-50 px-3 py-2">
          <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400"><?php echo $k; ?></p>
          <p class="font-medium text-slate-700"><?php echo isset($mr[$k]) ? $mr[$k] : ''; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- KKM -->
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-sm font-bold text-slate-800">KKM Default</h3>
    <p class="text-xs text-slate-500">Dipakai rapor SMA/SMP/SD (bila mode menyetel punya_kkm).</p>
    <form method="post" action="<?php echo site_url('pengaturan/atur_kkm'); ?>" class="mt-3 flex items-end gap-2">
      <div>
        <input type="number" name="kkm" min="0" max="100" value="<?php echo (int) $kkm; ?>" class="w-28 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100">
      </div>
      <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
    </form>
  </div>

  <!-- Identitas instansi -->
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <h3 class="text-sm font-bold text-slate-800">Identitas Instansi</h3>
    <p class="text-xs text-slate-500">Kelola<span class="mx-1">:</span>
      <?php echo anchor('identitas', 'Halaman Identitas', array('class' => 'font-semibold text-sky-600 hover:underline')); ?>
    </p>
  </div>
</div>

<script>
  function pilihMode(kd) {
    document.getElementById('inp-mode-aktif').value = kd;
    document.getElementById('form-ganti-mode').submit();
  }
</script>