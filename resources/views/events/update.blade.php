@extends('admin.layout.app')
@section('title', 'eventUpdate')
@section('content')

    <style>
        .form-container {
            max-width: 500px;
            background: var(--gradient-bg);
            color: var(--text-color);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin: auto;
            margin-top: 50px;
        }

        .btn-submit {
            background: var(--gradient-bg);
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
            width: 100%;
            position: relative;
            display: inline-block;
            text-align: center;
        }

        .error-message {
            font-size: 14px;
            color: yellow;
        }

        label {
            font-weight: bold;
        }

        /* Loader Styles */
        .loading-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 3px solid white;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
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
            display: none;
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>

    <div class="main-content">
        <div class="card shadow mb-4">
            <div class="card-body">
                {{-- Main Heading --}}
                <h5 class="font-weight-bold text-dark text-center mb-4">Update Your Events Here</h5>

                {{-- Show Error Messages --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Show Success Message via Toastr on page load if session exists --}}
                @if (session('success'))
                    <script>
                        $(document).ready(function() {
                            toastr.success("Event updated successfully!");
                            setTimeout(function() {
                                window.location.href = "{{ route('event.view') }}";
                            }, 2000);
                        });
                    </script>
                @endif

                <form id="updateEventForm" action="{{ route('event.update', $find->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    {{-- Display Current Image --}}
                    <div class="mb-4 text-center">
                        @if ($find->eventImages->isNotEmpty())
                            @php
                                // Get first cover image or fallback to first image
                                $coverImage =
                                    $find->eventImages->firstWhere('is_cover', 1) ?? $find->eventImages->first();
                            @endphp

                            <img src="{{ asset('public/admin/assets/images/events/' . basename($coverImage->image_path)) }}"
                                alt="Event Cover" style="width: 200px; height: auto; object-fit: cover;">
                        @else
                            <div class="bg-secondary text-center py-4">
                                <i class="fas fa-image fa-3x text-light"></i>
                            </div>
                        @endif
                    </div>

                    <div class="form-group mb-4">
                        <label for="image">Event Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <span class="text-danger" id="imageError">Please upload an image.</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="title">Event Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            placeholder="Enter event title" value="{{ $find->title }}">
                        <span class="text-danger" id="titleError">Title is required.</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter event description">{{ $find->description }}</textarea>
                        <span class="text-danger" id="descriptionError">Description is required.</span>
                    </div>

                    <div class="row mb-4">
                        <div class="form-group col-md-6">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                placeholder="Enter date" value="{{ $find->date }}">
                            <span class="text-danger" id="dateError">Date is required.</span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="date">Time</label>
                            <input type="time" class="form-control" id="date" name="start_time"
                                placeholder="Enter date" value="{{ $find->start_time }}">


                        </div>

                        <div class="form-group col-md-6">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location"
                                placeholder="Enter location" value="{{ $find->Location }}">
                            <span class="text-danger" id="locationError">Location is required.</span>
                        </div>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn-submit btn-update-submit"
                            style="--gradient-bg: linear-gradient(90deg, #EA2930, #FF6868);">
                            <span class="button-text">Submit</span>
                            <div class="loading-spinner"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Toastr -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
        $(document).ready(function() {
            // Hide inline error messages when user focuses on the field.
            $('#updateEventForm input, #updateEventForm textarea').on('focus', function() {
                $(this).siblings('.text-danger').fadeOut();
            });

            $('#updateEventForm').on('submit', function(e) {
                e.preventDefault();

                // Optional client-side validation.
                var isValid = true;
                if ($('#title').val().trim() === '') {
                    $('#titleError').fadeIn();
                    isValid = false;
                }
                if ($('#description').val().trim() === '') {
                    $('#descriptionError').fadeIn();
                    isValid = false;
                }
                if ($('#date').val().trim() === '') {
                    $('#dateError').fadeIn();
                    isValid = false;
                }
                if ($('#location').val().trim() === '') {
                    $('#locationError').fadeIn();
                    isValid = false;
                }
                // If validation fails, stop submission.
                if (!isValid) return;

                // Show Loader on Submit Button
                $('.loading-spinner').show();
                $('.button-text').hide();
                $('.btn-update-submit').prop('disabled', true);

                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        toastr.success("Event updated successfully!");
                        setTimeout(function() {
                            window.location.href = "{{ route('event.view') }}";
                        }, 2000);
                    },
                    error: function(xhr) {
                        $('.loading-spinner').hide();
                        $('.button-text').show();
                        $('.btn-update-submit').prop('disabled', false);
                        toastr.error("Error updating event. Please try again.");
                    }
                });
            });
        });
    </script>

@endsection
