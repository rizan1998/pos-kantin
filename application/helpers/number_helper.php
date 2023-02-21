<?php  defined('BASEPATH') OR exit('No direct script access allowed');
	
	function curr_format($price=""){
		if(empty($price)) return 0;
		
		$curr=get_instance();

		$decimal_point=",";
		$thousand_point=".";
		$decimal_prec="0";		

		return number_format($price, $decimal_prec, $decimal_point, $thousand_point);
	}

	function curr_format2($price=""){
		if(empty($price)) return "-";
		
		$curr=get_instance();

		$decimal_point=",";
		$thousand_point=".";
		$decimal_prec="0";		

		return number_format($price, $decimal_prec, $decimal_point, $thousand_point);
	}

	function curr_format3($price=""){
		if(empty($price)) return "";
		
		$curr=get_instance();

		$decimal_point=",";
		$thousand_point=".";
		$decimal_prec="0";		

		return number_format($price, $decimal_prec, $decimal_point, $thousand_point);
	}

	function dot_format($value=0){

		$ci =& get_instance();
		$decimal_point=",";
		$thousand_point=".";
		$decimal_prec="0";
		
		if( (strstr($value, ".")) or (strstr($value, ",")) ){
			if($decimal_point == "."){
				return str_replace(",", "", $value);
			}else{
				$value=str_replace(".","",$value);
				return $value=str_replace(",",".",$value);
			}
		}elseif(is_numeric($value) || $value>0){
			if($decimal_point == "."){
				return str_replace(",", "", $value);
			}else{
				$value=str_replace(".","",$value);
				return $value=str_replace(",",".",$value);
			}
		}else{
			return $value=0;
		}

	}

	function penyebut($nilai) {
		$nilai = abs($nilai);
		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = penyebut($nilai - 10). " belas";
		} else if ($nilai < 100) {
			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " seratus" . penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " seribu" . penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
		}     
		return $temp;
	}
 
	function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim(penyebut($nilai));
		} else {
			$hasil = trim(penyebut($nilai));
		}     		
		return $hasil;
	}

	function getRomawi($bln){
		switch ($bln){
				case 1: 
					return "I";
					break;
				case 2:
					return "II";
					break;
				case 3:
					return "III";
					break;
				case 4:
					return "IV";
					break;
				case 5:
					return "V";
					break;
				case 6:
					return "VI";
					break;
				case 7:
					return "VII";
					break;
				case 8:
					return "VIII";
					break;
				case 9:
					return "IX";
					break;
				case 10:
					return "X";
					break;
				case 11:
					return "XI";
					break;
				case 12:
					return "XII";
					break;
		  }
   }

   function pembulatan($uang)
	{
		$totalharga=ceil($uang);
		if (substr($totalharga,-2)<100){
			$total_harga=round($totalharga,-2);
		} else {
			$total_harga=round($totalharga,-2)+100;
		}

		return $total_harga;
	}
?>