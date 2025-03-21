@include('inc_admin.header')

<link rel="stylesheet" type="text/css" href="assets_admin/css/vendors/datatables.css">
@php
    // use App\Models\Zone_code;
    // use App\Models\Admin;
    // use App\Models\Inventory;
    // use App\Models\Meter_main;
    // use App\Models\Indent;
    // use App\Models\Error_record;
    // use App\Models\Successful_record;
    // use App\Models\Warehouse_meter;

@endphp
<div class="main-content">
    <div class="page-content ">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">

                        <h1>Welcome

                            <p>{{ ucwords(session('rexkod_vishvin_auth_name')) }}</p>

                        </h1>


                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">HOME</a></li>
                                <li class="breadcrumb-item active">HOME PAGE</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->
@php
    if (session('rexkod_vishvin_auth_user_type') == "admin") {
                $location = 'admins/admin_index';
            }else if(session('rexkod_vishvin_auth_user_type') == "project_head"){
                $location = 'project_heads/index';


            }
            else if(session('rexkod_vishvin_auth_user_type') == "inventory_manager"){
                $location = 'inventories/inventory_manager_index';

            }
            else if(session('rexkod_vishvin_auth_user_type') == "inventory_executive"){
                $location = 'inventories/inventory_executive_index';

            }
            else if(session('rexkod_vishvin_auth_user_type') == "inventory_reporter"){
                $location = 'inventories/inventory_reporter_index';

            }
            else if(session('rexkod_vishvin_auth_user_type') == "qc_manager"){
                $location = 'qcs/index';


            }
            else if(session('rexkod_vishvin_auth_user_type') == "qc_executive"){
                $location = 'qcs/qc_executive_index';

            }
            else if(session('rexkod_vishvin_auth_user_type') == "hescom_manager"){
                $location = 'hescoms/index';


            }
            else if(session('rexkod_vishvin_auth_user_type') == "aee"){
                $location = 'hescoms/aee_index';


            }
            else if(session('rexkod_vishvin_auth_user_type') == "ae"){
                $location = 'hescoms/ae_index';


            }
            else if(session('rexkod_vishvin_auth_user_type') == "aao"){
                $location = 'hescoms/aao_index';

            }
            else if(session('rexkod_vishvin_auth_user_type') == "contractor_manager"){
                $location = 'contractors/index';

            }
            else if(session('rexkod_vishvin_auth_user_type') == "bmr"){
                $location = 'bmrs/index';

            }
@endphp
            <div class="row project-wrapper">
                <div class="col-xxl-12">
                    <div class="row">
                        <p>To access your Dashboard <a href="/{{$location}}">click here</a> </p>

                    </div><!-- end row -->

                </div>
                <!-- end col   -->

            </div>

        </div>
        <!-- container-fluid  -->
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">


<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            dom: 'Bfrtip',
            buttons: [

            ]
        });
    });
    sessionStorage.removeItem('section_code');
</script>
