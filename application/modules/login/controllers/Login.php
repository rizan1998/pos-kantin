<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

		public function __construct() {
			parent::__construct();	
			time_cek();
			$this->load->model('sistem_model');
		}
		
		public function index(){
			$data['title'] = ':: POS :: LOGIN';
			$this->load->view('login_tpl', $data);
		}

	 	public function validate()
	 	{
	 		$username=$this->input->post('username');
	 		$password=md5($this->input->post('password'));

	 		$query=$this->sistem_model->_validate($username, $password);
			// echo json_encode($query); die;
	 		if($query){
	 				$log=array();
				 	$log['id_user']=$query['id'];
				 	$log['type_log']="LOGIN";
				 	$log['datetime']=date('Y-m-d H:i:s');
				 	$log['ipaddress']=get_client_ip();
				 	

				 	$this->sistem_model->_input('log_user', $log);

					$data = array();
					$data['id_user'] = $query['id'];
					// $data['name'] = $query['name'];
					$data['is_logged_in']=true;
					$data['username'] = $query['username'];
					$data['fullname'] = $query['fullname'];
					$data['roles'] = $query['roles'];
					$data['profile'] = 1;
					$this->session->set_userdata($data);
				 	
					
		 			redirect('dashboard/');	
	 		}else{
	 			$this->session->set_flashdata('msg','Invalid Email and Password');
	 			redirect('login');
	 		}
	 	}	
	 	
}

?>