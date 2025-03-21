@include("inc_admin.header")


<head>

    <title>All Products</title>


</head>


<!-- Begin page -->
<div id="layout-wrapper">


    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">QC Reports</h4>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                    <li class="breadcrumb-item active">Basic Tables</li> -->
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{url('/')}}/qcs/qc_view" id="">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="form-label" for="product-title-input">Section code
                                            <span class="mandatory_star">*<sup><i>required</i></sup></span>
                                        </label>
                                        <select id="so_code" name="so_code" class="form-control">
                                            <option value="" selected>Select</option>
                                            @foreach ($so_pincodes as $so_code)
                                                @if(!empty($filter_requests['so_code']) && $filter_requests['section_code'] === $so_code->so_code)
                                                    <option value="{{$so_code->so_code}}"
                                                            selected>{{$so_code->so_code}}</option>
                                                @else
                                                    <option value="{{$so_code->so_code}}">{{$so_code->so_code}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" id="submitFilter">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <!-- end card header -->

                            <div class="card-body">

                                <div class="live-preview">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead>
                                            <tr>
                                                <th scope="col">Reference ID</th>
                                                <th scope="col"> Account ID</th>
                                                <th scope="col"> Subdivision Code</th>
                                                <th scope="col"> Section Code</th>
                                                <th scope="col"> Contractor Name</th>
                                                <th scope="col"> Installation Date</th>
                                                <th scope="col">Actions</th>
                                                {{-- <th scope="col">	<label ><input type="checkbox" name="sample" class="selectall"/> Select all</label></th> --}}
                                            </tr>
                                            </thead>
                                            <form id="createproduct-form" autocomplete="off" class="needs-validation"
                                                  action="/qcs/bulk_approve_qcs_report" method="POST"
                                                  enctype="multipart/form-data">
                                                @csrf
                                                <tbody>
                                                @foreach($meter_mains as $meter_main)
                                                    @php
                                                        $contractorName = null;
                                                        foreach ($contractors as $contractorKey => $contractorValue){
                                                            if($meter_main->field_executive_contractor_id == $contractorValue->contractor_id){
                                                                $contractorName = $contractorValue->contractor_name;
                                                            }
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{$meter_main->id}}</td>
                                                        <td>{{$meter_main->account_id}}</td>
                                                        <td>{{$meter_main->sub_division}}</td>
                                                        <td>{{$meter_main->section}}</td>
                                                        <td>{{$contractorName}}</td>
                                                        <td>{{$meter_main->created_at}}</td>
                                                        <td><a href="/qcs/qc_view_detail/{{$meter_main->id}}">
                                                                <button type="button" class="btn btn-secondary">View
                                                                </button>
                                                            </a></td>
                                                        {{-- <td><input type="checkbox" name="meter_main_id[]" value="{{$meter_main->id}}"></td> --}}
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="d-none code-view">

                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

                {{-- <div class="text-end mb-3">
                    <button type="submit" class="btn btn-success w-sm">Approve</button>
                </div> --}}
                </form>

            </div>
            <!-- container-fluid -->

        </div>
        <!-- End Page-content -->


    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->


{{-- @include("inc_admin.footer") --}}

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

    $('.selectall').click(function () {
        if ($(this).is(':checked')) {
            $('div input').attr('checked', true);
        } else {
            $('div input').attr('checked', false);
        }
    });

    document.getElementById("submitFilter").addEventListener("click", function (event) {
        // console.log(event);
        //event.preventDefault();
        document.getElementById('so_code').disabled = false;
        sessionStorage.setItem('section_code', document.getElementById('so_code').value);
        // console.log('here');
        //event.currentTarget.submit();
    });
    var temp_section_code = sessionStorage.getItem('section_code');
    if (temp_section_code != null) {
        console.log(temp_section_code);
        document.getElementById('so_code').value = temp_section_code;
        document.getElementById('so_code').disabled = true;
    }
</script>




