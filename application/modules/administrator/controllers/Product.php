<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        time_cek();
        cache_login();
        is_logged_in();
        $this->load->model('sistem_model', 'Sistem');
        $this->load->model('category_model');
        $this->load->model('type_model');
        $this->load->model('unit_model');
        $this->load->model('product_model');
        $this->load->model('type_paid_model');
        $this->id_user = $this->session->userdata('id_user');
        $this->active = 'master';
    }

    public function index()
    {
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product';
        $this->load->view('master/product_view', $data, FALSE);
    }

    public function ajx_data_product()
    {
        $list = $this->product_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->code;
            $row[] = $field->name;
            $row[] = $field->category_name;
            $row[] = $field->unit_name;
            $row[] = '<div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-secondary" idatr="' . $id . '" id="edit"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger"  idatr="' . $id . '" id="delete"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-default" idatr="' . $id . '" id="detail-price" title="detail harga"><i class="fa fa-sign-out"></i></button>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_model->count_all(),
            "recordsFiltered" => $this->product_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function add_product()
    {
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product';
        $data['category'] = $this->Sistem->_get_wheres('category', array('ket !=' => 'DELETE'));
        $data['type'] = $this->Sistem->_get_wheres('type', array('ket !=' => 'DELETE'));
        $data['unit'] = $this->Sistem->_get_wheres('unit', array('ket !=' => 'DELETE'));
        $data['code'] = $this->Sistem->_automatic_code("BRG", "items");
        $this->load->view('master/product_form_view', $data, FALSE);
    }


    public function edit_product($idProduct = "")
    {
        $ceksProduct = $this->Sistem->_get_where_id('items', array('id' => $idProduct));
        $data['items'] = $ceksProduct;
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product';
        $data['category'] = $this->Sistem->_get('category');
        $data['type'] = $this->Sistem->_get('type');
        $data['unit'] = $this->Sistem->_get('unit');
        $this->load->view('master/product_form_edit', $data, FALSE);
    }


    public function product_process()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $name = $this->input->post('name');

        $val['code'] = $this->input->post('code');
        $val['barcode'] = $this->input->post('barcode');
        $val['name'] = $name;
        $val['type_id'] = $this->input->post('type_id');
        $val['category_id'] = $this->input->post('category_id');
        $val['unit_id'] = $this->input->post('unit_id');
        $val['min_stok'] = $this->input->post('min_stok');
        $val['price'] = $this->input->post('price');
        $val['ppn'] = $this->input->post('ppn');
        $val['price_ppn'] = $this->input->post('price_ppn');
        $val['promo'] = $this->input->post('promo');
        $val['id'] = hash_id($name);
        $val['id_user'] = $this->id_user;
        if ($type == "input") {
            $val['created'] = date('Y-m-d H:i:s');
            $val['ket'] = 'INPUT';
            $this->Sistem->_input('items', $val);
        } else {
            $val['ket'] = 'UPDATE';
            $val['updated'] = date('Y-m-d H:i:s');
            $this->Sistem->_update('items', $val, array('id' => $id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }




    public function product_delete($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('items', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $val['ket'] = 'DELETE';
        $val['deleted'] = date('Y-m-d H:i:s');
        $this->Sistem->_update('items', $val, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function detail_price_product($idProduct = "")
    {
        $ceksProduct = $this->Sistem->_get_where_id('items', array('id' => $idProduct));

        if (count($ceksProduct) == 0) {
            redirect('notfound');
        }


        $product = $this->product_model->view_product(array('i.id' => $idProduct));
        $data['items'] = $product;
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Harga Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product';
        $data['unit'] = $this->Sistem->_get('unit');
        $data['items_sell'] = $this->product_model->items_sell_get(array('isell.item_id' => $ceksProduct['inc_id']));
        $this->load->view('master/product_detail_price', $data, FALSE);
    }

    public function detail_price_process($idProduct = "")
    {
        $ceksProduct = $this->Sistem->_get_where_id('items', array('id' => $idProduct));

        if (count($ceksProduct) == 0) {
            redirect('notfound');
        }

        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $val['id'] = hash_id($idProduct);
        $val['item_id'] = $ceksProduct['inc_id'];
        $val['unit_id'] = $this->input->post('unit_id');
        $val['type_price'] = $this->input->post('type_price');
        $val['price_sell'] = $this->input->post('price');
        $val['discount'] = $this->input->post('discount');
        $val['id_user'] = $this->id_user;
        // $val['trans_in_detail_id'] = $this->input->post("id_detail_trans");
        if ($type == "input") {
            $val['created'] = date('Y-m-d H:i:s');
            $val['ket'] = 'INPUT';
            $this->Sistem->_input('items_sell', $val);
        } else {
            $val['ket'] = 'UPDATE';
            $val['updated'] = date('Y-m-d H:i:s');
            $this->Sistem->_update('items_sell', $val, array('id' => $id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function product_detail_delete($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('items_sell', array('id' => $id));
        if (count($ceks) == 0) {
            redirect('error');
        }

        $val['ket'] = 'DELETE';
        $val['deleted'] = date('Y-m-d H:i:s');
        $this->Sistem->_update('items_sell', $val, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }



    public function category()
    {
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Kategori Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product_category';
        $this->load->view('master/product_category_view', $data, FALSE);
    }

    public function ajx_data_product_category()
    {
        $list = $this->category_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = '<div class="btn-group btn-group-sm" role="group"">
            <button type="button" class="btn btn-secondary" idatr="' . $id . '" id="edit"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger"  idatr="' . $id . '" id="delete"><i class="fa fa-trash"></i></button>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->category_model->count_all(),
            "recordsFiltered" => $this->category_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }



    public function category_process()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $name = $this->input->post('name');

        $val['name'] = $name;
        $val['id'] = hash_id($name);
        $val['datetime'] = date('Y-m-d H:i:s');
        $val['id_user'] = $this->id_user;
        if ($type == "input") {
            $val['ket'] = 'INPUT';
            $this->Sistem->_input('category', $val);
        } else {
            $val['ket'] = 'UPDATE';
            $this->Sistem->_update('category', $val, array('id' => $id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function get_where_category($id = "")
    {
        $data = $this->Sistem->_get_where_id('category', array('id' => $id));
        echo json_encode($data);
    }

    public function category_delete($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('category', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $val['ket'] = 'DELETE';
        $val['datetime'] = date('Y-m-d H:i:s');
        $this->Sistem->_update('category', $val, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function type()
    {
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Jenis Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product_type';
        $this->load->view('master/product_type_view', $data, FALSE);
    }

    public function ajx_data_product_type()
    {
        $list = $this->type_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = '<div class="btn-group btn-group-sm" role="group"">
            <button type="button" class="btn btn-secondary" idatr="' . $id . '" id="edit"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger"  idatr="' . $id . '" id="delete"><i class="fa fa-trash"></i></button>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->type_model->count_all(),
            "recordsFiltered" => $this->type_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function type_process()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $name = $this->input->post('name');

        $val['name'] = $name;
        $val['id'] = hash_id($name);
        $val['datetime'] = date('Y-m-d H:i:s');
        $val['id_user'] = $this->id_user;
        if ($type == "input") {
            $val['ket'] = 'INPUT';
            $this->Sistem->_input('type', $val);
        } else {
            $val['ket'] = 'UPDATE';
            $this->Sistem->_update('type', $val, array('id' => $id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function get_where_type($id = "")
    {
        $data = $this->Sistem->_get_where_id('type', array('id' => $id));
        echo json_encode($data);
    }

    public function type_delete($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('type', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $val['ket'] = 'DELETE';
        $val['datetime'] = date('Y-m-d H:i:s');
        $this->Sistem->_update('type', $val, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }


    public function unit()
    {
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Satuan Barang');
        $data['active'] = $this->active;
        $data['subactive'] = 'product_unit';
        $this->load->view('master/product_unit_view', $data, FALSE);
    }

    public function ajx_data_product_unit()
    {
        $list = $this->unit_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = '<div class="btn-group btn-group-sm" role="group"">
            <button type="button" class="btn btn-secondary" idatr="' . $id . '" id="edit"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger"  idatr="' . $id . '" id="delete"><i class="fa fa-trash"></i></button>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->unit_model->count_all(),
            "recordsFiltered" => $this->unit_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function unit_process()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $name = $this->input->post('name');

        $val['name'] = $name;
        $val['id'] = hash_id($name);
        $val['datetime'] = date('Y-m-d H:i:s');
        $val['id_user'] = $this->id_user;
        if ($type == "input") {
            $val['ket'] = 'INPUT';
            $this->Sistem->_input('unit', $val);
        } else {
            $val['ket'] = 'UPDATE';
            $this->Sistem->_update('unit', $val, array('id' => $id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function get_where_unit($id = "")
    {
        $data = $this->Sistem->_get_where_id('unit', array('id' => $id));
        echo json_encode($data);
    }

    public function unit_delete($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('unit', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $val['ket'] = 'DELETE';
        $val['datetime'] = date('Y-m-d H:i:s');
        $this->Sistem->_update('unit', $val, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function type_paid()
    {
        $data['page'] = strtoupper('Master Data');
        $data['subpage'] = strtoupper('Jenis Pembayaran');
        $data['active'] = $this->active;
        $data['subactive'] = 'type_paid';
        $this->load->view('master/jenis_pembayaran_view', $data, FALSE);
    }

    public function ajx_data_product_typepaid()
    {
        $list = $this->type_paid_model->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $id = $field->id;
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = '<div class="btn-group btn-group-sm" role="group"">
            <button type="button" class="btn btn-secondary" idatr="' . $id . '" id="edit"><i class="fa fa-edit"></i></button>
            <button type="button" class="btn btn-danger"  idatr="' . $id . '" id="delete"><i class="fa fa-trash"></i></button>
            </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->type_paid_model->count_all(),
            "recordsFiltered" => $this->type_paid_model->count_filtered(),
            "data" => $data,
        );

        echo json_encode($output);
    }

    public function type_paid_process()
    {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $name = $this->input->post('name');

        $val['name'] = $name;
        $val['id'] = hash_id($name);
        $val['datetime'] = date('Y-m-d H:i:s');
        $val['id_user'] = $this->id_user;
        if ($type == "input") {
            $val['ket'] = 'INPUT';
            $this->Sistem->_input('type_paid', $val);
        } else {
            $val['ket'] = 'UPDATE';
            $this->Sistem->_update('type_paid', $val, array('id' => $id));
        }

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function get_where_type_paid($id = "")
    {
        $data = $this->Sistem->_get_where_id('type_paid', array('id' => $id));
        echo json_encode($data);
    }

    public function type_paid_delete($id = "")
    {
        $ceks = $this->Sistem->_get_where_id('type_paid', array('id' => $id));

        if (count($ceks) == 0) {
            redirect('error');
        }

        $val['ket'] = 'DELETE';
        $val['datetime'] = date('Y-m-d H:i:s');
        $this->Sistem->_update('type_paid', $val, array('id' => $id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function ajx_items()
    {
        $list = $this->product_model->get_datatables_items();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $btn = '<div class="text-center"
            ><button class="btn btn-primary btn-sm add_item" 
            data-id_item="' . $field->id . '"
            data-name="' . $field->name . '" ><i class="fa fa-plus-circle" aria-hidden="true"></i></button></div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->code;
            $row[] = $field->name;
            $row[] = $field->category_name;
            $row[] = $field->unit_name;
            $row[] = $btn;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_model->count_all_items(),
            "recordsFiltered" => $this->product_model->count_filtered_items(),
            "data" => $data,
        );

        echo json_encode($output);
    }


    public function ajx_items_sell()
    {
        $list = $this->product_model->get_datatables_items_sell();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $btn = '<div class="text-center"
            ><button class="btn btn-primary btn-sm add_stock" 
            data-id_item="' . $field->id . '" 
            data-price_sell="' . $field->price_sell . '"
            data-type_price="' . $field->type_price . '"
            data-name="' . $field->name . '"
            data-unit_id="' . $field->unit_id . '"
            ><i class="fa fa-plus-circle" aria-hidden="true"></i></button></div>';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->name;
            $row[] = $field->price_sell;
            $row[] = $field->stock_item_sell;
            $row[] = $field->unit_name;
            $row[] = $btn;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->product_model->count_all_items_sell(),
            "recordsFiltered" => $this->product_model->count_filtered_items_sell(),
            "data" => $data,
        );

        echo json_encode($output);
    }
}

/* End of file Product.php */
