<?php

	class Tampilan_utama extends CI_Controller
	{
		
		function index()
		{
			$quser = 'SELECT COUNT(*) AS hasil FROM tbl_user';
			$data['user'] = $this->db->query($quser)->row_array();

			$qsiswa = 'SELECT COUNT(*) AS hasil FROM tbl_siswa';
			$data['siswa'] = $this->db->query($qsiswa)->row_array();

			$qguru = 'SELECT COUNT(*) AS hasil FROM tbl_guru';
			$data['guru'] = $this->db->query($qguru)->row_array();

			$qruangan = 'SELECT COUNT(*) AS hasil FROM tbl_ruangan';
			$data['ruangan'] = $this->db->query($qruangan)->row_array();

			// data statistik siswa per tingkatan (untuk chart batang)
			$data['chart_tingkatan'] = $this->db->query(
				"SELECT tt.nama_tingkatan AS label, COUNT(*) AS jumlah
				 FROM tbl_siswa ts
				 JOIN tbl_kelas tk ON ts.kd_kelas = tk.kd_kelas
				 JOIN tbl_tingkatan_kelas tt ON tk.kd_tingkatan = tt.kd_tingkatan
				 GROUP BY tk.kd_tingkatan, tt.nama_tingkatan
				 ORDER BY tk.kd_tingkatan ASC"
			)->result_array();

			// data statistik siswa per jurusan (untuk chart donat)
			$data['chart_jurusan'] = $this->db->query(
				"SELECT tj.nama_jurusan AS label, COUNT(*) AS jumlah
				 FROM tbl_siswa ts
				 JOIN tbl_kelas tk ON ts.kd_kelas = tk.kd_kelas
				 JOIN tbl_jurusan tj ON tk.kd_jurusan = tj.kd_jurusan
				 GROUP BY tk.kd_jurusan, tj.nama_jurusan
				 ORDER BY tj.nama_jurusan ASC"
			)->result_array();

			$this->template->load('template', 'dashboard', $data);
		}

	}

?>