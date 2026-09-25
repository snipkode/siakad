<?php
 
	class Model_user extends CI_Model
	{

		public $table = "tbl_user";
		
		// mengambil data $username & $password dari hasil parsing controller Auth function check_login() dan mencocokanya dengan data yang ada di database
		function login($username, $password)
		{
			$this->db->where('username', $username);
			$user = $this->db->get('tbl_user')->row_array();
			if (empty($user)) { return null; }
			if (!check_password($password, $user['password'])) { return null; }
			// hash legacy (MD5) langsung diupgrade ke SHA-256 saat login sukses
			if (!hash_equals($user['password'], hash_password($password))) {
				$up = hash_password($password);
				$this->db->where('id_user', $user['id_user'])->update('tbl_user', array('password' => $up));
				$user['password'] = $up;
			}
			return $user;
		}

		function save($foto)
		{
			$data = array(
				//tabel di database => name di form
				'nama_lengkap'            => $this->input->post('nama_lengkap', TRUE),
				'username'          	  => $this->input->post('username', TRUE),
				'password'          	  => hash_password( $this->input->post('password', TRUE) ),
				'id_level_user'           => $this->input->post('level_user', TRUE),
				'foto'					  => $foto
			);
			$this->db->insert($this->table, $data);
		}

		function update($foto)
		{
			if (empty($foto)) {
				$data = array(
					//tabel di database => name di form
					'nama_lengkap'            => $this->input->post('nama_lengkap', TRUE),
					'username'          	  => $this->input->post('username', TRUE),
					'password'          	  => hash_password( $this->input->post('password', TRUE) ),
					'id_level_user'           => $this->input->post('level_user', TRUE),
				);
			} else {
				$data = array(
					//tabel di database => name di form
					'nama_lengkap'            => $this->input->post('nama_lengkap', TRUE),
					'username'          	  => $this->input->post('username', TRUE),
					'password'          	  => hash_password( $this->input->post('password', TRUE) ),
					'id_level_user'           => $this->input->post('level_user', TRUE),
					'foto'					  => $foto
				);
			}		
			$id_user 	= $this->input->post('id_user', TRUE);
			$this->db->where('id_user', $id_user);
			$this->db->update($this->table, $data);
		}

	}

?>