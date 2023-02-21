<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Transaction_In_Model extends CI_Model {

        var $table = 'transaction_in s';
        var $column_order = array('code_in', 'date_in', 'status');
        var $column_search = array('code_in', 'date_in', 'status');
        var $order = array('date_in'=>'desc');
    
        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
    
        

        private function _get_datatables_query()
        {
            $this->db->from($this->table);

            $tanggal_awal=date_format_db($this->input->post('start_date'));
            $tanggal_akhir=date_format_db($this->input->post('end_date'));
            
            if($this->input->post('list')==1){

                $this->db->where("date_in >=", $tanggal_awal);
                $this->db->where("date_in <=", $tanggal_akhir);

                if($this->input->post('status') != "-"){
                    $this->db->where("status ", $this->input->post('status'));
                }

            }
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
            $tanggal_awal=date_format_db($this->input->post('start_date'));
            $tanggal_akhir=date_format_db($this->input->post('end_date'));
            
            if($this->input->post('list')==1){

                $this->db->where("date_in >=", $tanggal_awal);
                $this->db->where("date_in <=", $tanggal_akhir);

                if($this->input->post('status') != "-"){
                    $this->db->where("status ", $this->input->post('status'));
                }

            }
            return $this->db->count_all_results();
        }

        public function _item_list($trans){
            $query = $this->db->query("
                SELECT i.name, ti.qty, ti.price, ti.price, ti.inc_id, ti.discount
                FROM transaction_in_detail ti 
                JOIN items i ON ti.items_id = i.inc_id
                WHERE ti.trans_in = $trans
            ");

            return $query->result_array();
        }
    
    
    }
    
    /* End of file Selling_Model.php */
    