<?php
 
	class Laporan_nilai extends CI_Controller
	{
		
		function index()
		{
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
	       
	        $this->load->library('CFPDF');
	        $pdf = new FPDF('P','mm','A4');
	        $pdf->AddPage();
	        $pdf->SetFont('Arial','B',12);
	        $pdf->Cell(190,5,'NAMA SEKOLAH',1,1,'C');
	        $pdf->SetFont('Arial','B',14);
	        $pdf->Cell(190,7,identitas('nama_sekolah'),1,1,'C');
	        $pdf->SetFont('Arial','',8);
	        $pdf->Cell(190,5,identitas('alamat'),1,1,'C');
	         
	        $pdf->Cell(190,5,'',0,1);
	        
	        $pdf->SetFont('Arial','B',9);
	        // BLOCK INFO SISWA
	        $pdf->Cell(30,5,'NIS',0,0,'L');
	        $pdf->Cell(88,5,': '.$siswa['nim'],0,0,'L');
	        $pdf->Cell(30,5,'KELAS',0,0,'L');
	        $pdf->Cell(40,5,': '.$siswa['nama_kelas'],0,1,'L');
	        
	        $pdf->Cell(30,5,'NAMA',0,0,'L');
	        $pdf->Cell(88,5,': '.$siswa['nama_siswa'],0,0,'L');
	        $pdf->Cell(30,5,'TAHUN AJARAN',0,0,'L');
	        $pdf->Cell(40,5,': '.  get_tahun_akademik('tahun_akademik'),0,1,'L');
	        
	        $pdf->Cell(30,5,'JURUSAN',0,0,'L');
	        $pdf->Cell(88,5,': '.$siswa['nama_jurusan'],0,0,'L');
	        $pdf->Cell(30,5,'SEMESTER',0,0,'L');
	        $pdf->Cell(40,5,': '.  get_tahun_akademik('semester'),0,1,'L');
	        
	        // END BLOCK INFO SISWA
	        
	        
	        // BLOCK NILAI SISWA ------------------------
	        $pdf->SetAutoPageBreak(true, 15);
	        $pdf->Cell(1,10,'',0,1);
	        $pdf->Cell(8,5,'NO',1,0,'L');
	        $pdf->Cell(50,5,'Mata Pelajaran',1,0,'L');
	        $pdf->Cell(10,5,'KKM',1,0,'L');
	        $pdf->Cell(12,5,'Angka',1,0,'L');
	        $pdf->Cell(30,5,'Huruf',1,0,'L');
	        $pdf->Cell(23,5,'Ketercapaian',1,0,'L');
	        $pdf->Cell(20,5,'Rata Kelas',1,0,'L');
	        $pdf->Cell(37,5,'Deskripsi Kemampuan',1,1,'L');
	        $pdf->SetFont('Arial','',9);

	        // daftar mapel UNIK milik kelas siswa pada semester aktif (bukan semua sesi jadwal)
	        $sqlMapel = "SELECT tm.nama_mapel, MIN(tj.id_jadwal) AS id_jadwal
	                    FROM tbl_jadwal AS tj
	                    JOIN tbl_mapel AS tm ON tm.kd_mapel = tj.kd_mapel
	                    WHERE tj.kd_kelas = ".$this->db->escape($kd_kelas)." AND tj.semester = ".$this->db->escape($semester)."
	                    GROUP BY tj.kd_mapel, tm.nama_mapel
	                    ORDER BY tm.nama_mapel";
	        $mapel = ($kd_kelas !== null) ? $this->db->query($sqlMapel)->result() : array();
	        $no=1;
	        foreach ($mapel as $m){
	            $pdf->Cell(8,5,$no,1,0,'L');
	            $pdf->Cell(50,5,$m->nama_mapel,1,0,'L');
	            $pdf->Cell(10,5,75,1,0,'L');
	            $nilai = check_nilai($siswa['nim'], $m->id_jadwal);
	            $rata  = $this->rata_rata_nilai($m->id_jadwal);
	            $pdf->Cell(12,5,  ($nilai === 0 || $nilai === '') ? '-' : $nilai,1,0,'L');
	            $pdf->Cell(30,5,  $this->huruf_mutu($nilai),1,0,'L');
	            $pdf->Cell(23,5,  ($nilai === 0 || $nilai === '') ? '-' : $this->ketercapaian_kopetensi($nilai),1,0,'L');
	            $pdf->Cell(20,5,  ($rata === null || (float)$rata == 0) ? '-' : ceil((float)$rata),1,0,'L');
	            $pdf->Cell(37,5,'Deskripsi Kemampuan',1,1,'L');
	            $no++;
	    }
	    // END BLOCK NILAI SISWA --------------------------------
	        
	        $pdf->Cell(190,5,'',0,1);
	        $pdf->Cell(8, 5, 'No', 1,0);
	        $pdf->Cell(50, 5, 'Pengembangan Diri', 1,0);
	        $pdf->Cell(10, 5, 'Nilai', 1,0);
	        $pdf->Cell(66, 5, 'Kepribadian', 1,0);
	        $pdf->Cell(20, 5, 'Niilai', 1,0);
	        $pdf->Cell(36, 5, 'Catatan Khusus', 1,1);
	        
	        $pdf->Cell(190,5,'',0,1);
	        $pdf->Cell(45, 15, 'Mengetahui,', 0,0,'C');
	        $pdf->Cell(87, 5, '', 0,0,'c');
	        $pdf->Cell(25, 5, 'Diberikan Di', 0,0,'c');
	        $pdf->Cell(33, 5, ': ', 0,1,'L');
	        $pdf->Cell(45, 15, 'Orang Tua Wali', 0,0,'C');
	        $pdf->Cell(87, 5, '', 0,0,'c');
	        $pdf->Cell(25, 5, 'Pada', 0,0,'c');
	        $pdf->Cell(33, 5, ': ', 0,1,'L');
	        $pdf->Cell(132, 5, '', 0,0,'c');
	        $pdf->Cell(25, 5, 'Wali Kelas', 0,0,'c');
	        $pdf->Cell(33, 5, ': ', 0,1,'L');
	        $pdf->Output();
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
	        if($nilai>90){
	            return 'Sangat baik';
	        }elseif($nilai>80 and $nilai<=90){
	            return 'Baik';
	        }elseif($nilai>75 and $nilai<=80){
	            return 'Cukup';
	        }else{
	            return "Kurang";
	        }
	    }

	}

?>