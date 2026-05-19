document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.login-form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    function clearErrors() {
        emailError.textContent = '';
        passwordError.textContent = '';
        emailInput.classList.remove('error-input');
        passwordInput.classList.remove('error-input');
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        if (email === '') {
            emailError.textContent = 'Email address is required.';
            emailInput.classList.add('error-input');
            return false;
        }
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            emailError.textContent = 'Please enter a valid email address (e.g., name@domain.com).';
            emailInput.classList.add('error-input');
            return false;
        }
        return true;
    }

    function validatePassword() {
        const password = passwordInput.value.trim();
        if (password === '') {
            passwordError.textContent = 'Password is required.';
            passwordInput.classList.add('error-input');
            return false;
        }
        return true;
    }

    emailInput.addEventListener('input', function() {
        emailError.textContent = '';
        emailInput.classList.remove('error-input');
    });

    passwordInput.addEventListener('input', function() {
        passwordError.textContent = '';
        passwordInput.classList.remove('error-input');
    });

    form.addEventListener('submit', function(event) {
        clearErrors();

        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();

        if (!isEmailValid || !isPasswordValid) {
            event.preventDefault();
            if (!isEmailValid) {
                emailInput.focus();
            } else if (!isPasswordValid) {
                passwordInput.focus();
            }
        }
    });
});