<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Report extends CI_Controller {
    
        public function __construct() {
            parent::__construct();
            time_cek();	
            cache_login();
            is_logged_in();
            $this->load->model('sistem_model', 'Sistem');
            $this->load->model('category_model');
            $this->load->model('type_model');
            $this->load->model('unit_model');
            $this->load->model('product_model');
            $this->id_user=$this->session->userdata('id_user');
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
    
        public function ajx_data_product(){
            $list = $this->product_model->get_datatables();
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
                $row[] = '<div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-secondary" idatr="'.$id.'" id="edit"><i class="fa fa-edit"></i></button>
                <button type="button" class="btn btn-danger"  idatr="'.$id.'" id="delete"><i class="fa fa-trash"></i></button>
                <button type="button" class="btn btn-default" idatr="'.$id.'" id="detail-price" title="detail harga"><i class="fa fa-sign-out"></i></button>
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
    }
    
    /* End of file Report.php */
    