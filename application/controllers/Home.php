<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->timbangan_ds = $this->load->database('timbangan_ds', TRUE);
		$this->timbangan_ax = $this->load->database('timbangan_ax', TRUE);
		$this->server       = $this->load->database('server', TRUE);
		$this->wanfeng      = $this->load->database('wanfeng', TRUE);

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
		$this->db->select('Dyelot, Text11');
		$this->db->from('Dyelots');
		$this->db->where('State', 25);
		$orgatex = $this->db->get()->result();

		foreach($orgatex as $data) {
			$ds = str_replace('/', '', $data->Dyelot) . 'KP' . $data->Text11;
			$dsResults = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 LIKE '%$ds%'");
			
			$ax = str_replace('/', '', $data->Dyelot) . 'KP' . $data->Text11;
			$axResults = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 LIKE '%$ax%'");
			
			$dsTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '$data->Dyelot' AND RecipeUnit = '%'")->num_rows();
			$axTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '$data->Dyelot' AND RecipeUnit = 'g/l'")->num_rows();

			// update state 27
			if($dsResults->num_rows() == $dsTotal && $axResults->num_rows() == $axTotal) {
				$this->db->where('Dyelot', $data->Dyelot);
        $this->db->update('Dyelots', ['State' => 27]);

				// actual amount
				if($dsResults->num_rows() > 0) {
					foreach($dsResults->result() as $dsRes) {
						$idwokp = $dsRes->唯一編號;
						$idwo  	= substr($idwokp, 0, 2) . '/' . substr($idwokp, 2, 4) . '/' . substr($idwokp, 6, 4);
						
						$this->db->where('Dyelot', $idwo);
						$this->db->where('ProductCode', $dsRes->藥劑編號);
						$this->db->update('Dyelot_Recipe', ['ActualAmount' => $dsRes->實際重量]);
					}
				}

				if($axResults->num_rows() > 0) {
					foreach($axResults->result() as $axRes) {
						$idwokp = $axRes->唯一編號;
						$idwo  	= substr($idwokp, 0, 2) . '/' . substr($idwokp, 2, 4) . '/' . substr($idwokp, 6, 4);
	
						$this->db->where('Dyelot', $idwo);
						$this->db->where('ProductCode', $axRes->藥劑編號);
						$this->db->update('Dyelot_Recipe', ['ActualAmount' => $axRes->實際重量]);
					}
				}

				$this->updateLastruntimeCount('updateState');
			} else {
				// update centang hijau
				if($dsResults->num_rows() == $dsTotal) {
					$this->db->where('Dyelot', $data->Dyelot);
        	$this->db->update('Dyelots', ['Text20' => 1]);
				}
				
				if($axResults->num_rows() == $axTotal) {
					$this->db->where('Dyelot', $data->Dyelot);
        	$this->db->update('Dyelots', ['Text20' => 2]);
				}
			}
		}
	}

	public function updateLastruntimeCount($table) {
		$lastruntime = date('Y-m-d H:i:s');
		$count = $this->server->query("SELECT count FROM tbltask WHERE name='$table'")->row()->count + 1;
		$this->server->query("UPDATE tbltask SET lastruntime='$lastruntime', count=$count WHERE name='$table'");
	}

	public function Test() {
		// $today = date('Y-m-d H:i:s');
		// $kemarin = date('Y-m-d 00:00:00', strtotime('0 days ago'));
		// $ds = $this->timbangan_ds->query("SELECT TOP 100 * FROM dbo.領料檔 ORDER BY 開始時間 DESC")->result();
		// $ax = $this->timbangan_ax->query("SELECT TOP 100 * FROM dbo.領料檔 ORDER BY 開始時間 DESC")->result();

		// $ds = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 LIKE '%WO09230240KP3827%'")->result();
		// $ax = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 LIKE '%WO09230240KP3827%'")->result();

		// var_dump($ds); 
		// echo '<hr>';
		// var_dump($ax); 
		// die();
	}
}
