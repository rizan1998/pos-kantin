<?php
setlocale(LC_ALL, 'IND');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" href="<?php echo base_url() ?>theme/dist/favicon.ico" type="image/x-icon">
    <title>POS | <?php echo $title ?></title>
    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    <style type="text/css">
        body {
            font-family: 'Open Sans', serif;
        }

        #print-faktur {
            margin: 0 auto;
            width: 100%;
        }

        #footer {
            font-size: 10px;
            position: absolute;
        }

        .hr {
            width: 100%;
            border-bottom: 3px double;
            margin-bottom: 10px;
        }

        .alamat {
            margin-top: -15px;
            width: 60%;
            font-size: 9px;
        }

        h2 {
            font-size: 18px;
        }

        .phone {
            margin-top: -20px;
            font-size: 9px
        }

        table#ket-faktur {
            font-size: 9px;
        }

        table#item {
            font-size: 12px;
            border: 1px solid #000;
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            border-collapse: collapse;
            line-height: 8px;
        }

        table#item td {
            border-bottom: 1px solid #000;
            border-right: 1px solid #000;
            padding: 10px;
            font-size: 13px;
        }

        table#item th {
            border-right: 0px solid #000;
            padding: 10px;
            font-size: 14px;
        }

        table#item tr {
            border-right: 1px solid #000;
            padding: 5px;
        }

        table#item tbody {
            border: 1px solid #000;
        }

        table#item2 {
            font-size: 11px;
            margin-top: 10px;
        }

        table#item2 tbody {
            border: 1px solid #000;
        }

        @media print {
            #print {
                display: none;
            }

            /* @page {size: landscape} */
        }
    </style>
</head>

<body oncontextmenu="return false" onload="window.print()">

    <div id="printArea">
        <table border="0" width="100%" class="mb-3">
            <tr>
                <td align="center" width="100%">
                    <font size="4%"><b>KANTIN KELUARGA</b></font>
                    <font size="2%"><br>Kp. Cigombong No. 64 RT.01/09 Desa Ciherang<br>Telp. (0263) 513513 <br>Website : www.klinikkeluarga.com
                    </font>
                </td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 0px solid #000; border-top: 1px solid #000;"></td>
            </tr>
        </table>

        <h4 style="text-align:center">STOCK OPNAME KANTIN KELUARGA BULAN <?php echo strtoupper(date('F Y', strtotime($tgl_so))) ?></h4>
        <table width="100%" border="1" id="item">
            <thead>

                <tr>
                    <th>No</th>
                    <th width='10%'>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga beli</th>
                    <th>Stok awal</th>
                    <th>Nilai</th>
                    <th>Barang masuk</th>
                    <th>Nilai</th>
                    <th>Penjualan</th>
                    <th>Nilai</th>
                    <th>Stok Sistem</th>
                    <th>Stok Fisik</th>
                    <th>Selisih</th>
                    <th>total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total_harta = 0;
                $total_stock_awal = 0;
                $total_barang_masuk = 0;
                $total_penjualan = 0;
                $total_minus = 0;
                foreach ($items as $item) {
                    $total_harga = $item['stock_physic'] * $item['harga_beli'];
                    // $total_stock_awal = $item['stock_awal'] * $item['harga_beli'];
                    // $total_barang_masuk = $item['total_barang_masuk'] * $item['harga_beli'];
                    // $total_penjualan = $item['total_penjualan'] * $item['price'];

                    if ($item['stock_system'] < $item['stock_physic']) {
                        $dif = '<span class="badge badge-success">' . $item['differential'] . '</span>';
                    } else {
                        $dif = '<span class="badge badge-danger">' . $item['differential'] . '</span>';
                    }
                    // nilai barang
                    $nilai_barang_masuk = $item['harga_beli'] * $item['total_barang_masuk'];
                    $int_nilai_barang_masuk = $nilai_barang_masuk;
                    $nilai_barang_masuk = number_format($nilai_barang_masuk, 0, ',', '.');

                    $nilai_harga_jual = $item['total_penjualan'] * $item['price'];
                    $int_nilai_harga_jual = $nilai_harga_jual;
                    $nilai_harga_jual = number_format($nilai_harga_jual, 0, ',', '.');

                    $nilai_stock_awal = $item['stock_awal'] * $item['harga_beli'];
                    $int_nilai_stock_awal = $nilai_stock_awal;
                    $nilai_stock_awal = number_format($nilai_stock_awal, 0, ',', '.');



                    $differend = intval($dif);
                    $nilai_minus = $differend * $item['harga_beli'];
                    echo gettype($differend);

                    echo '
                     <tr>
              <td>' . $no++ . '-' . $item['items_sell_id'] . '</td>
              <td>' . $item["name"] . 'barang masuk = ' . $item['stock_awal'] . '</td>
              <td> ' . $item["unit_name"] . '</td>
              <td>Rp. ' . $item['harga_beli'] . '</td>
              <td>' . $item['stock_awal'] . '</td>|
              <td>Rp. ' . $nilai_stock_awal . '</td>
              <td> ' . $item['total_barang_masuk'] . '</td>
              <td>Rp. ' . $nilai_barang_masuk . '</td>
              <td>' . $item['total_penjualan'] . '</td> 
              <td>Rp. ' . $nilai_harga_jual . '</td>
              <td class="text-center">' . $item['stock_system'] . '</td>
              <td class="text-center">' . $item['stock_physic'] . '</td>
              <td class="text-center">' . $dif . '</td>
              <td style="text-align: right">Rp ' . number_format($total_harga, 0, ',', '.') . '</td>
          </tr>';
                    $total_harta += $total_harga;
                    $total_stock_awal += $int_nilai_stock_awal;
                    $total_barang_masuk += $int_nilai_barang_masuk;
                    $total_penjualan += $int_nilai_harga_jual;
                    $total_minus += $nilai_minus;
                } ?>
                <tr>
                    <th colspan="4" align="right">Total</th>
                    <th></th>
                    <th align="left">Rp. <?php echo number_format($total_stock_awal, 0, ',', '.') ?></th>
                    <th></th>
                    <th align="left">Rp. <?php echo number_format($total_barang_masuk, 0, ',', '.') ?></th>
                    <td></td>
                    <th align="left">Rp. <?php echo number_format($total_penjualan, 0, ',', '.') ?></th>
                    <th colspan="2"></th>
                    <th align="left">Rp. <?php echo ' ' . number_format($total_minus, 0, ',', '.') ?></th>
                    <th style="text-align: right" align="left">Rp. <?php echo ' ' . number_format($total_harta, 0, ',', '.') ?></th>

                </tr>
            </tbody>
        </table>
    </div>