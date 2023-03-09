<?php
$this->load->view('parts/header_part');
?>

    <!-- custom link rel -->

    <link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<?php
$this->load->view('parts/body_part');
$this->load->view('parts/top_bar_part');
$this->load->view('parts/left_menu_part');
?>
<!-- Main PART -->
<?php $this->load->view('parts/main_part');?>
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
                    <h2>TRANSAKSI  <?php echo $subpage ?></h2>
                </div>
                <div class="body">
                    <table class="table" width="50%">
                        <thead>
                            <tr>
                                <td>Kode Stock Opname</td>
                                <td>: <?php echo $stockopname['code_in'] ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Stock Opname</td>
                                <td>: <?php echo date_format_display($stockopname['date_stockopname']) ?></td>
                            </tr>
                            <tr>
                                <td>Status Stock Opname</td>
                                <td>:
                                    <?php
if ($stockopname['status'] == 1) {
    $status = '<span class="badge badge-warning">Process</span>';
} elseif ($stockopname['status'] == 2) {
    $status = '<span class="badge badge-success">Finish</span>';
} else {
    $status = '<span class="badge badge-danger">Cancel</span>';
}

echo $status;
?>
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <a href="<?php echo base_url() ?>stock-opname" class="btn btn-default">Kembali</a>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>DETAIL ITEM <?php echo $subpage ?></h2>
                </div>
                <div class="body">
                     <div class="table-responsive">
                        <table class="table table-bordered  table-hover" id="data-datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Stock Fisik</th>
                                    <th>Stock Sistem</th>
                                    <th>Selisih</th>
                                </tr>
                            </thead>
                            <tbody id="list-items">
                            <?php
$no = 1;
foreach ($item as $i):
    if ($i['stock_system'] < $i['stock_physic']) {
        $dif = '<span class="badge badge-success">' . $i['differential'] . '</span>';
    } else {
        $dif = '<span class="badge badge-danger">' . $i['differential'] . '</span>';
    }

    ?>
															                                    <tr>
															                                        <td><?php echo $no++ ?></td>
															                                        <td><?php echo $i['name'] ?></td>
															                                        <td class="text-center"><?php echo $i['stock_physic'] ?></td>
															                                        <td class="text-center"><?php echo $i['stock_system'] ?></td>
															                                        <td class="text-center"><?php echo $dif ?></td>

															                                    </tr>
															                            <?php endforeach?>
                            </tbody>
                        </table>
                     </div>
                     <div style="text-align:right">
                        <button id="print" class="btn btn-primary">Print</button>
                     </div>
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
    <script src="<?php echo base_url() ?>theme/dist/assets/bundles/datatablescripts.bundle.js"></script>
    <script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
    <script src="<?php echo base_url() ?>theme/dist/assets/js/pages/tables/jquery-datatable.js"></script>

    <script src="<?php echo base_url() ?>theme/dist/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
          $(document).ready(function(){
            $("#print").on('click', function(){
                let stockOpnameId = "<?=$inc_id;?>";
                let category_id = '<?=$category_id;?>';

                window.open('<?php echo base_url() ?>stock-opname-cetak/'+stockOpnameId+"/"+category_id, '_blank');
            })
          })
    </script>


<?php
$this->load->view('parts/end_body_part');

?>