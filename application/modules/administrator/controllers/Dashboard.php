<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Dashboard extends CI_Controller {

        public function __construct() {
			parent::__construct();
			time_cek();	
			cache_login();
			is_logged_in();
			$this->load->model('sistem_model');
			$this->id_user=$this->session->userdata('id_user');
			
		}
        public function index()
        {

            // echo "Dashboard";
            // exit;
            $data['page'] = 'Dashboard';
            $data['active'] = 'dashboard';
            $this->load->view('dashboard', $data);
            
        }
        
    
    }
    
    /* End of file Dashboard.php */
    
?>