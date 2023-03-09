<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        time_cek();
        cache_login();
        is_logged_in();
        $this->load->model('sistem_model');
        $this->load->model('category_model');
        $this->load->model('selling_model');
        $this->id_user = $this->session->userdata('id_user');
    }

    public function index()
    {
        $data['category'] = $this->sistem_model->_get('category');
        $data['penjualan_per_category'] = $this->category_model->penjualan_per_category();

        $data['top_selling'] = $this->selling_model->top_selling();
        $data['penjualan_hari_ini'] = $this->selling_model->penjualan_hari_ini();
        $data['penjualan_bulan_ini'] = $this->selling_model->penjualan_bulan_ini();
        $data['page'] = 'Dashboard';
        $data['active'] = 'dashboard';
        $this->load->view('dashboard', $data);
    }
}
    
    /* End of file Dashboard.php */
