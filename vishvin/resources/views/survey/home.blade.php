@include("inc_pages.header")
<link rel="stylesheet" href="//cdn.datatables.net/buttons/2.3.4/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .dt-buttons {
        display: none;
    }
</style>
<body class="fixed-bottom-padding">
<div class="osahan-home-page">
    <div class="shadow-sm bg-info p-3" style="border-radius:5px;">
        <div class="title d-flex align-items-center">
            <a href="/pages/home" class="text-decoration-none text-dark d-flex align-items-center">
                <img class="osahan-logo me-2" src="/assets_admin/images/vishvin/logo.jpg">
            </a>
            <p class="ms-auto m-0"></p>
        </div>
    </div>
    
    <!-- Body -->
    <div class="osahan-body" style="padding-bottom:420px;">
        <div class="text-center position-absolute top-50 start-50 translate-middle">
            <h6 class="mb-2">Welcome User</h6>
            @php
                $count = count($data['meter_main']);
            @endphp
            <p>{{ $data['lat'] ?? '--' }} {{ session()->get('user_lat') }} You have surveyed <span style="font-size:17px;">{{ $count }}</span> meters.</p>
        </div>
    </div>

    <!-- Table to display meter details -->
    <div class="row m-0 table-responsive-sm mt-4">
        <div class="col ps-0 pe-1 py-1" class="table-responsive-sm">
        <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Account ID</th>
                <th>RR No</th>
                <th>Created At</th>
                <th>Lat</th>
                <th>Lon</th>
                <th>geolink</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['meter_main'] as $index => $meter)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $meter->account_id }}</td>
                    <td>{{ $meter->rr_no }}</td>
                    <td>{{ $meter->created_at ? \Carbon\Carbon::parse($meter->created_at)->format('Y-m-d H:i:s') : '--' }}</td>
                    <td>{{ $meter->lat ?? '--' }}</td>
                    <td>{{ $meter->lon ?? '--' }}</td>
                    <td>
                        @if($meter->geo_link)
                            <a href="{{ $meter->geo_link }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-external-link-alt"></i> Open Link
                            </a>
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        @if($meter->geo_link)
                            <a 
                                href="https://wa.me/?text={{ urlencode($meter->geo_link) }}" 
                                target="_blank" 
                                class="btn btn-success btn-sm">
                                <i class="fab fa-whatsapp"></i> Share
                            </a>
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        @if($meter->lat && $meter->lon)
                            <button 
                                class="btn btn-primary btn-sm view-location" 
                                data-lat="{{ $meter->lat }}" 
                                data-lon="{{ $meter->lon }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                        @else
                            --
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        </div>
    </div>
</div>

<!-- Modal for Location Details -->
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">Location Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="locationDetails">Loading location details...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = "https://api.opencagedata.com/geocode/v1/json";
    const apiKey = "58624296be5d4188a887e89b40b0c3fb"; // Replace with your OpenCage API Key

    // Check if .view-location buttons are available
    const viewLocationButtons = document.querySelectorAll(".view-location");

    if (viewLocationButtons.length > 0) {
        viewLocationButtons.forEach(function (button) {
            button.addEventListener("click", function () {
                const lat = parseFloat(this.getAttribute("data-lat")).toFixed(8); // Ensure 8-digit precision
                const lon = parseFloat(this.getAttribute("data-lon")).toFixed(8); // Ensure 8-digit precision

                if (!lat || !lon) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Latitude or Longitude is missing.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                    return;
                }

                // Show a loading alert while fetching data
                Swal.fire({
                    title: 'Fetching location details...',
                    text: 'Please wait while we retrieve the data.',
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Fetch location details from OpenCage API
                fetch(`${apiUrl}?key=${apiKey}&q=${lat},${lon}&pretty=1&no_annotations=1`)
                    .then((response) => response.json())
                    .then((data) => {
                        if (data && data.status.code === 200 && data.results.length > 0) {
                            const result = data.results[0]; // Fetch the first result
                            const bounds = result.bounds || {};
                            const components = result.components || {};
                            const confidence = result.confidence || '--';
                            const distanceFromQ = result.distance_from_q || {};
                            const formatted = result.formatted || '--';
                            const geometry = result.geometry || {};

                            // Generate map links with high precision
                            const googleMapsLink = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lon}`;
                            const openStreetMapLink = `https://www.openstreetmap.org/?mlat=${geometry.lat || lat}&mlon=${geometry.lng || lon}&zoom=16`;

                            // Create an HTML structure dynamically to display all fields
                            const resultHtml = `
                                <p><strong>Address:</strong> ${formatted}</p>
                                <p><strong>Confidence:</strong> ${confidence}</p>
                                <p><strong>Distance from Query:</strong> ${distanceFromQ.meters || '--'} meters</p>
                                <p><strong>Geometry:</strong> Lat: ${geometry.lat || '--'}, Lng: ${geometry.lng || '--'}</p>
                                <p><strong>Bounds:</strong></p>
                                <ul>
                                    <li><strong>Northeast:</strong> Lat: ${bounds.northeast?.lat || '--'}, Lng: ${bounds.northeast?.lng || '--'}</li>
                                    <li><strong>Southwest:</strong> Lat: ${bounds.southwest?.lat || '--'}, Lng: ${bounds.southwest?.lng || '--'}</li>
                                </ul>
                                <p><strong>Components:</strong></p>
                                <ul>
                                    ${Object.entries(components).map(([key, value]) => `<li><strong>${key}:</strong> ${value}</li>`).join('')}
                                </ul>
                                <p><strong>View on Map:</strong></p>
                                <ul>
                                    <li><a href="${googleMapsLink}" target="_blank">Google Maps</a></li>
                                    <li><a href="${openStreetMapLink}" target="_blank">OpenStreetMap</a></li>
                                </ul>
                            `;

                            // Display the data in SweetAlert popup
                            Swal.fire({
                                title: 'Location Details',
                                html: resultHtml,
                                icon: 'info',
                                confirmButtonText: 'Close'
                            });
                        } else {
                            // If no data found or status is not success
                            Swal.fire({
                                title: 'Error!',
                                text: 'Could not fetch location details. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'Close'
                            });
                        }
                    })
                    .catch((error) => {
                        // Handle any error in the fetch request
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while fetching location details.',
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                        console.error("Error fetching location details:", error);
                    });
            });
        });
    } else {
        console.warn("No .view-location buttons found in the DOM.");
    }
});

   </script>


@include("inc_pages.suveryfooter")


    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            "pageLength": 5, // Set the number of rows per page to 5
            "lengthMenu": [5, 10, 15, 20], // Options for pagination
        });
    });
    </script>
<script type="text/javascript" src="//code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function () {
            $('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        }
    );
    $(document).ready(function () {
        $('#tblSub1View').dataTable({
            "bJQueryUI": true,
            "sPaginationType": "full_numbers",
            "bDestroy": true,
            "aoColumnDefs": [{
                'bSortable': false,
                'aTargets': [0, 1]
            }],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "iDisplayLength": 10,
        });
    });

    $.get('/api/Survey/home_api', function (data) {
        console.log(data);
    })

</script>
