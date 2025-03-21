@include('inc_admin.header')

<div id="layout-wrapper">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Meter Serial No. ownership Takeover</h4>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <form method="POST" action="{{ url('/') }}/hescoms/serial_no_search">
                            @csrf
                            <div class="row">
                                <div class="col-4">
                                    <label class="form-label" for="serial_no">Serial Number <span class="mandatory_star">*</span></label>
                                    <input type="text" class="form-control" name="serial_no" id="serial_no" placeholder="Enter Serial Number" required>
                                </div>
                                <div class="col-lg-4">                                                               
                                    <button type="submit" class="btn btn-primary mt-4">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('inc_admin.footer')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Display SweetAlert if there's a flash message
            @if(session('alert'))
                Swal.fire({
                    icon: 'info',  // Change based on alert type
                    title: 'Notification',
                    text: '{{ session('alert') }}',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect if necessary
                        @if(session('alert') == 'Account details not found! Redirecting to account ID ownership page.')
                            window.location.href = "{{ url('hescoms/serial_number_ownership') }}";
                        @endif
                    }
                });
            @endif
        });
    </script>
