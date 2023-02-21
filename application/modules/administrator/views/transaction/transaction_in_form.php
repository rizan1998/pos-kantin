<?php 
    $this->load->view('parts/header_part');
?>

    <!-- custom link rel -->

    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
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
                    <h2>TRANSAKSI  <?php echo $subpage?></h2>
                </div>
                <div class="body">
                    <table class="table" width="50%">
                        <thead>
                            <tr>
                                <td>Kode Barang Masuk</td>
                                <td>: <?php echo $transaction['code_in'] ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Barang Masuk</td>
                                <td>: <?php echo date_format_display($transaction['date_in']) ?></td>
                            </tr>
                            <tr>
                                <td>Status Barang Masuk</td>
                                <td>: 
                                    <?php 
                                        if($transaction['status'] == 1 ){
                                            $status =  '<span class="badge badge-warning">Process</span>';
                                        }elseif($transaction['status'] == 2) {
                                            $status =  '<span class="badge badge-success">Finish</span>';
                                        }else {
                                            $status =  '<span class="badge badge-danger">Cancel</span>';
                                        }

                                        echo $status;
                                    ?>
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <a href="<?php echo base_url()?>transaction-in" class="btn btn-default">Kembali</a>
                    
                    <button type="button" class="btn btn-primary" onclick="finish_transaction()">Transaksi Selesai</button>
                    
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>TAMBAH ITEM <?php echo $subpage?></h2>
                </div>
                <div class="body">
                    <!-- <form id="basic-form" method="post" novalidate> -->
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <select class="form-control product-ajax" name="item_id" id="item_id"></select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="form-group">
                                    <label>Harga Beli</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" name="price" id="price" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-12">
                                <div class="form-group">
                                    <label>QTY</label>
                                    <input type="number" class="form-control" name="qty" id="qty" min=1 value="1">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="form-group">
                                    <label>DISCOUNT</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" name="discount" id="discount" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-12">
                                <label for="">&nbsp;</label>
                                <button type="button" name="search" id="btn-save" class="btn btn-primary btn-block"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                     <!-- </form> -->
                     <hr>
                     <h6>TAMBAH ITEM <?php echo $subpage?></h6>
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
    <script src="<?php echo base_url()?>theme/dist/assets/bundles/datatablescripts.bundle.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
    <script src="<?php echo base_url()?>theme/dist/assets/js/pages/tables/jquery-datatable.js"></script>

    <script src="<?php echo base_url()?>theme/dist/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function load_item(){
            var id = "<?php echo $transaction['id'] ?>";
            $.get("<?php echo base_url()?>administrator/transaction/detail_in_item/"+id, function(data){
                $("#list-items").html(data)
            })
        }

        function finish_transaction(){
            var id = "<?php echo $transaction['id'] ?>";
            swal.fire({
                    title: 'Are you sure?',
                    text: "Kamu akan menyelesaikan transaksi ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, selesaikan ini!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.get("<?php echo base_url(); ?>administrator/transaction/update_transaction/"+id, function(data){
                            var req=JSON.parse(data);
                            if(req.info=="yes"){
                                // table.ajax.reload(); 
                                swal.fire("Berhasil!", "transaksi sudah selesai", "success");
                                setTimeout(location.href="<?php echo base_url()?>transaction-in", 15000);
                            }else{
                                swal.fire("Error!", "data gagal diselesaikan", "error");
                            }  		 
                        })
                    } 
                });
        }

        $(document).ready(function(){
            load_item()
            $('#start-date').datepicker({
			    format: 'dd/mm/yyyy'
			});
            
            $('.product-ajax').select2({
                minimumInputLength: 2,
                multiple: false,
                placeholder: 'Cari Data',
                ajax: {
                    url: "<?php echo base_url(); ?>administrator/selling/get_product/",
                    dataType: 'json',
                    contentType: "application/json",
                    delay: 250,
                    data: function (params) {
                        return {
                            search:params.term
                        }
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };

                    }
                }
            });

            $(".product-ajax").change(function(){
                let pro = $("#name").val()
                // console.log(pro)
            })

            $("#btn-save").click(function(){
                var item_id = $("#item_id").val();
                var id = "<?php echo $transaction['id'] ?>";
                const datas = {
                    "item_id" : $("#item_id").val(),
                    "trans_in" : "<?php echo $transaction['id']?>",
                    "price": $("#price").val(),
                    "discount": $("#discount").val(),
                    "qty": $("#qty").val(),
                }

                console.log(datas);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>administrator/transaction/transaction_in_add_item/"+id,
                        data: datas,
                        success: function(data){
                            var req=JSON.parse(data);
                            if(req.info=="yes"){		
                                swal.fire("Berhasil!", "data tersimpan, cek di data penjualan", "success");
                                setTimeout(
                                    load_item()
                                    , 3000);
                            }
                        },
                        error:function(){
                            swal.fire("Error!", "data gagal tersimpan", "error");
                        }
                    });	

            })

            $("#edit-form").submit(function(){
                event.preventDefault();
                var data_val=$(this).serializeArray();
                console.log(data_val);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>administrator/stock/process_update/",
                    data: data_val,
                    success: function(data){
                        var req=JSON.parse(data);
                        if(req.info=="yes"){		
                            swal.fire("Berhasil!", "data telah tersimpan", "success");
                            $(".clear").val('');
                            $("#stock-modal").modal('hide')
                            setTimeout(
                                location.reload(), 5000
                            )
                        }
                    },
                    error:function(){
                        swal.fire("Error!", "data gagal tersimpan", "error");
                    }
                });			
            })
        })
    </script>

<?php 
    $this->load->view('parts/end_body_part');
    
?>