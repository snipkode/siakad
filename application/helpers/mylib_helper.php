<?php

	// --- Hash & verifikasi password (SHA-256), dengan dukungan legacy MD5 ---
	function hash_password($plain)
	{
		return hash('sha256', (string) $plain);
	}

	function check_password($plain, $hash)
	{
		if (!is_string($hash) || $hash === '') { return false; }
		if (hash_equals($hash, hash_password($plain))) { return true; }
		// kompatibilitas hash lama (MD5) sebelum migrasi SHA-256
		return hash_equals($hash, md5($plain));
	}

	function cmb_dinamis($name, $table, $field, $pk, $selected=null, $extra=null, $class='form-control', $where=null)
	{
		$ci   = get_instance();
		$cmb  = "<select name='$name' class='$class' $extra>";

		if (is_array($where) && count($where)) {
			$data = $ci->db->get_where($table, $where)->result();
		} else {
			$data = $ci->db->get($table)->result();
		}
		foreach ($data as $row) {
			$cmb .= "<option value='".$row->$pk."'";
			//Apabila $selected bernilai sama dengan nilai $pk maka akan bernilai selected selain itu akan bernilai null
			$cmb .= $selected == $row->$pk ? 'selected' : '';
			$cmb .= ">".$row->$field."</option>";
		}
		$cmb .= "</select>";

		return $cmb;
	}

	// identitas sekolah dinamis (tabel tbl_identitas), fallback ke config.php
	function identitas($field = '')
	{
		$ci  = get_instance();
		static $cache = array();

		$mode = meta_mode();
		if (!array_key_exists($mode, $cache)) {
			$q   = $ci->db->where('kd_mode', $mode)->get('tbl_identitas');
			$cache[$mode] = ($q->num_rows() > 0) ? $q->row_array() : array();
		}

		$row = $cache[$mode];

		if ($field === '') {
			return $row;
		}

		$val = isset($row[$field]) ? $row[$field] : '';

		if (trim($val) === '') {
			$map = array('nama_sekolah' => 'sekolah_nama', 'alamat' => 'sekolah_alamat');
			if (isset($map[$field])) {
				$cfg = $ci->config->item($map[$field]);
				if ($cfg !== null) {
					$val = $cfg;
				}
			}
		}

		return $val;
	}

	// untuk mendapatkan tahun akademik aktif dan biar mudah untuk dipanggil 
	function get_tahun_akademik($field)
	{
		$ci    = get_instance();
		$ci->db->where('is_aktif', 'Y');
		$tahun = $ci->db->get('tbl_tahun_akademik')->row_array();
		// apabila belum ada tahun akademik aktif, kembalikan string kosong
		return (is_array($tahun) && isset($tahun[$field])) ? $tahun[$field] : '';
	}

	function checkAksesModule()
	{
		$ci   = get_instance();

		$controller = $ci->uri->segment(1);
		$method		= $ci->uri->segment(2);

		if (empty($method)) {
			$url = $controller;
		} else {
			$url = $controller.'/'.$method;
		}

		$level_User = $ci->session->userdata('id_level_user');

		// Bila tidak ada session (belum login / sesi habis > 30 menit) maka tendang ke halaman login.
		if (empty($level_User)) {
			redirect('auth/');
			return;
		}

		// Menu tidak terdaftar (mis. dashboard/tampilan_utama) => izinkan selama sudah login.
		$menu = $ci->db->get_where('tabel_menu', array('link' => $url))->row_array();
		if (empty($menu) || !isset($menu['id'])) {
			return;
		}

		$check = $ci->db->get_where('tbl_user_rule', array('id_level_user' => $level_User, 'id_menu' => $menu['id']));

		if ($check->num_rows() < 1 AND $method != 'data' AND $method != 'add' AND $method != 'edit' AND $method != 'delete' AND $method != 'upload_foto_siswa' AND $method != 'siswa_aktif' AND $method != 'loadDataSiswa' AND $method != 'export_excel' AND $method != 'upload_foto_siswa') {
			echo "Anda Tidak Boleh Akses Module Ini";
			die;
		}
	}

	function check_nilai($nim, $id_jadwal)
	{
		$ci   = get_instance();

		$nilai = $ci->db->get_where('tbl_nilai', array('nim' => $nim, 'id_jadwal' => $id_jadwal));
		if ($nilai->num_rows() > 0) {
			$row = $nilai->row_array();
			return $row['nilai'];
		} else {
			return 0;
		}
	}

	function Terbilang($x) {
        $x = (int) $x; // hindari deprecation konversi float->int pada operasi pembagian
        $abil = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($x < 12)
            return " " . $abil[$x];
        elseif ($x < 20)
            return Terbilang($x - 10) . "belas";
        elseif ($x < 100)
            return Terbilang($x / 10) . " puluh" . Terbilang($x % 10);
        elseif ($x < 200)
            return " seratus" . Terbilang($x - 100);
        elseif ($x < 1000)
            return Terbilang($x / 100) . " ratus" . Terbilang($x % 100);
        elseif ($x < 2000)
            return " seribu" . Terbilang($x - 1000);
        elseif ($x < 1000000)
            return Terbilang($x / 1000) . " ribu" . Terbilang($x % 1000);
        elseif ($x < 1000000000)
            return Terbilang($x / 1000000) . " juta" . Terbilang($x % 1000000);
    }

	// =============================================================
	// HELPER ARSITEKTUR METADATA-DRIVEN (multi-mode)
	// Berbasis library Meta yang di-autoload.
	// =============================================================

	/**
	 * Kode mode aktif (KAMPUS/SMA/SMP/SD/TK).
	 */
	function meta_mode()
	{
		return get_instance()->meta->mode();
	}

	/**
	 * Label per mode, contoh: meta_label('label_peserta').
	 */
	function meta_mode_label($kolom)
	{
		return get_instance()->meta->mode_label($kolom);
	}

	/**
	 * Cek fitur mode: meta_punya('punya_krs').
	 */
	function meta_punya($kolom)
	{
		return get_instance()->meta->punya($kolom);
	}

	/**
	 * Label sebuah field entitas untuk mode aktif:
	 * contoh: meta('peserta','nomor_induk') => 'NIM' / 'NIS' / 'NISN'.
	 */
	function meta_label($kd_entitas, $kd_field, $fallback = '')
	{
		return get_instance()->meta->label($kd_entitas, $kd_field, $fallback);
	}

	/**
	 * Definisi field sebuah entitas (array), diurutkan.
	 */
	function meta_fields($kd_entitas, $kriteria = null)
	{
		return get_instance()->meta->fields($kd_entitas, $kriteria);
	}

	/**
	 * Referensi terpadu: meta_ref('PRODI') => [kode => nama].
	 */
	function meta_ref($kategori, $full = false)
	{
		return get_instance()->meta->ref($kategori, $full);
	}

	/**
	 * Nama referensi dari kode: meta_ref_nama('PRODI','SI').
	 */
	function meta_ref_nama($kategori, $kode, $fallback = '')
	{
		return get_instance()->meta->ref_nama($kategori, $kode, $fallback);
	}

	/**
	 * Atribut JSON referensi: meta_ref_attr('MAPEL','BID','sks').
	 */
	function meta_ref_attr($kategori, $kode, $kolom = null)
	{
		return get_instance()->meta->ref_atribut($kategori, $kode, $kolom);
	}

	/**
	 * Membaca atribut EAV entitas: meta_eav('peserta','18SI1000','prodi').
	 */
	function meta_eav($kd_entitas, $id_induk, $kd_field = null, $fallback = '')
	{
		$ci = get_instance();
		if ($kd_field !== null) {
			return $ci->meta->eav_val($kd_entitas, $id_induk, $kd_field, $fallback);
		}
		return $ci->meta->eav($kd_entitas, $id_induk);
	}

	/**
	 * Ambil nilai "ROMBONGAN" (nama kelas/kelompok/prodi angkatan) sebuah
	 * peserta dengan format sesuai mode, dari kolom kd_kelas & referensi.
	 * Dipakai untuk label dinamis di daftar siswa.
	 */
	function meta_rombongan_peserta($kd_kelas)
	{
		if (empty($kd_kelas)) {
			return '';
		}
		$ci = get_instance();
		$kelas = $ci->db->where('kd_kelas', $kd_kelas)->get('tbl_kelas')->row_array();
		if (empty($kelas)) {
			return $kd_kelas;
		}
		$parts = array();
		if (meta_punya('punya_prodi') && !empty($kelas['kd_prodi'])) {
			$parts[] = meta_ref_nama('PRODI', $kelas['kd_prodi'], $kelas['kd_prodi']);
		} elseif (meta_punya('punya_jurusan') && !empty($kelas['kd_jurusan'])) {
			$parts[] = meta_ref_nama('JURUSAN', $kelas['kd_jurusan'], $kelas['kd_jurusan']);
		}
		$ting = !empty($kelas['kd_tingkatan'])
			? meta_ref_nama('TINGKATAN', $kelas['kd_tingkatan'], $kelas['kd_tingkatan'])
			: '';
		if ($ting !== '') {
			$parts[] = $ting;
		}
		if (empty($parts)) {
			return $kelas['nama_kelas'];
		}
		return implode(' ', $parts);
	}

?>