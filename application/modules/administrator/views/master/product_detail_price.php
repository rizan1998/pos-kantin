<?php
$this->load->view('parts/header_part'); ?>
<!-- custom link rel -->

<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
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
                <h2>DETAIL <?php echo strtoupper($items['name']) ?> </h2>
            </div>
            <div class="body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Kode Barang</td>
                            <td width="1%">:</td>
                            <td><?php echo $items['code'] ?></td>

                            <td>Kategori </td>
                            <td width="1%">:</td>
                            <td><?php echo $items['category_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td width="1%">:</td>
                            <td><?php echo $items['name'] ?></td>

                            <td>Satuan</td>
                            <td width="1%">:</td>
                            <td><?php echo $items['unit_name'] ?></td>
                        </tr>
                        <tr>
                            <td>Harga Beli</td>
                            <td width="1%">:</td>
                            <td>Rp. <?php echo curr_format($items['price']) ?></td>

                            <td>Harga Beli + Ppn</td>
                            <td width="1%">:</td>
                            <td>Rp. <?php echo curr_format($items['price_ppn']) ?></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary" id="btn-back">Kembali</button>

            </div>
        </div>
    </div>

    <div class="col-lg-5 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <h2>FORM <?php echo $subpage ?></h2>
            </div>
            <div class="body">
                <form id="basic-form" method="post" novalidate>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="id" id="id" value="0">
                        <input type="hidden" class="form-control" name="type" id="type" value="input">
                        <input type="hidden" name="id_detail_trans" id="id_detail_trans">
                    </div>
                    <!-- <div class="form-group">
                        <label for="id_barang_masuk">Barang Masuk</label>
                        <input type="text" readonly id="id_barang_masuk" class="form-control" placeholder="Barang masuk" data-toggle="modal" data-target="#table_barang_masuk">
                    </div>
                    <div class="form-group">
                        <label for="harga_beli">Harga beli</label>
                        <input type="text" readonly id="harga_beli" class="form-control" placeholder="Harga beli">
                    </div> -->

                    <div class="form-group">
                        <label>Jenis Harga</label>
                        <select name="type_price" id="type_price" class="form-control">
                            <option value="1">Harga Retail</option>
                            <option value="2">Harga Distributor</option>
                            <option value="3">Harga Online</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Satuan Barang</label>
                        <select name="unit_id" id="unit_id" class="form-control">
                            <option value="">-</option>
                            <?php foreach ($unit as $u) {
                                echo '<option value="' . $u['inc_id'] . '">' . $u['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control clear" name="price" id="price" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Diskon</label>
                        <div class="input-group">
                            <input type="text" class="form-control clear" name="discount" id="discount" value="0">
                            <div class="input-group-prepend">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" id="submit" value="Simpan">
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <h2>DETAIL <?php echo $subpage ?></h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered  table-hover" id="data-datatable" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Satuan</th>
                                <th>Jenis Harga</th>
                                <th>Stok</th>
                                <th>Harga Jual</th>
                                <th>Diskon</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($items_sell as $is) :
                                if ($is['type_price'] == 1)
                                    $type_price = "Harga Retail";
                                elseif ($is['type_price'] == 2)
                                    $type_price = "Harga Distributor";
                                else
                                    $type_price = "Harga Online";
                            ?>
                                <tr>

                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $is['unit_name'] ?></td>
                                    <td><?php echo $type_price ?></td>
                                    <td><?php echo $is['stock_item_sell'] > 1 ? $is['stock_item_sell'] : '0'; ?></td>
                                    <td><?php echo curr_format($is['price_sell']) ?></td>
                                    <td><?php echo $is['discount'] ?>%</td>
                                    <td class="text">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="#price-<?php echo $is['id'] ?>" data-toggle="modal" data-target="#price-<?php echo $is['id'] ?>" type="button" class="btn btn-secondary"><i class="fa fa-edit"></i></a>


                                            <div class="modal fade" id="price-<?php echo $is['id'] ?>" tabindex="-1" role="dialog">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="title" id="defaultModalLabel">Edit Harga</h4>
                                                        </div>
                                                        <form id="edit-form" method="post" novalidate>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $is['id'] ?>">
                                                                    <input type="hidden" class="form-control" name="type" id="type" value="update">
                                                                </div>
                                                                <!-- <div class="form-group">
                                                                    <label>Harga barang masuk</label>
                                                                    <div class="input-group">

                                                                        <span class="input-group-text">Rp</span>
                                                                        <div class="input-group-prepend">
                                                                        </div>
                                                                        <input type="text" class="form-control clear" readonly name="price" id="price" value="">
                                                                    </div>
                                                                </div> -->
                                                                <div class="form-group">
                                                                    <label>Jenis Harga</label>
                                                                    <select name="type_price" id="type_price" class="form-control">
                                                                        <option value="1" <?php if ($type_price == 1) echo 'selected';
                                                                                            else echo ''; ?>>Harga Retail</option>
                                                                        <option value="2" <?php if ($type_price == 2) echo 'selected';
                                                                                            else echo ''; ?>>Harga Distributor</option>
                                                                        <option value="3" <?php if ($type_price == 2) echo 'selected';
                                                                                            else echo ''; ?>>Harga Online</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Satuan Barang</label>
                                                                    <select name="unit_id" id="unit_id" class="form-control">
                                                                        <option value="">-</option>
                                                                        <?php foreach ($unit as $u) : ?>
                                                                            <option value="<?php echo $u['inc_id'] ?>" <?php if ($is['unit_id'] == $u['inc_id']) echo 'selected';
                                                                                                                        echo '' ?>><?php echo $u['name'] ?></option>;
                                                                        <?php endforeach ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Harga Jual</label>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">Rp</span>
                                                                        </div>
                                                                        <input type="text" class="form-control clear" name="price" id="price" value="<?php echo $is['price_sell'] ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Diskon</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control clear" name="discount" id="discount" value="<?php echo $is['discount'] ?>">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">%</span>
                                                                        </div>
                                                                    </div>
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

                                            <a href="javascript:void(0)" type="button" class="btn btn-danger" onclick="deletePrice('<?php echo $is['id'] ?>')"><i class="fa fa-trash"></i></a>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="table_barang_masuk" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1080px">
        <div class="modal-content">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card planned_task">
                        <div class="header">
                            <h2>DATA <?php echo $subpage ?></h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered  table-hover" id="data-datatable-transaction-in" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Tanggal</th>
                                            <th>harga beli</th>
                                            <th style="text-align: center;"></th>
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
        </div>
    </div>
</div>

<!-- END MAIN PART -->
<?php
$this->load->view('parts/end_main_part');
$this->load->view('parts/script_part');
?>
<!-- CUSTOM CSS -->
<style>
    @media (max-width: 576px) {
        #table_barang_masuk .modal-dialog {
            max-width: 100%;
            margin: 0;
        }
    }
</style>
<!-- CUSTOM JAVASCRIPT -->
<script src="<?php echo base_url() ?>theme/dist/assets/bundles/datatablescripts.bundle.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.colVis.min.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.html5.min.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/buttons/buttons.print.min.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/js/pages/tables/jquery-datatable.js"></script>

<script>
    function set_default_field() {
        $("#id").val(0)
        $("#type").val('input')
    }

    function deletePrice(id) {
        console.log(id);
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
                $.get("<?php echo base_url(); ?>administrator/product/product_detail_delete/" + id, function(data) {
                    var req = JSON.parse(data);
                    if (req.info == "yes") {
                        swal.fire("Berhasil!", "data telah terhapus", "success");
                        setTimeout(
                            location.reload(), 5000
                        )
                    } else {
                        swal.fire("Error!", "data gagal terhapus", "error");
                    }
                })
            }
        });
    }

    $(document).ready(function() {
        $("#basic-form").submit(function() {
            event.preventDefault();
            var data_val = $(this).serializeArray();
            var idProduct = "<?php echo $items['id'] ?>";
            console.log(data_val);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>administrator/product/detail_price_process/" + idProduct,
                data: data_val,
                success: function(data) {
                    var req = JSON.parse(data);
                    if (req.info == "yes") {
                        swal.fire("Berhasil!", "data telah tersimpan", "success");
                        $(".clear").val('');
                        set_default_field()
                        setTimeout(
                            location.reload(), 5000
                        )
                    }
                },
                error: function() {
                    swal.fire("Error!", "data gagal tersimpan", "error");
                }
            });
        })

        $("#edit-form").submit(function() {
            event.preventDefault();
            var data_val = $(this).serializeArray();
            var idProduct = "<?php echo $items['id'] ?>";
            console.log(data_val);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>administrator/product/detail_price_process/" + idProduct,
                data: data_val,
                success: function(data) {
                    var req = JSON.parse(data);
                    if (req.info == "yes") {
                        swal.fire("Berhasil!", "data telah tersimpan", "success");
                        $(".clear").val('');
                        set_default_field()
                        setTimeout(
                            location.reload(), 5000
                        )
                    }
                },
                error: function() {
                    swal.fire("Error!", "data gagal tersimpan", "error");
                }
            });
        })

        $("#btn-back").click(function() {
            window.location.href = "<?php echo base_url() ?>master-product";
        })


        table = $('#data-datatable-transaction-in').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo base_url(); ?>administrator/transaction/ajx_data_detail_transaction_in/<?php echo $items['code'] ?>",
                "type": "POST"
            },

            "columnDefs": [{
                // "targets": [5, 0],
                "orderable": false,
                "class": "text-center"
            }],
        });

        // $("#data-datatable-transaction-in").on('click', '#add_transaction_detail', () => {
        //     alert('testing');
        // })

        $('#data-datatable-transaction-in').on('click', '#add_transaction_detail', function() {
            // alert("testing")
            let name = $(this).data('name') + ' (' + $(this).data("date") + ')';
            let price = $(this).data('price');
            let id = $(this).data('id_detail_trans');

            $("#id_barang_masuk").val(name);
            $("#harga_beli").val(price);
            $("#id_detail_trans").val(id);
            $("#table_barang_masuk").modal('hide');

        });


    });
</script>
<?php
$this->load->view('parts/end_body_part');

?>