@include('inc_admin.header')


@php
use Illuminate\Support\Facades\Session;
    use App\Models\Zone_code;
    use App\Models\Admin;


    $meter_main = $data['meter_main'] ?? null;
    $consumer_detail = $data['consumer_detail'] ?? null;
    $successful_record = $data['successful_record'] ?? null;
    $error_records = $data['error_records'] ?? collect(); // Make sure error_records is initialized as a collection
    $account_data = $data['account_id'] ?? null;

    //dd($account_data);

@endphp

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

                                        <div class="row">
                                                <div class="col-12">
                                                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                                                <h4 class="mb-sm-0">Vishvin Qc Push</h4>                                                           
                                                        </div>
                                                </div>
                                        </div>


            </div>
            <!-- end page title -->
                       


                                    <div class="row">
                                        <div class="col-12">
                                                    <form method="POST" action="{{url('/')}}/hescoms/push_to_vishvin_search">
                                                        @csrf
                                                        <div class="row">
                                                        
                                                        <div class="col-4">
                                                                <label class="form-label" for="product-title-input">Account Id
                                                                    <span class="mandatory_star">*</span>
                                                                </label>
                                                                <input type="text" class="form-control" name="account_id"  placeholder="Enter Account Id" required>
                                                                
                                                            </div>

    
                                                    
                                                                <div class="col-lg-4">                                                               
                                                                    <button type="submit" class="btn btn-primary mt-4">Submit</button>
                                                                </div>
                                                    </form>
                                                    
                                                       
                                        </div>
                                        <br>
                                    </div>
                                    <br>

            <div class="row">
                <div class="col-xl-12 col-md-12 dash-xl-100 dash-lg-100 dash-39">
                      

                        <div class="card ongoing-project recent-orders">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Vishvin QC push account details</h4>
                        </div>
                        <div class="card-body pt-0">
                            @inject('carbon', 'Carbon\Carbon')
                            <div class="table-responsive">


                            <form autocomplete="off" class="needs-validation" id="updateForm" action="/qc/sp_id_update/{{$consumer_detail->id}}" method="POST" enctype="multipart/form-data">
                             @csrf

                         
                                <table class="table table-striped">
                                @if ($meter_main !== null && isset($meter_main->id))                                 
                                 <input id="id" name="id" value={{$meter_main->id}} hidden />                
                                 @endif   


                                    <tr>

                              
                                                     <th scope="row">Account Id</th>
                                  
                                                    <td>
                                                        @if (!empty($meter_main->account_id))
                                                   
                                                            {{ $meter_main->account_id }}
                                                        @else
                                                            <p>N/A</p>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <!-- Input box for searching -->
                                                        <input type="text" id="accountSearch" class="form-control" placeholder="Search Account ID" oninput="fetchAccountIds()">

                                                        <!-- Dropdown for selecting account IDs -->
                                                        <select name="account_id" id="account_id" class="form-control" onchange="updateAccountId()">
                                                            <option value="">Search Account ID</option>
                                                            <!-- Options will be populated by AJAX -->
                                                        </select>

                                                        @if (!empty($meter_main->account_id))
                                                            <p style="display:none">Search Account ID: {{ $meter_main->account_id }}</p>
                                                        @else
                                                            <p>N/A</p>
                                                        @endif
                                                    </td>


                                    <th scope="row">new Meter Serial Number </th>
                                        <td>
                                        @if (!empty($meter_main->serial_no_new))
                                        {{$meter_main->serial_no_new}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                        </td>

                             
                                    

                                       <th scope="row">RR Number</th>
                                        <td>
                                        @if (!empty($consumer_detail->rr_no))
                                        {{$consumer_detail->rr_no}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                       </td>

                                    
                                     
                                        <th scope="row">Serial No New</th>
                                        <td>  
                                        @if (!empty($meter_main->serial_no_new))
                                        {{$meter_main->serial_no_new}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                       </td>

                                      
                                     
                                    </tr>
                                    <tr>    
                                        <th scope="row">Old Image</th>
                                        <td>
                                                    @if (!empty($meter_main->image_1_old))
                                                        <a href="javascript:void(0);"
                                                           onclick="window.open('{{ asset($meter_main->image_1_old) }}', '_blank', 'width=800,height=600');">
                                                            <img src="{{ asset($meter_main->image_1_old) }}"
                                                                 alt="photo1" style="height: 47px; max-width: 100%;">
                                                        </a>
                                                    @else
                                                        {{-- <i class="fa fa-eye-slash"></i> --}}
                                                        <p>No image</p>
                                                    @endif
                                        </td>
                                        <th scope="row">New Image</th>
                                        <td>
                                                    @if (!empty($meter_main->image_1_new))
                                                        <a href="javascript:void(0);"
                                                           onclick="window.open('{{ asset($meter_main->image_1_new) }}', '_blank', 'width=800,height=600');">
                                                            <img src="{{ asset($meter_main->image_1_new) }}"
                                                                 alt="photo1" style="height: 47px; max-width: 100%;">
                                                        </a>
                                                    @else
                                                        {{-- <i class="fa fa-eye-slash"></i> --}}
                                                        <p>No image</p>
                                                    @endif
                                         </td>
                                                <th scope="row">old image 2</th>
                                                <td>
                                                    @if (!empty($meter_main->image_2_old))
                                                        <a href="javascript:void(0);"
                                                           onclick="window.open('{{ asset($meter_main->image_2_old) }}', '_blank', 'width=800,height=600');">
                                                            <img src="{{ asset($meter_main->image_2_old) }}"
                                                                 alt="photo1" style="height: 47px; max-width: 100%;">
                                                        </a>
                                                    @else
                                                        {{-- <i class="fa fa-eye-slash"></i> --}}
                                                        <p>No image</p>
                                                    @endif
                                                </td>

                                       <th scope="row">FR Reading</th>
                                        <td>
                                        @if (!empty($meter_main->final_reading))
                                        {{$meter_main->final_reading}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                       </td>


                                      </tr>
                                      <tr>
                                     
                                                <th scope="row">New image 2</th>
                                                <td>
                                                    @if (!empty($meter_main->image_2_new))
                                                        <a href="javascript:void(0);"
                                                           onclick="window.open('{{ asset($meter_main->image_2_new) }}', '_blank', 'width=800,height=600');">
                                                            <img src="{{ asset($meter_main->image_2_new) }}"
                                                                 alt="photo1" style="height: 47px; max-width: 100%;">
                                                        </a>
                                                    @else
                                                        {{-- <i class="fa fa-eye-slash"></i> --}}
                                                        <p>No image</p>
                                                    @endif
                                                </td>

                                                <th scope="row">Consumer name</th>
                                                <td>
                                                @if ($consumer_detail !== null && isset($consumer_detail->consumer_name))                                 
                                               <span id="consumer_name" name="consumer_name" readonly> {{$consumer_detail->consumer_name}}</span>               
                                                   @endif   
                                                </td>
                                    </tr>

                                    <tr>
                                    <th scope="row">Vishvin QC Status</th>
                                            <td>
                                                @if (!empty($meter_main->qc_status) && $meter_main->qc_status == 1)
                                                    Done
                                                @else
                                                    Pending
                                                @endif
                                            </td>
                                  

                                       <th scope="row">Vishvin SO  Status</th>
                                            <td>
                                                @if (!empty($meter_main->so_status) && $meter_main->so_status == 1)
                                                    Done
                                                @else
                                                    Pending
                                                @endif
                                            </td>
                                 

                                       <th scope="row">Vishvin AEE  Status</th>
                                            <td>
                                                @if (!empty($meter_main->aee_status) && $meter_main->aee_status == 1)
                                                    Done
                                                @else
                                                    Pending
                                                @endif
                                            </td>
                                   

                                       <th scope="row">Vishvin AAO  Status</th>
                                            <td>
                                                @if (!empty($meter_main->aao_status) && $meter_main->aao_status == 1)
                                                    Done
                                                @else
                                                    Pending
                                                @endif
                                            </td>
                                       </tr>


                                       <tr>
                                       <th scope="row">download flag</th>
                                       <td>
                                        @if (!empty($meter_main->download_flag))
                                        {{$meter_main->download_flag}}
                                        @else
                                        <p> 0 </p>
                                        @endif
                                       </td>
                                        </th>
                                     


                               
                                        <th scope="row">account is in BMR Success</th>
                                        <td>
                                        @if (!empty($successful_record->account_id))
                                        {{$meter_main->account_id}}
                                        @else
                                        <p>N/A</p>
                                        @endif

                                        <th scope="row"> BMR Success update at</th>
                                        <td>
                                        @if (!empty($successful_record->updated_at))
                                        {{$successful_record->updated_at}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                       </td>

                                       <th scope="row">Account was in Error</th>
                                        <td>
                                            @if ($error_records->isNotEmpty()) <!-- Ensure there are error records -->
                                                <p>{{ $error_records->first()->account_id }}</p> <!-- Display the first account_id -->
                                            @else
                                                <p>N/A</p> <!-- Show N/A if no error records exist -->
                                            @endif
                                        </td>



                                       
                                        <th scope="row">Account Error Update At</th>
                                        <td>
                                                <select class="form-control" readonly>
                                                    @if ($error_records->isNotEmpty()) <!-- Ensure there are error records -->
                                                        @foreach ($error_records as $error_record)
                                                            <option>{{ $error_record->updated_at }}</option> <!-- Display updated_at of each error record -->
                                                        @endforeach
                                                    @else
                                                        <option selected>N/A</option> <!-- Show N/A if no error records exist -->
                                                    @endif
                                                </select>
                                            </td>

                                            <th scope="row">Justification by AAO</th>
                                                <td>
                                                    <select class="form-control" readonly>
                                                        @if ($error_records->isNotEmpty()) <!-- Ensure there are error records -->
                                                            @foreach ($error_records as $error_record)
                                                                <option>{{ $error_record->justification_by_aao }}</option> <!-- Display justification_by_aao of each error record -->
                                                            @endforeach
                                                        @else
                                                            <option selected>N/A</option> <!-- Show N/A if no error records exist -->
                                                        @endif
                                                    </select>
                                                </td>


                                    
                                            </tr>
                                                                                                                            
                                                            
                                </table>

                                


                                </form>
                    <?php 

                   // print_r($meter_main)
                    
                    
                    ?>
                                                
                                                <td>
                            <!-- Blade Form to handle Push to Vishvin QC and update qc_status to 0 -->
                            <form autocomplete="off" class="needs-validation" id="pushToVishvinQCForm" action="/push_to_vishvin_qc_account_status/{{ $meter_main->id }}" method="POST" enctype="multipart/form-data">
                            @csrf <!-- CSRF protection -->

                            <!-- Hidden inputs to set statuses to 0 (Pending) -->
                            <input type="hidden" name="qc_status" value="0">
                            <input type="hidden" name="so_status" value="0">
                            <input type="hidden" name="aee_status" value="0">
                            <input type="hidden" name="aao_status" value="0">
                            <input type="hidden" name="download_flag" value="0">
                            <input type="text" class="form-control" name="account_id" id="account_id_input" placeholder="Enter Account Id" value="{{ $meter_main->account_id }}" required hidden>
                                                          

                                <!-- Submit button to push qc_status to Pending (0) -->
                                <button type="submit" class="btn btn-secondary w-sm mt-4">
                                    <i class="align-bottom me-1 mt-4"></i>Push to Vishvin QC (Set to Pending)
                                </button>
                            </form>
                        </td>


                                <button type="button" class="btn btn-primary w-sm mt-4" onclick="submitForm()" hidden>
                                    <i class="align-bottom me-1 mt-4"></i>Update
                                </button>

                              

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
   /* 
   $(document).ready(function () {
        $('#search-fault-serial-id-form').on('submit', function (e) {
            var replacedSerialNumber = $('#new_meter_serial_no').val();
            if (replacedSerialNumber.trim() === '') {
                e.preventDefault(); // Prevent form submission if the Replaced Meter Serial Number is empty
                alert('Please enter the Replaced Meter Serial Number.');
            }
            var userInput = $('#userInput').val();
            var foundMatch = false;
            $('#serial_no option').each(function () {
                if ($(this).val() === userInput) {
                    foundMatch = true;
                    return false; // Stop looping if a match is found
                }
            });
            if (!foundMatch) {
                e.preventDefault(); // Prevent form submission if no fault number is found
                alert('No Fault meter serial number found with this Number.');
            }
        });

        $('#userInput').on('input', function () {
            var userInput = $(this).val();
            if (userInput.trim() !== '') {
                var foundMatch = false;
                $('#serial_no option').each(function () {
                    if ($(this).val() === userInput) {
                        $('#searchResult').html('Fault meter serial found with this Number, Replace with New Meter serial Number');
                        $('#account-details').show(); // Show the account details container
                        foundMatch = true;
                        $('#searchResult').removeClass('no-match').addClass('match'); // Add 'match' class
                        return false; // Stop looping if a match is found
                    }
                });
                if (!foundMatch) {
                    $('#searchResult').html('No Fault meter serial number found with this Number');
                    $('#account-details').hide(); // Hide the account details container
                    $('#searchResult').removeClass('match').addClass('no-match'); // Add 'no-match' class
                }
            } else {
                $('#searchResult').empty().removeClass('match no-match'); // Clear the result and remove classes if input is empty
                $('#account-details').hide(); // Hide the account details container if input is empty
            }
        });
    });
    */
</script>

@if(session('success'))
    <div class="alert alert-success mt-4" id="alert">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(function(){
            document.getElementById('alert').style.display = 'none';
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
@endif


<script>
    function submitForm()
     {
     
       document.getElementById("updateForm").submit();

    }


</script>

<script>
    function submitForm1()
     {
     
       document.getElementById("pushToVishvinQCForm").submit();

    }


</script>

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


<!----<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<script>
    // Function to debounce the fetch of account IDs
    let debounceTimer;

    function debouncedFetchAccountIds() {
        clearTimeout(debounceTimer); // Clear the previous timer

        debounceTimer = setTimeout(function() {
            fetchAccountIds(); // Call the actual fetch function after the user has stopped typing
        }, 300); // Wait for 300ms after the last keystroke
    }

    function fetchAccountIds() {
        var searchValue = $('#accountSearch').val(); // Get the input value

                        // Ensure we only send requests for non-empty search values and at least 7 characters long
                if (searchValue.length >= 7) {
                    $.ajax({
                        url: '/account/search',
                        type: 'GET',
                        data: { query: searchValue }, // Send the search term
                        success: function(data) {
                            console.log(data);
                            $('#account_id').empty(); // Clear previous options
                            $('#account_id').append('<option value="">Select Account ID</option>'); // Reset to default option

                            // Populate dropdown with returned account IDs
                            $.each(data, function(index, accountId) {
                                $('#account_id').append('<option value="' + accountId + '">' + accountId + '</option>');
                            });
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText); // Handle any errors
                            alert('The Entered account id is Already Exist');
                        }
                    });
                } else if (searchValue.length > 10 && searchValue.length < 0) {
                    // If the search value is less than 7 characters but not empty, raise an alert
                    alert('The account ID must be at least 10 characters long. Please enter a valid account ID.');
                    
                    // Clear dropdown as well in case of invalid input
                    $('#account_id').empty();
                    $('#account_id').append('<option value="">Select Account ID</option>'); // Reset to default option
                } else {
                    // If the input is cleared or empty, reset the dropdown
                    $('#account_id').empty();
                    $('#account_id').append('<option value="">Select Account ID</option>'); // Reset to default option
                }

    }

    function updateAccountId() {
        var dropdown = document.getElementById('account_id');
        var selectedValue = dropdown.value;

        // Set the read-only input to the selected account ID
        document.getElementById('account_id_input').value = selectedValue; 
       // console.log(selectedValue);

        // Optional: Log the selected value for debugging
        console.log("Selected Account ID: " + selectedValue);
    }
</script>

