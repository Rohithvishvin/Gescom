@include("inc_admin.header")
@php
    use App\Models\Admin;
    use App\Models\Zone_code;
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
                            <h4 class="card-title mb-0 flex-grow-1">Report Filter</h4>
                        </div>
                        {{-- filter --}}
                        <div class="container-fluid">
                            <form method="POST" id="myForm" action="{{url('/')}}/project_heads/report_filter">
                                @csrf
                                <div class="row">

                                    @if (session('rexkod_vishvin_auth_user_type') == 'ae')
                                        @php
                                            //$admin = Admin::where('id',session('rexkod_vishvin_auth_userid'))->first();
                                            //$zone_code = Zone_code::where('so_code',$admin->so_pincode)->first();
                                        @endphp
                                        <div class="col-4" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <input type="text" name="division"
                                                       value="{{$data['zone_code']->div_code}}">
                                            </div>
                                        </div>
                                        <div class="col-4" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Sub Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <input type="text" name="sub_division"
                                                       value="{{$data['zone_code']->sd_code}}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Section <span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="section" id="section">
                                                    <option value="{{$data['user_data']->so_pincode}}"
                                                            selected="selected">{{$data['user_data']->so_pincode}}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">
                                                    Select Feeder Name
                                                    <span class="mandatory_star">*<sup><i>required</i></sup>
                                            </span>
                                                </label>
                                                <select class="form-control" name="feeder_code" id="feeder_code">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Report Type<span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="report_type" id="report_type"
                                                        required>
                                                    <option disabled="enabled" selected="selected" value="">Please
                                                        Select
                                                    </option>
                                                    <option value="1">Release Meter Report</option>
                                                    <option value="2">Meter Replacement Report</option>
                                                    <option value="6"
                                                            @if(isset($data['to_date'])) @if($data['report_type']=="6") selected="selected" @endif @endif>
                                                        BMR Report
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @elseif(session('rexkod_vishvin_auth_user_type') == 'aee' || session('rexkod_vishvin_auth_user_type') == 'aao')
                                        @php
                                            $admin = Admin::where('id',session('rexkod_vishvin_auth_userid'))->first();
                                            $zone_code = Zone_code::where('sd_code',$admin->sd_pincode)->first();
                                        @endphp
                                        <div class="col-4" style="display: none;">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <input type="text" name="division" value="{{$zone_code->div_code}}">
                                                {{-- <select class="form-control" name="division" id="section">
                                                    <option value="" disabled="disabled" selected="selected">Please Select
                                                    </option>
                                                    <option value="530010">Belagavi, 530010</option>
                                                        <option value="530016">Vijayapura, 530016</option>
                                                </select> --}}
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Sub Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="sub_division" id="sub_division2">
                                                    <option value="{{$admin->sd_pincode}}"
                                                            selected="selected">{{$admin->sd_pincode}}
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Section <span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="section" id="section2">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">
                                                    Select Feeder Name
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="feeder_code" id="feeder_code">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Report Type<span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="report_type" id="report_type"
                                                        required>
                                                    <option disabled="enabled" selected="selected" value="">Please
                                                        Select
                                                    </option>
                                                    <option value="1">Release Meter Report</option>
                                                    <option value="2">Meter Replacement Report</option>
                                                    <option value="6"
                                                            @if(isset($data['to_date'])) @if($data['report_type']=="6") selected="selected" @endif @endif>
                                                        BMR Report
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @elseif(session('rexkod_vishvin_auth_user_type') == 'project_head' || session('rexkod_vishvin_auth_user_type') == 'hescom_manager')
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Division
                                                    <span class="mandatory_star">*
                                                        <sup><i>required</i></sup>
                                                    </span>
                                                </label>
                                                <select class="form-control" name="division" id="division">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                    @foreach( $data['divisions'] as $key=>$division)
                                                        <option value="{{$division->div_code}}">{{$division->division}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Sub Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="sub_division" id="sub_division">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Section
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="section" id="section">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Feeder
                                                    Name<span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="feeder_code" id="feeder_code">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Report Type<span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="report_type" id="report_type"
                                                        required>
                                                    <option disabled="enabled" selected="selected" value="">Please
                                                        Select
                                                    </option>
                                                    <option value="1">Release Meter Report</option>
                                                    <option value="2">Meter Replacement Report</option>
                                                    <option value="3">ANX-1 Abstract Report</option>
                                                    <option value="4">ANX-1 Detailed Report</option>
                                                    <option value="5">ANX-3 Report</option>
                                                    <option value="6"
                                                            @if(isset($data['to_date'])) @if($data['report_type']=="6") selected="selected" @endif @endif>
                                                        BMR Report
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @elseif( session('rexkod_vishvin_auth_user_type') == 'hescom_manager')
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="division" id="division">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                    @foreach( $data['divisions'] as $key=>$division)
                                                        <option
                                                                value="{{$division->div_code}}">{{$division->division}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Sub Division
                                                    <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="sub_division" id="sub_division">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Section <span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="section" id="section">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Feeder
                                                    Name<span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="feeder_code" id="feeder_code">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Report Type<span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="report_type" id="report_type"
                                                        required>
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                    <option value="1">Release Meter Report</option>
                                                    <option value="2">Meter Replacement Report</option>
                                                    <option value="3">ANX-1 Abstract Report</option>
                                                    <option value="4">ANX-1 Detailed Report</option>
                                                    <option value="5">ANX-3 Report</option>
                                                </select>
                                            </div>
                                        </div>
                                    @elseif(session('rexkod_vishvin_auth_user_type') == 'bmr')
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">
                                                    Select Division
                                                    <span class="mandatory_star">*
                                                <sup><i>required</i></sup>
                                            </span>
                                                </label>
                                                <select class="form-control" name="division" id="division">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                    @foreach( $data['divisions'] as $key=>$division)
                                                        <option
                                                                value="{{$division->div_code}}">{{$division->division}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Sub Division
                                                    <span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="sub_division" id="sub_division">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Section <span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="section" id="section">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Select Feeder
                                                    Name<span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="feeder_code" id="feeder_code">
                                                    <option value="" disabled="disabled" selected="selected">Please
                                                        Select
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">Report Type<span
                                                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                                                <select class="form-control" name="report_type" id="report_type"
                                                        required>
                                                    <option value="6" selected="selected">
                                                        BMR Report
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
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
                                                <input type="radio" name="format" id="custom" value="custom"
                                                       @if(isset($data['format'])) checked @endif>
                                                <label for="custom" style="margin-bottom:0;">Custom</label>
                                            </div>
                                        </div>
                                        <div class="row mb-2" id="custom_date" style="display: none">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="start_date">Start Date:</label>
                                                    <input type="date" class="form-control" name="start_date"
                                                           id="start_date"
                                                           @if(isset($data['from_date'])) value={{ $data['from_date'] }}@endif>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="end_date">End Date:</label>
                                                    <input type="date" class="form-control" name="end_date"
                                                           id="end_date"
                                                           @if(isset($data['to_date'])) value={{ $data['to_date'] }}@endif>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            {{-- <label for="end_date">Action </label> --}}
                                            <button type="submit" name="inward" class="btn btn-primary"
                                                    id="filter_submit">Search
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        {{-- filter end --}}
                        @if (session('rexkod_vishvin_auth_user_type') == 'ae' || session('rexkod_vishvin_auth_user_type') == 'project_head' || session('rexkod_vishvin_auth_user_type') == 'aao' || session('rexkod_vishvin_auth_user_type') == 'aee' || session('rexkod_vishvin_auth_user_type') == 'bmr')

                            @if(isset($data) && !empty($data['from_date']))
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
                                                <td><a href="{{ $data['success_report_url'] }}" target="_blank">View
                                                        Success Report</a></td>
                                                <td>{{ $data['success_count'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><a href="{{ $data['error_report_url'] }}" target="_blank">View Error
                                                        Report</a></td>
                                                <td>{{ $data['error_count'] }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif


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
    //    $(document).ready(function() {
    //  $('.table').DataTable({
    //       dom: 'Bfrtip',
    //        buttons: [
    //            'excelHtml5'
    //        ]
    //    });
    // });
</script>
<script>
    function fetchAndPopulateFeederCodes(division_code = null, sub_division_code = null, section_code = null) {
        $('#feeder_code').empty();
        $('#feeder_code').append($('<option>', {value: '', text: '-- Select --'}));

        $.get('/project_heads/get_feeder_codes/' + division_code + '/' + sub_division_code + '/' + section_code, function (feeder_codes) {
            $.each(feeder_codes, function (i, code) {
                // console.log(code);
                var option_text = code.feeder_code + ' , ' + code.feeder_name;
                $('#feeder_code').append($('<option>', {value: code.feeder_code, text: option_text}));
            });
        });
    }

    $(document).ready(function () {
        $('#division').change(function () {
            var division = $(this).val();
            var sub_division = null;
            var section = null;
            if ($('#sub_division').val() != '') sub_division = $('#sub_division').val();
            if ($('#section').val() != '') section = $('#section').val();

            $('#sub_division').empty();
            $('#sub_division').append($('<option>', {value: '', text: '-- Select --'}));
            if (division) {
                $.get('/project_heads/get_sd_code/' + division, function (sd_codes) {
                    $.each(sd_codes, function (i, code) {
                        // console.log(code);
                        var option_text = code.sd_code + ' , ' + code.sub_division;
                        $('#sub_division').append($('<option>', {value: code.sd_code, text: option_text}));
                    });
                    fetchAndPopulateFeederCodes(division, sub_division, section);
                });
            }
        }).trigger('change'); // Trigger the change event on page load

        $('#sub_division').change(function () {
            var sub_division = $(this).val();
            var division = null;
            var section = null;
            if ($('#division').val() != '') division = $('#division').val();
            if ($('#section').val() != '') section = $('#section').val();

            $('#section').empty();
            $('#section').append($('<option>', {value: '', text: '-- Select --'}));
            if (sub_division) {
                $.get('/project_heads/get_so_code/' + sub_division, function (so_codes) {
                    $.each(so_codes, function (i, code) {
                        // console.log(code);
                        var option_text = code.so_code + ' , ' + code.section_office;
                        $('#section').append($('<option>', {value: code.so_code, text: option_text}));
                    });
                });
                fetchAndPopulateFeederCodes(division, sub_division, section);
            }
        }).trigger('change'); // Trigger the change event on page load

        $('#section').change(function () {
            var division = null
            var sub_division = null;
            var section = $(this).val();
            if ($('#division').val() != '') division = $('#division').val();
            if ($('#sub_division').val() != '') sub_division = $('#sub_division').val();
            if (section) {
                fetchAndPopulateFeederCodes(division, sub_division, section);
            }
        }).trigger('change'); // Trigger the change event on page load

        $('#report_type').change(function () {
            var division = $(this).val();
            var dailyRadio = document.querySelector('#daily');
            var weeklyRadio = document.querySelector('#weekly');
            var monthlyRadio = document.querySelector('#monthly');
            console.log(division);
            if (division == "6") {
                $('#daily').prop('checked', false);
                $('#weekly').prop('checked', false);
                $('#monthly').prop('checked', false);
                $('#daily').prop('disabled', true);
                $('#weekly').prop('disabled', true);
                $('#monthly').prop('disabled', true);
            } else {
                $('#daily').prop('disabled', false);
                $('#weekly').prop('disabled', false);
                $('#monthly').prop('disabled', false);
            }
        }).trigger('change'); // Trigger the change event on page load

        if (($('#section').val() == "" || $('#section').val() == undefined) && ($('#sub_division2').val() == "" || $('#sub_division2').val() == undefined) && ($('#section2').val() == "" || $('#section2').val() == undefined)) {
            fetchAndPopulateFeederCodes();
        }
    });

</script>
{{-- aee --}}
<script>

    $(document).ready(function () {
        function fetchAndPopulateSections(division) {
            $('#section2').empty();
            $('#section2').append($('<option>', {value: '', text: '-- Select --'}));
            if (division) {
                $.get('/project_heads/get_so_code/' + division, function (so_codes) {
                    $.each(so_codes, function (i, code) {
                        var option_text = code.so_code + ' , ' + code.section_office;
                        $('#section2').append($('<option>', {value: code.so_code, text: option_text}));
                    });
                });
                fetchAndPopulateFeederCodes(null, division, null);
            }
        }

        // Trigger the function on page load
        fetchAndPopulateSections($('#sub_division2').val());

        // Attach the change event handler
        $('#sub_division2').change(function () {
            var division2 = null;
            var section2 = null;
            var sub_division2 = $(this).val();
            if ($('#section2').val() != '') section2 = $('#section2').val();
            fetchAndPopulateSections(sub_division2);
            if (sub_division2) {
                console.log('hrere 2');
                fetchAndPopulateFeederCodes(division2, sub_division2, section2);
            }
        });

        $('#section2').change(function () {
            var division2 = null;
            var sub_division2 = null;
            var section2 = $(this).val();
            if ($('#sub_division2').val() != '') sub_division2 = $('#sub_division2').val();
            if (section2) {
                console.log('hrere 3');
                fetchAndPopulateFeederCodes(division2, sub_division2, section2);
            }
        }).trigger('change'); // Trigger the change event on page load
    });

</script>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        var dailyRadio = document.querySelector('#daily');
        var weeklyRadio = document.querySelector('#weekly');
        var monthlyRadio = document.querySelector('#monthly');
        var customRadio = document.querySelector('#custom');
        var customDiv = document.querySelector('#custom_date');

        dailyRadio.addEventListener('change', function () {
            customDiv.style.display = 'none';
            document.getElementById('start_date').removeAttribute('required');
            document.getElementById('end_date').removeAttribute('required');
        });

        weeklyRadio.addEventListener('change', function () {
            customDiv.style.display = 'none';
            document.getElementById('start_date').removeAttribute('required');
            document.getElementById('end_date').removeAttribute('required');
        });

        monthlyRadio.addEventListener('change', function () {
            customDiv.style.display = 'none';
            document.getElementById('start_date').removeAttribute('required');
            document.getElementById('end_date').removeAttribute('required');
        });

        customRadio.addEventListener('change', function () {
            customDiv.style.display = '';
            document.getElementById('start_date').setAttribute('required', 'required');
            document.getElementById('end_date').setAttribute('required', 'required');
        });
        if ($('#custom').is(':checked')) {
            customDiv.style.display = '';
        }
    });


</script>

<script>
    document.getElementById("myForm").addEventListener("submit", function (event) {
        const radioButtons = document.querySelectorAll('input[name="format"]');
        let atLeastOneSelected = false;

        radioButtons.forEach(function (radioButton) {
            if (radioButton.checked) {
                atLeastOneSelected = true;
            }
        });

        if (!atLeastOneSelected) {
            alert("Please select one date format.");
            event.preventDefault();
        } else {
            var Form = document.getElementById('myForm');
            if (Form.checkValidity() == false) {
                var list = Form.querySelectorAll(':invalid');
                for (var item of list) {
                    item.focus();
                }
            } else {
                Swal.fire({icon: 'info', title: 'Please Wait', showConfirmButton: false, timer: 50000})
            }
        }
    });

    // document.getElementById("filter_submit").addEventListener("click", function(event){
    //     document.getElementById('filter_submit').disabled = true;
    // });

    // $("#filter_submit").one('click', function (event) {
    //     Swal.fire({icon: 'info',title: 'Please Wait',showConfirmButton: false,timer: 30000})
    // });

    // var Form = document.getElementById('FormID');
    // if (Form.checkValidity() == false) {
    //     var list = Form.querySelectorAll(':invalid');
    //     for (var item of list) {
    //         item.focus();
    //     }
    // }
    // else{
    //     Swal.fire({icon: 'info',title: 'Please Wait',showConfirmButton: false,timer: 30000})
    // }
</script>

