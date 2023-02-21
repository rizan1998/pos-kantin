<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Tags extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		time_cek();
		cache_login();
		is_logged_in();
		$this->load->model('sistem_model');
		$this->load->model('tags_model');
		$this->id_user = $this->session->userdata('id_user');
		$this->page = 'category-info';
	}


	public function index()
	{
		$data['page'] = $this->page;
		$data['title'] = ucwords('tag berita');
		$this->load->view('configs/tags_view', $data);
	}

	public function getData($id = "")
	{
		$data = $this->sistem_model->_get_where_id('tags', array('id' => $id));
		echo json_encode($data);
	}

	public function create_process()
	{
		$type = $this->input->post('type');
		$create['name'] = strtolower($this->input->post('name'));
		$create['created'] = date('Y-m-d H:i:s');
		if ($type == 'input') {
			$create['id'] = hash_id(date('Ymd'));
			$this->sistem_model->_input('tags', $create);
		} else {
			$id = $this->input->post('id');
			$this->sistem_model->_update('tags', $create, array('id' => $id));
		}

		$data['info'] = 'yes';
		echo json_encode($data);
	}

	public function ajx_get_data()
	{
		$list = $this->tags_model->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $field) {
			$id = $field->id;
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $field->name;
			$row[] = "<a href='javascript:void(0)' class='edit' idatr='$id' title='edit halaman'><span class='step size-18'><i class='fa fa-edit'></i></span></a> &nbsp;<a class='red deleted' href='javascript:void(0)' idatr='$id' title='Hapus Tindakan'><span class='step size-18'><i class='fa fa-times'></i></span></a>";

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->tags_model->count_all(),
			"recordsFiltered" => $this->tags_model->count_filtered(),
			"data" => $data,
		);

		echo json_encode($output);
	}

	public function delete_process($id = "")
	{
		$this->sistem_model->_delete('tags', array('id' => $id));

		$data['info'] = 'yes';
		echo json_encode($data);
	}
}
    
    /* End of file Profile.php */
