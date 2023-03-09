<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Selling_Model extends CI_Model
{

    public $table = 'selling s';
    public $column_order = array('nota', 'date_nota', 'status');
    public $column_search = array('nota', 'date_nota', 'status');
    public $order = array('date_nota' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function list_items($nota)
    {
        $query = $this->db->query("
                SELECT ds.inc_id,  ds.selling_id, ds.items_id, s.nota, s.date_nota, i.name, ds.price, sum(ds.qty) qty, SUM(ds.discount) discount
                FROM selling_detail ds
                LEFT JOIN selling s ON s.inc_id = ds.selling_id
                LEFT JOIN items i ON ds.items_id = i.inc_id
                WHERE nota = '$nota' AND s.status != 4
                GROUP BY i.name
            ");

        return $query->result_array();
    }

    private function _get_datatables_query()
    {
        $this->db->from($this->table);

        $tanggal_awal = date_format_db($this->input->post('start_date'));
        $tanggal_akhir = date_format_db($this->input->post('end_date'));

        if ($this->input->post('list') == 1) {

            $this->db->where("date_nota >=", $tanggal_awal);
            $this->db->where("date_nota <=", $tanggal_akhir);

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
        return $this->db->count_all_results();
    }

    public function _report_list($start_date, $end_date, $status)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->join('selling_detail sd', 's.inc_id = sd.selling_id', 'left');
        $this->db->where('status', $status);
        $this->db->where("date_nota >=", $start_date);
        $this->db->where("date_nota <=", $end_date);

        $query = $this->db->get();
        return $query->result_array();
    }

    // public function _report_list_category($start_date, $end_date, $status, $category_id){
    //   $start_date_new = $start_date. " 00:00:00";
    //   $end_date_new = $end_date. " 00:00:00";

    //     // $this->db->select("i.name as nama, sd.datetime as tanggal, sd.qty as jumlah ");
    //     $this->db->select('*, 
    //     i.name as nama, 
    //     sd.qty as terjual, 
    //     sd.datetime as tanggal, 
    //     i.stock as stock_awal,
    //     i.inc_id as id_stok_awal, 
    //     tid.qty as stock_masuk, 
    //     sd.price as harga_jual, 
    //     ti.created as tanggal_barang_masuk, 
    //     ti.created');
    //     $this->db->from('selling_detail sd');
    //     $this->db->join('selling s', 'sd.selling_id = s.inc_id', 'left');
    //     $this->db->join('items i', 'sd.items_id = i.inc_id', 'left');
    //     $this->db->join('category c', 'i.category_id = c.inc_id', 'left');
    //     $this->db->join('transaction_in_detail tid', 'sd.items_id = tid.items_id', 'left');
    //     $this->db->join('transaction_in ti', 'tid.trans_in = ti.inc_id','right');
    //     $this->db->where('sd.datetime >=', $start_date_new);
    //     $this->db->where('sd.datetime <=', $end_date_new);
    //     $this->db->where('s.status', $status);
    //     $this->db->where('i.category_id', $category_id);

    //     $this->db->group_by('i.inc_id');
    //     $query = $this->db->get();
    //     return $query->result_array();
    // }

    public function _report_list_penjualan_category($dateSeller, $category_id)
    {
        $dateSellerStart = $dateSeller . " 00:00:00";
        $dateSellerEnd = $dateSeller . " 23:59:00";

        // var_dump($dateSellerStart, $dateSellerEnd, $category_id);
        // die;

        $this->db->select('
          i.inc_id,
          i.name,
          i.stock,
          sd.price as harga_jual,
          (SUM(sd.qty)) as jumlah_penjualan');
        $this->db->from('items i');
        $this->db->join('selling_detail sd', 'i.inc_id = sd.items_id', 'left');
        $this->db->join('selling s', 'sd.items_id = s.inc_id', 'left');
        $this->db->where('sd.datetime >=', $dateSellerStart);
        $this->db->where('sd.datetime <=', $dateSellerEnd);
        $this->db->where('i.category_id', $category_id);
        $this->db->group_by('i.inc_id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function _report_list_barang_masuk_category($date_seller, $category_id, $inc_id)
    {
        $startDateSeller = $date_seller . " 00:00:00";
        $endDateSeller = $date_seller . " 23:59:00";

        $this->db->select('
        i.inc_id, 
        i.name, 
        ti.date_in, sum(tid.qty) as jumlah_barang_masuk
        ');
        $this->db->from('items i');
        $this->db->join('transaction_in_detail tid', 'i.inc_id = tid.items_id', 'left');
        $this->db->join('transaction_in ti', 'tid.trans_in = ti.inc_id', 'left');
        $this->db->where('ti.date_in >=', $startDateSeller);
        $this->db->where('ti.date_in <=', $endDateSeller);
        // $this->db->where('i.category_id', $category_id);
        $this->db->where('i.inc_id', $inc_id);
        $this->db->group_by('i.inc_id');


        $query = $this->db->get();
        return $query->row_array();
    }

    //   public function _report_list_barang_masuk_category2($start_date, $end_date, $status, $category_id){
    //     $start_date_new = $start_date. " 00:00:00";
    //     $end_date_new = $end_date. " 23:59:00";

    //     $this->db->select('
    //     i.inc_id, 
    //     i.name, 
    //     ti.date_in, sum(tid.qty) as jumlah_barang_masuk
    //     ');
    //     $this->db->from('items i');
    //     $this->db->join('transaction_in_detail tid', 'i.inc_id = tid.items_id', 'left');
    //     $this->db->join('transaction_in ti', 'tid.trans_in = ti.inc_id', 'left');
    //     $this->db->where('ti.date_in >=', $start_date);
    //     $this->db->where('ti.date_in <=', $end_date);
    //     $this->db->where('i.category_id', $category_id);
    //     $this->db->where('ti.status', $status);
    //     $this->db->group_by('i.inc_id');


    //     $query = $this->db->get();
    //     return $query->result_array();
    //   }


    public function sell_detail($id)
    {
        $this->db->select('s.*, tp.name pembayaran');
        $this->db->from($this->table);
        $this->db->join('type_paid tp', 's.type_of_payment = tp.inc_id', 'left');
        $this->db->where('s.id', $id);

        $query = $this->db->get();
        return $query->row_array();
    }

    public function top_selling()
    {
        $firtMonth = date('Y-m-01');
        $lastMonth = date('Y-m-t');

        $this->db->select("it.name, sum(sd.qty) as jumlah_penjualan, sd.price");
        $this->db->from('selling_detail sd');
        $this->db->join('items it', 'sd.items_id = it.inc_id', 'left');
        $this->db->where('sd.datetime >=', $firtMonth);
        $this->db->where('sd.datetime <=', $lastMonth);
        $this->db->group_by('it.inc_id');
        $this->db->order_by("jumlah_penjualan", 'desc');
        $this->db->limit(10);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function penjualan_hari_ini()
    {
        $startTime = date("Y-m-d 00:00:00");
        $endTime = date("Y-m-d 23:00:00");

        $this->db->select("sd.qty, sd.price");
        $this->db->from('selling_detail sd');
        $this->db->where('sd.datetime >=', $startTime);
        $this->db->where('sd.datetime <=', $endTime);
        $this->db->group_by('sd.inc_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function penjualan_bulan_ini()
    {
        $firtMonth = date('Y-m-01');
        $lastMonth = date('Y-m-t');

        $this->db->select("sd.qty, sd.price");
        $this->db->from('selling_detail sd');
        $this->db->where('sd.datetime >=', $firtMonth);
        $this->db->where('sd.datetime <=', $lastMonth);
        $this->db->group_by('sd.inc_id');
        $query = $this->db->get();
        return $query->result_array();
    }
}

/* End of file Selling_Model.php */
