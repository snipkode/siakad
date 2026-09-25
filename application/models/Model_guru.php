<?php

  class Model_guru extends CI_Model
  {

    public $table = "tbl_guru";

    function _meta()
    {
      return get_instance()->meta;
    }

    function _mode()
    {
      return get_instance()->meta->mode();
    }

    /**
     * Definisi field EAV entitas /guru/ yang berlaku untuk mode aktif.
     */
    function eav_fields()
    {
      $out = array();
      foreach ($this->_meta()->fields('guru') as $kd => $f) {
        if ($f['is_eav'] === 'Y') {
          $out[$kd] = $f;
        }
      }
      return $out;
    }

    /**
     * Simpan atribut EAV hasil submit form (prefix name: eav_<kd_field>).
     */
    function simpan_eav($id_guru)
    {
      $meta = $this->_meta();
      foreach ($this->eav_fields() as $kd => $f) {
        $nilai = (string) $this->input->post('eav_'.$kd, TRUE);
        $meta->eav_set('guru', $id_guru, $kd, $nilai);
      }
    }

    /**
     * Ambil satu guru/dosen dengan atribut EAV digabung ke dalam satu baris.
     */
    function ambil($id_guru)
    {
      $row = $this->db->where('id_guru', $id_guru)->get($this->table)->row_array();
      if (empty($row)) {
        return array();
      }
      $eav = $this->_meta()->eav('guru', $id_guru);
      return array_merge($row, $eav);
    }

    function save()
    {
      $data = array(
        //tabel di database => name di form
        'nuptk'       => $this->input->post('nuptk', TRUE),
        'nama_guru'   => $this->input->post('nama_guru', TRUE),
        'gender'      => $this->input->post('gender', TRUE),
        'username'    => $this->input->post('username', TRUE),
        'password'    => hash_password($this->input->post('password', TRUE)),
        'kd_mode'     => $this->_mode(),
      );
      $this->db->insert($this->table, $data);
      $this->simpan_eav($this->db->insert_id());
    }

    function update()
    {
      $data = array(
        //tabel di database => name di form
        'nuptk'       => $this->input->post('nuptk', TRUE),
        'nama_guru'   => $this->input->post('nama_guru', TRUE),
        'gender'      => $this->input->post('gender', TRUE),
        'username'    => $this->input->post('username', TRUE),
        //'semester_aktif'  = $this->input->post('semester_aktif', TRUE)
      );
      $password = (string) $this->input->post('password');
      if ($password !== '') {
        $data['password'] = hash_password($password);
      }
      $id_guru = $this->input->post('id_guru');
      $this->db->where('id_guru', $id_guru);
      $this->db->update($this->table, $data);
      $this->simpan_eav($id_guru);
    }

    function login($username, $password)
    {
      $this->db->where('username', $username);
      $user = $this->db->get('tbl_guru')->row_array();
      if (empty($user)) { return null; }
      if (!check_password($password, $user['password'])) { return null; }
      // hash legacy (MD5) langsung diupgrade ke SHA-256 saat login sukses
      if (!hash_equals($user['password'], hash_password($password))) {
        $up = hash_password($password);
        $this->db->where('id_guru', $user['id_guru'])->update('tbl_guru', array('password' => $up));
        $user['password'] = $up;
      }
      return $user;
    }

  }
 
?>