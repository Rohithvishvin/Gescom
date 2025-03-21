@include("inc_admin.header")

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>
    .btn-primary {
        --vz-btn-bg: #3480ff;
    }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- start page title -->

            <div class="row">
                <div class="col-12">

                    <div class="page-title-box d-sm-flex align-items-center justify-content-between  mt-1">
                        <h4 class="mb-sm-0">Faulty Meter Report</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">Faulty Meter</a></li>
                                <li class="breadcrumb-item active">Report</li>
                            </ol>
                        </div>

                    </div>
       

                </div>
              
                <div class="row">

                    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                      <div class="card ongoing-project recent-orders">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Total no. of records: {{$data['count']}}  </h4>

                        </div>
                        <br>
                        <div class="card-body pt-0">
                    
                          <div class="table-responsive">
                            <table class="table cell-border compact strip" id="myTable" data-order='[[ 0, "asc" ]]' data-page-length='10'>
                              <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Faulty Meter Serial Number</th>
                                    <th>Replaced Serial Number</th>
                                    <th>User</th>
                                    <th>Account id</th>
                                    <th>status id</th>
                                    <th>Reason for Faulty</th>
                                    <th>Created at</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                              </thead>
                              <tbody>
                                @php
                                    $flag = 1;
                                @endphp
                                @foreach ($data['successful_records'] as $successful_record)
                                <tr>
                                    <td>{{ $flag }}</td>
                                    <td>{{ $successful_record->faulty_meter_serial_number }}</td>
                                    <td>{{ $successful_record->replaced_serial_number ?: 'N/A' }}</td> {{-- Display "N/A" if empty --}}
                                    <td>{{ $data['admin_names'][$successful_record->userid] ?? 'Unknown' }}</td> {{-- Display admin name --}}
                                    <td>{{ $successful_record->account_id ?: 'N/A' }}</td> {{-- Display "N/A" if empty --}}
                                    <td>{{ $successful_record->status_id }}</td>
                                    <td>{{ $successful_record->reason }}</td>
                                    <td>{{ $successful_record->created_at }}</td>
                                    {{-- <td><a href="/bmrs/successfull_report_single/{{$successful_record->account_id}}" class="btn btn-secondary">View</a></td> --}}
                                </tr>
                                @php
                                    $flag++;
                                @endphp
                                @endforeach




                              </tbody>
                            </table>
                          </div>
                        
                        </div>
                      </div>
                    </div>

            </div>

            <!-- end page title -->



        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


</div>


{{-- @include("inc_admin.footer") --}}


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    $(document).ready(function() {
      $('#myTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
          'excelHtml5',
          'pdfHtml5'
        ]
      });
    });


  </script>

<?php if(!empty(session()->get('failed'))) { ?>
  <script type="text/javascript">
  Swal.fire({
   icon: 'warning',
  // title: '{{ session()->get('failed') }}',
   showConfirmButton: false,
   timer: 5000
 })
  </script>
 <?php } session()->forget('failed'); ?>


 <?php if(!empty(session()->get('success'))) { ?>
	<script type="text/javascript">
	Swal.fire({
	 icon: 'success',
	// title: '{{ session()->get('success') }}',
	 showConfirmButton: false,
	 timer: 5000,

   })
	</script>
   <?php } session()->forget('success'); ?>


