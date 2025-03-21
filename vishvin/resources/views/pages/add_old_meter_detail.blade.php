@include("inc_pages.header")
<?php
$consumer_detail = $data['get_consumer_detail'];
$meter_main_id = $data['id'];
$meter_main = $data['meter_main'];
$meter_previous_final_reading = $data['meter_previous_final_reading'];
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
        <form action="/pages/update_old_meter_detail/{{$meter_main_id}}" method="post" autocomplete="off"
              enctype="multipart/form-data" id="oldMeterDetail">
            @csrf
            <div class="mb-3">
                <label for="account_id" class="form-label">Account Id</label>
                <input type="text" id="account_id" class="form-control" value="{{$consumer_detail->account_id}}"
                       placeholder="Enter Account Id" readonly disabled>
            </div>
            <div class="mb-3">
                <label for="rr_number" class="form-label">RR Number</label>
                <input type="text" id="rr_number" class="form-control" value="{{$consumer_detail->rr_no}}"
                       placeholder="Enter RR Number" readonly disabled>
            </div>
            <div class="mb-3">
                <label for="consumer_name" class="form-label">Name of the Consumer</label>
                <input id="consumer_name" type="text" class="form-control" placeholder="Enter Consumer Name"
                       value="{{$consumer_detail->consumer_name}}" readonly disabled>
            </div>
            <div class="mb-3">
                <label for="consumer_address" class="form-label">Consumer Address</label>
                {{-- <input type="text" class="form-control"   value="{{$consumer_detail->consumer_address}}" placeholder="Enter Consumer Address" readonly disabled> --}}
                <textarea id="consumer_address" class="form-control" rows="3" placeholder="Enter Consumer Address"
                          readonly disabled>{{$consumer_detail->consumer_address}}</textarea>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input id="section" type="text" class="form-control" placeholder="Enter Section"
                       value="{{$consumer_detail->so_pincode}}" readonly disabled>
            </div>
            <div class="mb-3">
                <label for="sub_section" class="form-label">Subdivision</label>
                <input id="sub_section" type="text" class="form-control" placeholder="Enter Subdivision"
                       value="{{$consumer_detail->sd_pincode}}" readonly disabled>
            </div>
            <div class="mb-3 row">
                <div class="dropdown col-6">
                    {{-- <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> --}}
                    <button class="btn btn-secondary " type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if($consumer_detail->meter_type==1)
                            Single Phase
                        @else
                            Three Phase
                        @endif
                    </button>
                </div>
                <div class="dropdown col-6">
                    <button class="btn btn-secondary" disabled data-bs-toggle="dropdown" aria-expanded="false">
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


            <div class="mb-3">
                <label for="meter_make" class="form-label">Meter Make <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                <input type="text" class="form-control" id="meter_make" name="meter_make_old"
                       value="<?php if(!empty($meter_main->meter_make_old)){echo ($meter_main->meter_make_old); }?>"
                       placeholder="Enter Meter Make" aria-describedby="textHelp" required>
            </div>

            <div class="mb-3">
                <label for="serial_no" class="form-label">Meter Serial No <span
                        class="mandatory_star">*<sup><i>required</i></sup></span></label>
                <input type="number" id="serial_no" class="form-control" name="serial_no_old"
                       value="<?php if(!empty($meter_main->serial_no_old)){echo ($meter_main->serial_no_old); }?>"
                       placeholder="Enter Serial Number" aria-describedby="textHelp" required>
            </div>

            <div class="mb-3">
                <label for="mfd_year" class="form-label">Year Of Manufacture <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                <input type="number" id="mfd_year" class="form-control" name="mfd_year_old"
                       value="<?php if(!empty($meter_main->mfd_year_old)){echo ($meter_main->mfd_year_old); }?>"
                       placeholder="Enter Manufacture Year" aria-describedby="textHelp" required>
            </div>
            @if(!empty($meter_previous_final_reading))
                <div class="mb-3">
                    <label for="prev_fr_reading" class="form-label">Previous Meter Reading
                        on {{ $meter_previous_final_reading->billed_date }}</label>
                    <input id="prev_fr_reading" type="text" class="form-control" placeholder="Enter Subdivision"
                           value="{{ $meter_previous_final_reading->reading??0 }}" readonly disabled>
                </div>
            @endif
            <div class="mb-3">
                <label for="final_reading" class="form-label">Final Reading (FR)-kWh <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                <input type="number" id="final_reading" class="form-control" name="final_reading"
                       value="<?php if(!empty($meter_main->final_reading)){echo ($meter_main->final_reading); }?>"
                       placeholder="Enter Final Reading" aria-describedby="textHelp" required>
            </div>


            <!-- LOCATION AND DATE SHOULD BE AUTO FETCHED -->

            {{-- <button type="submit" class="btn btn-primary">Next</button> --}}
            <button class="btn btn-primary w-sm"
                    onclick="checkFormValidationResult()"
                    class="btn btn-success btn-sm" type="button">Next
            </button>
            <button type="button" class="btn btn-warning" style="float:right;" data-bs-toggle="modal"
                    data-bs-target="#exampleModalCenter">
                Cancel
            </button>
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
            <a href="/pages/add_meter_first_step" class="">
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

