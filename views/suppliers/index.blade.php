@include('layouts.header', ['pageTitle' => 'Suppliers'])

<div id="main-content">
    <h2>Suppliers</h2>

    <!-- Success/Error Message Area -->
    <div id="message-area"></div>

    <!-- ADD FORM -->
    <form id="addSupplierForm" method="post" action="<?= url('/suppliers/create') ?>">
        <input name="name" placeholder="Supplier Name" required>
        <input 
    name="contact"
    placeholder="Contact Info"
    pattern="^9[0-9]{9}$"
    title="Enter a 10-digit phone number starting with 9"
    required
>
        <button>Add Supplier</button>
    </form>

    <hr>

    <!-- SUPPLIERS TABLE -->
    <table border="1" cellpadding="5" id="suppliersTable">
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Action</th>
        </tr>

        @foreach($suppliers as $supplier)
            <tr>
                <td>{{{ $supplier['name'] }}}</td>
                <td>{{{ $supplier['contact'] ?? '-' }}}</td>
                <td>
                    <a href="<?= url('/suppliers/edit') ?>?id={{ $supplier['id'] }}">Edit</a> |
                    <a href="#" onclick="deleteSupplier({{ $supplier['id'] }}); return false;">Delete</a>
                </td>
            </tr>
        @endforeach

    </table>
</div>

<!-- External JS Files -->


@include('layouts.footer')