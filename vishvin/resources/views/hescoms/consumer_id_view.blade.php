@include('inc_admin.header')

<?php
$meter_main = $data['meter_main'];
$consumer_detail = $data['consumer_detail'];
$admin = $data['zone_info'];
use App\Models\Admin;
use App\Models\Contractor_inventory;
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Consumer Details update</h4>                                                           
                    </div>
                </div>
            </div>
        </div>

        <!-- Form to search Account ID -->
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ url('/') }}/hescoms/consumer_accounts_search">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label" for="account_id_input">Account ID<span class="mandatory_star">*</span></label>
                            <input type="text" class="form-control" name="account_id" id="account_id_input" placeholder="Enter Account ID" required>
                        </div>
                        <div class="col-lg-4">                                                               
                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        </div>
                    </div>
                </form>
                <br>
            </div>
        </div>
        <br>

        <!-- Display Account Details and Update Form -->
        <div class="row">
            <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                <div class="card ongoing-project recent-orders">
                    <div class="card-header border-0 align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Account Details</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <form id="updateForm" action="{{ url('/hescoms/consumer_details_update/' . $consumer_detail->id . '/') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <table class="table table-striped">
                                    <tr>
                         
           

                                        <th scope="row">Account id</th>
                                        <td>{{ $consumer_detail->account_id ?? 'N/A' }}</td>

                                        <th scope="row">RR Number</th>
                                        <td>{{ $consumer_detail->rr_no ?? 'N/A' }}</td>

                                        <th scope="row">meter Type</th>
                                        <td>{{ $consumer_detail->phase_type ?? 'N/A' }}</td>
                                               
                                    
                              
                                    </tr>
                                    <tr>
                                        
                                    <th scope="row">Division</th>
                                        <td>{{ $admin->division ?? 'N/A' }}</td>
                                        
                                        <th scope="row">sub_division</th>
                                        <td>{{ $admin->sub_division ?? 'N/A' }}</td>
                                        
                                        <th scope="row">section_office</th>
                                        <td>{{ $admin->section_office ?? 'N/A' }}</td>
                                        </tr>
                              
                                    <tr>
                                    <th scope="row">Consumer Name</th>
                                        <td>
                                        {{ $consumer_detail->consumer_name ?? '' }}
                                        </td>
                                        <th scope="row">Consumer Address</th>
                                        <td>
                                        {{ $consumer_detail->consumer_address ?? ''}}
                                        </td>
                                        <th scope="row">Meter Type Change</th>
                                        <td>
                                        <select id="meter_type" name="meter_type" onchange="updatePhaseType(this)">
                                                        <option value="1" {{ $consumer_detail->meter_type == 1 ? 'selected' : '' }}>Single Phase</option>
                                                        <option value="2" {{ $consumer_detail->meter_type == 2 ? 'selected' : '' }}>Three Phase</option>
                                         </select>

                                <!-- Hidden input to store the phase type -->
                                <input type="hidden" id="phase_type" name="phase_type" value="{{ $consumer_detail->phase_type ?? 'N/A' }}">

                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <button type="button" class="btn btn-primary w-sm mt-4" onclick="document.getElementById('updateForm').submit();">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('inc_admin.footer')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/responsive/1.12.1/js/dataTables.responsive.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: ['excelHtml5']
        });
    });
</script>

<script>
    function updatePhaseType(selectElement) {
        const phaseTypeInput = document.getElementById('phase_type');

        if (selectElement.value == "1") {
            phaseTypeInput.value = "Single Phase";
        } else if (selectElement.value == "2") {
            phaseTypeInput.value = "Three Phase";
        }
    }
</script>


@if(session('success'))
    <div class="alert alert-success mt-4" id="alert">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function(){
            document.getElementById('alert').style.display = 'none';
        }, 5000); // Hide alert after 5 seconds
    </script>
@endif
