<?php

	class Kelas extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			checkAksesModule();
			$this->load->library('ssp');
			$this->load->model('model_kelas');
		}

		function data()
		{
			// nama table
			$table      = 'view_kelas_meta';
			// nama PK
			$primaryKey = 'kd_kelas';
			$mode       = $this->model_kelas->_mode();

			$columns = array(
				array('db' => 'kd_kelas', 'dt' => 'kd_kelas'),
		        array('db' => 'nama_kelas', 'dt' => 'nama_kelas'),
		        array(
		              'db' => 'kd_kelas',
		              'dt' => 'aksi',
		              'formatter' => function($d) {
		               		return anchor('kelas/edit/'.$d, '<i class="fa fa-pencil"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" data-placement="top" title="Edit"').' 
		               		'.anchor('kelas/delete/'.$d, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus data ini?")\'');
		            }
		        )
		    );

			if ($mode === 'KAMPUS') {
				array_splice($columns, 2, 0, array(
					array('db' => 'nama_prodi', 'dt' => 'nama_prodi'),
					array('db' => 'angkatan',   'dt' => 'angkatan'),
				));
			} else {
				array_splice($columns, 2, 0, array(
					array('db' => 'nama_tingkatan', 'dt' => 'nama_tingkatan'),
					array('db' => 'nama_jurusan',   'dt' => 'nama_jurusan'),
				));
			}

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
		    );

		    $whereAll = "kd_mode = ".$this->db->escape($mode);

		    echo json_encode(
		     	SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll)
		     );

		}

		function index()
		{
			$this->template->load('template', 'kelas/view');
		}

		function add()
		{
			if (isset($_POST['submit'])) {
				$this->model_kelas->save();
				redirect('kelas');
			} else {
				$this->template->load('template', 'kelas/add');
			}
		}

		function edit()
		{
			if (isset($_POST['submit'])) {
				$this->model_kelas->update();
				redirect('kelas');
			} else {
				$kd_kelas 		= $this->uri->segment(3);
				$data['kelas']	= $this->db->get_where('tbl_kelas', array('kd_kelas' => $kd_kelas))->row_array();
				$this->template->load('template', 'kelas/edit', $data);
			}
		}

		function delete()
		{
			$kode_kelas = $this->uri->segment(3);
			if (!empty($kode_kelas)) {
				$this->db->where('kd_kelas', $kode_kelas);
				$this->db->delete('tbl_kelas');
			}
			redirect('kelas');
		}


		// siswa_aktif() -> untuk menampilkan view peserta didik ->terletak di controller Siswa
		// combobox_kelas() -> untuk menampilkan data kelas sesuai jurusan yang dipilih -> terletak di controller Kelas
		// loadDataSiswa() -> untuk menampilkan data siswa nim dan nama sesuai kode_kelas yang dipilih di filter, lalu ditampilkan ke div id = kelas yang bedada di view/siswa_aktif -> terletak di controller Siswa
		function combobox_kelas()
		{
			$jurusan = $this->input->get('kd_jurusan');
			echo "<select id='cbkelas' name='kelas' onChange='loadSiswa()' class='block w-full rounded-lg border border-slate-300 bg-white px-2.5 py-2 text-sm text-slate-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100'>";

			$this->db->where('kd_jurusan', $jurusan);
			$kelas = $this->db->get('tbl_kelas');
			if ($kelas->num_rows() == 0) {
				echo "<option value=''>-- Pilih kelas --</option>";
			}
			foreach ($kelas->result() as $row) {
				echo "<option value='$row->kd_kelas'>$row->nama_kelas</option>";
			}

			echo "</select>";
		}

	}

?>