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

    public function stockopname_add_item($id)
    {
        $ceks = $this->Sistem->_get_where_id('stockopname', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $items_id = $this->input->post('item_id');
        $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $items_id));
        $stockSystem = $ceksItems['stock'];

        $inp['items_id'] = $items_id;
        $inp['stockopname_id'] = $ceks['inc_id'];
        $inp['stock_physic'] = $this->input->post('stock_physic');

        $inp['stock_system'] = $stockSystem;
        $inp['differential'] = $this->input->post('stock_physic') - $stockSystem;
        $inp['info'] = $this->input->post('info');
        $this->Sistem->_input('stockopname_detail', $inp);

        // proses pengurangan

        $resultStock = $this->input->post('stock_physic');
        $items['stock'] = $resultStock;
        $this->Sistem->_update('items', $items, array('inc_id' => $this->input->post('item_id')));

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
        $data['items'] = $this->stockopname_model->_item_list_category($id, $category_id);
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
