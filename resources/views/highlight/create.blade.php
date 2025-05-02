@extends('admin.layout.app')

@section('title', 'Create Video Highlight')

@section('content')
    <div class="container mt-5">
        <style>
            :root {
                --bs-primary: #EA2930;
                --bs-secondary: #FF6868;
                --text-color: #ffffff;
                --gradient-bg: linear-gradient(90deg, #EA2930, #FF6868);
            }

            .btn-primary {
                background: var(--gradient-bg);
                border: none;
                color: var(--text-color);
                transition: background 0.3s ease;
            }

            .btn-primary:hover {
                background: var(--bs-secondary);
            }

            .form-control:focus {
                border-color: var(--bs-secondary);
                box-shadow: 0 0 0 0.25rem rgba(255, 104, 104, 0.5);
            }

            .card-header {
                background: var(--gradient-bg) !important;
                color: white !important;
            }

            .loading-spinner {
                display: none;
                width: 18px;
                height: 18px;
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

            /* Preview container styling */
            #videoPreview iframe {
                width: 100%;
                max-width: 560px;
                height: 315px;
            }
        </style>

        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-light">Create Your Video Highlight</h4>
                </div>
                <div class="card-body">
                    <form id="highlightForm" method="POST" action="{{ route('highlight.create') }}">
                        @csrf
                        <!-- Embed URL input field -->
                        <div class="mb-3">
                            <label for="embed_url" class="form-label">Embed URL</label>
                            <input type="text" class="form-control" name="video" id="embed_url"
                                placeholder="Enter your video embed URL here" required>
                        </div>
                        <!-- Title input field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="title"
                                placeholder="Write your video title here" required>
                        </div>
                        <!-- Video preview container -->
                        <div class="mb-3">
                            <label class="form-label">Video Preview</label>
                            <div id="videoPreview" style="border: 1px solid #ddd; padding: 10px;">
                                <!-- Embed preview will appear here -->
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                            <span class="loading-spinner me-2"></span>
                            <span class="btn-text">Submit</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Toastr -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
        $(document).ready(function() {
            // Function to extract YouTube video ID from URL
            function extractVideoID(url) {
                var videoID = '';
                var match = url.match(
                    /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/
                );
                if (match && match[1]) {
                    videoID = match[1];
                }
                return videoID;
            }

            // Embed URL input field change event to show preview
            $('#embed_url').on('input', function() {
                var url = $(this).val().trim();
                var videoID = extractVideoID(url);
                if (videoID !== '') {
                    var embedUrl = 'https://www.youtube.com/embed/' + videoID;
                    $('#videoPreview').html('<iframe src="' + embedUrl +
                        '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                    );
                } else {
                    $('#videoPreview').html('');
                }
            });

            // Form submission via AJAX
            $('#highlightForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();
                let submitBtn = $(this).find('button[type="submit"]');
                let spinner = submitBtn.find('.loading-spinner');
                let btnText = submitBtn.find('.btn-text');

                submitBtn.prop('disabled', true);
                spinner.show();
                btnText.text('Uploading...');

                $.ajax({
                    url: "{{ route('highlight.create') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success('✅ Video Uploaded Successfully!');
                        setTimeout(function() {
                            window.location.href = "{{ route('highlight.view') }}";
                        }, 2000);
                    },
                    error: function(response) {
                        toastr.error('❌ Something went wrong. Please try again.');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.hide();
                        btnText.text('Submit');
                    }
                });
            });
        });
    </script>
@endsection
