<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transaction extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        time_cek();
        cache_login();
        is_logged_in();
        $this->load->model('sistem_model', 'Sistem');
        $this->load->model('stock_model');
        $this->load->model('transaction_in_model');

        $this->id_user = $this->session->userdata('id_user');
        $this->active = 'transaction';
        $this->progress = 1;
        $this->finish = 2;
        $this->cancel = 3;
    }

    public function index()
    {
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Barang Masuk');
        $data['active'] = $this->active;

        $data['code'] = $this->Sistem->_automatic_code_trans(date('Ymd') . "INB", "transaction_in", "code_in");
        $this->load->view('transaction/transaction_in_view', $data, FALSE);
    }

    public function ajx_data_transaction_in()
    {
        $list = $this->transaction_in_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            if ($field->status == 1) {
                $status =  '<span class="badge badge-warning">Not Finish</span>';
                $link = '<button type="button" class="btn btn-sm btn-warning" idatr="' . $id . '" id="trans"><i class="fa fa-sign-out"></i></button>';
                $link .= '&nbsp; <button type="button" class="btn btn-sm btn-danger" idatr="' . $id . '" id="delete"><i class="fa fa-close"></i></button>';
            } elseif ($field->status == 2) {
                $status =  '<span class="badge badge-success">Finish</span>';
                $link = '<button type="button" class="btn btn-sm btn-default" idatr="' . $id . '" id="details"><i class="fa fa-eye"></i></button>';
            } else {
                $status =  '<span class="badge badge-danger">Cancel</span>';
                $link = "";
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->code_in;
            $row[] = $field->date_in;
            $row[] = $status;
            $row[] = $link;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->transaction_in_model->count_all(),
            "recordsFiltered" => $this->transaction_in_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function transaction_in_add()
    {
        $code = $this->input->post('code');

        $ceksCode = $this->Sistem->_get_where_id('transaction_in', array('code_in' => $code));
        if (count($ceksCode) > 0) {
            redirect('error');
        }

        $date = date_format_db($this->input->post('date'));
        $inp['id'] = hash_id($code . $date);
        $inp['code_in'] = $code;
        $inp['date_in'] = $date;
        $inp['status'] = $this->progress;
        $inp['id_user'] = $this->id_user;
        $inp['ket'] = 'INPUT';
        $inp['created'] = date('Y-m-d H:i:s');

        $this->Sistem->_input('transaction_in', $inp);

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function transaction_in_form($id = "")
    {
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Barang Masuk');
        $data['active'] = $this->active;
        $data['transaction'] = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));
        $this->load->view('transaction/transaction_in_form', $data, FALSE);
    }

    public function transaction_in_add_item($id)
    {
        $ceks = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $inp['items_id'] = $this->input->post('item_id');
        $inp['trans_in'] = $ceks['inc_id'];
        $inp['qty'] = $this->input->post('qty');
        $inp['price'] = $this->input->post('price');
        $inp['discount'] = $this->input->post('discount');
        $this->Sistem->_input('transaction_in_detail', $inp);

        $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $this->input->post('item_id')));

        // proses pengurangan

        $resultStock = $ceksItems['stock'] + $this->input->post('qty');
        $items['stock'] = $resultStock;
        $this->Sistem->_update('items', $items, array('inc_id' => $this->input->post('item_id')));



        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function detail_in_item($id)
    {
        $ceks = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $data['item'] = $this->transaction_in_model->_item_list($ceks['inc_id']);

        $this->load->view('transaction/in_list_item', $data, FALSE);
    }

    public function update_transaction($id)
    {
        $ceks = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $upd['status'] = $this->finish;
        $upd['id_user'] = $this->id_user;
        $upd['ket'] = 'UPDATE';
        $upd['updated'] = date('Y-m-d H:i:s');

        $this->Sistem->_update('transaction_in', $upd, array('id' => $id));
        $data['info'] = 'yes';

        echo json_encode($data);
    }

    public function transaction_in_details($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Barang Masuk');
        $data['active'] = $this->active;
        $data['transaction'] = $ceks;
        $data['item'] = $this->transaction_in_model->_item_list($ceks['inc_id']);

        $this->load->view('transaction/transaction_in_details', $data, FALSE);
    }

    public function print_barang_masuk($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Barang Masuk');
        $data['active'] = $this->active;
        $data['transaction'] = $ceks;
        $data['item'] = $this->transaction_in_model->_item_list($ceks['inc_id']);

        $this->load->view('transaction/transaction_in_report', $data, FALSE);
    }

    public function cancel_trans_in($id)
    {
        $ceksNota = $this->Sistem->_get_where_id('trans_in', array('id' => $id));
        if (count($ceksNota) == 0) {
            redirect('error');
        }

        $sell['status'] = $this->cancel; //3. Hapus
        $sell['id_user'] = $this->id_user;
        $sell['updated'] = date('Y-m-d H:i:s');
        $sell['ket'] = 'DELETE';

        $this->Sistem->_update('trans_in', $sell, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }
}
    
    /* End of file Transaction.php */
