<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Selling extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        time_cek();
        cache_login();
        is_logged_in();
        $this->load->model('sistem_model', 'Sistem');
        $this->load->model('stock_model');
        $this->load->model('selling_model');

        $this->id_user = $this->session->userdata('id_user');
        $this->active = 'selling';
        $this->temporary = 1;
        $this->save = 2;
        $this->selesai = 3;
        $this->delete = 4;
        $this->debt = 5;
    }

    public function index()
    {
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Penjualan');
        $data['active'] = $this->active;
        $data['category'] = $this->Sistem->_get_wheres('category', array('ket !=' => 'DELETE'));
        $data['code'] = $this->Sistem->_automatic_nota(date('Ymd') . "-KK-", "selling");
        $this->load->view('selling/selling_view', $data, false);
    }

    public function get_product()
    {
        $search = $this->input->get('search');
        $items = $this->stock_model->_get_product_selling($search);
        foreach ($items as $i) {
            $data[] = array('id' => $i['item_id'], 'text' => $i['name'] . ' ' . $i['price_sell'], 'price_sell' => $i['price_sell']);
        }

        echo json_encode($data);
    }

    public function items_category($categoryId = "")
    {
        $cekCategory = $this->Sistem->_get_where_id('category', array('id' => $categoryId));
        if (count($cekCategory) == 0) {
            redirect('error');
        }

        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Penjualan');
        $data['active'] = $this->active;
        $data['category'] = $this->Sistem->_get('category');
        $data['def_category'] = $cekCategory['inc_id'];
        $data['items'] = $this->stock_model->_get_product_category($cekCategory['inc_id']);
        $this->load->view('selling/selling_view', $data, false);
    }

    public function detail_items_selling($nota = "")
    {
        $ceksNota = $this->Sistem->_get_where_id('selling', array('nota' => $nota));

        if (count($ceksNota) == 0) {
            $sell['id'] = hash_id($nota);
            $sell['nota'] = $nota;
            $sell['date_nota'] = date('Y-m-d');
            $sell['status'] = $this->temporary; //1. temporary
            $sell['id_user'] = $this->id_user;

            $this->Sistem->_input('selling', $sell);
            $lastid = $this->db->insert_id();
        } else {
            $lastid = $ceksNota['inc_id'];
        }

        $item_id = $this->input->post('items');
        $qty = $this->input->post('qty');
        $item_selling_id = $this->input->post('item_selling_id');

        $detailSell['selling_id'] = $lastid;
        $detailSell['items_id'] = $item_id;
        $detailSell['price'] = $this->input->post('price');
        $detailSell['discount'] = $this->input->post('discount');
        $detailSell['qty'] = $qty;
        $detailSell['datetime'] = date('Y-m-d H:i:s');
        $detailSell['id_user'] = $this->id_user;

        // $this->Sistem->_input('selling_detail', $detailSell);

        // pengurangan stok di item sell
        $ceksItemsSelling = $this->Sistem->_get_where_id('items_sell', array('inc_id' => $item_selling_id));

        if ($ceksItemsSelling['stock_item_sell'] > $qty) {
            $resultStock = $ceksItemsSelling['stock_item_sell'] - $this->input->post('qty');
            $items['stock_item_sell'] = $resultStock;
            $this->Sistem->_update('items_sell', $items, array('inc_id' => $item_selling_id));
        }

        // pengurangan stok
        $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $item_id));

        // proses pengurangan
        if ($ceksItems['stock'] > $qty) {
            $resultStock = $ceksItems['stock'] - $this->input->post('qty');
            $items['stock'] = $resultStock;
            $this->Sistem->_update('items', $items, array('inc_id' => $item_id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function list_item_selling($nota = "")
    {
        $data['list'] = $this->selling_model->list_items($nota);
        $data['type_paid'] = $this->Sistem->_get_wheres('type_paid', array('ket !=' => 'DELETE'));
        $this->load->view('selling/selling_items_list', $data, false);
    }

    public function update_selling_items($nota)
    {
        $ceksNota = $this->Sistem->_get_where_id('selling', array('nota' => $nota));

        $sell['status'] = $this->selesai; //3. Lunas
        $sell['id_user'] = $this->id_user;
        $sell['type_of_payment'] = $this->input->post('jenis_pembayaran');
        $sell['total_pembayaran'] = $this->input->post('total_pembayaran');
        $sell['total_discount'] = $this->input->post('discount');
        $sell['total_bayar'] = $this->input->post('bayar');
        $sell['created'] = date('Y-m-d H:i:s');
        $sell['updated'] = date('Y-m-d H:i:s');
        $sell['ket'] = 'UPDATE';

        $this->Sistem->_update('selling', $sell, array('nota' => $nota));

        $data['info'] = 'yes';
        $data['id'] = $ceksNota['id'];
        echo json_encode($data);
    }

    public function print_bill($id)
    {

        $data['selling'] = $this->Sistem->_get_where_id('selling', array('id' => $id));
        $data['list'] = $this->selling_model->list_items($data['selling']['nota']);

        $data['user'] = $this->Sistem->_get_where_id('users', array('id' => $this->id_user));
        $this->load->view('selling/print_bill', $data);
    }

    public function selling_view()
    {
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = ucwords('Penjualan');
        $data['categoryes'] = $this->Sistem->_get('category');
        $data['active'] = 'selling-list';
        $this->load->view('selling/selling_list_view', $data, false);
    }

    public function ajx_data_selling()
    {
        $list = $this->selling_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            if ($field->status == 1 or $field->status == 2) {
                $status = '<span class="badge badge-warning">Belum Selesai</span>';
                $link = '<button type="button" class="btn btn-sm btn-warning" idatr="' . $id . '" id="trans"><i class="fa fa-sign-out"></i></button>';
                $link .= '&nbsp; <button type="button" class="btn btn-sm btn-danger" idatr="' . $id . '" id="delete"><i class="fa fa-close"></i></button>';
            } elseif ($field->status == 3) {
                $status = '<span class="badge badge-success">Selesai</span>';
                $link = '<button type="button" class="btn btn-sm btn-default" idatr="' . $id . '" id="details"><i class="fa fa-eye"></i></button>';
            } elseif ($field->status == 5) {
                $status = '<span class="badge badge-danger">Utang</span>';
                $link = '<button type="button" class="btn btn-sm btn-secondary" idatr="' . $id . '" id="debt"><i class="fa fa-money"></i></button>';
            } else {
                $status = '<span class="badge badge-danger">Cancel</span>';
                $link = "";
            }

            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->nota;
            $row[] = $field->date_nota;
            $row[] = curr_format($field->total_pembayaran);
            $row[] = $status;
            $row[] = $link;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->selling_model->count_all(),
            "recordsFiltered" => $this->selling_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function saving_selling_items($nota)
    {
        $ceksNota = $this->Sistem->_get_where_id('selling', array('nota' => $nota));

        $sell['status'] = $this->save; //2. Simpan
        $sell['id_user'] = $this->id_user;
        $sell['total_pembayaran'] = $this->input->post('total_pembayaran');
        $sell['created'] = date('Y-m-d H:i:s');
        $sell['ket'] = 'INPUT';

        $this->Sistem->_update('selling', $sell, array('nota' => $nota));

        $data['info'] = 'yes';
        $data['id'] = $ceksNota['id'];
        echo json_encode($data);
    }

    public function detail_selling($id)
    {
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Data Penjualan');
        $data['active'] = 'selling-list';
        $data['selling'] = $this->selling_model->sell_detail($id);
        $data['list'] = $this->selling_model->list_items($data['selling']['nota']);

        $data['user'] = $this->Sistem->_get_where_id('users', array('id' => $this->id_user));
        $this->load->view('selling/selling_detail_view', $data);
    }

    public function selling_edit($id)
    {
        $data['page'] = strtoupper('Transaksi');
        $data['subpage'] = strtoupper('Penjualan');
        $data['active'] = $this->active;
        $data['category'] = $this->Sistem->_get_wheres('category', array('ket !=' => 'DELETE'));
        $data['selling'] = $this->Sistem->_get_where_id('selling', array('id' => $id));
        $this->load->view('selling/selling_edit_view', $data, false);
    }

    public function cancel_selling($id)
    {
        $ceksNota = $this->Sistem->_get_where_id('selling', array('id' => $id));
        if (count($ceksNota) == 0) {
            redirect('error');
        }

        $sell['status'] = $this->delete; //3. Hapus
        $sell['id_user'] = $this->id_user;
        $sell['updated'] = date('Y-m-d H:i:s');
        $sell['ket'] = 'DELETE';

        $this->Sistem->_update('selling', $sell, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function delete_item_list($selling_id, $items_id)
    {
        $cekSellingItem = $this->Sistem->_get_wheres('selling_detail', array('selling_id' => $selling_id, 'items_id' => $items_id));

        $qty = 0;
        foreach ($cekSellingItem as $sel) {
            $qty += $sel['qty'];
        }

        // proses pengurangan
        $ceksItems = $this->Sistem->_get_where_id('items', array('inc_id' => $items_id));
        // echo json_encode($qty);
        // echo json_encode($ceksItems['stock']);
        // die;

        $resultStock = $ceksItems['stock'] + $qty;
        $items['stock'] = $resultStock;
        $this->Sistem->_update('items', $items, array('inc_id' => $items_id));

        $this->Sistem->_delete('selling_detail', array('selling_id' => $selling_id, 'items_id' => $items_id));

        redirect('/selling');
    }

    public function print_selling($start_date, $end_date, $status, $id = "")
    {
        $data['title'] = 'data penjualan';
        $tanggal_awal = date_format_db(str_replace('-', '/', $start_date));
        $tanggal_akhir = date_format_db(str_replace('-', '/', $end_date));

        $data['start_date'] = date_format_display_print($tanggal_awal);
        $data['end_date'] = date_format_display_print($tanggal_akhir);
        $data['title'] = 'DATA PENJUALAN';
        $data['status'] = $status;


        $data['list'] = $this->selling_model->_report_list($tanggal_awal, $tanggal_akhir, $status);
        $this->load->view('selling/selling_report', $data);
    }

    public function print_item_percategory($date_seller, $category_id)
    {
        $data['date_seller'] = date_format_display_print($date_seller);
        $data['title'] = 'DATA PENJUALAN';

        $dateSeller = date_format_db(str_replace('-', '/', $date_seller));
        $condition = array("id" => $category_id);
        $category = $this->Sistem->_get_where_id('category', $condition);
        $category_inc_id = $category['inc_id'];

        $data['list_penjualan'] = $this->selling_model->_report_list_penjualan_category($dateSeller, $category_inc_id);

        $arrayFinal = array();
        foreach ($data['list_penjualan'] as $lp) {
            $barangMasuk = $this->selling_model->_report_list_barang_masuk_category($dateSeller, $category_inc_id, $lp['inc_id']);

            array_push(
                $arrayFinal,
                array(
                    'name' => $lp['name'],
                    'stock' => $lp['stock'],
                    'harga_jual' => $lp['harga_jual'],
                    'jumlah_penjualan' => $lp['jumlah_penjualan'],
                    'jumlah_barang_masuk' => $barangMasuk['jumlah_barang_masuk']
                )
            );

            // $arrayFinal[] = [
            //     'name' => $lp['name'],
            //     'stock' => $lp['stock'],
            //     'harga_jual' => $lp['harga_jual'],
            //     'jumlah_penjualan' => $lp['jumlah_penjualan'],
            //     'jumlah_barang_masuk' => $barangMasuk['jumlah_barang_masuk']
            // ];
        }

        $data['list_laporan'] = $arrayFinal;

        $this->load->view('selling/selling_category_report', $data);
    }
}
