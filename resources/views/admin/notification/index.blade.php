@extends('admin.layout.app')
@section('title', 'Notifications')
@section('content')

<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Send Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('notification.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- User Type Dropdown -->
                    <div class="form-group">
                        <label for="userType">Select User Type</label>
                        <select class="form-control" id="userType" name="user_type" required>
                            <option value="" disabled selected>Select User Type</option>
                            <option value="all">All</option>
                            <option value="farmers">Farmers</option>
                            <option value="dealers">Authorized Dealers</option>
                        </select>
                    </div>
                    <!-- Message Textbox -->
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message here" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>



    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Notifications</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (Auth::guard('admin')->check() || $sideMenuPermissions->contains(fn ($permission) => $permission['side_menu_name'] === 'Notifications' && $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white" href="#" data-toggle="modal" data-target="#notificationModal">Create</a>
                                @endif

                                                                
                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>John Deo</td>
                                            <td>Description</td>
                                            <td>
                                                {{-- @if ($subAdmin->status == 0) --}}
                                                    <div class="badge badge-danger badge-shadow">Sending</div>
                                                {{-- @else --}}
                                                    {{-- <div class="badge badge-success badge-shadow">Sent</div> --}}
                                                {{-- @endif --}}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-4 justify-content-center">
                                                    @if (Auth::guard('admin')->check() || $sideMenuPermissions->contains(fn ($permission) => $permission['side_menu_name'] === 'Notifications' && $permission['permissions']->contains('delete')))
                                                        <form action=
                                                        "{{ route('notification.index') }}"
                                                            {{--  method="POST" --}}
                                                             style="display:inline-block; margin-left: 10px">
                                                            {{-- @csrf
                                                            @method('DELETE') --}}
                                                            <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
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
