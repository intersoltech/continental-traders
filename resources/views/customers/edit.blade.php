@extends('layouts.app')
@section('title', 'Edit Customer')
@section('content')
<main id="main" class="main">

  <div class="pagetitle">
    <h1>Edit Customer</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Edit Customer</h5>

            <form method="POST" action="{{ route('customers.update', $customer->id) }}">
              @csrf
              @method('PUT')
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                  <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                  <input type="email" name="email" class="form-control" value="{{ $customer->email }}">
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Address</label>
                <div class="col-sm-10">
                  <textarea name="address" class="form-control" style="height: 80px">{{ $customer->address }}</textarea>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Update Customer</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection
