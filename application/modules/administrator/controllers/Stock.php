<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {

    public function __construct() {
        parent::__construct();
        time_cek();	
        cache_login();
        is_logged_in();
        $this->load->model('sistem_model', 'Sistem');
        $this->load->model('category_model');
        $this->load->model('type_model');
        $this->load->model('unit_model');
        $this->load->model('stock_model');
        $this->id_user=$this->session->userdata('id_user');
        $this->active = 'stock';
        
    }

    public function index()
    {
        $data['page'] = strtoupper('Stok Barang');
        $data['subpage'] = strtoupper('stok');
        $data['active'] = $this->active;
        $data['subactive'] = 'stok';
        $this->load->view('stock/stock_view', $data, FALSE); 
    }

    public function ajx_data_stock(){
        $list = $this->stock_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $field) {
			$id = $field->id;
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->code ;
			$row[] = $field->name;
			$row[] = $field->category_name;
			$row[] = $field->unit_name;
			$row[] = $field->stock;
            $row[] = '<button type="button" class="btn btn-sm btn-default" idatr="'.$id.'" id="stockies"><i class="fa fa-eye"></i></button>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->stock_model->count_all(),
			"recordsFiltered" => $this->stock_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
    }

    public function get_product($id)
    {
        $data = $this->stock_model->view_product(array('i.id'=>$id));
        echo json_encode($data);
    }

    public function process_update()
    {
        $id = $this->input->post('id');
        $stock = $this->input->post('stock');

        $val['id_user'] = $this->id_user;
        $val['stock'] = $stock;
        $val['ket'] = 'UPDATE';
        $val['updated'] = date('Y-m-d H:i:s');  
        $this->Sistem->_update('items', $val, array('id'=>$id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function product_delete($id="")
    {
        $ceks = $this->Sistem->_get_where_id('items', array('id'=>$id));
        if(count($ceks) == 0) {
            redirect('error');
        }

        $this->Sistem->_delete('items', array('id'=>$id));

        $data['info'] = 'yes';
        echo json_encode($data);
    }

    public function print_stock(){
        $data['list'] = $this->stock_model->stock_list();
        $data['title'] = 'DATA STOK';
            // echo json_encode($data); die;
        $this->load->view('stock/stock_report', $data);
            
    }
}

/* End of file Product.php */
