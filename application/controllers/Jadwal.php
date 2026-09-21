<?php

	class Jadwal extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			//checkAksesModule();
			// $this->load->library('ssp');
			$this->load->model('model_jadwal');
		}

		function index()
		{
			// Apabila yang login = guru (id_level_user 3 = guru) maka hanya akan menampilkan jadwal yang hanya diajar oleh guru tersebut
			if ($this->session->userdata('id_level_user') == 3) {
				$sql = "SELECT tj.id_jadwal, tk.nama_kelas, tju.nama_jurusan, ttk.nama_tingkatan, tm.nama_mapel, tj.jam, 
						tr.nama_ruangan, tj.hari, tj.semester 
						FROM tbl_jadwal AS tj, tbl_kelas AS tk, tbl_jurusan AS tju, tbl_ruangan AS tr, tbl_mapel AS tm, tbl_tingkatan_kelas AS ttk
						WHERE tj.kd_kelas = tk.kd_kelas AND tj.kd_jurusan = tju.kd_jurusan AND tj.kd_ruangan = tr.kd_ruangan AND tj.kd_mapel = tm.kd_mapel AND tj.kd_tingkatan = ttk.kd_tingkatan AND tj.id_guru =".$this->session->userdata('id_guru')." ORDER BY tj.kd_kelas, tj.kd_mapel";
				$data['jadwal'] =$this->db->query($sql);
				// load daftar ngajar guru
				$this->template->load('template', 'jadwal/jadwal_ajar_guru', $data);
			} else {
				$this->template->load('template', 'jadwal/view');
			}
		}

		function generate_jadwal()
		{
			if (isset($_POST['submit'])) {
				$this->model_jadwal->generateJadwal();
			}
			redirect('jadwal');
		}

		function auto_isi()
		{
			$this->model_jadwal->autoIsiJadwal();
			redirect('jadwal');
		}

		function seed_full()
		{
			$this->model_jadwal->seedJadwalFull();
			redirect('jadwal');
		}

		function dataJadwal()
		{
			$kode_jurusan		= $_GET['kd_jurusan'];
			$kode_tingkatan		= $_GET['kd_tingkatan'];
			//$idkurikulum		= $_GET['kurikulumnya'];
			$kelas 				= $_GET['kelas'];

			echo "<div class='overflow-x-auto'>
                    <table class='w-full text-sm'>
                    <thead>
                        <tr>
                            <th class='w-[3%] px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500'>No</th>
                            <th class='w-[23%] px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Mata Pelajaran</th>
                            <th class='w-[27%] px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Guru</th>
                            <th class='w-[17%] px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Ruangan</th>
                            <th class='w-[13%] px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Hari</th>
                            <th class='w-[17%] px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>Jam</th>
                            <th class='w-14 px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500'></th>
                        </tr>
                    </thead><tbody>";

  			$sql_datajadwal	= "SELECT tj.id_jadwal, tm.nama_mapel, tg.id_guru, tg.nama_guru, tr.kd_ruangan, tj.hari, 				   tj.jam
							   FROM tbl_jadwal AS tj
							   LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel = tm.kd_mapel
							   LEFT JOIN tbl_guru AS tg ON tj.id_guru = tg.id_guru
							   LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
							   WHERE tj.kd_jurusan = '$kode_jurusan' AND tj.kd_kelas = '$kelas'";
			$data_jadwal	= $this->db->query($sql_datajadwal)->result();
			$no = 1;
			$jam_pelajaran	= $this->model_jadwal->jamPelajaran();
			$hari           = array(
								'Senin'  => 'Senin',
								'Selasa' => 'Selasa',
								'Rabu'   => 'Rabu',
								'Kamis'  => 'Kamis',
								'Jumat'  => 'Jumat',
								'Sabtu'  => 'Sabtu'
							  );

			foreach ($data_jadwal as $row) {
				echo "<tr class='border-b border-slate-100 transition-colors hover:bg-slate-50'>
						<td class='px-3 py-2.5 text-center text-xs font-semibold text-slate-400' data-row data-label='No'>$no</td>
						<td class='px-3 py-2.5 font-medium text-slate-800' data-label='Mata Pelajaran'>$row->nama_mapel</td>
						<td class='px-3 py-2.5' data-label='Guru'>
							<div class='jadwal-cell'>".cmb_dinamis('guru', 'tbl_guru', 'nama_guru', 'id_guru', $row->id_guru, "id='guru".$row->id_jadwal."' onChange='updateGuru(".$row->id_jadwal.")'")."</div>
						</td>
						<td class='px-3 py-2.5' data-label='Ruangan'>
							<div class='jadwal-cell'>".cmb_dinamis('ruangan', 'tbl_ruangan', 'nama_ruangan', 'kd_ruangan', $row->kd_ruangan, "id='ruangan".$row->id_jadwal."' onChange='updateRuangan(".$row->id_jadwal.")'")."</div>
						</td>
						<td class='px-3 py-2.5' data-label='Hari'>
							<div class='jadwal-cell'>".form_dropdown('hari', $hari, $row->hari, "class='form-control jadwal-inline' id='hari".$row->id_jadwal."' onChange='updateHari(".$row->id_jadwal.")'")."</div>
						</td>
						<td class='px-3 py-2.5' data-label='Jam'>
							<div class='jadwal-cell'>".form_dropdown('jam', $jam_pelajaran, $row->jam, "class='form-control jadwal-inline' id='jam".$row->id_jadwal."' onChange='updateJam(".$row->id_jadwal.")'")."</div>
						</td>
						<td class='px-3 py-2.5 text-right'>".anchor('jadwal/delete_dataJadwal/'.$row->id_jadwal, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus jadwal ini?")\'')."</td>
					 </tr>";
				$no++;
			}

			if (empty($data_jadwal)) {
				echo "<tr>
						<td colspan='7' class='px-3 py-12 text-center'>
							<i class='fa fa-folder-open-o text-3xl text-slate-200'></i>
							<p class='mt-2 text-xs font-semibold uppercase tracking-wide text-slate-400'>Belum ada jadwal untuk kelas ini</p>
						</td>
					</tr>";
			}

            echo  "</tbody></table></div>";
		}

		function update_guru()
		{
			$idguru 	= $_GET['id_guru'];
			$idjadwal 	= $_GET['id_jadwal'];
			$this->db->where('id_jadwal', $idjadwal);
			$this->db->update('tbl_jadwal', array('id_guru' => $idguru));
		}

		function update_ruangan()
		{
			$kdruangan 	= $_GET['kd_ruangan'];
			$idjadwal 	= $_GET['id_jadwal'];
			$this->db->where('id_jadwal', $idjadwal);
			$this->db->update('tbl_jadwal', array('kd_ruangan' => $kdruangan));
		}

		function update_hari()
		{
			$harinya 	= $_GET['hari'];
			$idjadwal 	= $_GET['id_jadwal'];
			$this->db->where('id_jadwal', $idjadwal);
			$this->db->update('tbl_jadwal', array('hari' => $harinya));
		}

		function update_jam()
		{
			$jamnya 	= $_GET['jam'];
			$idjadwal 	= $_GET['id_jadwal'];
			$this->db->where('id_jadwal', $idjadwal);
			$this->db->update('tbl_jadwal', array('jam' => $jamnya));
		}

		function tampil_kelas()
		{
			echo "<select id='kelas' name='kelas' class='form-control' onChange='loadPelajaran()'>";

			// menggunakan get_where
			// $where = array('kd_tingkatan' => $_GET['kd_tingkatan'], 'kd_jurusan' => $_GET['jurusan']);
			// $kelas = $this->db->get_where('tbl_kelas', $where);

			// menggunakan get
			$this->db->where('kd_jurusan', $_GET['kd_jurusan']);
			$this->db->where('kd_tingkatan', $_GET['kd_tingkatan']);
			$kelas = $this->db->get('tbl_kelas');
			
			foreach ($kelas->result() as $row) {
				echo "<option value='$row->kd_kelas'>$row->nama_kelas</option>";
			}

			echo "</select>";
		}

		function cetak_jadwal() {
 		$kelas = $_POST['kelas'];
 		$this->load->library('CFPDF');

 		$days            = array(
							'SENIN'  => 'SENIN',
							'SELASA' => 'SELASA',
							'RABU'   => 'RABU',
							'KAMIS'  => 'KAMIS',
							'JUMAT'  => 'JUMAT',
							'SABTU'  => 'SABTU'
						 );

 		$pdf = new CFPDF('L', 'mm', 'A4');
 		$pdf->AliasNbPages();
 		$pdf->AddPage();
 		$pdf->SetMargins(10, 12, 10);

 		// judul PDF
 		$nama_kelas = $kelas;
 		$kq = $this->db->query("SELECT nama_kelas FROM tbl_kelas WHERE kd_kelas='$kelas'");
 		if ($kq->num_rows() > 0) { $nama_kelas = $kq->row()->nama_kelas; }

 		$pdf->SetFont('Arial', 'B', 13);
 		$pdf->Cell(0, 10, 'JADWAL PELAJARAN', 0, 1, 'C');
 		$pdf->SetFont('Arial', '', 10);
 		$pdf->Cell(0, 8, 'Kelas : '.$nama_kelas.' ('.$kelas.')', 0, 1, 'C');
 		$pdf->Ln(3);

 		// header matriks
 		$pdf->SetFont('Arial','B',9);
        	$pdf->Cell(9,9,'NO',1,0,'C');
        	$pdf->Cell(26,9,'WAKTU',1,0,'C');

        	foreach ($days as $day) {
        		$pdf->Cell(40,9,$day,1,0,'C');
        	}
        	$pdf->Ln(9);

        	// baris matriks
        	$pdf->SetFont('Arial', '', 8);
        	$jam_ajar = $this->model_jadwal->jamPelajaran();
        	$no=1;

        	foreach ($jam_ajar as $jam) {
        		$pdf->Cell(9,9,$no,1,0,'C');
            	$pdf->Cell(26,9,$jam,1,0,'C');

            	foreach ($days as $day) {
            		$pdf->Cell(40,9,$this->getPelajaran($jam, $day, $kelas),1,0,'L');
            	}
            	$pdf->Ln(9);
            	$no++;
        	}
        	$pdf->Ln(2);

 		// rincian jadwal kelas tsb (agar tidak ada jadwal yang hilang/tampak kosong)
 		$sqlr = "SELECT tm.nama_mapel, tg.nama_guru, tr.nama_ruangan, tj.hari, tj.jam
				 FROM tbl_jadwal AS tj
				 LEFT JOIN tbl_mapel AS tm ON tj.kd_mapel  = tm.kd_mapel
				 LEFT JOIN tbl_guru AS tg  ON tj.id_guru   = tg.id_guru
				 LEFT JOIN tbl_ruangan AS tr ON tj.kd_ruangan = tr.kd_ruangan
				 WHERE tj.kd_kelas = '$kelas'
				 ORDER BY FIELD(tj.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), tj.jam";
 		$rincian = $this->db->query($sqlr);
 		if ($rincian->num_rows() > 0) {
 			$pdf->SetAutoPageBreak(false, 10);
 			if ($pdf->GetY() > 190) { $pdf->AddPage(); }
 			$pdf->Ln(4);
 			$pdf->SetFont('Arial', 'B', 9);
 			$pdf->Cell(0, 7, 'RINCIAN JADWAL', 0, 1, 'L');
 			$pdf->Ln(1);

 			$drawRincianHeader = function () use ($pdf) {
 				$pdf->SetFont('Arial', 'B', 8);
 				$pdf->Cell(8, 6, 'NO', 1, 0, 'C');
 				$pdf->Cell(64, 6, 'MATA PELAJARAN', 1, 0, 'C');
 				$pdf->Cell(75, 6, 'GURU', 1, 0, 'C');
 				$pdf->Cell(47, 6, 'RUANGAN', 1, 0, 'C');
 				$pdf->Cell(36, 6, 'HARI', 1, 0, 'C');
 				$pdf->Cell(47, 6, 'JAM', 1, 1, 'C');
 				$pdf->SetFont('Arial', '', 8);
 			};
 			$drawRincianHeader();

 			$n = 1;
 			foreach ($rincian->result() as $r) {
 				if ($pdf->GetY() > 188) { $pdf->AddPage(); $drawRincianHeader(); }
 				$hari = trim($r->hari);
 				$jam  = trim($r->jam);
 				$pdf->Cell(8, 6, $n, 1, 0, 'C');
 				$pdf->Cell(64, 6, $r->nama_mapel, 1, 0, 'L');
 				$pdf->Cell(75, 6, $r->nama_guru, 1, 0, 'L');
 				$pdf->Cell(47, 6, $r->nama_ruangan, 1, 0, 'L');
 				$pdf->Cell(36, 6, $hari !== '' ? $hari : 'Belum diatur', 1, 0, 'L');
 				$pdf->Cell(47, 6, $jam !== '' ? $jam : 'Belum diatur', 1, 1, 'L');
 				$n++;
 			}
 		}

	 		$pdf->Output();
	 	}

	 	function getPelajaran($jam, $hari, $kelas) {
	 		$sql = "SELECT tj.*,tm.nama_mapel
                   FROM tbl_jadwal as tj, tbl_mapel as tm 
                   WHERE tj.kd_mapel=tm.kd_mapel and tj.kd_kelas='$kelas' and tj.hari='$hari' and tj.jam='$jam'";
	 		$pelajaran = $this->db->query($sql);
	 		if ($pelajaran->num_rows()>0) {
	 			$row = $pelajaran->row_array();
	 			return $row['nama_mapel'];
	 		} else {
	 			return '-';
	 		}
	 	}

	}

?>