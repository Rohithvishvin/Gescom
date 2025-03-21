<?php
$meter_mains = $data['meter_mains']; // This will be a collection of meter details
$consumer_details = $data['consumer_details']; // This will be an array of consumer details
?>

<body class="fixed-bottom-padding">
<div>
    <nav class="navbar fixed-top navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <h6 class="fw-bold m-0 ms-3">
                    Details of Electromechanical Meter
                </h6>
            </div>
        </div>
    </nav>

    <div class="container col-sm-8" style="margin-top:20px;padding-left:50px;padding-right:50px;margin-bottom:50px;">
        <form action="/Survey/update_old_meter_detail/{{ implode(',', $meter_mains->pluck('id')->toArray()) }}" method="post" autocomplete="off" enctype="multipart/form-data" id="oldMeterDetail">
            @csrf
        
            @foreach($meter_mains as $index => $meter_main)
                <?php 
                    $consumer_detail = $consumer_details[$index]; 
                    $meter_main_id = $meter_main->id;
                ?>
        
                @if ($loop->first)
                <div class="table-responsive mt-4">
                    <table class="table table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>Account Id</th>
                                <th>RR Number</th>
                                <th>Name of the Consumer</th>
                                <th>Consumer Address</th>
                                <th>Section</th>
                                <th>Subdivision</th>
                                <th>Meter Serial No</th>
                            </tr>
                        </thead>
                        <tbody>
                @endif
        
                <!-- Consumer Details -->
                <tr>
                    <td>{{ $consumer_detail->account_id }}</td>
                    <td>{{ $consumer_detail->rr_no }}</td>
                    <td>{{ $consumer_detail->consumer_name }}</td>
                    <td>{{ $consumer_detail->consumer_address }}</td>
                    <td>{{ $consumer_detail->so_pincode }}</td>
                    <td>{{ $consumer_detail->sd_pincode }}</td>
                    <td>
                        <!-- Input field for serial_no_old with the current value -->
                        <input type="text" name="serial_no_old[{{ $meter_main_id }}]" value="{{ old('serial_no_old.' . $meter_main_id, $meter_main->serial_no_old) }}" class="form-control" placeholder="Enter Serial Number" required>
                    </td>
                </tr>
        
                <!-- If this is the last item in the loop -->
                @if ($loop->last)
                </tbody>
                    </table>
                </div>
        
                <div class="mb-3">
                    <label for="image_1_old_temp" class="form-label">Photo 1 with readings on display <span
                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                    @if(!empty($meter_main->image_1_old))
                        <img src="{{asset($meter_main->image_1_old)}}" alt="photo1" style="height:47px;width:100%;">
                        <div style="display: none;">
                            <input type="hidden" name="image_1_old" value="{{$meter_main->image_1_old}}">
                        </div>
                    @endif
                    @if(empty($meter_main->image_1_old))
                        <input type="file" class="form-control" id="image_1_old_temp" data-refID="image_1_old"
                               accept="image/*" @if(empty($meter_main->image_1_old)) required @endif>
                        <div style="display: none;">
                            <input type="file" id="image_1_old" name="image_1_old" accept="image/*">
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <label for="image_2_old_temp" class="form-label">Photo 2 with readings on display <span
                            class="mandatory_star">*<sup><i>required</i></sup></span></label>
                    @if(!empty($meter_main->image_2_old))
                        <img src="{{asset($meter_main->image_2_old)}}" alt="photo2" style="height:47px;width:100%;">
                        <div style="display: none;">
                            <input type="hidden" name="image_2_old" value="{{$meter_main->image_2_old}}">
                        </div>
                    @endif
                    @if(empty($meter_main->image_2_old))
                        <input type="file" class="form-control" id="image_2_old_temp" data-refID="image_2_old"
                               accept="image/*" @if(empty($meter_main->image_2_old)) required @endif>
                        <div style="display: none;">
                            <input type="file" id="image_2_old" name="image_2_old" accept="image/*">
                        </div>
                    @endif
                </div>
                @endif
            @endforeach

            <!-- Hidden Inputs for Latitude, Longitude, Survey Status, and Geo Link -->
            <input type="hidden" name="latitude" id="latitude" value="12.9328852">
            <input type="hidden" name="longitude" id="longitude" value="77.5422609">
            <input type="hidden" name="survey_status" id="survey_status" value="0">
            <input type="hidden" name="geo_link" id="geo_link" value="">

            <!-- Action buttons -->
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary w-sm" type="submit">Next</button>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">Cancel</button>
            </div>
        </form>
        
    </div>
</div>

@include("inc_pages.suveryfooter")
</body>

<script>
    // Function to set latitude, longitude, and generate geo_link dynamically
    function setGeolocation() {
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        // Generate geo_link if latitude and longitude are provided
        if (latitude && longitude) {
            const geoLink = `https://www.google.com/maps?q=${latitude},${longitude}`;
            document.getElementById('geo_link').value = geoLink;
        }
    }

    // Call setGeolocation to update geo_link before form submission
    document.getElementById('oldMeterDetail').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        setGeolocation(); // Update the geo_link dynamically
        this.submit(); // Submit the form after updating geo_link
    });
</script>