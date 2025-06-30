@extends('admin.layout.app')
@section('title', 'Users')

@section('content')
    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Users</h4>
                            </div>
                            <div class="card-body table-responsive">

                                @if (Auth::guard('admin')->check() ||
                                        ($sideMenuPermissions->has('Users') && $sideMenuPermissions['Users']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white"
                                        href="{{ url('/admin/user-create') }}">Create</a>
                                @endif

                                {{-- @if (Auth::guard('admin')->check() || ($sideMenuPermissions->has('users') && $sideMenuPermissions['users']->contains('view')))
                                    <a class="btn btn-primary mb-3 text-white" href="{{ url('admin/users/trashed') }}">View
                                        Trashed</a>
                                @endif --}}


                                <table class="table table-striped" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Toggle</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>
                                                    <label class="custom-switch">
                                                        <input type="checkbox" class="custom-switch-input toggle-status"
                                                            data-id="{{ $user->id }}"
                                                            {{ $user->toggle ? 'checked' : '' }}>
                                                        <span class="custom-switch-indicator"></span>
                                                        <span class="custom-switch-description">
                                                            {{ $user->toggle ? 'Activated' : 'Deactivated' }}
                                                        </span>
                                                    </label>
                                                </td>
                                                <td>
                                                    @if (Auth::guard('admin')->check() ||
                                                            ($sideMenuPermissions->has('Users') && $sideMenuPermissions['Users']->contains('edit')))
                                                        <a href="{{ route('user.edit', $user->id) }}"
                                                            class="btn btn-primary me-2"
                                                            style="float: left; margin-right: 8px;">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (Auth::guard('admin')->check() ||
                                                            ($sideMenuPermissions->has('Users') && $sideMenuPermissions['Users']->contains('delete')))
                                                        <form id="delete-form-{{ $user->id }}"
                                                            action="{{ route('user.delete', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>

                                                        <button class="show_confirm btn d-flex gap-4"
                                                            style="background-color: #ff5608;"
                                                            data-form="delete-form-{{ $user->id }}" type="button">
                                                            <span><i class="fa fa-trash"></i></span>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /.card-body -->
                        </div> <!-- /.card -->
                    </div> <!-- /.col -->
                </div> <!-- /.row -->
            </div> <!-- /.section-body -->
        </section>
    </div>


    <!-- Deactivation Reason Modal -->
    <div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog" aria-labelledby="deactivationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deactivationModalLabel">Deactivation Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deactivationForm">
                        @csrf
                        <input type="hidden" name="user_id" id="deactivatingUserId">
                        <div class="form-group">
                            <label for="deactivationReason">Please specify the reason for deactivation:</label>
                            <textarea class="form-control" id="deactivationReason" name="reason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmDeactivation">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#table_id_events')) {
                $('#table_id_events').DataTable().destroy();
            }
            $('#table_id_events').DataTable();
        });
    </script>

    <!-- Include SweetAlert -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script type="text/javascript">
        $('.show_confirm').click(function(event) {
            var formId = $(this).data("form");
            var form = document.getElementById(formId);
            event.preventDefault();

            swal({
                    title: "Are you sure you want to delete this record?",
                    text: "If you delete this User record, it will be gone forever.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        // Send AJAX request to delete
                        $.ajax({
                            url: form.action,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                swal({
                                    title: "Success!",
                                    text: "Record deleted successfully",
                                    icon: "success",
                                    button: false,
                                    timer: 3000
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                swal("Error!", "Failed to delete record.", "error");
                            }
                        });
                    }
                });
        });

        /// Toggle status
        let currentToggle = null;
        let currentUserId = null;

        $('.toggle-status').change(function() {
            let status = $(this).is(':checked') ? 1 : 0;
            currentToggle = $(this);
            currentUserId = $(this).data('id');

            if (status === 0) {
                $('#deactivatingUserId').val(currentUserId);
                $('#deactivationModal').modal('show');
            } else {
                updateUserStatus(currentUserId, 1);
            }
        });

        $('#confirmDeactivation').click(function() {
            let reason = $('#deactivationReason').val();
            if (reason.trim() === '') {
                toastr.error('Please provide a deactivation reason');
                setTimeout(() => {
                    location.reload();
                }, 800);
                return;
            }

            $('#deactivationModal').modal('hide');
            $('#deactivationReason').val('');
            updateUserStatus(currentUserId, 0, reason);
        });

        $('#deactivationModal').on('hidden.bs.modal', function() {
            if ($('#deactivationReason').val().trim() === '') {
                setTimeout(() => {
                    location.reload();
                }, 500);
            }
        });

        function updateUserStatus(userId, status, reason = null) {
            let $descriptionSpan = currentToggle.siblings('.custom-switch-description');
            $.ajax({
                url: "{{ route('user.toggle-status') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: userId,
                    status: status,
                    reason: reason
                },
                success: function(res) {
                    if (res.success) {
                        $descriptionSpan.text(res.new_status);
                        toastr.success(res.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        currentToggle.prop('checked', !status);
                        toastr.error(res.message);
                    }
                },
                error: function() {
                    currentToggle.prop('checked', !status);
                    toastr.error('Error updating status');
                }
            });
        }
    </script>
@endsection
