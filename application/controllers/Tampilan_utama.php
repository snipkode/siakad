<?php

	class Tampilan_utama extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			checkAksesModule();
		}

		function index()
		{
			$mode = meta_mode();
			$esc  = $this->db->escape($mode);

			$quser = 'SELECT COUNT(*) AS hasil FROM tbl_user';
			$data['user'] = $this->db->query($quser)->row_array();

			// Peserta & pengajar dihitung per mode aktif.
			$qsiswa = "SELECT COUNT(*) AS hasil FROM tbl_siswa WHERE kd_mode = $esc";
			$data['siswa'] = $this->db->query($qsiswa)->row_array();

			$qguru = "SELECT COUNT(*) AS hasil FROM tbl_guru WHERE kd_mode = $esc";
			$data['guru'] = $this->db->query($qguru)->row_array();

			$qruangan = 'SELECT COUNT(*) AS hasil FROM tbl_ruangan';
			$data['ruangan'] = $this->db->query($qruangan)->row_array();

			// Label kartu mengikuti mode.
			$data['label_peserta'] = meta_mode_label('label_peserta');
			$data['label_staf']    = meta_mode_label('label_staf');

			if ($mode === 'KAMPUS') {
				// Chart batang: mahasiswa per angkatan.
				$data['chart1'] = array(
					'title'          => 'Statistik '.meta_mode_label('label_peserta').' per Angkatan',
					'sub'            => 'Jumlah '.strtolower(meta_mode_label('label_peserta')).' pada tiap angkatan',
					'dataset_label'  => 'Jumlah '.meta_mode_label('label_peserta'),
					'data'           => $this->db->query(
						"SELECT IFNULL(NULLIF(TRIM(tk.angkatan),''),'Tanpa Angkatan') AS label, COUNT(*) AS jumlah
						 FROM tbl_siswa ts
						 JOIN tbl_kelas tk ON ts.kd_kelas = tk.kd_kelas
						 WHERE ts.kd_mode = $esc AND tk.kd_mode = $esc
						 GROUP BY tk.angkatan
						 ORDER BY tk.angkatan ASC"
					)->result_array(),
				);

				// Chart donat: mahasiswa per prodi.
				$data['chart2'] = array(
					'title'          => 'Statistik '.meta_mode_label('label_peserta').' per Prodi',
					'sub'            => 'Sebaran '.strtolower(meta_mode_label('label_peserta')).' berdasar program studi',
					'data'           => $this->db->query(
						"SELECT COALESCE(NULLIF(TRIM(r.nama),''), NULLIF(TRIM(tk.kd_prodi),''), 'Tanpa Prodi') AS label, COUNT(*) AS jumlah
						 FROM tbl_siswa ts
						 JOIN tbl_kelas tk ON ts.kd_kelas = tk.kd_kelas
						 LEFT JOIN tbl_referensi r ON r.kategori = 'PRODI' AND r.kode = tk.kd_prodi
						 WHERE ts.kd_mode = $esc AND tk.kd_mode = $esc
						 GROUP BY r.nama, tk.kd_prodi
						 ORDER BY r.nama ASC"
					)->result_array(),
				);
			} else {
				// Chart batang: peserta per tingkatan.
				$data['chart1'] = array(
					'title'          => 'Statistik '.meta_mode_label('label_peserta').' per Tingkatan',
					'sub'            => 'Jumlah '.strtolower(meta_mode_label('label_peserta')).' pada tiap tingkatan kelas',
					'dataset_label'  => 'Jumlah '.meta_mode_label('label_peserta'),
					'data'           => $this->db->query(
						"SELECT tt.nama_tingkatan AS label, COUNT(*) AS jumlah
						 FROM tbl_siswa ts
						 JOIN tbl_kelas tk ON ts.kd_kelas = tk.kd_kelas
						 JOIN tbl_tingkatan_kelas tt ON tk.kd_tingkatan = tt.kd_tingkatan
						 WHERE ts.kd_mode = $esc AND tk.kd_mode = $esc
						 GROUP BY tk.kd_tingkatan, tt.nama_tingkatan
						 ORDER BY tk.kd_tingkatan ASC"
					)->result_array(),
				);

				// Chart donat: peserta per jurusan.
				$data['chart2'] = array(
					'title'          => 'Statistik '.meta_mode_label('label_peserta').' per Jurusan',
					'sub'            => 'Sebaran '.strtolower(meta_mode_label('label_peserta')).' berdasar jurusan kelas',
					'data'           => $this->db->query(
						"SELECT tj.nama_jurusan AS label, COUNT(*) AS jumlah
						 FROM tbl_siswa ts
						 JOIN tbl_kelas tk ON ts.kd_kelas = tk.kd_kelas
						 JOIN tbl_jurusan tj ON tk.kd_jurusan = tj.kd_jurusan
						 WHERE ts.kd_mode = $esc AND tk.kd_mode = $esc
						 GROUP BY tk.kd_jurusan, tj.nama_jurusan
						 ORDER BY tj.nama_jurusan ASC"
					)->result_array(),
				);
			}

			$this->template->load('template', 'dashboard', $data);
		}

	}

?>
