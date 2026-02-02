@include('layouts.header', ['pageTitle' => 'Product Search'])

<div id="main-content">
    <h2>Advanced Product Search</h2>

    <!-- SEARCH FORM -->
    <form id="searchForm" method="post" action="<?= url('/products/search') ?>">
        <label><strong>Supplier:</strong></label><br>
        <select name="supplier_id" style="width: 300px;">
            <option value="">All Suppliers</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier['id'] }}" @if(isset($criteria['supplier_id']) && $criteria['supplier_id'] == $supplier['id']) selected @endif>
                    {{{ $supplier['name'] }}}
                </option>
            @endforeach
        </select>
        <br><br>

        <label><strong>Price Range:</strong></label><br>
        Min: <input name="min_price" type="number" step="0.01" placeholder="Min Price"
            value="{{{ $criteria['min_price'] ?? '' }}}" style="width: 120px;">
        &nbsp;&nbsp;
        Max: <input name="max_price" type="number" step="0.01" placeholder="Max Price"
            value="{{{ $criteria['max_price'] ?? '' }}}" style="width: 120px;">
        <br><br>

        <label><strong>Low Stock Alert (show items with quantity ≤):</strong></label><br>
        <input name="low_stock" type="number" placeholder="e.g., 10" value="{{{ $criteria['low_stock'] ?? '' }}}"
            style="width: 150px;">
        <br><br>

        <button type="submit">Search</button>
        <button type="button"
            onclick="if (typeof loadPage === 'function') { loadPage('<?= url('/products/search') ?>'); } else { window.location.href = '<?= url('/products/search') ?>'; }">Clear</button>
        <button type="button"
            onclick="if (typeof loadPage === 'function') { loadPage('<?= url('/products') ?>'); } else { window.location.href = '<?= url('/products') ?>'; }">View
            All Products</button>
    </form>

    <br>

    <!-- SEARCH RESULTS -->
    @if(isset($products))
        <h3>Search Results: {{ count($products) }} product(s) found</h3>

        @if(empty($products))
            <p><em>No products found matching your search criteria.</em></p>
        @else
            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr style="background-color: #f0f0f0;">
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Supplier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($products as $product)
                        <tr @if($product['quantity'] <= 10) style="background-color: #ffcccc;" @endif>
                            <td>{{{ $product['name'] }}}</td>
                            <td>{{ number_format($product['price'], 2) }}</td>
                            <td>
                                {{{ $product['quantity'] }}}
                                @if($product['quantity'] <= 10)
                                    <strong style="color: red;"> LOW STOCK</strong>
                                @endif
                            </td>
                            <td>{{{ $product['supplier_name'] ?? 'N/A' }}}</td>
                            <td>
                                <a href="<?= url('/products/edit') ?>?id={{ $product['id'] }}">Edit</a> |
                                <a href="#" onclick="deleteProduct({{ $product['id'] }}); return false;">Delete</a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @endif
    @endif
</div>

<script>
    // Initialize search form AJAX
    document.getElementById('searchForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('<?= url('/products/search') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#main-content') || doc.querySelector('body');

                if (newContent) {
                    document.querySelector('#main-content').innerHTML = newContent.innerHTML;

                    // Re-initialize product functionality
                    if (typeof initProductsPage === 'function') {
                        initProductsPage();
                    }
                }
            })
            .catch(error => {
                console.error('Search error:', error);
            });
    });
</script>

@include('layouts.footer')