@include('inc_admin.header')
<style>
    .radio-toolbar input[type="radio"], .sub input[type="radio"] {
        opacity: 0;
        position: fixed;
        width: 0;
    }

    .radio-toolbar label,
    .sub label {
        display: inline-block;
        background-color: #ddd;
        padding: 6px 20px;
        font-family: sans-serif, Arial;
        font-size: 16px;
        border: 1px solid #444;
        border-radius: 4px;
    }

    .radio-toolbar input[type="radio"]:checked + label, .sub input[type="radio"]:checked + label {
        background-color: #7580a9;
        color: #fff;
    }

    .admin-container {
        display: none;
    }

    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: #7580a9;
        display: none;
        opacity: 0.5;
        z-index: 99999;
    }

    .overlay__inner {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
    }

    .overlay__content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner {
        width: 75px;
        height: 75px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(255, 255, 255, 0.05);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }

</style>
<link rel="stylesheet" type="text/css" href="assets_admin/css/vendors/datatables.css">
@php
    // use App\Models\Zone_code;
    // use App\Models\Admin;
    // use App\Models\Inventory;
    // use App\Models\Meter_main;
    // use App\Models\Indent;
    // use App\Models\Error_record;
    // use App\Models\Successful_record;
    // use App\Models\Warehouse_meter;
@endphp
<div class="main-content">
    <div class="page-content ">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                        <h1>Welcome

                            <!---<p>{{ ucwords(session('rexkod_vishvin_auth_name')) }}, Project Head</p>-->
							
								<?php
							               
											$name = ucwords(session('rexkod_vishvin_auth_name'));

											if ($name == 'Nanda Gopal') {
												echo "<p>{$name}, Project Executive</p>";
											} else {
												echo "<p>{$name}, Project Head</p>";
											}
											?>

                        </h1>


                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">HOME PAGE</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="radio-toolbar col-12">
                    <h4>Division</h4>
                    @foreach( $data['divisions'] as $key=>$division)

                        <input type="radio" id="radioBelagavi{{$key}}" name="radioSection"
                               value="{{$division->div_code}}">
                        <label for="radioBelagavi{{$key}}">{{$division->division}}</label>
                    @endforeach
                </div>
            </div>
            <!-- Sub division start -->
            <div class="row">
                <div id="subDivision" style='display: none' class="sub-division-code sub"></div>
            </div>

            <div class="row">
                <div id="section" style='display: none' class="section-code sub"></div>
            </div>
        </div>
        <!-- Sub division end -->

        <!-- Section Start -->

        <!-- Section End -->

        <div class="overlay">
            <div class="overlay__inner">
                <div class="overlay__content"><span class="spinner"></span></div>
            </div>
        </div>
        <div class="admin-container mt-lg-5">

            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="text-uppercase fw-medium text-truncate mb-3">
                                Total Meter Replaced</p>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='totalMeter'> </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="text-uppercase fw-medium text-truncate mb-3">
                                Total Meter Replaced Today {{date('d-m-Y')}}</p>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0">
                                            <span class="counter-value" data-target=""
                                                  id='totalMeterInstalledToday'> </span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        QC Pending By Vishvin</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target=""
                                                                                id='vishvinQCPending'></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        QC Pending By AE</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aePending'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        QC Pending by AEE</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aeePending'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        QC Pending by AAO</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aaoPending'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="text-uppercase fw-medium text-truncate mb-3">
                                QC Done by Vishvin</p>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">

                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target=""
                                                                                id='vishvinQCDone'></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <p class="text-uppercase fw-medium text-truncate mb-3">
                                QC Done by AE</p>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">

                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aeDone'></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        QC done By AEE </p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aeeDone'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        QC Completed by AAO</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aaoDone'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        BMR SUCCESS RECORDS </p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='bmrdone'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        BMR ERROR RECORDS & Pending By AAO</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='bmrerror'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        BMR RECORDS PENDING FOR DOWNLOAD</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0">
                                            <span class="counter-value" data-target="" id='bmrpeningdownload'></span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
				
				
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="user" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-truncate mb-3">
                                        Rejected by AAO</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                                                data-target="" id='aaoReject'></span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div>
				
				
            </div>
        </div>
        <!-- container-fluid  -->

        <div class="row project-wrapper">
            <div class="col-xxl-12">
                <div class="row">


                </div>
                <!-- end row -->

                <!-- end col   -->

            </div>
            <!-- end row  -->

        </div>
        <!-- container-fluid  -->
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">


