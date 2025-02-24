@extends('layouts.app')

@section('title', 'Branches List')
@section('header-button')
    @if (Auth::user()->can('create branches'))
        <a href="{{ route('branches.create') }}" class="btn btn-primary">Add New Branch</a>
    @endif
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <x-error-message :message="$errors->first('message')" />
                    <x-success-message :message="session('success')" />

                    <div class="card">
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered dt-responsive table-striped nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Short Name</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        @canany(['edit branches', 'delete branches'])
                                            <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $key => $branch)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ ucwords($branch->name) }}</td>
                                            <td>{{ ucwords($branch->short_name) }}</td>
                                            <td>{{ $branch->email }}</td>
                                            <td>{{ $branch->contact }}</td>
                                            <td>{{ $branch->location }}</td>
                                            <td>
                                                <input type="checkbox" id="{{ $branch->id }}" class="update-status"
                                                    data-id="{{ $branch->id }}" switch="success" data-on="Active"
                                                    data-off="Inactive" {{ $branch->status === 'Active' ? 'checked' : '' }}
                                                    data-endpoint="{{ route('branch-status') }}" />
                                                <label class="mb-0" for="{{ $branch->id }}" data-on-label="Active"
                                                    data-off-label="Inactive"></label>
                                            </td>
                                            @canany(['edit branches', 'delete branches'])
                                                <td>
                                                    @if (Auth::user()->can('edit branches'))
                                                        <a href="{{ route('branches.edit', $branch->id) }}"
                                                            class="btn btn-primary btn-sm edit py-0 px-1"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                    @endif
                                                    @if (Auth::user()->can('delete branches'))
                                                        @if ($branch->id > 1 && !$branchesWithTransfers[$branch->id])
                                                            <button data-source="branch"
                                                                data-endpoint="{{ route('branches.destroy', $branch->id) }}"
                                                                class="delete-btn btn btn-danger btn-sm edit py-0 px-1">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </td>
                                            @endcanany
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <x-include-plugins :plugins="['dataTable', 'update-status']"></x-include-plugins>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                columnDefs: [{
                    type: 'string',
                    targets: 1
                }],
                order: [
                    [1, 'asc']
                ],
                drawCallback: function(settings) {
                    $('#datatable th, #datatable td').addClass('p-0');
                }
            });
        });
    </script>
@endsection
