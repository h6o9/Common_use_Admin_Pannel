@extends('admin.layout.app')
@section('title', 'Notifications')

@section('content')
    <style>
        .select2-container {
            display: block;
        }

        .read-more-link {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
        }

        .read-more-link:hover {
            text-decoration: underline;
        }

        .d-none {
            display: none;
        }

        .toggle-names {
            color: #007bff;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
            margin-left: 4px;
        }

        .toggle-names:hover {
            text-decoration: underline;
        }

        .d-none {
            display: none;
        }

        .name-preview {
            display: inline-block;
            margin-right: 5px;
        }

        .user-type-container {
            margin-bottom: 15px;
        }

        .user-type-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .user-type-btn {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .user-type-btn.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .select-all-types {
            margin-left: 10px;
            font-size: 0.9em;
            color: #007bff;
            cursor: pointer;
        }
    </style>

    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('notification.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Notification</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="user-type-container">
                            <label>Select User Types <span style="color: red;">*</span></label>
                            <div class="user-type-selector">
                                <div>
                                    <input type="checkbox" id="selectAllTypes" class="d-none">
                                    <label for="usersType" class="user-type-btn" data-type="users">
                                        Users
                                    </label>
                                </div>
                                <div>
                                    <label for="subadminsType" class="user-type-btn" data-type="subadmins">
                                        Subadmins
                                    </label>
                                </div>
                                <span class="select-all-types">Select All</span>
                            </div>
                            <input type="hidden" name="user_types" id="selectedUserTypes">
                        </div>

                        <div class="form-group" id="farmers-group">
                            <label for="farmers">Select Recipients <span style="color: red;">*</span></label>
                            <div>
                                <input type="checkbox" id="selectAllRecipients">
                                <label for="selectAllRecipients">Select All</label>
                            </div>
                            <select class="form-control" id="recipients" name="recipients[]" multiple>
                                <!-- Options will be loaded dynamically based on user type selection -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_title">Title <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message <span style="color: red;">*</span></label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Send Notification</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="main-content">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4>Notifications</h4>
                </div>
                <div class="card-body table-responsive">
                    @php
                        $sideMenuPermissions = collect();
                        // Check if user is not admin (normal subadmin)
                        if (!Auth::guard('admin')->check()) {
                            $user = Auth::guard('subadmin')->user()->load('roles');
                            // Get role_id of subadmin
                            $roleId = $user->role_id;
                            // Get all permissions assigned to this role
                            $permissions = UserRolePermission::with(['permission', 'sideMenue'])
                                ->where('role_id', $roleId)
                                ->get();
                            // Group permissions by side menu
                            $sideMenuPermissions = $permissions->groupBy('sideMenue.name')->map(function ($items) {
                                return $items->pluck('permission.name'); // ['view', 'create']
                            });
                        }
                    @endphp

                    @if (Auth::guard('admin')->check() ||
                            $sideMenuPermissions->contains(fn($p) => $p['side_menu_name'] === 'Notifications' && $p['permissions']->contains('create')))
                        <a class="btn btn-primary mb-3 text-white" data-toggle="modal"
                            data-target="#notificationModal">Create</a>
                    @endif

                    <table class="table table-bordered text-center" id="table_id_events">
                        <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>Name</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>User Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $index => $notification)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @php
                                            $names = [];
                                            $userTypes = [];

                                            foreach ($notification->targets as $target) {
                                                if ($target->targetable_type === \App\Models\User::class) {
                                                    $names[] =
                                                        $users->where('id', $target->targetable_id)->first()->name ??
                                                        'Unknown User';
                                                    $userTypes[] = 'User';
                                                } elseif ($target->targetable_type === \App\Models\SubAdmin::class) {
                                                    $names[] =
                                                        $subadmin->where('id', $target->targetable_id)->first()->name ??
                                                        'Unknown Subadmin';
                                                    $userTypes[] = 'Subadmin';
                                                }
                                            }

                                            $totalNames = count($names);
                                            $displayNames = array_slice($names, 0, 2);
                                            $uniqueUserTypes = array_unique($userTypes);
                                        @endphp

                                        @foreach ($displayNames as $name)
                                            <span class="name-preview">{{ $name }}</span><br>
                                        @endforeach

                                        @if ($totalNames > 2)
                                            <span class="additional-names d-none">
                                                @foreach (array_slice($names, 2) as $name)
                                                    {{ $name }}<br>
                                                @endforeach
                                            </span>
                                            <a href="#" class="toggle-names" data-count="{{ $totalNames - 2 }}">
                                                {{ $totalNames - 2 }}+
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $notification->title }}</td>
                                    <td>
                                        @php
                                            $words = explode(' ', $notification->message);
                                            $shortMessage = implode(' ', array_slice($words, 0, 3));
                                            $showReadMore = count($words) > 3;
                                        @endphp

                                        <span class="message-preview">{{ $shortMessage }}</span>
                                        @if ($showReadMore)
                                            <span class="full-message d-none">{{ $notification->message }}</span>
                                            <a href="#" class="read-more-link">... Read More</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ implode(', ', $uniqueUserTypes) }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary editBtn" data-id="{{ $notification->id }}"
                                            data-message="{{ $notification->message }}"
                                            data-user-types="{{ json_encode($uniqueUserTypes) }}"
                                            data-recipients="{{ json_encode($notification->targets->pluck('targetable_id')->toArray()) }}">
                                            Edit
                                        </button>

                                        <form action="{{ route('notification.destroy', $notification->id) }}"
                                            method="POST" class="deleteForm d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-danger show_confirm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editNotificationModal" tabindex="-1" role="dialog"
        aria-labelledby="editNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editNotificationForm" method="POST">
                @csrf
                @method('POST')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Notification</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="notification_id" id="edit_notification_id">

                        <div class="user-type-container">
                            <label>Select User Types <span style="color: red;">*</span></label>
                            <div class="user-type-selector">
                                <div>
                                    <input type="checkbox" id="edit_selectAllTypes" class="d-none">
                                    <label for="edit_usersType" class="user-type-btn" data-type="users">
                                        Users
                                    </label>
                                </div>
                                <div>
                                    <label for="edit_subadminsType" class="user-type-btn" data-type="subadmins">
                                        Subadmins
                                    </label>
                                </div>
                                <span class="select-all-types">Select All</span>
                            </div>
                            <input type="hidden" name="user_types" id="edit_selectedUserTypes">
                        </div>

                        <div class="form-group">
                            <label for="edit_recipients">Select Recipients <span style="color: red;">*</span></label>
                            <div>
                                <input type="checkbox" id="edit_selectAllRecipients">
                                <label for="edit_selectAllRecipients">Select All</label>
                            </div>
                            <select class="form-control" id="edit_recipients" name="recipients[]" multiple>
                                <!-- Options will be loaded dynamically -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_title">Title <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_message">Message <span style="color: red;">*</span></label>
                            <textarea name="message" class="form-control" id="edit_message" rows="5" required></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Update Notification</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize select2 dropdown
            $('#recipients, #edit_recipients').select2({
                placeholder: 'Select Recipients',
                allowClear: true
            });

            $('#table_id_events').DataTable();

            // User type selection logic for create modal
            const userTypeBtns = $('.user-type-btn');
            const selectAllTypes = $('.select-all-types');
            const selectedUserTypes = $('#selectedUserTypes');

            // Toggle user type selection
            userTypeBtns.on('click', function() {
                $(this).toggleClass('active');
                updateSelectedUserTypes();
                loadRecipients();
            });

            // Select all user types
            selectAllTypes.on('click', function() {
                const allActive = userTypeBtns.length === $('.user-type-btn.active').length;
                userTypeBtns.toggleClass('active', !allActive);
                updateSelectedUserTypes();
                loadRecipients();
            });

            function updateSelectedUserTypes() {
                const selectedTypes = [];
                $('.user-type-btn.active').each(function() {
                    selectedTypes.push($(this).data('type'));
                });
                selectedUserTypes.val(selectedTypes.join(','));
            }

            // Load recipients based on selected user types
            function loadRecipients() {
                const selectedTypes = selectedUserTypes.val().split(',');
                if (selectedTypes.length === 0) {
                    $('#recipients').empty().trigger('change');
                    return;
                }

                $.ajax({
                    url: '{{ route('notification.getRecipients') }}',
                    method: 'POST',
                    data: {
                        user_types: selectedTypes,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#recipients').empty();
                        $.each(response.recipients, function(id, name) {
                            $('#recipients').append(new Option(name, id));
                        });
                        $('#recipients').trigger('change');
                    }
                });
            }

            // Select all recipients
            $('#selectAllRecipients').change(function() {
                $('#recipients option').prop('selected', this.checked).trigger('change');
            });

            // Edit modal user type selection logic
            const editUserTypeBtns = $('#editNotificationModal .user-type-btn');
            const editSelectAllTypes = $('#editNotificationModal .select-all-types');
            const editSelectedUserTypes = $('#edit_selectedUserTypes');

            editUserTypeBtns.on('click', function() {
                $(this).toggleClass('active');
                updateEditSelectedUserTypes();
            });

            editSelectAllTypes.on('click', function() {
                const allActive = editUserTypeBtns.length === $(
                    '#editNotificationModal .user-type-btn.active').length;
                editUserTypeBtns.toggleClass('active', !allActive);
                updateEditSelectedUserTypes();
            });

            function updateEditSelectedUserTypes() {
                const selectedTypes = [];
                $('#editNotificationModal .user-type-btn.active').each(function() {
                    selectedTypes.push($(this).data('type'));
                });
                editSelectedUserTypes.val(selectedTypes.join(','));
            }

            // Select all recipients in edit modal
            $('#edit_selectAllRecipients').change(function() {
                $('#edit_recipients option').prop('selected', this.checked).trigger('change');
            });

            // Edit button logic
            $('.editBtn').on('click', function() {
                const id = $(this).data('id');
                const message = $(this).data('message');
                const userTypes = $(this).data('user-types');
                const recipients = $(this).data('recipients');
                const title = $(this).closest('tr').find('td:eq(2)').text();

                $('#edit_notification_id').val(id);
                $('#edit_message').val(message);
                $('#edit_title').val(title);

                // Set user types in edit modal
                $('#editNotificationModal .user-type-btn').removeClass('active');
                if (userTypes.includes('User')) {
                    $('#editNotificationModal .user-type-btn[data-type="users"]').addClass('active');
                }
                if (userTypes.includes('Subadmin')) {
                    $('#editNotificationModal .user-type-btn[data-type="subadmins"]').addClass('active');
                }
                updateEditSelectedUserTypes();

                // Load recipients for edit modal
                $.ajax({
                    url: '{{ route('notification.getRecipients') }}',
                    method: 'POST',
                    data: {
                        user_types: userTypes.map(type => type.toLowerCase()),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#edit_recipients').empty();
                        $.each(response.recipients, function(id, name) {
                            $('#edit_recipients').append(new Option(name, id));
                        });

                        // Select previously selected recipients
                        if (recipients && recipients.length > 0) {
                            $('#edit_recipients').val(recipients).trigger('change');
                        }

                        $('#edit_selectAllRecipients').prop('checked',
                            $('#edit_recipients option').length === $(
                                '#edit_recipients option:selected').length
                        );
                    }
                });

                $('#editNotificationForm').attr('action', '{{ route('notification.update', '') }}/' + id);
                $('#editNotificationModal').modal('show');
            });

            // Delete confirmation
            $('.show_confirm').click(function(event) {
                var form = $(this).closest("form");
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

            // Read more toggle
            $(document).on('click', '.read-more-link', function(e) {
                e.preventDefault();
                const $parent = $(this).parent();
                $parent.find('.message-preview').addClass('d-none');
                $parent.find('.full-message').removeClass('d-none');
                $(this).remove();
            });

            // Show more/less names
            $(document).on('click', '.toggle-names', function(e) {
                e.preventDefault();
                const $this = $(this);
                const $additionalNames = $this.prev('.additional-names');
                const count = $this.data('count');

                if ($additionalNames.hasClass('d-none')) {
                    $additionalNames.removeClass('d-none');
                    $this.text('Show less');
                } else {
                    $additionalNames.addClass('d-none');
                    $this.text(count + '+');
                }
            });

            // Toastr notifications
            @if (session('success'))
                toastr.success(@json(session('success')));
            @endif

            @if (session('error'))
                toastr.error(@json(session('error')));
            @endif

            @if (session('info'))
                toastr.info(@json(session('info')));
            @endif
        });
    </script>
@endsection
