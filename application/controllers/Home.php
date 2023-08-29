<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('m_home');
	}

	public function index()
	{
		$this->load->view('home/index');
	}

	public function readData($keyword=null)
	{
			$data['pagination'] = $this->m_home->pagination($keyword);
			echo $this->load->view('home/table', $data, TRUE);
	}
}
