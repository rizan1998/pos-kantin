<?php 
    setlocale(LC_ALL, 'IND');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <link rel="icon" href="<?php echo base_url()?>theme/dist/favicon.ico" type="image/x-icon">
  
  <title>POS | Kantin</title>
  <script type="text/javascript">
      
    function printDiv(divName) {
       var printContents = document.getElementById(divName).innerHTML;
       var originalContents = document.body.innerHTML;

       document.body.innerHTML = printContents;

       window.print();

       document.body.innerHTML = originalContents;
  }
  </script>
  </head>
<body>
    
  <div id="printArea" >
    <table border="0" width="100%"  class="mb-3">
      <tr>
        <td align="center" width="100%"><font size="2%"><b>KANTIN KELUARGA</b></font>
            <font size="0.5%"><br>Kp. Cigombong No. 64 RT.01/09 Desa Ciherang<br>Telp. (0263) 513513 <br>Website : www.klinikkeluarga.com
            </font></td>
        <td></td>
        </tr>
        <tr>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td colspan="3" style="border-bottom: 0px solid #000; border-top: 1px solid #000;"></td>
        </tr>
    </table>

    
    <h5 style="float:left; padding: 5px; line-height: 1.5em;">
        Nota : <?php echo $selling['nota']?><br>
        Petugas :  <?php echo $user['fullname']?>
    </h5>
    <h5 style="float:right"><?php echo date_format_display_full_hours($selling['created'])?></h5>
    <hr style="background: #000; background-color: #000; clear:both;">
    <table border="0" cellspacing="0" width="100%" style="font-size: 12px;">
        <thead>               
            <tr>
                <td >item</td>
                <td style="text-align: center;" width="40%">qty</td>
                <td style="text-align: center" colspan="2" width="20%">harga</td>
            </tr>
        </thead>
        <tbody>
              <tr style="font-size: 12px;">
                <td colspan="5" style="text-align: center"><hr style=" border: 0; border-bottom: 1px dashed #ccc;"></td>
            </tr> 
            <?php 
                $total = 0;
                $totalitem = 0;
                $discount = 0;
                foreach($list as $l){
                    $harga = $l['price'];
                    echo '<tr>
                        <td>'.$l['name'].'</td>
                        <td style="text-align: center">'.$l['qty'].'</td>
                        <td style="text-align: right; width: 10px">Rp.</td>
                        <td style="text-align: left">
                            '.curr_format($l['qty'] * $l['price']);
                    echo '        
                        </td>
                    </tr>';
                    $total += $harga * $l['qty'];
                    $totalitem += $l['qty'];
                    $discount += $l['discount'];
                }

              $totalpembayaran = ($total - $discount);
            ?>
            <tr style="font-size: 12px;">
                <td colspan="5" style="text-align: center"><hr style=" border: 0;border-bottom: 1px dashed #ccc;"></td>
            </tr>                                 
            <tr style="font-size: 12px;">
                <td colspan="2" style="text-align: right">Sub Total</td>
                <td style="text-align: right; width: 10px">Rp.</td>
                <td style="text-align: left"><?php echo curr_format($total).',-';?></td>
            </tr>
            <tr style="font-size: 12px;">
                <td colspan="2" style="text-align: right">Diskon</td>
                <td style="text-align: right; width: 10px">Rp.</td>
                <td style="text-align: left"><?php echo curr_format($discount).',-';?></td>
            </tr>

            <tr style="font-size: 12px;">
              <td colspan="2" style="text-align: right">Total</td>
              <td style="text-align: right; width: 10px">Rp.</td>
              <td style="text-align: left"><?php echo curr_format($totalpembayaran).',-';?></td>
            </tr>

            <tr style="font-size: 12px;">
                <td colspan="2" align="right">Bayar</td>
                <td style="text-align: right; width: 10px">Rp.</td>
                 <td style="text-align: left"><?php echo curr_format($selling['total_bayar']).',-';?></td>
            </tr>

            <tr style="font-size: 12px;">
                <td colspan="2" align="right">Kembalian</td>
                <td style="text-align: right; width: 10px">Rp.</td>
                <td  style="text-align: left"><?php echo curr_format($selling['total_bayar'] - $totalpembayaran).',-';?></td>
            </tr>
            <tr style="font-size: 12px;">
                <td colspan="4" style="text-align: center"><hr style=" border: 0; border-bottom: 1px dashed #ccc;"></td>
            </tr> 

        </tbody>
    </table> 
    <hr style="background: #000; background-color: #000;">
    
    <p class="antrian_desc" style="text-align: center; font-size: 12px; margin-top: 5px">Terima Kasih atas Kunjungan Anda</p>
  </div>
  <!-- <div id="no-print">
    <center><button type="button" id="print" onclick="printDiv('printArea'); " class="btn btn-success btn-sm"><i class="icon ion-android-print"></i>&nbsp;&nbsp;Print</button></center>
  </div>
              -->
 <script>
  window.print();
 </script>