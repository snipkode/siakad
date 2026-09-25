<?php

	class Profil extends CI_Controller
	{

		function __construct()
		{
			parent::__construct();
			$this->load->model('model_user');
			$this->load->model('model_guru');
		}

		function index()
		{
			$this->update_profile();
		}

		private function _is_guru()
		{
			return (bool) $this->session->userdata('id_guru');
		}

		private function _profil()
		{
			if ($this->_is_guru()) {
				return $this->db->get_where('tbl_guru', array('id_guru' => $this->session->userdata('id_guru')))->row_array();
			}
			return $this->db->get_where('tbl_user', array('id_user' => $this->session->userdata('id_user')))->row_array();
		}

		function update_profile()
		{
			$data['profil'] = $this->_profil();
			$this->template->load('template', 'profil/update_profile', $data);
		}

		function change_password()
		{
			$this->template->load('template', 'profil/change_password');
		}

		function save_profile()
		{
			if ($this->_is_guru()) {
				$this->model_guru->update_profil();
			} else {
				$this->model_user->update_profil();
			}
			$this->session->set_flashdata('sukses_profil', 'Profil berhasil diperbarui.');
			redirect('profil/update_profile');
		}

		function save_password()
		{
			$current = (string) $this->input->post('current_password');
			$new     = (string) $this->input->post('new_password');
			$confirm = (string) $this->input->post('confirm_password');

			if ($new === '' || $new !== $confirm) {
				$this->session->set_flashdata('gagal_password', 'Password baru tidak boleh kosong dan harus sama dengan konfirmasinya.');
				redirect('profil/change_password');
			}
			if (!check_password($current, $this->_profil()['password'])) {
				$this->session->set_flashdata('gagal_password', 'Password lama salah.');
				redirect('profil/change_password');
			}

			$hash = hash_password($new);
			if ($this->_is_guru()) {
				$this->model_guru->set_password($hash);
			} else {
				$this->model_user->set_password($hash);
			}
			$this->session->set_userdata('password', $hash);
			$this->session->set_flashdata('sukses_profil', 'Password berhasil diganti.');
			redirect('profil/change_password');
		}

	}