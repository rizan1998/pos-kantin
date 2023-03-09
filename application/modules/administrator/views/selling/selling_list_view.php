<?php
$this->load->view('parts/header_part');
?>

<!-- custom link rel -->

<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">

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
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="d-flex flex-row-reverse">
                <div class="page_action">
                    <!-- <button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Cetak Penjualan</button> -->
                    <a href="<?php echo base_url() ?>selling" type="button" class="btn btn-secondary"><i class="fa fa-send"></i> Tambah Penjualan</a>
                </div>
                <div class="p-2 d-flex">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-12 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <h2>TOOLS PENCARIAN <?php echo strtoupper($subpage) ?></h2>
            </div>
            <div class="body">
                <div class="row">
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label for="">Tanggal Awal</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" id="start-date" name="start_date" value="<?php echo date('d/m/Y') ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="form-group">
                            <label for="">Tanggal Akhir</label>
                            <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" id="end-date" name="end_date" value="<?php echo date('d/m/Y') ?>">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <div class="form-group">
                            <label for="">Status</label>
                            <select name="status" id="status" class="form-control" required="required">
                                <option value="-">-</option>
                                <option value="1">Not Finish</option>
                                <option value="2">Saving</option>
                                <option value="3">Finish</option>
                                <option value="4">Cancel</option>
                                <option value="5">Hutang</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <label for="">&nbsp;</label>
                        <button type="submit" name="search" id="search-date" class="btn btn-primary btn-block">Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="card planned_task">
            <div class="header">
                <div class="row">

                    <div class="col-lg-6 hidden-sm text-left">
                        <h2>DATA <?php echo strtoupper($subpage) ?></h2>
                    </div>
                    <?php
                    $roles = $this->session->userdata('roles');

                    if ($roles != 3) :
                    ?>
                        <div class="col-lg-6 hidden-sm text-right">
                            <button type="button" class="btn btn-outline-info" data-target="#modal-print" data-toggle="modal"><i class="fa fa-print"></i> Cetak Detail <?php echo $subpage ?></button>
                            <button type="button" class="btn btn-outline-info" data-target="#modal-print-penjualan" data-toggle="modal"><i class="fa fa-print"></i> Cetak <?php echo $subpage ?></button>

                        </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered  table-hover" id="data-datatable" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nota</th>
                                <th>Tanggal Nota</th>
                                <th>Total Pembayaran</th>
                                <th>Status </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="modal-print" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Data Detail <?php echo $subpage ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Tanggal Awal</label>
                    <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" id="start-date-print" name="start_date_print" value="<?php echo date('01/m/Y') ?>">
                </div>

                <div class="form-group">
                    <label for="">Tanggal Akhir</label>
                    <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" id="end-date-print" name="end_date_print" value="<?php echo date('t/m/Y') ?>">
                </div>

                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status_print" id="status-print" class="form-control" required="required">
                        <option value="-">-</option>
                        <option value="1">Not Finish</option>
                        <option value="2">Saving</option>
                        <option value="3">Finish</option>
                        <option value="4">Cancel</option>
                        <option value="5">Hutang</option>
                    </select>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-print">Cari Data</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-print-penjualan" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Data <?php echo $subpage ?> Harian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Tanggal Penjualan</label>
                    <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" id="date_seller" name="start_date_print" value="<?php echo date('d/m/Y') ?>">
                </div>


                <label for="">Jenis Item</label>
                <select name="category_item" id="category_item" class="form-control">
                    <option disabled value="0">pilih category</option>
                    <?php foreach ($categoryes as $category) { ?>
                        <option value="<?= $category['id'] ?>"><?= $category['name']; ?></option>
                    <?php } ?>
                </select>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-print-category">Cari Data</button>
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
        $('#start-date').datepicker({
            format: 'dd/mm/yyyy'
        });

        $('#end-date').datepicker({
            format: 'dd/mm/yyyy'
        });


        table = $('#data-datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url(); ?>administrator/selling/ajx_data_selling/",
                "type": "POST",
                "data": function(data) {
                    data.start_date = $('#start-date').val();
                    data.end_date = $('#end-date').val();
                    data.status = $('#status').val();
                    data.list = 1;
                }
            },

            "columnDefs": [{
                "targets": [5, 0],
                "orderable": false,
                "class": "text-center"
            }],
        });


        $("#search-date").click(function() {
            table.ajax.reload();
        })

        $('#data-datatable').on('click', '#trans', function() {
            var id = $(this).attr('idatr');
            window.location.href = "<?php echo base_url() ?>selling-edit/" + id;
            // alert(id);
        });

        $('#data-datatable').on('click', '#details', function() {
            var id = $(this).attr('idatr');
            window.location.href = "<?php echo base_url() ?>selling-detail/" + id;
            // alert(id);
        });

        $('#data-datatable').on('click', '#debt', function() {
            var id = $(this).attr('idatr');
            window.location.href = "<?php echo base_url() ?>debt-details/" + id;
            // alert(id);
        });



        $('#data-datatable').on('click', '#delete', function() {
            var id = $(this).attr('idatr');
            console.log(id);
            swal.fire({
                title: 'Are you sure?',
                text: "Kamu akan menghapus transaksi ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, hapus ini!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get("<?php echo base_url(); ?>administrator/selling/cancel_selling/" + id, function(data) {
                        var req = JSON.parse(data);
                        if (req.info == "yes") {
                            table.ajax.reload();
                            swal.fire("Berhasil!", "data telah terhapus", "success");
                        } else {
                            swal.fire("Error!", "data gagal terhapus", "error");
                        }
                    })
                }
            });
        });


        $("#btn-print").click(function() {
            const id = "<?php echo hash_id(date('ymdHis')) ?>";

            const data = {
                "start_date": $('#start-date-print').val(),
                "end_date": $('#end-date-print').val(),
                "status": $('#status-print').val(),
                "employee": $('#employee-print').val(),
            }

            var tanggal_awal = data.start_date.split('/').join('-');
            var tanggal_akhir = data.end_date.split('/').join('-');


            window.open("<?php echo base_url(); ?>administrator/selling/print_selling/" + tanggal_awal + "/" + tanggal_akhir + "/" + data.status + "/" + id, "_blank");

        })

        $("#btn-print-category").click(function() {
            const id = "<?php echo hash_id(date('ymdHis')) ?>";

            let date_seller_select = $("#date_seller").val();
            let date_seller = date_seller_select.split('/').join('-');
            let category_id = $('#category_item').val();

            window.open("<?php echo base_url(); ?>administrator/selling/print_item_percategory/" + date_seller + "/" + category_id + "/" + id, "_blank");

        });

        $('#jenis_laporan').on('change', function() {
            let jenisLaporan = $(this).children("option:selected").val();
            $('#select_category_item').val('');
            if (jenisLaporan == 0 || jenisLaporan == 'struk') {
                $("#category_item").hide();
            } else {
                $("#category_item").show();
            }
        });

    })
</script>

<?php
$this->load->view('parts/end_body_part');

?>