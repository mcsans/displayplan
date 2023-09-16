<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller {

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

	public function transData() {
		// TABLE DISPLAY PROSES
		$displayProses = $this->wanfeng->query("SELECT * FROM tbldisplayproses  WHERE start_time BETWEEN  CURDATE() - INTERVAL 1 DAY AND NOW()")->result_array();

		$sql = "INSERT INTO tbldisplayproses (id, id_wo, id_wo_ori, proses, id_grey, nama_unit, nama_proses, user, lastmodified, start_time, end_time, j_kain) VALUES ";
		$update_sql = " ON DUPLICATE KEY UPDATE id = VALUES(id), id_wo = VALUES(id_wo), id_wo_ori = VALUES(id_wo_ori), proses = VALUES(proses), id_grey = VALUES(id_grey), nama_unit = VALUES(nama_unit), nama_proses = VALUES(nama_proses), user = VALUES(user), lastmodified = VALUES(lastmodified), start_time = VALUES(start_time), end_time = VALUES(end_time), j_kain = VALUES(j_kain)";

		$value_strings = array();
		$values = array();
		foreach ($displayProses as $row) {
				$value_strings[] = '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
				$values = array_merge($values, array_values($row));
		}

		$sql .= implode(', ', $value_strings);
		$sql .= $update_sql;

		$this->server->query($sql, $values);

		// TABLE DISPLAY PROSES HISTORY
		$displayProsesHistory = $this->wanfeng->query('SELECT * FROM tbldisplayproseshistory   WHERE start_time BETWEEN  CURDATE() - INTERVAL 1 DAY AND NOW()')->result_array();

		$sql = "INSERT INTO tbldisplayproseshistory (id_produksi, id_wo, id_wo_ori, id_dpr, id_kode, kode, proses, waktu, operator, lastmodified, start_time, end_time) VALUES ";
		$update_sql = " ON DUPLICATE KEY UPDATE id_produksi = VALUES(id_produksi), id_wo = VALUES(id_wo), id_wo_ori = VALUES(id_wo_ori), id_dpr = VALUES(id_dpr), id_kode = VALUES(id_kode), kode = VALUES(kode), proses = VALUES(proses), waktu = VALUES(waktu), operator = VALUES(operator), lastmodified = VALUES(lastmodified), start_time = VALUES(start_time), end_time = VALUES(end_time)";

		$value_strings = array();
		$values = array();
		foreach ($displayProsesHistory as $row) {
				$value_strings[] = '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
				$values = array_merge($values, array_values($row));
		}

		$sql .= implode(', ', $value_strings);
		$sql .= $update_sql;

		$this->server->query($sql, $values);

		$this->updateLastruntimeCount('transData');
	}

	public function callProcedure() {
		$this->server->query('CALL updateDisplay()');
		$this->server->query('CALL update_woori()');
		$this->server->query('CALL update_historydisplay()');
		$this->server->query('CALL deleteWoLama()');
		$this->server->query('CALL Monitoring()');
		$this->server->query('FLUSH HOSTS');

		$this->updateLastruntimeCount('callProcedure');
	}

	public function updateLastruntimeCount($table) {
		$lastruntime = date('Y-m-d H:i:s');
		$count = $this->server->query("SELECT count FROM tbltask WHERE name='$table'")->row()->count + 1;
		$this->server->query("UPDATE tbltask SET lastruntime='$lastruntime', count=$count WHERE name='$table'");
	}
}
