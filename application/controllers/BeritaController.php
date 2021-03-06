<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BeritaController extends CI_Controller {

	const MODE_BERITA = [
		"BERITA"		=> "berita",
		"PENGUMUMAN"	=> "pengumuman",
		"AGENDA"		=> "agenda"
	];

	public function detail($id, $mode){
		$data['mode'] = $mode;
		$this->load->view('global_css');
		$this->load->view('header_mobile');
		$this->load->view('header');
		if($mode == BeritaController::MODE_BERITA["AGENDA"]){
			$data['berita'] = $this->model_agenda->agenda_edit($id)->first_row('array');
			$this->load->view('berita/display_agenda', $data);
		} else {
			$data['berita'] = $this->model_berita->getBerita($id);
			$this->load->view('berita/display_berita', $data);
		}
		$this->load->view('footer');
		$this->load->view('global_js');
	}
}
