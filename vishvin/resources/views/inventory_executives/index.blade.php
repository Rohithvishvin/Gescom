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

                            <p>{{ ucwords(session('rexkod_vishvin_auth_name')) }}, Inventory Executive</p>

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
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header border-0 align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Total Meters</h4>

                                    </div><!-- end card header -->

                                    <div class="card-header p-0 border-0 bg-soft-light">
                                        <div class="row g-0 text-center">
                                            <div class="col-6 col-sm-2">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" style="color:blue;"
                                                            data-target="{{ $data['es_total_meter_count_inventory_executive_wise'] }}">{{ $data['es_total_meter_count_inventory_executive_wise'] }}</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Total Meters</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6 col-sm-2">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" style="color:blue;"
                                                            data-target="{{ $data['es_total_unused_meter_count_inventory_executive_wise'] }}">{{ $data['es_total_unused_meter_count_inventory_executive_wise'] }}</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Unused Meters</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6 col-sm-2">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value" style="color:blue;"
                                                            data-target="{{ $data['es_total_used_meter_count_inventory_executive_wise'] }}">{{ $data['es_total_used_meter_count_inventory_executive_wise'] }}</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Used Meters</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-6 col-sm-2">
                                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                                    <h5 class="mb-1"><span class="counter-value" style="color:blue;"
                                                            data-target="{{ $data['em_total_inward_meter_count_inventory_executive_wise'] }}">{{ $data['em_total_inward_meter_count_inventory_executive_wise'] }}</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Inward EM</p>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-2">
                                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                                    <h5 class="mb-1"><span class="counter-value" style="color:blue;"
                                                            data-target="{{ $data['em_total_outward_meter_count_inventory_executive_wise'] }}">{{ $data['em_total_outward_meter_count_inventory_executive_wise'] }}</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Outward EM</p>
                                                </div>
                                            </div>
                                            <div class="col-6 col-sm-2">
                                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                                    <h5 class="mb-1"><span class="counter-value" style="color:blue;"
                                                            data-target="{{ $data['total_rejected_meter_count_inventory_executive_wise'] }}">{{ $data['total_rejected_meter_count_inventory_executive_wise'] }}</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Rejected Meter</p>
                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                    </div><!-- end card header -->

                                </div><!-- end col -->
                            </div>
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
