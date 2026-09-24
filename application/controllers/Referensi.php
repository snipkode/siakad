<?php

	/**
	 * Referensi
	 *
	 * CRUD terpadu untuk taksonomi umum (tbl_referensi).
	 * Kategori ditentukan oleh parameter URL segment 4, contoh:
	 *   referensi                  -> daftar kategori
	 *   referensi/index/PRODI      -> daftar referensi PRODI
	 *   referensi/data/PRODI       -> endpoint DataTables
	 *   referensi/add/PRODI        -> form tambah
	 *   referensi/edit/PRODI/1     -> form edit
	 *   referensi/delete/PRODI/1   -> hapus
	 */
	class Referensi extends CI_Controller
	{

		/** kategori yang boleh dikelola via modul ini */
		public $kategori_boleh = array(
			'AGAMA'     => 'Agama',
			'TINGKATAN' => 'Tingkatan / Kelompok',
			'JURUSAN'   => 'Jurusan',
			'PRODI'     => 'Program Studi (Kampus)',
			'MAPEL'     => 'Mata Ajar / Mata Kuliah',
			'RUANGAN'   => 'Ruangan',
			'KURIKULUM' => 'Kurikulum',
			'JENIS_BAYAR' => 'Jenis Pembayaran',
			'JALUR_MASUK' => 'Jalur Masuk (Kampus)',
			'STATUS_KAMPUS' => 'Status Mahasiswa',
			'JABATAN_AKADEMIK' => 'Jabatan Akademik',
		);

		function __construct()
		{
			parent::__construct();
			//checkAksesModule();
			$this->load->library('ssp');
		}

		private function _kategori()
		{
			$k = strtoupper((string) $this->uri->segment(3));
			return isset($this->kategori_boleh[$k]) ? $k : '';
		}

		function index()
		{
			$k = $this->_kategori();
			if ($k === '') {
				$data['kategori_boleh'] = $this->kategori_boleh;
				$this->template->load('template', 'referensi/daftar', $data);
				return;
			}
			$data['kategori']      = $k;
			$data['label_kategori']= $this->kategori_boleh[$k];
			$data['is_mapel']      = ($k === 'MAPEL');
			$this->template->load('template', 'referensi/view', $data);
		}

		function data()
		{
			$k = $this->_kategori();
			if ($k === '') {
				exit(json_encode(array('data' => array())));
			}

			$table      = 'tbl_referensi';
			$primaryKey = 'id';

			$columns = array(
				array('db' => 'id', 'dt' => 'id'),
				array('db' => 'kode', 'dt' => 'kode'),
				array('db' => 'nama', 'dt' => 'nama'),
				array('db' => 'atribut_json', 'dt' => 'atribut_json'),
				array(
					'db' => 'id',
					'dt' => 'aksi',
					'formatter' => function($d) {
						$k = strtoupper((string) $this->uri->segment(3));
						return anchor('referensi/edit/'.$k.'/'.$d, '<i class="fa fa-pencil"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" data-placement="top" title="Edit"')
							.' '.anchor('referensi/delete/'.$k.'/'.$d, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus data ini?")\'');
					}
				)
			);

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
			);

			$k_esc = $this->db->escape($k);
			$whereAll = "kategori = {$k_esc}";

			echo json_encode(
				SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll)
			);
		}

		function add()
		{
			$k = $this->_kategori();
			if ($k === '') {
				redirect('referensi');
			}
			if (isset($_POST['submit'])) {
				$data = array(
					'kategori' => $k,
					'kode'     => trim((string) $this->input->post('kode')),
					'nama'     => trim((string) $this->input->post('nama')),
					'urutan'   => (int) $this->input->post('urutan'),
					'is_aktif' => $this->input->post('is_aktif') === 'N' ? 'N' : 'Y',
				);
				$json = array();
				foreach (array('sks', 'akreditasi', 'jenjang') as $kol) {
					$v = trim((string) $this->input->post('attr_'.$kol));
					if ($v !== '') { $json[$kol] = $v; }
				}
				$data['atribut_json'] = count($json) ? json_encode($json) : null;

				if ($data['kode'] === '' || $data['nama'] === '') {
					$this->session->set_flashdata('msg_ref', 'Kode dan nama wajib diisi.');
					redirect('referensi/add/'.$k);
				}
				$ada = $this->db->where('kategori', $k)->where('kode', $data['kode'])->get('tbl_referensi')->num_rows();
				if ($ada > 0) {
					$this->session->set_flashdata('msg_ref', 'Kode sudah dipakai untuk kategori ini.');
					redirect('referensi/add/'.$k);
				}
				$this->db->insert('tbl_referensi', $data);
				$this->session->set_flashdata('msg_ref', 'Data referensi disimpan.');
				redirect('referensi/index/'.$k);
			}
			$data['kategori'] = $k;
			$data['label_kategori'] = $this->kategori_boleh[$k];
			$data['is_mapel'] = ($k === 'MAPEL');
			$this->template->load('template', 'referensi/add', $data);
		}

		function edit()
		{
			$k = $this->_kategori();
			if ($k === '') { redirect('referensi'); }
			$id  = (int) $this->uri->segment(4);
			$ref = $this->db->where('id', $id)->get('tbl_referensi')->row_array();
			if (empty($ref)) { show_404(); }

			if (isset($_POST['submit'])) {
				$data = array(
					'kode'     => trim((string) $this->input->post('kode')),
					'nama'     => trim((string) $this->input->post('nama')),
					'urutan'   => (int) $this->input->post('urutan'),
					'is_aktif' => $this->input->post('is_aktif') === 'N' ? 'N' : 'Y',
				);
				$json = array();
				foreach (array('sks', 'akreditasi', 'jenjang') as $kol) {
					$v = trim((string) $this->input->post('attr_'.$kol));
					if ($v !== '') { $json[$kol] = $v; }
				}
				$data['atribut_json'] = count($json) ? json_encode($json) : null;

				if ($data['kode'] === '' || $data['nama'] === '') {
					$this->session->set_flashdata('msg_ref', 'Kode dan nama wajib diisi.');
					redirect('referensi/edit/'.$k.'/'.$id);
				}
				$duplikat = $this->db->where('kategori', $k)->where('kode', $data['kode'])->where('id !=', $id)->get('tbl_referensi')->num_rows();
				if ($duplikat > 0) {
					$this->session->set_flashdata('msg_ref', 'Kode sudah dipakai untuk kategori ini.');
					redirect('referensi/edit/'.$k.'/'.$id);
				}
				$this->db->where('id', $id)->update('tbl_referensi', $data);
				$this->session->set_flashdata('msg_ref', 'Data referensi diperbarui.');
				redirect('referensi/index/'.$k);
			}

			$attrs = !empty($ref['atribut_json']) ? json_decode($ref['atribut_json'], true) : array();
			$data['ref'] = $ref;
			$data['attrs'] = is_array($attrs) ? $attrs : array();
			$data['kategori'] = $k;
			$data['label_kategori'] = $this->kategori_boleh[$k];
			$data['is_mapel'] = ($k === 'MAPEL');
			$this->template->load('template', 'referensi/edit', $data);
		}

		function delete()
		{
			$k  = $this->_kategori();
			$id = (int) $this->uri->segment(4);
			if ($k !== '' && $id > 0) {
				$this->db->where('id', $id)->delete('tbl_referensi');
			}
			redirect('referensi/index/'.$k);
		}

	}