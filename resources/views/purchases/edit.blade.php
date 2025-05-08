@extends('layouts.app')
@section('title', 'Edit Purchase')
@section('content')
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Edit Purchase</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
        <li class="breadcrumb-item active">Edit</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Update Purchase</h5>

            <form action="{{ route('purchases.update', $purchase) }}" method="POST">
              @csrf
              @method('PUT')
              <!-- … existing vendor, date, payment method fields … -->
              
              <!-- PRODUCT ROWS -->
              <hr>
              <div id="product-rows">
                @foreach($purchase->items as $i => $item)
                  <div class="row mb-3 product-row">
                    <!-- Product Select -->
                    <div class="col-md-4">
                      <select name="products[{{ $i }}][product_id]" class="form-select" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                          <option value="{{ $product->id }}"
                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->capacity }})
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <!-- Quantity -->
                    <div class="col-md-3">
                      <input type="number"
                             name="products[{{ $i }}][quantity]"
                             class="form-control quantity"
                             value="{{ $item->quantity }}"
                             placeholder="Quantity"
                             required>
                    </div>
                    <!-- Cost Price -->
                    <div class="col-md-3">
                      <input type="number"
                             name="products[{{ $i }}][cost_price]"
                             class="form-control cost_price"
                             value="{{ $item->price }}" <!-- assuming price field -->
                             placeholder="Cost Price"
                             step="0.01"
                             required>
                    </div>
                    <!-- Remove Button -->
                    <div class="col-md-2">
                      <button type="button" class="btn btn-danger remove-row">
                        Remove
                      </button>
                    </div>
                  </div>
                @endforeach
              </div>
            
              <div class="mb-3">
                <button type="button" class="btn btn-secondary" id="add-product-row">
                  Add Another Product
                </button>
              </div>
            
              <!-- TOTAL AMOUNT -->
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Amount</label>
                <div class="col-sm-10">
                  <input type="number"
                         step="0.01"
                         name="total_amount"
                         id="total_amount"
                         class="form-control"
                         value="{{ $purchase->total_amount }}"
                         readonly>
                </div>
              </div>
            
              <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" class="btn btn-primary">
                    Update Purchase
                  </button>
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
  let rowCount = {{ $purchase->items->count() }};

  document.getElementById('add-product-row').addEventListener('click', function() {
    const container = document.getElementById('product-rows');
    const template = container.querySelector('.product-row').cloneNode(true);
    
    // Reset values and update indexes
    template.querySelectorAll('select, input').forEach(el => {
      const name = el.getAttribute('name');
      const newName = name.replace(/\[\d+\]/, `[${rowCount}]`);
      el.setAttribute('name', newName);
      if (el.tagName === 'INPUT') el.value = '';
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
    });
    container.appendChild(template);
    rowCount++;
    calculateTotal();
  });

  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) {
      const row = e.target.closest('.product-row');
      row.remove();
      calculateTotal();
    }
  });

  document.addEventListener('input', function(e) {
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

  // Initial calculation on page load
  document.addEventListener('DOMContentLoaded', calculateTotal);
</script>

  
@endpush
