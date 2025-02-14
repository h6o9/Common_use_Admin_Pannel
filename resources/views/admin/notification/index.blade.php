@extends('admin.layout.app')
@section('title', 'Notifications')
@section('content')

    <style>
        .select2-container {
            display: block;
        }
    </style>

    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
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
                            <select class="form-control" name="user_type" id="userType" required multiple>
                                <option value="farmers">Farmers</option>
                                <option value="dealers">Authorized Dealers</option>
                            </select>
                        </div>

                        <div class="form-group d-none" id="farmers-group">
                            <label for="farmers">Select Farmers</label>
                            <div>
                                <input type="checkbox" id="selectAllFarmers"> <label for="selectAllFarmers">Select All</label>
                            </div>
                            <select class="form-control" id="farmers" name="farmers[]" required multiple>
                                @foreach ($farmers as $farmer)
                                    <option value="{{ $farmer->id }}">{{ $farmer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group d-none" id="dealers-group">
                            <label for="dealers">Select Authorized Dealer</label>
                            <div>
                                <input type="checkbox" id="selectAllDealers"> <label for="selectAllDealers">Select All</label>
                            </div>
                            <select class="form-control" id="dealers" name="dealers[]" required multiple>
                                @foreach ($dealers as $dealer)
                                    <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Message Textbox -->
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Enter your message here"
                                required></textarea>
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
                                @if (Auth::guard('admin')->check() ||
                                        $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Notifications' &&
                                                $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white" href="#" data-toggle="modal"
                                        data-target="#notificationModal">Create</a>
                                @endif


                                <table class="table text-center" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            {{-- <th scope="col">Actions</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            {{-- <td>
                                                <div class="d-flex gap-4 justify-content-center">
                                                    @if (Auth::guard('admin')->check() || $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Notifications' && $permission['permissions']->contains('delete')))
                                                        <form action=
                                                        "{{ route('notification.index') }}"
                                                             method="POST"
                                                             style="display:inline-block; margin-left: 10px">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-flat show_confirm" data-toggle="tooltip">Delete</button>
                                                        </form>
                                                    @endif

                                                </div>
                                            </td> --}}
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

    <script>
        $(document).ready(function() {
            $('#userType, #dealers, #farmers').select2({
                placeholder: "Select User",
                allowClear: true
            });

            $('#userType').on('change', function() {
                var selectedValues = $(this).val();

                // Hide both groups initially
                $('#farmers-group, #dealers-group').addClass('d-none');

                // Show respective groups based on selection
                if (selectedValues) {
                    if (selectedValues.includes('farmers')) {
                        $('#farmers-group').removeClass('d-none');
                    }
                    if (selectedValues.includes('dealers')) {
                        $('#dealers-group').removeClass('d-none');
                    }
                }
            });

            $('#selectAllFarmers').on('change', function() {
            if ($(this).is(':checked')) {
                $('#farmers option').prop('selected', true);
            } else {
                $('#farmers option').prop('selected', false);
            }
            $('#farmers').trigger('change'); // Update Select2 if used
        });

        $('#selectAllDealers').on('change', function() {
            if ($(this).is(':checked')) {
                $('#dealers option').prop('selected', true);
            } else {
                $('#dealers option').prop('selected', false);
            }
            $('#dealers').trigger('change'); // Update Select2 if used
        });

        });
    </script>

@endsection
