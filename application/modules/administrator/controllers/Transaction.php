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
        $data['unit']        = $this->Sistem->_get('unit');
        $this->load->view('transaction/transaction_in_form', $data, FALSE);
    }

    public function transaction_in_add_item($id)
    {
        $ceks = $this->Sistem->_get_where_id('transaction_in', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $id_item = '';
        $id_item_sell = '';
        if ($this->input->post('type_item') == 'lama') {
            $id_item_sell = $this->input->post('id_item');
            $item_sell = $this->Sistem->_get_where_id('items_sell', ['inc_id' => $id_item_sell]);
            $id_item_sell = $item_sell['inc_id'];
            $id_item = $item_sell['item_id'];
        } else {
            // create item sell;
            $id_item = $this->input->post('id_item');
            $val['id'] = hash_id($id_item);
            $val['item_id'] = $id_item;
            $val['unit_id'] = $this->input->post('unit_id');
            $val['type_price'] = $this->input->post('type_price');
            $val['price_sell'] = $this->input->post('price');
            $val['discount'] = $this->input->post('discount');
            $val['id_user'] = $this->id_user;
            $val['created'] = date('Y-m-d H:i:s');
            $val['ket'] = 'INPUT';
            $id_item_sell = $this->Sistem->_input_return_id('items_sell', $val);
        }

        // tambah stok ke item sell stok
        $item_sell = $this->Sistem->_get_where_id('items_sell', ['inc_id' => $id_item_sell]);
        $item_sell_stok = $item_sell['stock_item_sell'];
        $new_stock      = $item_sell_stok + $this->input->post('qty');

        $updStck['stock_item_sell'] = $new_stock;
        $updStck['ket'] = 'UPDATE';
        $updStck['updated'] = date("Y-m-d H:i:s");
        $updStck['id_user']         = $this->id_user;
        $this->Sistem->_update('items_sell', $updStck, ['inc_id' => $id_item_sell]);

        $inp['items_id'] = $id_item;
        $inp['items_sell_id'] = $id_item_sell;
        $inp['trans_in'] = $ceks['inc_id'];
        $inp['qty'] = $this->input->post('qty');
        $inp['price'] = $this->input->post('price');
        $inp['discount'] = $this->input->post('discount');
        $this->Sistem->_input('transaction_in_detail', $inp);

        $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $id_item));

        // proses penambahan
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


    public function ajx_data_detail_transaction_in($kodeBarang)
    {
        $list = $this->transaction_in_model->get_datatables_detail_trans($kodeBarang);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $id = $field->inc_id;
            // if ($field->status == 1) {
            //     $status =  '<span class="badge badge-warning">Not Finish</span>';
            //     $link = '<button type="button" class="btn btn-sm btn-warning" idatr="' . $id . '" id="trans"><i class="fa fa-sign-out"></i></button>';
            //     $link .= '&nbsp; <button type="button" class="btn btn-sm btn-danger" idatr="' . $id . '" id="delete"><i class="fa fa-close"></i></button>';
            // } elseif ($field->status == 2) {
            //     $status =  '<span class="badge badge-success">Finish</span>';
            //     $link = '<button type="button" class="btn btn-sm btn-default" idatr="' . $id . '" id="details"><i class="fa fa-eye"></i></button>';
            // } else {
            //     $status =  '<span class="badge badge-danger">Cancel</span>';
            //     $link = "";
            // }

            $link = '<div class="text-center">
                    <button type="button" class="btn btn-sm btn-primary"
                    data-id_detail_trans="' . $id . '" 
                    data-name="' . $field->name . '"
                    data-date="' . date("d-m-Y", strtotime($field->tanggal_masuk)) . '"
                    data-price="' . $field->price . '"
                    id="add_transaction_detail"><i class="fa fa-plus-circle"></i></button>
                    </div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = date('d-m-Y', strtotime($field->tanggal_masuk));
            $row[] = $field->price;
            $row[] = $link;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->transaction_in_model->count_all_detail_trans(),
            "recordsFiltered" => $this->transaction_in_model->count_filtered_detail_trans($kodeBarang),
            "data" => $data,
        );

        echo json_encode($output);
    }
}
    
    /* End of file Transaction.php */
