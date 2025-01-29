@extends('admin.layout.app')
@section('title', $farmer->name . ' - Ensured Crops')
@section('content')


    {{-- Add Ensured Crops Modal --}}
    <div class="modal fade" id="EnsuredCropModal" tabindex="-1" role="dialog" aria-labelledby="EnsuredCropModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EnsuredCropModalLabel">Add Ensured Crop</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('ensured.crops.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="farmer_id" value="{{ $farmer->id }}">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="crop_name">Crop</label>
                                    <select name="crop_name_id" id="crop_name" class="form-control">
                                        <option value="" selected disabled>Select Crop</option>
                                        @foreach ($cropNames as $cropName)
                                            <option value="{{ $cropName->id }}">{{ strtoupper($cropName->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('crop_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="land_area">Land Area</label>
                                    <input type="number" name="land_area" class="form-control"
                                        value="{{ old('land_area') }}">
                                    @error('land_area')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="area_unit_id">Unit</label>
                                    <select name="area_unit_id" id="area_unit_id" class="form-control">
                                        <option value="" selected disabled>Select Unit</option>
                                        @foreach ($Units as $unit)
                                            <option value="{{ $unit->id }}">{{ strtoupper($unit->unit) }}</option>
                                        @endforeach
                                    </select>
                                    @error('area_unit_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="company_id">Insurance Company</label>
                                    <select name="company_id" id="company_id" class="form-control">
                                        <option value="" selected disabled>Select Company</option>
                                        @foreach ($Insurance_companies as $Insurance_company)
                                            <option value="{{ $Insurance_company->id }}">{{ $Insurance_company->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="insurance_type_id">Insurance Type</label>
                                    <select name="insurance_type_id" id="insurance_type_id" class="form-control">
                                        <option value="" selected disabled>Select Insurance Type</option>
                                    </select>
                                    @error('insurance_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="insurance_subtype_id">Insurance Sub-Type</label>
                                    <select name="insurance_subtype_id" id="insurance_subtype_id" class="form-control">
                                        <option value="" selected disabled>Select Insurance Sub-Type</option>
                                    </select>
                                    @error('insurance_subtype_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="insurance_start_date">Insurance Start Date</label>
                                    <input type="date" name="insurance_start_date" class="form-control"
                                        value="{{ old('insurance_start_date') }}">
                                    @error('insurance_start_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="insurance_end_date">Insurance End Date</label>
                                    <input type="date" name="insurance_end_date" class="form-control"
                                        value="{{ old('insurance_end_date') }}">
                                    @error('insurance_end_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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



    {{-- Edit Ensured Crops Modal --}}
    @foreach ($EnsuredCrops as $EnsuredCrop)
        <div class="modal fade" id="EditEnsuredCropModal-{{ $EnsuredCrop->id }}" tabindex="-1" role="dialog"
            aria-labelledby="EditEnsuredCropModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditEnsuredCropModalLabel">Edit Ensured Crop</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('district.update', $EnsuredCrop->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="farmer_id" value="{{ $farmer->id }}">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="crop_name">Crop Name</label>
                                        <input type="text" name="crop_name_id" class="form-control"
                                            value="{{ old('crop_name', $EnsuredCrop->crop_name) }}">
                                        @error('crop_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="land_area">Land Area</label>
                                        <input type="text" name="land_area" class="form-control"
                                            value="{{ old('land_area', $EnsuredCrop->land_area) }}">
                                        @error('land_area')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="insurance_start_date">Insurance Start Date</label>
                                        <input type="date" name="insurance_start_date" class="form-control"
                                            value="{{ old('insurance_start_date', $EnsuredCrop->insurance_start_date) }}">
                                        @error('insurance_start_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="insurance_end_date">Insurance End Date</label>
                                        <input type="date" name="insurance_end_date" class="form-control"
                                            value="{{ old('insurance_end_date', $EnsuredCrop->insurance_end_date) }}">
                                        @error('insurance_end_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

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
                        <a class="btn btn-primary mb-2" href="{{ route('farmers.index') }}">Back</a>
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>{{ $farmer->name }} - Ensured Crops</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (Auth::guard('admin')->check() ||
                                        $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Ensured Crops' &&
                                                $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white" href="#" data-toggle="modal"
                                        data-target="#EnsuredCropModal">Create</a>
                                @endif

                                <table class="table responsive" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Crop Name</th>
                                            <th>Type</th>
                                            <th>Season</th>
                                            <th>Land Area</th>
                                            <th>Insured Amount</th>
                                            <th>Insured Start Date</th>
                                            <th>Insured End Date</th>
                                            <th>Policy Number</th>
                                            <th>Remarks</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($EnsuredCrops as $EnsuredCrop)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $EnsuredCrop->cropName->name }}</td>
                                                <td>{{ $EnsuredCrop->season }}</td>
                                                <td>{{ $EnsuredCrop->land_area }}</td>
                                                <td>{{ $EnsuredCrop->insured_amount }}</td>
                                                <td>{{ $EnsuredCrop->insurance_start_date }}</td>
                                                <td>{{ $EnsuredCrop->insurance_end_date }}</td>
                                                <td>{{ $EnsuredCrop->policy_number }}</td>
                                                <td>{{ $EnsuredCrop->remarks }}</td>
                                                <td>
                                                    <div class="d-flex gap-4">
                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Ensured Crops' &&
                                                                        $permission['permissions']->contains('edit')))
                                                            <a href="#" class="btn btn-primary"
                                                                style="margin-left: 10px" data-toggle="modal"
                                                                data-target="#EditEnsuredCropModal-{{ $EnsuredCrop->id }}">Edit</a>
                                                        @endif

                                                        @if (Auth::guard('admin')->check() ||
                                                                $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Ensured Crops' &&
                                                                        $permission['permissions']->contains('delete')))
                                                            <form
                                                                action="
                                                            {{ route('ensured.crops.destroy', $EnsuredCrop->id) }}
                                                            "
                                                                method="POST"
                                                                style="display:inline-block; margin-left: 10px">
                                                                @csrf
                                                                @method('DELETE')
                                                                <input type="hidden" name="farmer_id"
                                                                    value="{{ $farmer->id }}">
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
    {{-- <script>
        $(document).ready(function() {
            $('#craft').on('change', function() {
                var craftId = $(this).val();

                $.ajax({
                    url: "{{ route('get-sub-crafts') }}",
                    type: "GET",
                    data: {
                        craft_id: craftId
                    },
                    success: function(data) {
                        $('#sub_craft').empty();
                        $('#sub_craft').append(
                            '<option value="" selected disabled>Select Sub-Craft</option>');

                        $.each(data, function(key, value) {
                            $('#sub_craft').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });
    </script> --}}

@endsection
