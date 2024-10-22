@extends('layouts.app')

@section('title', 'Report a Concern')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="mb-4">Report a Concern</h1>
            <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" id="complaintForm">
                @csrf
                <input type="hidden" name="anonymous" value="1">

                @guest
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Anonymity</h5>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="anonymousCheckbox" name="anonymous" value="0" checked>
                                <label class="form-check-label" for="anonymousCheckbox">File Anonymously</label>
                            </div>
                        </div>
                    </div>

                    <div id="userInfoFields" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Personal Information</h5>
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}">
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Address Information</h5>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="zip" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="zip" name="zip" value="{{ old('zip') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Report Details</h5>
                        <div class="mb-3">
                            <label for="complaint_type" class="form-label">Concern Category</label>
                            <select class="form-select" id="complaint_type" name="complaint_type" required>
                                <option selected disabled>Select a category</option>
                                <option value="Animal Control" {{ old('complaint_type') == 'Animal Control' ? 'selected' : '' }}>Animal Control</option>
                                <option value="City Planning" {{ old('complaint_type') == 'City Planning' ? 'selected' : '' }}>City Planning</option>
                                <option value="Community Services" {{ old('complaint_type') == 'Community Services' ? 'selected' : '' }}>Community Services</option>
                                <option value="Education" {{ old('complaint_type') == 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Environmental Concerns" {{ old('complaint_type') == 'Environmental Concerns' ? 'selected' : '' }}>Environmental Concerns</option>
                                <option value="Infrastructure" {{ old('complaint_type') == 'Infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                                <option value="Public Health" {{ old('complaint_type') == 'Public Health' ? 'selected' : '' }}>Public Health</option>
                                <option value="Public Safety" {{ old('complaint_type') == 'Public Safety' ? 'selected' : '' }}>Public Safety</option>
                                <option value="Parks and Recreation" {{ old('complaint_type') == 'Parks and Recreation' ? 'selected' : '' }}>Parks and Recreation</option>
                                <option value="Transportation" {{ old('complaint_type') == 'Transportation' ? 'selected' : '' }}>Transportation</option>
                                <option value="Utilities" {{ old('complaint_type') == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="Waste Management" {{ old('complaint_type') == 'Waste Management' ? 'selected' : '' }}>Waste Management</option>
                                <option value="other" {{ old('complaint_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="mb-3" id="custom_type_div" style="display: none;">
                            <label for="custom_type" class="form-label">Custom Type</label>
                            <input type="text" class="form-control" id="custom_type" name="custom_type" value="{{ old('custom_type') }}">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="incident_date" class="form-label">Incident Date and Time</label>
                            <div style="max-width: 250px; display: inline-block;">
                                <input type="datetime-local" class="form-control" id="incident_date" name="incident_date" value="{{ old('incident_date') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="street_address" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="street_address" name="street_address" value="{{ old('street_address') }}">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">City Information</h5>
                        <div class="mb-3">
                            <label for="city_address" class="form-label">Address</label>
                            <div>
                                <input type="text" class="form-control" id="city_address" name="city_address" value="{{ old('city_address') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="person_name" class="form-label">Person's Name</label>
                            <div>
                                <input type="text" class="form-control" id="person_name" name="person_name" value="{{ old('person_name') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="person_number" class="form-label">Person's Phone</label>
                            <div>
                                <input type="text" class="form-control" id="person_number" name="person_number" value="{{ old('person_number') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="person_email" class="form-label">Person's Email</label>
                            <div>
                                <input type="email" class="form-control" id="person_email" name="person_email" value="{{ old('person_email') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Attachments</h5>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Upload Files (Images, Videos, Documents)</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 10px;
        background-color: #ffffff;
    }

    .form-check-input {
        width: 16px;
        height: 16px;
    }
</style>

<script>
    @guest
    document.getElementById('anonymousCheckbox').addEventListener('change', function() {
        var userInfoFields = document.getElementById('userInfoFields');
        var addressFields = document.getElementById('addressFields');
        if (this.checked) {
            userInfoFields.style.display = 'none'; // Hide user info fields if anonymous
            addressFields.style.display = 'none'; // Hide address fields if anonymous
            // Clear the values of the user info and address fields
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('phone').value = '';
            document.getElementById('email').value = '';
            document.getElementById('password').value = '';
            document.getElementById('address').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('zip').value = '';
        } else {
            userInfoFields.style.display = 'block'; // Show user info fields if not anonymous
            addressFields.style.display = 'block'; // Show address fields if not anonymous
            // The hidden input will ensure that anonymous is set to 0
        }
    });

    document.getElementById('complaintForm').addEventListener('submit', function(e) {
        console.log('Form action:', this.action); // Log the form action URL
        console.log('Anonymous value:', document.querySelector('input[name="anonymous"]').value); // Log the anonymous value

        var anonymous = document.getElementById('anonymousCheckbox').checked;
        if (!anonymous) {
            var requiredFields = ['first_name', 'last_name', 'phone', 'email', 'password', 'address', 'city', 'state', 'zip'];
            for (var i = 0; i < requiredFields.length; i++) {
                var field = document.getElementById(requiredFields[i]);
                if (!field.value) {
                    e.preventDefault(); // Prevent form submission only if validation fails
                    alert('Please fill in all required fields.'); // Alert user
                    return; // Exit the function
                }
            }
        }
        console.log('Form is valid, submitting...'); // Log for debugging
        // Form will submit normally if all validations pass
    });
    @endguest

    document.addEventListener('DOMContentLoaded', function() {
      let statesData = @json($states);

      const stateSelect = document.getElementById('state');
      const citySelect = document.getElementById('city');

      stateSelect.addEventListener('change', function() {
        const stateId = this.value;
        citySelect.innerHTML = '<option selected disabled>Select a city</option>';
        citySelect.disabled = true;

        const selectedState = statesData.find(state => state.id == stateId);
        if (selectedState) {
          selectedState.cities.forEach(city => {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            citySelect.appendChild(option);
          });
          citySelect.disabled = false;
        }
      });
    });

    document.getElementById('complaint_type').addEventListener('change', function() {
      var customTypeDiv = document.getElementById('custom_type_div');
      if (this.value === 'other') {
        customTypeDiv.style.display = 'block';
      } else {
        customTypeDiv.style.display = 'none';
      }
    });
</script>
@endsection

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
