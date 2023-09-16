<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_home extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
		$this->timbangan_ds = $this->load->database('timbangan_ds', TRUE);
		$this->timbangan_ax = $this->load->database('timbangan_ax', TRUE);
    }

    public function pagination($keyword)
    {
        $now = date('Y-m-d 00:00:00');
        $perMesin = $this->input->get('perMesin');

        // QUERY
		$this->db->select('QueueTime, Machine, Dyelot, Text11, Article, ColourDescript, ColourNo, Weight, State, Text20');
		$this->db->from('dyelots');
        
		$this->db->where('QueueTime >', '2023-08-28 00:00:00');
		$this->db->where('Machine !=', 'TEMP');
		$this->db->where('State', 25);
		
        if($perMesin != 'ALL') {
            $this->db->where('Machine', $perMesin);
        }
        if ($keyword != "") {
            $this->db->group_start();
            $this->db->like('dyelots.Machine', base64_decode($keyword));
            $this->db->or_like('dyelots.Text11', base64_decode($keyword));
            $this->db->or_like('dyelots.Article', base64_decode($keyword));
            $this->db->or_like('dyelots.ColourDescript', base64_decode($keyword));
            $this->db->or_like('dyelots.ColourNo', base64_decode($keyword));
            $this->db->or_like('dyelots.Weight', base64_decode($keyword));
            $this->db->or_like('dyelots.QueueTime', base64_decode($keyword));
            $this->db->or_like('dyelots.State', base64_decode($keyword));
            $this->db->group_end();
        }
        $this->db->order_by('dyelots.Machine', 'asc');
        $this->db->order_by('dyelots.QueueTime', 'asc');
        // END QUERY

        $query      = clone $this->db;
        $now        = $this->input->get('page');
        $perPage    = $this->input->get('perPage');
        $total_rows = $this->db->get()->num_rows();

        $data = [
            'now'      => $now,
            'from'     => $now * $perPage - $perPage,
            'to'       => ($total_rows < $perPage) ? $total_rows : $perPage * $now,
            'total'    => $total_rows,
            'perPage'  => $perPage,
            'lastPage' => (int) ceil($total_rows / $perPage),
        ];

        $data['paginator'] = $query->get('', $data['perPage'], $data['from'])->result_array();

        return $data;
    }

	public function backupBanner()
	{
		// // update banner
		// $i = 0;
		// foreach($data['paginator'] as $orgatex) {
		// 	$ds = str_replace('/', '', $orgatex['Dyelot']) . 'KP' . $orgatex['Text11'];
		// 	$dsResults = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 LIKE '%$ds%'")->num_rows();
			
		// 	$ax = str_replace('/', '', $orgatex['Dyelot']) . 'KP' . $orgatex['Text11'];
		// 	$axResults = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 LIKE '%$ax%'")->num_rows();
			
		// 	$dsTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '" .$orgatex['Dyelot']. "' AND RecipeUnit = '%'")->num_rows();
		// 	$axTotal = $this->db->query("SELECT Dyelot FROM Dyelot_recipe WHERE Dyelot = '" .$orgatex['Dyelot']. "' AND RecipeUnit = 'g/l'")->num_rows();

		// 	// var_dump($dsResults . ' ds result');
		// 	// var_dump($dsTotal . ' ds total');
		// 	// var_dump($axResults . ' ax result');
		// 	// var_dump($axTotal . ' ax total');
		// 	// echo '<hr>';

		// 	if($dsResults == $dsTotal) {
		// 		$data['paginator'][$i]['Text20'] = 1;
		// 	}
			
		// 	if($axResults == $axTotal) {
		// 		$data['paginator'][$i]['Text20'] = 2;
		// 	}

		// 	$i++;
		// }
	}
}