@include("inc_pages.footer")

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
    let getMeterReadingMessageModalId = document.getElementById('meterReadingMessage');
    let getMeterReadingMessageModalInstance = bootstrap.Modal.getOrCreateInstance(getMeterReadingMessageModalId);

    function checkFormValidationResult(skip_check = '') {

        var previous_meter_reading_db = 0;
        var previous_meter_reading_ele = document.getElementById('prev_fr_reading');
        var meter_final_reading_value = document.getElementById('final_reading').value;
        if (previous_meter_reading_ele && skip_check === '') {
            previous_meter_reading_db = document.getElementById('prev_fr_reading').value;
            console.log(previous_meter_reading_db);
            if (previous_meter_reading_db >= 0) {
                let difference = meter_final_reading_value - previous_meter_reading_db;
                console.log(difference);
                if (meter_final_reading_value > previous_meter_reading_db) {
                    if (difference < 100) {
                        //document.getElementById('modal-message').innerText = "Entered FR Reading is Greater than Previous Meter Reading from database";
                        //getMeterReadingMessageModalInstance.show();
                        console.log('proceed further');
                        var Form = document.getElementById('oldMeterDetail');
                        if (Form.checkValidity() == false) {
                            var list = Form.querySelectorAll(':invalid');
                            for (var item of list) {
                                item.focus();
                            }
                        } else {
                            Swal.fire({icon: 'info', title: 'Please Wait', showConfirmButton: false, timer: 50000})
                            document.getElementById("oldMeterDetail").submit();
                        }
                    } else {
                        document.getElementById('modal-message').innerText = "Entered FR Reading is greater than Previous Meter Reading from database";
                        document.getElementById('modal-difference-message').innerText = "Difference in Meter Reading : " + difference;
                        getMeterReadingMessageModalInstance.show();
                    }
                } else {
                    document.getElementById('modal-message').innerText = "Entered FR Reading is less than Previous Meter Reading from database";
                    document.getElementById('modal-difference-message').innerText = "Difference in Meter Reading : " + difference;
                    //let myModal =new bootstrap.Modal('#meterReadingMessage');
                    //$('#meterReadingMessage').modal('show');
                    getMeterReadingMessageModalInstance.show();
                    //Swal.fire({icon: 'info', title: 'Entered FR Reading is less than Previous Meter Reading from database', showConfirmButton: false, timer: 5000})
                }
            }
        } else {
            var Form = document.getElementById('oldMeterDetail');
            if (Form.checkValidity() == false) {
                var list = Form.querySelectorAll(':invalid');
                for (var item of list) {
                    item.focus();
                }
            } else {
                Swal.fire({icon: 'info', title: 'Please Wait', showConfirmButton: false, timer: 50000})
                document.getElementById("oldMeterDetail").submit();
            }
        }

    }

    function closeMeterReadingMessageModal(elementId = '') {
        if (elementId !== '') {
            console.log('focus : ' + elementId);
            document.getElementById(elementId).focus();
        }
        console.log('close modal');
        getMeterReadingMessageModalInstance.hide();
    }
</script>
