<?php

	/**
	 * KRS (Kartu Rencana Studi) — khusus mode KAMPUS.
	 *
	 * Alur:
	 *   krs                                  -> daftar mahasiswa + ringkasan (v_krs_mahasiswa)
	 *   krs/data                             -> endpoint DataTables daftar mahasiswa
	 *   krs/detail/{nim}                     -> detail KRS mahasiswa (matakuliah + ambil/batal + nilai)
	 *   krs/simpan/{nim}                     -> simpan KRS (POST checkbox + nilai)
	 *   krs/khs/{nim}                        -> cetak KHS (PDF) semester aktif
	 *   krs/generate/{nim}                   -> buat penawaran matakuliah bila belum ada (otomatis saat detail)
	 */
	class Krs extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			checkAksesModule();
			$this->load->library('ssp');
		}

		private function _mode_kampus_guard()
		{
			if (meta_mode() !== 'KAMPUS') {
				redirect('dashboard');
			}
		}

		/** tahun akademik aktif (array penuh) */
		private function _ta()
		{
			$ci =& get_instance();
			$ci->db->where('is_aktif', 'Y');
			$t = $ci->db->get('tbl_tahun_akademik')->row_array();
			return is_array($t) ? $t : array();
		}

		function index()
		{
			$this->_mode_kampus_guard();
			$this->template->load('template', 'krs/list');
		}

		function data()
		{
			$this->_mode_kampus_guard();
			$table        = 'v_krs_mahasiswa';
			$primaryKey   = 'nim';

			$columns = array(
				array('db' => 'nim', 'dt' => 'nim'),
				array('db' => 'nama', 'dt' => 'nama'),
				array('db' => 'nama_prodi', 'dt' => 'nama_prodi'),
				array('db' => 'angkatan', 'dt' => 'angkatan'),
				array('db' => 'nama_kelas', 'dt' => 'nama_kelas'),
				array('db' => 'sks_diambil', 'dt' => 'sks_diambil'),
				array('db' => 'ip', 'dt' => 'ip'),
				array('db' => 'ipk', 'dt' => 'ipk'),
				array(
					'db' => 'nim',
					'dt' => 'aksi',
					'formatter' => function($d) {
						return anchor('krs/detail/'.$d, '<i class="fa fa-list-alt"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" data-placement="top" title="KRS / KHS"');
					}
				)
			);

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
			);

			$whereAll = "kd_mode = ".$this->db->escape(meta_mode());

			echo json_encode(
				SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll)
			);
		}

		/**
		 * Buat penawaran matakuliah (tbl_jadwal KAMPUS) untuk rombongan mahasiswa
		 * pada tahun akademik & semester aktif, jika belum ada.
		 */
		private function _pastikan_penawaran($kd_kelas)
		{
			$ta   = $this->_ta();
			$jml  = $this->db->where('kd_kelas', $kd_kelas)
			                  ->where('id_tahun_akademik', (int) $ta['id_tahun_akademik'])
			                  ->where('semester', $ta['semester'])
			                  ->where('kd_mode', 'KAMPUS')
			                  ->from('tbl_jadwal')->count_all_results();
			if ($jml > 0) {
				return;
			}
			$mapel = $this->db->where('kategori', 'MAPEL')->where('is_aktif', 'Y')->order_by('urutan', 'asc')->get('tbl_referensi')->result();
			foreach ($mapel as $m) {
				$this->db->insert('tbl_jadwal', array(
					'id_tahun_akademik' => (int) $ta['id_tahun_akademik'],
					'semester'          => $ta['semester'],
					'kd_jurusan'        => '',
					'kd_tingkatan'      => '',
					'kd_kelas'          => $kd_kelas,
					'kd_mapel'          => $m->kode,
					'id_guru'           => 0,
					'jam'               => '',
					'kd_ruangan'        => '000',
					'hari'              => '',
					'kd_mode'           => 'KAMPUS',
				));
			}
		}

		function detail()
		{
			$this->_mode_kampus_guard();
			$nim = $this->uri->segment(3);
			if ($nim === '') { redirect('krs'); }

			$data['siswa'] = $this->_infomhs($nim);
			if (empty($data['siswa'])) {
				$this->session->set_flashdata('msg_krs', 'Mahasiswa tidak ditemukan / bukan mahasiswa aktif.');
				redirect('krs');
			}

			// pastikan penawaran matakuliah sudah ada utk rombongan semester ini
			$this->_pastikan_penawaran($data['siswa']['kd_kelas']);

			$ta = $this->_ta();
			$sql = "SELECT tj.id_jadwal, tj.kd_mapel, r.nama AS nama_mapel, r.atribut_json,
					        tn.id_nilai, tn.nilai, tn.sks
					FROM tbl_jadwal AS tj
					JOIN tbl_referensi AS r ON r.kategori = 'MAPEL' AND r.kode = tj.kd_mapel
					LEFT JOIN tbl_nilai AS tn ON tn.id_jadwal = tj.id_jadwal AND tn.nim = ".$this->db->escape($nim)."
					WHERE tj.kd_kelas = ".$this->db->escape($data['siswa']['kd_kelas'])."
					  AND tj.id_tahun_akademik = ".(int) $ta['id_tahun_akademik']."
					  AND tj.semester = ".$this->db->escape($ta['semester'])."
					  AND tj.kd_mode = 'KAMPUS'
					ORDER BY r.urutan, r.nama";
			$rows = $this->db->query($sql)->result_array();

			// sks snapshot: dari referensi mapel (attribute), untuk penawaran tanpa KRS
			foreach ($rows as &$r) {
				$attr = !empty($r['atribut_json']) ? json_decode($r['atribut_json'], true) : array();
				$r['sks_mapel'] = is_array($attr) && isset($attr['sks']) ? (int) $attr['sks'] : 0;
				if ($r['sks'] == 0 && $r['id_nilai'] !== null) {
					$r['sks'] = $r['sks_mapel'];
				}
			}
			unset($r);

			$data['matakuliah'] = $rows;
			$data['ta']  = $ta;
			$total_sks   = 0; $bobot = 0.0; $cnt = 0;
			foreach ($rows as $r) {
				if ($r['id_nilai'] === null) { continue; }
				$total_sks += (int) $r['sks'];
				if ($r['nilai'] > 0) { $bobot += (float) $r['nilai'] * (int) $r['sks']; $cnt += (int) $r['sks']; }
			}
			$data['total_sks'] = $total_sks;
			$data['ip']        = $cnt > 0 ? round($bobot / $cnt, 2) : 0;
			$data['ipk']       = $this->_ipk($nim);

			$this->template->load('template', 'krs/detail', $data);
		}

		private function _infomhs($nim)
		{
			$ta = $this->_ta();
			$q = $this->db->query("SELECT ts.nim, ts.nama, ts.gender, tk.kd_kelas, tk.nama_kelas,
										  rp.nama AS nama_prodi, tk.angkatan, tk.kd_prodi
								   FROM tbl_siswa AS ts
								   JOIN tbl_riwayat_kelas AS trk ON trk.nim = ts.nim
								   JOIN tbl_kelas AS tk ON tk.kd_kelas = trk.kd_kelas
								   LEFT JOIN tbl_referensi AS rp ON rp.kategori = 'PRODI' AND rp.kode = tk.kd_prodi
								   WHERE ts.kd_mode = 'KAMPUS' AND tk.kd_mode = 'KAMPUS'
								     AND ts.nim = ".$this->db->escape($nim)."
								     AND trk.id_tahun_akademik = ".(int) $ta['id_tahun_akademik']."
								   LIMIT 1");
			return $q->num_rows() > 0 ? $q->row_array() : array();
		}

		private function _ipk($nim)
		{
			$q = $this->db->query("SELECT ROUND(SUM(tn.nilai * tn.sks) / NULLIF(SUM(CASE WHEN tn.nilai > 0 THEN tn.sks END), 0), 2) AS ipk
								   FROM tbl_nilai AS tn
								   JOIN tbl_jadwal AS tj ON tj.id_jadwal = tn.id_jadwal
								   WHERE tn.nim = ".$this->db->escape($nim)." AND tj.kd_mode = 'KAMPUS' AND tn.nilai > 0");
			$r = $q->row_array();
			$ipk = isset($r['ipk']) ? (float) $r['ipk'] : 0;
			return $ipk > 0 ? $ipk : 0;
		}

		function simpan()
		{
			$this->_mode_kampus_guard();
			$nim = $this->uri->segment(3);
			$siswa = $this->_infomhs($nim);
			if (empty($siswa)) { redirect('krs'); }

			$ambil = (array) $this->input->post('ambil');
			$nilai = (array) $this->input->post('nilai');

			foreach ($ambil as $id_jadwal => $on) {
				$id_jadwal = (int) $id_jadwal;
				$jd = $this->db->where('id_jadwal', $id_jadwal)
				               ->where('kd_kelas', $siswa['kd_kelas'])
				               ->where('kd_mode', 'KAMPUS')
				               ->get('tbl_jadwal')->row_array();
				if (empty($jd)) { continue; }

				$attr = $this->_sks_mapel($jd['kd_mapel']);
				$n    = isset($nilai[$id_jadwal]) ? (int) $nilai[$id_jadwal] : 0;
				$n    = max(0, min(100, $n));

				$ada = $this->db->where('id_jadwal', $id_jadwal)->where('nim', $nim)->get('tbl_nilai')->row_array();
				$save = array('nilai' => $n, 'sks' => $attr, 'kd_mode' => 'KAMPUS');
				if (!empty($ada)) {
					$this->db->where('id_nilai', $ada['id_nilai'])->update('tbl_nilai', $save);
				} else {
					$save['nim'] = $nim;
					$save['id_jadwal'] = $id_jadwal;
					$this->db->insert('tbl_nilai', $save);
				}
			}

			// matakuliah yang sebelumnya diambil tapi sekarang tidak dicentang -> dihapus
			$ta   = $this->_ta();
			$semua = $this->db->select('id_jadwal')
			                  ->where('kd_kelas', $siswa['kd_kelas'])
			                  ->where('id_tahun_akademik', (int) $ta['id_tahun_akademik'])
			                  ->where('semester', $ta['semester'])
			                  ->where('kd_mode', 'KAMPUS')
			                  ->get('tbl_jadwal')->result_array();
			$semua_ids = array_map(function($r) { return (int) $r['id_jadwal']; }, $semua);
			$diambil_ids = array_map('intval', array_keys($ambil));
			$hapus_ids = array_diff($semua_ids, $diambil_ids);
			if (!empty($hapus_ids)) {
				$this->db->where_in('id_jadwal', $hapus_ids)->where('nim', $nim)->delete('tbl_nilai');
			}

			$this->session->set_flashdata('msg_krs', 'KRS berhasil disimpan.');
			redirect('krs/detail/'.$nim);
		}

		private function _sks_mapel($kd_mapel)
		{
			$m = $this->db->where('kategori', 'MAPEL')->where('kode', $kd_mapel)->get('tbl_referensi')->row_array();
			$attr = !empty($m['atribut_json']) ? json_decode($m['atribut_json'], true) : array();
			return is_array($attr) && isset($attr['sks']) ? (int) $attr['sks'] : 0;
		}

	}

?>