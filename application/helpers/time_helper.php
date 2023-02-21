<?php  
defined('BASEPATH') OR exit('No direct script access allowed');

	function date_format_display_full_hours($date=""){
		$format="d F Y - H:m:s";
		$get_date=date($format, strtotime($date));
		return $get_date;
	}	

	function date_format_display_full_hours_euro($date=""){
		$format="d/m/Y H:m:s";
		$get_date=date($format, strtotime($date));
		return $get_date;
	}	


	function date_format_display_month_day_year($date=""){
		$format="M d, Y";
		$get_date=date($format, strtotime($date));
		return $get_date;
	}	


	function date_format_display_print($date=""){
		$format="d M Y";
		$get_date=date($format, strtotime($date));
		return $get_date;
	}	

	function date_format_unix_datetime($datetime=""){
		$format="Y-m-d\TH:i:s.000\Z";
		$get_date=date($format, strtotime($datetime));
		return $get_date;
	}

	function date_format_display($date=""){
		/*$format="m/d/Y";
		$get_date=date($format, strtotime($date));
		return $get_date;*/

		$exp=explode("-", $date);
		$tanggal=$exp[2]."/".$exp[1]."/".$exp[0];

		return $tanggal;		
	}	

	function date_format_display2($date=""){
		$format="m/d/Y";
		$get_date=date($format, strtotime($date));
		return $get_date;		
	}	

	function date_format_bpjs($date=""){
		$exp=explode("-", $date);
		$tanggal=$exp[2]."-".$exp[1]."-".$exp[0];

		return $tanggal;				
	}

	function time_format_db($time=""){
		$format=date('H:i', strtotime($time));
		$detik=date("s");

		$waktu=$format.":".$detik;
		return $waktu;
	}


	function time_display($time=""){
		$format=date('h:i A', strtotime($time));

		$waktu=$format;
		return $waktu;
	}

	function time_format_show($time=""){
		$waktu=date('h:i A', strtotime($time));
		return $waktu;
	}

	function date_format_db($date=""){
		if($date==""){
			$date="00/00/0000";
		}

		$exp=explode("/", $date);

		$tanggal=$exp[2]."-".$exp[1]."-".$exp[0];

		return $tanggal;
	}

	function time_cek(){
		date_default_timezone_set("Asia/Jakarta");
	}

	function tanggal_indo($tanggal)
	{
		$bulan = array (1 =>   'Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
				);
		$split = explode('-', $tanggal);
		return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	
	}
		

	function hari($tanggal){
		$day = date('D', strtotime($tanggal));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);		

		return $dayList[$day];
	}

	function hari_number($day){
		$dayList = array(
			0 => 'Minggu',
			1 => 'Senin',
			2 => 'Selasa',
			3 => 'Rabu',
			4 => 'Kamis',
			5 => 'Jumat',
			6 => 'Sabtu'
		);		

		return $dayList[$day];
	}	

	function bulan_list(){
		$bulan = array ('01' =>   'Januari',
					'02'=>'Februari',
					'03'=>'Maret',
					'04'=>'April',
					'05'=>'Mei',
					'06'=>'Juni',
					'07'=>'Juli',
					'08'=>'Agustus',
					'09'=>'September',
					'10'=>'Oktober',
					'11'=>'November',
					'12'=>'Desember'
				);	
				
		return $bulan;
	}

	function bulan_list2($a){
		$bulan = array ('01' =>   'Januari',
					'02'=>'Februari',
					'03'=>'Maret',
					'04'=>'April',
					'05'=>'Mei',
					'06'=>'Juni',
					'07'=>'Juli',
					'08'=>'Agustus',
					'09'=>'September',
					'10'=>'Oktober',
					'11'=>'November',
					'12'=>'Desember'
				);	
				
		return $bulan[$a];
	}	


	function bulan_list3($a){
		$bulan = array ('1' =>   'Januari',
					'2'=>'Februari',
					'3'=>'Maret',
					'4'=>'April',
					'5'=>'Mei',
					'6'=>'Juni',
					'7'=>'Juli',
					'8'=>'Agustus',
					'9'=>'September',
					'10'=>'Oktober',
					'11'=>'November',
					'12'=>'Desember'
				);	
				
		return $bulan[$a];
	}	
	
?>