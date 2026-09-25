<?php

	class Mapel extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			checkAksesModule();
			$this->load->library('ssp');
			$this->load->model('model_mapel');
		}

		function data()
		{

			// nama table
			$table      = 'tbl_mapel';
			// nama PK
			$primaryKey = 'kd_mapel';
			// list field yang mau ditampilkan
			$columns    = array(
				//tabel db(kolom di database) => dt(nama datatable di view)
				array('db' => 'kd_mapel', 'dt' => 'kd_mapel'),
		        array('db' => 'nama_mapel', 'dt' => 'nama_mapel'),
		        //untuk menampilkan aksi(edit/delete dengan parameter kode mapel)
		        array(
		              'db' => 'kd_mapel',
		              'dt' => 'aksi',
		              'formatter' => function($d) {
		               		return anchor('mapel/edit/'.$d, '<i class="fa fa-pencil"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" data-placement="top" title="Edit"').' 
		               		'.anchor('mapel/delete/'.$d, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus '.$this->meta->mode_label('label_mata_ajar').' ini?")\'');
		            }
		        )
		    );

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
		    );

		    $whereAll = "kd_mode = ".$this->db->escape($this->meta->mode());

		    echo json_encode(
		     	SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll)
		     );

		}

		function index()
		{
			$this->template->load('template', 'mapel/view');
		}

		function add()
		{
			if (isset($_POST['submit'])) {
				$this->model_mapel->save();
				redirect('mapel');
			} else {
				$this->template->load('template', 'mapel/add');
			}
		}

		function edit()
		{
			if (isset($_POST['submit'])) {
				$this->model_mapel->update();
				redirect('mapel');
			} else {
				$kd_mapel 		= $this->uri->segment(3);
				$data['mapel'] 	= $this->db->get_where('tbl_mapel', array('kd_mapel' => $kd_mapel, 'kd_mode' => $this->meta->mode()))->row_array();
				if (empty($data['mapel'])) { show_404(); }
				$this->template->load('template', 'mapel/edit', $data);
			}
		}

		function delete()
		{
			$kode_mapel = $this->uri->segment(3);
			if (!empty($kode_mapel)) {
				$kd_mode = $this->meta->mode();
				$this->db->where('kd_mapel', $kode_mapel);
				$this->db->where('kd_mode', $kd_mode);
				$this->db->delete('tbl_mapel');
			}
			redirect('mapel');
		}

	}

?>