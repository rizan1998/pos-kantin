<?php
	/**
	* 
	*/
	class Logout extends CI_Controller
	{
		
		function __construct()
		{
			parent::__construct();
			time_cek();
			$this->load->model('sistem_model');
		}
	
		public function index(){
			$log['id_user']=$this->session->userdata('id_user');
			$log['type_log']="LOGOUT";
			$log['datetime']=date('Y-m-d H:i:s');
			$log['ipaddress']=get_client_ip();

			$this->sistem_model->_input('log_user', $log);
				
			$this->session->sess_destroy();
		 	redirect('login');			
		}
	}
?>