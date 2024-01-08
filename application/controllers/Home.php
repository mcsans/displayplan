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

	public function readData($keyword = null)
	{
		$data['pagination'] = $this->m_home->pagination($keyword);
		echo $this->load->view('home/table', $data, TRUE);
	}

	public function endPlan($idwo, $kp)
	{
		$this->db->where('Dyelot', base64_decode($idwo));
		$this->db->update('Dyelots', ['State' => 27]);

		$ds = str_replace('/', '', base64_decode($idwo)) . 'KP' . $kp;
		$dsResults = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 唯一編號 LIKE '%$ds%' ORDER BY 開始時間 DESC");

		$ax = str_replace('/', '', base64_decode($idwo)) . 'KP' . $kp;
		$axResults = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 唯一編號 LIKE '%$ax%' ORDER BY 開始時間 DESC");

		if ($dsResults->num_rows() > 0) {
			foreach ($dsResults->result() as $dsRes) {
				$idwokp = $dsRes->唯一編號;
				$idwo  	= substr($idwokp, 0, 2) . '/' . substr($idwokp, 2, 4) . '/' . substr($idwokp, 6, 4);

				$this->db->where('Dyelot', $idwo);
				$this->db->where('ProductShortName', $dsRes->藥劑編號);
				$this->db->group_start();
				$this->db->where('ActualAmount', 0);
				$this->db->or_where('ActualAmount', null);
				$this->db->group_end();
				$this->db->update('Dyelot_Recipe', ['ActualAmount' => $dsRes->實際重量]);
			}
		}

		if ($axResults->num_rows() > 0) {
			foreach ($axResults->result() as $axRes) {
				$idwokp = $axRes->唯一編號;
				$idwo  	= substr($idwokp, 0, 2) . '/' . substr($idwokp, 2, 4) . '/' . substr($idwokp, 6, 4);

				$this->db->where('Dyelot', $idwo);
				$this->db->where('ProductShortName', $axRes->藥劑編號);
				$this->db->group_start();
				$this->db->where('ActualAmount', 0);
				$this->db->or_where('ActualAmount', null);
				$this->db->group_end();
				$this->db->update('Dyelot_Recipe', ['ActualAmount' => $axRes->實際重量]);
			}
		}
	}

	public function updateState()
	{
		$this->db->select('Dyelot, ReDye, Text11, LoadTime');
		$this->db->from('Dyelots');
		$this->db->where('State', 25);
		$orgatex = $this->db->get()->result();

		foreach ($orgatex as $data) {

			//Ini yang otomatis ya?

			// if ($data->ReDye == 0) {
			$ds = str_replace('/', '', $data->Dyelot) . 'KP' . $data->Text11;
			$dsResults = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 唯一編號 LIKE '%$ds%' ORDER BY 開始時間 DESC");

			$ax = str_replace('/', '', $data->Dyelot) . 'KP' . $data->Text11;
			$axResults = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 唯一編號 LIKE '%$ax%' ORDER BY 開始時間 DESC");

			$dsTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '$data->Dyelot' AND RecipeUnit = '%'")->num_rows();
			$axTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '$data->Dyelot' AND RecipeUnit = 'g/l'")->num_rows(); // -1;
			// } else {
			// 	$ds = str_replace('/', '', $data->Dyelot) . 'KP' . $data->Text11;
			// 	$dsResults = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 開始時間 >= '$data->LoadTime' AND 唯一編號 LIKE '%$ds%'");

			// 	$ax = str_replace('/', '', $data->Dyelot) . 'KP' . $data->Text11;
			// 	$axResults = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 開始時間 >= '$data->LoadTime' AND 唯一編號 LIKE '%$ax%'");

			// 	$dsTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '$data->Dyelot' AND RecipeUnit = '%'")->num_rows();
			// 	$axTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '$data->Dyelot' AND RecipeUnit = 'g/l'")->num_rows() -1;
			// }

			// update state 27
			if ($dsResults->num_rows() >= $dsTotal && $axResults->num_rows() >= $axTotal && $dsResults->num_rows() > 0 && $axResults->num_rows() > 0) {
				// $this->db->where('Dyelot', $data->Dyelot);
				// $this->db->update('Dyelots', ['State' => 27]);

				// actual amount
				if ($dsResults->num_rows() > 0) {

					foreach ($dsResults->result() as $dsRes) {
						$idwokp = $dsRes->唯一編號;
						$idwo  	= substr($idwokp, 0, 2) . '/' . substr($idwokp, 2, 4) . '/' . substr($idwokp, 6, 4);

						$this->db->where('Dyelot', $idwo);
						$this->db->where('ProductShortName', $dsRes->藥劑編號);
						$this->db->where('ActualAmount', 0);
						$this->db->update('Dyelot_Recipe', ['ActualAmount' => $dsRes->實際重量]);
					}
				}

				if ($axResults->num_rows() > 0) {
					foreach ($axResults->result() as $axRes) {
						$hdwokp = $axRes->唯一編號;
						$idwo  	= substr($idwokp, 0, 2) . '/' . substr($idwokp, 2, 4) . '/' . substr($idwokp, 6, 4);

						$this->db->where('Dyelot', $idwo);
						$this->db->where('ProductShortName', $axRes->藥劑編號);
						$this->db->where('ActualAmount', 0);
						$this->db->update('Dyelot_Recipe', ['ActualAmount' => $axRes->實際重量]);
					}
				}
			}
			// update centang hijau
			if ($dsResults->num_rows() >= $dsTotal && $dsResults->num_rows() > 0) {
				$this->db->where('Dyelot', $data->Dyelot);
				$this->db->update('Dyelots', ['Text19' => 1]);
			}

			if ($axResults->num_rows() >= $axTotal && $axResults->num_rows() > 0) {
				$this->db->where('Dyelot', $data->Dyelot);
				$this->db->update('Dyelots', ['Text20' => 1]);
			}
		}

		$this->updateLastruntimeCount('updateState');
	}

	public function updateLastruntimeCount($table)
	{
		$lastruntime = date('Y-m-d H:i:s');
		$count = $this->server->query("SELECT count FROM tbltask WHERE name='$table'")->row()->count + 1;
		$this->server->query("UPDATE tbltask SET lastruntime='$lastruntime', count=$count WHERE name='$table'");
	}

	public function TestActualWanfeng()
	{
		$id_ch = 'MC/0713/0008';
		$tgl_mulai = '2023-10-03';
		$tgl_akhir = '2023-10-03 23:59:59';

		$query = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 藥劑編號 = '$id_ch' AND 實際重量 != 0 AND 開始時間 >= '$tgl_mulai' AND 開始時間 <= '$tgl_akhir'")->result();

		var_dump($query);
		die();
	}

	public function Test()
	{
		// $today = date('Y-m-d H:i:s');
		// $kemarin = date('Y-m-d 00:00:00', strtotime('0 days ago'));
		// $ds = $this->timbangan_ds->query("SELECT TOP 100 * FROM dbo.領料檔 ORDER BY 開始時間 DESC")->result();
		// $ax = $this->timbangan_ax->query("SELECT TOP 100 * FROM dbo.領料檔 ORDER BY 開始時間 DESC")->result();

		$dsResult = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 唯一編號 LIKE '%WO11230580KP5209%'")->result_array();
		$dsTotal = $this->db->query("SELECT Dyelot, ProductShortName FROM Dyelot_recipe WHERE Dyelot = 'WO/1123/0580' AND RecipeUnit = '%'")->result_array();

		$axResult = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 實際重量 != 0 AND 唯一編號 LIKE '%WO11230580KP5209%'")->result_array();
		$axTotal = $this->db->query("SELECT Dyelot, ProductShortName FROM Dyelot_recipe WHERE Dyelot = 'WO/1123/0580' AND RecipeUnit = 'g/l'")->result_array();

		var_dump($dsResult);
		var_dump($dsTotal);
		echo '<hr>';
		var_dump($axResult);
		var_dump($axTotal);
		die();
	}
}
