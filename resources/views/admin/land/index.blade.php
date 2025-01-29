@extends('admin.layout.app')
@section('title', 'Land Data Management')
@section('content')

    {{-- Add District Modal --}}
    <div class="modal fade" id="districtModal" tabindex="-1" role="dialog" aria-labelledby="districtModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="districtModalLabel">Add District</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('district.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Message Textbox -->
                        <div class="form-group">
                            <label for="message">District Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- Edit District Modal --}}
    @foreach ($lands as $land)
        <div class="modal fade" id="editdistrictModal-{{ $land->id }}" tabindex="-1" role="dialog"
            aria-labelledby="districtModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="districtModalLabel">Edit District</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('district.update', $land->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <!-- Message Textbox -->
                            <div class="form-group">
                                <label for="message">District Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $land->name }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach



    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12 d-flex justify-content-between">
                                    <h4>Land Data Management</h4>
                                    <a class="btn btn-primary" href="{{ route('units.index') }}">Area Units</a>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (Auth::guard('admin')->check() ||
                                        $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Land Data Management' &&
                                                $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3" href="#" data-toggle="modal"
                                        data-target="#districtModal">Add District</a>
                                @endif

                                <table class="table responsive" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>District</th>
                                            <th>Tehsil</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lands as $land)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $land->name }}</td>
                                                <td>
                                                    @if (Auth::guard('admin')->check() ||
                                                            $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Land Data Management' &&
                                                                    $permission['permissions']->contains('create')))
                                                        <a class="btn btn-primary"
                                                            href="{{ route('tehsil.index', $land->id) }}">Tehsil</a>
                                                    @endif

                                                </td>
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Land Data Management' &&
                                                                        $permission['permissions']->contains('edit')))
                                                            <a href="#" data-toggle="modal"
                                                                data-target="#editdistrictModal-{{ $land->id }}"
                                                                class="btn btn-primary" style="margin-left: 10px">Edit</a>
                                                        @endif

                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Land Data Management' &&
                                                                        $permission['permissions']->contains('delete')))
                                                            <form action="{{ route('district.destroy', $land->id) }}"
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 10px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-flat show_confirm"
                                                                    data-toggle="tooltip">Delete</button>
                                                            </form>
                                                        @endif

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
            var form = $(this).closest("form");
            var name = $(this).data("name");
            event.preventDefault();
            swal({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>

@endsection
