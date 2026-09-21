<?php

	require_once APPPATH.'/libraries/fpdf.php';

	class CFPDF extends FPDF {

		function Footer() {
			$this->SetY(-10);
			$this->SetFont('Arial', '', 8);
			$this->Cell(0, 10, 'Halaman '.$this->PageNo().' / {nb}', 0, 0, 'C');
		}

	}

?>