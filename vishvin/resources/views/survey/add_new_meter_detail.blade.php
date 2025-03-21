@include('inc_pages.header')
<?php
	$consumer_detail = $data['get_consumer_detail'];
	$meter_main_id = $data['id'];
	$meter_main = $data['meter_main'];

// namespace App\Http\Controllers;
	use Illuminate\Support\Facades\Session;
	use App\Models\Admin;
	use App\Models\Contractor_inventory;

?>
<style>
    a.disabled {
        pointer-events: none;
        cursor: default;
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<body class="fixed-bottom-padding">
<div>
    <nav class="navbar fixed-top navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <h5 class="fw-bold m-0 ms-3">
                    Details of Electrostatic Meter
                </h5>
            </div>
        </div>
    </nav>
    <div class="container col-sm-6" style="margin-top:80px;padding-left:50px;padding-right:50px;margin-bottom:50px;">
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
        <form action="/Survey/update_new_meter_detail/{{ $meter_main_id }}" method="post" autocomplete="off"
              enctype="multipart/form-data" id="newMeterDetail">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">Photo 1 with readings on display <span
                            class="mandatory_star">*</span></label>
                @if(!empty($meter_main->image_1_new))
                    <img src="{{asset($meter_main->image_1_new)}}" alt="photo2" style="height:47px;width:100%;">
                    <div style="display: none;">
                        <input type="hidden" name="image_1_new" value="{{$meter_main->image_1_new}}">
                    </div>
                @endif
                @if(empty($meter_main->image_1_new))
                    <input type="file" class="form-control" id="image_1_new_temp" data-refID="image_1_new"
                           accept="image/*" @if(empty($meter_main->image_1_new)) required @endif>
                    <div class="mb-3" style="display: none;">
                        <input type="file" class="form-control" id="image_1_new" name="image_1_new" accept="image/*">
                    </div>
                @endif
                {{-- @if (empty($meter_main->image_1_new)) required @endif --}}
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Photo 2 with readings on display <span
                            class="mandatory_star">*</span></label>
                @if(!empty($meter_main->image_2_new))
                    <img src="{{asset($meter_main->image_2_new)}}" alt="photo2" style="height:47px;width:100%;">
                    <div style="display: none;">
                        <input type="hidden" name="image_2_new" value="{{$meter_main->image_2_new}}">
                    </div>
                @endif
                @if(empty($meter_main->image_2_new))
                    <input type="file" class="form-control" id="image_2_new_temp" data-refID="image_2_new"
                           accept="image/*" @if(empty($meter_main->image_2_new)) required @endif>
                    <div style="display: none;">
                        <input type="file" class="form-control" id="image_2_new" name="image_2_new" accept="image/*">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="serial_no" class="form-label">Meter Serial No</label>
                <select class="form-control" name="serial_no_new" required id="serial_no_new_dropdown" disabled>
                    <option value="" selected disabled readonly>--Select--</option>
                                        <?php
                    if (empty($meter_main->serial_no_new)){
                        $get_field_executive_contractor = Admin::where('id', session('rexkod_pages_id'))->first();
                        
                        // Apply default contractor_id of 117 if $get_field_executive_contractor->created_by is null or empty
                        $contractor_id = !empty($get_field_executive_contractor->created_by) ? $get_field_executive_contractor->created_by : 4;
                        
                        $contractor_inventories = Contractor_inventory::where('contractor_id', $contractor_id)->get();
                        
                        foreach ($contractor_inventories as $contractor_inventory) {
                            if ($contractor_inventory->meter_type == $consumer_detail->meter_type) {
                                $str = $contractor_inventory->unused_meter_serial_no;
                                
                                if ($str !== null && $str !== '') {
                                    $nums = explode(",", $str);
                                    foreach ($nums as $num) { ?>
                                        <option value="<?php echo $num; ?>"><?php echo $num; ?></option>
                                    <?php }
                                }
                            }
                        }
                    } else { ?>
                        <option value="<?php echo $meter_main->serial_no_new; ?>" selected><?php echo $meter_main->serial_no_new; ?></option>
                    <?php } ?>
                </select>
            </div>

            <script>
                const serialNoDropdown = document.getElementById('serial_no_new_dropdown');
                const options = serialNoDropdown.options;
                const filterInput = document.createElement('input');
                filterInput.type = 'number';
                filterInput.placeholder = 'Search...';
                filterInput.required = true;
                filterInput.maxLength = "7";
                filterInput.min = "0000000";
                filterInput.max = "9999999";
                serialNoDropdown.parentNode.insertBefore(filterInput, serialNoDropdown);

                filterInput.addEventListener('input', () => {
                    const filterValue = filterInput.value.toUpperCase();
                    const isSevenDigits = /^\d{7}$/.test(filterValue);
                    serialNoDropdown.disabled = !isSevenDigits;
                    for (let i = 0; i < options.length; i++) {
                        const optionValue = options[i].value.toUpperCase();
                        const containsFilterValue = optionValue.includes(filterValue);
                        options[i].style.display = containsFilterValue ? 'block' : 'none';
                    }
                });
            </script>


			<?php
				// if($meter_main->serial_no_new==$i){ echo "selected";}
			?>


            <div class="mb-3">
                <label for="mfd_year" class="form-label">Manufacturer (Given)</label>
                <input type="text" class="form-control" name="meter_make_new" value="GENUS POWER INFRASTRUCTURES LTD."
                       placeholder="GENUS OVERSEAS ELECTRONICS LTD" aria-describedby="textHelp" readonly>
            </div>
            <div class="mb-3">
                <label for="mfd_year" class="form-label">Year Of Manufacture</label>
                <input type="text" class="form-control" name="mfd_year_new" value="2023" placeholder="2023"
                       aria-describedby="textHelp" readonly>
            </div>
            <div class="mb-3">
                <label for="final_reading" class="form-label">Initial Reading (IR)-kWh <span
                            class="mandatory_star">*</span></label>
				@if (empty($meter_main->initial_reading_kwh))
					<input type="number" class="form-control" name="initial_reading_kwh"
                       value="00"
                       placeholder="Enter Initial Reading"
                       aria-describedby="textHelp" required>
				@else 
					<input type="number" class="form-control" name="initial_reading_kwh"
                       value="{{$meter_main->initial_reading_kwh}} "
                       placeholder="Enter Initial Reading"
                       aria-describedby="textHelp" required>
			   @endif
            </div>
            <div class="mb-3">
                <label for="final_reading" class="form-label">Initial Reading (IR)-kVAh <span
                            class="mandatory_star">*</span></label>
				@if (empty($meter_main->initial_reading_kvah))
					<input type="number" class="form-control" name="initial_reading_kvah"
                       value="00"
                       placeholder="Enter Initial Reading"
                       aria-describedby="textHelp" required>
				@else 
					<input type="number" class="form-control" name="initial_reading_kvah"
                       value="{{$meter_main->initial_reading_kvah}} "
                       placeholder="Enter Initial Reading"
                       aria-describedby="textHelp" required>
			   @endif
            </div>


            <!-- LOCATION AND DATE SHOULD BE AUTO FETCHED -->

            {{-- <button type="submit" class="btn btn-success">Submit</button> --}}
            {{--                <button class="btn btn-primary w-sm"  onclick="Swal.fire({icon: 'info',title: 'Please Wait..',showConfirmButton: false,timer: 500000})" class="btn btn-success btn-sm" type="submit" name="importSubmit">Submit</button>--}}
            <button class="btn btn-primary w-sm" onclick="checkFormValidationResult()" class="btn btn-success btn-sm"
                    type="submit" name="importSubmit">Submit
            </button>
            <button type="button" class="btn btn-danger" style="float:right;" data-toggle="modal"
                    data-target="#exampleModalCenter">
                Cancel
            </button>
        </form>
    </div>
</div>
{{-- <div class="osahan-menu-fotter fixed-bottom bg-dark text-center m-3 shadow rounded py-2">
    <div class="row m-0">
        <a href="/pages/home" class="text-white col small text-decoration-none p-2 disabled">
            <p class="h5 m-0"><i class="fa-sharp fa-solid fa-house" style="color:white;"></i></p>Home
        </a>
        <a href="/pages/add_meter_first_step" class="text-white col small text-decoration-none p-2 disabled">
            <p class="h5 m-0"><i class="fa-solid fa-plus"></i></p>
            Add Meter
        </a>
        <a href="/pages/records" class="text-white col small text-decoration-none p-2 disabled">
            <p class="h5 m-0"><i class="icofont-bag"></i></p>
            Rejected Meters
        </a>
        <a href="/pages/account" class="text-white col small text-decoration-none p-2 disabled">
            <p class="h5 m-0"><i class="icofont-user"></i></p>
            Account
        </a>
    </div>
</div> --}}

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="height:300px;">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">Are you sure you want to cancel ?<br><br>
                    The meter reading is already in progress, if you cancel now you wont be able to start again
                    until the current account number is re-allocated to field executive.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    style="width:100%;">No
            </button>
            <a href="/pages/add_meter_first_step">
                <button type="button" class="btn btn-primary"
                        style="width:100%;background-color:chocolate;">Yes
                </button>
            </a>
            {{-- <center><div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
     <a href="/pages/add_meter_first_step"> <button type="button" class="btn btn-primary">Yes</button></a>
    </div></center> --}}
            {{-- <div class="modal-body" >
      The meter reading is already in progress, if you cancel now you wont be able to start again until the current account number is re-allocated to field executive.
        </div> --}}


        </div>
    </div>
</div>

<script type="text/javascript" src="/assets_page/js/image-resize.js"></script>

@include('inc_pages.suveryfooter')
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
    function checkFormValidationResult() {
        var Form = document.getElementById('newMeterDetail');
        if (Form.checkValidity() == false) {
            var list = Form.querySelectorAll(':invalid');
            for (var item of list) {
                item.focus();
            }
        } else {
            Swal.fire({icon: 'info', title: 'Please Wait', showConfirmButton: false, timer: 50000})
        }
    }
</script>

