@extends('admin.layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="main-content">
        <div class="container mt-5">
            <h2 class="text-center mb-4">Update Your Highlights</h2>

            <!-- Toastr Notifications -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

            <style>
                .btn-primary {
                    background: linear-gradient(45deg, #EA2930, #FF6868);
                    border: none;
                }

                .btn-primary:hover {
                    background: linear-gradient(45deg, #FF6868, #EA2930);
                }
            </style>

            <form id="video-update-form" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Embed URL Input Field -->
                <div class="mb-3">
                    <label for="embed_url" class="form-label">Embed URL</label>
                    <input type="text" class="form-control" name="video" id="embed_url"
                        placeholder="Enter your video embed URL here" required
                        value="{{ old('video', $highlight->video) }}">
                </div>

                <!-- Video Preview Container -->
                <div class="mb-3">
                    <label class="form-label">Video Preview</label>
                    <div id="videoPreview" style="border: 1px solid #ddd; padding: 10px;">
                        @if ($highlight->video)
                            @php
                                preg_match(
                                    '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                                    $highlight->video,
                                    $matches,
                                );
                                $videoID = $matches[1] ?? '';
                            @endphp
                            @if ($videoID)
                                <iframe width="560" height="315"
                                    src="https://www.youtube.com/embed/Ainhc_c1vvg?si=rDiHOGLqqYtRrwb8"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            @else
                                <p>No valid video preview available.</p>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Video Title Input -->
                <div class="mb-3">
                    <label for="title" class="form-label">Video Title</label>
                    <input type="text" class="form-control" name="title" id="title"
                        value="{{ old('title', $highlight->title) }}" placeholder="Enter video title">
                </div>

                <button type="submit" class="btn btn-primary w-100" id="update-btn">
                    <span id="btn-text">Update Video</span>
                    <span id="btn-loader" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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
                $('#videoPreview').html('<iframe width="100%" height="315" src="' + embedUrl +
                    '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
                );
            } else {
                $('#videoPreview').html('<p>No valid video preview available.</p>');
            }
        });

        // Form submission logic remains unchanged
        document.getElementById('video-update-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let updateBtn = document.getElementById('update-btn');
            let btnText = document.getElementById('btn-text');
            let btnLoader = document.getElementById('btn-loader');

            // Show loader
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');

            fetch("{{ route('video.update', $highlight->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Hide loader
                    btnText.classList.remove('d-none');
                    btnLoader.classList.add('d-none');

                    if (data.success) {
                        toastr.success('Video updated successfully!');
                        setTimeout(() => {
                            window.location.href = '{{ route('highlight.mainview') }}';
                        }, 2000);
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred. Please try again.');
                    btnText.classList.remove('d-none');
                    btnLoader.classList.add('d-none');
                });
        });
    </script>
@endsection
