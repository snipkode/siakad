<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Edit Tahun Akademik</h3>

  <?php echo form_open('tahunakademik/edit', 'role="form"'); ?>
  <?php echo form_hidden('id_tahunakademik', $tahunakademik['id_tahun_akademik']); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Tahun Akademik</label>
        <input type="text" value="<?php echo $tahunakademik['tahun_akademik']; ?>" name="tahun_akademik" placeholder="Masukkan Tahun Akademik"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Semester</label>
        <?php
          echo form_dropdown('semester', array('Pilih Semester', 'ganjil' => 'Ganjil', 'genap' => 'Genap'), ($tahunakademik['is_aktif'] == 'Y') ? $tahunakademik['semester'] : NULL, "class='w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30' ".($tahunakademik['is_aktif'] == 'Y' ? '' : "disabled='disabled'"));
        ?>
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('tahunakademik', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>