<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Debt_model extends CI_Model {

        var $table = 'debt s';
        var $column_order = array('debt_date', 's.status', 'employee', 'paid_off', 'date_paidoff', 'type_of_paid');
        var $column_search = array('debt_date', 's.status', 'employee', 'paid_off', 'date_paidoff', 'type_of_paid');
        var $order = array('debt_date'=>'desc');
    
        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
    
        

        private function _get_datatables_query()
        {
            $this->db->select('sell.id as id_sell, sell.nota, sell.date_nota, s.*');
            
            $this->db->from($this->table);
            $this->db->join('selling sell', 'sell.inc_id = s.selling_id', 'left');
            
            
            $tanggal_awal=date_format_db($this->input->post('start_date'));
            $tanggal_akhir=date_format_db($this->input->post('end_date'));
            
            if($this->input->post('list')==1){
                
                $this->db->where("debt_date >=", $tanggal_awal);
                $this->db->where("debt_date <=", $tanggal_akhir);

                if($this->input->post('status') == 2){
                    $this->db->where("s.status ", $this->input->post('status'));
                    $this->db->where('sell.status', 3);
                }else {
                    $this->db->where("s.status ", 1);
                    $this->db->where('sell.status', 5);
                }

                if($this->input->post('employee') == NULL ){
                    $this->db->like("s.employee ", $this->input->post('employee'));
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

            $this->db->select('sell.id as id_sell, sell.nota, sell.date_nota, s.*');
            
            $this->db->from($this->table);
            $this->db->join('selling sell', 'sell.inc_id = s.selling_id', 'left');
            $this->db->where('sell.status', 5);
            $tanggal_awal=date_format_db($this->input->post('start_date'));
            $tanggal_akhir=date_format_db($this->input->post('end_date'));
            
            if($this->input->post('list')==1){

                $this->db->where("debt_date >=", $tanggal_awal);
                $this->db->where("debt_date <=", $tanggal_akhir);

                if($this->input->post('status') != "-"){
                    $this->db->where("s.status ", $this->input->post('status'));
                }

                if($this->input->post('employee') == NULL){
                    $this->db->like("s.employee ", $this->input->post('employee'));
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
        
        public function _report_list_debt($start_date, $end_date, $status, $employee){
            $this->db->select('sell.nota, sell.date_nota, s.*');
            
            $this->db->from($this->table);
            $this->db->join('selling sell', 'sell.inc_id = s.selling_id', 'left');
            
            $status == 1 ? $sellstatus = 5 : $sellstatus = 3;
            $this->db->where('sell.status', $sellstatus);
            $this->db->where("debt_date >=", $start_date);
            $this->db->where("debt_date <=", $end_date);
            $this->db->where("s.status ", $status);
            $this->db->like("s.employee ", $employee);

            $query = $this->db->get();
            return $query->result_array();
        }

        public function _report_list_debt_id($start_date, $end_date, $status, $employee){            
            $this->db->from($this->table);
            $this->db->where("debt_date >=", $start_date);
            $this->db->where("debt_date <=", $end_date);
            $this->db->where("s.status ", $status);
            $this->db->like("s.employee ", $employee);

            $query = $this->db->get();
            return $query->row_array();
        }

    }
    
    /* End of file Selling_Model.php */
    