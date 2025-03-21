@include('inc_admin.header')

<?php
$meter_main = $data['meter_main'];
$consumer_detail = $data['consumer_detail'];
use App\Models\Admin;
use App\Models\Contractor_inventory;
?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Meter mains Update</h4>                                                           
                    </div>
                </div>
            </div>
        </div>

        <!-- Form to search Account ID -->
        <div class="row">
            <div class="col-12">
                <form method="POST" action="{{ url('/') }}/hescoms/sp_id_search">
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
                            <form id="updateForm" action="{{ url('/qc/sp_id_update/' . $consumer_detail->id . '/' . $meter_main->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <table class="table table-striped">
                                    <tr>
                                       

										 
                                        <th scope="row">Account ID</th>
                                        <td>{{ $meter_main->account_id ?? 'N/A' }}</td>
                                        <th scope="row">RR Number</th>
                                        <td>{{ $consumer_detail->rr_no ?? 'N/A' }}</td>
										  <th scope="row">Consumer name</th>
                                        <td>{{ $consumer_detail->consumer_name ?? 'N/A' }}</td>
                                        <!---<th scope="row">Serial No New</th>
                                        <td>{{ $meter_main->serial_no_new ?? 'N/A' }}</td>--->
										 <th scope="row">New Meter Serial Number</th>
                                       <!---- <td>{{ $meter_main->serial_no_new ?? 'N/A' }}</td>-->
										           <td>
                                         
                                            <div class="mb-3">
                                    <label for="serial_no" class="form-label">Meter Serial No</label>
                                    <select class="form-control" name="serial_no_new" value="{{ $meter_main->serial_no_new  }}"  required id="serial_no_new_dropdown" disabled>
                                        <?php

                                $get_field_executive_contractor =  Admin::where('id',$meter_main->created_by)->first();
                                $contractor_inventories =  Contractor_inventory::where('contractor_id', 177)->get();
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
                                            </td>
                          

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
                                    </tr>
                                    <tr>
                                        <th scope="row">Old Image</th>
                                        <td>
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
                                        </td>
                                        <th scope="row">Old Image 2</th>
                                        <td>
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
                                        </td>





                                        <th scope="row">New Image</th>
                                        <td>
                                         <label for="ticket-status" class="form-label">Photo 1 (with reading) @if (!empty($meter_main->image_1_old))
                                            <a href="{{ asset($meter_main->image_1_new) }}" target="_blank"> <i
                                                    class="fa fa-eye"></i></a>
                                        @else
                                            <i class="fa fa-eye-slash"></i>
                                        @endif
                                    </label>
                                    <input type="file" id="assignedtoName-field" class="form-control"
                                        placeholder="Section" value="{{ $meter_main->image_1_new }}"
                                        name="image_1_new" />
                                        </td>
                                        <th scope="row">New Image 2</th>
                                        <td>
                                          <label for="ticket-status" class="form-label">Photo 2 (with reading)
                                        @if (!empty($meter_main->image_2_old))
                                            <a href="{{ asset($meter_main->image_2_new) }}" target="_blank"> <i
                                                    class="fa fa-eye"></i></a>
                                        @else
                                            <i class="fa fa-eye-slash"></i>
                                        @endif
                                    </label>
                                    <input type="file" id="assignedtoName-field" class="form-control"
                                        placeholder="Section" value="{{ $meter_main->image_2_new }}"
                                        name="image_2_new" />
                                        </td>
                                      
                                        
                                    </tr>
                                    <tr>
                                    <th scope="row">FR Reading</th>
                                        <td>
                                            <input id="final_reading" name="final_reading" value="{{ $meter_main->final_reading ?? '' }}" rows="10" cols="150">
                                        </td>
                                        <th scope="row">Consumer Name</th>
                                        <td>
                                            <input id="consumer_name" name="consumer_name" value="{{ $consumer_detail->consumer_name }}" readonly>
                                        </td>
                                        <th scope="row">SP ID</th>
                                        <td>
                                            <input id="sp_id" name="sp_id" value="{{ $consumer_detail->sp_id }}" rows="10" cols="150">
                                        </td>
										        <input type="text" name="serial_no_old" id="serial_no_old" value="{{ $meter_main->serial_no_old }}" readonly hidden>
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
