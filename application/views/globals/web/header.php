<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $siteName ?></title>
    <base href="<?= base_url() ?>">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicons -->

    <link rel="icon" type="image/png" href="assets/img/tp-favicon.png">

    <!-- Google font (font-family: 'Dosis', Roboto;) -->
    <link rel="stylesheet" type="text/css" href="css1/font-awesome.min.css" />

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/plugins.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Cusom css -->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="<?= base_url('assets/css/sweetalert2.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/toastr.min.css') ?>">
    <!-- jQuery -->
    <script src="js/vendor/jquery-3.2.1.min.js"></script>
    <!-- Modernizer js -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
    <!-- bootstrap switch toggle -->
    <link rel="stylesheet" href="css1/bootstrap-switch-button.min.css">
    <script src="js1/bootstrap-switch-button.min.js"></script>
    <style>
        #example_wrapper {
            width: 100%;
        }

        #srch_tbl tbody tr:nth-of-type(odd) {
            background-color: transparent !important;
        }

        #srch_tbl tbody tr:nth-of-type(even) {
            background-color: transparent !important;
        }

        #srch_tbl tbody td {
            border: none !important;
        }

        .dataTables_wrapper {
            width: 95%;
        }

        .dataTables_length .custom-select {
            width: 60px !important;
        }

        .icon-bar {
            display: none !important;
        }

        body {
            font-weight: 300 !important;
        }

        .nw-footer-list {
            padding: 0 0px;
        }

        .nw-footer-list li {
            position: relative;
            padding: 3px 0;
            clear: both;
            display: block;
            font-weight: 300;
        }

        .nw-footer-list li::after {
            content: "";
            width: 203px;
            height: 1px;
            background-color: #868484;
            position: absolute;
            bottom: 0;
            margin: auto;
            left: 0;
            right: 0;
        }

        .nw-footer-list li:last-child:after {
            content: ".";
            display: none;
        }

        .li-list-nw {
            display: block;
            clear: both;
            margin-bottom: 15px;
            line-height: 16px;
            font-weight: 300;
        }

        .footer-align {
            float: right;

        }

        .footer-align li {
            float: left;
        }

        .ftr-icon {
            background-color: #fff;
            font-size: 14px;
            text-align: center;
            border-radius: 4px;
            color: #333 !important;
            margin: 0 3px;
            width: 20px;
            height: 20px;
            display: block;
            line-height: 20px;
        }

        .site-footer {
            padding: 10px 0 0 !important;
            background-color: #333 !important;
        }

        .footer_inner {
            padding: 10px 30px 0 !important;
        }

        .nw-ft-img {
            width: 60%;
            float: right;
            margin-bottom: 30px;
        }

        .nw-img {
            width: 52%;
            margin: auto;
            display: block;
        }

        .nw-img-1 {
            width: 100%;
            margin: 0;
            display: block;
            margin-top: 10px;
        }

        .iphone_frame {
            background-image: url(<?= base_url('/images/category_bg.png') ?>);
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('css/category.css') ?>">
</head>

<body>


    <!-- Main wrapper -->
    <div class="wrapper" id="wrapper">
        <!-- Header -->
        <header id="header" class="jnr__header header--one clearfix">
            <!-- Start Mainmenu Area -->
            <div class="mainmenu__wrapper bg__cat--1 poss-relative header_top_line sticky__header">
                <div class="container-fluid">
                    <div class="row d-none d-lg-flex">
                        <div class="col-sm-4 col-md-3 col-lg-2 order-1 order-lg-1">
                            <div class="logo">
                                <?php //print_r($logo);
                                ?>
                                <a href="<?= base_url('dashboard'); ?>">
                                    <img src="assets/img/<?php echo $logo['file_name']; ?>" alt="">
                                </a>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-2 col-lg-10 order-3 order-lg-2">
                            <div class="mainmenu__wrap">
                                <nav class="mainmenu__nav">
                                    <ul class="mainmenu">
                                        <?php if (!$is_erp_login) : ?>
                                            <?php if ($user->school_name != '') { ?>
                                                <li class=""><a href="#" style="color: #89d700;font-weight: 800;padding-right: 30px;padding-top: 5px;font-size: 16px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;max-width: 443px;"> <span style="text-transform: uppercase;color:#ffffff;"> <span style="text-transform: uppercase;color:#ffffff;"> &nbsp;<?= $user->school_name ?></span></a></li>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <li class="active"><a href="dashboard">Home</a></li>
                                        <!--<li class=""><a href="helps">Help</a></li>-->
                                        <li class=""><a href="logout">Logout</a></li>
                                        <li class=""><a href="profile">Profile</a></li>
                                        <?php if (!$is_erp_login) : ?>
                                            <?php if ($user->teacher_code != '') { ?>
                                                <li class=""><a href="#" style="color:#000000;font-weight:600;">Teacher Code : <span style="text-transform: uppercase;color:#ff9900;"> &nbsp;<?= $user->teacher_code ?></span></a></li>
                                            <?php } ?>
                                        <?php endif; ?>
                                        <li><a href="profile" class="spe-us">

                                                <?php if (empty($user->dp)) { ?>
                                                    <img src="assets/img/3.png" alt="logo images">
                                                <?php } else { ?>
                                                    <img src="assets/img/<?= $user->dp ?>" alt="logo images">
                                                <?php } ?>
                                                &nbsp;
                                                Hi,&nbsp; <b class="ss-pp"><?= $user->fullname ?></b></a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        <!--                            <div class="col-lg-1 col-sm-4 col-md-4 order-2 order-lg-3">
                                                            <div class="shopbox d-flex justify-content-end align-items-center">
                                                                <a class="minicart-trigger" href="#">
                                                                    <i class="fa fa-shopping-basket"></i>
                                                                </a>
                                                                <span>03</span>
                                                            </div>
                                                        </div>-->
                    </div>
                    <!-- Mobile Menu -->
                    <div class="mobile-menu d-block d-lg-none">
                        <div class="logo">
                            <?php //foreach ($logo as $lo): 
                            ?>
                            <a href="<?= base_url(); ?>">
                                <img src="assets/img/<?php echo $logo['file_name']; ?>" alt="Touchpad">
                            </a>
                            <?php // endforeach; 
                            ?>
                        </div>
                        <a class="minicart-trigger" href="#">
                            <i class="fa fa-shopping-basket"></i>
                        </a>
                    </div>
                    <!-- Mobile Menu -->
                </div>
            </div>
            <!-- End Mainmenu Area -->
        </header>
        <!-- //Header -->