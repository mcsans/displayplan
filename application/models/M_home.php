<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_home extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function pagination($keyword)
    {
        $now = date('Y-m-d 00:00:00');
        $perMesin = $this->input->get('perMesin');

        // QUERY
		$this->db->select('*');
		$this->db->from('dyelots');
		$this->db->where('QueueTime >', '2023-08-23 00:00:00');
		$this->db->where('State !=', 40);
		$this->db->where('StartTime', null);
		$this->db->where('QueueTime !=', null);
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

        // var_dump($data['paginator'][0]); die();
        return $data;
    }
}
