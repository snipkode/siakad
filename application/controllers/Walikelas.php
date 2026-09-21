<?php
 
	class Walikelas extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			//checkAksesModule();
			$this->load->library('ssp');
			//$this->load->model();
		}

		function data()
		{
		// $sql = "SELECT tg.nama_guru, tk.nama_kelas, tw.id_walikelas, tw.id_tahun_akademik, tj.nama_jurusan, 				ttk.nama_tingkatan, tta.tahun_akademik
		// 	FROM tbl_walikelas AS tw, tbl_kelas AS tk, tbl_guru AS tg, tbl_jurusan AS tj, tbl_tingkatan_kelas AS ttk, 		tbl_tahun_akademik AS tta
		// 	WHERE tw.kd_kelas = tk.kd_kelas AND tw.id_guru = tg.id_guru AND tk.kd_jurusan = tj.kd_jurusan AND 				tk.kd_tingkatan = ttk.kd_tingkatan AND tw.id_tahun_akademik = tta.id_tahun_akademik"

		// Biasanya menggunakan query untuk mengambil nama dari tabel yang berbeda tapi saling berelasi,
		// karena terlalu panjang, harus menggunakan foreach lagi dan menurut saya sepertinya 
		//tidak bisa melakukan foreach di datatable, maka saya menggunaka create view kita bisa membuat query tersebut menjadi sebuah table

			// nama table
			$table      = 'view_walikelas';
			// nama PK
			$primaryKey = 'id_walikelas';
			// list field yang mau ditampilkan
			$columns    = array(
				//tabel db(kolom di database) => dt(nama datatable di view)
				array('db' => 'nama_kelas', 'dt' => 'nama_kelas'),
		        array('db' => 'nama_jurusan', 'dt' => 'nama_jurusan'),
		        array('db' => 'nama_tingkatan', 'dt' => 'nama_tingkatan'),
		        // menampilkan combobox berisi guru
		        array(
		        	 'db' => 'id_walikelas', 
		        	 'dt' => 'nama_guru',
		        	 'formatter' => function ($d) {
		        	 	$walikelas = $this->db->get_where('tbl_walikelas',array('id_walikelas'=>$d))->row_array();

		        	 	$select_class = "block w-full max-w-[180px] rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs font-semibold text-slate-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-100";

	                  	return cmb_dinamis('guru', 'tbl_guru', 'nama_guru', 'id_guru', $walikelas['id_guru'], "id='guru$d' onchange='updateWalikelas($d)'", $select_class);
		        	 }
		        	),
		    );

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
		    );

			// mengambil nilai tahun akademik berdasarkan tahun akademik aktif menggunakan helper get_tahun_akademik()
		    $tahunAK = get_tahun_akademik('tahun_akademik');
		    $where = $tahunAK !== '' ? "tahun_akademik='".$tahunAK."'" : '';

		    // karena memasukan parameter $where maka meggunakan ssp::complex bukan yang simple lagi
		    echo json_encode(
		     	SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, $where)
		     );

		}

		function index()
		{
			$this->template->load('template', 'walikelas/view');
		}

		function update_walikelas()
		{
			$id_walikelas = (int) $this->input->get('id_walikelas');
			$id_guru	  = (int) $this->input->get('id_guru');

			if (empty($id_walikelas)) {
				$this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'error', 'message' => 'ID walikelas tidak valid')));
				return;
			}

			$this->db->where('id_walikelas', $id_walikelas);
			$this->db->update('tbl_walikelas', array('id_guru' => $id_guru));

			$message = $this->db->affected_rows() > 0 ? 'Data wali kelas berhasil diperbarui' : 'Tidak ada perubahan data';

			$this->output->set_content_type('application/json')->set_output(json_encode(array('status' => 'success', 'message' => $message)));
		}

	}

?>