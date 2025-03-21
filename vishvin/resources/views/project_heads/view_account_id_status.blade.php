@include('inc_admin.header')


@php

    use App\Models\Zone_code;
    use App\Models\Admin;

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
                                    <a href="javascript: void(0);">Project Head</a>
                                </li>
                                <li class="breadcrumb-item active">Account Id Details</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Account Id Details</h4>
                        </div>
                        <div class="card-body pt-0">
                            <form id="search-account-id-form" autocomplete="off" class="needs-validation" action="/project_heads/get-account-id-details" method="GET" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-4">
                                        <label class="form-label" for="product-title-input">Account Id
                                            <span class="mandatory_star">
                                            *<sup><i>required</i></sup>
                                        </span>
                                        </label>
                                        <input type="text" class="form-control" id="account_id" name="account_id" value="" placeholder="Enter Account Id">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label" for="product-title-input">New Meter Serial No
                                            <span class="mandatory_star">
                                            *<sup><i>required</i></sup>
                                        </span>
                                        </label>
                                        <input type="text" class="form-control" id="new_meter_serial_no" name="new_meter_serial_no" value="" placeholder="Enter New Meter Serial No">
                                    </div>
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-success w-sm">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                        $account_data = $data['account_data'];
                        $contractors = $data['contractors'];
                        $qcs = $data['qcs'];
                        $aes = $data['aes'];
                        $aees = $data['aees'];
                        $aaos = $data['aaos'];
                    ?>
                    @if(!empty($account_data) && is_object($account_data))
                    <div class="card ongoing-project recent-orders">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Account Details</h4>
                        </div>
                        <div class="card-body pt-0">
                            @inject('carbon', 'Carbon\Carbon')
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th scope="row">Account Id</th>
                                        <td>{{$account_data->meter_mains_account_id}}</td>
                                        <th scope="row">Division Code</th>
                                        <td>{{$account_data->division}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">RR No</th>
                                        <td>{{$account_data->rr_no}}</td>
                                        <th scope="row">Sub Division Code</th>
                                        <td>{{$account_data->sub_division}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Consumer Name</th>
                                        <td>{{$account_data->consumer_name}}</td>
                                        <th scope="row">Section Code</th>
                                        <td>{{$account_data->section}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Old Meter Serial No</th>
                                        <td>{{$account_data->serial_no_old}}</td>
                                        <th scope="row">Old Meter Final Reading</th>
                                        <td>{{$account_data->final_reading}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">New Meter Serial No</th>
                                        <td>{{$account_data->serial_no_new}}</td>
                                        <th scope="row">New Meter Serial Reading</th>
                                        <td>{{$account_data->initial_reading_kvah}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Date of Replacement</th>
                                        <td>
                                            {{$carbon::parse($account_data->meter_installed_at)->format('d-m-Y')}}
                                        </td>
                                        <th scope="row">Field Executive Name</th>
                                        <td>
                                            {{$account_data->field_executive_name}}
                                        </td>
                                        <th scope="row">Contractor Name</th>
                                        <td>
                                            @foreach($contractors as $contractor)
{{--                                                {{$contractor}}--}}
                                                @if($contractor->id == $account_data->field_executive_contractor_id)
                                                    {{$contractor->name}}
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                       <th scope="row">QC Status</th>
                                        <td>
                                            @if($account_data->qc_status=== '1')
                                                Pass
                                            @elseif($account_data->qc_status=== '2')
                                                Rejected
                                            @elseif($account_data->qc_status=== '3')
                                                Under Review
                                            @else
                                                Pending
                                            @endif
                                            @if($account_data->qc_status=== '1' || $account_data->qc_status=== '2')
                                                <span> on ({{$carbon::parse($account_data->qc_updated_at)->format('d-m-Y')}})</span>
                                            @endif
                                            @foreach($qcs as $qc)
                                                @if($qc->id == $account_data->qc_updated_by)
                                                    <span>by {{$qc->name}}</span>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">AE Status</th>
                                        <td>
                                            @if($account_data->so_status=== '1')
                                                Pass
                                            @elseif($account_data->so_status=== '2')
                                                Rejected
                                            @else
                                                Pending
                                            @endif
                                            @if($account_data->so_status=== '1' || $account_data->so_status=== '2')
                                                <span> on ({{$carbon::parse($account_data->qc_updated_at)->format('d-m-Y')}})</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">AEE Status</th>
                                        <td>
                                            @if($account_data->aee_status=== '1')
                                                Pass
                                            @elseif($account_data->aee_status=== '2')
                                                Rejected
                                            @else
                                                Pending
                                            @endif
                                            @if($account_data->aee_status=== '1' || $account_data->aee_status=== '2')
                                                <span> on ({{$carbon::parse($account_data->qc_updated_at)->format('d-m-Y')}})</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">AAO Status</th>
                                        <td>
                                            @if($account_data->aao_status=== '1')
                                                Pass
                                            @elseif($account_data->aao_status=== '2')
                                                Rejected
                                            @else
                                                Pending
                                            @endif
                                            @if($account_data->aao_status=== '1' || $account_data->aao_status=== '2')
                                                <span> on ({{$carbon::parse($account_data->qc_updated_at)->format('d-m-Y')}})</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if(!empty($account_data->successful_records_account_id))
                                    <tr>
                                        <th scope="row">BMR Success Reported On</th>
                                        <td>
                                            {{$carbon::parse($account_data->successful_reported_at)->format('d-m-Y')}}
                                        </td>
                                    </tr>
                                    @endif
                                    @if(!empty($account_data->error_records_account_id))
                                    <tr>
                                        <th scope="row">BMR Error Reported On</th>
                                        <td>
                                            {{$carbon::parse($account_data->error_records_reported_at)->format('d-m-Y')}}
                                        </td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    @elseif($account_data == "not found")
                        <div class="row">
                            <div class="col-12">
                                <h3 class="bg-danger">Account ID / New Meter Serial Not Found in Meter Mains</h3>
                            </div>
                        </div>
                    @endif
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
            ]
        });
    });
</script>
