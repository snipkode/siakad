<?php
 
	class Model_pembayaran extends CI_Model
	{
		
		public $table = "tbl_pembayaran";

		function save()
		{
			$data = array(
				//tabel di database => name di form
				'nim'			=> $this->input->post('nim', TRUE),
				'jenis_bayar'		=> $this->input->post('jenis_bayar', TRUE),
				'jumlah'		=> $this->input->post('jumlah', TRUE),
				'id_tahun_akademik'	=> $this->input->post('id_tahun_akademik', TRUE),
				'tanggal_bayar'		=> $this->input->post('tanggal_bayar', TRUE),
				'keterangan'		=> $this->input->post('keterangan', TRUE),
				'kd_mode'		=> meta_mode()
			);
			$this->db->insert($this->table, $data);
		}

		function update()
		{
			$data = array(
				//tabel di database => name di form
				'jenis_bayar'		=> $this->input->post('jenis_bayar', TRUE),
				'jumlah'		=> $this->input->post('jumlah', TRUE),
				'id_tahun_akademik'	=> $this->input->post('id_tahun_akademik', TRUE),
				'tanggal_bayar'		=> $this->input->post('tanggal_bayar', TRUE),
				'keterangan'		=> $this->input->post('keterangan', TRUE)
			);
			$id_pembayaran = $this->input->post('id_pembayaran');
			$this->db->where('id_pembayaran', $id_pembayaran);
			$this->db->update($this->table, $data);
		}

	}

?>