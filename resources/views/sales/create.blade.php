@extends('layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Add Sale</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Sales</a></li>
                <li class="breadcrumb-item active">Add</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sales Form</h5>
                        <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
                            @csrf

                            <!-- Customer Selection -->
                            <div class="mb-3">
                                <label for="customer_id">Customer Phone Number</label>
                                <input type="text" id="customer-phone" name="customer_phone" class="form-control" placeholder="Search by Phone Number" oninput="searchCustomer()" required>
                                <div id="customer-search-results" class="mt-2"></div>  
                                <input type="hidden" id="customer-id" name="customer_id">
                            </div>

                            <div class="mb-3">
                                <label>Or Create New Customer</label>
                                <input type="text" id="new-customer-name" name="customer_name" class="form-control" placeholder="Customer Name (If new)" required>
                                <input type="text" id="new-customer-phone" name="new_customer_phone" class="form-control mt-2" placeholder="Customer Phone" required>
                                <input type="text" id="new-customer-address" name="new_customer_address" class="form-control mt-2" placeholder="Customer Address (Optional)">
                            </div>

                            <div class="mb-3">
                                <label>Payment Method</label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="cash">Cash</option>
                                    <option value="online">Online</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>

                            <h5>Products</h5>
                            <table class="table" id="products-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Custom Price</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <button type="button" class="btn btn-secondary mb-3" onclick="addRow()">Add Product</button>

                            <div class="mb-3 mt-3">
                                <label>Discount</label>
                                <input type="number" step="0.01" name="discount" class="form-control" value="0" oninput="updateTotal()" id="discount-input">
                                <select class="form-control mt-2" onchange="applyDiscount(this.value)">
                                    <option value="0">No Discount</option>
                                    <option value="2">2%</option>
                                    <option value="4">4%</option>
                                    <option value="6">6%</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Final Total</label>
                                <input type="number" step="0.01" name="final_total" class="form-control" readonly>
                            </div>

                            <button class="btn btn-success">Save Sale</button>
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
    let products = @json($products);
    let productIndex = 0; 

    // Add one row on page load to help user
    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });

    function addRow() 
    {
        const currentIndex = productIndex++; // Increment the global index

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="products[${currentIndex}][product_id]" class="form-control" onchange="updatePrice(this, ${currentIndex})" required>
                    <option value="" selected disabled>Select Product</option>
                    ${products.map(p => `<option value="${p.id}" data-cost="${p.cost_price}" data-price="${p.selling_price}" data-quantity="${p.inventory ? p.inventory.quantity : 0}">
                        ${p.name} (In stock: ${p.inventory ? p.inventory.quantity : 0})
                    </option>`).join('')}
                </select>
            </td>
            <td><input type="number" class="form-control cost-price" readonly></td>
            <td><input type="number" class="form-control selling-price" readonly></td>
            <td><input type="number" name="products[${currentIndex}][price]" class="form-control custom-price" oninput="updateSubtotal(this)" required></td>
            <td><input type="number" name="products[${currentIndex}][quantity]" class="form-control quantity" oninput="updateSubtotal(this)" min="1" required></td>
            <td><input type="number" name="products[${currentIndex}][subtotal]" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotal();">X</button></td>
        `;
        document.querySelector("#products-table tbody").appendChild(row);
    }


    function updatePrice(select, index) 
    {
        const row = select.closest('tr');
        const selected = select.selectedOptions[0];
        const costPrice = selected.dataset.cost || 0;
        const sellingPrice = selected.dataset.price || 0;

        row.querySelector('.cost-price').value = costPrice;
        row.querySelector('.selling-price').value = sellingPrice;

        const customPrice = row.querySelector(`input[name="products[${index}][price]"]`);
        customPrice.value = sellingPrice;

        updateSubtotal(customPrice);
    }


    function updateSubtotal(input) {
    const row = input.closest('tr');

    const priceInput = row.querySelector('input[name*="[price]"]');
    const quantityInput = row.querySelector('input[name*="[quantity]"]');

    const price = parseFloat(priceInput?.value || 0);
    const qty = parseFloat(quantityInput?.value || 0);

    row.querySelector('.subtotal').value = (price * qty).toFixed(2);
    updateTotal();
}


    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(s => {
            total += parseFloat(s.value || 0);
        });
        const discount = parseFloat(document.querySelector('#discount-input').value || 0);
        document.querySelector('[name="final_total"]').value = (total - discount).toFixed(2);
    }

    function applyDiscount(percent) {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(s => {
            total += parseFloat(s.value || 0);
        });
        const discountValue = (total * parseFloat(percent) / 100).toFixed(2);
        document.querySelector('#discount-input').value = discountValue;
        updateTotal();
    }

    // Validate form inputs before submission
    function validateSaleForm() {
    const rows = document.querySelectorAll('#products-table tbody tr');
    if (rows.length === 0) {
        alert('Please add at least one product.');
        return false;
    }

    for (let row of rows) {
        const productSelect = row.querySelector('select[name^="products"]');
        const priceInput = row.querySelector('input[name*="[price]"]');
        const qtyInput = row.querySelector('input[name*="[quantity]"]');

        if (!productSelect?.value) {
            alert('Please select a product for each row.');
            return false;
        }

        if (!priceInput?.value || parseFloat(priceInput.value) <= 0) {
            alert('Please enter a valid price for each product.');
            return false;
        }

        if (!qtyInput?.value || parseFloat(qtyInput.value) <= 0) {
            alert('Each product must have a quantity greater than 0.');
            return false;
        }
    }

    const customerId = document.getElementById('customer-id').value;
    const newCustomerName = document.getElementById('new-customer-name').value.trim();
    const newCustomerPhone = document.getElementById('new-customer-phone').value.trim();

    if (!customerId && (!newCustomerName || !newCustomerPhone)) {
        alert('Please select an existing customer or fill in new customer details.');
        return false;
    }

    return true;
}


    // Remove empty product rows on submit
    function cleanEmptyProductRows() {
        const rows = document.querySelectorAll('#products-table tbody tr');
        rows.forEach(row => {
            const select = row.querySelector('select[name^="products"]');
            const qty = row.querySelector('input[name^="products"][name$="[quantity]"]').value;
            if (!select.value || qty === '' || qty <= 0) {
                row.remove();
            }
        });
    }

    // Attach submit event to clean rows and validate form
    document.getElementById('sale-form').addEventListener('submit', function(event) {
        cleanEmptyProductRows();
        if (!validateSaleForm()) {
            event.preventDefault();
        }
    });

    // Customer search function
    function searchCustomer() {
        const phone = document.getElementById('customer-phone').value;

        if (phone.length < 3) {
            document.getElementById('customer-search-results').innerHTML = '';
            return;
        }

        fetch(`/search-customer?phone=${phone}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if (data.length > 0) {
                    data.forEach(customer => {
                        html += `<div><a href="#" onclick="selectCustomer(${customer.id}, '${customer.name}', '${customer.phone}')">${customer.name} - ${customer.phone}</a></div>`;
                    });
                } else {
                    html = '<div>No customer found. You can create a new one.</div>';
                }
                document.getElementById('customer-search-results').innerHTML = html;
            });
    }

    // Select customer from search results
    function selectCustomer(id, name, phone) {
        document.getElementById('customer-id').value = id;
        document.getElementById('customer-phone').value = phone;
        document.getElementById('new-customer-name').value = name;
        document.getElementById('new-customer-phone').value = phone;
        document.getElementById('customer-search-results').innerHTML = '';
    }
</script>
@endpush
