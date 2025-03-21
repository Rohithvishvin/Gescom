@include("inc_pages.header")
<?php
$consumer_detail = $data['get_consumer_detail'];
$meter_main_id = $data['id'];
$meter_main = $data['meter_main'];
?>
    <!-- CSS -->
{{--
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-YWNzIkpAATugcUIGC6p0L6UoCZmH+Y9XJ1zrQ2b05BldyUivn+U6FJn6EkGxstCZ" crossorigin="anonymous">


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-hJPkMy86Rz8ozJwBgNpPcNTeFdYZvR8M5WZWhLzYJX9/3vzq3F/4h29mCtaIfFZZ" crossorigin="anonymous"></script> --}}
{{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">--}}
{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>--}}

{{-- this disabled the anchor tag, as in this page we cant allow the user to go back and forth while adding or replacing the meters --}}
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
    }

    .modal-dialog {
    }

    .modal-content {


        background-color: #BBD6EC;

    }

    .modal-header {

        background-color: #337AB7;

        /* padding:16px 16px; */

        color: #FFF;

        border-bottom: 2px dashed #337AB7;

    }

</style>
{{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css">--}}
<body class="fixed-bottom-padding">
<div>
    <nav class="navbar fixed-top navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <h6 class="fw-bold m-0 ms-3">
                    Details of Electromechanical Meter
                </h6>
            </div>
        </div>
    </nav>
    <div class="container col-sm-6" style="margin-top:20px;padding-left:50px;padding-right:50px;margin-bottom:50px;">
    <form action="/Survey/update_old_meter_detail/{{$meter_main_id}}" method="post" autocomplete="off"
      enctype="multipart/form-data" id="oldMeterDetail">
    @csrf
    <!-- Consumer Details -->
    <div class="mb-3">
        <label for="account_id" class="form-label">Account Id</label>
        <input type="text" id="account_id" class="form-control" value="{{$consumer_detail->account_id}}"
               readonly disabled>
    </div>
    <div class="mb-3">
        <label for="rr_number" class="form-label">RR Number</label>
        <input type="text" id="rr_number" class="form-control" value="{{$consumer_detail->rr_no}}"
               readonly disabled>
    </div>
    <div class="mb-3">
        <label for="consumer_name" class="form-label">Name of the Consumer</label>
        <input id="consumer_name" type="text" class="form-control"
               value="{{$consumer_detail->consumer_name}}" readonly disabled>
    </div>
    <div class="mb-3">
        <label for="consumer_address" class="form-label">Consumer Address</label>
        <textarea id="consumer_address" class="form-control" rows="3" readonly disabled>{{$consumer_detail->consumer_address}}</textarea>
    </div>
    <div class="mb-3">
        <label for="section" class="form-label">Section</label>
        <input id="section" type="text" class="form-control"
               value="{{$consumer_detail->so_pincode}}" readonly disabled>
    </div>
    <div class="mb-3">
        <label for="sub_section" class="form-label">Subdivision</label>
        <input id="sub_section" type="text" class="form-control"
               value="{{$consumer_detail->sd_pincode}}" readonly disabled>
    </div>

    <!-- Phase Type -->
    <div class="mb-3 row">
        <div class="col-6">
            <button class="btn btn-secondary" type="button" disabled>
                @if($consumer_detail->meter_type==1) Single Phase @else Three Phase @endif
            </button>
        </div>
        <div class="col-6">
            <button class="btn btn-secondary" type="button" disabled>
                Electromechanical
            </button>
        </div>
    </div>

    <div class="mb-3">
                <label for="image_1_old_temp" class="form-label">Photo 1 with readings on display <span
                        class="mandatory_star">*<sup><i>required</i></sup></span></label>
                @if(!empty($meter_main->image_1_old))
                    <img src="{{asset($meter_main->image_1_old)}}" alt="photo1" style="height:47px;width:100%;">
                    <div style="display: none;">
                        <input type="hidden" name="image_1_old" value="{{$meter_main->image_1_old}}">
                    </div>
                @endif
                @if(empty($meter_main->image_1_old))
                    <input type="file" class="form-control" id="image_1_old_temp" data-refID="image_1_old"
                           accept="image/*" @if(empty($meter_main->image_1_old)) required @endif>
                    <div style="display: none;">
                        <input type="file" id="image_1_old" name="image_1_old" accept="image/*">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="image_2_old_temp" class="form-label">Photo 2 with readings on display <span
                        class="mandatory_star">*<sup><i>required</i></sup></span></label>
                @if(!empty($meter_main->image_2_old))
                    <img src="{{asset($meter_main->image_2_old)}}" alt="photo2" style="height:47px;width:100%;">
                    <div style="display: none;">
                        <input type="hidden" name="image_2_old" value="{{$meter_main->image_2_old}}">
                    </div>
                @endif
                @if(empty($meter_main->image_2_old))
                    <input type="file" class="form-control" id="image_2_old_temp" data-refID="image_2_old"
                           accept="image/*" @if(empty($meter_main->image_2_old)) required @endif>
                    <div style="display: none;">
                        <input type="file" id="image_2_old" name="image_2_old" accept="image/*">
                    </div>
                @endif
            </div>
            <div class="mb-3" style="display: none;">
                <label for="image_3_old_temp" class="form-label">Photo 3 with readings on display (optional)</label>
                @if(!empty($meter_main->image_3_old))
                    <img src="{{asset($meter_main->image_3_old)}}" alt="photo2" style="height:47px;width:100%;">
                    <div style="display: none;">
                        <input type="hidden" name="image_3_old" value="{{$meter_main->image_3_old}}">
                    </div>
                @endif
                @if(empty($meter_main->image_3_old))
                    <input type="file" class="form-control" id="image_3_old_temp"
                           accept="image/*" @if(empty($meter_main->image_3_old))  @endif>
                    <input type="hidden" name="image_3_old">
                @endif
            </div>




    <!-- Old Meter Details -->
    <div class="mb-3">
        <label for="meter_make" class="form-label">Meter Make <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
        <input type="text" class="form-control" id="meter_make" name="meter_make_old"
               value="{{$meter_main->meter_make_old ?? ''}}" placeholder="Enter Meter Make" required>
    </div>
    <div class="mb-3">
        <label for="serial_no" class="form-label">Meter Serial No <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
        <input type="number" id="serial_no" class="form-control" name="serial_no_old"
               value="{{$meter_main->serial_no_old ?? ''}}" placeholder="Enter Serial Number" required>
    </div>
    <div class="mb-3">
        <label for="mfd_year" class="form-label">Year Of Manufacture <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
        <input type="number" id="mfd_year" class="form-control" name="mfd_year_old"
               value="{{$meter_main->mfd_year_old ?? ''}}" placeholder="Enter Manufacture Year" required>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between">
        <button class="btn btn-primary w-sm" onclick="submitForm()" type="button">Next</button>
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Cancel</button>
    </div>
</form>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure you want to cancel ?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            <a href="/Survey/add_meter_first_step" class="">
                <button type="button" class="btn btn-primary" style="width:100%;background-color:chocolate;">Yes
                </button>
            </a>
        </div>
    </div>
</div>


<div class="modal" id="meterReadingMessage" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Are you sure you want to proceed ?</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 id="modal-message" style="color:red"></h2>
                <h2 id="modal-difference-message" style="color:red"></h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        onclick="closeMeterReadingMessageModal('final_reading')">No
                </button>
                <button type="button" class="btn btn-primary" onclick="checkFormValidationResult('skip_check')">Yes
                </button>
            </div>
        </div>
    </div>
</div>


{{--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>--}}

<script type="text/javascript" src="/assets_page/js/image-resize.js"></script>

@include("inc_pages.suveryfooter")

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty(session()->get('failed'))) { ?>
<script type="text/javascript">
    Swal.fire({
        icon: 'warning',
        title: '{{ session()->get('failed') }}',
        showConfirmButton: false,
        timer: 5000
    })
</script>
<?php } session()->forget('failed'); ?>


<?php if (!empty(session()->get('success'))) { ?>
<script type="text/javascript">
    Swal.fire({
        icon: 'success',
        title: '{{ session()->get('success') }}',
        showConfirmButton: false,
        timer: 5000,

    })
</script>
<?php } session()->forget('success'); ?>

@php if(!empty(session()->get('validation_errors'))) {
	  //foreach (session()->get('validation_errors') as $errorKey=>$errorValue) {
@endphp
<script type="text/javascript">
    Swal.fire({
        icon: 'warning',
        title: 'Validation Error',
        //text: '',
        showConfirmButton: false,
        timer: 10000,
    })
</script>
@php //}
    }
    session()->forget('validation_errors');
@endphp

<script>
    // Function to submit the form with hardcoded geolocation values
    function submitForm() {
        const form = document.getElementById('oldMeterDetail');

        // Hardcoded latitude and longitude (your provided values)
        const latitude = 12.9328852;  // Latitude
        const longitude = 77.5422609; // Longitude

        // Set the latitude and longitude in the form's hidden fields
        const latitudeInput = document.createElement('input');
        latitudeInput.type = 'hidden';
        latitudeInput.name = 'latitude'; // Hidden input for latitude
        latitudeInput.value = latitude;
        form.appendChild(latitudeInput);

        const longitudeInput = document.createElement('input');
        longitudeInput.type = 'hidden';
        longitudeInput.name = 'longitude'; // Hidden input for longitude
        longitudeInput.value = longitude;
        form.appendChild(longitudeInput);

        // Now submit the form
        form.submit();
    }

    // Call the submitForm function when the form is submitted
    document.getElementById('oldMeterDetail').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        submitForm();
    });
</script>

