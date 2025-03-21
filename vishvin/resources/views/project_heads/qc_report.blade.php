<!doctype html>

<?php
//  if(($_SESSION['rexkod_admin_id'])) header("Location: ".URLROOT."/admins/login");
 ?>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" >
<head>
<meta charset="utf-8" />
<link rel="icon" type="image/x-icon" href="/assets_admin/images/vishvin/favicon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="#" name="description" />
<meta content="#" name="author" />
<!-- App favicon -->


<!-- Layout config Js -->
<script src="/assets_admin/js/layout.js"></script>
<!-- Bootstrap Css -->
<link href="/assets_admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="/assets_admin/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="/assets_admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="/assets_admin/css/custom.min.css" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<script src="//unpkg.com/alpinejs" defer></script>

<style>
    .mandatory_star{
        color :#e14848;

    }


</style>
        <title>Vishvin QC Report</title>


        <!-- jsvectormap css -->
        <link href="/assets_admin/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />

        <!--Swiper slider css-->
        <link href="/assets_admin/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />



    </head>

    <header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header" style="height:62px;">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="/assets_admin/images/logo-sm.png" alt="" height="40">
                        </span>
                        <span class="logo-lg">
                            <img src="/assets_admin/images/logo-dark.png" alt="" height="40">
                        </span>
                    </a>

                    <a href="index.php" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="/assets_admin/images/logo-sm.png" alt="" height="40">
                        </span>
                        <span class="logo-lg">
                            <img src="/assets_admin/images/logo-light.png" alt="" height="40">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
                <form class="app-search d-none d-md-block">
                    {{-- <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off"
                            id="search-options" value="" >
                        <span class="mdi mdi-magnify search-widget-icon"></span>
                        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none"
                            id="search-close-options"></span>
                    </div> --}}
                    <div class="dropdown-menu dropdown-menu-lg" id="search-dropdown">
                        <div data-simplebar style="max-height: 180px;">
                            <!-- item-->
                            <div class="dropdown-header">
                                <h6 class="text-overflow text-muted mb-0 text-uppercase">Recent Searches</h6>
                            </div>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a href="index.php" class="btn btn-soft-secondary btn-sm btn-rounded">Device 1<i
                                        class="mdi mdi-magnify ms-1"></i></a>
                                <a href="index.php" class="btn btn-soft-secondary btn-sm btn-rounded">Device 2 <i
                                        class="mdi mdi-magnify ms-1"></i></a>
                            </div>
                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-1 text-uppercase">Pages</h6>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-bubble-chart-line align-middle fs-18 text-muted me-2"></i>
                                <span>Analytics Dashboard</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-lifebuoy-line align-middle fs-18 text-muted me-2"></i>
                                <span>Help Center</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="ri-user-settings-line align-middle fs-18 text-muted me-2"></i>
                                <span>My account settings</span>
                            </a>

                            <!-- item-->
                            <div class="dropdown-header mt-2">
                                <h6 class="text-overflow text-muted mb-2 text-uppercase">Members</h6>
                            </div>

                            <div class="notification-list">
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="/assets_admin/images/users/avatar-2.jpg"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="m-0">Angela Bernier</h6>
                                            <span class="fs-11 mb-0 text-muted">Manager</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="/assets_admin/images/users/avatar-3.jpg"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="m-0">David Grasso</h6>
                                            <span class="fs-11 mb-0 text-muted">Web Designer</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- item -->
                                <a href="javascript:void(0);" class="dropdown-item notify-item py-2">
                                    <div class="d-flex">
                                        <img src="/assets_admin/images/users/avatar-5.jpg"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-1">
                                            <h6 class="m-0">Mike Bunch</h6>
                                            <span class="fs-11 mb-0 text-muted">React Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="text-center pt-3 pb-1">
                            <a href="" class="btn btn-primary btn-sm">View All Results <i
                                    class="ri-arrow-right-line ms-1"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex align-items-center">

                {{-- <div class="dropdown d-md-none topbar-head-dropdown header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="bx bx-search fs-22"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-search-dropdown">
                        <form class="p-3">
                            <div class="form-group m-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search ..."
                                        aria-label="Recipient's username">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> --}}

                <div class="dropdown ms-sm-3 header-item topbar-user" >
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="/assets_admin/images/user.png"
                                alt="Header Avatar" style="height:40px;width:40px;">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">Vishvin</span>
                                <span class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">      @if(session('rexkod_vishvin_auth_user_type') == 'admin')
                                    <p>Admin</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'project_head')
                                       <p>Project Head</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'inventory_manager')
                                       <p>Inventory Manager</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'inventory_executive')
                                       <p>Inventory Executive</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'inventory_reporter')
                                       <p>Inventory Reporter</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'contractor_manager')
                                       <p>Contractor Manager</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'qc_manager')
                                       <p>QC Manager</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'qc_executive')
                                       <p>QC Executive</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'hescom_manager')
                                       <p>Hescom Manager</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'aee')
                                       <p>AEE</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'ae')
                                       <p>AE</p>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'aao')
                                       <p>AAO</p></span>
                                       @elseif(session('rexkod_vishvin_auth_user_type') == 'bmr')
                                       <p>BMR</p></span>
                                       @endif
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" >
                        <!-- item-->
                        <h6 class="dropdown-header">Welcome

                            @if(session('rexkod_vishvin_auth_user_type') == 'admin')
                            <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'project_head')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'inventory_manager')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'inventory_executive')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'inventory_reporter')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'contractor_manager')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'qc_manager')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'qc_executive')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'hescom_manager')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'aee')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'ae')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'aao')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @elseif(session('rexkod_vishvin_auth_user_type') == 'bmr')
                               <p>{{ucwords(session('rexkod_vishvin_auth_user_name'))}}</p>
                               @endif
                            </h6>
                        <a class="dropdown-item" href="pages-profile.php"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle" >Profile</span></a>

                        <a class="dropdown-item" href="pages-faqs.php"><i
                                class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Help</span></a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="/admins/logout"><i
                                class="mdi mdi-logout text-muted fs-16 align-middle me-1" ></i>

                                <span >Logout</span>
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<body>

<!-- Begin page -->
<div id="layout-wrapper">

    @include("inc_admin.navbar")


@php


use Carbon\Carbon;


@endphp

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reports</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">Admin</a></li>
                                <li class="breadcrumb-item active">Vishvin QC Report</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                    <div class="card ongoing-project recent-orders">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Vishvin qc Report</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sl. no.</th>
                                            <th>Date of QC</th>
                                            <th>Name of the Executive(QC)</th>
                                            <th>Division Name</th>
                                            <th>Subdivision </th>
                                            <th>Section</th>
                                            <th>No of Meters Approved</th>
                                            {{-- <th>Remarks</th> --}}

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $count =0;


                                        @endphp
                                        @foreach ($data['meter_main'] as $meter_main)
                                        @php
                                        $count++;
                                        // $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $meter_main->created_date)->format('d-m-Y');
                                        @endphp
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$meter_main->date}}</td>
                                            <td>{{$meter_main->qc_name}}</td>
                                            <td>{{$meter_main->division}}</td>
                                            <td>{{$meter_main->sd_pincode}}</td>
                                            <td>{{$meter_main->so_pincode}}</td>
                                            <td>{{$meter_main->installed_count}}</td>

                                        </tr>
                                        @php

                                        @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


</div>




@include('inc_admin.footer')


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<script type="text/javascript" src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/responsive/1.12.1/css/dataTables.responsive.css">
<script type="text/javascript" src="//cdn.datatables.net/responsive/1.12.1/js/dataTables.responsive.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">


<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5'
            ],
        });
    });

</script>

