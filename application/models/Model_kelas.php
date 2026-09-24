<?php
 
	class Model_kelas extends CI_Model
	{
		
		public $table = "tbl_kelas";

		function _mode()
		{
			return get_instance()->meta->mode();
		}

		function save()
		{
			$mode = $this->_mode();
			$data = array(
				//tabel di database => name di form
				'kd_kelas'            => $this->input->post('kd_kelas', TRUE),
				'nama_kelas'          => $this->input->post('nama_kelas', TRUE),
				'kd_tingkatan'		  => (string) $this->input->post('tingkatan', TRUE),
				'kd_jurusan'		  => (string) $this->input->post('jurusan', TRUE),
				'kd_prodi'            => (string) $this->input->post('prodi', TRUE),
				'angkatan'            => (string) $this->input->post('angkatan', TRUE),
				'kd_mode'             => $mode,
			);
			$this->db->insert($this->table, $data);
		}

		function update()
		{
			$data = array(
				//tabel di database => name di form
				'nama_kelas'          => $this->input->post('nama_kelas', TRUE),
				'kd_tingkatan'		  => (string) $this->input->post('tingkatan', TRUE),
				'kd_jurusan'		  => (string) $this->input->post('jurusan', TRUE),
				'kd_prodi'            => (string) $this->input->post('prodi', TRUE),
				'angkatan'            => (string) $this->input->post('angkatan', TRUE),
			);
			$kode_kelas	= $this->input->post('kd_kelas');
			$this->db->where('kd_kelas', $kode_kelas);
			$this->db->update($this->table, $data);
		}

	}

?>