@extends('admin.layout.app')
@section('title', 'Farmers')
@section('content')

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Hightlights</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (Auth::guard('admin')->check() ||
                                        $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Farmers' &&
                                                $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white"
                                        href="{{ route('event.createview') }}">Create</a>
                                @endif

                                <table class="table responsive" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>Start Time</th>
                                            <th>Slider Images</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @php
                                                    @endphp
                                                    @if ($event->eventImages->isNotEmpty())
                                                        @php
                                                            $coverImage =
                                                                $event->eventImages->firstWhere('is_cover', 1) ??
                                                                $event->eventImages->first();
                                                            // dd($event->eventImages);
                                                        @endphp
                                                        <img src=" {{ $coverImage->image_path }}" alt="Event Image"
                                                            class="event-image">
                                                    @else
                                                        <img src="https://via.placeholder.com/250x150.png?text=No+Image"
                                                            alt="Event Image" class="event-image">
                                                    @endif
                                                </td>

                                                <td>{{ $event->title }}</td>
                                                <td>{{ $event->description }}</td>
                                                <td>{{ $event->date }}</td>
                                                <td>{{ $event->Location }}</td>
                                                <td>{{ $event->start_time }}</td>
                                                <td
                                                    class="d-flex justify-content-center align-items-center gap-2 align-items-center">
                                                    <button class="btn btn-danger">
                                                        <a href="{{ route('event.addMoreview', $event->id) }}"
                                                            class="text-danger">Add
                                                            More</a>
                                                    </button>
                                                    <button class="btn btn-danger">
                                                        <a href="{{ route('event.sliderimagesview', $event->id) }}">Slider
                                                            Images
                                                            Details</a></button>
                                                </td>

                                                <td>
                                                    <div class="d-flex gap-4">
                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Farmers' &&
                                                                        $permission['permissions']->contains('edit')))
                                                            <a href="{{ route('event.updateview', $event->id) }}"
                                                                class="btn btn-primary"
                                                                style="margin-right: 10px; padding:6px 6px">Edit</a>
                                                        @endif

                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Farmers' &&
                                                                        $permission['permissions']->contains('delete')))
                                                            <!-- Example of a delete button in your Blade file -->
                                                            <form id="delete-form-{{ $event->id }}"
                                                                action="{{ route('event.delete', $event->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE') <!-- Spoof DELETE request -->
                                                            </form>

                                                            <!-- Trigger button -->
                                                            <button class="show_confirm btn btn-danger d-flex gap-4"
                                                                data-form="delete-form-{{ $event->id }}" type="button">
                                                                Delete Record
                                                            </button>
                                                        @endif
                                                </td>
                            </div>
                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>

@endsection

@section('js')

    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable()
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var formId = $(this).data("form");
            var form = document.getElementById(formId);
            event.preventDefault();
            swal({
                    title: "Are you sure?",
                    text: "This action cannot be undone!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // Send AJAX request
                        $.ajax({
                            url: form.action,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                // सफलता टोस्ट दिखाएं और 3 सेकंड बाद पेज रिफ्रेश करें
                                swal({
                                    title: "Success!",
                                    text: "Record deleted successfully!",
                                    icon: "success",
                                    button: false,
                                    timer: 3000
                                }).then(() => {
                                    location.reload(); // टोस्ट बंद होने पर पेज रिफ्रेश
                                });
                            },
                            error: function(xhr) {
                                swal("Error!", "Failed to delete record.", "error");
                            }
                        });
                    }
                });
        });
    </script>

@endsection
