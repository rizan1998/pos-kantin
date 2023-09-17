<?php

class Product_model extends CI_Model
{

    var $table = 'items i';
    var $column_order = array('i.name');
    var $column_search = array('i.name');
    var $order = array('i.name' => 'asc');


    var $column_order_item_sell = array('i.name');
    var $column_search_item_sell = array('i.name');
    var $order_item_sell = array('i.name' => 'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->select('i.id, i.inc_id, i.code, i.name, t.name as type_name, c.name as category_name, u.name as unit_name');
        $this->db->join('type t', 'i.type_id = t.inc_id', 'left');
        $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');

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
        $this->db->select('i.id, i.inc_id, i.code, i.name, t.name as type_name, c.name as category_name, u.name as unit_name');
        $this->db->join('type t', 'i.type_id = t.inc_id', 'left');
        $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        return $this->db->count_all_results();
    }

    public function view_product($where)
    {
        $this->db->select('i.id, i.inc_id, i.code, i.name, t.name as type_name, c.name as category_name, u.name as unit_name, price, price_ppn, ppn, promo');
        $this->db->from('items i');
        $this->db->join('type t', 'i.type_id = t.inc_id', 'left');
        $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        $this->db->where($where);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function items_sell_get($where)
    {
        $this->db->select('isell.id, isell.inc_id, i.code, i.name, u.inc_id as unit_id, u.name as unit_name, isell.discount, price_sell, type_price, isell.stock_item_sell');
        $this->db->from('items_sell isell');
        $this->db->join('items i', 'isell.item_id = i.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        // $this->db->join('transaction_in_detail ti', 'isell.trans_in_detail_id = ti.inc_id', 'left');
        // $this->db->join('transaction_in t', 'ti.trans_in = t.inc_id', 'left');s
        $this->db->where($where);
        $this->db->where('isell.ket !=', 'DELETE');
        $this->db->group_by('isell.inc_id');

        $query = $this->db->get();
        return $query->result_array();
    }

    private function _get_datatables_items_sell_query()
    {
        $this->db->from('items_sell is');
        $this->db->select('is.*, is.inc_id as id, i.name, un.name as unit_name');
        $this->db->join('items i', 'is.item_id = i.inc_id', 'left');
        $this->db->join('unit un', 'is.unit_id = un.inc_id', 'left');
        $this->db->where("is.ket !=", 'DELETE');
        $this->db->group_by('is.inc_id');

        $i = 0;
        foreach ($this->column_search_item_sell as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search_item_sell) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order_sell[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_items_sell()
    {
        $this->_get_datatables_items_sell_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_items_sell()
    {
        $this->_get_datatables_items_sell_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_items_sell()
    {
        $this->db->from('items_sell is');
        $this->db->select('is.*');
        $this->db->join('items i', 'is.item_id = i.inc_id', 'left');
        $this->db->group_by('is.inc_id');
        return $this->db->count_all_results();
    }

    private function _get_datatables_items_query()
    {
        $this->db->select("i.inc_id as id, i.code, i.name, c.name as category_name, t.name as type_name, u.name as unit_name");
        $this->db->from("items i");
        $this->db->join("type t", 'i.type_id = t.inc_id', 'left');
        $this->db->join("category c", 'i.category_id = c.inc_id', 'left');
        $this->db->join("unit u", 'i.unit_id = u.inc_id', 'left');
        $this->db->group_by('i.inc_id');

        $i = 0;
        foreach ($this->column_search_item_sell as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search_item_sell) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order_sell[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables_items()
    {
        $this->_get_datatables_items_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered_items()
    {
        $this->_get_datatables_items_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_items()
    {
        $this->db->select("i.code, i.name, c.name as category_name, t.name as type_name, u.name as unit_name");
        $this->db->from("items i");
        $this->db->join("type t", 'i.type_id = t.inc_id', 'left');
        $this->db->join("category c", 'i.category_id = c.inc_id', 'left');
        $this->db->join("unit u", 'i.unit_id = u.inc_id', 'left');
        $this->db->group_by('i.inc_id');
        return $this->db->count_all_results();
    }
}
