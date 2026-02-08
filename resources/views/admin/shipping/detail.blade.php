@extends('admin.layouts.app',['page'=>'User','page2'=>'Shipping-All'])

@section('css')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('admin') }}/node_modules/summernote/dist/summernote-bs4.css" />
@endsection

@section('content')

<div class="section-header">
    <div class="section-header-back">
        <a href="{{ url()->previous() }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Profile</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="{{ route('shipping.index') }}">Shipping</a>
        </div>
        <div class="breadcrumb-item">Profile</div>
    </div>
</div>

<div class="section-body">
    <h2 class="section-title">Shippings</h2>
    <p class="section-lead">Components relating to Shippings, lists of Shippings and so on.</p>

    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Shipping Address By: <a href="{{ route('user.detail',$user->id) }}">{{ $user->name }}</a>
                    </h4>
                </div>
                <div>
                    <div class="card-body">
                        <form>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>ID</label>
                                    <input type="text" class="form-control" value="{{ $shipping->id }}" readonly />
                                    {{-- <div class="invalid-feedback">
                                            Please fill in the first name
                                        </div> --}}
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>Created At</label>
                                    <input type="text" class="form-control" value="{{ $shipping->created_at }}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $shipping->country }}" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $shipping->name }}" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>Company Name</label>
                                    <input type="text" class="form-control" value="{{ $shipping->company_name }}"
                                        readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12 col-12">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $shipping->address }}" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-7 col-12">
                                    <label>Province <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $shipping->province }}"
                                        readonly />
                                </div>
                                <div class="form-group col-md-5 col-12">
                                    <label>Zip <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" value="{{ $shipping->zip }}" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-7 col-12">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ $shipping->email }}" readonly />
                                </div>
                                <div class="form-group col-md-5 col-12">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" value="{{ $shipping->phone }}" readonly />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Notes</label>
                                    <textarea class="form-control summernote-simple"
                                        readonly>{{ $shipping->notes }}</textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<!-- JS Libraies -->
<script src="{{ asset('admin') }}/js/page/components-user.js"></script>

<!-- Page Specific JS File -->
@endsection
