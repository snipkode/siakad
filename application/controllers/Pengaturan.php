<?php

	class Pengaturan extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			checkAksesModule();
		}

		function index()
		{
			$data['mode_aktif']  = $this->meta->mode();
			$data['modes']       = $this->meta->modes();
			$data['kkm']         = $this->meta->kkm();
			$data['identitas']   = $this->db->get('tbl_identitas')->row_array();
			$this->template->load('template', 'pengaturan/form', $data);
		}

		function ganti_mode()
		{
			$kd_mode = trim((string) $this->input->post('mode_aktif'));
			if ($kd_mode === '') {
				$this->session->set_flashdata('msg_pengaturan', 'Mode belum dipilih.');
				redirect('pengaturan');
			}
			if (!$this->meta->set_mode($kd_mode)) {
				$this->session->set_flashdata('msg_pengaturan', 'Mode tidak valid.');
				redirect('pengaturan');
			}
			$this->session->set_flashdata('msg_pengaturan', 'Mode berhasil diganti ke '.$kd_mode.'. Menu & label menyesuaikan.');
			redirect('pengaturan');
		}

		function atur_kkm()
		{
			$kkm = (int) $this->input->post('kkm');
			if ($kkm < 0 || $kkm > 100) {
				$this->session->set_flashdata('msg_pengaturan', 'KKM harus antara 0-100.');
				redirect('pengaturan');
			}
			$this->meta->set_setting('kkm_default', (string) $kkm);
			$this->session->set_flashdata('msg_pengaturan', 'KKM default diperbarui.');
			redirect('pengaturan');
		}

	}