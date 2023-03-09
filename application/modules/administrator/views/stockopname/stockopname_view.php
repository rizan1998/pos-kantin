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
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="d-flex flex-row-reverse">
                    <div class="page_action">
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modal-transin"><i class="fa fa-send"></i> TAMBAH <?php echo $subpage ?></button>
                    </div>

                    <!-- Modal -->
                    <div class="modal" id="modal-transin" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Input Stock Opname</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Kode Stock Opname</label>
                                        <input type="text" name="code" id="code" value="<?php echo $code ?>" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Stock Opname</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" class="form-control" id="date-in" name="date_stockopname" value="<?php echo date('d/m/Y') ?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="savetransin()">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>TOOLS PENCARIAN <?php echo $subpage ?></h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-lg-4 col-md-12">
                            <div class="form-group">
                                <label for="">Tanggal Awal</label>
                                <input type="text" data-provide="datepicker" data-date-autoclose="true"  class="form-control" id="start-date" name="start_date" value="<?php echo date('d/m/Y') ?>">
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
                                    <option value="2">Finish</option>
                                    <option value="3">Cancel</option>
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
        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>DATA <?php echo $subpage ?></h2>
                </div>
                <div class="body">
                     <div class="table-responsive">
                        <table class="table table-bordered  table-hover" id="data-datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Stock Opname</th>
                                    <th>Tanggal Stock Opname</th>
                                    <th>Status</th>
                                    <th width="20%">Action</th>
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
        function savetransin(){
            var code = "<?php echo $code ?>";

            const datas = {
                    "date" : $("#date-in").val(),
                    "code" : code
                }

                console.log(datas);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>administrator/stock_opname/stockopname_add/",
                        data: datas,
                        success: function(data){
                            var req=JSON.parse(data);
                            if(req.info=="yes"){
                                swal.fire("Berhasil!", "data tersimpan, cek di data penjualan", "success");
                                setTimeout(
                                    location.reload()
                                    , 10000);
                            }
                        },
                        error:function(){
                            swal.fire("Error!", "data gagal tersimpan", "error");
                        }
                    });

        }

        $(document).ready(function(){
            $("#start-date").datepicker({
			    format: 'dd/mm/yyyy'
			});

			$('#end-date').datepicker({
			    format: 'dd/mm/yyyy'
			});

			$('#date-in').datepicker({
			    format: 'dd/mm/yyyy'
			});


            table=$('#data-datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "<?php echo base_url(); ?>administrator/stock_opname/ajx_data_stockopname/",
                    "type": "POST",
                    "data": function ( data ) {
		                data.start_date = $('#start-date').val();
		                data.end_date = $('#end-date').val();
		                data.status = $('#status').val();
                        data.list=1;
                    }
                },

                "columnDefs": [
                    {
                        "targets": [ 4, 0 ],
                        "orderable": false,
                        "class" : "text-center"
                    }
                ],
            });


            $("#search-date").click(function(){
                table.ajax.reload();
            })

            $('#data-datatable').on('click', '#trans', function () {
                var id=$(this).attr('idatr');
                window.location.href="<?php echo base_url() ?>stock-opname-form/"+id;
                // alert(id);
            });

            $('#data-datatable').on('click', '#details', function () {
                var id=$(this).attr('idatr');
                window.location.href="<?php echo base_url() ?>stock-opname-detail/"+id;
                // alert(id);
            });


            $('#data-datatable').on('click', '#delete', function () {
                var id=$(this).attr('idatr');
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
                        $.get("<?php echo base_url(); ?>administrator/stock_opname/cancel_stockopname/"+id, function(data){
                            var req=JSON.parse(data);
                            if(req.info=="yes"){
                                table.ajax.reload();
                                swal.fire("Berhasil!", "data telah terhapus", "success");
                            }else{
                                swal.fire("Error!", "data gagal terhapus", "error");
                            }
                        })
                    }
                });
            });

            $('#data-datatable').on('change', '.categorySelect', function(){
               let category_id = $(this).children("option:selected").val();
               let stokOpnameId = $(this).find(':selected').attr('data-stokOpnameId');

                window.open('<?php echo base_url() ?>stock-opname-category-detail/'+stokOpnameId+"/"+category_id, '_blank');
            })

        })
    </script>

<?php
$this->load->view('parts/end_body_part');

?>