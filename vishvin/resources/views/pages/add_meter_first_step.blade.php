@include("inc_pages.header")

<body class="fixed-bottom-padding">
  <div>
    <!-- <div class="p-3 shadow-sm bg-white border-bottom">
            <div class="d-flex align-items-center">
               <h5 class="fw-bold m-0">Add Account</h5> -->
    <!-- <a class="toggle ms-auto" href="#"><i class="bi bi-list "></i></a> -->
    <!-- </div>
         </div> -->
    <nav class="navbar fixed-top navbar-light bg-light">
      <div class="container-fluid">
        <div class="d-flex align-items-center">
          <a class="fw-bold text-success text-decoration-none" href="/pages/home">
            <i class="bi bi-arrow-left back-page"></i></a>
          <h6 class="fw-bold m-0 ms-3">
            <center> Enter Account ID OR RR Number</center>
          </h6>

        </div>
      </div>
    </nav>
    <div class="container col-sm-6" style="margin-top:80px;padding-left:50px;padding-right:50px;margin-bottom:50px;">
      <form action="/pages/check_rr_number" method="post" autocomplete="off" enctype="multipart/form-data">
        @csrf
      <div class="mb-3">
          <label class="form-label" for="product-title-input">Section code
              <span class="mandatory_star">*<sup><i>required</i></sup></span>
          </label>
          <select id="section_code" name="section_code" class="form-control" required>
              <option value="" selected>Select</option>
              @foreach ($so_pincodes as $so_code)
                  @if(!empty($filter_requests['so_code']) && $filter_requests['section_code'] === $so_code->so_code)
                      <option value="{{$so_code->so_code}}" selected>{{$so_code->so_code}}</option>
                  @else
                      <option value="{{$so_code->so_code}}" >{{$so_code->so_code}}</option>
                  @endif
              @endforeach
          </select>
{{--          <label for="account_id" class="form-label">Section Code <span class="mandatory_star">*<sup><i>required</i></sup></span></label>--}}
{{--          <input type="number" id="section_code" class="form-control" name="section_code" placeholder="Enter Your Section Code" required>--}}
      </div>
        <div class="mb-3">
          <label for="account_id" class="form-label">Account Id <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
          <input type="number" id="input1" class="form-control" name="account_id" placeholder="Enter Account ID">
        </div>
		  
        <p class="text-center">or</p>
        <div class="mb-3">
            <label for="account_id" class="form-label">RR Number <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
            <input type="text" id="input2" class="form-control" name="rr_number" placeholder="Enter RR Number">
        </div>
		    <!---  <p class="text-center" hidden>or</p>
        <div class="mb-3">
            <label for="account_id" class="form-label" hidden>RR Number <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
            <input type="text" id="input2" class="form-control" name="rr_number" placeholder="Enter RR Number" hidden>
        </div>
		  -->
		  





<!-- LOCATION AND DATE SHOULD BE AUTO FETCHED -->

        {{-- <button type="submit" class="btn btn-primary">Next</button> --}}
        <button class="btn btn-primary w-sm"  onclick="Swal.fire({icon: 'info',title: 'Please Wait, Uploading',showConfirmButton: false,timer: 500000})" class="btn btn-success btn-sm" type="submit" name="importSubmit" id="checkMeter">Next</button>
      </form>
    </div>
  </div>
  {{-- <div class="osahan-menu-fotter fixed-bottom bg-dark text-center m-3 shadow rounded py-2">
    <div class="row m-0">
      <a href="/pages/home" class="text-white col small text-decoration-none p-2">
        <p class="h5 m-0"><i class="fa-sharp fa-solid fa-house" style="color:white;"></i></p>Home
      </a>
      <a href="/pages/add_meter_first_step" class="text-white col small text-decoration-none p-2">
        <p class="h5 m-0"><i class="fa-solid fa-plus"></i></p>
        Add Meter
      </a>
      <a href="/pages/records" class="text-white col small text-decoration-none p-2">
        <p class="h5 m-0"><i class="icofont-bag"></i></p>
        Rejected Meters
      </a>
      <a href="/pages/account" class="text-white col small text-decoration-none p-2">
        <p class="h5 m-0"><i class="icofont-user"></i></p>
        Account
      </a>
    </div>
  </div> --}}

  @include("inc_pages.footer")
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  {{-- <script>
   $(document).ready(function() {
  $("#input1").click(function() {
    $("#input2").prop("disabled", !$("#input2").prop("disabled"));
  });

  $("#input2").click(function() {
    $("#input1").prop("disabled", !$("#input1").prop("disabled"));
  });
});

  </script> --}}

  <script>
      document.getElementById("checkMeter").addEventListener("click", function(event){
          // console.log(event);
          //event.preventDefault();
          document.getElementById('section_code').disabled = false;
          sessionStorage.setItem('section_code', document.getElementById('section_code').value );
          // console.log('here');
          //event.currentTarget.submit();
      });
      var temp_section_code = sessionStorage.getItem('section_code');
      if(temp_section_code != null){
          console.log(temp_section_code);
          document.getElementById('section_code').value = temp_section_code;
          document.getElementById('section_code').disabled = true;
      }
  </script>
