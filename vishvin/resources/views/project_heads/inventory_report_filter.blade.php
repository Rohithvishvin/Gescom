
@include("inc_admin.header")


@php

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
                                <li class="breadcrumb-item active">Inventory Report Filter</li>
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
                            <h4 class="card-title mb-0 flex-grow-1">Inventory Report Filter</h4>
                        </div>
                        {{-- filter --}}
                        <div class="container-fluid">
                                <form method="POST" action="{{url('/')}}/project_heads/inventory_report_filter">
                                    @csrf
                                    <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="product-title-input">Select Division <span
                                                class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                        <select class="form-control" name="division" id="division" required>
                                            <option value="" disabled="disabled" selected="selected">Please Select
                                            </option>
                                                @foreach( $data['divisions'] as $key=>$division)
                                                    <option value="{{$division->div_code}}">
                                                        {{$division->division}}
                                                    </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="product-title-input">Report Type<span
                                                class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                            <select class="form-control" name="report_type" id="report_type" required>
                                            <option value="" disabled="disabled" selected="selected">Please Select
                                            </option>
                                            <option value="1">Inward new meters</option>
                                            <option value="2">Outward for Installation</option>
                                            <option value="3">Contractor wise Stock Report</option>
                                            <option value="4">Contractor wise Installation Report</option>
                                            <option value="5">Vishvin QC Report</option>
                                            <option value="6">Field Executive wise Installation Report</option>
                                            <option value="7">Section Wise Inward & Installation Report</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4" id="contractor_id_div" style="display: none">
                                        <div class="mb-3">
                                            <label class="form-label" for="product-title-input">Report Type<span
                                                    class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                            <select class="form-control" name="contractor_id" id="contractor_id">
                                                <option value="" disabled="disabled" selected="selected">Please Select
                                                </option>
                                                @foreach( $data['contractors'] as $key=>$contractor)
                                                    <option value="{{$contractor->id}}">
                                                        {{$contractor->name}}
                                                    </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row mb-2">
                                        <div class="col-lg-3 d-flex">
                                            <input type="radio" name="format" id="daily" value="daily">
                                            <label for="daily" style="margin-bottom:0;">Daily</label>
                                        </div>
                                        <div class="col-lg-3 d-flex">
                                            <input type="radio" name="format" id="weekly" value="weekly">
                                            <label for="weekly" style="margin-bottom:0;">Weekly</label>
                                        </div>
                                        <div class="col-lg-3 d-flex">
                                            <input type="radio" name="format" id="monthly" value="monthly">
                                            <label for="monthly" style="margin-bottom:0;">Monthly</label>
                                        </div>
                                        <div class="col-lg-3 d-flex">
                                            <input type="radio" name="format" id="custom" value="custom">
                                            <label for="custom" style="margin-bottom:0;">Custom</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2" id="custom_date" style="display: none">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label for="start_date">Start Date:</label>
                                            <input type="date" class="form-control" name="start_date" id="start_date">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                            <label for="end_date">End Date:</label>
                                            <input type="date" class="form-control" name="end_date" id="end_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            {{-- <label for="end_date">Action </label> --}}
                                            <button type="submit" name= "inward" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>
                                </form>

                        </div>
                        {{-- filter end --}}


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
            ]
        });
    });
</script>

<script>

    document.addEventListener('DOMContentLoaded', function() {
    var dailyRadio = document.querySelector('#daily');
    var weeklyRadio = document.querySelector('#weekly');
    var monthlyRadio = document.querySelector('#monthly');
    var customRadio = document.querySelector('#custom');
    var customDiv = document.querySelector('#custom_date');

        var report_type = document.querySelector('#report_type');

        report_type.addEventListener('change', function (e) {
            if (report_type.value == 3 || report_type.value == 4) {
                document.getElementById('contractor_id_div').style.display = "block";
            } else {
                document.getElementById('contractor_id_div').style.display = "none";
            }
        });

    dailyRadio.addEventListener('change', function() {
        customDiv.style.display = 'none';
    });

    weeklyRadio.addEventListener('change', function() {
        customDiv.style.display = 'none';
    });

    monthlyRadio.addEventListener('change', function() {
        customDiv.style.display = 'none';
    });

    customRadio.addEventListener('change', function() {
        customDiv.style.display = '';
    });
});


</script>
