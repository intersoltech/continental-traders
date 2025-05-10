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
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sales Form</h5>
                            <form action="{{ route('sales.store') }}" method="POST">
                                @csrf

                                <!-- Customer Search or Add New Customer -->
                                <div class="mb-3">
                                    <label for="customer_id">Customer Phone Number</label>
                                    <input type="text" id="customer-phone" name="customer_phone" class="form-control"
                                        placeholder="Search by Phone Number" oninput="searchCustomer()" required>
                                    <div id="customer-search-results" class="mt-2"></div>  
                                    
                                    <!-- Hidden field to store customer_id -->
                                    <input type="hidden" id="customer-id" name="customer_id">
                                </div>

                                <div class="mb-3">
                                    <label>Or Create New Customer</label>
                                    <input type="text" id="new-customer-name" name="customer_name" class="form-control"
                                        placeholder="Customer Name (If new)" required>
                                    <input type="text" id="new-customer-phone" name="new_customer_phone"
                                        class="form-control mt-2" placeholder="Customer Phone" required>
                                    <input type="text" id="new-customer-address" name="new_customer_address"
                                        class="form-control mt-2" placeholder="Customer Address (Optional)">
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
                                    <tbody>
                                        <!-- Dynamic product rows will be added here -->
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-secondary" onclick="addRow()">Add Product</button>

                                <div class="mb-3 mt-3">
                                    <label>Discount</label>
                                    <input type="number" step="0.01" name="discount" class="form-control" value="0"
                                        oninput="updateTotal()">
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
        // Initialize products data
        let products = @json($products);

        // Function to add a new row for product selection
        function addRow() {
            const row = `<tr>
                <td>
                    <select name="products[][product_id]" class="form-control" onchange="updatePrice(this)">
                        <option selected>Select Product</option>
                        ${products.map(p => `<option value="${p.id}" data-cost="${p.cost_price}" data-price="${p.selling_price}" data-quantity="${p.inventory ? p.inventory.quantity : 0}">${p.name} (In stock: ${p.inventory ? p.inventory.quantity : 0})</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" class="form-control cost-price" readonly></td>
                <td><input type="number" class="form-control selling-price" readonly></td>
                <td><input type="number" name="products[][price]" class="form-control custom-price" oninput="updateSubtotal(this)"></td>
                <td><input type="number" name="products[][quantity]" class="form-control quantity" oninput="updateSubtotal(this)"></td>
                <td><input type="number" name="products[][subtotal]" class="form-control subtotal" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); updateTotal();">X</button></td>
            </tr>`;

            document.querySelector("#products-table tbody").insertAdjacentHTML("beforeend", row);
        }

        // Function to update the price and subtotal when a product is selected
        function updatePrice(select) {
            const costPrice = select.selectedOptions[0].dataset.cost;
            const sellingPrice = select.selectedOptions[0].dataset.price;
            const row = select.closest('tr');

            row.querySelector('.cost-price').value = costPrice; // Set Cost Price
            row.querySelector('.selling-price').value = sellingPrice; // Set Selling Price
            row.querySelector('input[name="products[][price]"]').value = sellingPrice; // Set Custom Price to Selling Price by default
            updateSubtotal(row.querySelector('.quantity'));
        }

        // Function to update subtotal and total when quantity or custom price changes
        function updateSubtotal(input) {
            const row = input.closest('tr');
            const price = parseFloat(row.querySelector('input[name="products[][price]"]').value || 0);
            const qty = parseFloat(input.value || 0);
            const subtotal = price * qty;
            row.querySelector('.subtotal').value = subtotal.toFixed(2);
            updateTotal();
        }

        // Function to update the final total based on subtotals and discount
        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(s => {
                total += parseFloat(s.value || 0);
            });
            const discount = parseFloat(document.querySelector('[name="discount"]').value || 0);
            document.querySelector('[name="final_total"]').value = (total - discount).toFixed(2);
        }

        // Search customer by phone
        function searchCustomer() {
            let phone = document.getElementById('customer-phone').value;

            if (phone.length < 3) {
                document.getElementById('customer-search-results').innerHTML = '';
                return;
            }

            fetch(`/search-customer?phone=${phone}`)
                .then(response => response.json())
                .then(data => {
                    let results = '';
                    if (data.length > 0) {
                        data.forEach(customer => {
                            results +=  
                                `<div><a href="#" onclick="selectCustomer(${customer.id}, '${customer.name}', '${customer.phone}')">${customer.name} - ${customer.phone}</a></div>`;                            
                        });
                    } else {
                        results = '<div>No customer found. You can create a new one.</div>';
                    }
                    document.getElementById('customer-search-results').innerHTML = results;
                });
        }

        // Select a customer from the search results
        function selectCustomer(id, name, phone) {
            // Populate the hidden customer ID field
            document.getElementById('customer-id').value = id;
            // Populate the phone and name fields
            document.getElementById('customer-phone').value = phone;
            document.getElementById('new-customer-name').value = name;
            document.getElementById('new-customer-phone').value = phone;
            document.getElementById('customer-search-results').innerHTML = ''; // Clear search results
        }
    </script>
@endpush
