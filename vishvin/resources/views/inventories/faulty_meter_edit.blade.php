@include('inc_admin.header')

@php

    use App\Models\Zone_code;
    use App\Models\Admin;

    $meter_main = $data['meter_main'];
    $consumer_detail = $data['consumer_detail'];
    use App\Models\Contractor_inventory;

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
                                                        <a href="javascript: void(0);">Admin</a>
                                                    </li>
                                                    <li class="breadcrumb-item active">Replace faulty Meter Serial Number</li>
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
                                                    <h4 class="card-title mb-0 flex-grow-1">Serial Number Details</h4>
                                                </div>
                                            <div class="card-body pt-0">
                                                <form id="search-fault-serial-id-form" autocomplete="off" class="needs-validation" action="/inventories/check_faulty_meter" method="POST"  enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                        <div class="col-4">
                                                            <label class="form-label" for="product-title-input">Check your meter serial Number is Faulty ?
                                                                <span class="mandatory_star">
                                                                *<sup><i>required</i></sup>
                                                            </span>
                                                            </label>
                                                        <input type="text" class="form-control" id="userInput" name="userInput" placeholder="Enter data">                              
                                                        </div>

                                                        <div class="col-4">
                                                            <button type="submit" class="btn btn-success w-sm">Search</button>
                                                        </div>
                                                    </div>
                                                </form>

                                                </div>                        
                                            
                                        </div>
                                    
                                                            
                                    </div>
                                </div>
                            <!-- container-fluid -->



                      

                            @if(session()->has('failed'))

    <div class="error-message">
        {{ session('failed') }}
    </div>
    <script>
        alert("{{ session('failed') }}");
    </script>
