@include('layouts.header', ['pageTitle' => 'Edit Product'])

<div id="main-content">
    <h2>Edit Product</h2>

    <!-- Success/Error Message Area -->
    <div id="message-area"></div>

    <form id="editProductForm" method="post" action="<?= url('/products/edit') ?>?id={{ $product['id'] }}">
        <input type="hidden" name="id" value="{{ $product['id'] }}">

        <label>Name:</label><br>
        <input name="name" value="{{{ $product['name'] }}}" required style="width: 300px;"><br><br>

        <label>Price:</label><br>
        <input name="price" type="number" step="0.01" value="{{{ $product['price'] }}}" required
            style="width: 300px;"><br><br>

        <label>Quantity:</label><br>
        <input name="quantity" type="number" value="{{{ $product['quantity'] }}}" required
            style="width: 300px;"><br><br>

        <label>Supplier:</label><br>
        <select name="supplier_id" style="width: 300px;">
            <option value="">Select Supplier</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier['id'] }}" {{ $supplier['id'] == $product['supplier_id'] ? 'selected' : '' }}>
                    {{{ $supplier['name'] }}}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Update Product</button>
        <button type="button"
            onclick="if (typeof loadPage === 'function') { loadPage('<?= url('/products') ?>'); } else { window.location.href = '<?= url('/products') ?>'; }">Cancel</button>
    </form>
</div>

<script>
    document.getElementById('editProductForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const productId = formData.get('id');

        fetch(`<?= url('/products/edit') ?>?id=${productId}`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showMessage === 'function') {
                        showMessage('Product updated successfully!', 'success');
                    }

                    // Refetch low stock info immediately
                    if (typeof window.checkLowStock === 'function') {
                        window.checkLowStock();
                    }

                    // Navigate back to products list after 1 second using AJAX
                    setTimeout(() => {
                        if (typeof loadPage === 'function') {
                            loadPage('<?= url('/products') ?>');
                        } else {
                            window.location.href = '<?= url('/products') ?>';
                        }
                    }, 1000);
                } else {
                    if (typeof showMessage === 'function') {
                        showMessage(data.message || 'Error updating product', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof showMessage === 'function') {
                    showMessage('An error occurred', 'error');
                }
            });
    });
</script>

@include('layouts.footer')