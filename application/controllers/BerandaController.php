<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BerandaController extends CI_Controller {

	// function __construct()
	// {
	// 	parent::__construct();
	// 	// if ($this->session->userdata('logged_in') !== TRUE) {
	// 	//   redirect('login');
	// 	// }
	// 	$this->load->model('Model_Berita');
	// 	date_default_timezone_set('Asia/Bangkok');
	// }

	public function index(){

		// print_r($this->model_berita->totalBerita(4));
		$data['news'] = $this->model_berita->lastNews(4);
		$data['announc'] = $this->model_berita->lastAnnouncement(3);
		$data['headline'] = $this->model_berita->headLine(3);
		$data['agenda'] = $this->model_agenda->lastAgenda(3);
        $this->load->view('global_css');
        $this->load->view('header_mobile');
        $this->load->view('header');
		$this->load->view('beranda', $data);
		$this->load->view('footer');
		$this->load->view('specificJS/beranda_js');
		$this->load->view('global_js');
	}

	public function allNews(){
		$data['news'] = $this->model_berita->allNews();
        $this->load->view('global_css');
        $this->load->view('header_mobile');
        $this->load->view('header');
		$this->load->view('all_news', $data);
		$this->load->view('footer');
		$this->load->view('global_js');
	}

	public function pageNews(){
        $this->load->view('global_css');
        $this->load->view('header_mobile');
        $this->load->view('header');
		$this->load->view('news_page');
		$this->load->view('footer');
		$this->load->view('global_js');
	}
}
