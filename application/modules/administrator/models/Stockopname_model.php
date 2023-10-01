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
        SELECT i.name, ti.stock_physic, ti.stock_system, ti.differential, ti.info, i.inc_id, ti.inc_id AS id_detail, isell.price_sell
        FROM stockopname_detail ti
        LEFT JOIN items i ON ti.items_id = i.inc_id 
        LEFT JOIN items_sell isell ON ti.items_sell_id = isell.inc_id
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
        isell.price_sell as price,
        u.name as unit_name,
        SUM(sd.qty) as total_penjualan,
        sd.qty,
        isell.inc_id as items_sell_id,
        tid.price as harga_beli

        FROM stockopname_detail ti
        LEFT JOIN items i ON ti.items_id = i.inc_id
        LEFT JOIN category c ON i.category_id = c.id
        LEFT JOIN items_sell isell ON ti.items_sell_id = isell.inc_id
        LEFT JOIN unit u ON isell.unit_id = u.inc_id
        LEFT JOIN selling_detail sd ON isell.inc_id = sd.item_sell_id
        LEFT JOIN transaction_in_detail tid ON ti.items_sell_id = tid.items_sell_id
        WHERE ti.stockopname_id = $stockopname_id AND i.category_id = $category_id
        GROUP BY ti.inc_id
            ");

        return $query->result_array();
    }

    public function get_selling_total($item_sell_id, $tgl_so)
    {
        $tanggal = $tgl_so;
        $timestamp = strtotime($tanggal);

        $bulanHasil = date("m", $timestamp);
        $tahunHasil = date("Y", $timestamp);

        $this->db->from('selling_detail sd');
        $this->db->select("SUM(sd.qty) as total_penjualan");
        $this->db->where("sd.item_sell_id", $item_sell_id);
        $this->db->where('YEAR(sd.datetime)', $tahunHasil);
        $this->db->where('MONTH(sd.datetime)', $bulanHasil);
        $this->db->group_by('sd.items_id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_transaction_in_total($item_sell_id, $tgl_so)
    {
        $tanggal = $tgl_so;
        $timestamp = strtotime($tanggal);

        $bulanHasil = date("m", $timestamp);
        $tahunHasil = date("Y", $timestamp);

        $this->db->from('transaction_in_detail ts');
        $this->db->join("transaction_in ti", "ts.trans_in = ti.inc_id", "left");
        $this->db->select("SUM(ts.qty) as total_barang_masuk");
        $this->db->where("ts.items_sell_id", $item_sell_id);
        $this->db->where('YEAR(ti.date_in)', $tahunHasil);
        $this->db->where('MONTH(ti.date_in)', $bulanHasil);
        $this->db->group_by('ts.items_sell_id');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function items_sell($item_id)
    {
        $this->db->select('ts.inc_id as id_item_sell, ts.price_sell as harga, i.name, i.inc_id as id_item');
        $this->db->from('items_sell ts');
        $this->db->join('items i', 'ts.item_id = i.inc_id', 'left');
        $this->db->where('i.inc_id', $item_id);
        $this->db->group_by('ts.inc_id');
        $query = $this->db->get();
        return $query->result_array();
    }
}

/* End of file Selling_Model.php */
