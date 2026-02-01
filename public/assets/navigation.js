(function () {
    'use strict';

    function setupNavigation() {
        const navLinks = document.querySelectorAll('nav a');

        navLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const url = this.getAttribute('href');

                if (url.includes('logout')) {
                    handleLogout();
                } else {
                    loadPage(url);
                }
            });
        });
    }

    function handleLogout() {
        fetch(window.APP_BASE_PATH + '/logout', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                window.location.href = window.APP_BASE_PATH + '/logout';
            });
    }

    window.loadPage = function loadPage(url) {
        document.body.style.transition = 'opacity 0.3s';
        document.body.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(function (html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newBody = doc.querySelector('body');
                if (!newBody) {
                    throw new Error('Invalid HTML response');
                }

                document.body.innerHTML = newBody.innerHTML;
                document.body.style.opacity = '1';

                history.pushState({ url: url }, '', url);

                const scripts = document.body.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    if (oldScript.src && (
                        oldScript.src.indexOf('navigation.js') !== -1 ||
                        oldScript.src.indexOf('app.js') !== -1
                    )) {
                        return;
                    }

                    const newScript = document.createElement('script');

                    Array.from(oldScript.attributes).forEach(attr => {
                        newScript.setAttribute(attr.name, attr.value);
                    });

                    if (oldScript.src) {
                        newScript.src = oldScript.src;
                        newScript.async = false;
                    } else {
                        newScript.textContent = oldScript.textContent;
                    }

                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });

                setupNavigation();

                const path = new URL(url, window.location.origin).pathname;

                if (typeof window.initApp === 'function') {
                    window.initApp();
                }

                if (path.includes('/suppliers') && typeof window.initSuppliersPage === 'function') {
                    window.initSuppliersPage();
                }

                if ((path === '/' || path.includes('/products')) && typeof window.initProductsPage === 'function') {
                    window.initProductsPage();
                }

                if (document.getElementById('auto-low-stock-alert') && typeof window.checkLowStock === 'function') {
                    window.checkLowStock();
                }
            })
            .catch(function (error) {
                console.error('AJAX navigation failed:', error);
                document.body.style.opacity = '1';
            });
    }

    window.addEventListener('popstate', function (event) {
        if (event.state && event.state.url) {
            loadPage(event.state.url);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        setupNavigation();
    });

    window.showMessage = function (message, type) {
        const msgArea = document.getElementById('message-area');
        if (!msgArea) return;

        const color = type === 'success' ? 'green' : 'red';
        msgArea.innerHTML = `<p style="color: ${color}; font-weight: bold;">${message}</p>`;

        setTimeout(() => {
            msgArea.innerHTML = '';
        }, 3000);
    };

    window.escapeHtml = function (text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    };

    window.showConfirmModal = function (options) {
        const { title, text, confirmText, onConfirm } = options;

        const modalHtml = `
            <div class="modal-overlay" id="confirmModal">
                <div class="modal-content">
                    <div class="modal-icon">🗑</div>
                    <div class="modal-title">${title || 'Are you sure?'}</div>
                    <div class="modal-text">${text || 'This action cannot be undone.'}</div>
                    <div class="modal-actions">
                        <button class="btn-cancel" id="modalCancel">Cancel</button>
                        <button class="btn-confirm" id="modalConfirm">${confirmText || 'Delete'}</button>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const modal = document.getElementById('confirmModal');

        setTimeout(() => modal.classList.add('active'), 10);

        const cleanup = () => {
            modal.classList.remove('active');
            setTimeout(() => modal.remove(), 300);
        };

        document.getElementById('modalCancel').onclick = cleanup;
        document.getElementById('modalConfirm').onclick = () => {
            onConfirm();
            cleanup();
        };

        modal.onclick = (e) => {
            if (e.target === modal) cleanup();
        };
    };

    window.checkLowStock = function () {
        const alertDiv = document.getElementById('auto-low-stock-alert');
        if (!alertDiv) return;

        fetch(window.APP_BASE_PATH + '/api/low-stock')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.count > 0) {
                    let alertHTML = `
                        <div style="background-color: #fff3cd; border: 2px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
                            <strong style="color: #856404;">LOW STOCK ALERT!</strong>
                            <p style="margin: 5px 0;">
                                You have <strong>${data.count}</strong> product(s) with low stock (≤10 units):
                            </p>
                            <ul style="margin: 5px 0;">
                    `;

                    data.products.forEach(product => {
                        const name = window.escapeHtml(String(product.name));
                        alertHTML += `
                            <li>
                                <strong>${name}</strong>
                                - Only ${product.quantity} left in stock
                                [<a href="#" onclick="
    if (typeof loadPage === 'function') {
        loadPage(window.APP_BASE_PATH + '/products/edit?id=${product.id}');
    } else {
        window.location.href = window.APP_BASE_PATH + '/products/edit?id=${product.id}';
    }
    return false;
">Restock</a>]

                            </li>
                        `;
                    });

                    alertHTML += `
                            </ul>
                            <small style="color: #666;">Last checked: ${new Date().toLocaleTimeString()}</small>
                        </div>
                    `;

                    alertDiv.innerHTML = alertHTML;
                } else if (data.success && data.count === 0) {
                    alertDiv.innerHTML = '<p style="color: green;">All products have nice stock levels!</p>';
                } else {
                    alertDiv.innerHTML = '<p style="color: red;">Error checking stock levels</p>';
                }
            })
            .catch(err => {
                console.error('Error:', err);
            });
    };

})();
