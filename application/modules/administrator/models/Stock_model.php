<?php

class Stock_model extends CI_Model {

    var $table = 'items i';
    var $column_order = array('i.name', 'stock', 'code');
    var $column_search = array('i.name', 'stock', 'code');
    var $order = array('i.code'=>'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);
        $this->db->select('i.id, i.inc_id, i.code, i.name, t.name as type_name, c.name as category_name, u.name as unit_name, stock');
        $this->db->join('type t', 'i.type_id = t.inc_id', 'left');
        $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        
        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {

                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }

        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
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
        $this->db->select('i.id, i.inc_id, i.code, i.name, t.name as type_name, c.name as category_name, u.name as unit_name, stock');
        $this->db->join('type t', 'i.type_id = t.inc_id', 'left');
        $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        return $this->db->count_all_results();
    }
    
    public function view_product($where){
        $this->db->select('i.id, i.inc_id, i.code, i.name, t.name as type_name, c.name as category_name, u.name as unit_name, price, price_ppn, ppn, promo, stock');
        $this->db->from('items i');
        $this->db->join('type t', 'i.type_id = t.inc_id', 'left');
        $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        $this->db->where($where);
        
        $query = $this->db->get();
        return $query->row_array();    
    }

    public function items_sell_get($where){
        $this->db->select('isell.id, isell.inc_id, i.code, i.name, u.inc_id as unit_id, u.name as unit_name, discount, price_sell, type_price');
        $this->db->from('items_sell isell');
        $this->db->join('items i', 'isell.item_id = i.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        $this->db->where($where);
        
        $query = $this->db->get();
        return $query->result_array();    
    }

    function _get_product_selling($like="") {
        $this->db->select('isell.id, isell.inc_id, isell.item_id, i.code, i.name, u.inc_id as unit_id, u.name as unit_name, discount, price_sell, type_price');
        $this->db->from('items_sell isell');
        $this->db->join('items i', 'isell.item_id = i.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        $this->db->like('i.name', "$like");
        $this->db->where('isell.type_price', 1); //retail
        
        
        $query = $this->db->get();
        return $query->result_array();
    }     

    function _get_product_category($category_id="") {
        $this->db->select('isell.id, isell.inc_id, i.code, i.name, u.inc_id as unit_id, u.name as unit_name, discount, price_sell, type_price');
        $this->db->from('items_sell isell');
        $this->db->join('items i', 'isell.item_id = i.inc_id', 'left');
        $this->db->join('unit u', 'i.unit_id = u.inc_id', 'left');
        $this->db->where('i.category_id', $category_id);
        $this->db->where('isell.type_price', 1); //retail
        
        
        $query = $this->db->get();
        return $query->result_array();
    }     

    public function stock_list(){
        $this->db->select('code, i.name, min_stok, stock, price, ppn, price_ppn, u.name as unit, t.name as type, c.name as category');
        $this->db->from($this->table);
        $this->db->join('unit u', 'u.inc_id = i.unit_id', 'left');
        $this->db->join('type t', 't.inc_id = i.type_id', 'left');
        $this->db->join('category c', 'c.inc_id = i.category_id', 'left');
        
        $query = $this->db->get();
        return $query->result_array();
    }

}
