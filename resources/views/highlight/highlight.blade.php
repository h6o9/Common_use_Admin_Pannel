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
                                        href="{{ route('farmer.create') }}">Create</a>
                                @endif

                                <table class="table responsive" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Video</th>
                                            <th>Title</th>


                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($videos as $farmer)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <video width="200" controls src="{{ asset($farmer->video) }}">
                                                        {{-- <source src="{{ asset($farmer->video) }}" type="video/mp4"> --}}
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </td>

                                                <td>{{ $farmer->title }}</td>

                                                <td>
                                                    <div class="d-flex gap-4">
                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Farmers' &&
                                                                        $permission['permissions']->contains('edit')))
                                                            <a href="{{ route('highlight.updateview', $farmer->id) }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                        @endif

                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Farmers' &&
                                                                        $permission['permissions']->contains('delete')))
                                                            <!-- Example of a delete button in your Blade file -->
                                                            <form id="delete-form-{{ $farmer->id }}"
                                                                action="{{ route('highlight.delete', $farmer->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE') <!-- Spoof DELETE request -->
                                                            </form>

                                                            <!-- Trigger button -->
                                                            <button class="show_confirm btn btn-danger d-flex gap-4"
                                                                data-form="delete-form-{{ $farmer->id }}" type="button">
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
