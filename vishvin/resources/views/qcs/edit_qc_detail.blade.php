@include('inc_admin.header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
$meter_main = $data['meter_main'];
$consumer_detail = $data['consumer_detail'];
use App\Models\Admin;
use App\Models\Contractor_inventory;
?>
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
                        <h4 class="mb-sm-0">Edit QC Report</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">QC</a>
                                </li>
                                <li class="breadcrumb-item active">Edit QC Report</li>
                            </ol>
                        </div>

                    </div>
                    <!-- <div class="mx-5 mt-2 page-title-box" style="display: flex;justify-content:space-between">
                        <h4>Project Name : project name</h4>
                        <h4>Project Lead : project lead</h4>
                        <h4>Module Name : module name</h4>
                    </div> -->

                </div>


            </div>

            <!-- end page title -->


            <form id="createproduct-form" autocomplete="off" class="needs-validation"
                action="/qcs/update_qc_report/{{ $meter_main->id }}" method="POST" enctype="multipart/form-data">
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
                                        } ?>">


                                    {{-- @if ($consumer_detail->meter_type == 1)Single Phase
                                    @elseif($consumer_detail->meter_type==2)Three Phase
                                    @endif" readonly /> --}}
                                </div>
                                <!-- <div class="mb-3">
                                    <select class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="">Inventory</option>
                                        <option value="">Annexure</option>

                                    </select>



                                </div> -->





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
                                        name="meter_make_old" />
                                </div>
                                <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Meter Serial No</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Meter Serial No" value="{{ $meter_main->serial_no_old }}"
                                        name="serial_no_old" />
                                </div>
                                <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Year of Manufacture</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Year of Manufacture" value="{{ $meter_main->mfd_year_old }}"
                                        name="mfd_year_old" />
                                </div>
                                <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Final Reading
                                        (FR)-kWh</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Meter Make" value="{{ $meter_main->final_reading }}"
                                        name="final_reading" />
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
                                    <label for="assignedtoName-field" class="form-label">Meter Serial No</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Meter Serial No" value="{{ $meter_main->serial_no_new }}"
                                        name="serial_no_new" />
                                </div>
                                <div class="mb-3">
                                    <label for="serial_no" class="form-label">Meter Serial No</label>
                                    <select class="form-control" name="serial_no_new" required id="serial_no_new_dropdown" disabled>
                                        <?php

                                $get_field_executive_contractor =  Admin::where('id',$meter_main->created_by)->first();
                                $contractor_inventories =  Contractor_inventory::where('contractor_id', $get_field_executive_contractor->created_by)->get();
                                foreach ($contractor_inventories as $contractor_inventory) {
                                    if($contractor_inventory->meter_type==$consumer_detail->meter_type)
                {
                                        $str = $contractor_inventory->unused_meter_serial_no;
                                        if($str!== Null && $str !==''){
                                  $nums = explode(",", $str);
                                  foreach($nums as $num) { ?>
                                        <option value="<?php echo $num; ?>"><?php echo $num; ?></option>
                                        <?php }
                                    }

                                }
                            }
                               ?>
                                        <option value="<?php echo $meter_main->serial_no_new; ?>" selected><?php echo $meter_main->serial_no_new; ?></option>
                                        <?php  ?>
                                    </select>
                                </div>

                                <script>
                                    const serialNoDropdown = document.getElementById('serial_no_new_dropdown');
                                    const options = serialNoDropdown.options;
                                    const filterInput = document.createElement('input');
                                    filterInput.type = 'number';
                                    filterInput.placeholder = 'Search...';
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
                                        name="initial_reading_kwh" />
                                </div>
                                <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Initial Reading g
                                        (IR)-kVAh</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Meter Make" value="{{ $meter_main->initial_reading_kvah }}"
                                        name="initial_reading_kvah" />
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
            <a href="/qcs/qc_view_detail/{{ $meter_main->id }}"> <button
                class="btn btn-primary w-sm">Cancel</button></a>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


</div>




@include('inc_admin.footer')
