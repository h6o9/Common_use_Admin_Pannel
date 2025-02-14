@extends('admin.layout.app')
@section('title', 'Company Insurance Types')
@section('content')

<style>
    .select2-container{
        display: block;
    }
</style>

    {{-- Add Insurance Types Modal --}}
    <div class="modal fade" id="InsuranceTypesModal" tabindex="-1" role="dialog"
        aria-labelledby="InsuranceTypesModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="InsuranceTypesModalLabel">Add Insurance Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('company.insurance.types.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="insurance_company_id" value="{{ $Company->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="insurance_type_id">Types</label>
                                    <select name="insurance_type_id[]" id="insurance_type_id" class="form-control" multiple required>
                                        @foreach ($Insurance_types as $Insurance_type)
                                            <option value="{{ $Insurance_type->id }}">{{ $Insurance_type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('insurance_type_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="" selected disabled>Select an Option</option>
                                        <option value="1" {{ old('status') }}>Active</option>
                                        <option value="0" {{ old('status') }}>Deactive</option>
                                    </select>
                                    @error('status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group">
                                    <div class="text-danger">If you want to select a sub-type, then please ignore the price field.</div>
                                    <label for="price" id="priceLabel" style="display: none;">Price (Optional)</label>
                                    <div id="priceContainer"></div>
                                    @error('price')
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



    {{-- Edit Insurance Types Modal --}}
    @foreach ($CompanyInsurances as $InsuranceType)
        <div class="modal fade" id="EditInsuranceTypesModal-{{ $InsuranceType->id }}" tabindex="-1" role="dialog"
            aria-labelledby="EditInsuranceTypesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditInsuranceTypesModalLabel">Edit Insurance Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('company.insurance.types.update', $InsuranceType->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="incurance_company_id" value="{{ $Company->id }}">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price (Optional)</label>
                                        <input type="number" name="price" id="" value="{{ $InsuranceType->price }}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="" {{ old('status', $InsuranceType->status) === null ? 'selected' : '' }} disabled>Select an Option</option>
                                            <option value="1" {{ old('status', $InsuranceType->status) == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status', $InsuranceType->status) == '0' ? 'selected' : '' }}>Deactive</option>
                                        </select>
                                        @error('status')
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
                        <a class="btn btn-primary mb-2" href="{{ route('insurance.company.index') }}">Back</a>
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>{{ $Company->name }} - (Insurance types)</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (Auth::guard('admin')->check() ||
                                        $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Insurance Types & Sub-Types' &&
                                                $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white" href="#" data-toggle="modal"
                                    data-target="#InsuranceTypesModal">Add Insurance Types</a>
                                @endif

                                <table class="table responsive" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Sub-Types</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($CompanyInsurances as $InsuranceType)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $InsuranceType->insuranceType->name }}
                                            </td>
                                            <td> 
                                                @if ($InsuranceType->price)
                                                    {{ number_format($InsuranceType->price) }} PKR
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            
                                            <td>
                                                <a href="{{ route('company.insurance.sub.types.index', $InsuranceType->id) }}" class="btn btn-primary">View</a>
                                            </td>
                                            <td>
                                                @if ($InsuranceType->status == 1)
                                                <div class="badge badge-success badge-shadow">Activated</div>
                                                @else
                                                    <div class="badge badge-danger badge-shadow">Deactivated</div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-4">
                                                    @if (Auth::guard('admin')->check() ||
                                                            $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Insurance Types & Sub-Types' &&
                                                                    $permission['permissions']->contains('edit')))
                                                        <a class="btn btn-primary text-white"
                                                            href="#" data-toggle="modal" data-target="#EditInsuranceTypesModal-{{ $InsuranceType->id }}">Edit</a>
                                                    @endif

                                                    <!-- Delete Button -->
                                                    @if (Auth::guard('admin')->check() ||
                                                            $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Insurance Types & Sub-Types' &&
                                                                    $permission['permissions']->contains('delete')))
                                                        <form action="
                                                        {{ route('company.insurance.types.destroy', $InsuranceType->id) }}
                                                            " method="POST" 
                                                            style="display:inline-block; margin-left: 10px">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="incurance_company_id" value="{{ $Company->id }}">
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
    
    <script>
        $(document).ready(function() {
        $('#insurance_type_id').select2({
            placeholder: "Select Type",
            allowClear: true
        });
    });
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
        $(document).ready(function () {

        $('#insurance_type_id').on('change', function () {
            let selectedTypes = $(this).val();
            let priceContainer = $('#priceContainer');
            let priceLabel = $('#priceLabel');

            priceContainer.empty();

            if (selectedTypes && selectedTypes.length > 0) {
            priceLabel.show();
                
            selectedTypes.forEach(function (typeId) {
                let typeName = $('#insurance_type_id option[value="' + typeId + '"]').text();

                let inputHtml = `
                    <div class="input-group mb-2" id="price-group-${typeId}">
                        <input type="number" name="price[${typeId}]" class="form-control" placeholder="Enter price for ${typeName}">
                    </div>`;
                priceContainer.append(inputHtml);
            });
        } else {
            priceLabel.hide(); // Hide label if no types are selected
        }
        });
    });
    </script>


@endsection
