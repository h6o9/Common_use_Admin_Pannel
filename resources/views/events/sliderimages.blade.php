{{-- @php
$test = $event $image;
    return $test;
@endphp --}}
@if ($event->eventImages->isNotEmpty())
    @foreach ($event->eventImages as $image)
        <div class="image-card mb-3 p-3 border rounded">
            <!-- Display Image -->
            <img src="{{ asset('public/admin/assets/images/events/' . basename($image->image_path)) }}"
                class="img-thumbnail mb-2" style="max-width: 200px;">

            <!-- Image Path -->
            <div class="text-muted small mb-2">{{ $image->image_path }}</div>

            <!-- Action Buttons -->
            <div class="btn-group">
                <form action="{{ route('event.slidereditimage', $image->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="images[]" multiple>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </form>

                <form action="" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    @endforeach
@else
    <div class="alert alert-info">
        No images found for this event
    </div>
@endif

<!-- Add New Image Button -->
<a href="{{ route('event.addimage', $event->id) }}" class="btn btn-primary mt-3">
    <i class="fas fa-plus"></i> Add New Image
</a>
