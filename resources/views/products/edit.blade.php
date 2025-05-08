@extends('layouts.app')
@section('title')
    Edit Product
@endsection
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Product</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Products</li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Battery</h5>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">

              @csrf
              @method('PUT')

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Product Image</label>
                <div class="col-sm-10">
                  <input type="file" name="product_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                  
                  <div class="mt-2">
                    @if ($product->product_image)
                      <p class="mb-1">Current Image:</p>
                      <img src="{{ asset('storage/' . $product->product_image) }}" id="currentPreview" class="img-thumbnail mb-2" style="max-height: 150px;">
                    @endif
              
                    <img id="preview" src="#" style="display: none; max-height: 150px;" class="img-thumbnail">
                  </div>
                </div>
              </div>
              

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-10">
                  <input type="text" name="type" class="form-control" value="{{ $product->type }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Capacity</label>
                <div class="col-sm-10">
                  <input type="text" name="capacity" class="form-control" value="{{ $product->capacity }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Warranty (Months)</label>
                <div class="col-sm-10">
                  <input type="number" name="warranty_months" class="form-control" value="{{ $product->warranty_months }}">
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Cost Price</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="cost_price" class="form-control" value="{{ $product->cost_price }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Selling Price</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="selling_price" class="form-control" value="{{ $product->selling_price }}" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select name="vendor_id" class="form-select" required>
                    @foreach ($vendors as $vendor)
                      <option value="{{ $vendor->id }}" {{ $vendor->id == $product->vendor_id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-success">Update</button>
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
@push('scripts')
<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
      const preview = document.getElementById('preview');
      preview.src = reader.result;
      preview.style.display = 'block';

      // Hide current image if new one selected
      const current = document.getElementById('currentPreview');
      if (current) current.style.display = 'none';
    };

    if (event.target.files[0]) {
      reader.readAsDataURL(event.target.files[0]);
    }
  }
</script>  
@endpush