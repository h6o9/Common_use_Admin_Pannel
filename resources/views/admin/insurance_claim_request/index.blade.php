@extends('admin.layout.app')
@section('title', 'Insurance Claim Requests')
@section('content')


    <div class="main-content" style="min-height: 562px;">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>Insurance Claim Requests</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                
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
                                            <td>Premium</td>
                                            <td>Desctiption is here</td>
                                            <td>
                                                {{-- @if ($subAdmin->status == 1) --}}
                                                    <div class="badge badge-success badge-shadow">Activated</div>
                                                {{-- @else
                                                    <div class="badge badge-danger badge-shadow">Deactivated</div>
                                                @endif --}}
                                            </td>
                                            <td>
                                                <div class="d-flex gap-4 justify-content-center">
                                                    @if (Auth::guard('admin')->check() || $sideMenuPermissions->contains(fn ($permission) => $permission['side_menu_name'] === 'Insurance Claim Requests' && $permission['permissions']->contains('edit')))
                                                        <a class="btn btn-primary text-white" href="
                                                        {{-- {{ route('insurance.claim.edit', $insuranceClaim->id) }} --}}
                                                            ">Edit</a>
                                                    @endif

                                                    <!-- Delete Button -->
                                                    @if (Auth::guard('admin')->check() || $sideMenuPermissions->contains(fn ($permission) => $permission['side_menu_name'] === 'Insurance Claim Requests' && $permission['permissions']->contains('delete')))
                                                        <form 
                                                        action=
                                                        "{{ route('insurance.claim.index') }}" 
                                                        {{-- method="POST" --}}
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
