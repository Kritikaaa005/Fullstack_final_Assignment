@include('layouts.header', ['pageTitle' => 'Edit Supplier'])

<div id="main-content">
    <h2>Edit Supplier</h2>

    <!-- Success/Error Message Area -->
    <div id="message-area"></div>

    <form id="editSupplierForm" method="post" action="<?= url('/suppliers/edit') ?>?id={{ $supplier['id'] }}">
        <input type="hidden" name="id" value="{{ $supplier['id'] }}">

        <label>Name:</label><br>
        <input name="name" value="{{{ $supplier['name'] }}}" required style="width: 300px;"><br><br>

        <label>Contact:</label><br>
        <input 
    name="contact"
    value="{{{ $supplier['contact'] ?? '' }}}"
    pattern="^9[0-9]{9}$"
    title="Enter a 10-digit phone number starting with 9"
    style="width: 300px;"
>
<br><br>

        <button type="submit">Update Supplier</button>
        <button type="button"
            onclick="if (typeof loadPage === 'function') { loadPage('<?= url('/suppliers') ?>'); } else { window.location.href = '<?= url('/suppliers') ?>'; }">Cancel</button>
    </form>
</div>

<script>
    document.getElementById('editSupplierForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const supplierId = formData.get('id');

        fetch(`<?= url('/suppliers/edit') ?>?id=${supplierId}`, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (typeof showMessage === 'function') {
                        showMessage('Supplier updated successfully!', 'success');
                    }

                    // Navigate back to suppliers list after 1 second using AJAX
                    setTimeout(() => {
                        if (typeof loadPage === 'function') {
                            loadPage('<?= url('/suppliers') ?>');
                        } else {
                            window.location.href = '<?= url('/suppliers') ?>';
                        }
                    }, 1000);
                } else {
                    if (typeof showMessage === 'function') {
                        showMessage(data.message || 'Error updating supplier', 'error');
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