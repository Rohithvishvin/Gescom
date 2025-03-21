@include('inc_admin.header')


<head>

    <title style="background-color:black;color:white;">All Project Heads</title>


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
                            <h4 class="mb-sm-0">All Project Heads</h4>
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

                <div class="row">
                    <div class="col-xl-1209">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" id="myForm" action="{{url('/')}}/project-heads/show-users">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="form-label" for="product-title-input">
                                                    Mobile Number
                                                </label>
                                                <input type="text" class="form-control" name="filter_mobile"
                                                       id="filter_mobile">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-secondary">Search</button>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-secondary">Reset Filter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <!-- end card header -->

                            <div class="card-body">

                                <div class="live-preview">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-nowrap align-middle mb-0">
                                            <thead>
                                            <tr>
                                                <th scope="col">Sl. No.</th>
                                                <th scope="col">UserName</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Mobile Number</th>
                                                {{--                                                    <th scope="col">SO Pincode</th>--}}
                                                {{--                                                    <th scope="col">SD Pincode</th>--}}
                                                {{--                                                    <th scope="col">Created At</th>--}}
                                                <th scope="col">Active</th>
                                                <th scope="col">Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {{-- {{dfdfdf#$$$df}} --}}
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach ($show_users as $user)
                                                @php
                                                    $count++;
                                                    if($user->password == "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"){
                                                        $active_text = 'In Active';
                                                    }
                                                    else{
                                                        $active_text = 'Active';
                                                    }
                                                @endphp
                                                <tr @if($active_text == 'In Active') style="background-color: grey" @endif>
                                                    <td>{{ $count }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->type }}</td>
                                                    <td>{{ $user->phone }}</td>
                                                    {{--                                                        <td>{{ $user->so_pincode }}</td>--}}
                                                    {{--                                                        <td>{{ $user->sd_pincode }}</td>--}}
                                                    {{--                                                        <td>{{ $user->created_at }}</td>--}}
                                                    <td>{{ $active_text }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editUserModal{{$user->id}}">Edit
                                                        </button>
                                                        <div class="modal" id="editUserModal{{$user->id}}" tabindex="-1"
                                                             role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-xl"
                                                                 role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Edit User</h4>
                                                                        <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <h2 id="modal-message" style="color:red"></h2>
                                                                        <h2 id="modal-difference-message"
                                                                            style="color:red"></h2>
                                                                        <div class="row">
                                                                            <form id="changePasswordForm{{$user->id}}"
                                                                                  autocomplete="off"
                                                                                  class="needs-validation"
                                                                                  action="{{ url('/') }}/project-heads/update-user-data/{{$user->id}}"
                                                                                  method="POST"
                                                                                  enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="row">
                                                                                    <div class="col-lg-12">
                                                                                        <div class="card">
                                                                                            <div class="card-body">
                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="product-title-input">User</label>
                                                                                                    <input type="hidden"
                                                                                                           class="form-control"
                                                                                                           name="user_id"
                                                                                                           value="{{$user->id}}">
                                                                                                </div>

                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="product-title-input">User
                                                                                                        Name</label>
                                                                                                    <input type="text"
                                                                                                           class="form-control"
                                                                                                           value="{{$user->name}}"
                                                                                                           readonly>
                                                                                                </div>

                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="product-title-input">User
                                                                                                        Type</label>
                                                                                                    <input type="text"
                                                                                                           class="form-control"
                                                                                                           value="{{$user->type}}"
                                                                                                           readonly>
                                                                                                </div>

                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="product-title-input">Mobile</label>
                                                                                                    <input type="text"
                                                                                                           class="form-control"
                                                                                                           value="{{$user->phone}}"
                                                                                                           readonly>
                                                                                                </div>

                                                                                                <div class="mb-3">
{{--                                                                                                    <div--}}
{{--                                                                                                        class="form-check form-switch">--}}
{{--                                                                                                        <input--}}
{{--                                                                                                            class="form-check-input"--}}
{{--                                                                                                            type="checkbox"--}}
{{--                                                                                                            role="switch"--}}
{{--                                                                                                            name="userActiveStatus"--}}
{{--                                                                                                        >--}}
{{--                                                                                                        <label--}}
{{--                                                                                                            class="form-check-label"--}}
{{--                                                                                                            for="userActiveStatus">Active</label>--}}
{{--                                                                                                    </div>--}}
                                                                                                    <div class="form-check">
                                                                                                        <input class="form-check-input" type="radio" name="userActiveStatus" id="userActiveStatus1" value="active" @if($active_text === "Active") checked @endif>
                                                                                                        <label class="form-check-label" for="userActiveStatus1">
                                                                                                            Active
                                                                                                        </label>
                                                                                                    </div>
                                                                                                    <div class="form-check">
                                                                                                        <input class="form-check-input" type="radio" name="userActiveStatus" id="userActiveStatus2" value="inactive" @if($active_text === "In Active") checked @endif>
                                                                                                        <label class="form-check-label" for="userActiveStatus2">
                                                                                                            In Active
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="password">
                                                                                                        Password
                                                                                                    </label>
                                                                                                    <div class="row">
                                                                                                        <div
                                                                                                            class="col-md-6">
                                                                                                            <input
                                                                                                                type="password"
                                                                                                                class="form-control password"
                                                                                                                name="password"
                                                                                                                value=""
                                                                                                                placeholder="Enter Password"
                                                                                                                id="password{{$user->id}}"
                                                                                                                data-showpasswordtext="password-strength-status-{{$user->id}}">
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="col-md-6">
                                                                                                            <button
                                                                                                                class="btn btn-outline-secondary show-password-button"
                                                                                                                type="button" onclick="showPassword('password{{$user->id}}')">
                                                                                                                <i class="bi bi-eye"></i>
                                                                                                                <span>Show Password</span>
                                                                                                            </button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="password-strength">
                                                                                                        <p>Minimum
                                                                                                            Requirements:</p>
                                                                                                        <ul>
                                                                                                            <li>At least
                                                                                                                8
                                                                                                                characters
                                                                                                                long
                                                                                                            </li>
                                                                                                            <li>Must
                                                                                                                include
                                                                                                                2
                                                                                                                uppercase
                                                                                                                letters
                                                                                                            </li>
                                                                                                            <li>Must
                                                                                                                include
                                                                                                                2
                                                                                                                lowercase
                                                                                                                letters
                                                                                                            </li>
                                                                                                            <li>Must
                                                                                                                include
                                                                                                                2
                                                                                                                numbers
                                                                                                            </li>
                                                                                                            <li>Must
                                                                                                                include
                                                                                                                alphanumeric
                                                                                                                characters
                                                                                                                (letters
                                                                                                                and
                                                                                                                numbers)
                                                                                                            </li>
                                                                                                        </ul>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        id="password-strength-status-{{$user->id}}"></div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <!-- end card -->


                                                                                        <div class="text-end mb-3">
                                                                                            <button type="button"
                                                                                                    class="btn btn-secondary"
                                                                                                    data-bs-dismiss="modal">
                                                                                                No
                                                                                            </button>
                                                                                            <button type="submit"
                                                                                                    class="btn btn-success w-sm">
                                                                                                Submit
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- end col -->


                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                    {{--                            <div class="modal-footer">--}}
                                                                    {{--                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>--}}
                                                                    {{--                                <button type="button" class="btn btn-primary"--}}
                                                                    {{--                                        onclick="checkFormValidationResult('skip_check')">Yes--}}
                                                                    {{--                                </button>--}}
                                                                    {{--                            </div>--}}
                                                                </div>
                                                            </div>
                                                        </div>
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
                    <!-- end col -->
                </div>
                <!-- end row -->

                {{--                <div class="modal" id="editUserModal" tabindex="-1" role="dialog" aria-hidden="true">--}}
                {{--                    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">--}}
                {{--                        <div class="modal-content">--}}
                {{--                            <div class="modal-header">--}}
                {{--                                <h4 class="modal-title">Edit User</h4>--}}
                {{--                                <button type="button" class="btn-close" data-bs-dismiss="modal"--}}
                {{--                                        aria-label="Close"></button>--}}
                {{--                            </div>--}}
                {{--                            <div class="modal-body">--}}
                {{--                                <h2 id="modal-message" style="color:red"></h2>--}}
                {{--                                <h2 id="modal-difference-message" style="color:red"></h2>--}}
                {{--                                <div class="row">--}}
                {{--                                    <form id="changePasswordForm" autocomplete="off" class="needs-validation"--}}
                {{--                                          action="{{ url('/') }}/update_user_password" method="POST"--}}
                {{--                                          enctype="multipart/form-data">--}}
                {{--                                        @csrf--}}
                {{--                                        <div class="row">--}}
                {{--                                            <div class="col-lg-12">--}}
                {{--                                                <div class="card">--}}
                {{--                                                    <div class="card-body">--}}
                {{--                                                        <div class="mb-3">--}}
                {{--                                                            <label class="form-label"--}}
                {{--                                                                   for="product-title-input">User</label>--}}
                {{--                                                            <input type="hidden" class="form-control" name="user_id"--}}
                {{--                                                                   value="" id="selectedUserId">--}}
                {{--                                                        </div>--}}

                {{--                                                        <div class="mb-3">--}}
                {{--                                                            <label class="form-label"--}}
                {{--                                                                   for="product-title-input">User</label>--}}
                {{--                                                            <input type="hidden" class="form-control" name="user_id"--}}
                {{--                                                                   value="" id="selectedUserId">--}}
                {{--                                                        </div>--}}

                {{--                                                        <div class="mb-3">--}}
                {{--                                                            <div class="form-check form-switch">--}}
                {{--                                                                <input class="form-check-input" type="checkbox" role="switch" id="userActiveStatus" name="userActiveStatus">--}}
                {{--                                                                <label class="form-check-label" for="userActiveStatus">Active</label>--}}
                {{--                                                            </div>--}}
                {{--                                                        </div>--}}

                {{--                                                        <div class="mb-3">--}}
                {{--                                                            <label class="form-label" for="password">--}}
                {{--                                                                Password--}}
                {{--                                                            </label>--}}
                {{--                                                            <div class="row">--}}
                {{--                                                                <div class="col-md-6">--}}
                {{--                                                                    <input type="password" class="form-control"--}}
                {{--                                                                           id="password" name="password" value=""--}}
                {{--                                                                           placeholder="Enter Password"></div>--}}
                {{--                                                                <div class="col-md-6">--}}
                {{--                                                                    <button class="btn btn-outline-secondary"--}}
                {{--                                                                            type="button"--}}
                {{--                                                                            id="show-password-button">--}}
                {{--                                                                        <i class="bi bi-eye"></i>--}}
                {{--                                                                        <span>Show Password</span>--}}
                {{--                                                                    </button>--}}
                {{--                                                                </div>--}}
                {{--                                                            </div>--}}
                {{--                                                            <div class="password-strength">--}}
                {{--                                                                <p>Minimum Requirements:</p>--}}
                {{--                                                                <ul>--}}
                {{--                                                                    <li>At least 8 characters long</li>--}}
                {{--                                                                    <li>Must include 2 uppercase letters</li>--}}
                {{--                                                                    <li>Must include 2 lowercase letters</li>--}}
                {{--                                                                    <li>Must include 2 numbers</li>--}}
                {{--                                                                    <li>Must include alphanumeric characters (letters--}}
                {{--                                                                        and numbers)--}}
                {{--                                                                    </li>--}}
                {{--                                                                </ul>--}}
                {{--                                                            </div>--}}
                {{--                                                            <div id="password-strength-status"></div>--}}
                {{--                                                        </div>--}}
                {{--                                                    </div>--}}
                {{--                                                </div>--}}
                {{--                                                <!-- end card -->--}}


                {{--                                                <div class="text-end mb-3">--}}
                {{--                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>--}}
                {{--                                                    <button type="submit" class="btn btn-success w-sm">Submit</button>--}}
                {{--                                                </div>--}}
                {{--                                            </div>--}}
                {{--                                            <!-- end col -->--}}


                {{--                                        </div>--}}
                {{--                                    </form>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                            <div class="modal-footer">--}}
                {{--                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>--}}
                {{--                                <button type="button" class="btn btn-primary"--}}
                {{--                                        onclick="checkFormValidationResult('skip_check')">Yes--}}
                {{--                                </button>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}


            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->


@include('inc_admin.footer')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
<script>
    function editUser(userId, activeText) {
        console.log(userId);
        $('#selectedUserId').val(userId);
        if (activeText == 'Active') {
            $('#userActiveStatus').attr('checked', true);
        } else {
            $('#userActiveStatus').attr('checked', false);
        }
        $('#editUserModal').modal('show');
    }
</script>
<script>
    const passwordInput = document.querySelector('.password');
    const showPasswordButton = document.querySelector('.show-password-button');

    function showPassword(passwordEle){
        const passwordInput = document.getElementById(passwordEle);
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        const icon = showPasswordButton.querySelector('i');
        const label = showPasswordButton.querySelector('span');

        if (type === 'password') {
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
            label.textContent = 'Show Password';
        } else {
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
            label.textContent = 'Hide Password';
        }
    }
    showPasswordButton.addEventListener('click', () => {

    });


    $(".password").on('keyup', function () {
        var number = /([0-9])/;
        var alphabets = /([a-zA-Z])/;
        var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<,.])/;
        var passwordValue = $(this).val();
        let passwordStrengthStatusElement = $('#'+ $(this).data('showpasswordtext'));
        if (passwordValue.length < 8) {
            passwordStrengthStatusElement.removeClass();
            passwordStrengthStatusElement.addClass('weak-password');
            passwordStrengthStatusElement.html("Weak (should be atleast 8 characters.)");
        } else {
            if (passwordValue.match(number) && passwordValue.match(alphabets) && passwordValue.match(special_characters)) {
                passwordStrengthStatusElement.removeClass();
                passwordStrengthStatusElement.addClass('strong-password');
                passwordStrengthStatusElement.html("Strong");
            } else {
                passwordStrengthStatusElement.removeClass();
                passwordStrengthStatusElement.addClass('medium-password');
                passwordStrengthStatusElement.html(
                    "Medium (should include alphabets, numbers and special characters or some combination.)"
                );
            }
        }
    });


    function numberOnly(id) {
        let input = document.getElementById(id);
        let value = input.value;
        if (value.length > input.maxLength) {
            input.value = value.substring(0, input.maxLength);
        }
    }
</script>
