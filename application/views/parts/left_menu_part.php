 <!-- main left menu -->
 <div id="left-sidebar" class="sidebar">
     <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-arrow-left"></i></button>
     <div class="sidebar-scroll">
         <div class="user-account">
             <img src="<?php echo base_url() ?>theme/dist/assets/images/user.png" class="rounded-circle user-photo" alt="User Profile Picture">
             <div class="dropdown">
                 <span>Welcome,</span>
                 <a href="javascript:void(0);" class="user-name"><strong><?php echo $this->session->userdata('fullname') ?></strong></a>
             </div>
             <hr>
         </div>

         <!-- Tab panes -->
         <div class="tab-content padding-0">
             <div class="tab-pane active" id="menu">
                 <nav id="left-sidebar-nav" class="sidebar-nav">
                     <?php
                        $roles = $this->session->userdata('roles');

                        if ($roles == 1) :
                        ?>

                         <ul id="main-menu" class="metismenu li_animation_delay">
                             <li <?php if ($active == "dashboard") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>dashboard" class="has-arrow"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
                             </li>
                             <li <?php if ($active == "selling") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>selling" class="has-arrow"><i class="fa fa-shopping-cart"></i><span>Penjualan</span></a>
                             </li>

                             <li <?php if ($active == "selling-list") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>selling-list" class="has-arrow"><i class="fa fa-columns"></i><span>Data Penjualan</span></a>
                             </li>
                             <li <?php if ($active == "master") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#App" class="has-arrow"><i class="fa fa-th-large"></i><span>Master Data</span></a>
                                 <ul>
                                     <li <?php if ($active == "master") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="javascript:void(0);"><span>Master Barang</span></a>
                                         <ul>
                                             <li <?php if (isset($subactive) && $subactive == "product") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product">Barang</a></li>
                                             <li <?php if (isset($subactive) && $subactive == "product_type") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product-type">Jenis Barang</a></li>
                                             <li <?php if (isset($subactive) && $subactive == "product_category") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product-category">Kategori Barang</a></li>
                                             <li <?php if (isset($subactive) && $subactive == "product_unit") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product-unit">Satuan Barang</a></li>
                                         </ul>
                                     </li>
                                     <li <?php if (isset($subactive) && $subactive == "user") {
                                                echo 'class="active"';
                                            }
                                            ?>>
                                         <a href="<?php echo base_url() ?>master-users">Pengguna</a>
                                     </li>

                                     <li <?php if (isset($subactive) && $subactive == "type_paid") {
                                                echo 'class="active"';
                                            }
                                            ?>>
                                         <a href="<?php echo base_url() ?>master-typepaid">Jenis Pembayaran</a>
                                     </li>
                                 </ul>
                             </li>

                             <li <?php if ($active == "transaction") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#Widgets" class="has-arrow"><i class="fa fa-exchange"></i><span>Transaksi</span></a>
                                 <ul>
                                     <li <?php if (isset($subactive) && $subactive == "in") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="<?php echo base_url() ?>transaction-in">Barang Masuk</a></li>
                                 </ul>
                             </li>
                             <li <?php if ($active == "stock") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#Widgets" class="has-arrow"><i class="fa fa-circle"></i><span>Stok</span></a>
                                 <ul>
                                     <li <?php if (isset($subactive) && $subactive == "stok") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="<?php echo base_url() ?>stock-items">Stok</a></li>
                                     <li <?php if (isset($subactive) && $subactive == "stokopname") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="<?php echo base_url() ?>stock-opname">Stok Opname</a></li>
                                 </ul>
                             </li>
                             <li <?php if ($active == "debt") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>debt" class="has-arrow"><i class="fa fa-columns"></i><span>Data Utang</span></a>
                             </li>
                         </ul>

                     <?php elseif ($roles == 2) : ?>

                         <ul id="main-menu" class="metismenu li_animation_delay">
                             <li <?php if ($active == "dashboard") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>dashboard" class="has-arrow"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
                             </li>
                             <li <?php if ($active == "selling") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>selling" class="has-arrow"><i class="fa fa-shopping-cart"></i><span>Penjualan</span></a>
                             </li>

                             <li <?php if ($active == "selling-list") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>selling-list" class="has-arrow"><i class="fa fa-columns"></i><span>Data Penjualan</span></a>
                             </li>
                             <li <?php if ($active == "debt") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>debt" class="has-arrow"><i class="fa fa-columns"></i><span>Data Utang</span></a>
                             </li>
                             <li <?php if ($active == "master") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#App" class="has-arrow"><i class="fa fa-th-large"></i><span>Master Data</span></a>
                                 <ul>
                                     <li <?php if ($active == "master") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="javascript:void(0);"><span>Master Barang</span></a>
                                         <ul>
                                             <li <?php if (isset($subactive) && $subactive == "product") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product">Barang</a></li>
                                             <li <?php if (isset($subactive) && $subactive == "product_type") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product-type">Jenis Barang</a></li>
                                             <li <?php if (isset($subactive) && $subactive == "product_category") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product-category">Kategori Barang</a></li>
                                             <li <?php if (isset($subactive) && $subactive == "product_unit") {
                                                        echo 'class="active"';
                                                    }
                                                    ?>><a href="<?php echo base_url() ?>master-product-unit">Satuan Barang</a></li>
                                         </ul>
                                     </li>
                                 </ul>
                             </li>

                             <li <?php if ($active == "stock") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#Widgets" class="has-arrow"><i class="fa fa-circle"></i><span>Stok</span></a>
                                 <ul>
                                     <li <?php if (isset($subactive) && $subactive == "stokopname") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="<?php echo base_url() ?>stock-opname">Stok Opname</a></li>
                                 </ul>
                             </li>

                             <li <?php if ($active == "transaction") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#Widgets" class="has-arrow"><i class="fa fa-exchange"></i><span>Transaksi</span></a>
                                 <ul>
                                     <li <?php if (isset($subactive) && $subactive == "in") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="<?php echo base_url() ?>transaction-in">Barang Masuk</a></li>
                                 </ul>
                             </li>
                             <li <?php if ($active == "transaction") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#Widgets" class="has-arrow"><i class="fa fa-exchange"></i><span>Transaksi</span></a>
                             </li>
                         </ul>

                     <?php else : ?>

                         <ul id="main-menu" class="metismenu li_animation_delay">
                             <li <?php if ($active == "dashboard") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>dashboard" class="has-arrow"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>
                             </li>
                             <li <?php if ($active == "selling") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>selling" class="has-arrow"><i class="fa fa-shopping-cart"></i><span>Penjualan</span></a>
                             </li>

                             <li <?php if ($active == "selling-list") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>selling-list" class="has-arrow"><i class="fa fa-columns"></i><span>Data Penjualan</span></a>
                             </li>
                             <li <?php if ($active == "debt") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="<?php echo base_url() ?>debt" class="has-arrow"><i class="fa fa-columns"></i><span>Data Hutang</span></a>
                             </li>

                             <li <?php if ($active == "transaction") {
                                        echo 'class="active"';
                                    }
                                    ?>>
                                 <a href="#Widgets" class="has-arrow"><i class="fa fa-exchange"></i><span>Transaksi</span></a>
                                 <ul>
                                     <li <?php if (isset($subactive) && $subactive == "in") {
                                                echo 'class="active"';
                                            }
                                            ?>><a href="<?php echo base_url() ?>transaction-in">Barang Masuk</a></li>
                                 </ul>
                             </li>
                         </ul>

                     <?php endif ?>
                 </nav>
             </div>
         </div>
     </div>
 </div>