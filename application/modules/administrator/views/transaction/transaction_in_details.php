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
                <h2>TRANSAKSI <?php echo $subpage ?></h2>
            </div>
            <div class="body">
                <table class="table" width="50%">
                    <thead>
                        <tr>
                            <td>Kode Barang Masuk</td>
                            <td>: <?php echo $transaction['code_in'] ?></td>
                            <input type="hidden" id="code_id" value="<?php echo $transaction['code_in'] ?>">
                        </tr>
                        <tr>
                            <td>Tanggal Barang Masuk</td>
                            <td>: <?php echo date_format_display($transaction['date_in']) ?></td>
                        </tr>
                        <tr>
                            <td>Status Barang Masuk</td>
                            <td>:
                                <?php
                                if ($transaction['status'] == 1) {
                                    $status =  '<span class="badge badge-warning">Process</span>';
                                } elseif ($transaction['status'] == 2) {
                                    $status =  '<span class="badge badge-success">Finish</span>';
                                } else {
                                    $status =  '<span class="badge badge-danger">Cancel</span>';
                                }

                                echo $status;
                                ?>
                            </td>
                        </tr>
                    </thead>
                </table>

                <a href="<?php echo base_url() ?>transaction-in" class="btn btn-default">Kembali</a>

            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <div class="row">
                    <div class="col-md-6">
                        <h2>DETAIL ITEM <?php echo $subpage ?></h2>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-outline-info" id="print_barang_masuk"><i class="fa fa-print"></i> Cetak Barang Masuk</button>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered  table-hover" id="data-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>QTY</th>
                                <th>Harga</th>
                                <th>Diskon</th>
                            </tr>
                        </thead>
                        <tbody id="list-items">
                            <?php
                            $no = 1;
                            foreach ($item as $i) : ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $i['name'] ?></td>
                                    <td class="text-center"><?php echo $i['qty'] ?></td>
                                    <td class="text-right"><?php echo curr_format($i['price']) ?></td>
                                    <td class="text-right"><?php echo curr_format($i['discount']) ?></td>

                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
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

<script>
    $(document).ready(function() {
        $('#print_barang_masuk').click(function() {
            let id = "<?php echo $transaction['id'] ?>";
            window.open("<?php echo base_url(); ?>administrator/transaction/print_barang_masuk/" + id, "_blank");
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php
$this->load->view('parts/end_body_part');

?>