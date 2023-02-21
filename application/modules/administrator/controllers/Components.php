<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Components extends CI_Controller {

    public function blank_page()
    {
        $data['page'] = 'Blank';
        // echo json_encode($data);
        $this->load->view('component/blank_view', $data);
    }

}

/* End of file Component.php */
