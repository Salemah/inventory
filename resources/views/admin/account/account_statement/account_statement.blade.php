@extends('layouts.dashboard.app')

@section('title', 'Account Statement')

@push('css')
    <
@endpush

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.account.cash.balance.sheet.index') }}">Account Statement</a>
            </li>

        </ol>
    </nav>
@endsection

@section('content')

    <!--Start Alert -->
    @include('layouts.dashboard.partials.alert')
    <!--End Alert -->
    <div class="row">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 col-12">
                        <label for="account_id"><b>Bank Account</b><span class="text-danger">*</span></label>
                        <div class="form-group{{ $errors->has('account_id') ? ' has-error' : '' }} has-feedback">
                            <select name="account_id" id="account_id" class="form-control">
                                <option value="" selected>-- Select Bank Account --</option>
                                @foreach ($bank_accounts as $bankAccount)
                                    <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}
                                        | {{ $bankAccount->account_number }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('account_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('account_id') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="date_form"><b>Form</b><span class="text-danger">*</span></label>
                        <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }} has-feedback">
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date') }}" placeholder="d/m/yy">
                            @if ($errors->has('start_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('start_date') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="date_to"><b>To</b><span class="text-danger">*</span></label>
                        <div class="form-group{{ $errors->has('end_date') ? ' has-error' : '' }} has-feedback">
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date') }}" placeholder="d/m/yy" required>
                            @if ($errors->has('end_date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('end_date') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 col-12">
                        <label for="date_to"><span class="text-danger"></span></label>
                        <div class="form-group has-feedback">
                            <button type="submit" class="btn btn-info btn-flat  search" onclick="search()" style="color: white"><i class='bx bx-search-alt'></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table class="table" id="table">
                            <thead class="table-light fw-semibold dataTableHeader">
                                <tr class="align-middle table">
                                    {{-- <th width="5%">SL#</th> --}}
                                    <th width="10%"> Transaction Date</th>
                                    <th width="66%"> Details</th>
                                    <th width="8%"> Debit</th>
                                    <th width="8%"> Credit</th>
                                    <th width="8%"> Balance</th>
                                </tr>
                                <tr id="previous_tr" class="bg-success" style="display: none">
                                    <td colspan="4">Previous Balance</td>
                                    <td id="prevBalance"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        function search() {
            var start_date = $("#start_date").val();
            var end_date = $("#end_date").val();
            var account_id = $("#account_id").val();
            var x = 1;
            if (start_date !== '' && end_date !== '' && account_id !== '') {
                var searchable = [];
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    }
                });
                var dTable = $('#table').DataTable({
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    processing: true,
                    serverSide: true,
                    "bDestroy": true,
                    language: {
                        processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                    },
                    "bDestroy": true,
                    ajax: {
                        url: "{{route('admin.account.statement.data')}}",
                        type: "POST",
                        data: {
                            'start_date': start_date,
                            'end_date': end_date,
                            'account_id': account_id,
                        },
                    },
                    columns: [
                        // { "render": function () {return x++; } },
                        {data: 'transaction_date', name:'transaction_date', orderable: true, searchable: true},
                        { data: 'details',name: 'details', orderable: true, searchable: true},
                        { data: 'debit',name: 'debit', orderable: true, searchable: true,class: "text-end"},
                        { data: 'credit',name: 'credit', orderable: true, searchable: true,class: "text-end"},
                        { data: 'balance', name: 'balance', orderable: true, searchable: true,class: "text-end"},
                    ],
                    order: [[0, 'asc']],
                    initComplete: function (data) {
                        var prevBalance = data.json.prevBalance;
                        $('#previous_tr').show()
                        document.getElementById('prevBalance').innerHTML = prevBalance;
                    },
                });
            }
            else {
                alert('Enter All Value')
            }
        }

    </script>
@endpush
