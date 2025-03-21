@include('inc_admin.header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
$meter_main = $data['meter_main'];
$consumer_detail = $data['consumer_detail'];
$error_message_detail = $data['error_message_detail'];
$meter_previous_final_reading = $data['meter_previous_final_reading'];
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
                        <h4 class="mb-sm-0">Edit Error Report</h4>
                        {{-- <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);"></a>
                                </li>
                                <li class="breadcrumb-item active">Edit QC Report</li>
                            </ol>
                        </div> --}}

                    </div>
                    @if(!empty($error_message_detail->error_reason))
                    <div class="mx-1 mt-1 page-title-box" style="display: flex;justify-content:space-between; color: red">
                        <h4 class="b-sm-0"> Error :  {{ $error_message_detail->error_reason }} </h4>
                    </div>
                    @endif
                </div>


            </div>

            <!-- end page title -->


            <form id="createproduct-form" autocomplete="off" class="needs-validation"
                action="/hescoms/update_error_reports/{{ $meter_main->id }}" method="POST" enctype="multipart/form-data">
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
								
								    <div class="mb-3" hidden>
                                    <label for="assignedtoName-field" class="form-label">Sp id </label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Section" value="{{ $consumer_detail->sp_id }}" name="sp_id" />
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

                                                    <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_1_old) }}', '_blank', 'width=800,height=600');">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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

                                                    <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_2_old) }}', '_blank', 'width=800,height=600');">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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

                                                    <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_3_old) }}', '_blank', 'width=800,height=600');">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
                                @if(!empty($meter_previous_final_reading->reading))
                                    <div class="mb-3 bg-danger">
                                        <label for="previousReading-field" class="form-label">Previous Final Reading</label>
                                        <input type="text" id="previousReading-field" class="form-control bg-danger" value="{{ $meter_previous_final_reading->reading??0 }}" readonly/>
                                    </div>
                                    <div class="mb-3 bg-danger">
                                        <label for="differenceReading-field" class="form-label">Difference fom FR Reading</label>
                                        <input type="text" id="differenceReading-field" class="form-control bg-danger" value="{{ ($meter_main->final_reading- $meter_previous_final_reading->reading??0) }}" readonly/>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="justification-by-aoo-field" class="form-label">Justification</label>
                                    <textarea type="text" id="justification-by-aoo-field" class="form-control"
                                              name="justification_by_aao">{{ $error_message_detail->justification_by_aao }}</textarea>
                                </div>
                                {{-- <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Error Reason</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Error Reason" value="{{ $data['error_records']->error_reason }}"
                                        name="" readonly/>
                                </div> --}}
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

                                                    <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_1_new) }}', '_blank', 'width=800,height=600');">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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

                                                    <a href="javascript:void(0);" onclick="window.open('{{ asset($meter_main->image_2_new) }}', '_blank', 'width=800,height=600');">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
                                        name="serial_no_new" readonly/>
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
                                        name="initial_reading_kwh" />
                                </div>
                                <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Initial Reading g
                                        (IR)-kVAh</label>
                                    <input type="text" id="assignedtoName-field" class="form-control"
                                        placeholder="Meter Make" value="{{ $meter_main->initial_reading_kvah }}"
                                        name="initial_reading_kvah" />
                                </div>
                                <div class="mb-3">
                                    <label for="assignedtoName-field" class="form-label">Installation Date</label>
                                    <input type="datetime-local" id="assignedtoName-field" class="form-control"
                                        placeholder="Installation Date" value="{{ $meter_main->created_at }}"
                                        name="created_at">
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
            <a href="/hescoms/error_reports"> <button
                class="btn btn-primary w-sm">Cancel</button></a>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->


</div>




@include('inc_admin.footer')
