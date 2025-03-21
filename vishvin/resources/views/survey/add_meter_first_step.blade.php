@include("inc_pages.header")

<body class="fixed-bottom-padding">


  <div>
    <nav class="navbar fixed-top navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="fw-bold text-success text-decoration-none" href="/Survey/home">
                    <i class="bi bi-arrow-left back-page"></i>
                </a>
                <h6 class="fw-bold m-0 ms-3">
                    <center> Enter Account ID OR RR Number</center>
                </h6>
            </div>
        </div>
    </nav>

    <div class="container col-sm-6" style="margin-top:80px;padding-left:50px;padding-right:50px;margin-bottom:50px;">
        <form action="/Survey/check_ack_number" method="post" autocomplete="off" enctype="multipart/form-data">
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
                            <option value="{{$so_code->so_code}}">{{$so_code->so_code}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="account_id" class="form-label">Account Id <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                <input type="text" id="account_id" class="form-control" name="account_id" placeholder="Enter Account ID">
                <div id="account-suggestions" class="dropdown-menu" style="width: 100%;"></div> <!-- Dropdown to display search results -->
            </div>

            <p class="text-center" hidden>or</p>
            <div class="mb-3">
                <label for="account_id" class="form-label" hidden>RR Number <span class="mandatory_star">*<sup><i>required</i></sup></span></label>
                <input type="text" id="input2" class="form-control" name="rr_number" placeholder="Enter RR Number" hidden>
            </div>

            <!-- Next Button -->
            <button class="btn btn-primary w-sm" onclick="Swal.fire({icon: 'info',title: 'Please Wait, Uploading',showConfirmButton: false,timer: 500000})" type="submit" name="importSubmit" id="checkMeter">Next</button>

            <!-- Selected Accounts Container -->
            <div id="selected-accounts" class="mt-3 text-center"></div>
        </form>
    </div>
</div>



  @include("inc_pages.suveryfooter")
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const accountIdInput = document.getElementById('account_id');
        const sectionCodeInput = document.getElementById('section_code'); // Corrected variable name
        const suggestionsDiv = document.getElementById('account-suggestions');
        const selectedAccountsDiv = document.getElementById('selected-accounts');
        let selectedAccountIds = []; // Store selected account IDs
    
        // Function to display selected accounts
        function displaySelectedAccounts() {
            selectedAccountsDiv.innerHTML = ''; // Clear the container
            selectedAccountIds.forEach((accountId, index) => {
                const accountChip = document.createElement('div');
                accountChip.classList.add('badge', 'bg-primary', 'me-1', 'mb-1');
                accountChip.textContent = accountId;
    
                // Add a remove button
                const removeButton = document.createElement('span');
                removeButton.classList.add('ms-2', 'cursor-pointer');
                removeButton.innerHTML = '×';
                removeButton.addEventListener('click', () => {
                    selectedAccountIds.splice(index, 1); // Remove the account ID
                    displaySelectedAccounts(); // Update the display
                    accountIdInput.value = selectedAccountIds.join(', '); // Update the input field
                });
    
                accountChip.appendChild(removeButton);
                selectedAccountsDiv.appendChild(accountChip);
            });
        }
    
        // Add event listener for 'input' event (when the user types)
        accountIdInput.addEventListener('input', function () {
            const accountId = accountIdInput.value.trim();
            const sectionCode = sectionCodeInput.value; // Get the selected section_code value
            console.log('Account ID:', accountId);
            console.log('Section Code:', sectionCode);
    
            // Check if the account ID is 5 digits or more and section_code is selected
            if (accountId.length >= 5 && sectionCode) {
                // Make an AJAX request to fetch the relevant account data from the backend
                fetch(`/getAccountSuggestions/${accountId}/${sectionCode}`)
                    .then((response) => response.json())
                    .then((data) => {
                        console.log('API Response:', data);
                        if (data.success && data.suggestions.length > 0) {
                            // Clear previous suggestions
                            suggestionsDiv.innerHTML = '';
    
                            // Add each suggestion to the dropdown menu
                            data.suggestions.forEach((item) => {
                                const suggestionItem = document.createElement('a');
                                suggestionItem.classList.add('dropdown-item');
                                suggestionItem.href = '#';
                                suggestionItem.textContent = `Account ID: ${item.account_id} - ${item.consumer_name}`;
    
                                // Attach a click event to select the suggestion
                                suggestionItem.addEventListener('click', function () {
                                    // If not already selected, add to the list
                                    if (!selectedAccountIds.includes(item.account_id)) {
                                        selectedAccountIds.push(item.account_id);
                                        accountIdInput.value = selectedAccountIds.join(', '); // Update input field with comma-separated values
                                        displaySelectedAccounts(); // Update the display of selected accounts
                                    }
                                    suggestionsDiv.innerHTML = ''; // Clear suggestions
                                });
    
                                suggestionsDiv.appendChild(suggestionItem);
                            });
    
                            // Show the dropdown menu
                            suggestionsDiv.classList.add('show');
                        } else {
                            // If no suggestions, clear the dropdown and show "No suggestions found"
                            suggestionsDiv.innerHTML = '<a class="dropdown-item">No suggestions found</a>';
                            suggestionsDiv.classList.add('show');
                        }
                    })
                    .catch((error) => {
                        console.error('Error fetching account suggestions:', error);
                        suggestionsDiv.innerHTML = '<a class="dropdown-item">Error fetching suggestions</a>';
                        suggestionsDiv.classList.add('show');
                    });
            } else {
                // Hide dropdown if the input is not 5 digits or section_code is not selected
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.classList.remove('show');
            }
        });
    
        // Hide dropdown when clicking outside the input or dropdown
        document.addEventListener('click', function (event) {
            const isClickInsideInput = accountIdInput.contains(event.target);
            const isClickInsideDropdown = suggestionsDiv.contains(event.target);
    
            // If the click is outside the input and dropdown, hide the dropdown
            if (!isClickInsideInput && !isClickInsideDropdown) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.classList.remove('show');
            }
        });
    });
</script>


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

