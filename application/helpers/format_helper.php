<?php defined('BASEPATH') OR exit('No direct script access allowed');
    function create_url($string, $ext='.html'){     
        $replace = '-';         
        $string = strtolower($string);        
        $string = preg_replace("/[\/\.]/", " ", $string);     
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);       
        $string = preg_replace("/[\s-]+/", " ", $string);     
        $string = preg_replace("/[\s_]/", $replace, $string);       
        $string = substr($string, 0, 100);     

        return $string;
    }  

    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function get_umur($date=""){
        $tgl_lahir=$date;
        $tgl = substr($tgl_lahir, 0, 2);
        $bln = substr($tgl_lahir, 3, 2);
        $thn = substr($tgl_lahir, 6, 4);
        $tgl_lahir1 = $thn.'-'.$bln.'-'.$tgl;
        $selisih = date_diff(date_create(), date_create($tgl_lahir1));
        $umur = $selisih->format('%y tahun %m bulan %d hari');         
        return $umur;       
    }

    function get_umur_tahun($date=""){
        if(empty($date)){
            return 0;
        }else{
            $tgl_lahir=$date;
            $tgl = substr($tgl_lahir, 0, 2);
            $bln = substr($tgl_lahir, 3, 2);
            $thn = substr($tgl_lahir, 6, 4);
            $tgl_lahir1 = $thn.'-'.$bln.'-'.$tgl;
            $selisih = date_diff(date_create(), date_create($tgl_lahir1));
            $umur = $selisih->format('%y tahun');         
            return $umur;  
        }     
    }

    function get_umur_hari($date=""){
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime(date('Y-m-d'));
        $interval = $datetime1->diff($datetime2);
        $hari=$interval->days." Hari";
        return $hari;    
    }

    function rand_color() {
            $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
            $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
            return $color;
     }

      function rgb_color($opacity=1){
       $r = rand(128,255); 
       $g = rand(128,255); 
       $b = rand(128,255); 

      return $color="rgba(".$r.",".$g.",".$b.",".$opacity.")";        
      }

    
    function aasort (&$array, $key) {
        $sorter=array();
        $ret=array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii]=$va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii]=$array[$ii];
        }
        $array=$ret;
    }

