<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->second_db = $this->load->database('second_db', TRUE);

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

	public function updateState() {
		$this->db->select('dyelot');
		$this->db->from('dyelots');
		$this->db->where('State =', 25);
		$query = $this->db->get()->result();

		// $today = date('Y-m-d H:i:s');
		// $kemarin = date('Y-m-d 00:00:00', strtotime('0 days ago'));

		foreach($query as $orgatex) {
			$query = $this->second_db->query("SELECT *, 'WO/' + SUBSTRING(唯一編號, 3, 4) + '/' + SUBSTRING(唯一編號, 7, 4) AS ID_WO FROM dbo.領料檔 WHERE  'WO/' + SUBSTRING(唯一編號, 3, 4) + '/' + SUBSTRING(唯一編號, 7, 4) = '$orgatex->dyelot'");
    	$results = $query->num_rows();

			if($results > 0) {
				$this->db->where('Dyelot', $orgatex->dyelot);
        $this->db->update('dyelots', ['State' => 27]);
			}
		}
	}
}
