<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class User extends CI_Controller {

        public function __construct() {
			parent::__construct();
			time_cek();	
			cache_login();
			is_logged_in();
			$this->load->model('sistem_model');
			$this->load->model('users_model');
			$this->id_user=$this->session->userdata('id_user');
			$this->active = 'master';
		}

		
        public function index()
        {
            $data['page'] = strtoupper('Master Data');
			$data['subpage'] = strtoupper('Pengguna');
			$data['active'] = $this->active;
			$data['subactive'] = 'user';
            $this->load->view('master/user_view', $data);            
        }

        public function get_where_user($id="")
        {
			$data= $this->sistem_model->_get_where_id('users', array('md5(id)'=>$id));
            echo json_encode($data);
        }
    
        public function create_process() {
			$type = $this->input->post('type');
			$create['roles'] = $this->input->post('roles');
			$create['status'] = 1;
			$create['fullname'] = $this->input->post('fullname');
			$create['username'] = $this->input->post('username');
			$create['password'] = md5($this->input->post('password'));
			if($type == 'input'){
				$create['created'] = date('Y-m-d H:i:s');
				$this->sistem_model->_input('users', $create);
			}else{
				$id = $this->input->post('id');
				$create['created'] = date('Y-m-d H:i:s');
				$this->sistem_model->_update('users', $create, array('id'=>$id));
			}
			
            $data['info'] = 'yes';
            echo json_encode($data);
            
        }

        public function ajx_get_data(){
	        $list = $this->users_model->get_datatables();
	        $data = array();
	        $no = $_POST['start'];

	        foreach ($list as $field) {
                $id = md5($field->id);
	            $no++;
	            $row = array();
				$field->status == 1 ? $status = '<span class="badge badge-success">AKTIF</span>' : $status='<span class="badge badge-danger">TIDAK AKTIF</span>';

				
				if($field->roles == 1) $roles = '<span class="badge badge-success">SUPERADMIN</span>'; elseif($field->roles == 2) $roles='<span class="badge badge-danger">ADMIN</span>'; else $roles = '<span class="badge badge-success">KARYAWAN</span>';
	            
				$row[] = $no;
	            $row[] = $field->fullname;
	            $row[] = $roles;
	            $row[] = $status;
	 			$row[] = '<div class="btn-group btn-group-sm" role="group">
				 <button type="button" class="btn btn-secondary" idatr="'.$id.'" id="edit"><i class="fa fa-edit"></i></button>
				 <button type="button" class="btn btn-danger"  idatr="'.$id.'" id="delete"><i class="fa fa-trash"></i></button>
				 </div>';

	            $data[] = $row;
	        }
	 
	        $output = array(
	            "draw" => $_POST['draw'],
	            "recordsTotal" => $this->users_model->count_all(),
	            "recordsFiltered" => $this->users_model->count_filtered(),
	            "data" => $data,
	        );

	        echo json_encode($output);			
		}

		public function delete_process($id = "") {
			$create['deleted'] = date('Y-m-d H:i:s');
			$this->sistem_model->_update('users', $create, array('md5(id)'=>$id));

            $data['info'] = 'yes';
            echo json_encode($data);
		}
    }
    
    /* End of file Profile.php */
    