/* unice code */
    function get_number_pendaftaran($perusahaan=0){
        $get=get_instance();
        $ceks=strlen($perusahaan);
        $get->load->model('sistem_model');

        if($ceks==1){
            $id_perusahaan="000".$perusahaan;
        }elseif($ceks==2){
            $id_perusahaan="00".$perusahaan;
        }elseif($ceks==3){
            $id_perusahaan="0".$perusahaan;
        }else{
            $id_perusahaan=$perusahaan;
        }

        $query=$get->sistem_model->_get_where_id('kk_kode', array('id_perusahaan'=>$perusahaan));
        $month=date('m');
        $bulan_val=$query['bulan'];
        

        if($month<>$bulan_val){
            $number_val=1;
        }else{
            $number_val=$query['kode'];
        }
        
        $ceks_number=strlen($number_val);
        if($ceks_number==1){
            $val_number="000".$number_val;
        }elseif($ceks_number==2){
            $val_number="00".$number_val;
        }elseif($ceks_number==3){
            $val_number="0".$number_val;
        }else{
            $val_number=$number_val;
        }

        $unikkode=rand(10000 , 99999);
        
        
        return $kode=$month.$val_number.$id_perusahaan.$unikkode;
    }

    function no_reg($id_perusahaan=0){

        $con=get_instance();
        $con->load->model('sistem_model');

        $y=date('Y');
        $m=date('m');

        // $con->db->trans_start();

            $month_no_reg=$con->sistem_model->_get_where_id('kk_config', array('type'=>'update_no_reg'));
            $query=$con->sistem_model->_get_where_id('kk_config', array('type'=>'noreg'));
            
            if($m==$month_no_reg['value']){
                $no=$query['value'];
                $length=strlen($no);

            }else{
                $no=1;
                $length=strlen($no);
                $con->sistem_model->_update('kk_config', array('value'=>$no), array('type'=>'noreg'));
                $con->sistem_model->_update('kk_config', array('value'=>$m), array('type'=>'update_no_reg'));
            }

        // $con->db->trans_complete();

        if($length>=5){
           $number=$no;
        }elseif($length==4){
           $number="0".$no;

        }elseif ($length==3) {
           $number="00".$no;
        }elseif($length==2){
           $number="000".$no;
        }else{
           $number="0000".$no;
        }


        $noreg=$y.$m.$number;
        return $noreg;
    }   

    function no_reg_upd($id_perusahaan=0){

        $con=get_instance();
        $con->load->model('sistem_model');
        $m=date('m');

        $query=$con->sistem_model->_get_where_id('kk_config', array('type'=>'noreg'));
        $month_no_reg=$con->sistem_model->_get_where_id('kk_config', array('type'=>'update_no_reg'));

        if($m==$month_no_reg['value']){
            $no=$query['value']+1;
        }else{
            $no=1;
            $upd=$con->sistem_model->_update('kk_config', array('value'=>$m), array('type'=>'update_no_reg'));
        }

        $upd=$con->sistem_model->_update('kk_config', array('value'=>$no), array('type'=>'noreg'));

    }

    function get_nomor_antrian($perusahaan=0){
        $get=get_instance();
        $get->load->model('sistem_model');
        $tanggal=date('Y-m-d');

        // $get->db->trans_start();
            $get_nomor=$get->sistem_model->_get_where_id('kk_config', array('type'=>'nomor_antrian', 'id_perusahaan'=>$perusahaan));
            $get_tanggal=$get->sistem_model->_get_where_id('kk_config', array('type'=>'tanggal_antrian', 'id_perusahaan'=>$perusahaan));

            if(count($get_nomor)>0){
                if($tanggal!=$get_tanggal['value']){
                    $nomor=1;
                    $upd1['value']=2;
                    $upd2['value']=$tanggal;

                    $get->sistem_model->_update('kk_config', $upd1, array('type'=>'nomor_antrian', 'id_perusahaan'=>$perusahaan));

                    $get->sistem_model->_update('kk_config', $upd2, array('type'=>'tanggal_antrian', 'id_perusahaan'=>$perusahaan));
                }else{
                    $nomor=$get_nomor['value'];

                    $upd1['value']=$nomor+1;
                    $get->sistem_model->_update('kk_config', $upd1, array('type'=>'nomor_antrian', 'id_perusahaan'=>$perusahaan));
                }

            }else{
                $inst['type']="nomor_antrian";
                $inst['value']=2;
                $inst['id_perusahaan']=$perusahaan;

                $inst2['type']="tanggal_antrian";
                $inst2['value']=$tanggal;
                $inst2['id_perusahaan']=$perusahaan;

                $get->sistem_model->_input('kk_config', $inst);
                $get->sistem_model->_input('kk_config', $inst2);

                $nomor=1;
            }

        // $get->db->trans_complete();

        return $nomor;

    }

    function kode_provider(){
        $con=get_instance();
        $con->load->model('sistem_model');
        $id_perusahaan=$con->session->userdata('id_perusahaan');

        $perusahaan=$con->sistem_model->_get_where_id('kk_perusahaan', array('id'=>$id_perusahaan));

        $kode_provider=$perusahaan['kode_provider'];//"0119B006";
        return $kode_provider;
    }

    function akses_bpjs(){
        $akses="true";
        return $akses;
    }


    function get_module($id_user=0, $id_perusahaan=0){
        $get=get_instance();
        $get->load->model('sistem_model');        
        $id_perusahaan=$get->session->userdata('id_perusahaan');
        $id_user=$get->session->userdata('id_user');

        $result=$get->sistem_model->_get_wheres('kk_akses', array('type'=>'kia', 'id_perusahaan'=>$id_perusahaan, 'id_user'=>$id_user));

        return $result;

    }

    function number_of_working_days($startDate, $endDate)
    {
        $workingDays = 0;
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);
        for ($i = $startTimestamp; $i <= $endTimestamp; $i = $i + (60 * 60 * 24)) {
            if (date("N", $i) <= 5) $workingDays = $workingDays + 1;
        }
        return $workingDays;
    }

    function sub_poli($id_layanan=0){
        $get=get_instance();
        $get->load->model('sistem_model');
        $result=$get->sistem_model->_get_wheres('kk_poli_sub', array('id_poli'=>$id_layanan));
        
        return $result;
    }

    function week_between_two_dates($date1, $date2)
    {
    $first = DateTime::createFromFormat('d/m/Y', $date1);
    $second = DateTime::createFromFormat('d/m/Y', $date2);
    if($date1 > $date2) return week_between_two_dates($date2, $date1);
    return floor($first->diff($second)->days/7);
    }

    function day_between_two_dates($date1, $date2)
    {
    $first = DateTime::createFromFormat('d/m/Y', $date1);
    $second = DateTime::createFromFormat('d/m/Y', $date2);
    if($date1 > $date2) return day_between_two_dates($date2, $date1);
    return floor($first->diff($second)->days);
    }

    function hash_id($string){
        // hash_id = sha2(CONCAT(NOW(), *filed_id_name* , concat( uuid())),256)
        $hash = uniqid().date('Y-m-d H:i:s').$string;
        $hash_id = hash('sha256', $hash);
        return $hash_id;
    }

    // function limit_words($string, $word_limit){
    //     $words = explode(" ",$string);
    //     echo implode(" ",array_splice($words,0,$word_limit));
    // }

    function limit_words($string, $count) { 
        $words = substr(strip_tags($string),$count,1);
        if($words !=" "){
            while($words !=" "){
                $i=1;
                $count += $i;
                $words = substr(strip_tags($string),$count,1);
            }
        }
        $words = substr(strip_tags($string),0,$count);
        return $words;
    }

    function sex_status($sex){
        return $sex==0 ? 'Laki-laki' : 'Perempuan';
    }

    function slug($string = ""){
        $explode = explode(" ",strtolower($string));
        $implode = implode("-",$explode);

        return $implode;
    }
?>