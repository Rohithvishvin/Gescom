@include('inc_admin.header')


@php
use Illuminate\Support\Facades\Session;
    use App\Models\Zone_code;
    use App\Models\Admin;


    $meter_main = $data['meter_main'];

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

                                       <div class="mt-1" id="searchResult"></div>
                                       <?php
                                        $meter_main = $data['meter_main'];
                                        $account_data =   $data["faulty_meter_serial_no"]["account_id"];
                                        $serial_number =   $data["faulty_meter_serial_no"]["serial_no"];
                                        $serial_no_status =  $data["faulty_meter_serial_no"]["contractory_inventory_status"];
                                        $serial_no_msg = $data["error"]["message"];
                                        $box_id =  $data["faulty_meter_serial_no"]["box_id"];
                                        $divison = $data["faulty_meter_serial_no"]["division"];
                                        //  print_r($account_data);
                                        ?>
                                         </div>

                         
                        
                        </div>
                        <div class="card ongoing-project recent-orders">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Account Details</h4>
                        </div>
                        <div class="card-body pt-0">
                            @inject('carbon', 'Carbon\Carbon')
                            <div class="table-responsive">


                            <form autocomplete="off" class="needs-validation" id="updateForm" action="/inventories/update_faulty_meter-unused" method="POST" enctype="multipart/form-data">
                             @csrf

                                <table class="table table-striped">
                                
                                    <tr>
                                    <th scope="row">user Meter Serial Number </th>
                                        <td>{{$serial_number}}</td>
                                       <input type="hidden" id="serial_number_user_input" name="serial_number_user_input" value="{{$serial_number}}" class="serial_number_user_input">
                             
                                        <th scope="row">Account Id</th>
                                        <td>
                                        @if (!empty($meter_main->account_id))
                                        {{$meter_main->account_id}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                       </td>
                                        <th scope="row">Meter Serial Number status</th>
                                        <td>  
                                        @if(!empty($serial_no_status))
                                        {{$serial_no_status}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                    
                                    
                                    </td>
                                        <th scope="row"> Serial Number status </th>
                                        <td>                                  
                                        @if(!empty($serial_no_msg))
                                        {{$serial_no_msg}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                    </td>
                                    </tr>
                                    <tr>
                                    <th scope="row" hidden>box id </th>
                                        <td hidden>                                   
                                        @if(!empty($box_id))
                                        {{$box_id}}
                                        @else
                                        <p>N/A</p>
                                        @endif                     
                                       </td>

                                        <th scope="row">Division</th>
                                        <td>
                                        @if(!empty($divison))
                                        {{$divison}}
                                        @else
                                        <p>N/A</p>
                                        @endif
                                    </td>
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
                                      </tr>
                                      <tr>
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
                                    </tr>
                                                                                                                            
                                                            
                                </table>

                                @if ($meter_main !== null && isset($meter_main->id))
                            
                                @else
                                <br>
                                    <span class="mt-4">Account Id not Available</span>
                                    <span class="mt-4 btn-danger">Moving it To Faulty meter serial Number ?</span>

                                    <div class="col-4">
                                        <label class="form-label" for="product-title-input">Reason For Pushing this serial Number as Faulty
                                            <span class="mandatory_star">
                                            *<sup><i>required</i></sup>
                                        </span>
                                        </label>
                                   
                                        <textarea id="serial_number_reason" name="serial_number_reason" rows="10" cols="150" placeholder="Reason"></textarea>                   
                                    </div>


                                    <!---<a href="/inventories/update_faulty_meter-unused">
                                    <button type="button" class="btn btn-primary w-sm mt-4" onclick="submitForm()">
                                    <i class="align-bottom me-1 mt-4"></i>Submit
                                </button>
                                            </a>  --->
                                <button type="button" class="btn btn-primary w-sm mt-4" onclick="submitForm()"><i class="align-bottom me-1 mt-4"></i>Submit</button>
                                @endif       





                                </form>

                                @if ($meter_main !== null && isset($meter_main->id))
                                <button class="btn btn-primary w-sm mt-4" onclick="window.location.href='/inventories/edit_faulty_meter/{{ $meter_main->id }}'">
                                <i class="ri-add-line align-bottom me-1 mt-4"></i>Edit</button>
                                @endif                                                                                                                                                                                     
                               
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
    
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excelHtml5'
            ]
        });
    });
</script>

