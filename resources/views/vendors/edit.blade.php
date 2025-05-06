@extends('layouts.app')

@section('title')
    Edit Vendor
@endsection

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Edit Vendor</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Vendor Form</h5>

                        <!-- Edit Vendor Form -->
                        <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Vendor Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $vendor->name }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="contact" class="col-sm-2 col-form-label">Contact</label>
                                <div class="col-sm-10">
                                    <input type="text" name="contact" class="form-control" id="contact" value="{{ $vendor->contact }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="address" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control" id="address" style="height: 100px" required>{{ $vendor->address }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-success">Update Vendor</button>
                                    <a href="{{ route('vendors.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form><!-- End Edit Vendor Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->
@endsection
