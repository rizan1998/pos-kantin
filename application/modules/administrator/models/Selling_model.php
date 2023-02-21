<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Selling_Model extends CI_Model {

        var $table = 'selling s';
        var $column_order = array('nota', 'date_nota', 'status');
        var $column_search = array('nota', 'date_nota', 'status');
        var $order = array('date_nota'=>'desc');
    
        public function __construct()
        {
            parent::__construct();
            $this->load->database();
        }
    
        public function list_items($nota){
            $query = $this->db->query("
                SELECT ds.inc_id,  ds.selling_id, ds.items_id, s.nota, s.date_nota, i.name, ds.price, sum(ds.qty) qty, SUM(ds.discount) discount
                FROM selling_detail ds
                LEFT JOIN selling s ON s.inc_id = ds.selling_id
                LEFT JOIN items i ON ds.items_id = i.inc_id
                WHERE nota = '$nota'
                GROUP BY i.name
            ");

            return $query->result_array();
        }

        private function _get_datatables_query()
        {
            $this->db->from($this->table);

            $tanggal_awal=date_format_db($this->input->post('start_date'));
            $tanggal_akhir=date_format_db($this->input->post('end_date'));
            
            if($this->input->post('list')==1){

                $this->db->where("date_nota >=", $tanggal_awal);
                $this->db->where("date_nota <=", $tanggal_akhir);

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
            return $this->db->count_all_results();
        }

        public function _report_list($start_date, $end_date, $status){
            $this->db->select('*');
            $this->db->from($this->table);
            $this->db->where('status', $status);
            $this->db->where("date_nota >=", $start_date);
            $this->db->where("date_nota <=", $end_date);

            $query = $this->db->get();
            return $query->result_array();
        }
        
        public function sell_detail($id){
            $this->db->select('s.*, tp.name pembayaran');
            $this->db->from($this->table);
            $this->db->join('type_paid tp', 's.type_of_payment = tp.inc_id', 'left');
            $this->db->where('s.id', $id);
            
            $query = $this->db->get();
            return $query->row_array();
        }
    
    }
    
    /* End of file Selling_Model.php */
    