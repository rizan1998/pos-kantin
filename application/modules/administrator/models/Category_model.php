<?php

class Category_model extends CI_Model
{

    var $table = 'category';
    var $column_order = array('name');
    var $column_search = array('name');
    var $order = array('name' => 'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->where('ket !=', 'DELETE');

        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {

        $this->db->from($this->table);
        $this->db->where('ket !=', 'DELETE');

        return $this->db->count_all_results();
    }

    public function penjualan_per_category()
    {

        $firtMonth = date('Y-m-01');
        $lastMonth = date('Y-m-t');

        $this->db->select('c.inc_id, c.name, sum(sd.price) as jumlah_penjulan, sd.qty');
        $this->db->from('category c');
        $this->db->join('items i', 'c.inc_id = i.category_id', 'left');
        $this->db->join('selling_detail sd', 'i.inc_id = sd.items_id', 'left');
        $this->db->where('sd.datetime >=', $firtMonth);
        $this->db->where('sd.datetime <=', $lastMonth);
        // $this->db->where('MONTH(sd.datetime)', $month);
        // $this->db->where('YEAR(sd.datetime)', $year);
        $this->db->group_by('c.inc_id');
        $query = $this->db->get();

        return $query->result_array();
    }
}
