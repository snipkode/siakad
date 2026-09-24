<?php
 
	class Pembayaran extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			//checkAksesModule();
			$this->load->library('ssp');
			$this->load->model('model_pembayaran');
		}

		function data()
		{

			// nama table
			$table      = 'view_pembayaran';
			// nama PK
			$primaryKey = 'id_pembayaran';
			// list field yang mau ditampilkan
			$columns    = array(
				//tabel db(kolom di database) => dt(nama datatable di view)
				array('db' => 'id_pembayaran', 'dt' => 'id_pembayaran'),
		        array('db' => 'nama', 'dt' => 'nama'),
		        array('db' => 'nim', 'dt' => 'nim'),
		        array('db' => 'jenis_bayar', 'dt' => 'jenis_bayar'),
		        array(
		              'db' => 'jumlah',
		              'dt' => 'jumlah',
		              'formatter' => function($d) {
		               		return 'Rp ' . number_format((int)$d, 0, ',', '.');
		               }
		        ),
		        array('db' => 'tahun_akademik', 'dt' => 'tahun_akademik'),
		        array('db' => 'semester', 'dt' => 'semester'),
		        array(
		              'db' => 'tanggal_bayar',
		              'dt' => 'tanggal_bayar',
		              'formatter' => function($d) {
		               		return date('d-m-Y', strtotime($d));
		              }
		        ),
		        array('db' => 'keterangan', 'dt' => 'keterangan'),
		        //untuk menampilkan aksi(edit/delete dengan parameter id)
		        array(
		              'db' => 'id_pembayaran',
		              'dt' => 'aksi',
		              'formatter' => function($d) {
		               		return anchor('pembayaran/edit/'.$d, '<i class="fa fa-pencil"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" data-placement="top" title="Edit"').' 
		               		'.anchor('pembayaran/delete/'.$d, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus data ini?")\'');
		            }
		        )
		    );

$sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => $this->db->database,
			'host' => $this->db->hostname
	    );

	    // Hanya data mode aktif.
	    $whereAll = "kd_mode = ".$this->db->escape(meta_mode());

	    echo json_encode(
	     	SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll)
	     );

	}

		function index()
		{
			$this->template->load('template', 'pembayaran/view');
		}

		function add()
		{
			$this->load->helper('mylib');
			if (isset($_POST['submit'])) {
				$this->model_pembayaran->save();
				redirect('pembayaran');
			} else {
				$data['siswa']  = $this->db->where('kd_mode', meta_mode())->order_by('nama', 'ASC')->get('tbl_siswa')->result();
				$data['tahun']  = $this->db->where('kd_mode', meta_mode())->order_by('id_tahun_akademik', 'ASC')->get('tbl_tahun_akademik')->result();
				$data['aktif']  = get_tahun_akademik('id_tahun_akademik');
				$this->template->load('template', 'pembayaran/add', $data);
			}
		}

		function edit()
		{
			$this->load->helper('mylib');
			if (isset($_POST['submit'])) {
				$this->model_pembayaran->update();
				redirect('pembayaran');
			} else {
				$id_pembayaran	 = $this->uri->segment(3);
				$data['pembayaran'] = $this->db->get_where('tbl_pembayaran', array('id_pembayaran' => $id_pembayaran))->row_array();
				$data['siswa']  = $this->db->where('kd_mode', meta_mode())->order_by('nama', 'ASC')->get('tbl_siswa')->result();
				$data['tahun']  = $this->db->where('kd_mode', meta_mode())->order_by('id_tahun_akademik', 'ASC')->get('tbl_tahun_akademik')->result();
				$this->template->load('template', 'pembayaran/edit', $data);
			}
		}

		function delete()
		{
			$id_pembayaran = $this->uri->segment(3);
			if (!empty($id_pembayaran)) {
				$this->db->where('id_pembayaran', $id_pembayaran);
				$this->db->delete('tbl_pembayaran');
			}
			redirect('pembayaran');
		}

	}

?>