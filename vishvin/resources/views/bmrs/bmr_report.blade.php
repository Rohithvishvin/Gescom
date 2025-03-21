@include("inc_admin.header")
<?php
$approved_meters = $data['approved_meters'];
$bmr_downloads = $data['bmr_downloads'];
//$getAllaccountsnotexistsuccessError = $data['getAllaccountsnotexistsuccessError'];
$matchingRecords = $data['matchingRecords'];
$getAllaccounts_success = $data['getAllaccounts_success'];
?>
<link rel="stylesheet" type="text/css" href="assets_admin/css/vendors/datatables.css">
<style>
  .form-group {
margin-bottom: 1rem;
}

label {
display: block;
font-weight: bold;
}

input[type="date"] {
padding: 0.5rem;
border: 1px solid #ccc;
border-radius: 0.25rem;
font-size: 1rem;
width: 100%;
box-sizing: border-box;
}

button[type="submit"] {
padding: 0.5rem 1rem;
border: none;
border-radius: 0.25rem;
font-size: 1rem;
background-color: #007bff;
color: #fff;
cursor: pointer;
}

</style>
<div id="layout-wrapper">
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">BMR Records</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                    <li class="breadcrumb-item active">Basic Tables</li> -->
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                {{-- <div class="row container-fluid">
                  <div class="col-12">
                      <form method="GET" action="{{url('/')}}/bmrs/bmr_report">
                          @csrf
                          <div class="row mb-2">
                              <div class="col-lg-4 d-flex">
                                  <input type="radio" name="format" id="weekly" value="weekly">
                                  <label for="weekly" style="margin-bottom:0;">Weekly</label>
                              </div>
                              <div class="col-lg-4 d-flex">
                                  <input type="radio" name="format" id="monthly" value="monthly">
                                  <label for="monthly" style="margin-bottom:0;">Monthly</label>
                              </div>
                              <div class="col-lg-4 d-flex">
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
                          <div class="row mb-2">
                              <div class="col-lg-4">

                                  <button type="submit" class="btn btn-primary">Search</button>
                              </div>
                          </div>
                      </form>

                  </div>
              </div> --}}
  <!-- Container-fluid starts-->
  <?php
  $url =  "{$_SERVER['REQUEST_URI']}";
  $url = explode('/', $url);
  ?>
  <div class="container-fluid general-widget">
    <div class="row">


      <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
          <br>
          <div class="card-body pt-0">
            <form action="/bmrs/downloaded_batch" method="post">
              @csrf
            <div class="table-responsive">
              <table class="table cell-border compact strip" id="myTable" data-order='[[ 0, "desc" ]]' data-page-length='10'>
                <thead>
                  <tr>

                    <th></th>
                    <th>Sl. No.</th>
                    <th>SP Id</th>
                    <th>Account Id</th>
                    <th>SubDivision code</th>
                    <th>Meter Replacement date (DD-MM-YYYY)</th>
                    <th>Serial NO.</th>
                    <th>Manufacturer (Meter Make)</th>
                    <th>Meter Type</th>
                    <th>Year Of Manufacture</th>
                    <th>Is meter digital</th>
                    <th>Final Reading(KWH)</th>
                    <th>Final Reading(KVAH)</th>
                    <th>SR CODE</th>
                    <th>Serial no.</th>
                    <th>Manufacturer(Meter Make)</th>
                    <th>Meter Type</th>
                    <th>Year of Manufacture(YYYY)</th>
                    <th>Meter Model</th>
                    <th>Is meter digital</th>
                    <th>Number of Revolution Pulse</th>
                    <th>Meter constant</th>
                    <th>Multiplication Factor</th>
                    <th>Inital Reading(KWH)</th>
                    <th>Initial Reading(KVAH)</th>
                    <th>Number of Dials(total no of digits inclluding decimal)</th>
                    <th>Default Decimal(digits after Decimal)</th>
                    <th>Current Rating</th>
                    <th>Voltage(in Volts)</th>
                    <th>SR code</th>
                    {{-- <th class="sort text-uppercase" data-sort="serial_no">
                        Serial No</th>
                    <th class="sort text-uppercase" data-sort="updated_at">Last Finalized</th> --}}



                  </tr>

                </thead>
                <tbody>
                    <?php $count = 0; ?>
                    @foreach ($approved_meters as $approved_meter)
                    <?php $count++; ?>
                    <tr>
                        <td><input type="checkbox" name="selected_id[]" id="" value="{{$approved_meter->id}}" style="display: none" checked></td>
                        <td><?php echo $count; ?></td>
                        <td>{{$approved_meter->sp_id??''}}</td>
                        <td>{{$approved_meter->account_id}}</td>
                        <td>{{$approved_meter->sd_pincode??''}}</td>
                        <td>{{ date('d-m-Y', strtotime($approved_meter->created_at)) }}</td>
                        <td>{{$approved_meter->serial_no_old}}</td>
                        <td>{{!empty($approved_meter->meter_make_old) ? $approved_meter->meter_make_old : 'Null'}}</td>
                        <td>EM</td>
                        <td>{{$approved_meter->mfd_year_old}}</td>
                        <td>N</td>
                        <td>{{$approved_meter->final_reading}}</td>
                        <td>0</td>
                        <td>900001</td>
                        <td>{{!empty($approved_meter->serial_no_new) ? $approved_meter->serial_no_new : 'Null'}}</td>
                        <td>GENUS POWER INFRASTRUCTURE LTD</td>
                        <td>{{$approved_meter->meter_type??'' == 1 ? 'EDL' : 'EDL3'}}</td>
                        <td>2023</td>
                        <td>DLMS</td>
                        <td>Y</td>
                        <td>{{$approved_meter->meter_type??'' == 1 ? '3200' : '1200'}}</td>
                        <td>1</td>
                        <td>1</td>
                        <td>{{isset($approved_meter->initial_reading_kwh) ? $approved_meter->initial_reading_kwh : 'Null'}}</td>
                        <td>{{isset($approved_meter->initial_reading_kvah) ? $approved_meter->initial_reading_kvah : 'Null'}}</td>
                        <td>7</td>
                        <td>2</td>
                        <td>30</td>
                        <td>{{$approved_meter->meter_type??'' == 1 ? '230' : '400'}}</td>
                        <td>{{$approved_meter->meter_type??'' == 1 ? '357006' : '357531'}}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
            <div class="text-end mt-2" >
                <button type="submit">Generate Report</button>
            </div>

          </form>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="container-fluid general-widget">
    <div class="row">
        <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
            <div class="card ongoing-project recent-orders">
                <br>
                <div class="card-body pt-0">
                    <h4 class="mb-sm-0">Previous Downloads</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Created At</th>
                                <th>Action</th>
                                <th>Matching Records Not uploaded</th> <!-- New Column to display count -->
                             
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bmr_downloads as $bmr_download)
                                @php
                                    $matchCount = 0;  // Initialize match counter
                                @endphp
                                <tr>
                                    <td>{{ $bmr_download->id }}</td>
                                    <td>{{ $bmr_download->created_at }}</td>
                                    <td><a href="/bmrs/bmr_report_single/{{ $bmr_download->id }}">Show</a></td>

                                    @foreach ($matchingRecords as $record)
                                        @if ($bmr_download->id == $record->download_flag) <!-- Match condition -->
                                            @php
                                                $matchCount++;  // Increment the count when there's a match
                                            @endphp
                                        @endif
                                    @endforeach

                                    <!-- Display the match count in the hidden td -->
                                    <td hidden>{{ $matchCount }}</td>
                                    
                                    <!-- Status column for displaying text color based on match count -->
                                    <td>
                                        @if ($matchCount > 0)
                                            <a href="" style="color: red;">{{ $matchCount }} account(s) need to upload from BMR download</a>
                                        @else
                                            <a href="" style="color: green;">All accounts are uploaded</a>
                                        @endif
                                    </td>

                                    <td hidden><a href="" style="color: red;">Not uploaded accounts list</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Additional Section for Matching Records -->
                    <h4 class="mt-4">List of accounts id not uploaded from BMR download</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Account ID</th>
                                <th>RR No.</th>
                                <th>Consumer Name</th>
                                <th>Serial No. New</th>
                                <th>Updated By AAO</th>
                                <th>Created At</th>
                                <th>Download Flag</th>
                             
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matchingRecords as $index => $record)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $record->account_id }}</td>
                                    <td>{{ $record->rr_no }}</td>
                                    <td>{{ $record->consumer_name }}</td>
                                    <td>{{ $record->serial_no_new }}</td>
                                    <td>{{ $record->aao_updated_by_name }}</td>
                                    <td>{{ $record->created_at }}</td>
                                    <td>{{ $record->download_flag }}</td>
                                  
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




</div>
</div>
</div>
</div>

@include("inc_admin.footer")


<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

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
//   $(document).ready(function() {
//     $('#myTable').DataTable({
//       dom: 'Bfrtip',
//       buttons: [
//         'excelHtml5',
//         'pdfHtml5'
//       ]
//     });
//   });


//   $('input[type="checkbox"]').prop('checked', true);

//   $('#myTable').on('click', '.excelHtml5', function() {
//     $.ajax({
//         url: '/admins/download_excel',
//         type: 'POST',
//         data: {
//             _token: '{{ csrf_token() }}'
//         },
//         success: function(response) {
//             window.location.href = response.url;
//         },
//         error: function(xhr) {
//             console.log(xhr.responseText);
//         }
//     });
// });
</script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
    var weeklyRadio = document.querySelector('input[value="weekly"]');
    var monthlyRadio = document.querySelector('input[value="monthly"]');
    var customRadio = document.querySelector('input[value="custom"]');
    var customDiv = document.querySelector('#custom_date');

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
