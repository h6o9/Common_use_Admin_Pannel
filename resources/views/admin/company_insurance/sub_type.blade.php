@extends('admin.layout.app')
@section('title', 'Company Insurance Sub-Types')
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
                    <h5 class="modal-title" id="InsuranceTypesModalLabel">Add Insurance Sub-Types</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="
                {{ route('company.insurance.sub.types.store') }}
                " method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="company_insurance_type_id" value="{{ $CompanyinsuranceType->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="insurance_subtype_id">Sub-Types</label>
                                    <select name="insurance_subtype_id[]" id="insurance_subtype_id" class="form-control" multiple required>
                                        @foreach ($savedInsuranceSubtypes as $Insurance_subtype)
                                            <option value="{{ $Insurance_subtype->id }}">{{ $Insurance_subtype->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('insurance_subtype_id')
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

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <div id="priceContainer"></div>
                                    @error('price.{$Insurance_type->id}')
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
    @foreach ($CompanyInsurancesSubTypes as $InsuranceType)
        <div class="modal fade" id="EditInsuranceTypesModal-{{ $InsuranceType->id }}" tabindex="-1" role="dialog"
            aria-labelledby="EditInsuranceTypesModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="EditInsuranceTypesModalLabel">Edit Insurance Sub-Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="
                    {{ route('company.insurance.sub.types.update', $InsuranceType->id) }}
                        " method="POST">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="company_insurance_type_id" value="{{ $CompanyinsuranceType->id }}">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" name="price" id="price" value="{{ $InsuranceType->price }}" class="form-control" required>
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
                        <div class="card">
                            <div class="card-header">
                                <div class="col-12">
                                    <h4>{{ $CompanyinsuranceType->insuranceCompany->name }} - {{ $CompanyinsuranceType->insuranceType->name }} - (Insurance Sub-Types)</h4>
                                </div>
                            </div>
                            <div class="card-body table-striped table-bordered table-responsive">
                                @if (Auth::guard('admin')->check() ||
                                        $sideMenuPermissions->contains(fn($permission) => $permission['side_menu_name'] === 'Insurance Types & Sub-Types' &&
                                                $permission['permissions']->contains('create')))
                                    <a class="btn btn-primary mb-3 text-white" href="#" data-toggle="modal"
                                    data-target="#InsuranceTypesModal">Add Insurance Sub-Types</a>
                                @endif

                                <table class="table responsive" id="table_id_events">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($CompanyInsurancesSubTypes as $InsuranceType)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $InsuranceSubTypeNames[$InsuranceType->insurance_subtype_id]->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($InsuranceType->price) ?? 'N/A' }} PKR</td>
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
                                                        {{ route('company.insurance.sub.types.destroy', $InsuranceType->id) }}
                                                            " method="POST" 
                                                            style="display:inline-block; margin-left: 10px">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="company_insurance_type_id" value="{{ $CompanyinsuranceType->id }}">
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
        $('#insurance_subtype_id').select2({
            placeholder: "Select Sub-Type",
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

        $('#insurance_subtype_id').on('change', function () {
            let selectedTypes = $(this).val();
            let priceContainer = $('#priceContainer');

            priceContainer.empty();

            if (selectedTypes) {
                selectedTypes.forEach(function (subtypeId) {

                    let subTypeName = $('#insurance_subtype_id option[value="' + subtypeId + '"]').text();

                    let inputHtml = `
                        <div class="input-group mb-2" id="price-group-${subtypeId}">
                            <input type="number" name="price[${subtypeId}]" class="form-control" placeholder="Enter price for ${subTypeName}" required>
                        </div>`;
                    priceContainer.append(inputHtml);
                });
            }
        });
    });
    </script>


@endsection
