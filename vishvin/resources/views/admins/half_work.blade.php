@include('inc_admin.header')

<head>
    <title style="background-color:black;color:white;">Half Work Meter Installation</title>
</head>

<!-- Begin page -->
<div id="layout-wrapper">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Half Work Installation Report</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">Account ID</th>
                                                <th scope="col">Rr No</th>
                                                <th scope="col">Consumer Name</th>                                          
                                                <th scope="col">Division</th>
                                                <th scope="col">Sub division</th>
                                                <th scope="col">Section</th>                                          
                                                <th scope="col">Serial No. Old</th>
                                                <th scope="col">Serial No. New</th>                                     
                                                <th scope="col">Created By</th>
                                                <th scope="col">Created At</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                
                                                $count = 1
                                            @endphp
                                           
                                           @foreach ($show_users as $user)
                                                <tr id="row-{{ $user->id }}">
                                                    <td>{{ $count++ }}</td>
                                                    <td>{{ $user->meter_mains_account_id }}</td>
                                                    <td>{{ $user->rr_no }}</td>
                                                    <td>{{ $user->consumer_name }}</td>
                                                    <td>{{ $user->division }}</td>
                                                    <td>{{ $user->sub_division }}</td>
                                                    <td>{{ $user->section }}</td>                                     
                                                    <td>{{ $user->serial_no_old }}</td>
                                                    <td>{{ $user->serial_no_new }}</td>
                                             
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->meter_installed_at }}</td>
                                                    <td>
                                                            <button type="button" class="btn btn-danger" onclick="deleteRow({{ $user->id }})">
                                                                <i class="fas fa-trash-alt"></i> Delete
                                                            </button>
                                                            <button type="button" class="btn btn-primary" onclick="viewRow({{ $user->id }})">
                                                                <i class="fas fa-eye"></i> View
                                                            </button>
                                                        </td>


                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc_admin.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    function deleteRow(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "Would you like to delete half installed meter",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('half_install/delete') }}/" + id,
                    type: "DELETE",
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire("Deleted!", response.message, "success");
                            $("#row-" + id).remove();
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error!", "Failed to delete the record.", "error");
                    }
                });
            }
        });
    }


    function viewRow(id) {
    $.ajax({
        url: "{{ url('/half_install/view') }}/" + id,
        type: "GET",
        success: function (response) {
            if (response.success) {
                const details = response.data;

                // Use Swal to display the details in a modal
                Swal.fire({
                    title: "Meter Details",
                    html: `
                        <b>Account ID:</b> ${details.account_id} <br>
                        <b>RR No:</b> ${details.rr_no} <br>
                        <b>Consumer Name:</b> ${details.consumer_name} <br>
                        <b>Consumer Address:</b> ${details.consumer_address} <br>
                         <b>Old meter serial Number:</b> ${details.serial_no_old} <br>
                          <b>New meter serial Number:</b> ${details.serial_no_new} <br>
                          <b>Final reading :</b> ${details.final_reading} <br>
                           <b>Consumer Address:</b> ${details.consumer_address} <br>
                        <b>Created At:</b> ${details.created_at} <br>

                    `,
                    icon: "info"
                });
            } else {
                Swal.fire("Error!", response.message, "error");
            }
        },
        error: function () {
            Swal.fire("Error!", "Failed to fetch the details.", "error");
        }
    });
}

</script>