@endif

                    <form id="createproduct-form" autocomplete="off" class="needs-validation"
                        action="/inventories/update_faulty_meter/{{ $meter_main->id }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="mb-3 mt-5">
                                            <label for="orderId" class="form-label">Account Id </label>
                                            <input type="text" id="orderId" class="form-control" placeholder="ID"
                                                value="{{ $meter_main->account_id }}" readonly />
                                        </div>

                                        <!-- <div class="mb-3">
                                            <label class="form-label" for="product-title-input">Project Name</label>
                                            <input type="number" class="form-control" id="product-title-input" name="phno" value="" placeholder="Enter Project Name" required>
                                        </div> -->
                                        <div class="mb-3">
                                            <label for="tasksTitle-field" class="form-label">RR Number </label>
                                            <input type="text" id="tasksTitle-field" class="form-control" placeholder="Title"
                                                value="{{ $consumer_detail->rr_no }}" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="client_nameName-field" class="form-label">Name of the consumer </label>
                                            <input type="text" id="client_nameName-field" class="form-control"
                                                value="{{ $consumer_detail->consumer_name }}" placeholder="Consumer Name"
                                                readonly />
                                        </div>

                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Section </label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $consumer_detail->section }}" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Subdivision </label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $consumer_detail->sub_division }}" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Meter Type </label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="<?php if ($consumer_detail->meter_type == 1) {
                                                    echo 'Single Phase';
                                                } else {
                                                    echo 'Three Phase';
                                                } ?>" readonly>

                                        </div>
                                 





                                    </div>
                                </div>
                                <!-- end card -->




                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        Electromechanical</div>
                                    <div class="card-body">

                                        <div class="mb-3 mt-0">
                                            <label for="ticket-status" class="form-label">Photo 1 (with reading) @if (!empty($meter_main->image_1_old))
                                                    <a href="{{ asset($meter_main->image_1_old) }}" target="_blank"> <i
                                                            class="fa fa-eye"></i></a>
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                @endif
                                            </label>
                                            <input type="file" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $meter_main->image_1_old }}"
                                                name="image_1_old" />
                                        </div>


                                        <div class="mb-3">
                                            <label for="ticket-status" class="form-label">Photo 2 (with reading)
                                                @if (!empty($meter_main->image_2_old))
                                                    <a href="{{ asset($meter_main->image_2_old) }}" target="_blank"> <i
                                                            class="fa fa-eye"></i></a>
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                @endif
                                            </label>
                                            <input type="file" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $meter_main->image_2_old }}"
                                                name="image_2_old" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="ticket-status" class="form-label">Photo 3 (with reading) @if (!empty($meter_main->image_3_old))
                                                    <a href="{{ asset($meter_main->image_3_old) }}" target="_blank"> <i
                                                            class="fa fa-eye"></i></a>
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                @endif
                                            </label>
                                            <input type="file" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $meter_main->image_3_old }}"
                                                name="image_3_old" />
                                        </div>

                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Meter Make</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Meter Make" value="{{ $meter_main->meter_make_old }}"
                                                name="meter_make_old" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Meter Serial No</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Meter Serial No" value="{{ $meter_main->serial_no_old }}"
                                                name="serial_no_old" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Year of Manufacture</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Year of Manufacture" value="{{ $meter_main->mfd_year_old }}"
                                                name="mfd_year_old" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Final Reading
                                                (FR)-kWh</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Meter Make" value="{{ $meter_main->final_reading }}"
                                                name="final_reading" readonly />
                                        </div>

                                    </div>
                                </div>
                                <!-- end card -->




                            </div>
                            <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        Electrostatic</div>
                                    <div class="card-body">

                                        <div class="mb-3 mt-0">
                                            <label for="ticket-status" class="form-label">Photo 1 (with reading) @if (!empty($meter_main->image_1_new))
                                                    <a href="{{ asset($meter_main->image_1_new) }}" target="_blank"> <i
                                                            class="fa fa-eye"></i></a>
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                @endif
                                            </label>
                                            <input type="file" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $meter_main->image_1_new }}"
                                                name="image_1_new" />
                                        </div>


                                        <div class="mb-3">
                                            <label for="ticket-status" class="form-label">Photo 2 (with reading) @if (!empty($meter_main->image_2_new))
                                                    <a href="{{ asset($meter_main->image_2_new) }}" target="_blank"> <i
                                                            class="fa fa-eye"></i></a>
                                                @else
                                                    <i class="fa fa-eye-slash"></i>
                                                @endif
                                            </label>
                                            <input type="file" id="assignedtoName-field" class="form-control"
                                                placeholder="Section" value="{{ $meter_main->image_2_new }}"
                                                name="image_2_new" />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Meter Make</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="GENUS OVERSEAS ELECTRONICS LTD"
                                                value="GENUS OVERSEAS ELECTRONICS LTD" readonly />
                                        </div>

                     

                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Year of Manufacture</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Year of Manufacture" value="{{ $meter_main->mfd_year_new }}"
                                                name="mfd_year_new"  readonly/>
                                        </div>
                                        {{-- <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Final Reading
                                                (FR)-kWh</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Meter Make" value="{{ $meter_main->initial_reading_kwh }}"
                                                name="initial_reading_kwh" />
                                        </div> --}}
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Initial Reading g
                                                (IR)-kWh</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Meter Make" value="{{ $meter_main->initial_reading_kwh }}"
                                                name="initial_reading_kwh" readonly />
                                        </div>
                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">Initial Reading g
                                                (IR)-kVAh</label>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                placeholder="Meter Make" value="{{ $meter_main->initial_reading_kvah }}"
                                                name="initial_reading_kvah" readonly />
                                        </div>

                                        <div class="mb-3">
                                            <label for="assignedtoName-field" class="form-label">New Meter Serial No</label>
                                            <span class="mandatory_star">
                                            *<sup><i>required</i></sup>
                                            <input type="text" id="assignedtoName-field" class="form-control"
                                                            placeholder="Meter Serial No" value="{{ $meter_main->serial_no_new }}"
                                                            name="serial_no_new" required />
                                        </div>
                                        <div class="col-4">
                                        <label class="form-label" for="product-title-input">Reason For Pushing this serial Number as Faulty
                                            <span class="mandatory_star">
                                            *<sup><i>required</i></sup>
                                        </span>
                                        </label>
                                        <textarea rows="10" cols="118" id="reason" name="reason" placeholder="Reason for Replacement of New Serial Number" required></textarea>
                                         <br>                    
                                    </div>
                                    </div>
                                </div>
                                <!-- end card -->



                                <div class="text-end mb-3">
                                    <button type="submit" class="btn btn-success w-sm">Submit</button>

                                </div>
                            </div>
                            <!-- end col -->


                        </div>
                        <!-- end row -->

                    </form>
                    <a href="/inventories/edit_faulty_meter/{{ $meter_main->id }}"> <button
                        class="btn btn-primary w-sm">Cancel</button></a>
                </div>
        <!-- container-fluid -->
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
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5'
            ]
        });
    });
</script>

