<div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-2.5 sm:px-5">
    <?php
      $nim = $this->uri->segment(3);
      $query = "SELECT ts.nama
              FROM tbl_nilai AS tn, tbl_siswa AS ts
              WHERE tn.nim = ts.nim AND tn.nim = '$nim'";
      $nama = $this->db->query($query)->row_array();
    ?>
    <h3 class="text-sm font-bold text-slate-800">Data Nilai Siswa <?php echo isset($nama['nama']) ? $nama['nama'] : ''; ?></h3>
  </div>
  <div class="overflow-x-auto p-2 sm:p-4">
    <table id="mytable" class="w-full text-sm">
      <thead>
        <tr class="border-b border-slate-200">
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">NO</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">Nama Mapel</th>
          <th class="px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">Nilai</th>
          <th class="px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500">Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $no = 1;
          foreach ($nilai_siswa->result() as $row) {
            if ($row->nilai > 90) {
              $Keterangan = '<p class="text-green">Sangat baik</p>';
            } elseif ($row->nilai > 80 and $row->nilai <= 90) {
              $Keterangan = '<p class="text-green">Baik</p>';
            } elseif ($row->nilai > 70 and $row->nilai <= 80) {
              $Keterangan = '<p class="text-yellow">Cukup</p>';
            } else {
              $Keterangan = '<p class="text-red">Kurang</p>';
            }

            echo "<tr class='border-b border-slate-100 last:border-0'>
                    <td class='px-3 py-2.5'>$no</td>
                    <td class='px-3 py-2.5'>$row->nama_mapel</td>
                    <td class='px-3 py-2.5'>$row->nilai</td>
                    <td class='px-3 py-2.5 text-center'>$Keterangan</td>
                  </tr>";
            $no++;
          }
        ?>
      </tbody>
    </table>
  </div>
  <div class="border-t border-slate-100 px-4 py-3">
    <?php echo anchor('siswa/siswa_aktif', 'Kembali', array('class'=>'btn btn-danger')); ?>
  </div>
</div>

<script src="<?php echo base_url(); ?>assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    $('#mytable').DataTable({
      "order": [[1, "asc"]],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
      "initComplete": function (settings, json) {
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").attr("placeholder", "Cari mapel...");
        $("#mytable_wrapper").parents(".rounded-2xl").first().find(".dataTables_filter input").css("min-width", "200px");
      }
    });
  });
</script>