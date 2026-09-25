<?php

  class Guru extends CI_Controller
  {
    
    function __construct()
    {
      parent::__construct();
      checkAksesModule();
      $this->load->library('ssp');
      $this->load->model('model_guru');
    }

    function data()
    {

      // nama table
      $table      = 'tbl_guru';
      // nama PK
      $primaryKey = 'id_guru';
      // list field yang mau ditampilkan
      $columns    = array(
            //tabel db(kolom di database) => dt(nama datatable di view)
            array('db' => 'id_guru', 'dt' => 'id_guru'),
            array('db' => 'nuptk', 'dt' => 'nuptk'),
            array('db' => 'nama_guru', 'dt' => 'nama_guru'),
            array(
                'db' => 'gender',
                'dt' => 'gender',
                'formatter' => function($d) {
                  return $d == 'P'
                    ? "<span class='inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-1 text-[11px] font-semibold text-sky-700'><i class='fa fa-mars text-[10px]'></i> Pria</span>"
                    : "<span class='inline-flex items-center gap-1 rounded-full bg-fuchsia-100 px-2.5 py-1 text-[11px] font-semibold text-fuchsia-700'><i class='fa fa-venus text-[10px]'></i> Wanita</span>";
                }
              ),
            //untuk menampilkan aksi(edit/delete dengan parameter id guru)
            array(
                  'db' => 'id_guru',
                  'dt' => 'aksi',
                  'formatter' => function($d) {
                      return "<div class='inline-flex gap-1.5'>".
                        anchor('guru/edit/'.$d, '<i class="fa fa-pencil"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100" data-placement="top" title="Edit"').' 
                        '.anchor('guru/delete/'.$d, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus '.$this->meta->mode_label('label_staf').' ini?")\'')."</div>";
                }
            )
        );

      $sql_details = array(
        'user' => $this->db->username,
        'pass' => $this->db->password,
        'db'   => $this->db->database,
        'host' => $this->db->hostname
        );

        $whereAll = "kd_mode = ".$this->db->escape($this->model_guru->_mode());

        echo json_encode(
          SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $whereAll)
         );

    }

    function index()
    {
      $this->template->load('template', 'guru/view');
    }

    function add()
    {
      if (isset($_POST['submit'])) {
        $this->model_guru->save();
        redirect('guru');
      } else {
        $data['eav_fields'] = $this->model_guru->eav_fields();
        $data['eav_values'] = array();
        $this->template->load('template', 'guru/add', $data);
      }
    }

    function edit()
    {
      if (isset($_POST['submit'])) {
        $this->model_guru->update();
        redirect('guru');
      } else {
        $id_guru        = $this->uri->segment(3);
        $data['guru']   = $this->model_guru->ambil($id_guru);
        $data['eav_fields'] = $this->model_guru->eav_fields();
        $data['eav_values'] = $data['guru'];
        $this->template->load('template', 'guru/edit', $data);
      }
    }

    function delete()
    {
      $id_guru = $this->uri->segment(3);
      if (!empty($id_guru)) {
        $this->db->where('id_guru', $id_guru);
        $this->db->delete('tbl_guru');
      }
      redirect('guru');
    }

  }

?>