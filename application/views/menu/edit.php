<div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-8">
  <h3 class="mb-6 text-lg font-bold text-slate-800">Form Edit Menu</h3>

  <?php echo form_open('menu/edit', 'role="form"'); ?>
  <?php echo form_hidden('id', $menu['id']); ?>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Nama Menu</label>
        <input type="text" value="<?php echo $menu['nama_menu']; ?>" name="nama_menu" placeholder="Masukkan Nama Menu"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Link</label>
        <input type="text" value="<?php echo $menu['link']; ?>" name="link" placeholder="Masukkan Link Menu"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Icon</label>
        <input type="text" value="<?php echo $menu['icon']; ?>" name="icon" placeholder="Masukkan Icon Menu (contoh: fa fa-book)"
               class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-slate-700">Is Main Menu</label>
        <select name="is_main_menu" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          <option value="0">Main Menu</option>
          <?php
            $tabelmenu = $this->db->get('tabel_menu');
            foreach ($tabelmenu->result() as $row) {
              echo "<option value='$row->id' ";
              echo $row->id == $menu['is_main_menu'] ? 'selected' : '';
              echo ">$row->nama_menu</option>";
            }
          ?>
        </select>
      </div>
    </div>

    <div class="mt-7 flex flex-wrap items-center gap-3">
      <button type="submit" name="submit"
              class="rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Simpan</button>
      <?php echo anchor('menu', 'Kembali', array('class'=>'rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-200')); ?>
    </div>

  </form>
</div>