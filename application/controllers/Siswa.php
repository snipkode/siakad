<?php

	class Siswa extends CI_Controller
	{
		private $filename = "import_data"; // nama file .csv
		
		function __construct() 
		{
			parent::__construct();
			//checkAksesModule();
			$this->load->library('ssp');
			$this->load->model('model_siswa');
		}

		function data()
		{

			// nama table
			$table      = 'tbl_siswa';
			// nama PK
			$primaryKey = 'nim';
			// list field yang mau ditampilkan
			$columns    = array(
				//tabel db(kolom di database) => dt(nama datatable di view)
				array('db' => 'foto', 
					  'dt' => 'foto',
					  'formatter' => function($d) {
					  		if (empty($d) || preg_match('/^user-siluet/i', $d)) {
					  			return "<img class='h-10 w-10 shrink-0 rounded-full border border-slate-200 object-cover shadow-sm' loading='lazy' src='".base_url()."uploads/default-avatar.svg'>";
					  		}
					  		return "<img class='h-10 w-10 shrink-0 rounded-full border border-slate-200 object-cover shadow-sm' loading='lazy' src='".base_url()."uploads/".$d."'>";
					  }
				),
				array('db' => 'nim', 'dt' => 'nim'),
		        array('db' => 'nama', 'dt' => 'nama'),
		        array('db' => 'nama_jurusan', 'dt' => 'jurusan'),
		        array('db' => 'tempat_lahir', 'dt' => 'tempat_lahir'),
		        array('db' => 'tanggal_lahir', 'dt' => 'tanggal_lahir'),
		        //untuk menampilkan aksi(edit/delete dengan parameter nim siswa)
		        array(
		              'db' => 'nim',
		              'dt' => 'aksi',
		              'formatter' => function($d) {
		               		return "<div class='inline-flex gap-1.5'>".
		               			"<button type='button' onclick=\"openEditModal('".$d."')\" class='inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100' data-placement='top' title='Edit'><i class='fa fa-pencil'></i></button>".
		               			anchor('siswa/delete/'.$d, '<i class="fa fa-trash"></i>', 'class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" data-placement="top" title="Delete" onclick=\'return confirm("Yakin ingin menghapus siswa ini?")\'')."</div>";
		            }
		        )
		    );

			$sql_details = array(
				'user' => $this->db->username,
				'pass' => $this->db->password,
				'db'   => $this->db->database,
				'host' => $this->db->hostname
		    );

		    $join = "LEFT JOIN tbl_kelas ON tbl_siswa.kd_kelas = tbl_kelas.kd_kelas
		             LEFT JOIN tbl_jurusan ON tbl_kelas.kd_jurusan = tbl_jurusan.kd_jurusan";

		    echo json_encode(
		     	SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns, $join)
		     );

		}

		function index()
		{
			$this->template->load('template', 'siswa/view');
		}

		function add()
		{
			if (isset($_POST['submit'])) {
				$uploadFoto = $this->upload_foto_siswa();
				if ($uploadFoto === false) {
					$data['upload_error'] = $this->upload->display_errors();
					$this->template->load('template', 'siswa/add', $data);
					return;
				}
				$this->model_siswa->save($uploadFoto);
				redirect('siswa');
			} else {
				$this->template->load('template', 'siswa/add');
			}
		}

		function edit()
		{
			if (isset($_POST['submit'])) {
				$uploadFoto = $this->upload_foto_siswa();
				if ($uploadFoto === false) {
					// saluran AJAX (modal popup): balas JSON biar error tampil di dalam modal
					if ($this->input->is_ajax_request()) {
						exit(json_encode(array('ok' => false, 'message' => $this->upload->display_errors())));
					}
					$nim           = $this->uri->segment(3);
					$data['siswa'] = $this->db->get_where('tbl_siswa', array('nim' => $nim))->row_array();
					$data['upload_error'] = $this->upload->display_errors();
					$this->template->load('template', 'siswa/edit', $data);
					return;
				}
				$this->model_siswa->update($uploadFoto);
				if ($this->input->is_ajax_request()) {
					exit(json_encode(array('ok' => true)));
				}
				redirect('siswa');
			} else {
				$nim           = $this->uri->segment(3);
				$data['siswa'] = $this->db->get_where('tbl_siswa', array('nim' => $nim))->row_array();
				$this->template->load('template', 'siswa/edit', $data);
			}
		}

		// Render form edit saja (partial) untuk dimuat ke modal popup via AJAX
		function form_edit()
		{
			$nim           = $this->uri->segment(3);
			$data['siswa'] = $this->db->get_where('tbl_siswa', array('nim' => $nim))->row_array();
			if (empty($data['siswa'])) {
				show_404();
				return;
			}
			$this->load->view('siswa/edit_form', $data);
		}

		function delete()
		{
			$nim = $this->uri->segment(3);
			if (!empty($nim)) {
				$this->db->where('nim', $nim);
				$this->db->delete('tbl_siswa');
			} 
			redirect('siswa');
		}

		function upload_foto_siswa()
		{
			// Jika tidak ada file yang dipilih -> foto kosong (untuk edit: tidak mengubah foto)
			if (!isset($_FILES['userfile']) || empty($_FILES['userfile']['name'])) {
				return '';
			}

			$config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
            $config['max_size']             = 2048;
            $this->load->library('upload', $config);

            // Jika proses upload sukses, kembalikan nama file; jika gagal kembalikan false
            if ($this->upload->do_upload('userfile')) {
            	return $this->upload->data('file_name');
            }

            return false;
		}

		// siswa_aktif() -> untuk menampilkan view peserta didik ->terletak di controller Siswa
		// combobox_kelas() -> untuk menampilkan data kelas sesuai jurusan yang dipilih -> terletak di controller Kelas
		// loadDataSiswa() -> untuk menampilkan data siswa nim dan nama sesuai kode_kelas yang dipilih di filter, lalu ditampilkan ke div id = kelas yang bedada di view/siswa_aktif -> terletak di controller Siswa
		function siswa_aktif()
		{
			$this->template->load('template', 'siswa/siswa_aktif');
		}

		function loadDataSiswa()
		{
			$kelas 	= $_GET['kd_kelas'];

			$this->db->where('kd_kelas', $kelas);
			$siswa = $this->db->get('tbl_siswa');

			if ($siswa->num_rows() == 0) {
				echo "<p class='py-10 text-center text-sm text-slate-400'><i class='fa fa-folder-open-o mr-2' aria-hidden='true'></i>Belum ada siswa di kelas ini</p>";
				return;
			}

			echo "<table class='w-full min-w-[420px] text-sm'>
					<tr class='border-b border-slate-200'>
						<th class='px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500'>NIM</th>
						<th class='px-3 py-2.5 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500'>NAMA</th>
						<th class='px-3 py-2.5 text-center text-[11px] font-semibold uppercase tracking-wide text-slate-500'>NILAI</th>
					</tr>";

			foreach ($siswa->result() as $row) {
				echo "<tr class='border-b border-slate-100 last:border-0'>
						<td class='px-3 py-2.5 text-center font-mono'>$row->nim</td>
						<td class='px-3 py-2.5'>$row->nama</td>
						<td class='px-3 py-2.5 text-center'>".anchor('siswa/nilai_siswa/'.$row->nim, '<i class="fa fa-eye" aria-hidden="true"></i>', array('class'=>'inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100', 'title'=>'Lihat nilai'))."</td>
					 </tr>";
			}
			echo "</table>";
		}

		function export_excel()
		{
			$this->load->library('CPHP_excel');
	        $objPHPExcel = new PHPExcel();
	        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'NIM');
	        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SISWA');
	        
	        $kelas = $_POST['kelas'];
	        $this->db->where('kd_kelas', $kelas);
	        $siswa = $this->db->get('tbl_siswa');
	        $no=2;
	        foreach ($siswa->result() as $row){
	            $objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->nim);
	            $objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->nama);
	            $no++;
	        }
	        
	        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
	        $objWriter->save("data-siswa.xlsx");
	        $this->load->helper('download');
	        force_download('data-siswa.xlsx', NULL);
		}

		function nilai_siswa()
		{
			$nim 					= $this->uri->segment(3);
			$sql 					= "SELECT ts.nama, tm.nama_mapel, tn.nilai
									  FROM tbl_nilai AS tn, tbl_jadwal AS tj, tbl_mapel AS tm, tbl_siswa AS ts
									  WHERE tn.id_jadwal = tj.id_jadwal AND tj.kd_mapel = tm.kd_mapel AND tn.nim = ts.nim AND tn.nim = '$nim'";
			$data['nilai_siswa'] 	= $this->db->query($sql);
			$this->template->load('template', 'siswa/nilai', $data);
		}

		function form(){
		    $data = array(); // Buat variabel $data sebagai array
		    
		    if(isset($_POST['preview'])){ // Jika user menekan tombol Preview pada form
		      // lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php
		      $uploadcsv = $this->model_siswa->upload_csv($this->filename);
		      
		      if($uploadcsv['result'] == "success"){ // Jika proses upload sukses
		        // Load plugin PHPExcel nya
		        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		        
		        $csvreader = PHPExcel_IOFactory::createReader('CSV');
		        $loadcsv = $csvreader->load('csv/'.$this->filename.'.csv'); // Load file yang tadi diupload ke folder csv
		        $sheet = $loadcsv->getActiveSheet()->getRowIterator();
		        
		        // Masukan variabel $sheet ke dalam array data yang nantinya akan di kirim ke file form.php
		        // Variabel $sheet tersebut berisi data-data yang sudah diinput di dalam csv yang sudha di upload sebelumnya
		        $data['sheet'] = $sheet; 
		      }else{ // Jika proses upload gagal
		        $data['upload_error'] = $uploadcsv['error']; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
		      }
		    }
		    
		    $this->load->view('siswa/form', $data);
		  }

		  function import(){
		  	// Load plugin PHPExcel nya
		  	include APPPATH.'third_party/PHPExcel/PHPExcel.php';
		    
		    $csvreader = PHPExcel_IOFactory::createReader('CSV');
		    $loadcsv = $csvreader->load('csv/'.$this->filename.'.csv'); // Load file yang tadi diupload ke folder csv
		    $sheet = $loadcsv->getActiveSheet()->getRowIterator();
		    
		    // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
		    $data = [];
		    
		    $numrow = 1;
		    foreach($sheet as $row){
		      // Cek $numrow apakah lebih dari 1
		      // Artinya karena baris pertama adalah nama-nama kolom
		      // Jadi dilewat saja, tidak usah diimport
		      if($numrow > 1){
		        // START -->
		        // Skrip untuk mengambil value nya
		        $cellIterator = $row->getCellIterator();
		        $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
		        
		        $get = array(); // Valuenya akan di simpan kedalam array,dimulai dari index ke 0
		        foreach ($cellIterator as $cell) {
		          array_push($get, $cell->getValue()); // Menambahkan value ke variabel array $get
		        }
		        // <-- END
		        
		        // Ambil data value yang telah di ambil dan dimasukkan ke variabel $get
		        $nim = $get[0]; // Ambil data NIS dari kolom A di csv
		        $nama = $get[1]; // Ambil data nama dari kolom B di csv
		        $tanggal_lahir = $get[2]; // Ambil data jenis kelamin dari kolom C di csv
		        $tempat_lahir = $get[3]; // Ambil data alamat dari kolom D di csv
		        
		        // Kita push (add) array data ke variabel data
		        array_push($data, [
		          'nim'=>$nim, // Insert data nis
		          'nama'=>$nama, // Insert data nama
		          'tanggal_lahir'=>$tanggal_lahir, // Insert data jenis kelamin
		          'tempat_lahir'=>$tempat_lahir, // Insert data alamat
		        ]);
		      }
		      
		      $numrow++; // Tambah 1 setiap kali looping
		    }
		    // Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model
		    $this->model_siswa->insert_multiple($data);
		    
		    redirect("Siswa"); // Redirect ke halaman awal (ke controller siswa fungsi index)
		  }

		  function naik_kelas() {
		  	$this->template->load('template', 'siswa/naik_kelas');
		  }

		  function Naiksiswa()
			{
				$kelas 	= $_GET['kd_kelas'];

				echo "<table class='table table-striped table-bordered table-hover table-full-width dataTable'>
						<tr>
							<th width=100 class='text-center'>NIM</th>
							<th>NAMA</th>
						</tr>";

				$this->db->where('kd_kelas', $kelas);
				$siswa = $this->db->get('tbl_siswa');
				foreach ($siswa->result() as $row) {
					echo "<tr>
							<td class='text-center'>$row->nim</td>
							<td>$row->nama</td>
						 </tr>";
				}
				echo "</table>";
			}

		function aksi_naikkelas() {
			$kelas 	= $_GET['kelas'];
			$this->db->where('kd_kelas', $kelas);
			$siswa = $this->db->get('tbl_siswa');
			foreach ($siswa->result() as $row) {
				$nim = $row->nim;
				print($nim);
			}
			//$querynaik = "UPDATE tbl_siswa SET kd_kelas = '8-A1' WHERE NIM = '18SI1000' AND kd_kelas = '$kelas'"
		}

		// function loadDataSiswa()
		// {
		// 	$kelas 	= $_GET['kd_kelas'];

		// 	echo "<table class='table table-striped table-bordered table-hover table-full-width dataTable'>
		// 			<tr>
		// 				<th width=100 class='text-center'>NIM</th>
		// 				<th>NAMA</th>
		// 				<th class='text-center'>NILAI</th>
		// 			</tr>";

		// 	$this->db->where('kd_kelas', $kelas);
		// 	$siswa = $this->db->get('tbl_siswa');
		// 	foreach ($siswa->result() as $row) {
		// 		echo "<tr>
		// 				<td class='text-center'>$row->nim</td>
		// 				<td>$row->nama</td>
		// 				<td class='text-center'>".anchor('siswa/nilai_siswa/'.$row->nim, '<i class="fa fa-eye" aria-hidden="true"></i>')."</td>
		// 			 </tr>";
		// 	}
		// 	echo "</table>";
		// }

	}

?>