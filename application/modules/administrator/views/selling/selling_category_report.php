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

<body oncontextmenu="return false">
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

        <h4 style="text-align: center;">LAPORAN <?php echo $title ?></h4>
        <h4 style="text-align: center; margin-top:-15px"><?php echo $date_seller  ?></h4>

        <table width="100%" border="1" id="item">
            <thead>
                <tr>
                    <th style="text-align: center">No</th>
                    <th style="text-align: center" width="">Nama</th>
                    <th style="text-align: center">Stok awal</th>
                    <th style="text-align: center">Penambahan</th>
                    <th style="text-align: center">Penjualan</th>
                    <th style="text-align: center">Harga</th>
                    <th style="text-align: center">Total harga</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalBayar = 0;
                foreach ($list_laporan as $l) {

                    $totalHarga = $l['jumlah_penjualan'] * $l['harga_jual'];



                    echo '
                    <tr>
                        <td style="text-align: center">' . $no++ . '</td>
                        <td style="text-align: right" >' . $l['name'] . '</td>
                        <td style="text-align: center">' . $l['stock'] . '</td>
                        <td style="text-align: center">' . $l['jumlah_barang_masuk'] . '</td>
                        <td style="text-align: center">' . $l['jumlah_penjualan'] . '</td>
                        <td style="text-align: right">Rp.' . curr_format($l['harga_jual']) . '</td>
                        <td style="text-align: right">Rp.' . curr_format($totalHarga) . '</td>
                    </tr>';
                    $totalBayar += $totalHarga;
                }
                ?>

                <tr>
                    <th colspan="6" style="text-align: right">TOTAL PENJUALAN</th>

                    <th style="text-align: right">Rp.<?php echo curr_format($totalBayar) ?></th>
                </tr>
            </tbody>
        </table>
    </div>