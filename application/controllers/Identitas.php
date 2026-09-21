<?php

	class Identitas extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			//checkAksesModule();
		}

		function index()
		{
			$data['identitas'] = $this->db->get('tbl_identitas')->row_array();
			$this->template->load('template', 'identitas/form', $data);
		}

		function save()
		{
			$data = array(
				'nama_sekolah'  => trim((string) $this->input->post('nama_sekolah')),
				'npsn'          => trim((string) $this->input->post('npsn')),
				'alamat'        => trim((string) $this->input->post('alamat')),
				'no_telp'       => trim((string) $this->input->post('no_telp')),
				'email'         => trim((string) $this->input->post('email')),
				'website'       => trim((string) $this->input->post('website')),
				'kepala_sekolah'=> trim((string) $this->input->post('kepala_sekolah')),
				'nip_kepala'    => trim((string) $this->input->post('nip_kepala'))
			);

			if ($data['nama_sekolah'] === '') {
				$this->session->set_flashdata('msg_identitas', 'Nama sekolah wajib diisi.');
				redirect('identitas');
			}

			if ($this->db->get('tbl_identitas')->num_rows() > 0) {
				$this->db->where('id', 1);
				$this->db->update('tbl_identitas', $data);
			} else {
				$this->db->insert('tbl_identitas', $data);
			}

			$this->session->set_flashdata('msg_identitas', 'Identitas sekolah berhasil disimpan.');
			redirect('identitas');
		}
	}

?>