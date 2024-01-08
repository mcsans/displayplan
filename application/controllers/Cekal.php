<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cekal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->timbangan_ds = $this->load->database('timbangan_ds', TRUE);
        $this->timbangan_ax = $this->load->database('timbangan_ax', TRUE);
        $this->server       = $this->load->database('server', TRUE);
        $this->wanfeng      = $this->load->database('wanfeng', TRUE);

        $this->load->model('m_cekal');
    }

    public function index()
    {
        $this->load->view('Cekal/index');
    }

    public function readData($keyword = null)
    {
        $data['pagination'] = $this->m_cekal->pagination($keyword);
        echo $this->load->view('Cekal/table', $data, TRUE);
    }
}