<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function () {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: []
        });

        $(document).on('change', 'input[name = "radioSection"]', radioSection);
        $(document).on('click', 'input[name = "radioSection"]', radioSection);

        function radioSection() {
            $('.sub-division-code').css('display', 'block');
            $('.section-code').css("display", "none");
            $('.admin-container').css("display", "none");
            $('.overlay').css("display", "block");
            $('.section').css("display", "none");
            console.log(this.value);
            $.ajax({
                type: 'GET',
                url: '/hescoms/count/' + this.value,
                datatype: 'json',
                success: function (resp) {
                    console.log(resp);
                    $('#subDivision').empty();
                    $('#subDivision').append('<h3>Sub Division Code</h3>')
                    resp.sub_division_code_res.forEach(function (e) {
                        var radioBtn = $('<input type="radio" id="' + e.sd_code + '" name="subDivisionCode" value="' + e.sd_code + '"><label  class="me-lg-3" for="' + e.sd_code + '">' + e.sub_division + '</label>');
                        radioBtn.appendTo('#subDivision');
                    });
                    $('.admin-container').css("display", "block")
                    console.log(resp);
                    $('#totalMeter').text(resp.division_implement);
                    $('#totalMeterInstalledToday').text(resp.division_implement_today_results);
                    $('#vishvinQCDone').text(resp.vishvin_qc_status_done);
                    $('#vishvinQCPending').text(resp.vishvin_qc_status_pending);
                    $('#aeDone').text(resp.so_status_done);
                    $('#aePending').text(resp.so_status_pending);
                    $('#aeeDone').text(resp.aee_status_done);
                    $('#aeePending').text(resp.aee_status_pending);
                    $('#aaoDone').text(resp.aao_status_done);
					  $('#aaoReject').text(resp.aao_status_rejected);
                    $('#aaoPending').text(resp.aao_status_pending);
                    $('#bmrdone').text(resp.bmr_success_query_results);
                    $('#bmrerror').text(resp.bmr_error_query_results);
                    $('#bmrpeningdownload').text(resp.bmr_pending_query_results);
                    $('.overlay').css("display", "none")
                    Swal.close();
                }
            })
        }

        $(document).on('change', 'input[name = "subDivisionCode"]', subDivisionCode);

        // $(document).on('click', 'input[name = "subDivisionCode"]', subDivisionCode);
        function subDivisionCode() {
            let temp = $('input[name = "radioSection"]:checked').val();
            if (temp === '530010') {
                $('.sub-division-belagavi').css('display', 'block');
                $('.sub-division-vijayapura').css('display', 'none');
            } else {
                $('.sub-division-belagavi').css('display', 'none');
                $('.sub-division-vijayapura').css('display', 'block');
            }
            $('.section-code').css("display", "none");
            $('.admin-container').css("display", "none");
            $('.overlay').css("display", "block");
            $.ajax({
                type: 'GET',
                url: '/hescoms/count/' + temp + '/' + this.value,
                datatype: 'json',
                success: function (resp) {
                    $('#section').empty();
                    $('#section').append('<h3>Section Code</h3>')
                    resp.section_code_res.forEach(function (e) {
                        var radioBtn = $('<input type="radio" id="' + e.so_code + '" name="sectionCode" value="' + e.so_code + '"><label  class="me-lg-3" for="' + e.so_code + '">' + e.section_office + '</label>');
                        radioBtn.appendTo('#section');
                    });
                    $('.section-code').css("display", "block");
                    $('.admin-container').css("display", "block")
                    console.log(resp);
                    $('#totalMeter').text(resp.division_implement);
                    $('#totalMeterInstalledToday').text(resp.division_implement_today_results);
                    $('#vishvinQCDone').text(resp.vishvin_qc_status_done);
                    $('#vishvinQCPending').text(resp.vishvin_qc_status_pending);
                    $('#aeDone').text(resp.so_status_done);
                    $('#aePending').text(resp.so_status_pending);
                    $('#aeeDone').text(resp.aee_status_done);
                    $('#aeePending').text(resp.aee_status_pending);
                    $('#aaoDone').text(resp.aao_status_done);
					  $('#aaoReject').text(resp.aao_status_rejected);
                    $('#aaoPending').text(resp.aao_status_pending);
                    $('#bmrdone').text(resp.bmr_success_query_results);
                    $('#bmrerror').text(resp.bmr_error_query_results);
                    $('#bmrpeningdownload').text(resp.bmr_pending_query_results);
                    $('.overlay').css("display", "none")
                    Swal.close();
                }
            })
        }

        $(document).on('change', 'input[name = "sectionCode"]', sectionCode);

        // $(document).on('click', 'input[name = "sectionCode"]', sectionCode);

        function sectionCode() {
            let temp = $('input[name = "radioSection"]:checked').val();
            let sub_div = $('input[name = "subDivisionCode"]:checked').val();
            if (temp === '530010') {
                $('.sub-division-belagavi').css('display', 'block');
                $('.sub-division-vijayapura').css('display', 'none');
            } else {
                $('.sub-division-belagavi').css('display', 'none');
                $('.sub-division-vijayapura').css('display', 'block');
            }
            $('.admin-container').css("display", "none");
            $('.overlay').css("display", "block");
            $('.section-code').css("display", "block");
            $.ajax({
                type: 'GET',
                url: '/hescoms/count/' + temp + '/' + sub_div + '/' + this.value,
                datatype: 'json',
                success: function (resp) {
                    $('.admin-container').css("display", "block")
                    console.log(resp);
                    $('#totalMeter').text(resp.division_implement);
                    $('#totalMeterInstalledToday').text(resp.division_implement_today_results);
                    $('#vishvinQCDone').text(resp.vishvin_qc_status_done);
                    $('#vishvinQCPending').text(resp.vishvin_qc_status_pending);
                    $('#aeDone').text(resp.so_status_done);
                    $('#aePending').text(resp.so_status_pending);
                    $('#aeeDone').text(resp.aee_status_done);
                    $('#aeePending').text(resp.aee_status_pending);
                    $('#aaoDone').text(resp.aao_status_done);
					  $('#aaoReject').text(resp.aao_status_rejected);
                    $('#aaoPending').text(resp.aao_status_pending);
                    $('#bmrdone').text(resp.bmr_success_query_results);
                    $('#bmrerror').text(resp.bmr_error_query_results);
                    $('#bmrpeningdownload').text(resp.bmr_pending_query_results);
                    $('.overlay').css("display", "none")
                    Swal.close();
                }
            })
        }
    });
</script>

<?php if (!empty(session()->get('failed_message'))) { ?>
<script type="text/javascript">
    Swal.fire({
        icon: 'warning',
        title: '{{ session()->get('failed_message') }}',
        showConfirmButton: false,
        timer: 50000
    })
</script>
<?php } session()->forget('failed_message'); ?>


<?php if (!empty(session()->get('success_message'))) { ?>
<script type="text/javascript">
    alert();
    Swal.fire({
        icon: 'success',
        title: '{{ session()->get('success_message') }}',
        showConfirmButton: false,
        timer: 50000,
    })
</script>
<?php } session()->forget('success_message'); ?>
