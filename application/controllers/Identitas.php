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
			$data['identitas'] = $this->db->where('kd_mode', meta_mode())->get('tbl_identitas')->row_array();
			$data['label_instansi'] = meta_mode_label('label_instansi');
			$data['label_kepala']   = meta_mode_label('label_kepala');
			$this->template->load('template', 'identitas/form', $data);
		}

		function save()
		{
			$label_instansi = meta_mode_label('label_instansi');
			$data = array(
				'nama_sekolah'  => trim((string) $this->input->post('nama_sekolah')),
				'npsn'          => trim((string) $this->input->post('npsn')),
				'alamat'        => trim((string) $this->input->post('alamat')),
				'no_telp'       => trim((string) $this->input->post('no_telp')),
				'email'         => trim((string) $this->input->post('email')),
				'website'       => trim((string) $this->input->post('website')),
				'kepala_sekolah'=> trim((string) $this->input->post('kepala_sekolah')),
				'nip_kepala'    => trim((string) $this->input->post('nip_kepala')),
				'kd_mode'       => meta_mode()
			);

			if ($data['nama_sekolah'] === '') {
				$this->session->set_flashdata('msg_identitas', 'Nama '.$label_instansi.' wajib diisi.');
				redirect('identitas');
			}

			if ($this->db->where('kd_mode', $data['kd_mode'])->get('tbl_identitas')->num_rows() > 0) {
				$this->db->where('kd_mode', $data['kd_mode']);
				$this->db->update('tbl_identitas', $data);
			} else {
				$this->db->insert('tbl_identitas', $data);
			}

			$this->session->set_flashdata('msg_identitas', 'Identitas '.$label_instansi.' berhasil disimpan.');
			redirect('identitas');
		}
	}

?>