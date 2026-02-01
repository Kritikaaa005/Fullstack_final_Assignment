document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('.auth-form');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        showLoginError(data.message);
                    }
                })
                .catch(error => {
                    console.error('Login error:', error);
                    showLoginError('An error occurred during login');
                });
        });
    }
});

function showLoginError(message) {
    const existingError = document.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }

    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `
        <i class="error-icon">⚠</i>
        <span>${message}</span>
    `;

    const authHeader = document.querySelector('.auth-header');
    authHeader.insertAdjacentElement('afterend', errorDiv);
}