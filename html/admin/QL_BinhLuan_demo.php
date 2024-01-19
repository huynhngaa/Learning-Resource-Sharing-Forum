<?php
    
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $mon_hoc = "SELECT * FROM mon_hoc a, khoi_lop b where a.kl_ma = b.kl_ma";
    $result_mon_hoc = mysqli_query($conn,$mon_hoc);
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý môn học</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- Thông báo -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
                include_once("includes/menu.php");
            ?>

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                    include_once("includes/navbar.php");
                ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">



                        <h4 class="py-3 mb-4">
                            <span class="text-muted fw-light">eCommerce / </span>Manage reviews
                        </h4>

                        <div class="row mb-4 g-4">
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body row widget-separator">
                                        <div class="col-sm-5 border-shift border-end">
                                            <h2 class="text-primary">4.89<i class="bx bxs-star mb-2 ms-1"></i></h2>
                                            <p class="fw-medium mb-1">Total 187 reviews</p>
                                            <p class="text-muted">All reviews are from genuine customers</p>
                                            <span class="badge bg-label-primary p-2 mb-3 mb-sm-0">+5 This week</span>
                                            <hr class="d-sm-none">
                                        </div>

                                        <div
                                            class="col-sm-7 gy-1 text-nowrap d-flex flex-column justify-content-between ps-4 gap-2 pe-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <small>5 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 70%" aria-valuenow="61.50" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">124</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>4 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 30%" aria-valuenow="24" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">40</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>3 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 15%" aria-valuenow="12" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">12</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>2 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 10%" aria-valuenow="7" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">7</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>1 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: 5%" aria-valuenow="2" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">2</small>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100">
                                    <div class="card-body row">
                                        <div class="col-sm-5">
                                            <div class="mb-5">
                                                <h4 class="mb-2 text-nowrap">Reviews statistics</h4>
                                                <p class="mb-0"> <span class="me-2">12 New reviews</span> <span
                                                        class="badge bg-label-success">+8.4%</span></p>
                                            </div>

                                            <div>
                                                <h5 class="mb-2 fw-normal">
                                                    <span class="text-success me-1">87%</span>Positive reviews
                                                </h5>
                                                <small class="text-muted">Weekly Report</small>
                                            </div>
                                        </div>
                                        <div class="col-sm-7 d-flex justify-content-sm-end align-items-end"
                                            style="position: relative;">
                                            <div id="reviewsChart" style="min-height: 215px;">
                                                <div id="apexchartsl5jjgabt"
                                                    class="apexcharts-canvas apexchartsl5jjgabt apexcharts-theme-light"
                                                    style="width: 295px; height: 200px;"><svg id="SvgjsSvg1001"
                                                        width="295" height="200" xmlns="http://www.w3.org/2000/svg"
                                                        version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg"
                                                        xmlns:data="ApexChartsNS" transform="translate(0, 0)"
                                                        style="background: transparent;">
                                                        <g id="SvgjsG1003" class="apexcharts-inner apexcharts-graphical"
                                                            transform="translate(22, 5)">
                                                            <defs id="SvgjsDefs1002">
                                                                <linearGradient id="SvgjsLinearGradient1006" x1="0"
                                                                    y1="0" x2="0" y2="1">
                                                                    <stop id="SvgjsStop1007" stop-opacity="0.4"
                                                                        stop-color="rgba(216,227,240,0.4)" offset="0">
                                                                    </stop>
                                                                    <stop id="SvgjsStop1008" stop-opacity="0.5"
                                                                        stop-color="rgba(190,209,230,0.5)" offset="1">
                                                                    </stop>
                                                                    <stop id="SvgjsStop1009" stop-opacity="0.5"
                                                                        stop-color="rgba(190,209,230,0.5)" offset="1">
                                                                    </stop>
                                                                </linearGradient>
                                                                <clipPath id="gridRectMaskl5jjgabt">
                                                                    <rect id="SvgjsRect1011" width="267" height="167.73"
                                                                        x="-2" y="0" rx="0" ry="0" opacity="1"
                                                                        stroke-width="0" stroke="none"
                                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                                </clipPath>
                                                                <clipPath id="forecastMaskl5jjgabt"></clipPath>
                                                                <clipPath id="nonForecastMaskl5jjgabt"></clipPath>
                                                                <clipPath id="gridRectMarkerMaskl5jjgabt">
                                                                    <rect id="SvgjsRect1012" width="267" height="171.73"
                                                                        x="-2" y="-2" rx="0" ry="0" opacity="1"
                                                                        stroke-width="0" stroke="none"
                                                                        stroke-dasharray="0" fill="#fff"></rect>
                                                                </clipPath>
                                                            </defs>
                                                            <rect id="SvgjsRect1010" width="11.27142857142857"
                                                                height="167.73" x="0" y="0" rx="0" ry="0" opacity="1"
                                                                stroke-width="0" stroke-dasharray="3"
                                                                fill="url(#SvgjsLinearGradient1006)"
                                                                class="apexcharts-xcrosshairs" y2="167.73" filter="none"
                                                                fill-opacity="0.9"></rect>
                                                            <g id="SvgjsG1031" class="apexcharts-xaxis"
                                                                transform="translate(0, 0)">
                                                                <g id="SvgjsG1032" class="apexcharts-xaxis-texts-g"
                                                                    transform="translate(0, -4)"><text
                                                                        id="SvgjsText1034"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="18.785714285714285" y="196.73"
                                                                        text-anchor="middle" dominant-baseline="auto"
                                                                        font-size="13px" font-weight="400"
                                                                        fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1035">M</tspan>
                                                                        <title>M</title>
                                                                    </text><text id="SvgjsText1037"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="56.357142857142854" y="196.73"
                                                                        text-anchor="middle" dominant-baseline="auto"
                                                                        font-size="13px" font-weight="400"
                                                                        fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1038">T</tspan>
                                                                        <title>T</title>
                                                                    </text><text id="SvgjsText1040"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="93.92857142857142" y="196.73"
                                                                        text-anchor="middle" dominant-baseline="auto"
                                                                        font-size="13px" font-weight="400"
                                                                        fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1041">W</tspan>
                                                                        <title>W</title>
                                                                    </text><text id="SvgjsText1043"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="131.5" y="196.73" text-anchor="middle"
                                                                        dominant-baseline="auto" font-size="13px"
                                                                        font-weight="400" fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1044">T</tspan>
                                                                        <title>T</title>
                                                                    </text><text id="SvgjsText1046"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="169.07142857142856" y="196.73"
                                                                        text-anchor="middle" dominant-baseline="auto"
                                                                        font-size="13px" font-weight="400"
                                                                        fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1047">F</tspan>
                                                                        <title>F</title>
                                                                    </text><text id="SvgjsText1049"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="206.6428571428571" y="196.73"
                                                                        text-anchor="middle" dominant-baseline="auto"
                                                                        font-size="13px" font-weight="400"
                                                                        fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1050">S</tspan>
                                                                        <title>S</title>
                                                                    </text><text id="SvgjsText1052"
                                                                        font-family="Helvetica, Arial, sans-serif"
                                                                        x="244.21428571428567" y="196.73"
                                                                        text-anchor="middle" dominant-baseline="auto"
                                                                        font-size="13px" font-weight="400"
                                                                        fill="#a1acb8"
                                                                        class="apexcharts-text apexcharts-xaxis-label "
                                                                        style="font-family: Helvetica, Arial, sans-serif;">
                                                                        <tspan id="SvgjsTspan1053">S</tspan>
                                                                        <title>S</title>
                                                                    </text></g>
                                                            </g>
                                                            <g id="SvgjsG1056" class="apexcharts-grid">
                                                                <g id="SvgjsG1057"
                                                                    class="apexcharts-gridlines-horizontal"
                                                                    style="display: none;">
                                                                    <line id="SvgjsLine1059" x1="0" y1="0" x2="263"
                                                                        y2="0" stroke="#e0e0e0" stroke-dasharray="0"
                                                                        stroke-linecap="butt"
                                                                        class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine1060" x1="0" y1="41.9325"
                                                                        x2="263" y2="41.9325" stroke="#e0e0e0"
                                                                        stroke-dasharray="0" stroke-linecap="butt"
                                                                        class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine1061" x1="0" y1="83.865" x2="263"
                                                                        y2="83.865" stroke="#e0e0e0"
                                                                        stroke-dasharray="0" stroke-linecap="butt"
                                                                        class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine1062" x1="0"
                                                                        y1="125.79749999999999" x2="263"
                                                                        y2="125.79749999999999" stroke="#e0e0e0"
                                                                        stroke-dasharray="0" stroke-linecap="butt"
                                                                        class="apexcharts-gridline"></line>
                                                                    <line id="SvgjsLine1063" x1="0" y1="167.73" x2="263"
                                                                        y2="167.73" stroke="#e0e0e0"
                                                                        stroke-dasharray="0" stroke-linecap="butt"
                                                                        class="apexcharts-gridline"></line>
                                                                </g>
                                                                <g id="SvgjsG1058" class="apexcharts-gridlines-vertical"
                                                                    style="display: none;"></g>
                                                                <line id="SvgjsLine1065" x1="0" y1="167.73" x2="263"
                                                                    y2="167.73" stroke="transparent"
                                                                    stroke-dasharray="0" stroke-linecap="butt"></line>
                                                                <line id="SvgjsLine1064" x1="0" y1="1" x2="0"
                                                                    y2="167.73" stroke="transparent"
                                                                    stroke-dasharray="0" stroke-linecap="butt"></line>
                                                            </g>
                                                            <g id="SvgjsG1013"
                                                                class="apexcharts-bar-series apexcharts-plot-series">
                                                                <g id="SvgjsG1014" class="apexcharts-series" rel="1"
                                                                    seriesName="seriesx1" data:realIndex="0">
                                                                    <path id="SvgjsPath1018"
                                                                        d="M13.149999999999999 164.73L13.149999999999999 142.77499999999998C13.149999999999999 140.77499999999998 14.149999999999999 139.77499999999998 16.15 139.77499999999998L21.42142857142857 139.77499999999998C23.42142857142857 139.77499999999998 24.42142857142857 140.77499999999998 24.42142857142857 142.77499999999998L24.42142857142857 142.77499999999998L24.42142857142857 164.73C24.42142857142857 166.73 23.42142857142857 167.73 21.42142857142857 167.73C21.42142857142857 167.73 16.15 167.73 16.15 167.73C14.149999999999999 167.73 13.149999999999999 166.73 13.149999999999999 164.73C13.149999999999999 164.73 13.149999999999999 164.73 13.149999999999999 164.73 "
                                                                        fill="#28d0941a" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 13.149999999999999 164.73L 13.149999999999999 142.77499999999998Q 13.149999999999999 139.77499999999998 16.15 139.77499999999998L 21.42142857142857 139.77499999999998Q 24.42142857142857 139.77499999999998 24.42142857142857 142.77499999999998L 24.42142857142857 142.77499999999998L 24.42142857142857 164.73Q 24.42142857142857 167.73 21.42142857142857 167.73L 16.15 167.73Q 13.149999999999999 167.73 13.149999999999999 164.73z"
                                                                        pathFrom="M 13.149999999999999 164.73L 13.149999999999999 164.73L 24.42142857142857 164.73L 24.42142857142857 164.73L 24.42142857142857 164.73L 24.42142857142857 164.73L 24.42142857142857 164.73L 13.149999999999999 164.73"
                                                                        cy="139.77499999999998" cx="50.72142857142857"
                                                                        j="0" val="20" barHeight="27.955000000000002"
                                                                        barWidth="11.27142857142857"></path>
                                                                    <path id="SvgjsPath1020"
                                                                        d="M50.72142857142857 164.73L50.72142857142857 114.82C50.72142857142856 112.82 51.72142857142856 111.82 53.72142857142857 111.82L58.99285714285714 111.82C60.99285714285715 111.82 61.99285714285715 112.82 61.99285714285714 114.82L61.99285714285714 114.82L61.99285714285714 164.73C61.99285714285715 166.73 60.99285714285715 167.73 58.99285714285714 167.73C58.99285714285714 167.73 53.72142857142857 167.73 53.72142857142857 167.73C51.72142857142856 167.73 50.72142857142856 166.73 50.72142857142857 164.73C50.72142857142857 164.73 50.72142857142857 164.73 50.72142857142857 164.73 "
                                                                        fill="#28d0941a" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 50.72142857142857 164.73L 50.72142857142857 114.82Q 50.72142857142857 111.82 53.72142857142857 111.82L 58.99285714285714 111.82Q 61.99285714285714 111.82 61.99285714285714 114.82L 61.99285714285714 114.82L 61.99285714285714 164.73Q 61.99285714285714 167.73 58.99285714285714 167.73L 53.72142857142857 167.73Q 50.72142857142857 167.73 50.72142857142857 164.73z"
                                                                        pathFrom="M 50.72142857142857 164.73L 50.72142857142857 164.73L 61.99285714285714 164.73L 61.99285714285714 164.73L 61.99285714285714 164.73L 61.99285714285714 164.73L 61.99285714285714 164.73L 50.72142857142857 164.73"
                                                                        cy="111.82" cx="88.29285714285714" j="1"
                                                                        val="40" barHeight="55.910000000000004"
                                                                        barWidth="11.27142857142857"></path>
                                                                    <path id="SvgjsPath1022"
                                                                        d="M88.29285714285714 164.73L88.29285714285714 86.865C88.29285714285714 84.865 89.29285714285714 83.865 91.29285714285714 83.865L96.56428571428572 83.865C98.56428571428572 83.865 99.56428571428572 84.865 99.56428571428572 86.865L99.56428571428572 86.865L99.56428571428572 164.73C99.56428571428572 166.73 98.56428571428572 167.73 96.56428571428572 167.73C96.56428571428572 167.73 91.29285714285714 167.73 91.29285714285714 167.73C89.29285714285714 167.73 88.29285714285714 166.73 88.29285714285714 164.73C88.29285714285714 164.73 88.29285714285714 164.73 88.29285714285714 164.73 "
                                                                        fill="#28d0941a" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 88.29285714285714 164.73L 88.29285714285714 86.865Q 88.29285714285714 83.865 91.29285714285714 83.865L 96.56428571428572 83.865Q 99.56428571428572 83.865 99.56428571428572 86.865L 99.56428571428572 86.865L 99.56428571428572 164.73Q 99.56428571428572 167.73 96.56428571428572 167.73L 91.29285714285714 167.73Q 88.29285714285714 167.73 88.29285714285714 164.73z"
                                                                        pathFrom="M 88.29285714285714 164.73L 88.29285714285714 164.73L 99.56428571428572 164.73L 99.56428571428572 164.73L 99.56428571428572 164.73L 99.56428571428572 164.73L 99.56428571428572 164.73L 88.29285714285714 164.73"
                                                                        cy="83.865" cx="125.86428571428571" j="2"
                                                                        val="60" barHeight="83.865"
                                                                        barWidth="11.27142857142857"></path>
                                                                    <path id="SvgjsPath1024"
                                                                        d="M125.86428571428571 164.73L125.86428571428571 58.90999999999998C125.8642857142857 56.90999999999998 126.8642857142857 55.90999999999998 128.8642857142857 55.90999999999998L134.13571428571427 55.90999999999998C136.13571428571427 55.90999999999998 137.13571428571427 56.90999999999998 137.13571428571427 58.90999999999998L137.13571428571427 58.90999999999998L137.13571428571427 164.73C137.13571428571427 166.73 136.13571428571427 167.73 134.13571428571427 167.73C134.13571428571427 167.73 128.8642857142857 167.73 128.8642857142857 167.73C126.8642857142857 167.73 125.8642857142857 166.73 125.86428571428571 164.73C125.86428571428571 164.73 125.86428571428571 164.73 125.86428571428571 164.73 "
                                                                        fill="#28d0941a" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 125.86428571428571 164.73L 125.86428571428571 58.90999999999998Q 125.86428571428571 55.90999999999998 128.8642857142857 55.90999999999998L 134.13571428571427 55.90999999999998Q 137.13571428571427 55.90999999999998 137.13571428571427 58.90999999999998L 137.13571428571427 58.90999999999998L 137.13571428571427 164.73Q 137.13571428571427 167.73 134.13571428571427 167.73L 128.8642857142857 167.73Q 125.86428571428571 167.73 125.86428571428571 164.73z"
                                                                        pathFrom="M 125.86428571428571 164.73L 125.86428571428571 164.73L 137.13571428571427 164.73L 137.13571428571427 164.73L 137.13571428571427 164.73L 137.13571428571427 164.73L 137.13571428571427 164.73L 125.86428571428571 164.73"
                                                                        cy="55.90999999999998" cx="163.43571428571428"
                                                                        j="3" val="80" barHeight="111.82000000000001"
                                                                        barWidth="11.27142857142857"></path>
                                                                    <path id="SvgjsPath1026"
                                                                        d="M163.43571428571428 164.73L163.43571428571428 30.954999999999984C163.43571428571428 28.954999999999984 164.43571428571428 27.954999999999984 166.43571428571428 27.954999999999984L171.70714285714286 27.954999999999984C173.70714285714286 27.954999999999984 174.70714285714286 28.954999999999984 174.70714285714286 30.954999999999984L174.70714285714286 30.954999999999984L174.70714285714286 164.73C174.70714285714286 166.73 173.70714285714286 167.73 171.70714285714286 167.73C171.70714285714286 167.73 166.43571428571428 167.73 166.43571428571428 167.73C164.43571428571428 167.73 163.43571428571428 166.73 163.43571428571428 164.73C163.43571428571428 164.73 163.43571428571428 164.73 163.43571428571428 164.73 "
                                                                        fill="rgba(113,221,55,0.85)" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 163.43571428571428 164.73L 163.43571428571428 30.954999999999984Q 163.43571428571428 27.954999999999984 166.43571428571428 27.954999999999984L 171.70714285714286 27.954999999999984Q 174.70714285714286 27.954999999999984 174.70714285714286 30.954999999999984L 174.70714285714286 30.954999999999984L 174.70714285714286 164.73Q 174.70714285714286 167.73 171.70714285714286 167.73L 166.43571428571428 167.73Q 163.43571428571428 167.73 163.43571428571428 164.73z"
                                                                        pathFrom="M 163.43571428571428 164.73L 163.43571428571428 164.73L 174.70714285714286 164.73L 174.70714285714286 164.73L 174.70714285714286 164.73L 174.70714285714286 164.73L 174.70714285714286 164.73L 163.43571428571428 164.73"
                                                                        cy="27.954999999999984" cx="201.00714285714287"
                                                                        j="4" val="100" barHeight="139.775"
                                                                        barWidth="11.27142857142857"></path>
                                                                    <path id="SvgjsPath1028"
                                                                        d="M201.00714285714287 164.73L201.00714285714287 58.90999999999998C201.00714285714287 56.90999999999998 202.00714285714287 55.90999999999998 204.00714285714287 55.90999999999998L209.27857142857144 55.90999999999998C211.27857142857144 55.90999999999998 212.27857142857144 56.90999999999998 212.27857142857144 58.90999999999998L212.27857142857144 58.90999999999998L212.27857142857144 164.73C212.27857142857144 166.73 211.27857142857144 167.73 209.27857142857144 167.73C209.27857142857144 167.73 204.00714285714287 167.73 204.00714285714287 167.73C202.00714285714287 167.73 201.00714285714287 166.73 201.00714285714287 164.73C201.00714285714287 164.73 201.00714285714287 164.73 201.00714285714287 164.73 "
                                                                        fill="#28d0941a" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 201.00714285714287 164.73L 201.00714285714287 58.90999999999998Q 201.00714285714287 55.90999999999998 204.00714285714287 55.90999999999998L 209.27857142857144 55.90999999999998Q 212.27857142857144 55.90999999999998 212.27857142857144 58.90999999999998L 212.27857142857144 58.90999999999998L 212.27857142857144 164.73Q 212.27857142857144 167.73 209.27857142857144 167.73L 204.00714285714287 167.73Q 201.00714285714287 167.73 201.00714285714287 164.73z"
                                                                        pathFrom="M 201.00714285714287 164.73L 201.00714285714287 164.73L 212.27857142857144 164.73L 212.27857142857144 164.73L 212.27857142857144 164.73L 212.27857142857144 164.73L 212.27857142857144 164.73L 201.00714285714287 164.73"
                                                                        cy="55.90999999999998" cx="238.57857142857142"
                                                                        j="5" val="80" barHeight="111.82000000000001"
                                                                        barWidth="11.27142857142857"></path>
                                                                    <path id="SvgjsPath1030"
                                                                        d="M238.57857142857142 164.73L238.57857142857142 86.865C238.57857142857142 84.865 239.57857142857142 83.865 241.57857142857142 83.865L246.85 83.865C248.85 83.865 249.85 84.865 249.85 86.865L249.85 86.865L249.85 164.73C249.85 166.73 248.85 167.73 246.85 167.73C246.85 167.73 241.57857142857142 167.73 241.57857142857142 167.73C239.57857142857142 167.73 238.57857142857142 166.73 238.57857142857142 164.73C238.57857142857142 164.73 238.57857142857142 164.73 238.57857142857142 164.73 "
                                                                        fill="#28d0941a" fill-opacity="1"
                                                                        stroke-opacity="1" stroke-linecap="round"
                                                                        stroke-width="0" stroke-dasharray="0"
                                                                        class="apexcharts-bar-area" index="0"
                                                                        clip-path="url(#gridRectMaskl5jjgabt)"
                                                                        pathTo="M 238.57857142857142 164.73L 238.57857142857142 86.865Q 238.57857142857142 83.865 241.57857142857142 83.865L 246.85 83.865Q 249.85 83.865 249.85 86.865L 249.85 86.865L 249.85 164.73Q 249.85 167.73 246.85 167.73L 241.57857142857142 167.73Q 238.57857142857142 167.73 238.57857142857142 164.73z"
                                                                        pathFrom="M 238.57857142857142 164.73L 238.57857142857142 164.73L 249.85 164.73L 249.85 164.73L 249.85 164.73L 249.85 164.73L 249.85 164.73L 238.57857142857142 164.73"
                                                                        cy="83.865" cx="276.15" j="6" val="60"
                                                                        barHeight="83.865" barWidth="11.27142857142857">
                                                                    </path>
                                                                    <g id="SvgjsG1016"
                                                                        class="apexcharts-bar-goals-markers"
                                                                        style="pointer-events: none">
                                                                        <g id="SvgjsG1017"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG1019"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG1021"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG1023"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG1025"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG1027"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                        <g id="SvgjsG1029"
                                                                            className="apexcharts-bar-goals-groups"></g>
                                                                    </g>
                                                                </g>
                                                                <g id="SvgjsG1015" class="apexcharts-datalabels"
                                                                    data:realIndex="0"></g>
                                                            </g>
                                                            <line id="SvgjsLine1066" x1="0" y1="0" x2="263" y2="0"
                                                                stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1"
                                                                stroke-linecap="butt" class="apexcharts-ycrosshairs">
                                                            </line>
                                                            <line id="SvgjsLine1067" x1="0" y1="0" x2="263" y2="0"
                                                                stroke-dasharray="0" stroke-width="0"
                                                                stroke-linecap="butt"
                                                                class="apexcharts-ycrosshairs-hidden"></line>
                                                            <g id="SvgjsG1068" class="apexcharts-yaxis-annotations"></g>
                                                            <g id="SvgjsG1069" class="apexcharts-xaxis-annotations"></g>
                                                            <g id="SvgjsG1070" class="apexcharts-point-annotations"></g>
                                                        </g>
                                                        <g id="SvgjsG1054" class="apexcharts-yaxis" rel="0"
                                                            transform="translate(-8, 0)">
                                                            <g id="SvgjsG1055" class="apexcharts-yaxis-texts-g"></g>
                                                        </g>
                                                        <g id="SvgjsG1004" class="apexcharts-annotations"></g>
                                                    </svg>
                                                    <div class="apexcharts-legend" style="max-height: 100px;"></div>
                                                    <div class="apexcharts-tooltip apexcharts-theme-light">
                                                        <div class="apexcharts-tooltip-title"
                                                            style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                        </div>
                                                        <div class="apexcharts-tooltip-series-group" style="order: 1;">
                                                            <span class="apexcharts-tooltip-marker"
                                                                style="background-color: rgba(40, 208, 148, 0.1);"></span>
                                                            <div class="apexcharts-tooltip-text"
                                                                style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;">
                                                                <div class="apexcharts-tooltip-y-group"><span
                                                                        class="apexcharts-tooltip-text-y-label"></span><span
                                                                        class="apexcharts-tooltip-text-y-value"></span>
                                                                </div>
                                                                <div class="apexcharts-tooltip-goals-group"><span
                                                                        class="apexcharts-tooltip-text-goals-label"></span><span
                                                                        class="apexcharts-tooltip-text-goals-value"></span>
                                                                </div>
                                                                <div class="apexcharts-tooltip-z-group"><span
                                                                        class="apexcharts-tooltip-text-z-label"></span><span
                                                                        class="apexcharts-tooltip-text-z-value"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light">
                                                        <div class="apexcharts-yaxistooltip-text"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="resize-triggers">
                                                <div class="expand-trigger">
                                                    <div style="width: 322px; height: 216px;"></div>
                                                </div>
                                                <div class="contract-trigger"></div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- review List Table -->
                        <div class="card">
                            <div class="card-datatable table-responsive">
                                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="card-header d-flex align-items-md-center pb-md-2 flex-wrap">
                                        <div class="me-5 ms-n2">
                                            <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                                <label>
                                                    <input type="search" class="form-control" placeholder="Tìm kiếm ....">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-end align-items-md-center justify-content-md-end pt-0 gap-3 flex-wrap">
                                            <div class="dataTables_length mt-0 mt-md-3" id="DataTables_Table_0_length">
                                                <label>
                                                    <select name="DataTables_  g/Table_0_length"
                                                        aria-controls="DataTables_Table_0" class="form-select">
                                                        <option value="10">10</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select></label></div>
                                            <div class="review_filter">
                                                <select id="Review" class="form-select">
                                                    <option value=""> All </option>
                                                    <option value="Pending" class="text-capitalize">Pending</option>
                                                    <option value="Published" class="text-capitalize">Published</option>
                                                </select>
                                            </div>
                                            <div class="mx-0 me-md-n3 mt-sm-0">
                                                <div class="dt-buttons">
                                                    <button class="dt-button buttons-collection dropdown-toggle btn btn-label-secondary me-3" type="button">
                                                        <span><i class="bx bx-export me-1"></i>Export</span>
                                                        <span class="dt-down-arrow">▼</span>
                                                    </button> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="datatables-review table dataTable no-footer dtr-column"
                                        id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info"
                                        style="width: 969px;">
                                        <thead>
                                            <tr>
                                                <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1"
                                                    style="width: 0px; display: none;" aria-label=""></th>
                                                <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all"
                                                    rowspan="1" colspan="1" style="width: 18px;" data-col="1"
                                                    aria-label=""><input type="checkbox" class="form-check-input"></th>
                                                <th class="sorting sorting_asc" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    style="width: 0px;"
                                                    aria-label="Product: activate to sort column descending"
                                                    aria-sort="ascending">Bài viết</th>
                                                <th class="text-nowrap sorting" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    style="width: 206px;"
                                                    aria-label="Reviewer: activate to sort column ascending">Người bình
                                                    luận
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                    rowspan="1" colspan="1" style="width: 565px;"
                                                    aria-label="Review: activate to sort column ascending">Nội dung</th>
                                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0"
                                                    rowspan="1" colspan="1" style="width: 0px;"
                                                    aria-label="Date: activate to sort column ascending">Thời gian</th>
                                                <th class="text-nowrap sorting" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    style="width: 0px;"
                                                    aria-label="Status: activate to sort column ascending">Trạng thái
                                                </th>
                                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 0px;"
                                                    aria-label="Actions">Tác vụ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="odd">
                                                <td class="control dtr-hidden" tabindex="0" style="display: none;"></td>
                                                <td class="dt-checkboxes-cell"><input type="checkbox"
                                                        class="dt-checkboxes form-check-input"></td>
                                                <td class="sorting_1">
                                                    <div
                                                        class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper">
                                                            <div class="avatar me-2 rounded-2 bg-label-secondary"><img
                                                                    src="./assets/img/ecommerce-images/product-9.png"
                                                                    alt="Product-9" class="rounded-2"></div>
                                                        </div>
                                                        <div class="d-flex flex-column"><span
                                                                class="fw-medium text-nowrap">Air Jordan</span><small
                                                                class="text-muted">Air Jordan is a line of basketball
                                                                shoes produced by Nike</small></div>
                                                    </div>
                                                </td>
                                                <td class="">
                                                    <div
                                                        class="d-flex justify-content-start align-items-center customer-name">
                                                        <div class="avatar-wrapper">
                                                            <div class="avatar me-2"><img
                                                                    src="./assets/img/avatars/5.png" alt="Avatar"
                                                                    class="rounded-circle"></div>
                                                        </div>
                                                        <div class="d-flex flex-column"><a
                                                                href="app-ecommerce-customer-details-overview.html"><span
                                                                    class="fw-medium">Gisela Leppard</span></a><small
                                                                class="text-muted text-nowrap">gleppard8@yandex.ru</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <div class="read-only-ratings ps-0 mb-2 jq-ry-container"
                                                            readonly="readonly" style="width: 112px;">
                                                            <div class="jq-ry-group-wrapper">
                                                                <div class="jq-ry-normal-group jq-ry-group"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="20px" height="20px"
                                                                        fill="gray">
                                                                        <path
                                                                            d="M12,2 L15.09,8.09 L22,9.9 L17,14 L18.18,20 L12,17.5 L5.82,20 L7,14 L2,9.9 L8.91,8.09 L12,2 Z">
                                                                        </path>
                                                                    </svg><svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="20px" height="20px"
                                                                        fill="gray" style="margin-left: 3px;">
                                                                        <path
                                                                            d="M12,2 L15.09,8.09 L22,9.9 L17,14 L18.18,20 L12,17.5 L5.82,20 L7,14 L2,9.9 L8.91,8.09 L12,2 Z">
                                                                        </path>
                                                                    </svg><svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="20px" height="20px"
                                                                        fill="gray" style="margin-left: 3px;">
                                                                        <path
                                                                            d="M12,2 L15.09,8.09 L22,9.9 L17,14 L18.18,20 L12,17.5 L5.82,20 L7,14 L2,9.9 L8.91,8.09 L12,2 Z">
                                                                        </path>
                                                                    </svg><svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="20px" height="20px"
                                                                        fill="gray" style="margin-left: 3px;">
                                                                        <path
                                                                            d="M12,2 L15.09,8.09 L22,9.9 L17,14 L18.18,20 L12,17.5 L5.82,20 L7,14 L2,9.9 L8.91,8.09 L12,2 Z">
                                                                        </path>
                                                                    </svg><svg xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="20px" height="20px"
                                                                        fill="gray" style="margin-left: 3px;">
                                                                        <path
                                                                            d="M12,2 L15.09,8.09 L22,9.9 L17,14 L18.18,20 L12,17.5 L5.82,20 L7,14 L2,9.9 L8.91,8.09 L12,2 Z">
                                                                        </path>
                                                                    </svg></div>

                                                            </div>
                                                        </div>
                                                        <p class="fw-medium mb-1 text-truncate text-capitalize">ut
                                                            mauris</p><small class="text-break pe-3">Fusce consequat.
                                                            Nulla nisl. Nunc nisl.</small>
                                                    </div>
                                                </td>
                                                <td><span class="text-nowrap">Apr 20, 2020</span></td>
                                                <td><span class="badge bg-label-success"
                                                        text-capitalized="">Published</span></td>
                                                <td>
                                                    <div class="text-xxl-center">
                                                        <div class="dropdown"><a href="javascript:;"
                                                                class="btn dropdown-toggle hide-arrow text-body p-0"
                                                                data-bs-toggle="dropdown"><i
                                                                    class="bx bx-dots-vertical-rounded"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-end"><a
                                                                    href="javascript:;"
                                                                    class="dropdown-item">Download</a><a
                                                                    href="javascript:;" class="dropdown-item">Edit</a><a
                                                                    href="javascript:;"
                                                                    class="dropdown-item">Duplicate</a>
                                                                <div class="dropdown-divider"></div><a
                                                                    href="javascript:;"
                                                                    class="dropdown-item delete-record text-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <div class="row mx-2">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status"
                                                aria-live="polite">Hiển thị 1 đến 10 trong 100 mục</div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="dataTables_paginate paging_simple_numbers"
                                                id="DataTables_Table_0_paginate">
                                                <ul class="pagination">
                                                    <li class="paginate_button page-item previous disabled"
                                                        id="DataTables_Table_0_previous"><a
                                                            aria-controls="DataTables_Table_0" aria-disabled="true"
                                                            role="link" data-dt-idx="previous" tabindex="0"
                                                            class="page-link">Previous</a></li>
                                                    <li class="paginate_button page-item active"><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            aria-current="page" data-dt-idx="0" tabindex="0"
                                                            class="page-link">1</a></li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            data-dt-idx="1" tabindex="0" class="page-link">2</a></li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            data-dt-idx="2" tabindex="0" class="page-link">3</a></li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            data-dt-idx="3" tabindex="0" class="page-link">4</a></li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            data-dt-idx="4" tabindex="0" class="page-link">5</a></li>
                                                    <li class="paginate_button page-item disabled"
                                                        id="DataTables_Table_0_ellipsis"><a
                                                            aria-controls="DataTables_Table_0" aria-disabled="true"
                                                            role="link" data-dt-idx="ellipsis" tabindex="0"
                                                            class="page-link">…</a></li>
                                                    <li class="paginate_button page-item "><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            data-dt-idx="9" tabindex="0" class="page-link">10</a></li>
                                                    <li class="paginate_button page-item next"
                                                        id="DataTables_Table_0_next"><a href="#"
                                                            aria-controls="DataTables_Table_0" role="link"
                                                            data-dt-idx="next" tabindex="0" class="page-link">Next</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <!-- / Content -->

                    <!-- Footer -->

                    <?php
                        include("includes/footer.php"); 
                    ?>

                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <?php
            include_once("includes/ThongBao.php");
        ?>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>