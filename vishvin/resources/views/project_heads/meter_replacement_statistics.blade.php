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
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Meter Replacement Statistics</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">Meter Replacement Statistics</a>
                                </li>
                                <li class="breadcrumb-item active">Statistics</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <form action="" method="get" id="meterReplacementStatistics">
                    <div class="col-4 sub">
                        <input type="radio" id="radioStatistics1" name="radioStatistics" value="daily">
                        <label class="me-lg-3" for="radioStatistics1">Daily</label>
                        <input type="radio" id="radioStatistics2" name="radioStatistics" value="weekly">
                        <label class="me-lg-3" for="radioStatistics2">Weekly</label>
                        <input type="radio" id="radioStatistics3" name="radioStatistics" value="monthly">
                        <label class="me-lg-3" for="radioStatistics3">Monthly</label>
                    </div>
                </form>
            </div>
            <div class="overlay">
                <div class="overlay__inner">
                    <div class="overlay__content"><span class="spinner"></span></div>
                </div>
            </div>
            <!-- end page title -->
            @if(!empty($data))
                <div class="row">
                    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                        @if($radioStatistics == "monthly")
                        <div class="card ongoing-project recent-orders" id="monthly">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Monthly</h4>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td>Sl. No.</td>
                                            <td>Package</td>
                                            <td>Division</td>
                                            <td>Div Code</td>
                                            <td>Sub Division</td>
                                            <td>SD Code</td>
                                            <td colspan="3">
                                                Total Awarded Quality
                                            </td>
                                            <td colspan="5">
                                                Monthly Progress
                                            </td>
                                            <td colspan="2">
                                                Cumilative Progress
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>1 Ph</td>
                                            <td>3 Ph</td>
                                            <td>Total</td>
                                            <td>1st Month</td>
                                            <td>2nd Month</td>
                                            <td>3rd Month</td>
                                            <td>4th Month</td>
                                            <td>5th Month</td>
                                            <td>Counts</td>
                                            <td>in %</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $count = 0; @endphp
                                        {{--                                    {{ dd($data['division_implement_monthly']) }}--}}
                                        @foreach($data['division_implement_monthly'] as $key=>$results)
                                            <tr>
                                                <td>{{++$count}}</td>
                                                <td>GESCOM</td>
                                                <td>{{$results['division']}}</td>
                                                <td>{{$results['div_code']}}</td>
                                                <td>{{$results['sub_division']}}</td>
                                                <td>{{$results['sd_code']}}</td>
                                                <td>{{$results['first_phase_meters']}}</td>
                                                <td>{{$results['third_phase_meters']}}</td>
                                                <td>{{$results['total_meters']}}</td>
                                                <td>{{$results['first']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['second']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['third']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['fourth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['fifth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['total_replaced_meters']}}</td>
                                                <td>{{$results['total_completion_percent']}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$data['grand_total_first_phase_meters_present']}}</td>
                                            <td>{{$data['grand_total_third_phase_meters_present']}}</td>
                                            <td>{{$data['grand_total_meters_present']}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$data['grand_total_meters_replaced']}}</td>
                                            <td>{{$data['grand_total_completion_percent']}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($radioStatistics == "weekly")
                        <div class="card ongoing-project recent-orders" id="weekly">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Weekly</h4>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td>Sl. No.</td>
                                            <td>Package</td>
                                            <td>Division</td>
                                            <td>Div Code</td>
                                            <td>Sub Division</td>
                                            <td>SD Code</td>
                                            <td colspan="3">
                                                Total Awarded Quality
                                            </td>
                                            <td colspan="5">
                                                Weekly Progress
                                            </td>
                                            <td colspan="2">
                                                Cumilative Progress
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>1 Ph</td>
                                            <td>3 Ph</td>
                                            <td>Total</td>
                                            <td>1st Week</td>
                                            <td>2nd Week</td>
                                            <td>3rd Week</td>
                                            <td>4th Week</td>
                                            <td>5th Week</td>
                                            <td>Counts</td>
                                            <td>in %</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $count = 0; @endphp
                                        {{--                                    {{ dd($data['division_implement_monthly']) }}--}}
                                        @foreach($data['division_implement_weekly'] as $key=>$results)
                                            <tr>
                                                <td>{{++$count}}</td>
                                                <td>GESCOM</td>
                                                <td>{{$results['division']}}</td>
                                                <td>{{$results['div_code']}}</td>
                                                <td>{{$results['sub_division']}}</td>
                                                <td>{{$results['sd_code']}}</td>
                                                <td>{{$results['first_phase_meters']}}</td>
                                                <td>{{$results['third_phase_meters']}}</td>
                                                <td>{{$results['total_meters']}}</td>
                                                <td>{{$results['first']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['second']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['third']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['fourth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['fifth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['total_replaced_meters']}}</td>
                                                <td>{{$results['total_completion_percent']}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$data['grand_total_first_phase_meters_present']}}</td>
                                            <td>{{$data['grand_total_third_phase_meters_present']}}</td>
                                            <td>{{$data['grand_total_meters_present']}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$data['grand_total_meters_replaced']}}</td>
                                            <td>{{$data['grand_total_completion_percent']}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($radioStatistics == "daily")
                        <div class="card ongoing-project recent-orders" id="daily">
                            <div class="card-header border-0 align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Daily</h4>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <td>Sl. No.</td>
                                            <td>Package</td>
                                            <td>Division</td>
                                            <td>Div Code</td>
                                            <td>Sub Division</td>
                                            <td>SD Code</td>
                                            <td colspan="3">
                                                Total Awarded Quality
                                            </td>
                                            <td colspan="7">
                                                Daily Progress
                                            </td>
                                            <td colspan="2">
                                                Cumilative Progress
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>1 Ph</td>
                                            <td>3 Ph</td>
                                            <td>Total</td>
                                            <td>1st Day</td>
                                            <td>2nd Day</td>
                                            <td>3rd Day</td>
                                            <td>4th Day</td>
                                            <td>5th Day</td>
                                            <td>6th Day</td>
                                            <td>7th Day</td>
                                            <td>Counts</td>
                                            <td>in %</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $count = 0; @endphp
                                        {{--                                    {{ dd($data['division_implement_monthly']) }}--}}
                                        @foreach($data['division_implement_daily'] as $key=>$results)
                                            <tr>
                                                <td>{{++$count}}</td>
                                                <td>GESCOM</td>
                                                <td>{{$results['division']}}</td>
                                                <td>{{$results['div_code']}}</td>
                                                <td>{{$results['sub_division']}}</td>
                                                <td>{{$results['sd_code']}}</td>
                                                <td>{{$results['first_phase_meters']}}</td>
                                                <td>{{$results['third_phase_meters']}}</td>
                                                <td>{{$results['total_meters']}}</td>
                                                <td>{{$results['first']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['second']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['third']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['fourth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['fifth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['sixth']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['seventh']['total_meter_replaced_count']}}</td>
                                                <td>{{$results['total_replaced_meters']}}</td>
                                                <td>{{$results['total_completion_percent']}}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$data['grand_total_first_phase_meters_present']}}</td>
                                            <td>{{$data['grand_total_third_phase_meters_present']}}</td>
                                            <td>{{$data['grand_total_meters_present']}}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$data['grand_total_meters_replaced']}}</td>
                                            <td>{{$data['grand_total_completion_percent']}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @endif
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
    //$(document).ready(function () {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        orthogonal: 'export'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    exportOptions: {
                        orthogonal: 'export'
                    }
                }
            ],
        });
        $(document).on('change', 'input[name = "radioStatistics"]', radioStatistics);

        //$(document).on('click', 'input[name = "radioStatistics"]', radioStatistics);

        function radioStatistics() {
            let requestType = this.value;
            $('.overlay').css("display", "block");
            $('#meterReplacementStatistics').submit();
            // $.ajax({
            //     type: 'GET',
            //     url: '/project_heads/meter_replacement_statistics_count',
            //     data: { type : requestType },
            //     datatype: 'json',
            //     success: function (resp) {
            //         console.log(resp);
            //         $('#'+ requestType).css('display', 'block');
            //         console.log($('#'+ requestType));
            //         if(requestType === "monthly"){
            //             $('#monthlyTableData').empty();
            //             $('#monthlyTableData').append('<tr>');
            //             resp.division_implement_monthly.forEach(function (e) {
            //                 var radioBtn = $('<tr type="radio" id="' + e.sd_code + '" name="subDivisionCode" value="' + e.sd_code + '"><label  class="me-lg-3" for="' + e.sd_code + '">' + e.sub_division + '</label>');
            //                 radioBtn.appendTo('#subDivision');
            //             });
            //             $('#monthlyTableData').append('</tr>');
            //         }
            //
            //         $('.overlay').css("display", "none")
            //         Swal.close();
            //     }
            // })
        }
    // });
</script>
