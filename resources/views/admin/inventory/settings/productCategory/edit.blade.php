@extends('layouts.dashboard.app')

@section('title', 'Edit Product Category')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;

        }
    </style>
@endpush

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>

            </li>
            <li class="breadcrumb-item">
             <a href="{{route('admin.inventory.settings.productCategory.index')}}">Product Category</a>
            </li>
        </ol>
        <a href="{{ route('admin.inventory.settings.productCategory.index') }}" class="btn btn-sm btn-dark">Back to list</a>
    </nav>
@endsection

@section('content')

    <!-- Alert -->
    @include('layouts.dashboard.partials.alert')

    <form action="{{ route('admin.inventory.settings.productCategory.update', $productCategory->id )}}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <div class="form-group col-12 col-sm-12 col-md-12 mb-2">
                                    <label for="name"><b>Category Name</b><span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $productCategory->name }}"
                                        placeholder="Enter Category Name">
                                    @error('name')
                                        <span class="alert text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-sm-12 col-md-12 mb-2">
                                    <label for="category_code"><b>Category Code</b><span class="text-danger">*</span></label>
                                    <input type="text" name="category_code" id="category_code"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ $productCategory->category_code }}"
                                        placeholder="Enter Product Category Code">
                                    @error('category_code')
                                        <span class="alert text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-sm-12 col-md-12 mb-2">
                                    <label for="status"><b>Status</b><span class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror">
                                        <option >--Select Status--</option>
                                        <option value="1" {{ $productCategory->status== 1 ? 'selected': '' }}>Active</option>
                                        <option value="0" {{ $productCategory->status== 0 ? 'selected': '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <span class="alert text-danger" role="alert">
                                         <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-12 col-sm-12 col-md-12 mb-2">
                                    <label for="parent_category"><b>Select Parent Category</b><span class="text-danger">*</span></label>
                                    <select name="parent_category" id="parent_category"
                                            class="form-select @error('parent_category') is-invalid @enderror">
                                        <option >--Select Category--</option>

                                        @foreach($productCategories as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($category)
                                                    @if ($item->id == $category->id)
                                                    {{ 'selected' }}
                                                    @endif
                                                @endif >
                                                {{ $item->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_category')
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="image"><b>Upload Brand Image</b> <span class="text-danger">*</span></label>
                                <input type="file" id="image"
                                    data-default-file="{{asset('img/inventory/setting/productCategory/'.$productCategory->image)}}"
                                    class="dropify form-control @error('image') is-invalid @enderror" name="image">
                                @error('image')
                                <span class="alert text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        <div>

                        <div class="row">
                            <div class="form-group col-12 mb-2">
                                <label for="description"><b>Description</b></label>
                                <textarea name="description" id="description" rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    value="{{ old('description') }}"
                                    placeholder="Description...">{{ $productCategory->description }}</textarea>
                                @error('description')
                                    <span class="alert text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"
        integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });

    </script>
@endpush
