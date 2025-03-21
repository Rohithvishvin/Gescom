@include('inc_admin.header')
<?php
?>
<link rel="stylesheet" type="text/css" href="assets_admin/css/vendors/datatables.css">
<style>
    .form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  font-weight: bold;
}

input[type="date"] {
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
  font-size: 1rem;
  width: 100%;
  box-sizing: border-box;
}

button[type="submit"] {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.25rem;
  font-size: 1rem;
  background-color: #007bff;
  color: #fff;
  cursor: pointer;
}

</style>
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
                                        <h4 class="mb-sm-0">Date Replacement</h4>
                                    
                                    </div>
                                </div>
                            </div>
                                    <div class="row">
                                        <div class="col-12">
                                                    <form method="POST" action="{{url('/')}}/qcs/date_change">
                                                        @csrf
                                                        <div class="row">
                                                        
                                                        <div class="col-4">
                                                                <label class="form-label" for="product-title-input">Account Id
                                                                    <span class="mandatory_star">*</span>
                                                                </label>
                                                                <input type="text" class="form-control" name="account_id" id="account_id_input" placeholder="Enter Account Id" required>
                                                                
                                                            </div>

                                                                <div class="col-4">
                                                                    <label class="form-label" for="end_date">Replacement Date <span class="mandatory_star">*</span></label>
                                                                    <input type="date" class="form-control" name="end_date" id="end_date" required>
                                                                </div> 
                                                    
                                                                <div class="col-lg-4">
                                                                
                                                                    <button type="submit" class="btn btn-primary mt-4">Submit</button>
                                                                </div>
                                                    </form>
                                                        <?php
                                                            $account_data = $data['account_id'] ?? null; 
                                                        ?>
                                        </div>
                                    </div>
                            @if($account_data == "notexist")
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h3 class="bg-danger">Account Id not Exist cant able to change the Date</h3>
                                    </div>
                                </div>
                            @endif
                            @if($account_data == "download-flag-error")
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h3 class="bg-danger">With this Account id can not able to change the Date</h3>
                                    </div>
                                </div>
                            @endif
                            @if($account_data == "input-error")
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h3 class="bg-danger">Input error cant able to change the Date</h3>
                                    </div>
                                </div>
                            @endif
            
            </div>
        </div>
    </div>
</div>

@include('inc_admin.footer')


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
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        orthogonal: 'export'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    exportOptions: {
                        orthogonal: 'export'
                    }
                }
            ]
        });
    });
</script>

<script>
    var successMessage = '{{ session('success') }}';
    if (successMessage) {
        setTimeout(function() {
            alert(successMessage);
        }, 5000); 
    }
</script>


