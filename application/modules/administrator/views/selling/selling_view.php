<?php
$this->load->view('parts/header_part');
?>

<!-- custom link rel -->

<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedcolumns.bootstrap4.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>theme/dist/assets/vendor/jquery-datatable/fixedeader/dataTables.fixedheader.bootstrap4.min.css">
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
    <div class="col-lg-8 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <div class="row">
                    <div class="col-lg-6">
                        <h2>ITEM <?php echo $subpage ?></h2>
                    </div>
                    <div class="col-lg-6">
                        <form action="<?php echo base_url() ?>selling" method="get">
                            <div class="input-group">
                                <input type="text" name="cari" id="cari" class="form-control" placeholder="" aria-describedby="helpId">
                                <div class="input-group-append">
                                    <input class="btn btn-outline-primary" type="submit" value="Cari">
                                    <a href="<?php echo base_url() ?>selling" type="button" class="btn btn-primary"><i class="fa fa-refresh"></i></a>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <div class="body">
                <?php
                $count = 0;
                $tab_menu = '';
                $tab_content = '';

                foreach ($category as $cat) {
                    if ($count == 0) {
                        $tab_menu .= '<li class="nav-item"><a class="nav-link active show" href="#' . $cat['name'] . '" data-toggle="tab">' . $cat['name'] . '</a></li>';
                        $tab_content .= '<div class="tab-pane show active" id="' . $cat['name'] . '"><div class="row">';
                    } else {
                        $tab_menu .= '<li class="nav-item"><a class="nav-link" href="#' . $cat['name'] . '" data-toggle="tab">' . $cat['name'] . '</a></li>';
                        $tab_content .= '<div class="tab-pane" id="' . $cat['name'] . '"><div class="row">';
                    }

                    if (isset($_GET['cari'])) {
                        $cari = $_GET['cari'];
                        if (!empty($cari)) {
                            $sql = "SELECT `isell`.`id`, `isell`.`inc_id`, isell.item_id, isell.stock_item_sell, `i`.`code`, `i`.`name`, `u`.`inc_id` as `unit_id`, `u`.`name` as `unit_name`, `discount`, `price_sell`, `type_price` FROM `items_sell` `isell` LEFT JOIN `items` `i` ON `isell`.`item_id` = `i`.`inc_id` LEFT JOIN `unit` `u` ON `i`.`unit_id` = `u`.`inc_id` WHERE `i`.`category_id` = " . $cat['inc_id'] . " AND `isell`.`ket` != 'DELETE' AND `isell`.`type_price` = 1 AND (i.name like '%$cari%' or i.code like '%$cari%') ";
                        } else {
                            $sql = "SELECT `isell`.`id`, `isell`.`inc_id`, isell.item_id, isell.stock_item_sell, `i`.`code`, `i`.`name`, `u`.`inc_id` as `unit_id`, `u`.`name` as `unit_name`, `discount`, `price_sell`, `type_price` FROM `items_sell` `isell` LEFT JOIN `items` `i` ON `isell`.`item_id` = `i`.`inc_id` LEFT JOIN `unit` `u` ON `i`.`unit_id` = `u`.`inc_id` WHERE `i`.`category_id` = " . $cat['inc_id'] . " AND `isell`.`type_price` = 1 AND `isell`.`ket` != 'DELETE'";
                        }
                    } else {
                        $sql = "SELECT `isell`.`id`, `isell`.`inc_id`, isell.item_id, isell.stock_item_sell, `i`.`code`, `i`.`name`, `u`.`inc_id` as `unit_id`, `u`.`name` as `unit_name`, `discount`, `price_sell`, `type_price` FROM `items_sell` `isell` LEFT JOIN `items` `i` ON `isell`.`item_id` = `i`.`inc_id` LEFT JOIN `unit` `u` ON `i`.`unit_id` = `u`.`inc_id` WHERE `i`.`category_id` = " . $cat['inc_id'] . " AND `isell`.`type_price` = 1 AND `isell`.`ket` != 'DELETE'";
                    }

                    $query = $this->db->query($sql);
                    foreach ($query->result_array() as $i) {
                        $stck = $i['stock_item_sell'] >= 1 ? $i['stock_item_sell'] : '0';
                        $tab_content .= '
                                                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-6">
                                                    <a data-toggle="modal" data-target="#modal-' . $i['inc_id'] . '">
                                                        <div class="card">
                                                            <div class="body">
                                                                <div class="pricing-option">
                                                                    <span style="font-size: 0.9em; font-weight: bold">' . $i['code'] . '</span>
                                                                    <h6>' . $i['name'] . ' <b>(' .  $stck  . ')</b></h6> 
                                                                    <div class="price">
                                                                        <span class="price text-primary"><b>' . curr_format($i['price_sell']) . '</b> IDR</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="modal fade" id="modal-' . $i['inc_id'] . '">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Info ' . $i['name'] . '</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <label>Qty</label>
                                                                            <input class="form-control" readonly type="text" value="' . $i['stock_item_sell'] . '" />
                                                                            </div>
                                                                        <div class="col-lg-6">
                                                                        <label>Jumlah</label>
                                                                        <input type="number" class="form-control" value="1" name="qty-' . $i['inc_id'] . '" min="0" id="qty-' . $i['inc_id'] . '" autofocues idatr="' . $i['inc_id'] . '">
                                                                        <input type="hidden" class="form-control" value="' . $i['price_sell'] . '" name="price" id="price-' . $i['inc_id'] . '">
                                                                        <input type="hidden" class="form-control" value="' . $i['discount'] . '" name="discount" id="discount-' . $i['inc_id'] . '">
                                                                        <input type="hidden" name="items" class="form-control" value="' . $i['item_id'] . '" id="items-' . $i['inc_id'] . '">
                                                                        <input type="hidden" name="item_selling_id" class="form-control" value="' . $i['inc_id'] . '" id="item_selling_id-' . $i['inc_id'] . '"/>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary" id="btn-selling-item" onclick="save_selling(' . $i['inc_id'] . ', event)">Save changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            ';
                    }
                    $tab_content .= ' </div>
                                </div>';
                    $count++;
                }
                ?>

                <ul class="nav nav-tabs-new2">
                    <?php echo $tab_menu; ?>

                </ul>
                <div class="tab-content">
                    <?php echo $tab_content ?>
                </div>


            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card planned_task">
            <div class="header">
                <h2>NOTA : <?php echo $code ?></h2>
            </div>
            <div class="body" style="min-height: 200px;">
                <div class="table-responsive">
                    <div id="list-item"></div>
                </div>
            </div>
        </div>
    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="modal-debt" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hutang Karyawan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Nama Karyawan</label>
                    <input type="text" class="form-control" name="employee" id="employee" placeholder="pastikan isi nama lengkap">
                </div>
                <div class="form-group">
                    <label for="">Total Hutang</label>
                    <input type="text" class="form-control" name="total_hutang" id="total_hutang" readonly>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save-debt">Save</button>
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

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function save_selling(items_id, e) {
        const nota = "<?php echo $code ?>";

        const datas = {
            "items": $("#items-" + items_id).val(),
            "price": $("#price-" + items_id).val(),
            "discount": $("#discount-" + items_id).val(),
            "qty": $("#qty-" + items_id).val(),
            "item_selling_id": $("#item_selling_id-" + items_id).val(),
            "nota": nota
        }


        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>administrator/selling/detail_items_selling/" + nota,
            data: datas,
            success: function(data) {
                var req = JSON.parse(data);
                if (req.info == "yes") {
                    $("#modal-" + items_id).modal('hide')
                    setTimeout(list_items_selling(), 6000);
                }
            },
            error: function() {
                swal.fire("Error!", "data gagal tersimpan", "error");
            }
        });

    }

    function savepembayaran(e) {
        const nota = "<?php echo $code ?>";

        const datas = {
            "subtotal": $("#subtotal").val(),
            "discount": $("#discount").val(),
            "bayar": $("#bayar").val(),
            "jenis_pembayaran": $("#jenis_pembayaran").val(),
            "total_pembayaran": $("#total_pembayaran").val(),
            "nota": nota
        }

        console.log(datas);
        if (e.keyCode === 13) {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>administrator/selling/update_selling_items/" + nota,
                data: datas,
                success: function(data) {
                    var req = JSON.parse(data);
                    if (req.info == "yes") {
                        $("#modal-transaction").modal('hide')
                        setTimeout(
                            window.open("<?php echo base_url() ?>administrator/selling/print_bill/" + req.id, "_blank"), 4000);
                        setTimeout(location.reload(), 6000)
                    }
                },
                error: function() {
                    swal.fire("Error!", "data gagal tersimpan", "error");
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>administrator/selling/update_selling_items/" + nota,
                data: datas,
                success: function(data) {
                    var req = JSON.parse(data);
                    if (req.info == "yes") {
                        $("#modal-transaction").modal('hide')

                        setTimeout(
                            window.open("<?php echo base_url() ?>administrator/selling/print_bill/" + req.id, "_blank"), 4000);
                        setTimeout(location.reload(), 6000)
                    }
                },
                error: function() {
                    swal.fire("Error!", "data gagal tersimpan", "error");
                }
            });
        }
    }

    function savetransaction() {
        const nota = "<?php echo $code ?>";

        const datas = {
            "total_pembayaran": $("#total_pembayaran").val(),
            "nota": nota
        }

        swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Masukan ke daftar hutang karyawan",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, masukan data ini!',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Ya');
                $("#modal-debt").modal('show');
                $("#total_hutang").val(datas.total_pembayaran);
                $("#save-debt").click(function() {
                    const datas_debt = {
                        "total_hutang": $("#total_hutang").val(),
                        "employee": $("#employee").val(),
                        "nota": nota
                    }
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>administrator/debt/saving_debt/" + nota,
                        data: datas_debt,
                        success: function(data) {
                            var req = JSON.parse(data);
                            if (req.info == "yes") {
                                swal.fire("Berhasil!", "data tersimpan, cek di data hutang", "success");
                                setTimeout(
                                    location.href = "<?php echo base_url() ?>selling", 15000);
                            }
                        },
                        error: function() {
                            swal.fire("Error!", "data sudah terinput ke data hutang", "error");
                        }
                    });
                })

            } else {
                console.log('Tidak');
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>administrator/selling/saving_selling_items/" + nota,
                    data: datas,
                    success: function(data) {
                        var req = JSON.parse(data);
                        if (req.info == "yes") {
                            swal.fire("Berhasil!", "data tersimpan, cek di data penjualan", "success");
                            setTimeout(
                                location.href = "<?php echo base_url() ?>selling", 20000);
                        }
                    },
                    error: function() {
                        swal.fire("Error!", "data gagal tersimpan", "error");
                    }
                });
            }
        });

    }


    function kembalian() {
        var total = $("#total_pembayaran").val();
        var bayar = $("#bayar").val();

        console.log(bayar)
        var kembalian = bayar - total;
        document.getElementById("kembalian").innerHTML = kembalian;
    }



    function list_items_selling() {
        const nota = "<?php echo $code ?>";

        $.get("<?php echo base_url() ?>administrator/selling/list_item_selling/" + nota, function(result) {
            $("#list-item").html(result)
        })
    }

    $(document).ready(function() {

        list_items_selling();

        $("#edit-form").submit(function() {
            event.preventDefault();
            var data_val = $(this).serializeArray();
            console.log(data_val);
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>administrator/stock/process_update/",
                data: data_val,
                success: function(data) {
                    var req = JSON.parse(data);
                    if (req.info == "yes") {
                        swal.fire("Berhasil!", "data telah tersimpan", "success");
                        $(".clear").val('');
                        $("#stock-modal").modal('hide')
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
    })
</script>

<?php
$this->load->view('parts/end_body_part');

?>