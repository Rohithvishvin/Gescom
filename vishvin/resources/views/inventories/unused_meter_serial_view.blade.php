
@include('inc_admin.header')


@php
use Illuminate\Support\Facades\Session;
    use App\Models\Zone_code;
    use App\Models\Admin;


    $meter_main = $data['meter_main'] ?? [];
    $meter_main = $data['contractory_inventory'] ?? [];
    $meter_main = $data['consumer_details'] ?? [];
    $meter_main = $data['Bmr_success'] ?? [];
    $meter_main = $data['meter_main'] ?? [];
    $admin = $data['admins'] ?? [];
$contractory_inventory = $data['contractory_inventory'] ?? [];
$consumer_details = $data['consumer_details'] ?? [];
$bmr_success = $data['Bmr_success'] ?? [];


@endphp


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Reports</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                                <li class="breadcrumb-item active">Replace Faulty Meter Serial Number</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Title -->

            <div class="row">
    <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
        <div class="card ongoing-project recent-orders">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Serial Number Details</h4>
            </div>
            <div class="card-body pt-0">
                <form id="search-fault-serial-id-form" autocomplete="off" class="needs-validation" 
                      action="/inventories/Push_serial_number_unused" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4">
                            <label class="form-label" for="userInput">Would you like to push the serial number unused state
                                <span class="mandatory_star">*</span>
                            </label>
                            <input type="text" class="form-control" id="userInput" name="userInput" placeholder="Enter data">
                        </div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-success w-sm">Search</button>
                        </div>
                    </div>
                </form>
                <div class="mt-1" id="searchResult"></div>
            </div>

            <!-- Display Meter Details -->
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <form id="updateForm" 
                          action="/inventories/update_faulty_meter-unused" method="POST" enctype="multipart/form-data">
                        @csrf
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>User Meter Serial Number</th>
                                    <td>{{ $meter_main['serial_no_new'] ?? 'Not installed' }}</td>
                                </tr>
                                <tr>
                                    <th>Account ID</th>
                                    <td>{{ $meter_main['account_id'] ?? 'Not installed' }}</td>
                                </tr>
                                <tr>
                                    <th>Meter Serial Number Status</th>
                                    <td>{{ $contractory_inventory['contractory_inventory_status'] ?? 'Not installed' }}</td>
                                </tr>
                                <tr>
                                <th>Contractor name</th>
                                <td>{{ $admin['name'] ?? 'Contractor name not found' }}</td>
                                    </tr>
                                <tr>
                                    <th>Consumer Name</th>
                                    <td>{{ $consumer_details['consumer_name'] ?? 'Not installed' }}</td>
                                </tr>
                                <tr>
                                    <th>Consumer Address</th>
                                    <td>{{ $consumer_details['consumer_address'] ?? 'Not installed' }}</td>
                                </tr>
                            
                                <tr>    
                              
                                <th hidden>   Would you Like to push the serial number unused ?</th>
                                     
                                
                                </tr>

                            </tbody>
                        </table>
                                                            </form>
                                                            @if ($meter_main['account_id'] == null && $contractory_inventory['contractory_inventory_status'] =='used' )
                                                            <form action="/inventories/Push_serial_unused_state/{{ $contractory_inventory['id'] }}/{{ $meter_main['serial_no_new'] }}" method="POST">
                                        @csrf
                                        <button class="btn btn-primary w-sm mt-4" type="submit">
                                          push to unused
                                        </button>
                                        
                                    </form>
@endif






                     

                 
                </div>
            </div>
        </div>
    </div>
                </div>

        </div>
    </div>
</div>


@include('inc_admin.footer')

<!-- Scripts -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#search-fault-serial-id-form').on('submit', function (e) {
            const userInput = $('#userInput').val().trim();
            if (userInput === '') {
                e.preventDefault();
                alert('Please enter the serial number.');
            }
        });
    });
</script>