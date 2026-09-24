<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| KONFIGURASI SIAKAD MULTI-MODE
| -------------------------------------------------------------------
| Nilai-nilai ini hANYA fallback. Sumber utama adalah database:
|   - tbl_pengaturan (kunci = 'mode_aktif')  => mode berjalan
|   - tbl_mode         => label & fitur tiap mode (KAMPUS/SMA/SMP/SD/TK)
|
| Jika key 'mode_aktif' tidak ada di tbl_pengaturan, sistem memakai
| nilai fallback di bawah ini.
| -------------------------------------------------------------------
*/
$config['mode_default'] = 'SMP';
$config['kkm_default']  = 75;

/*
| Daftar mode yang valid. Dipakai untuk validasi & menu "ganti mode".
*/
$config['modes_valid'] = array('KAMPUS', 'SMA', 'SMP', 'SD', 'TK');