<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stockopname_Model extends CI_Model
{

    public $table = 'stockopname s';
    public $column_order = array('code_in', 'date_stockopname', 'status');
    public $column_search = array('code_in', 'date_stockopname', 'status');
    public $order = array('date_stockopname' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $tanggal_awal = date_format_db($this->input->post('start_date'));
        $tanggal_akhir = date_format_db($this->input->post('end_date'));

        if ($this->input->post('list') == 1) {

            $this->db->where("date_stockopname >=", $tanggal_awal);
            $this->db->where("date_stockopname <=", $tanggal_akhir);

            if ($this->input->post('status') != "-") {
                $this->db->where("status ", $this->input->post('status'));
            }

        }
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }

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

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {

        $this->db->from($this->table);
        $tanggal_awal = date_format_db($this->input->post('start_date'));
        $tanggal_akhir = date_format_db($this->input->post('end_date'));

        if ($this->input->post('list') == 1) {

            $this->db->where("date_stockopname >=", $tanggal_awal);
            $this->db->where("date_stockopname <=", $tanggal_akhir);

            if ($this->input->post('status') != "-") {
                $this->db->where("status ", $this->input->post('status'));
            }

        }
        return $this->db->count_all_results();
    }

    public function _item_list($stockopname_id)
    {
        $query = $this->db->query("
                SELECT i.name, ti.stock_physic, ti.stock_system, ti.differential, ti.info,  i.inc_id, ti.inc_id as id_detail
                FROM stockopname_detail ti
                JOIN items i ON ti.items_id = i.inc_id
                WHERE ti.stockopname_id = $stockopname_id
            ");

        return $query->result_array();
    }

    public function _item_list_category($stockopname_id, $category_id)
    {
        $query = $this->db->query("
        SELECT i.name,
        ti.stock_physic,
        ti.stock_system,
        ti.differential,
        ti.info,
        i.inc_id,
        ti.inc_id as id_detail,
        i.category_id,
        i.price
        FROM stockopname_detail ti
        LEFT JOIN items i ON ti.items_id = i.inc_id
        LEFT JOIN category c ON i.category_id = c.id
        WHERE ti.stockopname_id = $stockopname_id AND i.category_id = $category_id
        GROUP BY ti.inc_id
            ");

        return $query->result_array();
    }

}

/* End of file Selling_Model.php */
