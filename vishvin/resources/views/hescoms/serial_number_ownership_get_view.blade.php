@include('inc_admin.header')


@php
use Illuminate\Support\Facades\Session;
    use App\Models\Zone_code;
    use App\Models\Admin;


    $meter_main = $data['meter_main'];
    $consumer_detail = $data['consumer_detail'];
    $contractor_inventories = $data['contractor_inventories'];

@endphp

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

                                        <div class="row">
                                                <div class="col-12">
                                                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                                                <h4 class="mb-sm-0">Meter Serial No. ownership Takeover</h4>                                                           
                                                        </div>
                                                </div>
                                        </div>


            </div>
            <!-- end page title -->
                       


                                    <div class="row">
                                        <div class="col-12">
                                                    <form method="POST" action="{{ url('/') }}/hescoms/serial_no_search">
                                                        @csrf
                                                        <div class="row">
                                                        
                                                        <div class="col-4">
                                                                <label class="form-label" for="product-title-input">Account Id
                                                                    <span class="mandatory_star">*</span>
                                                                </label>
                                                                <input type="text" class="form-control" name="serial_no" id="serial_no" placeholder="search serial_no" required>
                                                                
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
                                        <h4 class="card-title mb-0 flex-grow-1">Account Details</h4>
                                    </div>
                                    <div class="card-body pt-0">
                                        @inject('carbon', 'Carbon\Carbon')
                                        <div class="table-responsive">
                                            <form autocomplete="off" class="needs-validation" id="updateForm" action="/meter_ownership/serial_update/{{$contractor_inventories->id}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <table class="table table-striped">
                                                    @if ($meter_main !== null && isset($meter_main->id))                                 
                                                        <input id="id" name="id" value={{$meter_main->id}} hidden />                
                                                    @endif   

                                                    <tr>
                                                        <th scope="row">New Meter Serial Number</th>
                                                        <td>
                                                            @if (!empty($meter_main->serial_no_new))
                                                                {{$meter_main->serial_no_new}}
                                                            @else
                                                                <p>N/A</p>
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

                                                        <th scope="row">Account Id</th>
                                                        <td>
                                                            @if (!empty($meter_main->account_id))
                                                                {{$meter_main->account_id}}
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

                                                       
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">Old Image</th>
                                                        <td>
                                                            @if (!empty($meter_main->image_1_old))
                                                                <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_1_old) }}', '_blank', 'width=800,height=600');">
                                                                    <img src="{{ asset($meter_main->image_1_old) }}" alt="photo1" style="height: 47px; max-width: 100%;">
                                                                </a>
                                                            @else
                                                                <p>No image</p>
                                                            @endif
                                                        </td>
                                                        <th scope="row">New Image</th>
                                                        <td>
                                                            @if (!empty($meter_main->image_1_new))
                                                                <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_1_new) }}', '_blank', 'width=800,height=600');">
                                                                    <img src="{{ asset($meter_main->image_1_new) }}" alt="photo1" style="height: 47px; max-width: 100%;">
                                                                </a>
                                                            @else
                                                                <p>No image</p>
                                                            @endif
                                                        </td>
                                                        <th scope="row">Old Image 2</th>
                                                        <td>
                                                            @if (!empty($meter_main->image_2_old))
                                                                <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_2_old) }}', '_blank', 'width=800,height=600');">
                                                                    <img src="{{ asset($meter_main->image_2_old) }}" alt="photo1" style="height: 47px; max-width: 100%;">
                                                                </a>
                                                            @else
                                                                <p>No image</p>
                                                            @endif
                                                        </td>
                                                        <th scope="row">New Image 2</th>
                                                        <td>
                                                            @if (!empty($meter_main->image_2_new))
                                                                <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_2_new) }}', '_blank', 'width=800,height=600');">
                                                                    <img src="{{ asset($meter_main->image_2_new) }}" alt="photo1" style="height: 47px; max-width: 100%;">
                                                                </a>
                                                            @else
                                                                <p>No image</p>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th scope="row">Contractor Name</th>
                                                        <td>
                                                            @if (!empty($data['contractor_name']))
                                                                {{$data['contractor_name']}}
                                                            @else
                                                                <p>N/A</p>
                                                            @endif
                                                        </td>

                                                  

                                                     

                                                      

                                                        <th scope="row">Meter Type</th>
                                                        <td>
                                                            @if (!empty($contractor_inventories->meter_type))
                                                                {{$contractor_inventories->meter_type}}
                                                            @else
                                                                <p>N/A</p>
                                                            @endif
                                                        </td>

                                                        <th scope="row">Box ID</th>
                                                        <td>
                                                            @if (!empty($contractor_inventories->box_id))
                                                                {{$contractor_inventories->box_id}}
                                                            @else
                                                                <p>N/A</p>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                    <th scope="row">Serial No Unused</th>
                                                        <td>
                                                            @if (!empty($contractor_inventories->unused_meter_serial_no))
                                                                {{$contractor_inventories->unused_meter_serial_no}}
                                                            @else
                                                                <p>N/A</p>
                                                            @endif
                                                        </td>
                                                        </tr>


                                                        

                                                        <tr>
                                                            <th scope="row">Serial No</th>
                                                        <td>
                                                            @if (!empty($contractor_inventories->serial_no))
                                                                {{$contractor_inventories->serial_no}}
                                                            @else
                                                                <p>N/A</p>
                                                            @endif
                                                        </td>
                                                                </tr>


                                                                

                                                        <tr>
                                                        <th scope="row">Serial No Used</th>
                                                        <td>
                                                            @if (!empty($contractor_inventories->used_meter_serial_no))
                                                                {{$contractor_inventories->used_meter_serial_no}}
                                                            @else
                                                                <p>N/A</p>
                                                            @endif
                                                        </td>
                                                            </tr>



                                                    <tr>
                            <th scope="row">Choose Meter Serial </th>
                            <td id="checkboxContainer" style="width: 100%;">
                                <!-- Checkboxes will be generated here -->
                            </td>
                            <td>
                                <!-- Hidden input to store selected serial numbers -->
                                <input type="hidden" name="unused_meter_serial_no" id="unused_meter_serial_no" value="">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Selected Serial Numbers:</th>
                            <td colspan="3" id="selectedSerialsDisplay">None</td> <!-- Display selected serials here -->
                        </tr>
                                                </table>
                                            </form>

                                            <button type="button" id="updateButton" class="btn btn-primary w-sm mt-4" onclick="submitForm()" disabled>
                        <i class="align-bottom me-1 mt-4"></i>Update
                    </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                                    </div>
                                              
                           

                                    <script>
                                                document.addEventListener('DOMContentLoaded', function() {
    const updateButton = document.getElementById('updateButton');
    const checkboxContainer = document.getElementById('checkboxContainer');
    const hiddenInput = document.getElementById('unused_meter_serial_no');
    const selectedSerialsDisplay = document.getElementById('selectedSerialsDisplay');

    // Fetch data from JSON API using the contractor_inventories ID
    fetch(`/meter_ownership/user_serial/{{ $contractor_inventories->id }}`)
        .then(response => response.json())
        .then(data => {
            checkboxContainer.innerHTML = ''; // Clear any existing checkboxes

            // Check if the response contains the unused meter serial numbers
            if (data.unused_meter_serial_no) {
                const serialNumbers = data.unused_meter_serial_no.split(',');

                // Create a checkbox for each serial number
                serialNumbers.forEach(serialNo => {
                    const checkboxWrapper = document.createElement('div');
                    const checkbox = document.createElement('input');
                    const label = document.createElement('label');

                    checkbox.type = 'checkbox';
                    checkbox.value = serialNo.trim(); // Use trimmed value
                    checkbox.id = `serial-${serialNo.trim()}`; // Unique ID for each checkbox
                    checkbox.classList.add('serial-checkbox'); // Add a class to identify checkboxes

                    label.htmlFor = checkbox.id; // Associate label with checkbox
                    label.textContent = serialNo.trim(); // Display the serial number

                    // Add event listener for the checkbox
                    checkbox.addEventListener('change', function() {
                        updateSelectedSerials(); // Update on checkbox change
                    });

                    // Append checkbox and label to the wrapper
                    checkboxWrapper.appendChild(checkbox);
                    checkboxWrapper.appendChild(label);

                    // Append the wrapper to the checkbox container
                    checkboxContainer.appendChild(checkboxWrapper);
                });

                // Enable the update button if there are checkboxes
                updateButton.disabled = false;
            } else {
                // Handle the case where no unused serial numbers were found
                const message = document.createElement('div');
                message.textContent = 'No unused serial numbers found!';
                checkboxContainer.appendChild(message);

                // Disable the button since no serial numbers are found
                updateButton.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            updateButton.disabled = true; // Keep button disabled in case of error
        });
});

function updateSelectedSerials() {
    const selectedSerials = [];
    const checkboxes = document.querySelectorAll('.serial-checkbox:checked'); // Only checked checkboxes

    // Loop through checked checkboxes and collect their values
    checkboxes.forEach(checkbox => {
        selectedSerials.push(checkbox.value);
    });

    // Set the value of the hidden input to the selected serials (comma-separated)
    document.getElementById('unused_meter_serial_no').value = selectedSerials.join(',');

    // Display the selected serial numbers in the designated <td>
    const selectedSerialsDisplay = document.getElementById('selectedSerialsDisplay');
    selectedSerialsDisplay.textContent = selectedSerials.length > 0 ? selectedSerials.join(', ') : 'None';
}

function submitForm() {
    const selectedSerials = [];
    const checkboxes = document.querySelectorAll('.serial-checkbox:checked'); // Only checked checkboxes

    // Loop through checked checkboxes and collect their values
    checkboxes.forEach(checkbox => {
        selectedSerials.push(checkbox.value);
    });

    // Set the value of the hidden input to the selected serials (comma-separated)
    document.getElementById('unused_meter_serial_no').value = selectedSerials.join(',');

    // Check if at least one serial number is selected before submitting
    if (selectedSerials.length > 0) {
        document.getElementById('updateForm').submit(); // Submit the form
    } else {
        alert("Please select at least one meter serial.");
    }
}

                              
                              </script>





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
    
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5'
            ]
        });
    });
</script>
