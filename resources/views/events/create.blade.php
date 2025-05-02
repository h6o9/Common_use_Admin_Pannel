@extends('admin.layout.app')
@section('title', 'Create Event')
@section('content')

    <style>
        .form-container {
            max-width: 800px;
            background: var(--gradient-bg);
            color: var(--text-color);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            margin: 50px auto;
        }

        .btn-submit {
            background: linear-gradient(90deg, #EA2930, #FF6868);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid white;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .text-danger {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 5px;
            display: none;
        }

        .custom-file-label::after {
            content: "Browse";
        }
    </style>

    <div class="main-content">
        <div class="card shadow">
            <div class="card-body">
                <div class="form-container">
                    <h3 class="font-weight-bold text-center mb-4">Create New Event</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="eventForm" action="{{ route('event.create') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Images Upload -->
                        <div class="form-group mb-4">
                            <label>Event Images (Multiple)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="images" name="images[]" multiple>
                                <label class="custom-file-label" for="images">Choose files</label>
                            </div>
                            <span class="text-danger" id="imageError">At least one image is required.</span>
                        </div>

                        <!-- Title Input -->
                        <div class="form-group mb-4">
                            <label>Event Title</label>
                            <input type="text" class="form-control" name="title" placeholder="Enter event title">
                            <span class="text-danger" id="titleError">Title is required.</span>
                        </div>

                        <!-- Description Input -->
                        <div class="form-group mb-4">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Enter event description"></textarea>
                            <span class="text-danger" id="descriptionError">Description is required.</span>
                        </div>

                        <!-- Date, Start Time, and Location -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Date</label>
                                    <input type="date" class="form-control" name="date">
                                    <span class="text-danger" id="dateError">Date is required.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Start Time</label>
                                    <input type="time" class="form-control" name="start_time" id="start_time"
                                        placeholder="Enter event start time">
                                    <span class="text-danger" id="startTimeError">Start time is required.</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Location</label>
                                    <input type="text" class="form-control" name="location" placeholder="Enter location">
                                    <span class="text-danger" id="locationError">Location is required.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="d-flex justify-content-center align-items-center btn-submit w-100">
                            <span class="button-text">Create Event</span>
                            <span class="loading-spinner"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
        $(document).ready(function() {
            // Update file input label
            $('#images').on('change', function() {
                let files = $(this)[0].files;
                let label = files.length > 1 ?
                    files.length + ' files selected' :
                    files[0].name;
                $(this).next('.custom-file-label').html(label);
            });

            // Form submission handler
            $('#eventForm').on('submit', function(e) {
                e.preventDefault();
                let isValid = true;
                $(".text-danger").hide();

                // Validation checks
                if ($('#images')[0].files.length === 0) {
                    $('#imageError').show();
                    isValid = false;
                }
                if ($('input[name="title"]').val().trim() === '') {
                    $('#titleError').show();
                    isValid = false;
                }
                if ($('textarea[name="description"]').val().trim() === '') {
                    $('#descriptionError').show();
                    isValid = false;
                }
                if ($('input[name="date"]').val() === '') {
                    $('#dateError').show();
                    isValid = false;
                }
                if ($('input[name="start_time"]').val() === '') {
                    $('#startTimeError').show();
                    isValid = false;
                }
                if ($('input[name="location"]').val().trim() === '') {
                    $('#locationError').show();
                    isValid = false;
                }

                if (!isValid) return;

                // Show loading state
                $('.loading-spinner').show();
                $('.button-text').hide();
                $('.btn-submit').prop('disabled', true);

                // Create FormData object
                let formData = new FormData(this);

                // AJAX request
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success('Event created successfully!');
                        setTimeout(() => {
                            window.location.href = "{{ route('event.view') }}";
                        }, 1500);
                    },
                    error: function(xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                        $('.loading-spinner').hide();
                        $('.button-text').show();
                        $('.btn-submit').prop('disabled', false);
                    }
                });
            });
        });
    </script>

@endsection
