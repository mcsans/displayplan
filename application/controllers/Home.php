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
		$this->db->select('dyelot, Text11');
		$this->db->from('dyelots');
		$this->db->where('State =', 25);
		$wanfeng = $this->db->get()->result();
		
		// $today = date('Y-m-d H:i:s');
		// $kemarin = date('Y-m-d 00:00:00', strtotime('0 days ago'));
		// $test = $this->second_db->query("SELECT TOP 100 * FROM dbo.領料檔 ORDER BY 開始時間 DESC")->result();
		// var_dump($test); die();
		
		foreach($wanfeng as $data) {
			$idwokp  = str_replace('/', '', $wanfeng[1]->dyelot) . 'KP' . $wanfeng[1]->Text11 . 'X';
			$results = $this->second_db->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 = '$idwokp'")->num_rows();

			if($results > 0) {
				$this->db->where('Dyelot', $data->dyelot);
        $this->db->update('dyelots', ['State' => 27]);

				$this->output->set_content_type('application/json');
        echo json_encode(['UpdateState' => 'success!']);
			}
		}
	}
}
