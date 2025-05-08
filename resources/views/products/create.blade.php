@extends('layouts.app')
@section('title')
    Add Product
@endsection
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Product</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item">Products</li>
        <li class="breadcrumb-item active">Create</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">New Battery</h5>

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">

              @csrf
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Product Image</label>
                <div class="col-sm-10">
                  <input type="file" name="product_image" class="form-control" accept="image/*" onchange="previewImage(event)">
                  <div class="mt-2">
                    <img id="preview" src="#" alt="Preview" style="display: none; max-height: 150px;" class="img-thumbnail">
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                  <input type="text" name="name" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-10">
                  <input type="text" name="type" class="form-control" placeholder="Tubular / VRLA" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Capacity</label>
                <div class="col-sm-10">
                  <input type="text" name="capacity" class="form-control" placeholder="e.g. 12V 100Ah" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Warranty (Months)</label>
                <div class="col-sm-10">
                  <input type="number" name="warranty_months" class="form-control" min="0">
                </div>
              </div>              

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Cost Price</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="cost_price" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Selling Price</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="selling_price" class="form-control" required>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select name="vendor_id" class="form-select" required>
                    <option value="">Select Vendor</option>
                    @foreach ($vendors as $vendor)
                      <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Create</button>
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
    const input = event.target;
    const reader = new FileReader();

    reader.onload = function() {
      const preview = document.getElementById('preview');
      preview.src = reader.result;
      preview.style.display = 'block';
    };

    if (input.files && input.files[0]) {
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

  
@endpush
