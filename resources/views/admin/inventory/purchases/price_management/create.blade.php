@extends('layouts.dashboard.app')

@section('title', 'Create Price Management')

@push('css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
        <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <style>
        .select2-container--default .select2-selection--single {
            padding: 6px;
            height: 37px;
            width: 100%;
            font-size: 1.2em;
            position: relative;
        }
    </style>
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.20.0/ckeditor.js"integrity="sha512-BcYkQlDTKkWL0Unn6RhsIyd2TMm3CcaPf0Aw1vsV28Dj4tpodobCPiriytfnnndBmiqnbpi2EelwYHHATr04Kg=="crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            }
        });
    </script>
@endpush
@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                 <a href="{{route('admin.inventory.price-management.index')}}">Price Management </a>
            </li>
            <li class="breadcrumb-item">
                 Create
            </li>
        </ol>
        <a href="{{ route('admin.inventory.price-management.index') }}" class="btn btn-sm btn-dark">Back to list</a>
    </nav>
@endsection

@section('content')

    <!-- Alert -->
    @include('layouts.dashboard.partials.alert')

    <form action="{{ route('admin.inventory.price-management.store') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div  class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label>Select Product </label>
                                <div class="input-group mb-3">
                                    <select required name="product_id" id="product_id" data-live-search="true" data-live-search-style="begins" class="selectpicke form-control select2"  title="Select Product...">
                                    </select>
                                </div>
                            </div>
                            <div  class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                 <div class="">
                                    <label><strong>Product Cost *</strong> </label>
                                    <input type="number" name="cost" id="cost" required class="form-control" step="any">
                                    <span class="validation-msg"></span>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <div class="">
                                    <label><strong>Product Price *</strong> </label>
                                    <input type="number" name="price" id="price" required class="form-control" step="any">
                                    <span class="validation-msg"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="mb-5"></div>
@endsection
@push('script')
    <script>
        $('#product_id').select2({
            ajax: {
                url: '{{route('admin.inventory.purchase.product.search')}}',
                dataType: 'json',
                type: "POST",
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    return query;
                },
                processResults: function (data) {
                    console.log();
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                value: item.id,
                                id: item.id,
                            }
                        })
                    };
                }
            }
        });
        $('#product_id').on("change", function() {
            var  productId = $('#product_id').val();
            var url = '{{ route('admin.inventory.product.price.search', ':id') }}';
                $.ajax({
                    type: 'GET',
                    url: url.replace(':id', productId),
                    success: function(data) {
                        console.log(data);
                        // const myArray = data.name.slice(0, 4)+ ' - ';
                        $('#price').val(data.price);
                        $('#cost').val(data.cost);
                    }
                });

        });

    </script>
@endpush
