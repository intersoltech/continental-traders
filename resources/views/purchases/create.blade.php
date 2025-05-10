@extends('layouts.app')
@section('title', 'Add New Purchase')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Purchase</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
        <li class="breadcrumb-item active">Add</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Purchase Form</h5>

            <form action="{{ route('purchases.store') }}" method="POST">
              @csrf
            
              <!-- Vendor -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Vendor</label>
                <div class="col-sm-10">
                  <select name="vendor_id" class="form-select" required>
                    <option selected disabled>Select Vendor</option>
                    @foreach($vendors as $vendor)
                      <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            
              <!-- Purchase Date -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Purchase Date</label>
                <div class="col-sm-10">
                  <input type="date" name="purchase_date" class="form-control" required>
                </div>
              </div>
            
              <!-- Payment Method -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Payment Method</label>
                <div class="col-sm-10">
                  <select name="payment_method" class="form-select" required>
                    <option value="cash">Cash</option>
                    <option value="online">Online</option>
                    <option value="card">Card</option>
                  </select>
                </div>
              </div>
            
              <!-- Product Rows -->
              <hr>
              <div id="product-rows">
                <div class="row mb-3 product-row">
                  <div class="col-md-4">
                    <select name="products[0][product_id]" class="form-select" required>
                      <option value="">Select Product</option>
                      @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->capacity }})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-3">
                    <input type="number" name="products[0][quantity]" class="form-control quantity" placeholder="Quantity" required>
                  </div>
                  <div class="col-md-3">
                    <input type="number" name="products[0][cost_price]" class="form-control cost_price" placeholder="Cost Price" required step="0.01">
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-row d-none">Remove</button>
                  </div>
                </div>
              </div>
            
              <div class="mb-3">
                <button type="button" class="btn btn-secondary" id="add-product-row">Add Another Product</button>
              </div>
            
              <!-- Auto Total -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                  <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" readonly>
                </div>
              </div>
            
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">Save Purchase</button>
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
  let rowCount = 1;

  document.getElementById('add-product-row').addEventListener('click', function () {
    const container = document.getElementById('product-rows');
    const newRow = container.firstElementChild.cloneNode(true);

    // Update name attributes
    newRow.querySelectorAll('select, input').forEach((input) => {
      const name = input.getAttribute('name');
      if (name) {
        const newName = name.replace(/\[\d+\]/, `[${rowCount}]`);
        input.setAttribute('name', newName);
        input.value = '';
      }
    });

    newRow.querySelector('.remove-row').classList.remove('d-none');
    container.appendChild(newRow);
    rowCount++;
  });

  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-row')) {
      e.target.closest('.product-row').remove();
      calculateTotal();
    }
  });

  document.addEventListener('input', function (e) {
    if (e.target.classList.contains('quantity') || e.target.classList.contains('cost_price')) {
      calculateTotal();
    }
  });

  function calculateTotal() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
      const qty = parseFloat(row.querySelector('.quantity')?.value || 0);
      const price = parseFloat(row.querySelector('.cost_price')?.value || 0);
      total += qty * price;
    });
    document.getElementById('total_amount').value = total.toFixed(2);
  }
</script>

  
@endpush
