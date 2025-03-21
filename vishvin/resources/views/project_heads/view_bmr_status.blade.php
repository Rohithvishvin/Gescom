@include("inc_admin.header")

@php
    use App\Models\Admin;
    use App\Models\Zone_code;

	$admin = Admin::where('id',session('rexkod_vishvin_auth_userid'))->first();
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
                                    <a>{{$admin->type}}</a></li>
                                <li class="breadcrumb-item active"><a href="{{url('/')}}/project_heads/view_bmr_status">BMR Report Status</a></li>
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
                            <h4 class="card-title mb-0 flex-grow-1">BMR Status Report Filter</h4>
                        </div>
                        {{-- filter --}}
                        <div class="container-fluid">
                            <form method="POST" id="myForm" action="{{url('/')}}/project_heads/view_bmr_status_filter">
                                @csrf
                                <div class="row">
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="product-title-input">
                                                From
                                                <span class="mandatory_star">
                                                    *<sup><i>required</i></sup
                                                    ></span>
                                            </label>
                                            <input type="date" name="fliterFrom" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required @if(isset($data['from_date'])) value={{ $data['from_date'] }}@endif>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="product-title-input">
                                                To
                                                <span class="mandatory_star">
                                                    *<sup><i>required</i></sup>
                                                </span>
                                            </label>
                                            <input type="date" name="fliterTo" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required @if(isset($data['to_date'])) value={{ $data['to_date'] }}@endif>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="mb-3">
                                            <button type="submit">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($data))
                                <div class="row p-5">
                                    <div class="col-12">
                                        <h3>BMR Status From {{ $data['from_date'] }} - Till {{ $data['to_date'] }}</h3>
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Report Type</th>
                                                    <th scope="col">Report Entries Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><a href="{{ $data['success_report_url'] }}" target="_blank">View Success Report</a></td>
                                                <td>{{ $data['success_count'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><a href="{{ $data['error_report_url'] }}" target="_blank">View Error Report</a></td>
                                                <td>{{ $data['error_count'] }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </form>
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
    // $(document).ready(function() {
    //     $('.table').DataTable({
    //         dom: 'Bfrtip',
    //         buttons: [
    //             'excelHtml5'
    //         ]
    //     });
    // });
</script>
