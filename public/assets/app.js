document.addEventListener('submit', function (e) {
    if (!e.target) return;

    if (e.target.id === 'addSupplierForm') {
        e.preventDefault();
        handleSupplierSubmit(e.target);
    }

    if (e.target.id === 'addProductForm') {
        e.preventDefault();
        handleProductSubmit(e.target);
    }
});

function handleSupplierSubmit(form) {
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                addSupplierRow(data.supplier);
                form.reset();
                if (typeof showMessage === 'function') {
                    showMessage('Supplier added successfully!', 'success');
                }
            } else {
                if (typeof showMessage === 'function') {
                    showMessage(data.message || 'Error adding supplier', 'error');
                }
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof showMessage === 'function') {
                showMessage('Network error', 'error');
            }
        });
}

function addSupplierRow(supplier) {
    const table = document.getElementById('suppliersTable');
    if (!table) return;

    const row = table.insertRow(1);
    const escapedName = typeof escapeHtml === 'function' ? escapeHtml(supplier.name) : supplier.name;
    const escapedContact = typeof escapeHtml === 'function' ? escapeHtml(supplier.contact || '-') : (supplier.contact || '-');

    row.innerHTML = `
        <td>${escapedName}</td>
        <td>${escapedContact}</td>
        <td>
            <a href="/suppliers/edit?id=${supplier.id}">Edit</a> |
            <a href="#" onclick="deleteSupplier(${supplier.id}); return false;">Delete</a>
        </td>
    `;
}

function deleteSupplier(id) {
    if (typeof window.showConfirmModal !== 'function') {
        if (!confirm('Are you sure you want to delete this supplier?')) return;
        executeSupplierDelete(id);
        return;
    }

    window.showConfirmModal({
        title: 'Delete Supplier',
        text: 'Are you sure you want to remove this supplier? All linked products will remain but have no supplier.',
        confirmText: 'Confirm Delete',
        onConfirm: () => executeSupplierDelete(id)
    });
}

function executeSupplierDelete(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch(window.APP_BASE_PATH + '/suppliers/delete', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const link = document.querySelector(`a[onclick*="deleteSupplier(${id})"]`);
                if (link) {
                    const row = link.closest('tr');
                    row.classList.add('row-deleting');
                    setTimeout(() => row.remove(), 500);
                }
                if (typeof showMessage === 'function') {
                    showMessage('Supplier deleted successfully!', 'success');
                }
            } else {
                if (typeof showMessage === 'function') {
                    showMessage(data.message || 'Error deleting supplier', 'error');
                }
            }
        })
        .catch(() => {
            if (typeof showMessage === 'function') {
                showMessage('Network error', 'error');
            }
        });
}

function handleProductSubmit(form) {
    const formData = new FormData(form);

    fetch(window.APP_BASE_PATH + '/products/create', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const table = document.getElementById('productsTable');
                if (table) {
                    const newRow = table.insertRow(1);

                    const bgColor = data.product.quantity <= 10 ? '#ffcccc' : '';
                    if (bgColor) newRow.style.backgroundColor = bgColor;

                    const lowTag = data.product.quantity <= 10 ? '<strong style="color: red;">LOW</strong>' : '';

                    newRow.innerHTML = `
                    <td>${escapeHtml(data.product.name)}</td>
                    <td>${escapeHtml(data.product.price)}</td>
                    <td>${escapeHtml(data.product.quantity)} ${lowTag}</td>
                    <td>${escapeHtml(data.product.supplier_name || '-')}</td>
                    <td>
                        <a href="/products/edit?id=${data.product.id}">Edit</a> | 
                        <a href="#" onclick="deleteProduct(${data.product.id}); return false;">Delete</a>
                    </td>
                `;
                }

                form.reset();
                if (typeof showMessage === 'function') {
                    showMessage('Product added successfully!', 'success');
                }
                if (typeof window.checkLowStock === 'function') {
                    window.checkLowStock();
                }
            } else {
                if (typeof showMessage === 'function') {
                    showMessage(data.message || 'Error adding product', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showMessage === 'function') {
                showMessage('An error occurred', 'error');
            }
        });
}

function deleteProduct(id) {
    if (typeof window.showConfirmModal !== 'function') {
        if (!confirm('Delete this product?')) return;
        executeDelete(id);
        return;
    }

    window.showConfirmModal({
        title: 'Delete Product',
        text: 'Are you sure you want to remove this product from inventory?',
        confirmText: 'Delete Now',
        onConfirm: () => executeDelete(id)
    });
}

function executeDelete(id) {
    const formData = new FormData();
    formData.append('id', id);

    fetch(window.APP_BASE_PATH + `/products/delete`, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const link = document.querySelector(`a[onclick*="deleteProduct(${id})"]`);
                if (link) {
                    const row = link.closest('tr');
                    row.classList.add('row-deleting');
                    setTimeout(() => row.remove(), 500);
                }

                if (typeof showMessage === 'function') {
                    showMessage('Product deleted successfully!', 'success');
                }
                if (typeof window.checkLowStock === 'function') {
                    window.checkLowStock();
                }
            } else {
                if (typeof showMessage === 'function') {
                    showMessage(data.message || 'Error deleting product', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showMessage === 'function') {
                showMessage('An error occurred', 'error');
            }
        });
}

function initApp() {
    if (document.getElementById('auto-low-stock-alert')) {
        if (typeof window.checkLowStock === 'function') {
            window.checkLowStock();
            if (!window.lowStockInterval) {
                window.lowStockInterval = setInterval(window.checkLowStock, 30000);
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', initApp);

window.initApp = initApp;
