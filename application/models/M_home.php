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
		$this->db->select('*');
		$this->db->from('dyelots');
        
		$this->db->where('QueueTime >', '2023-08-28 00:00:00');
		$this->db->where('Machine !=', 'TEMP');
		$this->db->where('State', 25);

		// $this->db->where('State !=', 40);
		// $this->db->where('StartTime', null);
		// $this->db->where('QueueTime !=', null);
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

		// update banner
		$i = 0;
		foreach($data['paginator'] as $orgatex) {
			$ds = str_replace('/', '', $orgatex['Dyelot']) . 'KP' . $orgatex['Text11'] . 'D';
			$dsResults = $this->timbangan_ds->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 = '$ds'")->num_rows();
			
			$ax = str_replace('/', '', $orgatex['Dyelot']) . 'KP' . $orgatex['Text11'] . 'X';
			$axResults = $this->timbangan_ax->query("SELECT * FROM dbo.領料檔 WHERE 唯一編號 = '$ax'")->num_rows();

			if($dsResults > 0) {
				$data['paginator'][0]['Text20'] = 1;
			}
			
			if($axResults > 0) {
				$data['paginator'][0]['Text20'] = 2;
			}

			$i++;
		}

        return $data;
    }
}
