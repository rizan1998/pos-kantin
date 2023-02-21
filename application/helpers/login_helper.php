<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

	function cache_login(){
		$cache_login=get_instance();
		$cache_login->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$cache_login->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$cache_login->output->set_header('Pragma: no-cache');
		$cache_login->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 				
	}

	function is_logged_in(){

		$login=get_instance();
		$is_logged_in = $login->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true){
			$login->session->set_flashdata('msg','Access denied you don\'t have permission to access this page or session has expired. Please use login form!');
			redirect('login');
		}

	}	
