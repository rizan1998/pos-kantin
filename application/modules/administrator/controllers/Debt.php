<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Debt extends CI_Controller {
        public function __construct() {
            parent::__construct();
            time_cek();	
            cache_login();
            is_logged_in();
            $this->load->model('sistem_model', 'Sistem');
            $this->load->model('stock_model');
            $this->load->model('selling_model');
            $this->load->model('debt_model');
            
            $this->id_user=$this->session->userdata('id_user');
            $this->active = 'debt';
            $this->notpaid = 1;
            $this->paid = 2;
        }
    
        public function index()
        {
            $data['page'] = strtoupper('Utang');
            $data['subpage'] = strtoupper('Utang');
            $data['active'] = $this->active;
            
            $this->load->view('debt/debt_view', $data, FALSE); 
        }

        public function ajx_data_debt(){
            $list = $this->debt_model->get_datatables();
            $data = array();
            $no = $_POST['start'];
    
            foreach ($list as $field) {
                $id = $field->id_sell;
                if($field->status == 1){
                    $status =  '<span class="badge badge-warning">Belum Lunas</span>';
                    $link = '<button type="button" class="btn btn-sm btn-info" idatr="'.$id.'" id="next"><i class="fa fa-sign-out"></i></button>';
                }else {
                    $status =  '<span class="badge badge-success">Lunas</span>';
                    $link = '<button type="button" class="btn btn-sm btn-default" idatr="'.$id.'" id="next"><i class="fa fa-eye"></i></button>';
                }
                
                $no++;
                $row = array();
                $row[] = $no;
                $row[] = $field->nota;
                $row[] = $field->date_nota;
                $row[] = curr_format($field->debt_total);
                $row[] = $field->employee;
                $row[] = curr_format($field->paid_off);
                $row[] = $field->date_paidoff;
                $row[] = $status;
                $row[] = $link;
    
                $data[] = $row;
            }
    
            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->debt_model->count_all(),
                "recordsFiltered" => $this->debt_model->count_filtered(),
                "data" => $data,
            );
    
            echo json_encode($output);
        }

        public function saving_debt($nota)
        {
            $ceksNota = $this->Sistem->_get_where_id('selling', array('nota'=>$nota));
            
            $cekDebt = $this->Sistem->_get_where_id('debt', array('selling_id'=>$ceksNota['inc_id']));

            if(count($cekDebt) > 0 ){
                redirect('error');
            }

            $sell['status'] = 5; //5 Hutang
            $sell['id_user'] = $this->id_user;
            $sell['total_pembayaran'] = $this->input->post('total_hutang');
            $sell['created'] = date('Y-m-d H:i:s');
            $sell['ket'] = 'INPUT';
            
            $this->Sistem->_update('selling', $sell, array('nota'=>$nota));

            $debt['id'] = hash_id($ceksNota['nota']);
            $debt['selling_id'] = $ceksNota['inc_id'];
            $debt['employee'] = $this->input->post('employee');
            $debt['debt_date'] = date('Y-m-d');
            $debt['debt_total'] = $this->input->post('total_hutang');
            $debt['status'] = 1;
            $debt['id_user'] = $this->id_user;
            $debt['ket'] = 'INPUT';
            $debt['created'] = date('Y-m-d H:i:s');

            $this->Sistem->_input('debt', $debt);
            
            $data['info'] = 'yes';
            echo json_encode($data);
        }

        public function details($id){
            $ceksNota = $this->Sistem->_get_where_id('selling', array('id'=>$id));
            $cekDebt = $this->Sistem->_get_where_id('debt', array('selling_id'=>$ceksNota['inc_id']));
            // echo json_encode($cekDebt);

            if(count($cekDebt) == 0 ){
                redirect('error');
            }
            $data['karyawan'] = $cekDebt['employee'];

            $data['page'] = strtoupper('Utang');
            $data['subpage'] = strtoupper('Utang');
            $data['active'] = $this->active;
            $data['selling'] = $this->selling_model->sell_detail($id);
            $data['list'] = $this->selling_model->list_items($data['selling']['nota']);
            $data['type_paid'] = $this->Sistem->_get_wheres('type_paid', array('ket !='=>'DELETE'));
            $data['user'] = $this->Sistem->_get_where_id('users', array('id'=>$this->id_user));
            $this->load->view('debt/debt_detail_view', $data, FALSE); 
        }

        public function print_debt($start_date, $end_date, $status, $employee, $id=""){
            $tanggal_awal=date_format_db(str_replace('-', '/', $start_date));
            $tanggal_akhir=date_format_db(str_replace('-', '/', $end_date));

            $data['list'] = $this->debt_model->_report_list_debt($tanggal_awal, $tanggal_akhir, $status, $employee);
            $data['debt'] = $this->debt_model->_report_list_debt_id($tanggal_awal, $tanggal_akhir, $status, $employee);
            $data['start_date'] = date_format_display_print($tanggal_awal);
            $data['end_date'] = date_format_display_print($tanggal_akhir);
            $data['title'] = 'DATA UTANG';
            // echo json_encode($data); die;
            $this->load->view('debt/debt_report_employee', $data);
            
        }

        public function debt_paid($nota){
            $ceksNota = $this->Sistem->_get_where_id('selling', array('nota'=>$nota));

            $sell['status'] = 3; //3. Lunas 
            $sell['id_user'] = $this->id_user;
            $sell['type_of_payment'] = $this->input->post('jenis_pembayaran');
            $sell['total_pembayaran'] = $this->input->post('total_pembayaran');
            $sell['total_discount'] = $this->input->post('discount');
            $sell['total_bayar'] = $this->input->post('bayar');
            $sell['created'] = date('Y-m-d H:i:s');
            $sell['updated'] = date('Y-m-d H:i:s');
            $sell['ket'] = 'UPDATE';
            
            $this->Sistem->_update('selling', $sell, array('nota'=>$nota));

            $debt['date_paidoff'] = date('Y-m-d');
            $debt['paid_off'] = $this->input->post('bayar');
            $debt['type_of_paid'] = $this->input->post('jenis_pembayaran');
            $debt['status'] = 2;
            $debt['id_user'] = $this->id_user;
            $debt['ket'] = 'UPDATE';
            $debt['updated'] = date('Y-m-d H:i:s');

            $this->Sistem->_update('debt', $debt, array('selling_id'=>$ceksNota['inc_id']));
    
            $data['info'] = 'yes';
            $data['id'] = $ceksNota['id'];

            echo json_encode($data);
        }
    }
    
    /* End of file Debt.php */
    