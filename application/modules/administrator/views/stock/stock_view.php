<?php 
    $this->load->view('parts/header_part');
?>

    <!-- custom link rel -->

    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
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
                    <div class="row">

                        <div class="col-lg-6 hidden-sm text-left">
                            <h2>DATA <?php echo $subpage?></h2>
                        </div>
                        <?php 
                            $roles = $this->session->userdata('roles'); 

                            if($roles != 3 ):
                        ?>
                        <div class="col-lg-6 hidden-sm text-right">
                            <button type="button" class="btn btn-outline-info" id="btn-print"><i class="fa fa-print"></i> Cetak Stok</button>
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
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Satuan  </th>
                                    <th>Stock</th>
                                    <th></th>
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

    <div class="modal fade" id="stock-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="defaultModalLabel">Masukan Stok </h4>
                </div>
                <form id="edit-form" method="post" novalidate>
                <div class="modal-body"> 
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="id" id="id" value="">
                    </div>
                    
                    <div class="form-group">
                        <label>Stock <span id="item-name"></span></label>
                        <input type="text" class="form-control clear" name="stock" id="stock" value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">SAVE CHANGES</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                </div>
                </form>
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
    <script>
        $(document).ready(function(){
            table=$('#data-datatable').DataTable({
                "processing": true, 
                "serverSide": true, 
                "order": [], 
                "ajax": {
                    "url": "<?php echo base_url(); ?>administrator/stock/ajx_data_stock/",
                    "type": "POST"
                },
            
                "columnDefs": [
                    { 
                        "targets": [ 3, 4, 6, 0 ], 
                        "orderable": false,
                        "class" : "text-center"
                    },
                    { 
                        "targets": [ 5], 
                        "class" : "text-center"
                    }
                ],   	
            });

            $('#data-datatable').on('click', '#stockies', function () {
                var id=$(this).attr('idatr');
                $("#stock-modal").modal('show')
                $.get("<?php echo base_url(); ?>administrator/stock/get_product/"+id, function(data){
                    var req=JSON.parse(data);
                    console.log(req.id);
                    $("#id").val(req.id)
                    $("#item-name").html(req.name)
                })
            });

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


            $("#btn-print").click(function(){
                window.open("<?php echo base_url(); ?>administrator/stock/print_stock/", "_blank")
            })


        })
    </script>

<?php 
    $this->load->view('parts/end_body_part');
    
?>