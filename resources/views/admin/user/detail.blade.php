@extends('admin.layouts.app',['page'=>'User','page2'=>'User-All'])

@section('css')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('admin') }}/node_modules/summernote/dist/summernote-bs4.css" />
@endsection

@section('content')

<div class="section-header">
    <div class="section-header-back">
        <a href="{{ route('user.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
    <h1>Profile</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
        <div class="breadcrumb-item active">
            <a href="{{ route('user.index') }}">User</a>
        </div>
        <div class="breadcrumb-item">Profile</div>
    </div>
</div>

<div class="section-body">
    <h2 class="section-title">Users</h2>
    <p class="section-lead">Components relating to users, lists of users and so on.</p>

    <div class="row">
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card author-box card-primary">
                <div class="card-body">
                    <div class="author-box-left">
                        <img alt="image" src="{{ asset('admin') }}/img/avatar/avatar-1.png"
                            class="rounded-circle author-box-picture">
                    </div>
                    <div class="author-box-details">
                        <form>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Name</label>
                                        <input type="text" class="form-control" value="{{ $user->name }}" readonly />
                                        {{-- <div class="invalid-feedback">
                                            Please fill in the first name
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-7 col-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" readonly />
                                    </div>
                                    <div class="form-group col-md-5 col-12">
                                        <label>Phone</label>
                                        <input type="tel" class="form-control" value="{{ $user->phone }}" readonly />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label>Address</label>
                                        <textarea class="form-control summernote-simple"
                                            readonly>{{ $user->address ?? 'Null' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-lg-12">
            <h2 class="section-title">{{ $user->name }} Shipping Address</h2>
        </div>
        <div class="col-12 col-sm-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{$shippings->count() ?? '0'}} Shipping Address</h4>
                    <div class="card-header-form">
                        <form action="{{ route('user.shipping.search', $user->id) }}" method="GET">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search" name="search" value="">
                                <div class="input-group-btn">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder">
                        <li class="media">
                            <div class="media-body">
                                @foreach ($shippings as $shipping)
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Shipping Name: {{ $shipping->name }}</h4>
                                        <div class="card-header-action">
                                            <a data-collapse="#mycard-collapse-{{ $shipping->id }}"
                                                class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="collapse hide" id="mycard-collapse-{{ $shipping->id }}">
                                        <div class="card-body">
                                            <form>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>ID</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->id }}" readonly />
                                                        {{-- <div class="invalid-feedback">
                                                                Please fill in the first name
                                                            </div> --}}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Created At</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->created_at }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Country <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->country }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->name }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Company Name</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->company_name }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12 col-12">
                                                        <label>Address <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->address }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-7 col-12">
                                                        <label>State <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->state }}" readonly />
                                                    </div>
                                                    <div class="form-group col-md-5 col-12">
                                                        <label>Zip <span class="text-danger">*</span></label>
                                                        <input type="tel" class="form-control"
                                                            value="{{ $shipping->zip }}" readonly />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-7 col-12">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $shipping->email }}" readonly />
                                                    </div>
                                                    <div class="form-group col-md-5 col-12">
                                                        <label>Phone <span class="text-danger">*</span></label>
                                                        <input type="tel" class="form-control"
                                                            value="{{ $shipping->phone }}" readonly />
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
                                        {{-- <div class="card-footer">
                                            Card Footer
                                        </div> --}}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </li>
                    </ul>
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
