@extends('layouts.dashboard.app')

@section('title', 'employee salary create')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
        </ol>

    </nav>
@endsection

@section('content')

    <!-- Alert -->
    @include('layouts.dashboard.partials.alert')
    <!-- End:Alert -->

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{route('admin.salary.list.show')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-2">
                            <div class="col-sm-6 mb-2">
                                <div class="form-group">
                                    <label for="employee_id"><b>Employee</b><span class="text-danger">*</span></label>
                                    <select class="form-control employee_id " id="employee_id"name="employee_id" >
                                        <option value="" selected>--Select Employee--</option>
                                        @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                    <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-sm-3 mb-2">
                                <label for="month_from"><b>Month From</b> <span class="text-danger">*</span> </label>
                                <input type="month" id="month_from" name="month_from" class="form-control"  onkeyup="check_data()">
                                @error('month_from')
                                <span class="alert text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-3 mb-2">
                                <label for="month_to"><b>Month To</b> <span class="text-danger">*</span> </label>
                                <input type="month" id="month_to" name="month_to" class="form-control"  onkeyup="check_data()">
                                @error('month_to')
                                <span class="alert text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-2">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-sm btn-primary mr-2">Create Salary</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


