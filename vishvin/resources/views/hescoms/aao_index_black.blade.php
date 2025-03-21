@include('inc_admin.header')

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

                            <p>{{ ucwords(session('rexkod_vishvin_auth_name')) }}, AAO</p>

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

            <div class="row project-wrapper">
                <div class="col-xxl-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">AAO Approval</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['aao_meter_approved_count'] }}">{{ $data['aao_meter_approved_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">AAO  Rejection</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['aao_meter_rejected_count'] }}">{{ $data['aao_meter_rejected_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">AAO Pending</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['aao_meter_pending_count'] }}">{{ $data['aao_meter_pending_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">Successful Records as per TRM report</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['get_total_successful_record_count'] }}">{{ $data['get_total_successful_record_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">BMR Errors Records as per TRM report</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['get_total_error_record_count'] }}">{{ $data['get_total_error_record_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">Records Uploaded in TRM – Submitted for validation</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['error_record_pending_for_aao_count'] }}">{{ $data['error_record_pending_for_aao_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                        <div class="col-xl-3">
                            <div class="card card-animate">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                <i data-feather="award" class="text-warning"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <p class="text-uppercase fw-medium text-muted mb-3">Records pending for TRM Uploaded – Approved but BMR report not generated</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                        data-target="{{ $data['error_updated_but_not_downloaded_count'] }}">{{ $data['error_updated_but_not_downloaded_count'] }}</span>
                                                </h4>
                                                {{-- <span class="badge badge-soft-success fs-12"><i
                                            class="ri-arrow-up-s-line fs-13 align-middle me-1"></i>3.58
                                        %</span> --}}
                                            </div>
                                            <p class="text-muted mb-0">OVERALL</p>
                                        </div>
                                    </div>
                                </div><!-- end card body -->
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->

                </div>
                <!-- end col   -->

            </div>

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
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [

            ]
        });
    });
</script>
