<?php 
    $this->load->view('parts/header_part');
    // custom link rel
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
                    <h2>FORM <?php echo $subpage?></h2>
                </div>
                <div class="body">
                    <form id="basic-form" method="post" novalidate>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label>Kode Barang</label>
                                    <input type="text" class="form-control" readonly name="code" id="code" value="<?php echo $code ?>">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label>Barcode</label>
                                    <input type="text" class="form-control" name="barcode" id="barcode">
                                    <input type="hidden" class="form-control" name="id" id="id" value="0">
                                    <input type="hidden" class="form-control" name="type" id="type" value="input">
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label>Nama Barang</label>
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Kategori Barang</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">-</option>
                                        <?php foreach ($category as $c) {
                                            echo '<option value="'.$c['inc_id'].'">'.$c['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Jenis Barang</label>
                                    <select name="type_id" id="type_id" class="form-control">
                                        <option value="">-</option>
                                        <?php foreach ($type as $t) {
                                            echo '<option value="'.$t['inc_id'].'">'.$t['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Satuan Barang</label>
                                    <select name="unit_id" id="unit_id" class="form-control">
                                        <option value="">-</option>
                                        <?php foreach ($unit as $u) {
                                            echo '<option value="'.$u['inc_id'].'">'.$u['name'].'</option>';
                                        }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label>Min Stok</label>
                                    <input type="text" class="form-control"  name="min_stok" id="min_stok">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>Harga Beli</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control"  name="price" id="price" onkeyup="func_price_ppn()">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>PPn (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control"   name="ppn" id="ppn" value="11"  onkeyup="func_price_ppn()">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <label>Harga Beli + PPn</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control" readonly  name="price_ppn" id="price_ppn">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="form-group">
                                    <label>Promo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" class="form-control"  name="promo" id="promo">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-lg-12 col-md-12">
                            <p class="demo-button">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> <span>Simpan</span></button>
                                <button type="reset" class="btn btn-default"><i class="fa fa-refresh"></i> <span>Refresh</span></button>
                            </p>
                            </div>
                        </div>
                    </form>
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
    
    <script src="<?php base_url()?>theme/dist/assets/vendor/jquery-validation/jquery.validate.js"></script> <!-- Jquery Validation Plugin Css -->
    <script src="<?php base_url()?>theme/dist/assets/vendor/jquery-steps/jquery.steps.js"></script> <!-- JQuery Steps Plugin Js -->
    <script>
        function func_price_ppn(){
            let price = parseInt($("#price").val())
            let ppn = parseInt($("#ppn").val())
            let price_ppn = price + (price * (ppn/100));
            
            $("#price_ppn").val(price_ppn);
        }

        function set_default_field(){
            $("#id").val(0)
            $("#type").val('input')
        }

        $(document).ready(function(){
            $("#basic-form").submit(function(){
                event.preventDefault();
                var data_val=$(this).serializeArray();
                console.log(data_val);
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>administrator/product/product_process",
                    data: data_val,
                    success: function(data){
                        var req=JSON.parse(data);
                        if(req.info=="yes"){		
                            swal.fire("Berhasil!", "data telah tersimpan", "success");
                            $(".clear").val('');
                            set_default_field()
                            setTimeout(
                                location.reload(),
                                1000
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