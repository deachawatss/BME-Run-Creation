<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'third_party/fpdf/cfpdf.php';
class Fpdf_master extends FPDF {
		
	public function __construct() {
		
		$pdf = new PDF();
		#$pdf->AddPage();
		
		$CI =& get_instance();
		$CI->fpdf = $pdf;
		
	}
	
	
	
	
	
	
	
}