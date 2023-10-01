<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stock_opname extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        time_cek();
        cache_login();
        is_logged_in();
        $this->load->model('sistem_model', 'Sistem');
        $this->load->model('stock_model');
        $this->load->model('stockopname_model');
        $this->load->model('sistem_model');

        $this->id_user = $this->session->userdata('id_user');
        $this->active = 'stockopname';
        $this->progress = 1;
        $this->finish = 2;
        $this->cancel = 3;
    }

    public function index()
    {
        $data['page'] = strtoupper('Stock Opname');
        $data['subpage'] = strtoupper('SO');
        $data['active'] = $this->active;

        $data['code'] = $this->Sistem->_automatic_code_trans(date('Ymd') . "SOP", "stockopname", "code_in");
        $this->load->view('stockopname/stockopname_view', $data, false);
    }

    public function ajx_data_stockopname()
    {
        $list = $this->stockopname_model->get_datatables();
        $category = $this->sistem_model->_get('category');

        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;

            $categoryOption = '';
            foreach ($category as $ctgry) {
                $categoryOption .= "<option data-stokOpnameId='" . $id . "' value='" . $ctgry['inc_id'] . "' >" . $ctgry["name"] . "</option>";
            }

            if ($field->status == 1) {
                $status = '<span class="badge badge-warning">Belum Selesai</span>';
                $link = '<button type="button" class="btn btn-sm btn-warning" idatr="' . $id . '" id="trans"><i class="fa fa-sign-out"></i></button>';
                $link .= '&nbsp; <button type="button" class="btn btn-sm btn-danger" idatr="' . $id . '" id="delete"><i class="fa fa-close"></i></button>';
            } elseif ($field->status == 2) {
                $status = '<span class="badge badge-success">Selesai</span>';
                $link = '
                <div class="input-group">
                <button type="button" class="btn btn-sm btn-default " idatr="' . $id . '" id="details">
                    <i class="fa fa-eye"></i>
                </button>
                <select class="form-control categorySelect">
                ' . $categoryOption . '
                </select>
                </div>';
            } else {
                $status = '<span class="badge badge-danger">Batal</span>';
                $link = "";
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->code_in;
            $row[] = $field->date_stockopname;
            $row[] = $status;
            $row[] = $link;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stockopname_model->count_all(),
            "recordsFiltered" => $this->stockopname_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function stockopname_add()
    {
        $code = $this->input->post('code');

        $ceksCode = $this->Sistem->_get_where_id('stockopname', array('code_in' => $code));
        if (count($ceksCode) > 0) {
            redirect('error');
        }

        $date = date_format_db($this->input->post('date'));
        $inp['id'] = hash_id($code . $date);
        $inp['code_in'] = $code;
        $inp['date_stockopname'] = $date;
        $inp['status'] = $this->progress;
        $inp['id_user'] = $this->id_user;
        $inp['ket'] = 'INPUT';
        $inp['created'] = date('Y-m-d H:i:s');

        $this->Sistem->_input('stockopname', $inp);

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function stockopname_form($id = "")
    {
        $data['page'] = strtoupper('Stock Opname');
        $data['subpage'] = strtoupper('SO');
        $data['active'] = $this->active;
        $data['stockopname'] = $this->Sistem->_get_where_id('stockopname', array('id' => $id));
        $this->load->view('stockopname/stockopname_form', $data, false);
    }

    public function stockopname_list_item_sell($id_item)
    {
        // $item_sell = $this->Sistem->_get_wheres('items_sell', array('item_id' => $id_item));
        $items_sell = $this->stockopname_model->items_sell($id_item);
        $data['data'] = $items_sell;
        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function stockopname_add_item($id)
    {
        $ceks = $this->Sistem->_get_where_id('stockopname', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        $totalStock = 0;
        foreach ($data as $stock) {
            $totalStock += $stock['stock_physic'];
        }

        foreach ($data as $d) {
            $id_item_sell = $d['id_item_sell'];
            $stock_physic = $d['stock_physic'];
            $info = $d['info'];
            $id_item = $d['id_item'];

            $ceksItemsSell = $this->Sistem->_get_where_id('items_sell', array('inc_id' => $id_item_sell));
            $stockSystem = $ceksItemsSell['stock_item_sell'];

            $itemsSell['stock_item_sell'] = $stock_physic;
            $this->Sistem->_update('items_sell', $itemsSell, array('inc_id' => $id_item_sell));

            $inp['items_id'] = $id_item;
            $inp['stockopname_id'] = $ceks['inc_id'];
            $inp['stock_physic'] = $stock_physic;

            $inp['stock_system'] = $stock_physic;
            $inp['differential'] = $stock_physic - $stockSystem;
            $inp['stock_item_sell'] = $stock_physic;
            $inp['info'] = $info;
            $this->Sistem->_input('stockopname_detail', $inp);
        }


        // stock keseluruhan
        $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $id_item));
        $stockSystem = $ceksItems['stock'];

        // proses pengurangan stock keseluruhan
        $items['stock'] = $totalStock;
        $this->Sistem->_update('items', $items, array('inc_id' => $id_item));



        // $items_id = $this->input->post('item_id');
        // $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $items_id));
        // $stockSystem = $ceksItems['stock'];

        // $inp['items_id'] = $items_id;
        // $inp['stockopname_id'] = $ceks['inc_id'];
        // $inp['stock_physic'] = $this->input->post('stock_physic');

        // $inp['stock_system'] = $stockSystem;
        // $inp['differential'] = $this->input->post('stock_physic') - $stockSystem;
        // $inp['info'] = $this->input->post('info');
        // $this->Sistem->_input('stockopname_detail', $inp);

        // proses pengurangan

        // $resultStock = $this->input->post('stock_physic');
        // $items['stock'] = $resultStock;
        // $this->Sistem->_update('items', $items, array('inc_id' => $this->input->post('item_id')));


        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function detail_item($id)
    {
        $ceks = $this->Sistem->_get_where_id('stockopname', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $data['item'] = $this->stockopname_model->_item_list($ceks['inc_id']);

        $this->load->view('stockopname/in_list_item', $data, false);
    }

    public function update_stockopname($id)
    {
        $ceks = $this->Sistem->_get_where_id('stockopname', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $upd['status'] = $this->finish;
        $upd['id_user'] = $this->id_user;
        $upd['ket'] = 'UPDATE';
        $upd['updated'] = date('Y-m-d H:i:s');

        $this->Sistem->_update('stockopname', $upd, array('id' => $id));
        $data['info'] = 'yes';

        echo json_encode($data);
    }

    public function stockopname_details($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('stockopname', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Barang Masuk');
        $data['active'] = $this->active;
        $data['stockopname'] = $ceks;
        $data['category'] = false;
        $data['item'] = $this->stockopname_model->_item_list($ceks['inc_id']);

        $this->load->view('stockopname/stockopname_details', $data, false);
    }

    public function stockopname_category_detail($id = "", $category_id = "")
    {

        $ceks = $this->Sistem->_get_where_id('stockopname', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Barang Masuk');
        $data['active'] = $this->active;
        $data['stockopname'] = $ceks;
        $data['category'] = true;
        $data['inc_id'] = $ceks['inc_id'];
        $data['category_id'] = $category_id;
        $data['item'] = $this->stockopname_model->_item_list_category($ceks['inc_id'], $category_id);

        $this->load->view('stockopname/stockopname_details', $data, false);
    }

    public function cetak_hasil_so($id = "", $category_id = "")
    {
        $data['title'] = 'DATA STOKOPNAME';
        $ceks = $this->sistem_model->_get_where_id('stockopname', array('inc_id' => $id));
        $data['tgl_so'] = $ceks['date_stockopname'];
        $stockopname_detail = $this->stockopname_model->_item_list_category($id, $category_id);

        $date = $ceks['date_stockopname'];
        $test = $this->stockopname_model->get_selling_total(25, $data['tgl_so']);


        // echo json_encode($data['items']);
        $stock_awal  = 0;
        $data['items'] = [];
        foreach ($stockopname_detail as $stckopname_dtl) {
            // $items = $this->Sistem->_get_where_id('items', ['inc_id' => $stckopname_dtl['items_id']]);
            $total_selling = $this->stockopname_model->get_selling_total($stckopname_dtl['items_sell_id'], $data['tgl_so']);
            $total_transaction_in = $this->stockopname_model->get_transaction_in_total($stckopname_dtl['items_sell_id'], $data['tgl_so']);
            $items_sell = $this->Sistem->_get_where_id('items_sell', ['inc_id' => $stckopname_dtl['items_sell_id']]);

            $total_penjualan = $total_selling['total_penjualan'];
            $total_barang_masuk = $total_transaction_in['total_barang_masuk'];
            $stock_awal = $items_sell['stock_item_sell'] + $total_penjualan - $total_barang_masuk;



            $data['items'][] = [
                'name' => $stckopname_dtl['name'],
                'stock_physic' => $stckopname_dtl['stock_physic'],
                'stock_system' => $stckopname_dtl['stock_system'],
                'differential' => $stckopname_dtl['differential'],
                'info' => $stckopname_dtl['info'],
                'inc_id' => $stckopname_dtl['inc_id'],
                'id_detail' => $stckopname_dtl['id_detail'],
                'category_id' => $stckopname_dtl['category_id'],
                'price' => $stckopname_dtl['price'],
                'unit_name' => $stckopname_dtl['unit_name'],
                'total_penjualan' => $stckopname_dtl['total_penjualan'],
                'qty' => $stckopname_dtl['qty'],
                'harga_beli' => $stckopname_dtl['harga_beli'],
                'items_sell_id' => $stckopname_dtl['items_sell_id'],
                'stock_awal' => $stock_awal,
                'total_barang_masuk' => $total_barang_masuk
            ];


            // echo $total_transaction_in['total_transaction_in'];
            // echo $total_selling['total_penjualan'];
            // echo "<br>";
        }
        // var_dump($data['items']);
        // die;

        return $this->load->view('stockopname/stockopname_report', $data);
    }

    public function cancel_stockopname($id)
    {
        $ceksNota = $this->Sistem->_get_where_id('stockopname', array('id' => $id));
        if (count($ceksNota) == 0) {
            redirect('error');
        }

        $sell['status'] = $this->cancel; //3. Hapus
        $sell['id_user'] = $this->id_user;
        $sell['updated'] = date('Y-m-d H:i:s');
        $sell['ket'] = 'DELETE';

        $this->Sistem->_update('stockopname', $sell, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function get_product()
    {
        $search = $this->input->get('search');
        $items = $this->stock_model->_get_product_selling($search);
        foreach ($items as $i) {
            $data[] = array('id' => $i['item_id'], 'text' => $i['name'] . " (" . $i['code'] . ")", 'unit' => $i['unit_name']);
        }

        echo json_encode($data);
    }
}

/* End of file stockopname.php */
