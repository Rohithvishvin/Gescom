<!doctype html>

<?php
//  if(($_SESSION['rexkod_admin_id'])) header("Location: ".URLROOT."/admins/login");
?>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none" data-preloader="disable">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/x-icon" href="/assets_admin/images/vishvin/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="#" name="description"/>
    <meta content="#" name="author"/>
    <!-- App favicon -->


    <!-- Layout config Js -->
    <script src="/assets_admin/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="/assets_admin/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="/assets_admin/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="/assets_admin/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
    <!-- custom Css-->
    <link href="/assets_admin/css/custom.min.css" id="app-style" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
          integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>


    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .mandatory_star {
            color: #e14848;

        }


    </style>
    <title>Release meter report (Division:{{$data['division'] ? $data['division']: ''}}
        ,Sub-division:{{$data['subdivision'] ? $data['subdivision']: ''}}
        ,Section:{{$data['section'] ? $data['section']: ''}})</title>

    <!-- jsvectormap css -->
    <link href="/assets_admin/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css"/>

    <!--Swiper slider css-->
    <link href="/assets_admin/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css"/>


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

                <div class="dropdown ms-sm-3 header-item topbar-user">
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
                    <div class="dropdown-menu dropdown-menu-end">
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
                                    class="align-middle">Profile</span></a>

                        <a class="dropdown-item" href="pages-faqs.php"><i
                                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                                    class="align-middle">Help</span></a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="/admins/logout"><i
                                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>

                            <span>Logout</span>
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
                                    <li class="breadcrumb-item active">Release Meter Report</li>
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
                                <h4 class="card-title mb-0 flex-grow-1">Release Meter Report</h4>
                            </div>


                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-2 m-1">
                                        <label class="form-label" for="filter_account_id">Account Id</label>
                                        <input type="text" class="form-control" id="filter_account_id" name="">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label" for="filter_rr_no">RR No</label>
                                        <input type="text" class="form-control" id="filter_rr_no" name="">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label" for="filter_meter_new_serial_no">New Meter Serial
                                            No</label>
                                        <input type="text" class="form-control" id="filter_meter_new_serial_no" name="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2">
                                        <a class="btn btn-success w-sm" id="downloadExcelBtn">Excel</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="releaseMeterTable" class="table">
                                        <thead>
                                        <tr>
                                            <th>Sl. no.</th>
                                            <th>Account Id</th>
                                            <th>RR NO.</th>
                                            <th>Consumer Name</th>
                                            <th>Feeder Code</th>
                                            <th>Feeder Name</th>
                                            <th>Division Code</th>
                                            <th>Sub-Division Code</th>
                                            <th>Section Code</th>
                                            <th>Tariff</th>
                                            <th>Installation Type</th>
                                            <th>EM Meter Sl. No.</th>
                                            <th>EM Make</th>
                                            <th>EM MFY</th>
                                            <th>EM Meter FR</th>
                                            <th>ES Meter Sl. No.</th>
                                            <th>New Meter Initial Reading</th>
                                            <th>ES Make</th>
                                            <th>Date of Replacement</th>
                                            <th class="project_head">Contractor</th>
                                            <th class="project_head">Field Executive</th>
                                        </tr>
                                        </thead>
                                        {{--                                    <tbody>--}}
                                        {{--                                        @foreach ($data['meter_main'] as $key=>$meter_main)--}}
                                        {{--                                        @php--}}
                                        {{--                                            $contractorName = null;--}}
                                        {{--                                            foreach ($data['contractors'] as $contractorKey => $contractorValue){--}}
                                        {{--                                                if($meter_main->field_executive_contractor_id == $contractorValue->contractor_id){--}}
                                        {{--													$contractorName = $contractorValue->contractor_name;--}}
                                        {{--                                                }--}}
                                        {{--                                            }--}}
                                        {{--                                        $formattedDate = Carbon::createFromFormat('Y-m-d H:i:s', $meter_main->created_at)->format('d-m-Y');--}}
                                        {{--                                        @endphp--}}
                                        {{--                                        <tr>--}}
                                        {{--                                            <td>{{++$key}}</td>--}}
                                        {{--                                            <td>{{$meter_main->account_id}}</td>--}}
                                        {{--                                            <td>{{$meter_main->rr_no}}</td>--}}
                                        {{--                                            <td>{{$meter_main->consumer_name}}</td>--}}
                                        {{--                                            <td>{{$meter_main->feeder_code}}</td>--}}
                                        {{--                                            <td>{{$meter_main->feeder_name}}</td>--}}
                                        {{--                                            <td>{{$meter_main->section}}</td>--}}
                                        {{--                                            <td>{{$meter_main->sub_division}}</td>--}}
                                        {{--                                            <td>{{$meter_main->tariff}}</td>--}}
                                        {{--                                            <td>{{$meter_main->meter_type == 1 ? "Single Phase" : "Three Phase"}}</td>--}}
                                        {{--                                            <td>{{$meter_main->phase_type}}</td>--}}
                                        {{--                                            <td>{{$meter_main->serial_no_old}}</td>--}}
                                        {{--                                            <td>{{$meter_main->meter_make_old}}</td>--}}
                                        {{--                                            <td>{{$meter_main->mfd_year_old}}</td>--}}
                                        {{--                                            <td>{{$meter_main->final_reading}}</td>--}}
                                        {{--                                            <td>{{$meter_main->serial_no_new}}</td>--}}
                                        {{--                                            <td>{{$meter_main->initial_reading_kvah}}</td>--}}
                                        {{--                                            <td>{{"GENUS POWER INFRASTRUCTURE LTD"}}</td>--}}
                                        {{--                                            <td>{{$formattedDate}}</td>--}}
                                        {{--                                            @if($data['users']->type == "project_head")--}}
                                        {{--                                            <td>{{$contractorName??''}}</td>--}}
                                        {{--                                            <td>{{$meter_main->field_executive_name??''}}</td>--}}
                                        {{--                                            @endif--}}
                                        {{--                                        </tr>--}}
                                        {{--                                        @endforeach--}}
                                        {{--                                    </tbody>--}}
                                        <tfoot>
                                        <tr>
                                            <th>Sl. no.</th>
                                            <th>Account Id</th>
                                            <th>RR NO.</th>
                                            <th>Consumer Name</th>
                                            <th>Feeder Code</th>
                                            <th>Feeder Name</th>
                                            <th>Division Code</th>
                                            <th>Sub-Division Code</th>
                                            <th>Section Code</th>
                                            <th>Tariff</th>
                                            <th>Installation Type</th>
                                            <th>EM Meter Sl. No.</th>
                                            <th>EM Make</th>
                                            <th>EM MFY</th>
                                            <th>EM Meter FR</th>
                                            <th>ES Meter Sl. No.</th>
                                            <th>New Meter Initial Reading</th>
                                            <th>ES Make</th>
                                            <th>Date of Replacement</th>
                                            <th class="project_head">Contractor</th>
                                            <th class="project_head">Field Executive</th>
                                        </tr>
                                        </tfoot>
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
        let url = window.location.href;
        let url_parts = url.split('/');
        let table = null;
        var filters_data = <?= json_encode($data) ?>;
        var user_data = filters_data.users;
        var contractor_data = filters_data.contractors;

        var is_project_head = 'false';

        if (user_data.type === "project_head") {
            is_project_head = 'true';
        }

        var download_base_url = url_parts[0] + '//' + url_parts[2] + "/api/project_heads/download_release_meter_report?";

        var download_url_params = new URLSearchParams({
            start_date: url_parts[5],
            end_date: url_parts[6],
            division: url_parts[7],
            subdivision: url_parts[8],
            section: url_parts[9],
            feeder_code: url_parts[10],
            project_head: is_project_head,
            download: 'yes'
        }).toString();

        download_base_url = download_base_url + download_url_params;

        var download_url = new URL(download_base_url);

        console.log(download_url)

        var ele = document.getElementById('downloadExcelBtn');

        ele.href = download_url;

        function extraFilters(type) {
            var filter = '&' + type + '=';
            if (type == "account_id") {
                let account_id_text = '';
                if ($('#filter_account_id').val()) { //.val().length !== 0
                    console.log('keypress value :' + $('#filter_account_id').val());
                    account_id_text = $('#filter_account_id').val();
                } else {
                    account_id_text = '';
                }
                filter = filter + account_id_text;
            }
            if (type == "rr_no") {
                let rr_no_text = '';
                if ($('#filter_rr_no').val()) { //.val().length !== 0
                    console.log('keypress value :' + $('#filter_rr_no').val());
                    rr_no_text = $('#filter_rr_no').val();
                } else {
                    rr_no_text = '';
                }

                filter = filter + rr_no_text;
            }
            if (type == "meter_new_serial_no") {
                let meter_serial_no_new_text = '';
                if ($('#filter_meter_new_serial_no').val()) { //.val().length !== 0
                    console.log('keypress value :' + $('#filter_meter_new_serial_no').val());
                    meter_serial_no_new_text = $('#filter_meter_new_serial_no').val();
                } else {
                    meter_serial_no_new_text = '';
                }

                filter = filter + meter_serial_no_new_text;
            }
            return filter;
        }

        function getUrlFilter(filter_data, type = '') {
            var url = "/api/project_heads/release_meter_report_data?";

            if (type == 'account_id' || $('#filter_account_id').val()) {
                type = 'account_id';
                url = url + extraFilters(type);
            }
            if (type == 'rr_no' || $('#filter_rr_no').val()) {
                type = 'rr_no';
                url = url + extraFilters(type);
            }
            if (type == 'meter_new_serial_no' || $('#filter_meter_new_serial_no').val()) {
                type = 'meter_new_serial_no';
                url = url + extraFilters(type);
            }

            if (filter_data.division != null) {
                url = url + '&division=' + filter_data.division;
            }
            if (filter_data.subdivision != null) {
                url = url + '&subdivision=' + filter_data.subdivision;
            }
            if (filter_data.section != null) {
                url = url + '&section=' + filter_data.section;
            }
            if (filter_data.feeder_code != null) {
                url = url + '&feeder_code=' + filter_data.feeder_code;
            }
            if (filter_data.contractor_id != null) {
                url = url + '&contractor_id=' + filter_data.contractor_id;
            }
            if (filter_data.start_date != null) {
                url = url + '&start_date=' + filter_data.start_date;
            }
            if (filter_data.end_date != null) {
                url = url + '&end_date=' + filter_data.end_date;
            }
            console.log(url);
            return url;

            // var url = "./offersListe2AJAX.php?source_list=contact&date_from="+startDate+"&date_till="+endDate+"&date_from_departure="+startDateDeparture+"&date_till_departure="+endDateDeparture+"&reschedule_departure_from_date="+startRescheduleDepartureDate+"&reschedule_departure_till_date="+endRescheduleDepartureDate+preference+status+airlineCode+filterBy+queuedFilter+extraFilter;
            //
            // return url;
        }

        function getDataByFilters(filter_data, type = '') {
            console.log(filter_data);
            console.log(type);
            table.ajax.url(getUrlFilter(filter_data, type)).load();
            //table.ajax.reload();
        }

        $('#filter_account_id').on('keyup', function (event) {
            console.log('keypress');
            getDataByFilters(filters_data, 'account_id');
        });

        $('#filter_rr_no').on('keyup', function (event) {
            console.log('keypress');
            getDataByFilters(filters_data, 'rr_no');
        });

        $('#filter_meter_new_serial_no').on('keyup', function (event) {
            console.log('keypress');
            getDataByFilters(filters_data, 'meter_new_serial_no');
        });

        function prepareUrl(filter_data) {

        }

        table = $('#releaseMeterTable').DataTable({
            fixedHeader: {
                header: true,
                footer: true
            },
            paging: true,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: true,
            keys: false,
            pageLength: 50,
            // dom: 'Bfrtip',
            // buttons: [
            //     'csv', 'excel'
            // ],
            "ajax": {
                url: getUrlFilter(filters_data),
                dataSrc: 'data',
            },
            "columns": [
                {
                    data: 'slno',
                    "type": "numeric",
                },
                {
                    data: 'account_id',
                    "type": "numeric",
                },
                {
                    data: 'rr_no',
                    "type": "numeric",
                },
                {
                    data: 'consumer_name',
                },
                {
                    data: 'feeder_code',
                },
                {
                    data: 'feeder_name',
                },
                {
                    data: 'division',
                },
                {
                    data: 'sub_division',
                },
                {
                    data: 'section',
                },
                {
                    data: 'tariff',
                },
                {
                    data: 'phase_type',
                },
                {
                    data: 'serial_no_old',
                },
                {
                    data: 'meter_make_old',
                },
                {
                    data: 'mfd_year_old',
                },
                {
                    data: 'final_reading',
                },
                {
                    data: 'serial_no_new',
                },
                {
                    data: 'initial_reading_kvah',
                },
                {
                    data: 'meter_make_new',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'contractor_name',
                    visible: false,
                    // render: function (contractor_id, type, row, meta) {
                    //     //console.log('meter_mains_contractor_id : ' + contractor_id);
                    //     let contractor_name = null;
                    //     contractor_data.forEach((contractor) => {
                    //         if (contractor.contractor_id == contractor_id) {
                    //             //console.log(contractor.contractor_name);
                    //             contractor_name = contractor.contractor_name;
                    //             return false
                    //         }
                    //     });
                    //     return contractor_name;
                    // }
                },
                {
                    data: 'field_executive_name',
                    visible: false,
                }
            ],
            "createdRow": function (row, data, index) {
                //console.log(row);
                //console.log(data);
                //console.log(index);
            },
            "initComplete": function (settings, json) {
                //console.log(settings);
                //console.log(json);
                console.log("initialized done");

                if (user_data.type === "project_head") {
                    table.columns('.project_head').visible('true');
                }
            },
        });


    </script>

