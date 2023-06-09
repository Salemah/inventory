@extends('layouts.dashboard.app')

@section('title', 'Authority Edit')

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="d-flex align-items-center justify-content-between" style="width: 100%">
        <ol class="breadcrumb my-0 ms-2">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.loan-authority.index') }}">Authority Edit</a>
            </li>
        </ol>
        <a href="{{ route('admin.loan-authority.index') }}" class="btn btn-sm btn-dark">Back to list</a>
    </nav>
@endsection

@section('content')
    <!-- Alert -->
    @include('layouts.dashboard.partials.alert')
    <form action="{{ route('admin.loan-authority.update',$authority->id) }}" enctype="multipart/form-data" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="deposit_source"><b>Name</b><span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"value="{{$authority->name}}" placeholder="Enter Name...">
                                    @if ($errors->has('name'))
                                        <span class="alert text-danger">
                                             {{ $errors->first('name') }}
                                        </span>
                                    @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="deposit_source"><b>Email</b></label>
                                <input type="text" name="email" id="email" class="form-control"value="{{$authority->email}}" placeholder="Enter email...">
                                    @if ($errors->has('email'))
                                        <span class="alert text-danger">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-6 mb-2">
                                <label for="phone"><b>Phone</b><span class="text-danger">*</span></label>
                                <input type="number" name="phone" id="phone" class="form-control"value="{{$authority->phone}}" placeholder="Enter phone...">
                                    @if ($errors->has('phone'))
                                        <span class="alert text-danger">
                                           {{ $errors->first('phone') }}
                                        </span>
                                    @endif
                            </div>
                            <div class="form-group col-12 col-sm-12 col-md-12 mb-2">
                                <label for="note"><b>Note</b></label>
                                <textarea name="note" id="note" rows="3" class="form-control " placeholder="Enter Note...">{{$authority->note}}</textarea>
                                    @if ($errors->has('note'))
                                        <span class="help-block">
                                            {{ $errors->first('note') }}
                                        </span>
                                    @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.20.0/ckeditor.js"integrity="sha512-BcYkQlDTKkWL0Unn6RhsIyd2TMm3CcaPf0Aw1vsV28Dj4tpodobCPiriytfnnndBmiqnbpi2EelwYHHATr04Kg=="crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    CKEDITOR.replace('note', {
            toolbarGroups: [
                {"name": "styles","groups": ["styles"]},
                {"name": "basicstyles","groups": ["basicstyles"]},
                {"name": "paragraph","groups": ["list", "blocks"]},
                {"name": "document","groups": ["mode"]},
                {"name": "links","groups": ["links"]},
                {"name": "insert","groups": ["insert"]},
                {"name": "undo","groups": ["undo"]},
            ],
            // Remove the redundant buttons from toolbar groups defined above.
            removeButtons: 'Source,Image,Strike,Subscript,Superscript,Anchor,Styles,Specialchar,PasteFromWord'
        });
</script>
@endpush
