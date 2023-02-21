<?php 
    $this->load->view('parts/header_part');
?>

    <!-- custom link rel -->

    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php
    $this->load->view('parts/body_part');
    $this->load->view('parts/top_bar_part');
    $this->load->view('parts/left_menu_part');
?>
<!-- Main PART -->
<?php $this->load->view('parts/main_part'); ?>
    <div class="block-header">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <h2><?php echo $subpage ?></h2>
                <ul class="breadcrumb">                   
                    <li class="breadcrumb-item"><?php echo $page ?></li>
                    <li class="breadcrumb-item active"><?php echo $subpage ?></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>DETAIL <?php echo $subpage?></h2>
                </div>
                <div class="body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>NOTA</th>
                                <th>: <?php echo $selling['nota']?></th>
                            </tr>
                            <tr>
                                <th>Tanggal Nota</th>
                                <th>: <?php echo date_format_display($selling['date_nota']) ?></th>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th colspan="2">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
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
                            <tr>
                                <td colspan="2" style="text-align: right">Sub Total</td>
                                <td style="text-align: right; width: 10px">Rp.</td>
                                <td style="text-align: left"><?php echo curr_format($total).',-';?></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: right">Diskon</td>
                                <td style="text-align: right; width: 10px">Rp.</td>
                                <td style="text-align: left"><?php echo curr_format($discount).',-';?></td>
                            </tr>

                            <tr>
                            <td colspan="2" style="text-align: right">Total</td>
                            <td style="text-align: right; width: 10px">Rp.</td>
                            <td style="text-align: left"><?php echo curr_format($totalpembayaran).',-';?></td>
                            </tr>

                            <tr>
                                <td colspan="2" align="right">Bayar</td>
                                <td style="text-align: right; width: 10px">Rp.</td>
                                <td style="text-align: left"><?php echo curr_format($selling['total_bayar']).',-';?></td>
                            </tr>

                            <tr>
                                <td colspan="2" align="right">Kembalian</td>
                                <td style="text-align: right; width: 10px">Rp.</td>
                                <td  style="text-align: left"><?php echo curr_format($selling['total_bayar'] - $totalpembayaran).',-';?></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="right">Jenis Pembayaran</td>
                                <td  style="text-align: left" colspan="2">
                                <?php 
                                    if($selling['type_of_payment'] != NULL)  
                                        $jp = '<span class="badge badge-success">'.$selling['pembayaran'].'</span>'; 
                                    else 
                                        $jp = "";
                                    echo $jp;
                                ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    
                    <a href="<?php echo base_url()?>selling-list" type="button" class="btn btn-default">Kembali</a>
                    <a href="<?php echo base_url()?>administrator/selling/print_bill/<?php echo $selling['id']?>" type="button" class="btn btn-primary" target="_blank">Print Nota</a>
                    
                </div>
            </div>
        </div>


    </div>


    

<!-- END MAIN PART -->
<?php 
    $this->load->view('parts/end_main_part');
    $this->load->view('parts/script_part');
?>
    <!-- CUSTOM JAVASCRIPT -->
    <script src="<?php echo base_url()?>theme/dist/assets/bundles/datatablescripts.bundle.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/js/pages/tables/jquery-datatable.js"></script>



<?php 
    $this->load->view('parts/end_body_part');
    
?>