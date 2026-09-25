<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Meta
 *
 * Library inti arsitektur metadata-driven.
 * Bertugas:
 *   1. Menentukan mode aktif (KAMPUS/SMA/SMP/SD/TK) dari tbl_pengaturan.
 *   2. Menyediakan label & fitur per mode dari tbl_mode.
 *   3. Menyediakan definisi field per entitas dari tbl_field.
 *   4. Akses referensi terpadu (tbl_referensi).
 *   5. Akses EAV (tbl_entitas_atribut) untuk atribut fleksibel per mode.
 *
 * Seluruh metadata di-cache statis per request agar tidak membanjiri
 * query ke database.
 */
class Meta
{
	protected $ci;

	/** @var string mode aktif */
	protected $mode = '';

	/** @var array baris tbl_mode untuk mode aktif */
	protected $mode_row = array();

	/** @var array label dari tbl_pengaturan (di-load pada demand) */
	protected $settings = array();

	/** @var array cache field per entitas: entitas => [kd_field => row] */
	protected $fields = array();

	/** @var array cache referensi per kategori */
	protected $referensi = array();

	/** @var bool cache EAV per (entitas, id_induk) */
	protected $eav_cache = array();

	public function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->database();
	}

	// ---------------------------------------------------------------
	// MODE
	// ---------------------------------------------------------------

	/**
	 * Kode mode aktif, diambil dari tbl_pengaturan 'mode_aktif'
	 * dengan fallback ke config/siakad.php.
	 */
	public function mode()
	{
		if ($this->mode !== '') {
			return $this->mode;
		}

		$list = $this->ci->config->item('modes_valid');
		$list = is_array($list) ? $list : array('KAMPUS', 'SMA', 'SMP', 'SD', 'TK');

		$aktif = $this->setting('mode_aktif');
		if (!in_array($aktif, $list, true)) {
			$aktif = (string) $this->ci->config->item('mode_default');
			if (!in_array($aktif, $list, true)) {
				$aktif = 'KAMPUS';
			}
		}

		$this->mode = $aktif;
		return $this->mode;
	}

	/**
	 * Ganti mode aktif (dipakai dari halaman Pengaturan).
	 */
	public function set_mode($kd_mode)
	{
		$list = $this->ci->config->item('modes_valid');
		$list = is_array($list) ? $list : array('KAMPUS', 'SMA', 'SMP', 'SD', 'TK');
		if (!in_array($kd_mode, $list, true)) {
			return false;
		}
		$this->set_setting('mode_aktif', $kd_mode);
		$this->mode = $kd_mode;
		$this->mode_row = array();
		$this->fields = array();
		$this->referensi = array();
		return true;
	}

	/**
	 * Baris lengkap tbl_mode untuk mode aktif (atau kd_mode tertentu).
	 */
	public function mode_row($kd_mode = null)
	{
		$kd = $kd_mode === null ? $this->mode() : $kd_mode;
		if (!isset($this->mode_row[$kd])) {
			$this->mode_row[$kd] = $this->ci->db->where('kd_mode', $kd)->get('tbl_mode')->row_array();
			if (empty($this->mode_row[$kd])) {
				$this->mode_row[$kd] = array();
			}
		}
		return $this->mode_row[$kd];
	}

	/**
	 * Ambil satu label/kolom dari profil mode aktif.
	 * Contoh: mode_label('label_peserta') => 'Mahasiswa'.
	 */
	public function mode_label($kolom)
	{
		$row = $this->mode_row();
		return isset($row[$kolom]) ? $row[$kolom] : '';
	}

	/**
	 * Cek fitur mode, contoh: meta()->punya('punya_krs') / punya('punya_jurusan').
	 */
	public function punya($kolom)
	{
		$row = $this->mode_row();
		return (isset($row[$kolom]) && $row[$kolom] === 'Y');
	}

	/**
	 * KKM default mode aktif.
	 */
	public function kkm()
	{
		$row = $this->mode_row();
		if (isset($row['kkm_default']) && (int) $row['kkm_default'] > 0) {
			return (int) $row['kkm_default'];
		}
		return (int) $this->setting('kkm_default') ?: (int) $this->ci->config->item('kkm_default');
	}

	// ---------------------------------------------------------------
	// PENGATURAN (tbl_pengaturan)
	// ---------------------------------------------------------------

	public function setting($kunci, $fallback = null)
	{
		if (!array_key_exists($kunci, $this->settings)) {
			$this->settings[$kunci] = null;
			$q = $this->ci->db->where('kunci', $kunci)->get('tbl_pengaturan')->row_array();
			if (!empty($q)) {
				$this->settings[$kunci] = $q['nilai'];
			}
		}
		if ($this->settings[$kunci] === null || $this->settings[$kunci] === '') {
			return $fallback;
		}
		return $this->settings[$kunci];
	}

	public function set_setting($kunci, $nilai)
	{
		$ada = $this->ci->db->where('kunci', $kunci)->count_all_results('tbl_pengaturan') > 0;
		if ($ada) {
			$this->ci->db->where('kunci', $kunci)->update('tbl_pengaturan', array('nilai' => $nilai));
		} else {
			$this->ci->db->insert('tbl_pengaturan', array('kunci' => $kunci, 'nilai' => $nilai));
		}
		$this->settings[$kunci] = $nilai;
	}

	// ---------------------------------------------------------------
	// DEFINISI FIELD (tbl_field)
	// ---------------------------------------------------------------

	/**
	 * Semua field aktif sebuah entitas untuk mode aktif, diurutkan.
	 * $kriteria: 'list' => hanya yang tampil di list, 'form' => wajib form,
	 *            atau null => semua.
	 */
	public function fields($kd_entitas, $kriteria = null)
	{
		$mode = $this->mode();
		$key  = $kd_entitas . '|' . $mode;
		if (!isset($this->fields[$key])) {
			$this->fields[$key] = array();
			$q = $this->ci->db
				->from('tbl_field')
				->where('kd_entitas', $kd_entitas)
				->where('is_aktif', 'Y')
				->order_by('urut', 'ASC')
				->get()->result_array();
			foreach ($q as $f) {
				if ($this->_field_berlaku($f, $mode)) {
					$this->fields[$key][$f['kd_field']] = $f;
				}
			}
		}
		$out = $this->fields[$key];
		if ($kriteria === 'list') {
			$out = array_filter($out, function ($f) { return $f['is_list'] === 'Y'; });
		}
		return $out;
	}

	/**
	 * Satu definisi field (atau null bila tidak berlaku untuk mode aktif).
	 */
	public function field($kd_entitas, $kd_field)
	{
		$fs = $this->fields($kd_entitas);
		return isset($fs[$kd_field]) ? $fs[$kd_field] : null;
	}

	/**
	 * Label UI sebuah field untuk mode aktif.
	 */
	public function label($kd_entitas, $kd_field, $fallback = '')
	{
		$f = $this->field($kd_entitas, $kd_field);
		if ($f === null) {
			return $fallback === '' ? ucwords(str_replace('_', ' ', $kd_field)) : $fallback;
		}
		return $f['label'];
	}

	// ---------------------------------------------------------------
	// REFERENSI (tbl_referensi)
	// ---------------------------------------------------------------

	/**
	 * Baris referensi aktif sebuah kategori: [kode => nama] (atau full row bila $full).
	 */
	public function ref($kategori, $full = false)
	{
		if (!isset($this->referensi[$kategori])) {
			$this->referensi[$kategori] = array();
			$q = $this->ci->db
				->from('tbl_referensi')
				->where('kategori', $kategori)
				->where('is_aktif', 'Y')
				->order_by('urutan', 'ASC')
				->order_by('nama', 'ASC')
				->get()->result_array();
			foreach ($q as $r) {
				$this->referensi[$kategori][$r['kode']] = $r;
			}
		}
		if ($full) {
			return $this->referensi[$kategori];
		}
		$out = array();
		foreach ($this->referensi[$kategori] as $code => $r) {
			$out[$code] = $r['nama'];
		}
		return $out;
	}

	/**
	 * Nama sebuah kode referensi.
	 */
	public function ref_nama($kategori, $kode, $fallback = '')
	{
		$ref = $this->ref($kategori);
		return isset($ref[$kode]) ? $ref[$kode] : $fallback;
	}

	/**
	 * Atribut JSON sebuah kode referensi (mis. sks).
	 */
	public function ref_atribut($kategori, $kode, $kolom = null)
	{
		$full = $this->ref($kategori, true);
		$row  = isset($full[$kode]) ? $full[$kode] : array();
		$json = isset($row['atribut_json']) && trim((string) $row['atribut_json']) !== ''
			? json_decode($row['atribut_json'], true) : array();
		if ($kolom !== null) {
			return isset($json[$kolom]) ? $json[$kolom] : null;
		}
		return $json;
	}

	// ---------------------------------------------------------------
	// EAV (tbl_entitas_atribut)
	// ---------------------------------------------------------------

	/**
	 * Baca semua atribut EAV sebuah entitas: [kd_field => nilai].
	 */
	public function eav($kd_entitas, $id_induk)
	{
		$key = $kd_entitas . '|' . $id_induk;
		if (!array_key_exists($key, $this->eav_cache)) {
			$this->eav_cache[$key] = array();
			$q = $this->ci->db
				->where('kd_entitas', $kd_entitas)
				->where('id_induk', $id_induk)
				->get('tbl_entitas_atribut')->result_array();
			foreach ($q as $row) {
				$this->eav_cache[$key][$row['kd_field']] = $row['nilai'];
			}
		}
		return $this->eav_cache[$key];
	}

	/**
	 * Baca satu nilai EAV.
	 */
	public function eav_val($kd_entitas, $id_induk, $kd_field, $fallback = '')
	{
		$attrs = $this->eav($kd_entitas, $id_induk);
		return isset($attrs[$kd_field]) && $attrs[$kd_field] !== null
			? $attrs[$kd_field] : $fallback;
	}

	/**
	 * Tulis/update satu nilai EAV (upsert).
	 */
	public function eav_set($kd_entitas, $id_induk, $kd_field, $nilai)
	{
		$this->ci->db->where('kd_entitas', $kd_entitas)
			->where('id_induk', $id_induk)
			->where('kd_field', $kd_field);
		if ($this->ci->db->count_all_results('tbl_entitas_atribut') > 0) {
			$this->ci->db->where('kd_entitas', $kd_entitas)
				->where('id_induk', $id_induk)
				->where('kd_field', $kd_field)
				->update('tbl_entitas_atribut', array('nilai' => $nilai));
		} else {
			$this->ci->db->insert('tbl_entitas_atribut', array(
				'kd_entitas' => $kd_entitas,
				'id_induk'   => $id_induk,
				'kd_field'   => $kd_field,
				'nilai'      => $nilai,
			));
		}
		$this->eav_cache = array();
	}

	// ---------------------------------------------------------------
	// UTILITAS
	// ---------------------------------------------------------------

	/**
	 * Apakah sebuah definisi field berlaku untuk kd_mode tertentu.
	 * 'ALL' => semua mode; selain itu daftar kode dipisah koma.
	 */
	protected function _field_berlaku($field, $mode)
	{
		$bk = isset($field['berlaku_mode']) ? trim((string) $field['berlaku_mode']) : 'ALL';
		if ($bk === '' || $bk === 'ALL') {
			return true;
		}
		$parts = array_map('trim', explode(',', $bk));
		return in_array($mode, $parts, true);
	}

	/**
	 * Daftar semua mode aktif untuk dropdown "ganti mode".
	 */
	public function modes()
	{
		$q = $this->ci->db->where('is_aktif', 'Y')->order_by('urutan', 'ASC')->get('tbl_mode')->result_array();
		return $q;
	}
}