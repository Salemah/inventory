@extends('layouts.dashboard.app')

@section('title', 'Add Fund-Transfer')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{route('admin.account.fund-transfer.create')}}">Add Fund-Transfer</a>
            </li>
        </ol>
        <a href="{{ route('admin.account.fund-transfer.index') }}" class="btn btn-sm btn-dark">Back to list</a>
    </nav>
@endsection

@section('content')

    <!-- Alert -->
    @include('layouts.dashboard.partials.alert')
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <form action="{{ route('admin.account.fund-transfer.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                     <div class="row">
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                 <label for="fund_transaction_title"><b>Fund Transfer Title</b><span class="text-danger">*</span></label>
                                    <input type="text" name="fund_transaction_title" id="fund_transaction_title"class="form-control" value="{{ old('fund_transaction_title') }}"placeholder="Enter Title...">
                                    @if ($errors->has('fund_transaction_title'))
                                        <span class="alert text-danger" role="alert">
                                            <strong>{{ $errors->first('fund_transaction_title') }}</strong>
                                        </span>
                                    @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="fund_transaction_date"><b>Date</b><span class="text-danger">*</span></label>
                                <input type="date" name="fund_transaction_date" id="fund_transaction_date"class="form-control" value="{{ old('fund_transaction_date') }}">
                                @if ($errors->has('fund_transaction_date'))
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $errors->first('fund_transaction_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="form_account_id"><b>Account From</b><span class="text-danger">*</span></label>
                                <select name="form_account_id" id="form_account_id" class="form-control"onchange="getBalance()">
                                    <option value="" selected>-- Select Bank Account --</option>
                                     @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }} | {{ $bankAccount->account_number }}
                                                @if ($bankAccount->type == 1) | Cash-Account @else | Bank-Account @endif</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('form_account_id'))
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $errors->first('form_account_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="to_account_id"><b>Account To</b><span class="text-danger">*</span></label>
                                <select name="to_account_id" id="to_account_id" class="form-control">
                                    <option value="" selected>-- Select Bank Account --</option>
                                    @foreach($bankAccounts as $bankAccount)
                                        <option value="{{ $bankAccount->id }}">{{ $bankAccount->name }}| {{ $bankAccount->account_number }}@if ($bankAccount->type == 1) | Cash-Account @else | Bank-Account @endif</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('to_account_id'))
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $errors->first('to_account_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="deposit_source"><b>Amount</b><span class="text-danger">*</span><span class="text-info" id="balance" style="display: none"></span></label>
                                <input type="hidden" value="" id="amount_balance">
                                <input type="number" min="0" name="amount" id="amount" class="form-control"value="{{ old('amount') }}" placeholder="1200 ..."onkeyup="checkAmount(this)">
                                @if ($errors->has('amount'))
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="cheque_number"><b>Cheque Number</b></label>
                                <input type="text" name="cheque_number" id="cheque_number" class="form-control"value="{{ old('cheque_number') }}" placeholder=" ...">
                                @if ($errors->has('cheque_number'))
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $errors->first('cheque_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-12 mb-2">
                                <label for="description"><b>Description</b></label>
                                <textarea name="description" id="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}"
                                    placeholder="Description..."></textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <div class="mb-5"></div>


@endsection
@push('script')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>

        function getBalance() {
            var accountId = $('#form_account_id').val();
            if (accountId !== null) {
                var url = '{{ route("admin.account.bank.account.balance",":id") }}';
                $.ajax({
                    type: "GET",
                    url: url.replace(':id', accountId),
                    success: function (resp) {
                        $('#balance').show();
                        document.getElementById('balance').innerHTML = '( ' + resp + ' )';
                        $('#amount_balance').val(resp);
                        document.getElementById('amount').max = resp;
                    }, // success end
                    error: function (error) {
                        // location.reload();
                    } // Error
                })
            } else {

            }
            }


            function getPurpose() {
            if ($('#transaction_purpose').val() == 2) {
                $('#balance').hide();
            } else {
                var accountId = $('#account_id').val();
                getBalance(accountId)
            }
            }

            function checkAmount(amount) {
            var amount = amount.value;
            var amountBalance = $('#amount_balance').val();
            if (parseFloat(amountBalance) < parseFloat(amount)) {
                swal({
                    title: `Alert?`,
                    text: "You don't have enough balance.",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $('#amount').val(0);
                    }

                });
            }
        }
    </script>
@endpush
