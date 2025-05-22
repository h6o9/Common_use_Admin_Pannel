@extends('admin.layout.app')
@section('title', 'Notifications')
@section('content')

    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12 d-flex justify-content-between">
                                    <h4>Notifications</h4>
                                    <!-- Create Button -->

                                    @if (Auth::guard('admin')->check() ||
                                            ($sideMenuPermissions->has('notification') && $sideMenuPermissions['notification']->contains('create')))
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#createAboutModal">
                                            <i class="fas fa-plus"></i> Create
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                <table class="table" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Image</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($item->image)
                                                        <img src="{{ asset('storage/' . $item->image) }}" width="100"
                                                            alt="About Us Image" required>
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! Str::limit(strip_tags($item->description), 200) !!}
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">

                                                        @if (Auth::guard('admin')->check() ||
                                                                ($sideMenuPermissions->has('notification') && $sideMenuPermissions['notification']->contains('edit')))
                                                            <a href="{{ route('admin.about-us.edit', $item->id) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endif

                                                        @if (Auth::guard('admin')->check() ||
                                                                ($sideMenuPermissions->has('notification') && $sideMenuPermissions['notification']->contains('delete')))
                                                            <form action="{{ route('admin.about-us.destroy', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger show_confirm">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No about us content found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createAboutModal" tabindex="-1" role="dialog" aria-labelledby="createAboutModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAboutModalLabel">Add New Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" placeholder="Upload Image" required
                                placeholder="Upload Image">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="5" required placeholder="Enter description"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#table_id_events').DataTable();
        });

        // SweetAlert for delete confirmation
        $('.show_confirm').click(function(event) {
            event.preventDefault();
            const form = $(this).closest("form");
            swal({
                title: 'Are you sure?',
                text: "This will be permanently deleted!",
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
