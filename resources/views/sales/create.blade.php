@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Sale</h1>
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Customer</label>
            <select name="customer_id" class="form-control" required>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <h5>Products</h5>
        <table class="table" id="products-table">
            <thead>
                <tr>
                    <th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button type="button" class="btn btn-secondary" onclick="addRow()">Add Product</button>

        <div class="mb-3 mt-3">
            <label>Discount</label>
            <input type="number" step="0.01" name="discount" class="form-control" value="0">
        </div>

        <div class="mb-3">
            <label>Final Total</label>
            <input type="number" step="0.01" name="final_total" class="form-control" readonly>
        </div>

        <button class="btn btn-success">Save Sale</button>
    </form>
</div>

<script>
let products = @json($products);

function addRow() {
    const row = `<tr>
        <td>
            <select name="items[][product_id]" class="form-control" onchange="updatePrice(this)">
                ${products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (In stock: ${p.quantity})</option>`).join('')}
            </select>
        </td>
        <td><input type="number" name="items[][price]" class="form-control price" readonly></td>
        <td><input type="number" name="items[][quantity]" class="form-control quantity" oninput="updateSubtotal(this)"></td>
        <td><input type="number" name="items[][subtotal]" class="form-control subtotal" readonly></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotal();">X</button></td>
    </tr>`;
    document.querySelector("#products-table tbody").insertAdjacentHTML("beforeend", row);
}

function updatePrice(select) {
    const price = select.selectedOptions[0].dataset.price;
    const row = select.closest('tr');
    row.querySelector('.price').value = price;
    updateSubtotal(row.querySelector('.quantity'));
}

function updateSubtotal(input) {
    const row = input.closest('tr');
    const price = parseFloat(row.querySelector('.price').value || 0);
    const qty = parseFloat(input.value || 0);
    const subtotal = price * qty;
    row.querySelector('.subtotal').value = subtotal.toFixed(2);
    updateTotal();
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(s => {
        total += parseFloat(s.value || 0);
    });
    const discount = parseFloat(document.querySelector('[name="discount"]').value || 0);
    document.querySelector('[name="final_total"]').value = (total - discount).toFixed(2);
}
</script>
@endsection
