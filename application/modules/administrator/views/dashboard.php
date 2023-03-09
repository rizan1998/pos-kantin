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
            <h2>eCommerce</h2>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-dashboard"></i></a></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item active">eCommerce</li>
            </ul>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="d-flex flex-row-reverse">
                <div class="page_action">
                    <button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Download report</button>
                    <button type="button" class="btn btn-secondary"><i class="fa fa-send"></i> Send report</button>
                </div>
                <div class="p-2 d-flex">

                </div>
            </div>
        </div>
    </div>
</div>


<div class="row clearfix row-deck">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="card top_widget primary-bg">
            <div class="body">
                <div class="icon bg-light"><i class="fa fa-shopping-basket"></i> </div>
                <div class="content text-light">
                    <div class="text mb-2 text-uppercase">Penjualan hari ini</div>
                    <h4 class="number mb-0">
                        <?php
                        $totalPenjualanPerHari = 0;
                        foreach ($penjualan_hari_ini as $phi) {
                            $countPenjualan = $phi['qty'] * $phi['price'];
                            $totalPenjualanPerHari += $countPenjualan;
                        }
                        echo "Rp. " . curr_format($totalPenjualanPerHari);
                        ?>
                    </h4>
                    <small>Analytics for this day</small>
                </div>
                <!-- <div class="sparkline text-left mt-3" data-type="bar" data-offset="100" data-width="100%" data-height="40px" data-bar-Width="4" data-bar-Color="#ffffff">2,9,8,7,4,4,6,7,3,4,9,1,5,1,7,8,4,2,1,4,1,2,4,6,7,8,3,2,1,2,5</div> -->
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="card top_widget secondary-bg">
            <div class="body">
                <div class="icon bg-light"><i class="fa fa-dollar"></i> </div>
                <div class="content text-light">
                    <div class="text mb-2 text-uppercase">Penjualan bulan ini</div>
                    <h4 class="number mb-0">
                        <?php
                        $totalPenjualanPerBulan = 0;
                        foreach ($penjualan_bulan_ini as $phi) {
                            $countPenjualanBulanini = $phi['qty'] * $phi['price'];
                            $totalPenjualanPerBulan += $countPenjualanBulanini;
                        }
                        echo "Rp. " . curr_format($totalPenjualanPerBulan);
                        ?>
                    </h4>
                    <small>Analytics this month</small>
                </div>
                <!-- <div class="sparkline text-left mt-3" data-type="bar" data-offset="100" data-width="100%" data-height="40px" data-bar-Width="4" data-bar-Color="#ffffff">2,7,8,3,2,1,2,5,6,7,3,4,9,1,5,9,8,7,4,4,4,1,2,4,6,1,7,8,4,2,1</div> -->
            </div>
        </div>
    </div>
</div>

<div class="row clearfix row-deck">
    <div class="col-lg-12 col-md-6 col-sm-6">
        <div class="card">
            <div class="header">
                <h2>Penjualan per category <small>Chart penjualan per category bulan ini</small></h2>
                <ul class="header-dropdown">
                    <li><a href="#" title=""><i class="fa fa-envelope"></i></a></li>
                    <li><a href="#" title=""><i class="fa fa-download"></i></a></li>
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li><a href="javascript:void(0);">Action</a></li>
                            <li><a href="javascript:void(0);">Another Action</a></li>
                            <li><a href="javascript:void(0);">Something else</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="body">
                <div class="d-flex justify-content-between">
                </div>
                <div id="Annual-Report" class="annual_report" style="height: auto"></div>
            </div>
        </div>
    </div>
</div>

<div class="row clearfix row-deck">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <h2>Top selling item list</h2>
            </div>
            <div class="body">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Items</th>
                            <th>Penjualan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($top_selling as $tp) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $tp['name']; ?></td>
                                <td><?= $tp['jumlah_penjualan']; ?></td>
                                <td>
                                    <?php
                                    $totalHarga = $tp['jumlah_penjualan'] * $tp['price'];
                                    $totalFormt = curr_format($totalHarga);
                                    echo "Rp. " . $totalFormt;
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- END MAIN PART -->
<?php $this->load->view('parts/end_main_part'); ?>
<!-- CUSTOM JAVASCRIPT -->
<!-- Javascript -->
<script src="<?php echo base_url() ?>theme/dist/assets/bundles/libscripts.bundle.js"></script>
<script src="<?php echo base_url() ?>theme/dist/assets/bundles/vendorscripts.bundle.js"></script>

<script src="<?php echo base_url() ?>theme/dist/assets/bundles/jvectormap.bundle.js"></script> <!-- JVectorMap Plugin Js -->
<script src="<?php echo base_url() ?>theme/dist/assets/bundles/c3.bundle.js"></script>


<!-- page js file -->
<script src="<?php echo base_url() ?>theme/dist/assets/bundles/mainscripts.bundle.js"></script>

<script src="<?php echo base_url() ?>theme/dist/assets/js/index8.js"></script>

<!-- chart -->

<script>
    $(document).ready(function() {

        var chart = c3.generate({
            bindto: '#Annual-Report',
            data: {
                columns: [
                    ['pendapatan',
                        <?php
                        foreach ($penjualan_per_category as $ppc) {
                            $currFrmt = curr_format($ppc['jumlah_penjulan']);
                            echo "'" . $currFrmt     . "'" . ",";
                        }
                        ?>
                    ]
                ],
                type: "bar"
            },
            axis: {
                x: {
                    type: 'category',
                    categories: [
                        <?php
                        foreach ($penjualan_per_category as $ppc) {
                            echo "'" . $ppc['name'] . "'" . ",";
                        }
                        ?>
                    ]
                }
            }
        });

    });
</script>

<?php
// $this->load->view('parts/script_part');
$this->load->view('parts/end_body_part');

?>