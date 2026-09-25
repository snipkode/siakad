<?php
 
	class Laporan_nilai extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			checkAksesModule();
		}

		function index()
		{
			// KAMPUS: daftar mahasiswa + IP/IPK, aksi KHS & Transkrip
			if (meta_mode() === 'KAMPUS') {
				$cari = trim((string) $this->input->get('cari'));
				$this->db->where('kd_mode', 'KAMPUS');
				if ($cari !== '') {
					$this->db->group_start();
					$this->db->like('nama', $cari);
					$this->db->or_like('nim', $cari);
					$this->db->group_end();
				}
				$this->db->order_by('nama', 'asc');
				$data['mahasiswa'] = $this->db->get('v_krs_mahasiswa')->result_array();
				$data['cari']      = $cari;
				$this->template->load('template', 'laporan_nilai/list_kampus', $data);
				return;
			}

			$is_guru 	= ($this->session->userdata('id_level_user') == 3);
			$tahun 		= (int) get_tahun_akademik('id_tahun_akademik');
			$semester 	= (string) get_tahun_akademik('semester');

			// Filter (admin): pencarian + kelas terpilih
			$cari	 	= trim((string) $this->input->get('cari'));
			$kd_kelas 	= trim((string) $this->input->get('kelas'));

			if ($is_guru) {
				$id_guru 	= (int) $this->session->userdata('id_guru');
				$walikelas 	= $this->db->get_where('tbl_walikelas', array('id_guru' => $id_guru))->row_array();
				$kd_kelas 	= (!empty($walikelas['kd_kelas'])) ? $walikelas['kd_kelas'] : null;
			}

			// info kelas (wali kelas => kelasnya; admin => kelas filter bila dipilih)
			$kelas_pilih = ($kd_kelas === '') ? null : $kd_kelas;
			if ($is_guru) {
				$qk = ($kelas_pilih !== null)
					? $this->db->query("SELECT tk.nama_kelas, tju.nama_jurusan, ttk.nama_tingkatan
										FROM tbl_kelas AS tk
										JOIN tbl_jurusan AS tju ON tju.kd_jurusan = tk.kd_jurusan
										JOIN tbl_tingkatan_kelas AS ttk ON ttk.kd_tingkatan = tk.kd_tingkatan
										WHERE tk.kd_kelas = ".$this->db->escape($kelas_pilih))
					: null;
			} else {
				$qk = ($kelas_pilih !== null)
					? $this->db->query("SELECT tk.nama_kelas, tju.nama_jurusan, ttk.nama_tingkatan
										FROM tbl_kelas AS tk
										JOIN tbl_jurusan AS tju ON tju.kd_jurusan = tk.kd_jurusan
										JOIN tbl_tingkatan_kelas AS ttk ON ttk.kd_tingkatan = tk.kd_tingkatan
										WHERE tk.kd_kelas = ".$this->db->escape($kelas_pilih))
					: null;
			}
			$kelas = ($qk !== null && $qk->num_rows() > 0) ? $qk->row_array() : null;

			$siswa 		= "SELECT ts.nim, ts.nama, ts.gender, trk.kd_kelas, tk.nama_kelas,
							  (SELECT COUNT(DISTINCT tj2.kd_mapel)
							   FROM tbl_nilai AS tn2 JOIN tbl_jadwal AS tj2 ON tj2.id_jadwal = tn2.id_jadwal
							   WHERE tn2.nim = ts.nim AND tj2.kd_kelas = trk.kd_kelas
							   	 AND tj2.id_tahun_akademik = ".$tahun." AND tj2.semester = ".$this->db->escape($semester).") AS jml_mapel,
							  (SELECT ROUND(AVG(tn3.nilai), 1)
							   FROM tbl_nilai AS tn3 JOIN tbl_jadwal AS tj3 ON tj3.id_jadwal = tn3.id_jadwal
							   WHERE tn3.nim = ts.nim AND tj3.kd_kelas = trk.kd_kelas
							   	 AND tj3.id_tahun_akademik = ".$tahun." AND tj3.semester = ".$this->db->escape($semester).") AS rata_nilai
							  FROM tbl_siswa AS ts
							  JOIN tbl_riwayat_kelas AS trk ON trk.nim = ts.nim
							  LEFT JOIN tbl_kelas AS tk ON tk.kd_kelas = trk.kd_kelas
							  WHERE 1=1";
			if ($kelas !== null) {
				$siswa .= " AND trk.kd_kelas = ".$this->db->escape($kelas_pilih);
			}
			if ($cari !== '') {
				$siswa .= " AND (ts.nama LIKE ".$this->db->escape('%'.$cari.'%')." OR ts.nim LIKE ".$this->db->escape('%'.$cari.'%').")";
			}
			$siswa .= " AND trk.id_tahun_akademik = ".$tahun." ORDER BY tk.nama_kelas, ts.nama";

			// jumlah mata pelajaran (distinct) yang harus dinilai per kelas pada tahun akademik aktif
			$jml_mapel_kelas = array();
			$qc = $this->db->query("SELECT tj.kd_kelas, COUNT(DISTINCT tj.kd_mapel) AS c
									FROM tbl_jadwal tj
									WHERE tj.id_tahun_akademik = ".$tahun." AND tj.semester = ".$this->db->escape($semester)."
									GROUP BY tj.kd_kelas");
			foreach ($qc->result() as $r) {
				$jml_mapel_kelas[$r->kd_kelas] = (int) $r->c;
			}

			$data['kelas'] 			= $kelas;
			$q_siswa 			= $this->db->query($siswa);
			$data['siswa'] 			= $q_siswa;
			$data['total_siswa'] 		= $q_siswa->num_rows();
			$data['jml_mapel_kelas'] 	= $jml_mapel_kelas;
			$data['is_guru'] 		= $is_guru;
			$data['cari'] 			= $cari;
			$data['kelas_pilih'] 		= $kelas_pilih;
			$data['jurusan_filter'] 	= trim((string) $this->input->get('jurusan'));
			$data['tingkatan_filter'] 	= trim((string) $this->input->get('tingkatan'));
			$this->template->load('template', 'laporan_nilai/list_siswa', $data);
		}

		function tampil_kelas()
		{
			$jurusannya 		= trim((string) $this->input->get('jurusan'));
			$tingkatannya 		= trim((string) $this->input->get('tingkatan'));
			$terpilih 			= trim((string) $this->input->get('kelas'));

			$this->db->where('kd_jurusan', $jurusannya);
			$this->db->where('kd_tingkatan', $tingkatannya);
			$this->db->order_by('kd_kelas');
			$kelas = $this->db->get('tbl_kelas')->result();

			echo "<select id='slcKelas' name='kelas' class='form-control'>";
			echo "<option value=''>-- Pilih Kelas --</option>";
			foreach ($kelas as $row) {
				$sel = ($row->kd_kelas == $terpilih) ? ' selected' : '';
				echo "<option value='$row->kd_kelas'$sel>$row->nama_kelas</option>";
			}
			echo "</select>";
		}

		function nilai_semester(){
			// KAMPUS: cetak KHS semester aktif
			if (meta_mode() === 'KAMPUS') {
				$this->cetak_khs($this->uri->segment(3));
				return;
			}

       		// blok query info siswa (kelas = riwayat pada tahun akademik aktif)
	       $nim 		= $this->uri->segment(3);
	       $tahun 		= (int) get_tahun_akademik('id_tahun_akademik');
	       $semester 	= (string) get_tahun_akademik('semester');

	       $rk 			= $this->db->query("SELECT kd_kelas FROM tbl_riwayat_kelas
										WHERE nim = ".$this->db->escape($nim)." AND id_tahun_akademik = ".$tahun)->row_array();
	       $kd_kelas 	= !empty($rk['kd_kelas']) ? $rk['kd_kelas'] : null;

	       $sqlSiswa = "SELECT ts.nama AS nama_siswa, ts.nim, tju.nama_jurusan, tk.nama_kelas, tk.kd_tingkatan
	                    FROM tbl_riwayat_kelas AS trk
	                    JOIN tbl_siswa AS ts ON ts.nim = trk.nim
	                    JOIN tbl_kelas AS tk ON tk.kd_kelas = trk.kd_kelas
	                    LEFT JOIN tbl_jurusan AS tju ON tju.kd_jurusan = tk.kd_jurusan
	                    WHERE trk.nim = ".$this->db->escape($nim)." AND trk.id_tahun_akademik = ".$tahun;
	       $siswa = $this->db->query($sqlSiswa)->row_array();
	       if (empty($siswa)) {
	       	$siswa = array('nim' => $nim, 'nama_siswa' => '-', 'nama_kelas' => '-', 'nama_jurusan' => '-', 'kd_tingkatan' => null);
	       }

	       // wali kelas & kepala sekolah untuk blok tanda tangan
	       $wali = $this->db->query("SELECT tg.nama_guru, tg.nuptk
	       							FROM tbl_walikelas AS tw
	       							JOIN tbl_guru AS tg ON tg.id_guru = tw.id_guru
	       							WHERE tw.kd_kelas = ".$this->db->escape($kd_kelas)." AND tw.id_tahun_akademik = ".$tahun)
	       							->row_array();
	       $wali = (!empty($wali)) ? $wali : array('nama_guru' => null, 'nuptk' => null);

	        $this->load->library('CFPDF');
	        $pdf = new FPDF('P','mm','A4');
	        $pdf->AliasNbPages();
	        $pdf->SetTitle('Rapor '.$siswa['nama_siswa'].' - '.$siswa['nim'], true);
	        $pdf->SetTopMargin(12);
	        $pdf->SetLeftMargin(15);
	        $pdf->SetRightMargin(15);
	        $pdf->SetAutoPageBreak(true, 15);
	        $pdf->AddPage();

	        // ---------- KOP SEKOLAH ----------
	        $pdf->SetFont('Arial','B',14);
	        $pdf->Cell(0,7,strtoupper(identitas('nama_sekolah')),0,1,'C');
	        $pdf->SetFont('Arial','',9);
	        $alamat 	= trim(identitas('alamat'));
	        $kontak 	= array();
	        if (trim(identitas('no_telp')) !== '' && stripos($alamat, identitas('no_telp')) === false) {
	        	$kontak[] = 'Telp. '.identitas('no_telp');
	        }
	        if (trim(identitas('email')) !== '') 	$kontak[] = 'Email: '.identitas('email');
	        if (trim(identitas('website')) !== '') 	$kontak[] = trim(identitas('website'));
	        if (trim(identitas('npsn')) !== '') 	$kontak[] = 'NPSN '.identitas('npsn');
	        $kop = trim($alamat.(count($kontak) ? ' - '.implode(' - ', $kontak) : ''));
	        $pdf->Cell(0,5,$kop,0,1,'C');
	        $y = $pdf->GetY() + 2;
	        $pdf->SetDrawColor(0,0,0);
	        $pdf->SetLineWidth(0.8);
	        $pdf->Line(15,$y,195,$y);
	        $pdf->SetLineWidth(0.2);
	        $pdf->Line(15,$y+1,195,$y+1);
	        $pdf->SetY($y+4);

	        // ---------- JUDUL ----------
	        $pdf->SetFont('Arial','B',12);
	        $pdf->Cell(0,7,'LAPORAN HASIL BELAJAR SISWA',0,1,'C');
	        $pdf->SetFont('Arial','B',9);
	        $pdf->Cell(0,5,'SEMESTER '.strtoupper($semester).'  TAHUN PELAJARAN '.get_tahun_akademik('tahun_akademik'),0,1,'C');
	        $pdf->Ln(4);

	        // ---------- DATA SISWA (grid 2 kolom, lebar konsisten 180) ----------
	        $pdf->SetDrawColor(203,213,225);
	        $pdf->SetFillColor(226,232,240);
	        $lw = 33; $vw = 57; // (33+57) x 2 = 180
	        $kelas_tampil = preg_replace('/^Kelas\s+/i', '', $siswa['nama_kelas']);
	        $pdf->SetFont('Arial','B',9);
	        $this->_identitas_baris($pdf, $lw, $vw, 'Nama Siswa', $siswa['nama_siswa'], 'NIS', $siswa['nim']);
	        $this->_identitas_baris($pdf, $lw, $vw, 'Kelas', $kelas_tampil, 'Jurusan', $siswa['nama_jurusan']);
	        $this->_identitas_baris($pdf, $lw, $vw, 'Semester', ucfirst($semester), 'Tahun Pelajaran', get_tahun_akademik('tahun_akademik'));
	        $pdf->Ln(4);

	        // ---------- TABEL NILAI MAPEL ----------
	        $pdf->SetFont('Arial','B',8.5);
	        $pdf->SetFillColor(226,232,240);
	        $pdf->Cell(8,7,'NO',1,0,'C',1);
	        $pdf->Cell(56,7,'MATA PELAJARAN',1,0,'L',1);
	        $pdf->Cell(10,7,'KKM',1,0,'C',1);
	        $pdf->Cell(13,7,'ANGKA',1,0,'C',1);
	        $pdf->Cell(27,7,'KETERCAPAIAN',1,0,'C',1);
	        $pdf->Cell(15,7,'RATA',1,0,'C',1);
	        $pdf->Cell(51,7,'DESKRIPSI KEMAMPUAN',1,1,'C',1);

	        // daftar mapel UNIK milik kelas siswa pada semester aktif (bukan semua sesi jadwal)
	        $sqlMapel = "SELECT tm.nama_mapel, MIN(tj.id_jadwal) AS id_jadwal
	                    FROM tbl_jadwal AS tj
	                    JOIN tbl_mapel AS tm ON tm.kd_mapel = tj.kd_mapel
	                    WHERE tj.kd_kelas = ".$this->db->escape($kd_kelas)."
	                      AND tj.id_tahun_akademik = ".$tahun."
	                      AND tj.semester = ".$this->db->escape($semester)."
	                    GROUP BY tj.kd_mapel, tm.nama_mapel
	                    ORDER BY tm.nama_mapel";
	        $mapel = ($kd_kelas !== null) ? $this->db->query($sqlMapel)->result() : array();

	        $kkm = 75;
	        $total = 0; $cnt = 0; $fill = false;
	        $no = 1;
	        $pdf->SetFont('Arial','',8.5);
	        foreach ($mapel as $m){
	            $fill = !$fill;
	            $pdf->SetFillColor(245,247,250);
	            $nilai = check_nilai($siswa['nim'], $m->id_jadwal);
	            $rata  = $this->rata_rata_nilai($m->id_jadwal);
	            $ada   = ($nilai !== 0 && $nilai !== '' && $nilai !== null);
	            if ($ada) { $total += (int) $nilai; $cnt++; }

	            $pdf->Cell(8,6,$no,1,0,'C',$fill);
	            $pdf->Cell(56,6,$m->nama_mapel,1,0,'L',$fill);
	            $pdf->Cell(10,6,$kkm,1,0,'C',$fill);
	            $pdf->Cell(13,6,$ada ? $nilai : '-',1,0,'C',$fill);
	            $pdf->Cell(27,6,$ada ? $this->ketercapaian_kopetensi($nilai) : '-',1,0,'L',$fill);
	            $pdf->Cell(15,6,($rata === null || (float)$rata == 0) ? '-' : ceil((float)$rata),1,0,'C',$fill);
	            $pdf->Cell(51,6,'-',1,1,'L',$fill);
	            $no++;
	        }

	        // baris rata-rata siswa
	        $fill = !$fill;
	        $avg = ($cnt > 0) ? $total / $cnt : 0;
	        $pdf->SetFillColor(226,232,240);
	        $pdf->SetFont('Arial','B',8.5);
	        $pdf->Cell(8,6,'',1,0,'C',$fill);
	        $pdf->Cell(56,6,'RATA-RATA',1,0,'L',$fill);
	        $pdf->Cell(10,6,'',1,0,'C',$fill);
	        $pdf->Cell(13,6,$avg > 0 ? round($avg,1) : '-',1,0,'C',$fill);
	        $pdf->Cell(27,6,$avg > 0 ? $this->ketercapaian_kopetensi(round($avg)) : '-',1,0,'L',$fill);
	        $pdf->Cell(15,6,'',1,0,'C',$fill);
	        $pdf->Cell(51,6,'',1,1,'C',$fill);

	        // ---------- CATATAN WALI KELAS ----------
	        $pdf->Ln(3);
	        $pdf->SetFont('Arial','B',9);
	        $pdf->Cell(0,6,'Catatan Wali Kelas :',0,1,'L');
	        $pdf->SetFillColor(245,247,250);
	        $pdf->Cell(180,20,'',1,0,'L',1);
	        $pdf->Ln(5);

	        // ---------- TANDA TANGAN ----------
	        $pdf->SetFont('Arial','',9);
	        $pdf->Cell(0,6,'Diberikan di : .....................................................',0,1,'R');
	        $pdf->Cell(0,6,'Pada Tanggal : ......................................................',0,1,'R');
	        $pdf->Ln(6);

	        $cw = 60; // tiga kolom x 60 = 180
	        $pdf->SetFont('Arial','B',9);
	        $pdf->Cell($cw,6,'Orang Tua / Wali',0,0,'C');
	        $pdf->Cell($cw,6,'Wali Kelas',0,0,'C');
	        $pdf->Cell($cw,6,'Kepala Sekolah',0,1,'C');
	        $pdf->Ln(18);

	        $pdf->SetFont('Arial','',9);
	        $pdf->Cell($cw,5,'(  .....................................................  )',0,0,'C');
	        $pdf->Cell($cw,5,$this->_nullable($wali['nama_guru'],'-'),0,0,'C');
	        $pdf->Cell($cw,5,$this->_nullable(identitas('kepala_sekolah'),'-'),0,1,'C');
	        $pdf->Ln(2);
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell($cw,5,'',0,0,'C');
	        $navali = !empty($wali['nuptk']) ? 'NIP/NUPTK. '.$wali['nuptk'] : '';
	        $nakep  = trim(identitas('nip_kepala')) !== '' ? 'NIP. '.identitas('nip_kepala') : '';
	        $pdf->Cell($cw,5,$navali,0,0,'C');
	        $pdf->Cell($cw,5,$nakep,0,1,'C');

	        $pdf->Output('Rapor_'.$siswa['nim'].'.pdf', 'I');
	    }

	    private function _nullable($v, $fallback = '-')
	    {
	    	return (trim((string) $v) !== '') ? $v : $fallback;
	    }

	    /** KHS (Kartu Hasil Studi) — mode KAMPUS, semester akademik aktif */
	    function cetak_khs($nim)
	    {
	    	$this->load->library('CFPDF');
	    	$nim = (string) $nim;
	    	$this->db->where('is_aktif', 'Y');
	    	$ta  = $this->db->get('tbl_tahun_akademik')->row_array();
	    	$mhs = $this->_info_kampus($nim);
	    	if (empty($mhs)) { redirect('laporan_nilai'); }

	    	$sql = "SELECT tj.kd_mapel, r.nama AS nama_mapel, tn.sks, tn.nilai
	    	        FROM tbl_nilai AS tn
	    	        JOIN tbl_jadwal AS tj ON tj.id_jadwal = tn.id_jadwal
	    	        JOIN tbl_referensi AS r ON r.kategori = 'MAPEL' AND r.kode = tj.kd_mapel
	    	        WHERE tn.nim = ".$this->db->escape($nim)."
	    	          AND tj.kd_mode = 'KAMPUS'
	    	          AND tj.id_tahun_akademik = ".(int) $ta['id_tahun_akademik']."
	    	          AND tj.semester = ".$this->db->escape($ta['semester'])."
	    	        ORDER BY r.nama";
	    	$rows = $this->db->query($sql)->result();

	    	list($total_sks, $bobot, $cnt) = array(0, 0.0, 0);
	    	foreach ($rows as $r) {
	    		$total_sks += (int) $r->sks;
	    		if ($r->nilai > 0) { $bobot += (float) $r->nilai * (int) $r->sks; $cnt += (int) $r->sks; }
	    	}
	    	$ip = ($cnt > 0) ? $bobot / $cnt : 0;
	    	$ipk = $this->_ipk_kampus($nim);

	    	$pdf = new FPDF('P','mm','A4');
	    	$pdf->AliasNbPages();
	    	$pdf->SetTitle('KHS '.$mhs['nama'].' - '.$nim, true);
	    	$pdf->SetMargins(15, 12, 15);
	    	$pdf->SetAutoPageBreak(true, 15);
	    	$pdf->AddPage();

	    	$this->_kop_kampus($pdf, 'KARTU HASIL STUDI', 'SEMESTER '.strtoupper($ta['semester']).'  TAHUN AKADEMIK '.get_tahun_akademik('tahun_akademik'));

	    	// identitas mahasiswa
	    	$pdf->SetDrawColor(203,213,225);
	    	$pdf->SetFillColor(226,232,240);
	    	$lw = 33; $vw = 57;
	    	$this->_identitas_baris($pdf, $lw, $vw, 'NIM', $mhs['nim'], 'Nama', $mhs['nama']);
	    	$this->_identitas_baris($pdf, $lw, $vw, 'Program Studi', $this->_nullable($mhs['nama_prodi'], '-'), 'Angkatan', $this->_nullable($mhs['angkatan'], '-'));
	    	$this->_identitas_baris($pdf, $lw, $vw, 'Rombongan', $this->_nullable($mhs['nama_kelas'], '-'), 'Semester', ucfirst($ta['semester']));
	    	$pdf->Ln(3);

	    	// tabel nilai
	    	$pdf->SetFont('Arial','B',8.5);
	    	$pdf->SetFillColor(226,232,240);
	    	foreach (array('NO'=>8,'KODE'=>20,'MATA KULIAH'=>72,'SKS'=>12,'NILAI'=>15,'MUTU'=>15,'BOBOT'=>28) as $h=>$w) {
	    		$align = in_array($h, array('NO','SKS','NILAI','MUTU','BOBOT')) ? 'C' : 'L';
	    		$pdf->Cell($w,7,$h,1,0,$align,1);
	    	}
	    	$pdf->Ln(7);

	    	$pdf->SetFont('Arial','',8.5);
	    	$no = 1;
	    	$fill = false;
	    	foreach ($rows as $r) {
	    		$fill = !$fill;
	    		$pdf->SetFillColor(245,247,250);
	    		$mutu = ($r->nilai > 0) ? $this->huruf_mutu($r->nilai) : '-';
	    		$bobot_v = ($r->nilai > 0) ? round($r->nilai * (int) $r->sks, 1) : '-';
	    		$pdf->Cell(8,6,$no++,1,0,'C',$fill);
	    		$pdf->Cell(20,6,$this->_nullable($r->kd_mapel,'-'),1,0,'L',$fill);
	    		$pdf->Cell(72,6,$r->nama_mapel,1,0,'L',$fill);
	    		$pdf->Cell(12,6,$r->sks,1,0,'C',$fill);
	    		$pdf->Cell(15,6,$r->nilai > 0 ? $r->nilai : '-',1,0,'C',$fill);
	    		$pdf->Cell(15,6,$mutu,1,0,'C',$fill);
	    		$pdf->Cell(28,6,$bobot_v,1,1,'C',$fill);
	    	}
	    	$pdf->SetFillColor(226,232,240);
	    	$pdf->SetFont('Arial','B',8.5);
	    	$pdf->Cell(8,6,'',1,0,'C',1);
	    	$pdf->Cell(92,6,'TOTAL SKS',1,0,'L',1);
	    	$pdf->Cell(12,6,$total_sks,1,0,'C',1);
	    	$pdf->Cell(15,6,'',1,0,'C',1);
	    	$pdf->Cell(43,6,'IP : '.number_format($ip,2),1,1,'L',1);

	    	$pdf->Ln(3);
	    	$pdf->SetFont('Arial','',9);
	    	$pdf->Cell(0,6,'IP Kumulatif (IPK) : '.number_format($ipk,2).'   |   Predikat : '.$this->_predikat($ipk),0,1,'L');

	    	$pdf->Ln(6);
	    	$cw = 60;
	    	$pdf->SetFont('Arial','B',9);
	    	$pdf->Cell($cw,6,'Mahasiswa',0,0,'C');
	    	$pdf->Cell($cw,6,'Dosen Wali / Pejabat',0,0,'C');
	    	$pdf->Cell($cw,6,'Mengetahui',0,1,'C');
	    	$pdf->Ln(18);
	    	$pdf->SetFont('Arial','',9);
	    	$pdf->Cell($cw,5,'(  '.$mhs['nama'].'  )',0,0,'C');
	    	$pdf->Cell($cw,5,'(  ............................  )',0,0,'C');
	    	$pdf->Cell($cw,5,'(  '.$this->_nullable(identitas('kepala_sekolah'), '............................').'  )',0,1,'C');

	    	$pdf->Output('KHS_'.$nim.'.pdf', 'I');
	    }

	    /** Transkrip nilai kumulatif — mode KAMPUS */
	    function transkrip($nim)
	    {
	    	$this->load->library('CFPDF');
	    	$nim = (string) $nim;
	    	$mhs = $this->_info_kampus($nim);
	    	if (empty($mhs)) { redirect('laporan_nilai'); }

	    	$sql = "SELECT tj.kd_mapel, r.nama AS nama_mapel, tn.sks, tn.nilai,
	    			        tj.semester, tta.tahun_akademik
	    	        FROM tbl_nilai AS tn
	    	        JOIN tbl_jadwal AS tj ON tj.id_jadwal = tn.id_jadwal
	    	        JOIN tbl_referensi AS r ON r.kategori = 'MAPEL' AND r.kode = tj.kd_mapel
	    	        JOIN tbl_tahun_akademik AS tta ON tta.id_tahun_akademik = tj.id_tahun_akademik
	    	        WHERE tn.nim = ".$this->db->escape($nim)." AND tj.kd_mode = 'KAMPUS'
	    	        ORDER BY tta.tahun_akademik, tj.semester, r.nama";
	    	$rows = $this->db->query($sql)->result();

	    	$total_sks = 0; $bobot = 0.0; $cnt = 0;
	    	foreach ($rows as $r) {
	    		$total_sks += (int) $r->sks;
	    		if ($r->nilai > 0) { $bobot += (float) $r->nilai * (int) $r->sks; $cnt += (int) $r->sks; }
	    	}
	    	$ipk = ($cnt > 0) ? $bobot / $cnt : 0;

	    	$pdf = new FPDF('P','mm','A4');
	    	$pdf->AliasNbPages();
	    	$pdf->SetTitle('Transkrip '.$mhs['nama'].' - '.$nim, true);
	    	$pdf->SetMargins(15, 12, 15);
	    	$pdf->SetAutoPageBreak(true, 15);
	    	$pdf->AddPage();

	    	$this->_kop_kampus($pdf, 'TRANSKRIP NILAI', 'SEMUA SEMESTER - TAHUN AKADEMIK '.get_tahun_akademik('tahun_akademik'));

	    	$pdf->SetDrawColor(203,213,225);
	    	$pdf->SetFillColor(226,232,240);
	    	$lw = 33; $vw = 57;
	    	$this->_identitas_baris($pdf, $lw, $vw, 'NIM', $mhs['nim'], 'Nama', $mhs['nama']);
	    	$this->_identitas_baris($pdf, $lw, $vw, 'Program Studi', $this->_nullable($mhs['nama_prodi'], '-'), 'Angkatan', $this->_nullable($mhs['angkatan'], '-'));
	    	$pdf->Ln(3);

	    	$pdf->SetFont('Arial','B',8.5);
	    	$pdf->SetFillColor(226,232,240);
	    	foreach (array('NO'=>8,'KODE'=>20,'MATA KULIAH'=>62,'SEMESTER'=>22,'TAHUN AKADEMIK'=>30,'SKS'=>12,'NILAI'=>15,'MUTU'=>15) as $h=>$w) {
	    		$align = in_array($h, array('NO','SEMESTER','TAHUN AKADEMIK','SKS','NILAI','MUTU')) ? 'C' : 'L';
	    		$pdf->Cell($w,7,$h,1,0,$align,1);
	    	}
	    	$pdf->Ln(7);

	    	$pdf->SetFont('Arial','',8.5);
	    	$no = 1; $fill = false;
	    	foreach ($rows as $r) {
	    		$fill = !$fill;
	    		$pdf->SetFillColor(245,247,250);
	    		$pdf->Cell(8,6,$no++,1,0,'C',$fill);
	    		$pdf->Cell(20,6,$this->_nullable($r->kd_mapel,'-'),1,0,'L',$fill);
	    		$pdf->Cell(62,6,$r->nama_mapel,1,0,'L',$fill);
	    		$pdf->Cell(22,6,ucfirst($r->semester),1,0,'C',$fill);
	    		$pdf->Cell(30,6,$r->tahun_akademik,1,0,'C',$fill);
	    		$pdf->Cell(12,6,$r->sks,1,0,'C',$fill);
	    		$pdf->Cell(15,6,$r->nilai > 0 ? $r->nilai : '-',1,0,'C',$fill);
	    		$pdf->Cell(15,6,$r->nilai > 0 ? $this->huruf_mutu($r->nilai) : '-',1,1,'C',$fill);
	    	}
	    	$pdf->SetFillColor(226,232,240);
	    	$pdf->SetFont('Arial','B',8.5);
	    	$pdf->Cell(8,6,'',1,0,'C',1);
	    	$pdf->Cell(142,6,'TOTAL',1,0,'L',1);
	    	$pdf->Cell(12,6,$total_sks,1,0,'C',1);
	    	$pdf->Cell(30,6,'IPK : '.number_format($ipk,2),1,1,'C',1);

	    	$pdf->Ln(6);
	    	$cw = 60;
	    	$pdf->SetFont('Arial','B',9);
	    	$pdf->Cell($cw,6,'Mahasiswa',0,0,'C');
	    	$pdf->Cell($cw,6,'Dosen Wali / Pejabat',0,0,'C');
	    	$pdf->Cell($cw,6,'Mengetahui',0,1,'C');
	    	$pdf->Ln(18);
	    	$pdf->SetFont('Arial','',9);
	    	$pdf->Cell($cw,5,'(  '.$mhs['nama'].'  )',0,0,'C');
	    	$pdf->Cell($cw,5,'(  ............................  )',0,0,'C');
	    	$pdf->Cell($cw,5,'(  '.$this->_nullable(identitas('kepala_sekolah'), '............................').'  )',0,1,'C');

	    	$pdf->Output('Transkrip_'.$nim.'.pdf', 'I');
	    }

	    private function _info_kampus($nim)
	    {
	    	$q = $this->db->query("SELECT ts.nim, ts.nama, tk.kd_kelas, tk.nama_kelas,
	    								  rp.nama AS nama_prodi, tk.angkatan
	    						   FROM tbl_siswa AS ts
	    						   JOIN tbl_riwayat_kelas AS trk ON trk.nim = ts.nim
	    						   JOIN tbl_kelas AS tk ON tk.kd_kelas = trk.kd_kelas
	    						   LEFT JOIN tbl_referensi AS rp ON rp.kategori = 'PRODI' AND rp.kode = tk.kd_prodi
	    						   WHERE ts.kd_mode = 'KAMPUS' AND tk.kd_mode = 'KAMPUS' AND ts.nim = ".$this->db->escape($nim)."
	    						   LIMIT 1");
	    	return $q->num_rows() > 0 ? $q->row_array() : array();
	    }

	    private function _ipk_kampus($nim)
	    {
	    	$q = $this->db->query("SELECT ROUND(SUM(tn.nilai * tn.sks) / NULLIF(SUM(CASE WHEN tn.nilai > 0 THEN tn.sks END), 0), 2) AS ipk
	    						   FROM tbl_nilai AS tn
	    						   JOIN tbl_jadwal AS tj ON tj.id_jadwal = tn.id_jadwal
	    						   WHERE tn.nim = ".$this->db->escape($nim)." AND tj.kd_mode = 'KAMPUS' AND tn.nilai > 0");
	    	$r = $q->row_array();
	    	$ipk = isset($r['ipk']) ? (float) $r['ipk'] : 0;
	    	return $ipk > 0 ? $ipk : 0;
	    }

	    private function _predikat($ipk)
	    {
	    	if ($ipk >= 3.51) return 'Cumlaude';
	    	if ($ipk >= 3.01) return 'Sangat Memuaskan';
	    	if ($ipk >= 2.76) return 'Memuaskan';
	    	if ($ipk >= 2.00) return 'Cukup';
	    	return '-';
	    }

	    private function _kop_kampus($pdf, $judul, $subjudul)
	    {
	    	$pdf->SetFont('Arial','B',14);
	    	$pdf->Cell(0,7,strtoupper(identitas('nama_sekolah')),0,1,'C');
	    	$pdf->SetFont('Arial','',9);
	    	$alamat = trim(identitas('alamat'));
	    	$kontak = array();
	    	if (trim(identitas('no_telp')) !== '' && stripos($alamat, identitas('no_telp')) === false) {
	    		$kontak[] = 'Telp. '.identitas('no_telp');
	    	}
	    	if (trim(identitas('email')) !== '') 	$kontak[] = 'Email: '.identitas('email');
	    	if (trim(identitas('website')) !== '') 	$kontak[] = trim(identitas('website'));
	    	// NPSN khas sekolah; untuk kampus sembunyikan
	    	if (meta_mode() !== 'KAMPUS' && trim(identitas('npsn')) !== '') $kontak[] = 'NPSN '.identitas('npsn');
	    	$kop = trim($alamat.(count($kontak) ? ' - '.implode(' - ', $kontak) : ''));
	    	$pdf->Cell(0,5,$kop,0,1,'C');
	    	$y = $pdf->GetY() + 2;
	    	$pdf->SetDrawColor(0,0,0);
	    	$pdf->SetLineWidth(0.8);
	    	$pdf->Line(15,$y,195,$y);
	    	$pdf->SetLineWidth(0.2);
	    	$pdf->Line(15,$y+1,195,$y+1);
	    	$pdf->SetY($y+4);

	    	$pdf->SetFont('Arial','B',12);
	    	$pdf->Cell(0,7,$judul,0,1,'C');
	    	$pdf->SetFont('Arial','B',9);
	    	$pdf->Cell(0,5,$subjudul,0,1,'C');
	    	$pdf->Ln(4);
	    }

	    private function _identitas_baris($pdf, $lw, $vw, $l1, $v1, $l2, $v2)
	    {
	    	$pdf->SetFont('Arial','B',9);
	    	$pdf->Cell($lw,6,$l1,1,0,'L',1);
	    	$pdf->SetFont('Arial','',9);
	    	$pdf->Cell($vw,6,'  '.$v1,1,0,'L');
	    	$pdf->SetFont('Arial','B',9);
	    	$pdf->Cell($lw,6,$l2,1,0,'L',1);
	    	$pdf->SetFont('Arial','',9);
	    	$pdf->Cell($vw,6,'  '.$v2,1,1,'L');
	    }
    
function rata_rata_nilai($id_jadwal){
        $sql   =  "SELECT sum(nilai)/count(nim) as nilai_rata_rata FROM tbl_nilai WHERE id_jadwal=".(int)$id_jadwal;
        $nilai = $this->db->query($sql)->row_array();
        return ($nilai && $nilai['nilai_rata_rata'] !== null) ? $nilai['nilai_rata_rata'] : null;
    }
    
    function huruf_mutu($nilai){
        if ($nilai === 0 || $nilai === '' || $nilai === null) return '-';
        $n = (int) $nilai;
        if ($n >= 90) return 'A';
        if ($n >= 80) return 'B';
        if ($n >= 70) return 'C';
        if ($n >= 60) return 'D';
        return 'E';
    }
	    
	    
	    function ketercapaian_kopetensi($nilai){
	        if($nilai >= 90){
	            return 'Sangat baik';
	        }elseif($nilai >= 80){
	            return 'Baik';
	        }elseif($nilai >= 75){
	            return 'Cukup';
	        }else{
	            return "Perlu bimbingan";
	        }
	    }

	}

?>