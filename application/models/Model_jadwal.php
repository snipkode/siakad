<?php
 
	class Model_jadwal extends CI_Model
	{
		
		function jamPelajaran() {
	 		 $jam_pelajaran	= array(
            	'07.15 - 08.00' => '07.15 - 08.00',
            	'08.00 - 08.45' => '08.00 - 08.45',
            	'08.45 - 09.30' => '08.45 - 09.30',
            	'09.30 - 10.00' => '09.30 - 10.00',
            	'10.00 - 10.45' => '10.00 - 10.45',
            	'10.45 - 11.30' => '10.45 - 11.30',
            	'11.30 - 12.15' => '11.30 - 12.15',
            	'12.15 - 13.00' => '12.15 - 13.00',
            	'13.00 - 13.30' => '13.00 - 13.30',
            	'13.30 - 14.15' => '13.30 - 14.15',
            	'14.15 - 15.00' => '14.15 - 15.00',
            );
	 		 return $jam_pelajaran;
	 	}

	 	function generateJadwal()
	 	{
	 		$idkurikulum	 = $this->input->post('kurikulum');
			$semester		 = $this->input->post('semester');

			// Mengambil detail data dari kurikulum yang dipilih (tbl_kurikulum_detail)
			$kurikulumDetail = $this->db->get_where('tbl_kurikulum_detail', array('id_kurikulum' => $idkurikulum));

			// Ambil tahun akademik yang aktif
			$tahunakademik 	 = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row_array();

			foreach ($kurikulumDetail->result() as $row) {

				// ambil kelas berdasarkan tingkatan dan jurusan
				$kelasnya = $this->db->get_where('tbl_kelas', array('kd_jurusan' => $row->kd_jurusan, 'kd_tingkatan' => $row->kd_tingkatan));

				foreach ($kelasnya->result() as $row_kelas) {
					$data = array(
						'id_tahun_akademik' => $tahunakademik['id_tahun_akademik'], 
						'semester'			=> $semester,
						'kd_jurusan'		=> $row->kd_jurusan, 
						'kd_tingkatan'		=> $row->kd_tingkatan, //sama seperti kelas di akademik
						'kd_kelas'			=> $row_kelas->kd_kelas, //sama seperti rombel di akademik
						'kd_mapel'			=> $row->kd_mapel, 
						'id_guru'			=> 0, 
						'jam'				=> '', 
						'kd_ruangan'		=> '000', 
						'hari'				=> ''
					);
					$this->db->insert('tbl_jadwal', $data);
				}

			}
	 	}

	 	function autoIsiJadwal()
	 	{
	 		$jam_pelajaran	= array_values($this->jamPelajaran());
	 		$hari_list		= array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
	 		$kelas			= $this->db->query("SELECT kd_kelas FROM tbl_kelas")->result();

	 		foreach ($kelas as $k) {
	 			$ruangan = $this->getRuangKelas($k->kd_kelas);

	 			// 1) cocokkan ruangan semua baris kelas ini dengan rombelnya
	 			if ($ruangan !== null) {
	 				$all = $this->db->query(
	 					"SELECT id_jadwal, kd_ruangan FROM tbl_jadwal WHERE kd_kelas = '".$k->kd_kelas."'"
	 				)->result();
	 				foreach ($all as $r) {
	 					if ($r->kd_ruangan !== $ruangan) {
	 						$this->db->where('id_jadwal', $r->id_jadwal);
	 						$this->db->update('tbl_jadwal', array('kd_ruangan' => $ruangan));
	 					}
	 				}
	 			}

	 			// 2) slot (hari+jam) yang sudah terpakai di kelas ini
	 			$used = array();
	 			$terisi = $this->db->query(
	 				"SELECT hari, jam FROM tbl_jadwal
	 				 WHERE kd_kelas = '".$k->kd_kelas."' AND TRIM(hari) != '' AND TRIM(jam) != ''"
	 			)->result();
	 			foreach ($terisi as $t) {
	 				$used[$t->hari.'|'.$t->jam] = 1;
	 			}

	 			// 3) baris yang belum lengkap (hari/jam kosong)
	 			$rows = $this->db->query(
	 				"SELECT id_jadwal, kd_ruangan FROM tbl_jadwal
	 				 WHERE kd_kelas = '".$k->kd_kelas."' AND (TRIM(hari) = '' OR TRIM(jam) = '')"
	 			)->result();
	 			if (empty($rows)) continue;

	 			$total_slots = count($hari_list) * count($jam_pelajaran);
	 			$i = 0;

	 			foreach ($rows as $row) {
	 				$slot = null;
	 				for (; $i < $total_slots; $i++) {
	 					$h = $hari_list[(int)($i / count($jam_pelajaran))];
	 					$j = $jam_pelajaran[$i % count($jam_pelajaran)];
	 					$key = $h.'|'.$j;
	 					if (!isset($used[$key])) {
	 						$slot = array($h, $j);
	 						$used[$key] = 1;
	 						$i++;
	 						break;
	 					}
	 				}
	 				if ($slot === null) continue;

	 				$this->db->where('id_jadwal', $row->id_jadwal);
	 				$this->db->update('tbl_jadwal', array('hari' => $slot[0], 'jam' => $slot[1]));
	 			}
	 		}
	 	}

	 	function seedJadwalFull()
	 	{
	 		// pastikan ruangan kelas 9 tersedia
	 		foreach (array(
	 			'IXA1' => 'Ruangan Kelas IX-A IPA',
	 			'IXA2' => 'Ruangan Kelas IX-A IPS',
	 			'IXB1' => 'Ruangan Kelas IX-B IPA',
	 			'IXB2' => 'Ruangan Kelas IX-B IPS'
	 		) as $kd => $nama) {
	 			if ($this->db->get_where('tbl_ruangan', array('kd_ruangan' => $kd))->num_rows() == 0) {
	 				$this->db->insert('tbl_ruangan', array('kd_ruangan' => $kd, 'nama_ruangan' => $nama));
	 			}
	 		}

	 		$tahun = $this->db->get_where('tbl_tahun_akademik', array('is_aktif' => 'Y'))->row();
	 		if (!$tahun) { return; }

	 		$mapel_by_tingkat = array(
	 			'7' => array('BID1', 'BIO1', 'MTK1', 'PAI1'),
	 			'8' => array('BID2', 'BIO2', 'MTK2', 'PAI2'),
	 			'9' => array('BID3', 'BIO3', 'MTK3', 'PAI3'),
	 		);
	 		$guru_mapel = array(
	 			'BID1' => '3', 'BID2' => '3', 'BID3' => '3',
	 			'BIO1' => '2', 'BIO2' => '2', 'BIO3' => '2',
	 			'MTK1' => '1', 'MTK2' => '1', 'MTK3' => '1',
	 			'PAI1' => '1', 'PAI2' => '1', 'PAI3' => '1',
	 		);

	 		$hari_list = array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
	 		$jam_list  = array_values($this->jamPelajaran());

	 		$this->db->query("DELETE FROM tbl_jadwal");

	 		$kelas  = $this->db->query("SELECT kd_kelas, kd_jurusan, kd_tingkatan FROM tbl_kelas ORDER BY kd_tingkatan, kd_kelas")->result();
	 		$n_ting = array();

	 		foreach ($kelas as $k) {
	 			$ting = $k->kd_tingkatan;
	 			if (!isset($mapel_by_tingkat[$ting])) { continue; }
	 			if (!isset($n_ting[$ting])) { $n_ting[$ting] = 0; }

	 			// geser pola per rombel agar jam/guru antar kelas berbeda
	 			$offset = ($n_ting[$ting] % count($mapel_by_tingkat[$ting])) * 9;
	 			$n_ting[$ting]++;

	 			$ruangan = $this->getRuangKelas($k->kd_kelas);

	 			foreach ($hari_list as $hi => $h) {
	 				foreach ($jam_list as $ji => $j) {
	 					$idx      = ($hi * count($jam_list) + $ji + $offset) % count($mapel_by_tingkat[$ting]);
	 					$kd_mapel = $mapel_by_tingkat[$ting][$idx];
	 					$this->db->insert('tbl_jadwal', array(
	 						'id_tahun_akademik' => $tahun->id_tahun_akademik,
	 						'semester'          => 'ganjil',
	 						'kd_jurusan'        => $k->kd_jurusan,
	 						'kd_tingkatan'      => $ting,
	 						'kd_kelas'          => $k->kd_kelas,
	 						'kd_mapel'          => $kd_mapel,
	 						'id_guru'           => $guru_mapel[$kd_mapel],
	 						'jam'               => $j,
	 						'kd_ruangan'        => $ruangan,
	 						'hari'              => $h,
	 					));
	 				}
	 			}
	 		}
	 	}

	 	function getRuangKelas($kd_kelas)
	 	{
	 		$num   = substr($kd_kelas, 0, 1);
	 		$roman = array('7' => 'VII', '8' => 'VIII', '9' => 'IX');
	 		if (!isset($roman[$num])) { return null; }
	 		$rombel = $roman[$num].substr($kd_kelas, 2);
	 		$q = $this->db->query("SELECT kd_ruangan FROM tbl_ruangan WHERE kd_ruangan = '".$rombel."'");
	 		return $q->num_rows() > 0 ? $q->row()->kd_ruangan : null;
	 	}

	}

?>