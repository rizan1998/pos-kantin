<?php 
    $this->load->view('parts/header_part'); ?>
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

        <div class="col-lg-5 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>FORM <?php echo $subpage?></h2>
                </div>
                <div class="body">
                    <form id="basic-form" method="post" novalidate>
                        <div class="form-group">
                            <label>Kategori Barang</label>
                            <input type="text" class="form-control clear" required name="name" id="name">
                            <input type="hidden" class="form-control" name="id" id="id" value="0">
                            <input type="hidden" class="form-control" name="type" id="type" value="input">
                        </div>
                        <input type="submit" class="btn btn-primary" id="submit" value="Simpan">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-md-12">
            <div class="card planned_task">
                <div class="header">
                    <h2>DATA <?php echo $subpage?></h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="data-datatable"  width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
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

         
        function set_default_field(){
            $("#id").val(0)
            $("#type").val('input')
        }

        $(document).ready(function(){
            table=$('#data-datatable').DataTable({
                "processing": true, 
                "serverSide": true, 
                "order": [], 
                "ajax": {
                    "url": "<?php echo base_url(); ?>administrator/product/ajx_data_product_category/",
                    "type": "POST"
                },
            
                "columnDefs": [
                    { 
                        "targets": [ 2, 0 ], 
                        "orderable": false,
                        "class" : "text-center"
                    }
                ],   	
            });

            $("#basic-form").submit(function(){
                event.preventDefault();
                var data_val=$(this).serializeArray();
                console.log(data_val);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>administrator/product/category_process",
                    data: data_val,
                    success: function(data){
                        var req=JSON.parse(data);
                        if(req.info=="yes"){		
                            swal.fire("Berhasil!", "data telah tersimpan", "success");
                            table.ajax.reload();  
                            $(".clear").val('');
                            set_default_field()
                        }
                    },
                    error:function(){
                        swal.fire("Error!", "data gagal tersimpan", "error");
                    }
                });			
            })

            $('#data-datatable').on('click', '#edit', function () {
                var id=$(this).attr('idatr');
                $.get("<?php echo base_url(); ?>administrator/product/get_where_category/"+id, function(data){
                    var items=JSON.parse(data);
                    $("#name").val(items.name);
                    $("#type").val("update");
                    $("#id").val(items.id);

                    

                    $("#delete").hide();
                    $("#edit").hide();

                    $("#submit").val('Perbaharui');
                });  	
            });


            $('#data-datatable').on('click', '#delete', function () {
                var id=$(this).attr('idatr');
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.get("<?php echo base_url(); ?>administrator/product/category_delete/"+id, function(data){
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
        })
    </script>
<?php 
    $this->load->view('parts/end_body_part');
    
?>