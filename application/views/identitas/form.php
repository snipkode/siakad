<?php if ($this->session->flashdata('msg_identitas')): ?>
  <div id="msg-identitas" class="mb-4 flex items-center gap-2.5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
    <i class="fa fa-check-circle" aria-hidden="true"></i>
    <?php echo $this->session->flashdata('msg_identitas'); ?>
  </div>
<?php endif; ?>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
  <form action="<?php echo site_url('identitas/save'); ?>" method="post" role="form" id="form-identitas"
        class="lg:col-span-2">
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-100 px-4 py-3 sm:px-6">
        <h3 class="text-sm font-bold text-slate-800">Identitas Sekolah</h3>
        <p class="text-xs text-slate-500">Informasi dasar yang ditampilkan di kop rapor &amp; dokumen sekolah</p>
      </div>
      <div class="space-y-5 p-4 sm:p-6">
        <div>
          <label for="nama_sekolah" class="mb-1.5 block text-sm font-medium text-slate-700">Nama Sekolah <span class="text-red-500">*</span></label>
          <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-university" aria-hidden="true"></i></span>
            <input type="text" id="nama_sekolah" name="nama_sekolah" value="<?php echo htmlspecialchars((string) @$identitas['nama_sekolah']); ?>" placeholder="Masukkan nama sekolah / yayasan"
                   class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          </div>
        </div>

        <div>
          <label for="npsn" class="mb-1.5 block text-sm font-medium text-slate-700">NPSN</label>
          <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-hashtag" aria-hidden="true"></i></span>
            <input type="text" id="npsn" name="npsn" value="<?php echo htmlspecialchars((string) @$identitas['npsn']); ?>" placeholder="Nomor Pokok Sekolah Nasional"
                   class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
          </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
          <div>
            <label for="kepala_sekolah" class="mb-1.5 block text-sm font-medium text-slate-700">Kepala Sekolah</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
              <input type="text" id="kepala_sekolah" name="kepala_sekolah" value="<?php echo htmlspecialchars((string) @$identitas['kepala_sekolah']); ?>" placeholder="Nama Kepala Sekolah"
                     class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
          </div>
          <div>
            <label for="nip_kepala" class="mb-1.5 block text-sm font-medium text-slate-700">NIP Kepala Sekolah</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-id-badge" aria-hidden="true"></i></span>
              <input type="text" id="nip_kepala" name="nip_kepala" value="<?php echo htmlspecialchars((string) @$identitas['nip_kepala']); ?>" placeholder="NIP Kepala Sekolah"
                     class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5 rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="border-b border-slate-100 px-4 py-3 sm:px-6">
        <h3 class="text-sm font-bold text-slate-800">Alamat &amp; Kontak</h3>
        <p class="text-xs text-slate-500">Kontak yang bisa dihubungi sekolah</p>
      </div>
      <div class="space-y-5 p-4 sm:p-6">
        <div>
          <label for="alamat" class="mb-1.5 block text-sm font-medium text-slate-700">Alamat</label>
          <div class="relative">
            <span class="pointer-events-none absolute left-0 top-2.5 flex w-10 items-start justify-center pt-1 text-slate-400"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
            <textarea id="alamat" name="alamat" rows="3" placeholder="Alamat lengkap sekolah"
                      class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30"><?php echo htmlspecialchars((string) @$identitas['alamat']); ?></textarea>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
          <div>
            <label for="no_telp" class="mb-1.5 block text-sm font-medium text-slate-700">No. Telpon</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-phone" aria-hidden="true"></i></span>
              <input type="text" id="no_telp" name="no_telp" value="<?php echo htmlspecialchars((string) @$identitas['no_telp']); ?>" placeholder="0651-23462"
                     class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
          </div>
          <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-envelope" aria-hidden="true"></i></span>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars((string) @$identitas['email']); ?>" placeholder="info@sekolah.sch.id"
                     class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
          </div>
          <div>
            <label for="website" class="mb-1.5 block text-sm font-medium text-slate-700">Website</label>
            <div class="relative">
              <span class="pointer-events-none absolute inset-y-0 left-0 flex w-10 items-center justify-center text-slate-400"><i class="fa fa-globe" aria-hidden="true"></i></span>
              <input type="text" id="website" name="website" value="<?php echo htmlspecialchars((string) @$identitas['website']); ?>" placeholder="https://www.sekolah.sch.id"
                     class="w-full rounded-xl border border-slate-300 py-2.5 pl-10 pr-3.5 text-sm focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500/30">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-5">
      <button type="submit" name="submit"
              class="inline-flex items-center gap-1.5 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700"><i class="fa fa-save" aria-hidden="true"></i> Simpan</button>
      <p class="text-xs text-slate-400">Perubahan langsung terpakai di kop rapor</p>
    </div>
  </form>

  <aside class="lg:sticky lg:top-4 lg:self-start">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="flex items-center gap-2.5 bg-gradient-to-br from-sky-500 to-blue-600 px-4 py-3 text-white">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/20">
          <i class="fa fa-eye" aria-hidden="true"></i>
        </span>
        <div class="flex-1">
          <p class="text-sm font-bold leading-tight">Preview Kop Rapor</p>
          <p class="text-[11px] leading-tight text-sky-100">Ter-update saat mengetik</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/20 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide">
          <span id="live-dot" class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span> Live
        </span>
      </div>

      <div class="border-b border-slate-100 px-5 py-6 text-center">
        <p class="text-[10px] uppercase tracking-[0.35em] text-slate-400">Nama Sekolah</p>
        <h2 id="prev-nama_sekolah" class="mt-2 text-lg font-extrabold uppercase leading-snug text-slate-800"><?php echo htmlspecialchars((string) @$identitas['nama_sekolah']); ?></h2>
        <p id="prev-npsn" data-prefix="NPSN " class="mt-1 text-[11px] text-slate-400">NPSN <?php echo htmlspecialchars((string) @$identitas['npsn']); ?></p>
        <div class="mx-auto mt-3 h-px w-24 bg-slate-200"></div>
        <p id="prev-alamat" class="mt-3 text-xs leading-relaxed text-slate-500"><?php echo htmlspecialchars((string) @$identitas['alamat']); ?></p>
      </div>

      <div class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 px-5 py-4 text-[11px] text-slate-500">
        <span class="inline-flex items-center gap-1.5" data-kontak="no_telp">
          <i class="fa fa-phone text-slate-400" aria-hidden="true"></i><span id="prev-no_telp"><?php echo htmlspecialchars((string) @$identitas['no_telp']); ?></span>
        </span>
        <span class="inline-flex items-center gap-1.5" data-kontak="email">
          <i class="fa fa-envelope text-slate-400" aria-hidden="true"></i><span id="prev-email"><?php echo htmlspecialchars((string) @$identitas['email']); ?></span>
        </span>
        <span class="inline-flex items-center gap-1.5" data-kontak="website">
          <i class="fa fa-globe text-slate-400" aria-hidden="true"></i><span id="prev-website"><?php echo htmlspecialchars((string) @$identitas['website']); ?></span>
        </span>
      </div>
    </div>
  </aside>
</div>

<script>
  $(document).ready(function () {
    var $inputs = $('#form-identitas input, #form-identitas textarea');

    function renderKontak() {
      $('[data-kontak]').each(function () {
        var t = $(this).find('span[id^=prev-]').text().trim();
        $(this).toggle(t !== '' && t !== '(kosong)');
      });
    }

    var EMPTY = '(kosong)';
    $inputs.on('input', function () {
      var $t = $(this);
      var $prev = $('#prev-' + $t.attr('name'));
      if ($prev.length) {
        var v = $t.val().trim();
        var prefix = $prev.data('prefix') || '';
        $prev.text(prefix + (v === '' ? EMPTY : $t.val()))
             .toggleClass('text-slate-300 italic', v === '')
             .toggleClass('text-slate-500', v === '' && prefix !== '')
             .toggleClass('text-slate-400', v === '' && prefix === '')
             .toggleClass('text-slate-800', v !== '');
      }
      renderKontak();
    });

    $inputs.each(function () { $(this).trigger('input'); });
    renderKontak();
  });
</script>