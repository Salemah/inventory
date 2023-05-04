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

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <p class="m-0">Employee Salary Report</p>
            <a href="" class="btn btn-sm btn-info">Back</a>
        </div>
        <div class="card-body">
            <table id="table" class="table table-bordered data-table" style="width: 100%">
                <thead>
                <tr style="text-align: center">
                    <th scope="col">Employee Name</th>
                    <th scope="col">Month</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Gross Salary</th>
                    <th scope="col">Date</th>
                </tr>
                </thead>

                <tbody style="text-align: center">
                {{--@foreach($reports as $key=> $report)--}}
                    {{--<tr>--}}
                        {{--<td>{{ $report -> date }}</td>--}}
                        {{--<td scope="row">{{ $report->student->name }}</td>--}}
                        {{--<td>{{$report->batch->name}}</td>--}}
                        {{--<td>{{$report->student->reg_no}}</td>--}}
                        {{--<td>--}}
                            {{--@if ( $report->status == 1)--}}
                                {{--Present--}}
                            {{--@else--}}
                                {{--Absent--}}
                            {{--@endif--}}
                            {{-- <input type="radio" name="attendance{{$key}}" id="present-{{$key}}" value="1" {{ $report->status == 1 ? 'checked' : ''  }}>--}}
                            {{--<label for="present-{{$key}}" >Present</label>--}}
                            {{--<input type="radio" name="attendance{{$key}}" id="absent-{{$key}}" value="0" {{ $report->status == 0 ? 'checked' : ''  }} >--}}
                            {{--<label for="absent-{{$key}}">Absent</label> --}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                </tbody>
            </table>
            <div>
                {{--@can('student_report')--}}
                    {{--<a href="javascript:window.print()" class="btn btn-sm btn-primary" style="float:right" >Print</a>--}}
                {{--@endcan--}}
            </div>
        </div>
    </div>
@endsection


