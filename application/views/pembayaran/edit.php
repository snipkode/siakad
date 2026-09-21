<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Edit Pembayaran</h3>

  <?php echo form_open('pembayaran/edit', 'role="form"'); ?>

    <input type="hidden" name="id_pembayaran" value="<?php echo $pembayaran['id_pembayaran']; ?>">

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Siswa</label>
        <input type="text"
               value="<?php $nama_siswa = function($nim) use (&$siswa) { foreach ($siswa as $s) { if ($s->nim == $nim) return $s->nama; } return $nim; }; echo $pembayaran['nim'] . ' - ' . $nama_siswa($pembayaran['nim']); ?>"
               readonly
               class="w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-sm text-slate-500">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tahun Akademik</label>
        <select name="id_tahun_akademik" required
                class="w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
          <?php foreach ($tahun as $t): ?>
            <option value="<?php echo $t->id_tahun_akademik; ?>" <?php echo ($t->id_tahun_akademik == $pembayaran['id_tahun_akademik']) ? 'selected' : ''; ?>><?php echo $t->tahun_akademik; ?> (<?php echo $t->semester; ?>)<?php echo ($t->is_aktif == 'Y') ? ' - Aktif' : ''; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jenis Pembayaran</label>
        <input type="text" name="jenis_bayar" list="jenis-list" value="<?php echo $pembayaran['jenis_bayar']; ?>" required
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
        <datalist id="jenis-list">
          <option value="Pendaftaran PSB">
          <option value="SPP Bulanan">
          <option value="Ujian Akhir">
          <option value="OSIS">
          <option value="Seragam">
          <option value="Kegiatan Sekolah">
        </datalist>
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Jumlah (Rp)</label>
        <input type="number" name="jumlah" min="0" step="500" value="<?php echo $pembayaran['jumlah']; ?>" required
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
      </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tanggal Bayar</label>
        <input type="date" name="tanggal_bayar" value="<?php echo $pembayaran['tanggal_bayar']; ?>" required
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Keterangan</label>
        <input type="text" name="keterangan" value="<?php echo $pembayaran['keterangan']; ?>"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/30">
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Simpan</button>
      <?php echo anchor('pembayaran', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>