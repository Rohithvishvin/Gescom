@include('inc_admin.header')

<link rel="stylesheet" type="text/css" href="assets_admin/css/vendors/datatables.css">
@php
    // use App\Models\Zone_code;
    // use App\Models\Admin;
    use App\Models\Inventory;
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

                            <p>{{ ucwords(session('rexkod_vishvin_auth_name')) }}, Contractor Manager</p>

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
                            <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                                <div class="card ongoing-project recent-orders">
                                    <br>
                                    <div class="card-body pt-0">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>

                                                        <th>Sl. no.</th>
                                                        <th>Qty Allocated</th>
                                                        <th>Qty Balance</th>
                                                        <th>Qty installed in the field</th>
                                                        {{-- <th>Balance Qty with Contractor for implementation</th> --}}

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $count =1;

                                                    @endphp
                                                    @foreach ($data['contractor_inventories_by_id'] as $contractor_inventory)
                                                        @php

                                                            $unused_count=0;
                                                            $unused_sl_no = $contractor_inventory->unused_meter_serial_no;
                                                    if($unused_sl_no !== null && $unused_sl_no !== ''){
                                                        $break_single_meter = explode(',', $unused_sl_no);
                                                                foreach ($break_single_meter as $es_meter_individual) {
                                                                    $unused_count++;
                                                                }
                                                    }


                                                                $single_box = $contractor_inventory->used_meter_serial_no;
                                                                $used_count=0;
                                                                if ($single_box !== null && $single_box !== '') {
                                                                $break_single_meter = explode(',', $single_box);

                                                                foreach ($break_single_meter as $es_meter_individual) {
                                                                    $used_count++;
                                                                }
                                                            }
                                                        @endphp
                                                    <tr style="border-bottom: 1px solid #dee2e6;">
                                                       <td>{{$count}}</td>
                                                       <td>{{$unused_count + $used_count}}</td>
                                                       <td>{{$unused_count}}</td>
                                                       <td>{{$used_count}}</td>
                                                       {{-- <td>{{$unused_count-$used_count}}</td> --}}
                                                    </tr>
                                                @php
                                                    $count++;
                                                @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                                <div class="card ongoing-project recent-orders">
                                    <br>
                                    <div class="card-body pt-0">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl. no.</th>
                                                        <th>Field Executive Name</th>
                                                        <th>No of Meters Replaced</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $count =1;

                                                    @endphp
                                                    @foreach ($data['get_field_executives'] as $get_field_executive)
                                                        @php
                                                            $inventory = count(Inventory::where('created_by',$get_field_executive->id)->get());

                                                        @endphp
                                                    <tr style="border-bottom: 1px solid #dee2e6;">
                                                       <td>{{$count}}</td>
                                                       <td>{{$get_field_executive->name}}</td>
                                                       <td>{{$inventory}}</td>

                                                    </tr>
                                                @php
                                                    $count++;
                                                @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                                                <div class="card ongoing-project recent-orders">
                                                    <br>
                                                    <div class="card-body pt-0">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sl. no.</th>
                                                                        <th>Field Executive Name</th>
                                                                        <th>No of Meters Replaced on {{ \Carbon\Carbon::today()->format('F j, Y') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $count = 1;
                                                                    @endphp
                                                                    @foreach ($data['today_fe_installed_results'] as $result)
                                                                        <tr style="border-bottom: 1px solid #dee2e6;">
                                                                            <td>{{ $count }}</td>
                                                                            <td>{{ $result->name }}</td>
                                                                            <td>{{ $result->count }}</td>
                                                                        </tr>
                                                                        @php
                                                                            $count++;
                                                                        @endphp
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
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
