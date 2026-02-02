@include('layouts.header', ['pageTitle' => 'Products'])

<div id="main-content">
    <h2>Products</h2>

    <!-- Success/Error Message Area -->
    <div id="message-area"></div>

    <div id="auto-low-stock-alert">
        <p style="color: #666;"><em>Checking stock levels...</em></p>
    </div>

    <form id="addProductForm" method="post" action="<?= url('/products/create') ?>">
        <label>Product Name:</label><br>
        <input name="name" placeholder="Product name" required style="width: 300px;"><br><br>

        <label>Price:</label><br>
        <input name="price" type="number" step="0.01" placeholder="0.00" required style="width: 300px;"><br><br>

        <label>Quantity:</label><br>
        <input name="quantity" type="number" placeholder="Stock quantity" required style="width: 300px;"><br><br>

        <label>Supplier:</label><br>
        <select name="supplier_id" style="width: 300px;">
            <option value="">Select Supplier</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier['id'] }}">
                    {{{ $supplier['name'] }}}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Add Product</button>
    </form>

    <br>

    <table border="1" cellpadding="5" id="productsTable">
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Supplier</th>
            <th>Action</th>
        </tr>

        @foreach($products as $product)
            <tr @if($product['quantity'] <= 10) style="background-color: #ffcccc;" @endif>
                <td>{{{ $product['name'] }}}</td>
                <td>{{{ $product['price'] }}}</td>
                <td>
                    {{{ $product['quantity'] }}}
                    @if($product['quantity'] <= 10)
                        <strong style="color: red;">LOW</strong>
                    @endif
                </td>
                <td>{{{ $product['supplier_name'] ?? '-' }}}</td>
                <td>
                    <a href="<?= url('/products/edit') ?>?id={{ $product['id'] }}">Edit</a> |
                    <a href="#" onclick="deleteProduct({{ $product['id'] }}); return false;">Delete</a>
                </td>
            </tr>
        @endforeach

    </table>
</div> 


@include('layouts.footer')