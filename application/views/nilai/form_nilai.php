<div class="mb-4 grid grid-cols-2 gap-3 lg:max-w-2xl">
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Tahun Akademik</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo get_tahun_akademik('tahun_akademik'); ?></p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Semester</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo get_tahun_akademik('semester'); ?></p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Jurusan &amp; Tingkatan</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo "Jurusan".' '.$kelas['nama_jurusan'].' '.$kelas['nama_tingkatan']; ?> (<?php echo $kelas['nama_kelas']; ?>)</p>
  </div>
  <div class="rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Mata Pelajaran</p>
    <p class="mt-0.5 text-sm font-bold text-slate-800"><?php echo $kelas['nama_mapel']; ?></p>
  </div>
</div>

<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <h3 class="text-sm font-bold text-slate-800">Daftar Siswa</h3>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200">
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NIM</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NAMA SISWA</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">NILAI</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($siswa as $row) {
            echo "<tr class='border-b border-slate-100 last:border-0'>
                    <td class='px-3 py-2.5 text-center'>$row->nim</td>
                    <td class='px-3 py-2.5'>$row->nama</td>
                    <td class='px-3 py-2.5'>
                      <input type='text' onKeyUp='updateNilai(\"$row->nim\")' id='nilai".$row->nim."' value='".check_nilai($row->nim, $this->uri->segment(3))."' class='form-control mx-auto text-center'>
                    </td>
                  </tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</div>

<script type="text/javascript">
  function updateNilai(nim) {
    var nilai = $("#nilai" + nim).val();
    $.ajax({
      type: 'GET',
      url: '<?php echo base_url(); ?>nilai/update_nilai',
      data: 'nim=' + nim + '&id_jadwal=' + <?php echo $this->uri->segment(3); ?> + '&nilai=' + nilai,
      success: function (html) { }
    });
  }
</script>