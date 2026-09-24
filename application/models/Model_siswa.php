<?php

	class Model_siswa extends CI_Model
	{

		public $table = "tbl_siswa";

		function _meta()
		{
			return get_instance()->meta;
		}

		function _mode()
		{
			return get_instance()->meta->mode();
		}

		/**
		 * Definisi field EAV (is_eav=Y) yang berlaku untuk mode aktif.
		 */
		function eav_fields()
		{
			$out = array();
			foreach ($this->_meta()->fields('peserta') as $kd => $f) {
				if ($f['is_eav'] === 'Y') {
					$out[$kd] = $f;
				}
			}
			return $out;
		}

		/**
		 * Simpan atribut EAV hasil submit form (prefix name: eav_<kd_field>).
		 */
		function simpan_eav($id_induk)
		{
			$meta = $this->_meta();
			foreach ($this->eav_fields() as $kd => $f) {
				$nilai = (string) $this->input->post('eav_'.$kd, TRUE);
				$meta->eav_set('peserta', $id_induk, $kd, $nilai);
			}
		}

		/**
		 * Ambil satu peserta dengan atribut EAV digabung ke dalam satu baris.
		 */
		function ambil($nim)
		{
			$row = $this->db->where('nim', $nim)->get($this->table)->row_array();
			if (empty($row)) {
				return array();
			}
			$eav = $this->_meta()->eav('peserta', $nim);
			return array_merge($row, $eav);
		}

		/**
		 * Daftar peserta mode aktif, lengkap dengan nama rombongan (kelas/
		 * jurusan/prodi) sesuai mode.
		 */
		function daftar()
		{
			return $this->db
				->select('ts.*, tk.nama_kelas, tk.nama_kelas')
				->from('tbl_siswa ts')
				->join('tbl_kelas tk', 'ts.kd_kelas = tk.kd_kelas', 'left')
				->where('ts.kd_mode', $this->_mode())
				->order_by('ts.nim', 'ASC')
				->get()->result_array();
		}

		function save($foto)
		{
			$data = array(
				//tabel di database => name di form
				'nim'           => $this->input->post('nim', TRUE),
				'nama'          => $this->input->post('nama', TRUE),
				'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
				'tempat_lahir'  => $this->input->post('tempat_lahir', TRUE),
				'gender'        => $this->input->post('gender', TRUE),
				'kd_agama'	    => $this->input->post('agama', TRUE),
				'foto'			=> $foto,
				'kd_kelas'	    => $this->input->post('kelas', TRUE),
				'nisn'          => $this->input->post('nisn', TRUE),
				'kd_mode'       => $this->_mode(),
			);
			$this->db->insert($this->table, $data);

			// ketika pengguna menginsert data siswa, maka data nim, kd_kelas dan tahun_akademik_aktif akan otomatis terinsert dengan sendirinya ke tbl_riwayat_kelas
			$tahun_akademik = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row_array();
			if (!empty($tahun_akademik)) {
				$riwayat = array(
								'nim' 				=> $this->input->post('nim', TRUE),
								'kd_kelas'			=> $this->input->post('kelas', TRUE),
								'id_tahun_akademik'	=> $tahun_akademik['id_tahun_akademik']
							); 
				$this->db->insert('tbl_riwayat_kelas', $riwayat);
			}

			// simpan atribut EAV (prodi, angkatan, jalur masuk, status, dll.)
			$this->simpan_eav($this->input->post('nim', TRUE));
		}

		function update($foto)
		{
			if (empty($foto)) {
				//update tanpa foto
				$data = array(
					'nama'          => $this->input->post('nama', TRUE),
					'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
					'tempat_lahir'  => $this->input->post('tempat_lahir', TRUE),
					'gender'        => $this->input->post('gender', TRUE),
					'kd_agama'	    => $this->input->post('agama', TRUE),
					'kd_kelas'	    => $this->input->post('kelas', TRUE),
					'nisn'          => $this->input->post('nisn', TRUE),
				);
			} else {
				//update dengan foto
				$data = array(
					'nama'          => $this->input->post('nama', TRUE),
					'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
					'tempat_lahir'  => $this->input->post('tempat_lahir', TRUE),
					'gender'        => $this->input->post('gender', TRUE),
					'kd_agama'	    => $this->input->post('agama', TRUE),
					'foto'			=> $foto,
					'kd_kelas'	    => $this->input->post('kelas', TRUE),
					'nisn'          => $this->input->post('nisn', TRUE),
				);
			}

			$nim	= $this->input->post('nim');
			$this->db->where('nim', $nim);
			$this->db->update($this->table, $data);

			$this->simpan_eav($nim);
		}

		// Fungsi untuk melakukan proses upload file
	  	public function upload_csv($filename){
		    $this->load->library('upload'); // Load librari upload
		    
		    $config['upload_path'] = './csv/';
		    $config['allowed_types'] = 'csv';
		    $config['max_size']  = '2048';
		    $config['overwrite'] = true;
		    $config['file_name'] = $filename;
		  
		    $this->upload->initialize($config); // Load konfigurasi uploadnya
		    if($this->upload->do_upload('file')){ // Lakukan upload dan Cek jika proses upload berhasil
		      // Jika berhasil :
		      $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
		      return $return;
		    }else{
		      // Jika gagal :
		      $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
		      return $return;
		    }
		  }
	  
		// Buat sebuah fungsi untuk melakukan insert lebih dari 1 data
		public function insert_multiple($data){
			foreach ($data as $i => $row) {
				$data[$i]['kd_mode'] = $this->_mode();
			}
		    $this->db->insert_batch($this->table, $data);
		}

	}
	
?>