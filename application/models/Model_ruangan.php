<?php
 
	class Model_ruangan extends CI_Model
	{
		
		public $table = "tbl_ruangan";

		function _mode()
		{
			return get_instance()->meta->mode();
		}

		function save()
		{
			$data = array(
				//tabel di database => name di form
				'kd_ruangan'		=> $this->input->post('kd_ruangan', TRUE),
				'nama_ruangan'		=> $this->input->post('nama_ruangan', TRUE),
				'kd_mode'			=> $this->_mode()
			);
			$this->db->insert($this->table, $data);
		}

		function update()
		{
			$kd_mode = $this->_mode();
			$data = array(
				//tabel di database => name di form
				'nama_ruangan'		=> $this->input->post('nama_ruangan', TRUE)
			);
			$kode_ruangan = $this->input->post('kd_ruangan');
			$this->db->where('kd_ruangan', $kode_ruangan);
			$this->db->where('kd_mode', $kd_mode);
			$this->db->update($this->table, $data);
		}

	}

?>