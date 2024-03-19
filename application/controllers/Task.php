<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Task extends CI_Controller
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
		$this->load->view('task/index');
	}

	public function readDataTask()
	{
		$data['data'] = $this->server->query("SELECT * FROM tbltask")->result();
		echo $this->load->view('task/table', $data, TRUE);
	}

	public function transData()
	{
		// TABLE DISPLAY PROSES
		// $displayProses = $this->wanfeng->query("SELECT * FROM tbldisplayproses WHERE start_time BETWEEN CURDATE() - INTERVAL 1 DAY AND NOW()")->result_array();
		$displayProses = $this->wanfeng->query("SELECT * FROM tbldisplayproses WHERE date(start_time) = '2024-03-07'")->result_array();

		$dataToInsert = [];

		foreach ($displayProses as $item) {
			$id = $item['id'];
			$id_wo = $item['id_wo'];
			$id_wo_ori = $item['id_wo_ori'];
			$proses = $item['proses'];
			$id_grey = $item['id_grey'];
			$nama_unit = $item['nama_unit'];
			$nama_proses = $item['nama_proses'];
			$user = $item['user'];
			$lastmodified = $item['lastmodified'];
			$start_time = $item['start_time'];
			$end_time = $item['end_time'];
			$j_kain = $item['j_kain'];

			$dataToInsert[] = "('$id', '$id_wo', '$id_wo_ori', '$proses', '$id_grey', '$nama_unit', '$nama_proses', '$user', '$lastmodified', '$start_time', '$end_time', '$j_kain')";
		}

		$valuesClause = implode(', ', $dataToInsert);

		$sql = "INSERT IGNORE INTO tbldisplayproses (id, id_wo, id_wo_ori, proses, id_grey, nama_unit, nama_proses, user, lastmodified, start_time, end_time, j_kain) VALUES $valuesClause";

		$this->server->query($sql);


		// TABLE DISPLAY PROSES HISTORY===============
		$displayProsesHistory = $this->wanfeng->query('SELECT * FROM tbldisplayproseshistory WHERE start_time BETWEEN CURDATE() - INTERVAL 1 DAY AND NOW()')->result_array();
		// $displayProsesHistory = $this->wanfeng->query("SELECT * FROM tbldisplayproseshistory WHERE date(start_time)  = '2024-03-07' ")->result_array();

		$dataToInsert = [];

		$reversedDisplayProsesHistory = array_reverse($displayProsesHistory);

		foreach ($reversedDisplayProsesHistory as $item) {
			$id_produksi = $item['id_produksi'];
			$id_wo = $item['id_wo'];
			$id_wo_ori = $item['id_wo_ori'];
			$id_dpr = $item['id_dpr'];
			$id_kode = $item['id_kode'];
			$kode = $item['kode'];
			$proses = $item['proses'];
			$waktu = $item['waktu'];
			$operator = $item['operator'];
			$lastmodified = $item['lastmodified'];
			$start_time = $item['start_time'];
			$end_time = $item['end_time'];

			$dataToInsert[] = "('$id_produksi', '$id_wo', '$id_wo_ori', '$id_dpr', '$id_kode', '$kode', '$proses', '$waktu', '$operator', '$lastmodified', '$start_time', '$end_time')";
		}

		$valuesClause = implode(', ', $dataToInsert);

		// $sql = "INSERT IGNORE INTO tbldisplayproseshistory (id_produksi, id_wo, id_wo_ori, id_dpr, id_kode, kode, proses, waktu, operator, lastmodified, start_time, end_time) VALUES $valuesClause";

		// $this->server->query($sql);

		$this->updateLastruntimeCount('transData');
	}

	public function callProcedure()
	{
		$this->server->query('CALL updateDisplay()');
		$this->server->query('CALL update_woori()');
		$this->server->query('CALL update_historydisplay()');
		$this->server->query('CALL deleteWoLama()');
		$this->server->query('CALL Monitoring()');
		$this->server->query('FLUSH HOSTS');

		$this->server->close();
		$this->updateLastruntimeCount('callProcedure');
	}

	public function updateStatusOrga()
	{
		$sqlQuery = "SELECT TOP 3 Dyelot, ReDye, State FROM Dyelots WHERE QueueTime >= DATEADD(day, -7, GETDATE())";
		$sqlResult = $this->db->query($sqlQuery)->result();

		$queryW = "";
		$queryIDJS = "";
		$queryIDWO = [];

		foreach ($sqlResult as $row) {
			$queryW .= "WHEN id_wo = '" . $row->Dyelot . "' THEN '" . $row->ReDye . "' ";
			$queryIDJS .= "WHEN id_wo = '" . $row->Dyelot . "' THEN '" . $row->State . "' ";
			$queryIDWO[] = "'" . $row->Dyelot . "'";
		}

		$queryID = implode(', ', $queryIDWO);

		$this->server->query("UPDATE tblwo SET id_w = CASE $queryW ELSE id_w END, id_js = CASE $queryIDJS ELSE id_js END WHERE id_wo IN ($queryID)");

		$this->updateLastruntimeCount('updateStatusOrga');
	}

	public function running($table)
	{
		$this->server->query("UPDATE tbltask SET status='1' WHERE name='$table'");
	}

	public function stopped($table)
	{
		$this->server->query("UPDATE tbltask SET status='0' WHERE name='$table'");
	}

	public function updateLastruntimeCount($table)
	{
		$lastruntime = date('Y-m-d H:i:s');
		$count = $this->server->query("SELECT count FROM tbltask WHERE name='$table'")->row()->count + 1;
		$this->server->query("UPDATE tbltask SET lastruntime='$lastruntime', count=$count WHERE name='$table'");
	}
}
